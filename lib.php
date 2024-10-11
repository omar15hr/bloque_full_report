<?php

// Función para generar la tabla
function generar_primera_tabla($data) {
    $tableattributes = ['class' => 'generaltable', 'style' => 'width: 100%; text-align: center; margin-top: 20px;'];

    $header = html_writer::start_tag('thead');
    $header .= html_writer::start_tag('tr');
    $header .= html_writer::tag('th', 'Id', ['class' => 'header']);
    $header .= html_writer::tag('th', 'RUT', ['class' => 'header']);
    $header .= html_writer::tag('th', 'Nombres', ['class' => 'header']);
    $header .= html_writer::tag('th', 'App', ['class' => 'header']);
    $header .= html_writer::tag('th', 'Apm', ['class' => 'header']);
    $header .= html_writer::tag('th', 'E-mail', ['class' => 'header']);
    $header .= html_writer::tag('th', 'Servicio', ['class' => 'header']);
    $header .= html_writer::tag('th', 'Dependencia', ['class' => 'header']);
    $header .= html_writer::tag('th', 'Curso', ['class' => 'header']);
    $header .= html_writer::tag('th', 'Establecimiento', ['class' => 'header']);
    $header .= html_writer::tag('th', 'Ley', ['class' => 'header']);
    $header .= html_writer::tag('th', 'Area Trabajo', ['class' => 'header']);
    $header .= html_writer::tag('th', 'Cargo', ['class' => 'header']);
    $header .= html_writer::tag('th', 'Último Acceso', ['class' => 'header']);
    $header .= html_writer::tag('th', 'Estado', ['class' => 'header']);
    $header .= html_writer::tag('th', 'Nota', ['class' => 'header']);
    $header .= html_writer::end_tag('tr');
    $header .= html_writer::end_tag('thead');

    $body = html_writer::start_tag('tbody');
    foreach ($data as $fila) {
        $body .= html_writer::start_tag('tr');
        foreach ($fila as $celda) {
            $body .= html_writer::tag('td', $celda);
        }
        $body .= html_writer::end_tag('tr');
    }
    $body .= html_writer::end_tag('tbody');

    $table = html_writer::start_tag('table', $tableattributes);
    $table .= $header;
    $table .= $body;
    $table .= html_writer::end_tag('table');

    /// Crear un div contenedor para el scrollbar horizontal y vertical
    $scrollDiv = html_writer::start_div('table-container', [
      'style' => 'overflow-x: auto; overflow-y: auto; max-height: 400px;'
  ]);
  $scrollDiv .= $table;
  $scrollDiv .= html_writer::end_div();

  return $scrollDiv;
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
  $tableattributes = ['class' => 'generaltable', 'style' => 'width: 100%; text-align: center; margin-top: 20px;'];

  // Crear los encabezados de la tabla
  $header = html_writer::start_tag('thead');
  $header .= html_writer::start_tag('tr');
  $header .= html_writer::tag('th', '#', ['class' => 'header']);
  $header .= html_writer::tag('th', 'Cursos', ['class' => 'header']);
  $header .= html_writer::tag('th', 'Ejecución', ['class' => 'header']);
  $header .= html_writer::tag('th', 'Matriculados', ['class' => 'header']);
  $header .= html_writer::tag('th', 'Aprobados', ['class' => 'header']);
  $header .= html_writer::tag('th', 'Reprobados', ['class' => 'header']);
  $header .= html_writer::tag('th', 'Sin Acceso', ['class' => 'header']);
  $header .= html_writer::tag('th', 'Abandonos', ['class' => 'header']);
  $header .= html_writer::end_tag('tr');
  $header .= html_writer::end_tag('thead');

  // Crear el cuerpo de la tabla con los datos proporcionados
  $body = html_writer::start_tag('tbody');
  foreach ($data as $fila) {
      $body .= html_writer::start_tag('tr');
      foreach ($fila as $celda) {
          $body .= html_writer::tag('td', $celda);
      }
      $body .= html_writer::end_tag('tr');
  }
  $body .= html_writer::end_tag('tbody');

  // Generar la tabla
  $table = html_writer::start_tag('table', $tableattributes);
  $table .= $header;
  $table .= $body;
  $table .= html_writer::end_tag('table');

  // Crear un div contenedor para el scrollbar horizontal y vertical
  $scrollDiv = html_writer::start_div('table-container', [
      'style' => 'overflow-x: auto; overflow-y: auto; max-height: 400px;'
  ]);
  $scrollDiv .= $table;
  $scrollDiv .= html_writer::end_div();

  return $scrollDiv;
}