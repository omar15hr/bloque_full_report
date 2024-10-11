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

        $data = [
            ['1', '12345678-9', 'Juan', 'Pérez', 'García', 'juan.perez@example.com', 'Servicio 1', 'Dependencia 1', 'Curso A', 'Colegio 1', 'Ley A', 'Área 1', 'Cargo 1', '2024-01-01', 'Activo', '7.0'],
            ['2', '87654321-0', 'Ana', 'López', 'Sánchez', 'ana.lopez@example.com', 'Servicio 2', 'Dependencia 2', 'Curso B', 'Colegio 2', 'Ley B', 'Área 2', 'Cargo 2', '2024-01-05', 'Inactivo', '6.5'],
            ['3', '11223344-5', 'Carlos', 'Martínez', 'Rodríguez', 'carlos.martinez@example.com', 'Servicio 3', 'Dependencia 3', 'Curso C', 'Colegio 3', 'Ley C', 'Área 3', 'Cargo 3', '2024-01-10', 'Activo', '8.0'],
            ['4', '99887766-5', 'Luisa', 'Gómez', 'Fernández', 'luisa.gomez@example.com', 'Servicio 4', 'Dependencia 4', 'Curso D', 'Colegio 4', 'Ley D', 'Área 4', 'Cargo 4', '2024-01-15', 'Inactivo', '5.5'],
            ['5', '55667788-9', 'Pedro', 'Gutiérrez', 'Lara', 'pedro.gutierrez@example.com', 'Servicio 5', 'Dependencia 5', 'Curso E', 'Colegio 5', 'Ley E', 'Área 5', 'Cargo 5', '2024-01-20', 'Activo', '7.5'],
            ['6', '33445566-7', 'Sofía', 'Jiménez', 'Ramos', 'sofia.jimenez@example.com', 'Servicio 6', 'Dependencia 6', 'Curso F', 'Colegio 6', 'Ley F', 'Área 6', 'Cargo 6', '2024-01-25', 'Inactivo', '6.0'],
            ['7', '77889900-1', 'Raúl', 'Vargas', 'Soto', 'raul.vargas@example.com', 'Servicio 7', 'Dependencia 7', 'Curso G', 'Colegio 7', 'Ley G', 'Área 7', 'Cargo 7', '2024-01-30', 'Activo', '8.5'],
            ['8', '22334455-6', 'María', 'Ortiz', 'León', 'maria.ortiz@example.com', 'Servicio 8', 'Dependencia 8', 'Curso H', 'Colegio 8', 'Ley H', 'Área 8', 'Cargo 8', '2024-02-01', 'Inactivo', '5.0'],
            ['9', '66554433-2', 'Javier', 'Morales', 'Reyes', 'javier.morales@example.com', 'Servicio 9', 'Dependencia 9', 'Curso I', 'Colegio 9', 'Ley I', 'Área 9', 'Cargo 9', '2024-02-05', 'Activo', '9.0'],
            ['10', '55443322-1', 'Laura', 'Castro', 'Herrera', 'laura.castro@example.com', 'Servicio 10', 'Dependencia 10', 'Curso J', 'Colegio 10', 'Ley J', 'Área 10', 'Cargo 10', '2024-02-10', 'Inactivo', '7.0'],
            ['11', '88776655-4', 'Diego', 'Salinas', 'Muñoz', 'diego.salinas@example.com', 'Servicio 11', 'Dependencia 11', 'Curso K', 'Colegio 11', 'Ley K', 'Área 11', 'Cargo 11', '2024-02-15', 'Activo', '8.0'],
            ['12', '44332211-0', 'Lucía', 'Vega', 'Ruiz', 'lucia.vega@example.com', 'Servicio 12', 'Dependencia 12', 'Curso L', 'Colegio 12', 'Ley L', 'Área 12', 'Cargo 12', '2024-02-20', 'Inactivo', '6.5'],
            ['13', '11224455-3', 'Mateo', 'Sáenz', 'Silva', 'mateo.saenz@example.com', 'Servicio 13', 'Dependencia 13', 'Curso M', 'Colegio 13', 'Ley M', 'Área 13', 'Cargo 13', '2024-02-25', 'Activo', '9.5'],
            ['14', '99882244-6', 'Valentina', 'Romero', 'Cruz', 'valentina.romero@example.com', 'Servicio 14', 'Dependencia 14', 'Curso N', 'Colegio 14', 'Ley N', 'Área 14', 'Cargo 14', '2024-03-01', 'Inactivo', '4.5'],
            ['15', '55669988-7', 'Nicolás', 'Flores', 'Peña', 'nicolas.flores@example.com', 'Servicio 15', 'Dependencia 15', 'Curso O', 'Colegio 15', 'Ley O', 'Área 15', 'Cargo 15', '2024-03-05', 'Activo', '8.0'],
            ['16', '33441122-8', 'Gabriela', 'Mendoza', 'Paredes', 'gabriela.mendoza@example.com', 'Servicio 16', 'Dependencia 16', 'Curso P', 'Colegio 16', 'Ley P', 'Área 16', 'Cargo 16', '2024-03-10', 'Inactivo', '5.5'],
            ['17', '77448866-9', 'Oscar', 'Navarro', 'Quintero', 'oscar.navarro@example.com', 'Servicio 17', 'Dependencia 17', 'Curso Q', 'Colegio 17', 'Ley Q', 'Área 17', 'Cargo 17', '2024-03-15', 'Activo', '7.5'],
            ['18', '22114433-2', 'Isabel', 'Ríos', 'Zamora', 'isabel.rios@example.com', 'Servicio 18', 'Dependencia 18', 'Curso R', 'Colegio 18', 'Ley R', 'Área 18', 'Cargo 18', '2024-03-20', 'Inactivo', '6.0'],
            ['19', '66443322-1', 'Tomás', 'Aguilar', 'Núñez', 'tomas.aguilar@example.com', 'Servicio 19', 'Dependencia 19', 'Curso S', 'Colegio 19', 'Ley S', 'Área 19', 'Cargo 19', '2024-03-25', 'Activo', '9.0'],
            ['20', '55221100-5', 'Daniela', 'Pizarro', 'Campos', 'daniela.pizarro@example.com', 'Servicio 20', 'Dependencia 20', 'Curso T', 'Colegio 20', 'Ley T', 'Área 20', 'Cargo 20', '2024-03-30', 'Inactivo', '4.0']
        ];
        

        // Usar la función para crear el div con los selectores
        $this->content->text .= crear_primer_div($years, $months). generar_primera_tabla($data);


        $data2 = [
            ['1', 'Curso A', '2024-01-01', '30', '25', '3', '2', '0'],
            ['2', 'Curso B', '2024-01-15', '25', '20', '3', '1', '1'],
            ['3', 'Curso C', '2024-02-01', '20', '18', '1', '1', '0'],
            ['4', 'Curso D', '2024-02-20', '35', '30', '2', '2', '1'],
            ['5', 'Curso E', '2024-03-01', '40', '35', '2', '3', '0'],
            ['6', 'Curso F', '2024-03-15', '22', '18', '2', '1', '1'],
            ['7', 'Curso G', '2024-03-25', '28', '25', '1', '1', '1'],
            ['8', 'Curso H', '2024-04-05', '32', '29', '2', '1', '0'],
            ['9', 'Curso I', '2024-04-20', '25', '20', '3', '2', '0'],
            ['10', 'Curso J', '2024-05-01', '30', '26', '2', '2', '0'],
        ];

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

 
