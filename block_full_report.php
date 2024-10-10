<?php
// This file is part of Moodle - http://moodle.org/
// Moodle is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

/**
 * Block definition class for the block_full_report plugin.
 *
 * @package   block_full_report
 * @copyright Year, You Name <your@email.address>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 class block_full_report extends block_list
{
    public function init()
    {
        $this->title = get_string('pluginname', 'block_full_report');
    }

    public function get_content()
    {
        global $OUTPUT, $DB;

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass();

        // Crear un formulario con selects (Fecha y Curso)
        $output = '';

        // Select para meses
        $months = [
            '01' => 'Enero',
            '02' => 'Febrero',
            '03' => 'Marzo',
            '04' => 'Abril',
            '05' => 'Mayo',
            '06' => 'Junio',
            '07' => 'Julio',
            '08' => 'Agosto',
            '09' => 'Septiembre',
            '10' => 'Octubre',
            '11' => 'Noviembre',
            '12' => 'Diciembre'
        ];

        // Obtener el mes seleccionado (por defecto enero)
        $selected_month = optional_param('month_select', '01', PARAM_TEXT);

        // Crear el select para meses
        $output .= html_writer::start_tag('form', ['method' => 'GET', 'id' => 'filterForm']);
        $output .= html_writer::label(get_string('select_month', 'block_full_report'), 'month_select', ['class' => 'custom-label']);
        $output .= html_writer::select(
          $months, 'month_select', 
          $selected_month, get_string('select_month', 'block_full_report'), 
          ['onchange' => 'this.form.submit()', 'class' => 'custom-select']);

        // Filtrar los cursos por el mes seleccionado
        $first_day_of_month = strtotime("2024-$selected_month-01");
        $last_day_of_month = strtotime("last day of", $first_day_of_month);

        $cursos = $DB->get_records_sql(
            "SELECT id, fullname 
            FROM {course} 
            WHERE startdate >= ? AND startdate <= ?",
            array($first_day_of_month, $last_day_of_month)
        );

        // Crear el select para cursos
        $course_options = [];
        foreach ($cursos as $curso) {
            $course_options[$curso->id] = $curso->fullname;
        }

        // Obtener el curso seleccionado
        $course_id = optional_param('course_select', null, PARAM_INT);

        $output .= html_writer::label(get_string('select_course', 'block_full_report'), 'course_select');
        $output .= html_writer::select($course_options, 'course_select', $course_id, get_string('all_courses', 'block_full_report'), ['onchange' => 'this.form.submit()']);
        $output .= html_writer::end_tag('form');

        // Crear la tabla de usuarios
        $table = new html_table();
        $table->head = [
            get_string('id', 'block_full_report'),
            get_string('username', 'block_full_report'),
            get_string('nombre', 'block_full_report'), 
            get_string('email', 'block_full_report'),
            get_string('cursos', 'block_full_report'),
            get_string('lastaccess', 'block_full_report'),
            get_string('nota', 'block_full_report')
        ];

        // Obtener los usuarios inscritos en el curso seleccionado
        if ($course_id) {
            $usuarios = $DB->get_records_sql(
                "SELECT u.id, u.username, u.firstname, u.lastname, u.email, u.lastaccess
                FROM {user} u
                JOIN {role_assignments} ra ON ra.userid = u.id
                JOIN {context} ctx ON ra.contextid = ctx.id
                WHERE ctx.instanceid = ? AND ctx.contextlevel = ?",
                array($course_id, CONTEXT_COURSE)
            );
        } else {
            // Si no hay curso seleccionado, no mostrar usuarios
            $usuarios = [];
        }

        // Mostrar los usuarios en la tabla
        foreach ($usuarios as $usuario) {
            $row = new html_table_row();
            $row->cells = [
                $usuario->id,
                $usuario->username,
                $usuario->firstname . ' ' . $usuario->lastname,
                $usuario->email,
                $DB->get_field('course', 'fullname', ['id' => $course_id]),
                $usuario->lastaccess ? userdate($usuario->lastaccess) : get_string('never'),
                'No calificado' // Placeholder para las notas
            ];
            $table->data[] = $row;
        }

        // Mostrar la tabla y el formulario
        $this->content->footer = $output . html_writer::table($table);

        return $this->content;
    }

    public function applicable_formats()
    {
        return [
            'admin' => false,
            'site-index' => true,
            'course-view' => true,
            'mod' => false,
            'my' => true,
        ];
    }
}

 
