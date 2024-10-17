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
        $sql = "SELECT u.id, u.username, u.firstname, u.lastname, c.fullname AS course_name, u.institution,
                    COALESCE(ROUND(gg.finalgrade, 2), 0) AS total_grade
                FROM {user} u
                JOIN {user_enrolments} ue ON ue.userid = u.id
                JOIN {enrol} e ON e.id = ue.enrolid
                JOIN {course} c ON c.id = e.courseid
                LEFT JOIN {grade_grades} gg ON gg.userid = u.id
                LEFT JOIN {grade_items} gi ON gi.id = gg.itemid
                WHERE e.courseid = :courseid
                GROUP BY u.id, u.username, u.firstname, u.lastname, c.fullname, u.institution;";

        $params = ['courseid' => $courseId];

        // Obtener usuarios
        $users = $DB->get_records_sql($sql, $params);

        // Formatear la respuesta
        $response = [
            'message' => 'Usuarios obtenidos correctamente.',
            'users' => [] // Inicializar el array de usuarios
        ];

        foreach ($users as $user) {
            // Lógica para determinar el estado del usuario
            $status = 'activo'; // Estado por defecto

            if ($user->total_grade == 0) {
                $status = 'sin acceso';
            } elseif ($user->total_grade === 0) { // Ajustar según tus condiciones
                $status = 'desiste';
            }

            $response['users'][] = [
                'id' => $user->id,
                'username' => $user->username,
                'name' => $user->firstname,
                'surname' => $user->lastname,
                'course_name' => $user->course_name,
                'institution' => $user->institution,
                'total_grade' => $user->total_grade,
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
