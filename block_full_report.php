<?php
// This file is part of Moodle - http://moodle.org/
// Moodle is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

/**
 * Block definition class for the block_full_report plugin.
 *
 * @package   block_full_report
 * @copyright Year, Your Name <your@email.address>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Incluir el archivo de funciones
require_once(__DIR__ . '\lib.php');

class block_full_report extends block_base
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
        
        // Obtener el mes seleccionado para usar en la tabla
        $selected_month = optional_param('month_select', '01', PARAM_TEXT);
        // Generar la tabla de cursos
        $courses_table_html = create_courses_table($DB, $selected_month);

        $second_select_html = create_second_select();
        $this->content->text = create_form($DB) . $courses_table_html;


        // Usar las funciones del archivo separado
        $this->content->footer = create_form($DB) . create_table($DB);

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
