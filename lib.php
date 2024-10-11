<?php

function obtener_usuarios() {
  global $DB;

  // Establecer el rango de fechas
  $start_date = mktime(0, 0, 0, $month, 1, $year); // Primer día del mes
  $end_date = mktime(0, 0, 0, $month + 1, 1, $year) - 1; // Último día del mes

  // Consulta para obtener Id, RUT, Nombres, Apellido y Correo de los usuarios
  $sql = "SELECT u.id, 
                   u.username AS rut, 
                   u.firstname AS nombres, 
                   u.lastname AS apellido, 
                   u.email,
                   MAX(ula.timeaccess) AS acceso
            FROM {user} u
            LEFT JOIN {user_lastaccess} ula ON u.id = ula.userid
            GROUP BY u.id, u.username, u.firstname, u.lastname, u.email
            ORDER BY u.lastname, u.firstname";

  $usuarios = $DB->get_records_sql($sql);

  return $usuarios;
}

// Función para generar la tabla
function generar_primera_tabla($data) {
  // Crear tabla
  $table = new html_table();
  $table->head = ['Id', 'RUT', 'Nombres', 'Apellido', 'Correo', 'Último Acceso'];

  /// Agregar datos de la consulta a la tabla
  foreach ($data as $usuario) {
    $row = new html_table_row();
    $row->cells = [
        $usuario->id,
        $usuario->rut,
        $usuario->nombres,
        $usuario->apellido,
        $usuario->email,
        $usuario->acceso ? userdate($usuario->acceso) : 'Nunca'
    ];
    $table->data[] = $row;
  }

  // Renderizar la tabla
  $table_html = html_writer::table($table);

  // Estilos para el contenedor de la tabla
  $scroll_div = html_writer::start_div('table-container', ['style' => 'overflow: auto; max-height: 400px; border: 1px solid #ddd;']);
  $scroll_div .= $table_html;
  $scroll_div .= html_writer::end_div();

  return $scroll_div;
}


function crear_primer_div($years, $months) {
  // Atributos del div
  $divattributes = ['style' => 'text-align: center; background-color: #f2f2f2; padding: 20px;'];

  // Crear el div
  $div = html_writer::start_div('', $divattributes);

  // Crear el select de años
  $select1attributes = ['style' => 'margin-right: 5px;'];
  $div .= html_writer::select($years, 'yearSelect', null, 'Seleccione el año', $select1attributes);

  // Crear el select de meses
  $div .= html_writer::select($months, 'monthSelect', null, 'Seleccione el mes', $select1attributes);

  // Crear el select de cursos vacío
  $div .= html_writer::select([], 'courseSelect', null, 'Seleccione el curso', ['id' => 'courseSelect']);

  // Cerrar el div
  $div .= html_writer::end_div();

  return $div;
}

function obtener_cursos() {
  global $DB;

  // Consulta para obtener ID del curso, nombre del curso y el número de matriculados
  $sql = "SELECT 
              c.id AS courseid,
              c.fullname AS course_name,
              c.startdate AS start_date,
              COUNT(ue.userid) AS enrolled_count
          FROM 
              mdl_course c
          LEFT JOIN 
              mdl_enrol e ON e.courseid = c.id
          LEFT JOIN 
              mdl_user_enrolments ue ON ue.enrolid = e.id
          GROUP BY 
              c.id, c.fullname, c.startdate
          ORDER BY 
              c.fullname;
          ";

  $cursos = $DB->get_records_sql($sql);

  return $cursos;
}


function crear_segundo_div($years, $months) {
  // Atributos del div
  $divattributes = ['style' => 'text-align: center; background-color: #f2f2f2; padding: 20px;'];

  // Crear el div
  $div = html_writer::start_div('', $divattributes);

  // Crear el select de años
  $select1attributes = ['style' => 'margin-right: 5px;'];
  $div .= html_writer::select($years, 'yearSelect', null, 'Seleccione el año', $select1attributes);

  // Crear el select de meses
  $div .= html_writer::select($months, 'monthSelect', null, 'Seleccione el mes', $select1attributes);

  // Cerrar el div
  $div .= html_writer::end_div();

  return $div;
}

function generar_segunda_tabla($data) {
  // Crear tabla
  $table = new html_table();
  $table->head = ['Id', 'Cursos', 'Ejecución', 'Matriculados'];

  /// Agregar datos de la consulta a la tabla
  foreach ($data as $curso) {
    $row = new html_table_row();
    $row->cells = [
        $curso->courseid,
        $curso->course_name,
        userdate($curso->start_date),
        $curso->enrolled_count
    ];
    $table->data[] = $row;
  }

  // Renderizar la tabla
  $table_html = html_writer::table($table);

  // Estilos para el contenedor de la tabla
  $scroll_div = html_writer::start_div('table-container', ['style' => 'overflow: auto; max-height: 400px; border: 1px solid #ddd;']);
  $scroll_div .= $table_html;
  $scroll_div .= html_writer::end_div();

  return $scroll_div;
}