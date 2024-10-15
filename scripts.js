document.addEventListener('DOMContentLoaded', function() {
  var selectMes = document.getElementById('select-mes');
  var selectYear = document.getElementById('select-year'); 
  var selectCursos = document.getElementById('select-cursos');

  selectMes.addEventListener('change', cargarCursos);
  selectYear.addEventListener('change', cargarCursos);


  // Asegúrate de que este código se encuentre dentro de la función `DOMContentLoaded`
  selectCursos.addEventListener('change', function() {
    var cursoSeleccionado = selectCursos.value; // Obtener el valor del curso seleccionado
    
  });


  function cargarCursos() {
      var mesSeleccionado = selectMes.value;
      var yearSeleccionado = selectYear.value;

      if (mesSeleccionado !== '' && yearSeleccionado !== '') {
          var xhr = new XMLHttpRequest();
          xhr.open('POST', M.cfg.wwwroot + '/blocks/full_report/ajax.php', true);
          xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
          
          xhr.onreadystatechange = function() {
              if (xhr.readyState === 4 && xhr.status === 200) {
                  var cursos = JSON.parse(xhr.responseText);
                  selectCursos.innerHTML = '<option value="">Selecciona un curso</option>';

                  cursos.forEach(function(curso) {
                      var option = document.createElement('option');
                      option.value = curso.id;
                      option.text = curso.fullname + ' (Inicio: ' + curso.startdate + ')';
                      selectCursos.appendChild(option);
                  });
              }
          };

          xhr.send('mes=' + mesSeleccionado + '&year=' + yearSeleccionado + '&sesskey=' + M.cfg.sesskey);
      } else {
          selectCursos.innerHTML = '<option value="">Selecciona un curso</option>';
      }
  }

});


