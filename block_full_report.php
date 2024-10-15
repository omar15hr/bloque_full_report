<?php

require_once(__DIR__ . '/lib.php');

 class block_full_report extends block_base
{
    public function init()
    {
        $this->title = get_string('pluginname', 'block_full_report');
    }

    public function get_content()
    {
        global $OUTPUT, $DB, $PAGE;

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass();
      
        // Crear el SEGUNDO div
        $this->content->text .= 'Hola';
        

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

 
