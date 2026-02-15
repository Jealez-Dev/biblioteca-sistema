document.addEventListener('DOMContentLoaded', function () {
    // Seleccionamos el <select> por su nombre
    const selectPerfil = document.querySelector('select[name="ID_Catg_de_User_SA"]');
    const divCarrera = document.getElementById('div_Carrera');
    const divDepto = document.getElementById('div_Departamento');

    function updateForm() {
        if (!selectPerfil) return;

        const valor = selectPerfil.value;

        // Comparamos el valor seleccionado en el dropdown
        if (valor === "1 - Estudiante") {
            divCarrera.style.display = 'block';
            divDepto.style.display = 'none';
        }
        else if (valor === "2 - Docente") {
            divDepto.style.display = 'block';
            divCarrera.style.display = 'none';
        }
        else {
            // Ocultar ambos si no hay selección o es otra distinta
            divCarrera.style.display = 'none';
            divDepto.style.display = 'none';
        }
    }

    if (selectPerfil) {
        selectPerfil.addEventListener('change', updateForm);
    }

    updateForm(); // Estado inicial al cargar la página
});