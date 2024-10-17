document.addEventListener("DOMContentLoaded", function() {

  // Función para actualizar la lista de cursos
  function updateCourseList() {
      var month2 = document.getElementById("monthSelect2").value;
      var year2 = document.getElementById("yearSelect2").value;

      // Verificar que se hayan seleccionado mes y año
      if (month2 === "" || year2 === "") {
          document.getElementById("ajaxResponse2").innerText = "Por favor, selecciona mes y año.";
          return;
      }

      // Crear una solicitud AJAX para obtener cursos
      var xhr = new XMLHttpRequest();
      xhr.open("POST", "/blocks/full_report/ajax-courses.php", true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

      // Manejar la respuesta
      xhr.onreadystatechange = function() {
          if (xhr.readyState === 4) {
              if (xhr.status === 200) {
                  // Mostrar directamente el HTML devuelto
                  document.getElementById("ajaxResponse2").innerHTML = xhr.responseText;
              } else {
                  document.getElementById("ajaxResponse2").innerText = "Error en la solicitud: " + xhr.status;
              }
          }
      };

      // Enviar los datos al servidor
      var params = "month2=" + encodeURIComponent(month2) + "&year2=" + encodeURIComponent(year2);
      xhr.send(params);
  }

  // Añadir eventos a los selectores de mes y año
  document.getElementById("monthSelect2").addEventListener("change", updateCourseList);
  document.getElementById("yearSelect2").addEventListener("change", updateCourseList);
});
