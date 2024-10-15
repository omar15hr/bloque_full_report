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

        // Definir el contenido
        $this->content = new stdClass();

        $this->content->text = $OUTPUT->render_from_template('block_full_report/content', []);

        // Incluir el script JS
        $PAGE->requires->js('/blocks/full_report/scripts.js');

        return $this->content;
    }

    public function applicable_formats() {
        return ['admin' => false, 'site-index' => true, 'course-view' => true, 'mod' => false, 'my' => true];
    }
}

 
