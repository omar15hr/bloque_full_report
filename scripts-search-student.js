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
                var resultHtml = "<ul class='search-results'>";
                response.users.forEach(function(user) {
                    resultHtml += "<li class='result-item'>";
                    resultHtml += "<span class='result-name'>Nombre: " + user.name + " " + user.surname + "</span><br>";
                    resultHtml += "<span class='result-username'>Username: " + user.username + "</span><br>";
                    resultHtml += "<span class='result-email'>Email: " + user.email + "</span><br>"; // Añadir el email
                    resultHtml += "<span class='result-course'>Curso: " + user.course + "</span><br>";
                    resultHtml += "<span class='result-institution'>Institución: " + user.institution + "</span><br>";
                    resultHtml += "<span class='result-status'>Estado: " + user.status + "</span><br>"; // Agregar esta línea para mostrar el estado
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
