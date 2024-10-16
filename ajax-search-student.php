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
        SELECT u.id, u.firstname, u.lastname, u.username, c.fullname AS course
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
        $response['users'][] = [
            'id' => $user->id,
            'name' => $user->firstname,
            'surname' => $user->lastname,
            'username' => $user->username,
            'course' => $user->course
        ];
    }

    // Enviar la respuesta en formato JSON
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
