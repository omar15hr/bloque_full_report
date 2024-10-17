document.getElementById("searchButton").addEventListener("click", function() {
    var username = document.getElementById("studentSearch").value;

    if (username.trim() === "") {
        document.getElementById("searchResults").innerText = "Por favor, ingresa un username.";
        return;
    }

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "/blocks/full_report/ajax-search-student.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);

            if (response.error) {
                document.getElementById("searchResults").innerText = response.error;
            } else {
                var resultHtml = "<ul>";
                response.users.forEach(function(user) {
                    resultHtml += "<li>Nombre: " + user.name + " " + user.surname + "<br>";
                    resultHtml += "Username: " + user.username + "<br>";
                    resultHtml += "Curso: " + user.course + "<br>";
                    resultHtml += "Institución: " + user.institution + "<br>";
                    
                    // Mostrar el estado del alumno
                    resultHtml += "Estado: " + user.status + "<br>"; // Agregar esta línea para mostrar el estado
            
                    // Mostrar las actividades y calificaciones
                    if (user.activities.length > 0) {
                        resultHtml += "<ul>";
                        user.activities.forEach(function(activity) {
                            resultHtml += "<li>Actividad: " + activity.name + " - Nota: " + (activity.grade !== null ? activity.grade : "Sin calificación") + "</li>";
                        });
                        resultHtml += "</ul>";
                    } else {
                        resultHtml += "No hay actividades evaluadas.<br>";
                    }
            
                    resultHtml += "</li><br>";
                });
                resultHtml += "</ul>";
                document.getElementById("searchResults").innerHTML = resultHtml;
            }
            
            
            
        }
    };

    // Enviar el nombre de usuario en la solicitud
    var params = "username=" + encodeURIComponent(username);
    xhr.send(params);
});
