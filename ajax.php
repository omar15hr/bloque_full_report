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
        // Consulta SQL para obtener la información del curso
        $sql = "SELECT c.fullname, c.startdate, 
                       (SELECT COUNT(*) FROM {user_enrolments} ue 
                        JOIN {enrol} e ON ue.enrolid = e.id 
                        WHERE e.courseid = c.id) AS enrolled_count 
                FROM {course} c 
                WHERE c.id = :courseid";
        
        // Ejecutar la consulta
        $params = ['courseid' => $courseId];
        $course = $DB->get_record_sql($sql, $params);
    
        // Formatear la respuesta
        if ($course) {
            $response = [
                'course_name' => $course->fullname,
                'startdate' => date('Y-m-d', $course->startdate),
                'enrolled_count' => $course->enrolled_count, // Ahora obtienes el conteo directamente
            ];
        } else {
            $response = [
                'error' => 'Curso no encontrado.',
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
