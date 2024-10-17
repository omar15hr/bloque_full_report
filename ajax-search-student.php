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

    // Consulta SQL para obtener el nombre, apellido, username y cursos en los que está matriculado
    $sql = "
        SELECT u.id, u.firstname, u.lastname, u.username, c.id AS courseid, c.fullname AS course, u.institution
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
        // Obtener todas las actividades evaluadas (tareas, cuestionarios, etc.) del curso
        $sql_activities = "
            SELECT gi.itemname AS activity_name, ROUND(gg.finalgrade, 2) AS activity_grade
            FROM {grade_items} gi
            JOIN {grade_grades} gg ON gi.id = gg.itemid
            WHERE gi.courseid = :courseid AND gi.itemtype != 'course' AND gg.userid = :userid
        ";
        $params_activities = [
            'courseid' => $user->courseid,
            'userid' => $user->id
        ];

        // Obtener las actividades y calificaciones
        $activities = $DB->get_records_sql($sql_activities, $params_activities);

        // Formatear las actividades en un arreglo
        $activity_list = [];
        foreach ($activities as $activity) {
            $activity_list[] = [
                'name' => $activity->activity_name,
                'grade' => $activity->activity_grade
            ];
        }

        // Calcular el estado basado en las notas
        $total_grades = array_column($activity_list, 'grade'); // Obtener solo las notas
        $all_zero = !array_filter($total_grades, fn($grade) => $grade > 0); // Verificar si todas son cero
        $has_passed = array_filter($total_grades, fn($grade) => $grade > 0); // Verificar si hay alguna nota diferente de cero
        $final_grade = $has_passed ? max($has_passed) : 0; // Obtener la nota máxima

        // Determinar el estado
        if ($all_zero) {
            $status = 'sin acceso';
        } elseif ($final_grade === 0 && !empty($has_passed)) {
            $status = 'desiste';
        } else {
            $status = 'activo'; // Puedes definir otro estado si lo necesitas
        }

        // Preparar la respuesta por usuario
        $response['users'][] = [
            'id' => $user->id,
            'name' => $user->firstname,
            'surname' => $user->lastname,
            'username' => $user->username,
            'course' => $user->course,
            'institution' => $user->institution,
            'activities' => $activity_list,
            'status' => $status // Añadir el estado a la respuesta
        ];
    }

    // Enviar la respuesta en formato JSON
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
