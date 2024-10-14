<?php

require_once(__DIR__ . '/lib.php');

 class block_full_report extends block_base
{
    public function init()
    {
        $this->title = get_string('pluginname', 'block_full_report');
    }

    public function get_content() {
        global $OUTPUT, $PAGE, $DB;

        $curso_seleccionado = '';
        $mes_seleccionado = '';
        $cursos = [];
    
        // Verifica si el formulario fue enviado (request POST)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['curso'])) {
                $curso_seleccionado = $_POST['curso']; // Obteniendo el valor del año seleccionado
            }
            if (isset($_POST['mes'])) {
                $mes_seleccionado = $_POST['mes']; // Obteniendo el valor del mes seleccionado
            }
    
            // Realizar la consulta SQL solo si ambos valores están seleccionados
            if (!empty($curso_seleccionado) && !empty($mes_seleccionado)) {
                $year_start = $curso_seleccionado . '-' . str_pad($mes_seleccionado, 2, '0', STR_PAD_LEFT) . '-01'; // Inicio del mes

                // Obtener el último día del mes
                $year_end = date("Y-m-t", strtotime($year_start)); // Fin del mes

                $sql = "SELECT * FROM {course} WHERE timecreated >= :year_start AND timecreated <= :year_end";
                $params = [
                    'year_start' => strtotime($year_start),
                    'year_end' => strtotime($year_end),
                ];
                
                $cursos = $DB->get_records_sql($sql, $params); // Obtener los cursos
            }
        }
    
        // Definir el contenido del bloque
        $this->content = new stdClass();
        $this->content->text = '
            <style>
                /* Estilos para el formulario */
                form {
                    margin: 20px;
                    font-family: Arial, sans-serif;
                }
    
                /* Estilos para el select */
                select {
                    padding: 10px;
                    border: 1px solid #ccc;
                    border-radius: 5px;
                    font-size: 16px;
                    transition: border-color 0.3s;
                }
    
                select:focus {
                    border-color: #007bff; /* Color de borde en foco */
                    outline: none; /* Sin contorno */
                }
    
                /* Estilos para el botón */
                button {
                    padding: 10px 15px;
                    background-color: #007bff;
                    color: white;
                    border: none;
                    border-radius: 5px;
                    cursor: pointer;
                    font-size: 16px;
                    margin-top: 10px;
                }
    
                button:hover {
                    background-color: #0056b3; /* Color en hover */
                }
            </style>
            <form method="POST" action="">
                <label for="curso">Selecciona un curso:</label>
                <select name="curso" id="curso" onchange="this.form.submit()">
                    <option value="2023">2023</option>
                    <option value="2024">2024</option>
                    <option value="2025">2025</option>
                </select>
            </form>
        ';

        // Añadir el formulario de selección de meses si un año ha sido seleccionado
        
            $this->content->text .= '
            <form method="POST" action="">
                <input type="hidden" name="curso" value="' . htmlspecialchars($curso_seleccionado) . '">
                <label for="mes">Selecciona un mes:</label>
                <select name="mes" id="mes" onchange="this.form.submit()">
                    <option value="">-- Selecciona un mes --</option>
                    <option value="1">Enero</option>
                    <option value="2">Febrero</option>
                    <option value="3">Marzo</option>
                    <option value="4">Abril</option>
                    <option value="5">Mayo</option>
                    <option value="6">Junio</option>
                    <option value="7">Julio</option>
                    <option value="8">Agosto</option>
                    <option value="9">Septiembre</option>
                    <option value="10">Octubre</option>
                    <option value="11">Noviembre</option>
                    <option value="12">Diciembre</option>
                </select>
            </form>
            ';
        

        // Mostrar selección
        if (!empty($curso_seleccionado) && !empty($mes_seleccionado)) {
            $this->content->text .= '<span>Has seleccionado: Año ' . htmlspecialchars($curso_seleccionado) . ', Mes ' . htmlspecialchars($mes_seleccionado) . '</span>';
        }

        // Crear un nuevo select con los cursos obtenidos
        if ($cursos) {
            $this->content->text .= '<h3>Cursos creados en ' . htmlspecialchars($curso_seleccionado) . ' en el mes ' . htmlspecialchars($mes_seleccionado) . ':</h3>';
            $this->content->text .= '<form method="POST" action="">';
            $this->content->text .= '<label for="curso_select">Selecciona un curso:</label>';
            $this->content->text .= '<select name="curso_select" id="curso_select" onchange="this.form.submit()">'; // Enviar automáticamente al seleccionar
            foreach ($cursos as $curso) {
                $this->content->text .= '<option value="' . htmlspecialchars($curso->id) . '">' . htmlspecialchars($curso->fullname) . '</option>'; // Muestra el nombre completo del curso
            }
            $this->content->text .= '</select>';
            $this->content->text .= '</form>';
        } else {
            if (!empty($curso_seleccionado) && !empty($mes_seleccionado)) {
                $this->content->text .= '<span>No se encontraron cursos creados en ' . htmlspecialchars($curso_seleccionado) . ' en el mes ' . htmlspecialchars($mes_seleccionado) . '.</span>';
            }
        }
    
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

 
