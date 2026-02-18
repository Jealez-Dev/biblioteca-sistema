/**
 * Ejemplar.js
 * Extends datalist functionality to support:
 * 1. Mapeo de múltiples campos hidden (separados por coma en data-hidden)
 *    desde un valor compuesto (separado por | en data-id).
 */
$(document).ready(function() {

    // Buscar inputs con datalist y data-hidden
    $('input[data-hidden][list]').each(function() {
        var $displayInput = $(this);
        var hiddenFieldConfig = $displayInput.attr('data-hidden'); // e.g., "Año_Obras,Cutter_Autores,Codigo_CDD"
        var datalistId = $displayInput.attr('list');
        var $datalist = $('#' + datalistId);

        if (!hiddenFieldConfig || $datalist.length === 0) return;

        // Separar los nombres de los campos hidden
        var hiddenFields = hiddenFieldConfig.split(',').map(function(s) { return s.trim(); });

        // Evento cambio
        $displayInput.on('input change', function() {
            var val = $(this).val();
            var matchedDataId = '';

            // 1. Buscar coincidencia
            var $option = $datalist.find('option').filter(function() {
                return $(this).val() === val;
            });

            if ($option.length > 0) {
                matchedDataId = $option.attr('data-id') || '';
            }

            // 2. Si hay match, splitear el data-id (usando pipe |)
            // Si data-id es "2020|Cut123|300", asigna a field1, field2, field3
            // Si es un valor simple "5", asigna a field1.
            var values = matchedDataId.split('|');

            // 3. Asignar valores a los inputs hidden
            hiddenFields.forEach(function(fieldName, index) {
                var $hidden = $('input[name="' + fieldName + '"]');
                if ($hidden.length > 0) {
                    var valueToSet = values[index] !== undefined ? values[index] : '';
                    $hidden.val(valueToSet);
                }
            });
        });

        // Trigger inicial si ya hay valor (Modo Update)
        if ($displayInput.val()) {
            // Nota: En update, los hidden ya vienen poblados por PHP value="", 
            // pero esto asegura consistencia si el usuario edita.
            // No, wait. Si disparamos esto y el display tiene texto pero NO macha exactamente (pq es editable?), podría borrar los hidden?
            // En update.php, value="" puebla display y hiddens.
            // Si el user no toca nada, está ok.
            // Si el user toca, debe seleccionar del datalist.
        }
    });

    // Validación Submit
    $('form').on('submit', function(e) {
        var missingFields = [];
        $('input[data-hidden][list]').each(function() {
            var $display = $(this);
            var hiddenFieldConfig = $display.attr('data-hidden');
            var hiddenFields = hiddenFieldConfig.split(',');
            
            // Verificar si el PRIMER hidden field tiene valor. 
            // (Asumimos que si uno falla, fallan todos en composite)
            var $firstHidden = $('input[name="' + hiddenFields[0].trim() + '"]');

            if ($display.val().trim() !== '' && $firstHidden.val().trim() === '') {
                var label = $display.closest('.col-md-6').find('label').text().replace(':', '').trim();
                missingFields.push(label);
            }
        });

        if (missingFields.length > 0) {
            e.preventDefault();
            alert('Por favor seleccione una opción válida de la lista para: ' + missingFields.join(', '));
            return false;
        }
    });
});
