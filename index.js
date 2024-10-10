function mostrarContenido() {
  const dropdown = document.getElementById("miDropdown");
  const contenido = document.getElementById("contenidoAdicional");
  contenido.innerHTML = ""; // Limpiar contenido previo

  // Cambiar contenido según la opción seleccionada
  switch (dropdown.value) {
    case "opcion1":
      contenido.innerHTML = `
                            <p>Has seleccionado Opción 1: Resultados por Usuarios Inscritos.</p>
                            <label for="select1">Seleccionar Ejecución:</label>
                            <select name="select1" id="select1">
                                <option value="opcion1a">Opción 1A</option>
                                <option value="opcion1b">Opción 1B</option>
                            </select>
                            <label for="select2">Seleccionar Curso:</label>
                            <select name="select2" id="select2">
                                <option value="opcion2a">Opción 2A</option>
                                <option value="opcion2b">Opción 2B</option>
                            </select>
                        `;
      break;
    case "opcion2":
      contenido.innerHTML = `
                            <p>Has seleccionado Opción 2: Resultados Totales Cursos por Ejecución.</p>
                            <label for="select3">Seleccionar Ejecución:</label>
                            <select name="select3" id="select3">
                                <option value="opcion3a">Opción 3A</option>
                            </select>
                        `;
      break;
    case "opcion3":
      contenido.innerHTML = `
                            <p>Has seleccionado Opción 3: Resultados Totales por Servicio.</p>
                            <label for="select4">Seleccionar Ejecución:</label>
                            <select name="select4" id="select4">
                                <option value="opcion4a">Opción 4A</option>
                                <option value="opcion4b">Opción 4B</option>
                            </select>
                            <label for="select5">Seleccionar Curso:</label>
                            <select name="select5" id="select5">
                                <option value="opcion5a">Opción 5A</option>
                                <option value="opcion5b">Opción 5B</option>
                            </select>
                        `;
      break;
    default:
      contenido.innerHTML = "<p>Por favor, selecciona una opción.</p>";
  }
}
