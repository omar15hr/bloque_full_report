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
        global $OUTPUT, $DB;

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass();

        // PRIMER LABEL
        $this->content->text .= html_writer::tag('h3', 'Resultados por Usuarios Inscritos', ['style' => 'text-align: center;']);
        
        // Datos para los selects
        $years = [];
        for ($year = 2020; $year <= 2025; $year++) {
            $years[$year] = $year;
        }

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
            '12' => 'Diciembre',
        ];

        $data = obtener_usuarios();
        $this->content->text .= crear_primer_div($years, $months) . generar_primera_tabla($data);


        $data2 = obtener_cursos();

        // SEGUNDO LABEL
        $this->content->text .= html_writer::tag('h3', 'Resultados Totales Cursos por Ejecución', ['style' => 'text-align: center; margin-top: 10px;']);

        // Crear el SEGUNDO div
        $this->content->text .= crear_segundo_div($years, $months). generar_segunda_tabla($data2);
        

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

 
