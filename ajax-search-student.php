<?php
require_once('../../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    global $DB;

    // Obtener el username desde la solicitud POST
    $username = isset($_POST['username']) ? $_POST['username'] : '';

    if (empty($username)) {
        echo json_encode(['error' => 'El nombre de usuario es requerido.']);
        exit;
    }

    // Consulta SQL para obtener el nombre, apellido, username, email y cursos en los que está matriculado
    $sql = "
        SELECT u.id, u.firstname, u.lastname, u.username, u.email, c.id AS courseid, c.fullname AS course, u.institution
        FROM {user} u
        JOIN {user_enrolments} ue ON ue.userid = u.id
        JOIN {enrol} e ON e.id = ue.enrolid
        JOIN {course} c ON c.id = e.courseid
        WHERE u.username = :username
    ";

    $params = ['username' => $username];

    // Ejecutar la consulta
    $users = $DB->get_records_sql($sql, $params);

    // Si no se encuentran usuarios, enviar un error
    if (empty($users)) {
        echo json_encode(['error' => 'No se encontró ningún usuario con ese username.']);
        exit;
    }

    // Preparar la respuesta
    $response = ['users' => []];
    foreach ($users as $user) {
        // Obtener el total de quiz del curso
        $sql_total_quiz = "
            SELECT COUNT(gi.id) AS total_quiz
            FROM {grade_items} gi
            WHERE gi.courseid = :courseid AND gi.itemmodule = 'quiz'
        ";
        $total_quiz = $DB->get_field_sql($sql_total_quiz, ['courseid' => $user->courseid]);

        // Obtener el total de quiz con nota > 0 para el usuario
        $sql_quiz_user = "
            SELECT COUNT(gg.id) AS total_grades
            FROM {grade_items} gi
            JOIN {grade_grades} gg ON gi.id = gg.itemid
            WHERE gi.courseid = :courseid AND gi.itemmodule = 'quiz' AND gg.userid = :userid AND gg.finalgrade > 0
        ";
        $total_grades = $DB->get_field_sql($sql_quiz_user, ['courseid' => $user->courseid, 'userid' => $user->id]);

        // Calcular la diferencia entre el total de quiz y los completados
        $difference = $total_quiz - $total_grades;

        // Determinar el estado
        if ($difference === 0) {
            // Si el alumno es activo, verificar si tiene un certificado
            $cert_sql = "
                SELECT 
                    CASE 
                        WHEN ci.id IS NOT NULL THEN 'Aprobado'  -- Tiene un certificado
                        ELSE 'Reprobado'                        -- No tiene un certificado
                    END AS status
                FROM 
                    {user} u
                JOIN 
                    {course} c ON c.fullname = 'Curso de Introducción a la Programación'  -- Filtrar por nombre del curso
                JOIN 
                    {enrol} e ON e.courseid = c.id  -- Enrolamiento en el curso
                JOIN 
                    {user_enrolments} ue ON ue.enrolid = e.id AND ue.userid = u.id  -- Usuarios inscritos en el curso
                LEFT JOIN 
                    {customcert_issues} ci ON u.id = ci.userid
                LEFT JOIN 
                    {customcert} cc ON ci.customcertid = cc.id AND cc.course = c.id  -- Asegurarse de que el certificado está vinculado al curso
                WHERE 
                    u.id = :userid AND u.deleted = 0  -- Asegúrate de incluir solo usuarios activos
            ";
        
            // Verificamos que la consulta SQL no falle
            try {
                // Ejecutar la consulta y obtener el estado
                $status = $DB->get_field_sql($cert_sql, ['userid' => $user->id]);
            } catch (Exception $e) {
                error_log("Error en la consulta de certificado: " . $e->getMessage());
                $status = 'Desconocido'; // Valor por defecto en caso de error
            }
        }
         elseif ($difference === $total_quiz) {
            $status = 'Sin acceso';
        } elseif ($difference > 0 && $difference < $total_quiz) {
            $status = 'Desiste';
        } else {
            $status = 'Sin acceso';
        }

        // Preparar la respuesta por usuario
        $response['users'][] = [
            'id' => $user->id,
            'name' => $user->firstname,
            'surname' => $user->lastname,
            'username' => $user->username,
            'email' => $user->email, // Añadir el email a la respuesta
            'course' => $user->course,
            'institution' => $user->institution,
            'status' => $status // Añadir el estado a la respuesta
        ];
    }

    // Enviar la respuesta en formato JSON
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?>
