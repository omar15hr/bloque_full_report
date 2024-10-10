<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle. If not, see <http://www.gnu.org/licenses/>.

/**
 * Block definition class for the block_full_report plugin.
 *
 * @package   block_full_report
 * @copyright Year, You Name <your@email.address>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_full_report extends block_list
{

  /**
   * Initialises the block.
   *
   * @return void
   */
  public function init()
  {
    $this->title = get_string('pluginname', 'block_full_report');
  }

  /**
   * Gets the block contents.
   *
   * @return string The block HTML.
   */
  public function get_content()
  {
    global $OUTPUT;

    if ($this->content !== null) {
      return $this->content;
    }

    $this->page->requires->js(new moodle_url('/blocks/full_report/index.js'));

    $this->content = new stdClass();
    $this->content->footer = '
        <label for="miDropdown">Resultados Cursos:</label>
        <select name="miDropdown" id="miDropdown" onchange="mostrarContenido()">
            <option value="" disabled selected>Selecciona una opción</option>
            <option value="opcion1">Resultados por Usuarios Inscritos</option>
            <option value="opcion2">Resultados Totales Cursos por Ejecución</option>
            <option value="opcion3">Resultados Totales por Servicio</option>
        </select>

        <div id="contenidoAdicional" style="margin-top: 10px;"></div>

        
    ';

    return $this->content;
  }

  /**
   * Defines in which pages this block can be added.
   *
   * @return array of the pages where the block can be added.
   */
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
