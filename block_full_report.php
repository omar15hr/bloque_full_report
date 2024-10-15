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
      
        $this->content->text .= '<select id="select-mes">';
        $this->content->text .= '<option value="">Selecciona un mes</option>';
        
        $months = [
            '01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo', '04' => 'Abril',
            '05' => 'Mayo', '06' => 'Junio', '07' => 'Julio', '08' => 'Agosto',
            '09' => 'Septiembre', '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre'
        ];
        
        foreach ($months as $key => $month) {
            $this->content->text .= '<option value="' . $key . '">' . $month . '</option>';
        }
        
        $this->content->text .= '</select>';
        
        $this->content->text .= '<select id="select-year">';
        $this->content->text .= '<option value="">Selecciona un año</option>';
        
        $years = range(2020, 2024); // Modifica estos años según sea necesario
        foreach ($years as $year) {
            $this->content->text .= '<option value="' . $year . '">' . $year . '</option>';
        }
        
        $this->content->text .= '</select>';
        
        $this->content->text .= '<select id="select-cursos">';
        $this->content->text .= '<option value="">Selecciona un curso</option>';
        $this->content->text .= '</select>';



        

        // Incluimos el script JS
        $PAGE->requires->js('/blocks/full_report/scripts.js');
        
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

 
