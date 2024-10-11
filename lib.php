<?php

function create_form($DB)
{
    // Obtener mes seleccionado (por defecto enero)
    $selected_month = optional_param('month_select', '01', PARAM_TEXT);

    // Select de meses
    $months = [
        '01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo', '04' => 'Abril', 
        '05' => 'Mayo', '06' => 'Junio', '07' => 'Julio', '08' => 'Agosto', 
        '09' => 'Septiembre', '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre'
    ];

    // Obtener cursos filtrados por mes
    $course_options = get_courses_by_month($DB, $selected_month);

    // Obtener curso seleccionado
    $course_id = optional_param('course_select', null, PARAM_INT);

    // Crear select de meses y cursos
    return html_writer::start_tag('form', ['method' => 'GET', 'id' => 'filterForm']) .
        html_writer::label(get_string('select_month', 'block_full_report'), 'month_select') .
        html_writer::select($months, 'month_select', $selected_month, null, ['onchange' => 'this.form.submit()', 'class' => 'custom-select']) .
        html_writer::label(get_string('select_course', 'block_full_report'), 'course_select') .
        html_writer::select($course_options, 'course_select', $course_id, get_string('all_courses', 'block_full_report'), ['onchange' => 'this.form.submit()']) .
        html_writer::end_tag('form');
}

function get_courses_by_month($DB, $selected_month)
{
    $first_day_of_month = strtotime("2024-$selected_month-01");
    $last_day_of_month = strtotime("last day of", $first_day_of_month);

    $courses = $DB->get_records_sql(
        "SELECT id, fullname FROM {course} WHERE startdate >= ? AND startdate <= ?",
        [$first_day_of_month, $last_day_of_month]
    );

    $course_options = [];
    foreach ($courses as $course) {
        $course_options[$course->id] = $course->fullname;
    }

    return $course_options;
}

function create_table($DB)
{
    $course_id = optional_param('course_select', null, PARAM_INT);

    // Solo cargar usuarios si se selecciona un curso
    $usuarios = $course_id ? get_users_by_course($DB, $course_id) : [];

    // Definir encabezado de tabla
    $table = new html_table();
    $table->head = [
        get_string('id', 'block_full_report'),
        get_string('username', 'block_full_report'),
        get_string('nombre', 'block_full_report'), 
        get_string('email', 'block_full_report'),
        get_string('lastaccess', 'block_full_report'),
        get_string('nota', 'block_full_report')
    ];

    // Poblar la tabla con datos
    foreach ($usuarios as $usuario) {
        $table->data[] = [
            $usuario->id,
            $usuario->username,
            "{$usuario->firstname} {$usuario->lastname}",
            $usuario->email,
            $usuario->lastaccess ? userdate($usuario->lastaccess) : get_string('never'),
            'No calificado' // Placeholder
        ];
    }

    return html_writer::table($table);
}

function get_users_by_course($DB, $course_id)
{
    return $DB->get_records_sql(
        "SELECT u.id, u.username, u.firstname, u.lastname, u.email, u.lastaccess
        FROM {user} u
        JOIN {role_assignments} ra ON ra.userid = u.id
        JOIN {context} ctx ON ra.contextid = ctx.id
        WHERE ctx.instanceid = ? AND ctx.contextlevel = ?",
        [$course_id, CONTEXT_COURSE]
    );
}

// SEGUNDO SELECT

function create_second_select() {
    // Definir los meses nuevamente
    $months = [
        '01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo', '04' => 'Abril', 
        '05' => 'Mayo', '06' => 'Junio', '07' => 'Julio', '08' => 'Agosto', 
        '09' => 'Septiembre', '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre'
    ];

    // Obtener mes seleccionado (por defecto enero)
    $selected_month_2 = optional_param('month_select_2', '01', PARAM_TEXT);

    // Crear el segundo select para meses
    $second_select = html_writer::label(get_string('select_month_2', 'block_full_report'), 'month_select_2') .
                     html_writer::select($months, 'month_select_2', $selected_month_2, null, ['onchange' => 'this.form.submit()', 'class' => 'custom-select']);
    
    // Devolver el HTML del segundo select
    return $second_select;
}

function create_courses_table($DB, $selected_month) {
    $first_day_of_month = strtotime("2024-$selected_month-01");
    $last_day_of_month = strtotime("last day of", $first_day_of_month);
    
    $courses = $DB->get_records_sql(
        "SELECT id, fullname FROM {course} WHERE startdate >= ? AND startdate <= ?",
        [$first_day_of_month, $last_day_of_month]
    );

    // Definir encabezado de tabla
    $table = '<table class="generaltable">';
    $table .= '<thead><tr><th>ID</th><th>Nombre del Curso</th></tr></thead>';
    $table .= '<tbody>';

    // Poblar la tabla con datos
    foreach ($courses as $course) {
        $table .= '<tr>';
        $table .= '<td>' . $course->id . '</td>';
        $table .= '<td>' . $course->fullname . '</td>';
        $table .= '</tr>';
    }

    $table .= '</tbody></table>';
    return $table;
}

