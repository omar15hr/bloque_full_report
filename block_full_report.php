<?php

class block_full_report extends block_base {
    public function init() {
        $this->title = get_string('pluginname', 'block_full_report');
    }

    public function get_content() {
        global $OUTPUT, $PAGE;

        if ($this->content !== null) {
            return $this->content;
        }

        // Incluir el CSS
        $PAGE->requires->css('/blocks/full_report/styles.css');

        // Incluir el script JS
        $PAGE->requires->js('/blocks/full_report/scripts.js');
        $PAGE->requires->js('/blocks/full_report/scripts-courses.js');
        $PAGE->requires->js('/blocks/full_report/scripts-search-student.js');

        // Definir el contenido
        $this->content = new stdClass();

        $this->content->text .= '<div class="rut-search">Buscador por RUT</div>';
        $this->content->text .= $OUTPUT->render_from_template('block_full_report/search', []);

        $this->content->text .= '<h3 class="resultados-titulo">Resultados por usuarios inscritos</h3>'; 
        $this->content->text .= $OUTPUT->render_from_template('block_full_report/content', []);

        $this->content->text .= '<br>';

        $this->content->text .= '<h3 class="resultados-titulo2">Resultados totales cursos por ejecución</h3>'; 
        $this->content->text .= $OUTPUT->render_from_template('block_full_report/courses', []);

        return $this->content;
    }

    public function applicable_formats() {
        return ['admin' => false, 'site-index' => true, 'course-view' => true, 'mod' => false, 'my' => true];
    }
}

 
