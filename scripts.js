// PRIMER SELECT

document.addEventListener('DOMContentLoaded', function() {
  let yearSelect = document.getElementById('yearSelect');
  let monthSelect = document.getElementById('monthSelect');
  let courseSelect = document.getElementById('courseSelect');
  
  // Escuchar el cambio en el selector de años
  yearSelect.addEventListener('change', function() {
      console.log('Año seleccionado: ' + this.value);
  });
  monthSelect.addEventListener('change', function() {
      console.log('Mes seleccionado: ' + this.value);
  });
  courseSelect.addEventListener('change', function() {
      console.log('Curso seleccionado: ' + this.value);
  });
});


// SEGUNDO SELECT
document.addEventListener('DOMContentLoaded', function() {
  let yearSelect2 = document.getElementById('yearSelect2');
  let monthSelect2 = document.getElementById('monthSelect2');
  
  // Escuchar el cambio en el selector de años
  yearSelect2.addEventListener('change', function() {
      console.log('Año 2 seleccionado: ' + this.value);
  });
  monthSelect2.addEventListener('change', function() {
      console.log('Mes 2 seleccionado: ' + this.value);
  });
});