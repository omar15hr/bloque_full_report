document.addEventListener("DOMContentLoaded", function() {
  // Función para actualizar el select de cursos
  function updateCourseSelect() {
      var month = document.getElementById("monthSelect").value;
      var year = document.getElementById("yearSelect").value;

      // Verificar que se hayan seleccionado mes y año
      if (month === "" || year === "") {
          document.getElementById("ajaxResponse").innerText = "Por favor, selecciona mes y año.";
          return;
      }

      // Crear una solicitud AJAX
      var xhr = new XMLHttpRequest();
      xhr.open("POST", "/blocks/full_report/ajax.php", true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

      // Manejar la respuesta
      xhr.onreadystatechange = function() {
          if (xhr.readyState === 4) {
              if (xhr.status === 200) {
                  var response = JSON.parse(xhr.responseText);
                  document.getElementById("ajaxResponse").innerText = response.message;

                  // Llenar el select de cursos
                  var courseSelect = document.getElementById("courseSelect");
                  courseSelect.innerHTML = '<option value="">Selecciona un curso</option>'; // Limpiar opciones previas

                  if (response.courses.length > 0) {
                      response.courses.forEach(function(course) {
                          var option = document.createElement("option");
                          option.value = course.id;
                          option.textContent = course.name;
                          courseSelect.appendChild(option);
                      });
                  } else {
                      var option = document.createElement("option");
                      option.textContent = "No se encontraron cursos.";
                      courseSelect.appendChild(option);
                  }
              } else {
                  document.getElementById("ajaxResponse").innerText = "Error en la solicitud: " + xhr.status;
              }
          }
      };

      // Enviar los datos al servidor
      var params = "month=" + encodeURIComponent(month) + "&year=" + encodeURIComponent(year);
      xhr.send(params);
  }

  // Añadir eventos a los selectores de mes y año
  document.getElementById("monthSelect").addEventListener("change", updateCourseSelect);
  document.getElementById("yearSelect").addEventListener("change", updateCourseSelect);

  // Evento para el botón de consulta de curso
  document.getElementById("ajaxForm").addEventListener("submit", function(event) {
    event.preventDefault(); // Evitar el envío del formulario normal
    var courseId = document.getElementById("courseSelect").value;

    if (!courseId) {
        document.getElementById("ajaxResponse").innerText = "Por favor, selecciona un curso.";
        return;
    }

    // Crear una solicitud AJAX para obtener la información del curso
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "/blocks/full_report/ajax.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    // Manejar la respuesta
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                var responseMessage = "Nombre del curso: " + response.course_name + "<br>" +
                                      "Fecha de inicio: " + response.startdate + "<br>" +
                                      "Matriculados: " + response.enrolled_count;
                document.getElementById("ajaxResponse").innerHTML = responseMessage;
            } else {
                document.getElementById("ajaxResponse").innerText = "Error en la solicitud: " + xhr.status;
            }
        }
    };

    // Enviar los datos al servidor
    var params = "courseId=" + encodeURIComponent(courseId);
    xhr.send(params);
  });
});
