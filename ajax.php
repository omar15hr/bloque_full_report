<?php

require_once('../../config.php');
require_login();

if (!isloggedin() || isguestuser()) {
    die('Acceso denegado');
}

$mes = required_param('mes', PARAM_INT);   // El mes es un número (01 a 12)
$year = required_param('year', PARAM_INT); // El año es un número (ej. 2024)

// Aseguramos que la sesskey es válida para la solicitud
require_sesskey();

// Consulta para obtener los cursos que inician en el mes y año seleccionados, y también su fecha de inicio
global $DB;

$cursos = $DB->get_records_sql("
    SELECT id, fullname, startdate
    FROM {course}
    WHERE MONTH(FROM_UNIXTIME(startdate)) = ? AND YEAR(FROM_UNIXTIME(startdate)) = ?
", [$mes, $year]);

// Convertimos los cursos a JSON, incluyendo la fecha de inicio en formato legible
$result = [];
foreach ($cursos as $curso) {
    $result[] = [
        'id' => $curso->id,
        'fullname' => $curso->fullname,
        'startdate' => date('d/m/Y', $curso->startdate),  // Formato legible de la fecha de inicio
    ];
}

echo json_encode($result);
