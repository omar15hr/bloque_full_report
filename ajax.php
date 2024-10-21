<?php
require_once(__DIR__ . '/../../config.php');
global $DB;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del formulario
    $month = isset($_POST['month']) ? $_POST['month'] : '';
    $year = isset($_POST['year']) ? $_POST['year'] : '';
    $courseId = isset($_POST['courseId']) ? $_POST['courseId'] : ''; 

    // Si se solicita el ID de un curso, manejamos esa solicitud
    if (!empty($courseId)) {
        // Consulta SQL para obtener la información de los usuarios inscritos en el curso
        $sql = "
            SELECT u.id, u.username, u.firstname, u.lastname, u.email, c.fullname AS course_name, u.institution,
                   COUNT(gi.id) AS total_quiz_course, 
                   COUNT(CASE WHEN gg.finalgrade > 0 THEN 1 ELSE NULL END) AS total_quiz_user
            FROM {user} u
            JOIN {user_enrolments} ue ON ue.userid = u.id
            JOIN {enrol} e ON e.id = ue.enrolid
            JOIN {course} c ON c.id = e.courseid
            LEFT JOIN {grade_items} gi ON gi.courseid = c.id AND gi.itemtype = 'mod' AND gi.itemmodule = 'quiz'
            LEFT JOIN {grade_grades} gg ON gg.itemid = gi.id AND gg.userid = u.id
            WHERE e.courseid = :courseid
            GROUP BY u.id, u.username, u.firstname, u.lastname, u.email, c.fullname, u.institution
        ";

        $params = ['courseid' => $courseId];

        // Obtener usuarios
        $users = $DB->get_records_sql($sql, $params);

        // Formatear la respuesta
        $response = [
            'message' => 'Usuarios obtenidos correctamente.',
            'users' => [] // Inicializar el array de usuarios
        ];

        foreach ($users as $user) {
            // Calcular la diferencia entre los quiz totales y los del usuario
            $difference = $user->total_quiz_course - $user->total_quiz_user;

            // Lógica para determinar el estado del usuario
            if ($difference === 0) {
                // Si el alumno es activo, verificar si tiene un certificado
                $cert_sql = "
                    SELECT COUNT(*) 
                    FROM {customcert_issues} ci
                    JOIN {customcert} c ON ci.customcertid = c.id
                    WHERE ci.userid = :userid AND c.course = :courseid
                ";
                try {
                    $has_certificate = $DB->get_field_sql($cert_sql, ['userid' => $user->id, 'courseid' => $courseId]);
                    $status = ($has_certificate > 0) ? 'Aprobado' : 'Reprobado';
                } catch (Exception $e) {
                    error_log("Error en la consulta de certificado: " . $e->getMessage());
                    $status = 'Desconocido'; // Valor por defecto en caso de error
                }
            } elseif ($difference == $user->total_quiz_course) {
                $status = 'Sin acceso';
            } elseif ($difference > 0 && $difference < $user->total_quiz_course) {
                $status = 'Desiste';
            } else {
                $status = 'Sin acceso'; // Manejo por defecto
            }

            // Añadir usuario a la respuesta
            $response['users'][] = [
                'id' => $user->id,
                'username' => $user->username,
                'name' => $user->firstname,
                'surname' => $user->lastname,
                'email' => $user->email, // Añadir el email a la respuesta
                'course_name' => $user->course_name,
                'institution' => $user->institution,
                'status' => $status // Agregar estado a la respuesta
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    // Si no se ha enviado un `courseId`, manejamos la solicitud de cursos por mes y año
    if (empty($month) || empty($year)) {
        echo json_encode(['error' => 'Mes y año son requeridos.']);
        exit;
    }

    // Consultar cursos que se iniciaron en el mes y año especificados
    $startDate = "$year-$month-01"; // Primer día del mes
    $endDate = date("Y-m-t", strtotime($startDate)); // Último día del mes

    // Consulta SQL para los cursos
    $sql = "SELECT id, fullname FROM {course} WHERE startdate BETWEEN :startdate AND :enddate";
    $params = [
        'startdate' => strtotime($startDate),
        'enddate' => strtotime($endDate)
    ];

    // Ejecutar la consulta
    $courses = $DB->get_records_sql($sql, $params);

    // Formatear la respuesta
    $response = [
        'message' => 'Cursos obtenidos correctamente.',
        'courses' => [] // Inicializar el array de cursos
    ];

    foreach ($courses as $course) {
        $response['courses'][] = [
            'id' => $course->id,
            'name' => $course->fullname
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?>
