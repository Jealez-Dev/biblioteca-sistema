/**
 * Renovacion.js
 * Datalist hidden-field mapping for Prestamo search
 */
$(document).ready(function() {

    $('input[data-hidden][list]').each(function() {
        var $displayInput = $(this);
        var hiddenFieldConfig = $displayInput.attr('data-hidden');
        var datalistId = $displayInput.attr('list');
        var $datalist = $('#' + datalistId);

        if (!hiddenFieldConfig || $datalist.length === 0) return;

        var hiddenFields = hiddenFieldConfig.split(',').map(function(s) { return s.trim(); });

        $displayInput.on('input change', function() {
            var val = $(this).val();
            var matchedDataId = '';

            var $option = $datalist.find('option').filter(function() {
                return $(this).val() === val;
            });

            if ($option.length > 0) {
                matchedDataId = $option.attr('data-id') || '';
            }

            var values = matchedDataId.split('|');

            hiddenFields.forEach(function(fieldName, index) {
                var $hidden = $('input[name="' + fieldName + '"]');
                if ($hidden.length > 0) {
                    var valueToSet = values[index] !== undefined ? values[index] : '';
                    $hidden.val(valueToSet);
                }
            });
        });
    });

    // Submit validation
    $('form').on('submit', function(e) {
        var missingFields = [];
        $('input[data-hidden][list]').each(function() {
            var $display = $(this);
            var hiddenFieldConfig = $display.attr('data-hidden');
            var hiddenFields = hiddenFieldConfig.split(',');
            
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
