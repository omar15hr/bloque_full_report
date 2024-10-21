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
            COUNT(ue.userid) AS enrolled_count
        FROM {course} c
        JOIN {enrol} e ON e.courseid = c.id
        JOIN {user_enrolments} ue ON ue.enrolid = e.id
        WHERE c.startdate BETWEEN :startdate AND :enddate
        GROUP BY c.id, c.fullname, c.startdate";

    $params = [
        'startdate' => strtotime($startDate),
        'enddate' => strtotime($endDate)
    ];

    // Ejecutar la consulta
    $courses = $DB->get_records_sql($sql, $params);

    // Formatear la respuesta
    $response = "<h3>Cursos obtenidos correctamente:</h3><ul>";

    foreach ($courses as $course) {
        // Agregar detalles del curso
        $response .= "<li>ID: {$course->id}, Nombre: {$course->fullname}, Fecha de inicio: " . date('Y-m-d', $course->startdate) . 
                     ", Alumnos matriculados: {$course->enrolled_count}";

        // Obtener la lista de alumnos matriculados en el curso
        $studentSql = "
            SELECT u.id, u.firstname, u.lastname
            FROM {user} u
            JOIN {user_enrolments} ue ON ue.userid = u.id
            JOIN {enrol} e ON e.id = ue.enrolid
            WHERE e.courseid = :courseid";

        $studentParams = ['courseid' => $course->id];
        $students = $DB->get_records_sql($studentSql, $studentParams);

        // Agregar lista de alumnos debajo del curso
        if ($students) {
            $response .= "<ul>";
            foreach ($students as $student) {
                $response .= "<li>{$student->firstname} {$student->lastname}</li>";
            }
            $response .= "</ul>";
        } else {
            $response .= "<ul><li>No hay alumnos matriculados.</li></ul>";
        }

        $response .= "</li>"; // Cerrar el li del curso
    }
    $response .= "</ul>";

    header('Content-Type: text/html');
    echo $response;
    exit;
}
