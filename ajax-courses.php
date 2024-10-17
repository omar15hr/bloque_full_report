<?php
require_once(__DIR__ . '/../../config.php');
global $DB;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del formulario
    $month2 = isset($_POST['month2']) ? $_POST['month2'] : '';
    $year2 = isset($_POST['year2']) ? $_POST['year2'] : '';

    // Si no se ha enviado un `month2` o `year2`, manejar la solicitud
    if (empty($month2) || empty($year2)) {
        echo json_encode(['error' => 'Mes y año son requeridos.']);
        exit;
    }

    // Consultar cursos que se iniciaron en el mes y año especificados
    $startDate = "$year2-$month2-01"; // Primer día del mes
    $endDate = date("Y-m-t", strtotime($startDate)); // Último día del mes

    // Consulta SQL para obtener los cursos y la cantidad de alumnos matriculados
    $sql = "
        SELECT c.id, c.fullname, c.startdate,
            (SELECT COUNT(*) FROM {user_enrolments} ue JOIN {enrol} e ON e.id = ue.enrolid WHERE e.courseid = c.id) AS enrolled_count,
            (SELECT COUNT(*) FROM {user_enrolments} ue JOIN {enrol} e ON e.id = ue.enrolid 
             JOIN {user} u ON u.id = ue.userid WHERE e.courseid = c.id AND ue.status = 0) AS not_access_count,
            (SELECT COUNT(*) FROM {user_enrolments} ue JOIN {enrol} e ON e.id = ue.enrolid 
             JOIN {user} u ON u.id = ue.userid WHERE e.courseid = c.id AND ue.status = 1) AS active_count,
            (SELECT COUNT(*) FROM {user_enrolments} ue JOIN {enrol} e ON e.id = ue.enrolid 
             JOIN {user} u ON u.id = ue.userid WHERE e.courseid = c.id AND ue.status = 2) AS desist_count
        FROM {course} c
        WHERE c.startdate BETWEEN :startdate AND :enddate";
    
    $params = [
        'startdate' => strtotime($startDate),
        'enddate' => strtotime($endDate)
    ];

    // Ejecutar la consulta
    $courses = $DB->get_records_sql($sql, $params);

    // Formatear la respuesta
    $response = "<h3>Cursos obtenidos correctamente:</h3><ul>"; // Cambiar para imprimir directamente

    foreach ($courses as $course) {
        $response .= "<li>ID: {$course->id}, Nombre: {$course->fullname}, Fecha de inicio: " . date('Y-m-d', $course->startdate) . 
                     ", Alumnos matriculados: {$course->enrolled_count}, Sin acceso: {$course->not_access_count}, " . 
                     "Activos: {$course->active_count}, Desisten: {$course->desist_count}</li>";
    }
    $response .= "</ul>";

    header('Content-Type: text/html'); // Cambiar a text/html para HTML
    echo $response; // Imprimir HTML directamente
    exit;
}
