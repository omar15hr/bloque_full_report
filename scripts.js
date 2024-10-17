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

        // Crear una solicitud AJAX para obtener cursos
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

    // Función para obtener usuarios del curso seleccionado
    function getUsersFromCourse(courseId) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "/blocks/full_report/ajax.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.error) {
                        document.getElementById("ajaxResponse").innerText = response.error;
                    } else {
                        // Iniciar la estructura de la tabla
                        var responseMessage = "<h3 class='title-table-course'>Curso: " + response.users[0].course_name + "</h3>";
                        responseMessage += "<table class='user-table'><thead><tr><th>Usuario</th><th>Nombre</th><th>Apellido</th><th>Institución</th><th>Email</th><th>Estado</th></tr></thead><tbody>";
                    
                        response.users.forEach(function(user) {
                            responseMessage += "<tr>"
                                + "<td>" + user.username + "</td>"
                                + "<td>" + user.name + "</td>"
                                + "<td>" + user.surname + "</td>"
                                + "<td>" + (user.institution || "Sin institución") + "</td>"
                                + "<td>" + user.email + "</td>"
                                + "<td>" + user.status + "</td>"
                                + "</tr>";
                        });
                    
                        responseMessage += "</tbody></table>";
                        document.getElementById("ajaxResponse").innerHTML = responseMessage;
                    }
                    
                } else {
                    document.getElementById("ajaxResponse").innerText = "Error en la solicitud: " + xhr.status;
                }
            }
        };

        // Enviar los datos al servidor
        var params = "courseId=" + encodeURIComponent(courseId);
        xhr.send(params);
    }

    // Añadir eventos a los selectores de mes y año
    document.getElementById("monthSelect").addEventListener("change", updateCourseSelect);
    document.getElementById("yearSelect").addEventListener("change", updateCourseSelect);

    // Evento para cuando se selecciona un curso
    document.getElementById("courseSelect").addEventListener("change", function() {
        var courseId = this.value;
        if (courseId) {
            getUsersFromCourse(courseId);
        } else {
            document.getElementById("ajaxResponse").innerHTML = ""; // Limpiar la respuesta si no hay curso seleccionado
        }
    });

});







