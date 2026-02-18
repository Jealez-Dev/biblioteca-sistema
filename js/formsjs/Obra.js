/**
 * Obra.js - JavaScript para el formulario de Obras
 * 
 * Funcionalidades:
 * 1. Mapeo automático de datalist: cuando el usuario selecciona una opción del datalist,
 *    se extrae el data-id de esa opción y se guarda en el campo hidden correspondiente.
 * 2. Validación frontend: verifica que los campos hidden tengan valor antes de enviar.
 * 3. Guardado de estado en sesión para el flujo "Crear nuevo".
 */
$(document).ready(function() {

    // ==================== MAPEO DATALIST → HIDDEN ====================
    // Para cada input que tenga atributo data-hidden y list (es un datalist),
    // cuando el usuario escribe/selecciona un valor, buscamos la opción correspondiente
    // en el datalist y copiamos su data-id al campo hidden.

    $('input[data-hidden][list]').each(function() {
        var $displayInput = $(this);
        var hiddenFieldName = $displayInput.attr('data-hidden');
        var $hiddenInput = $('input[name="' + hiddenFieldName + '"]');
        var datalistId = $displayInput.attr('list');
        var $datalist = $('#' + datalistId);

        if ($hiddenInput.length === 0 || $datalist.length === 0) return;

        // Evento: cuando el usuario cambia el valor del input (input event)
        $displayInput.on('input change', function() {
            var val = $(this).val();
            var matchedId = '';

            // Buscar en las opciones del datalist una que coincida exactamente
            $datalist.find('option').each(function() {
                if ($(this).val() === val) {
                    matchedId = $(this).attr('data-id') || '';
                    return false; // break
                }
            });

            $hiddenInput.val(matchedId);
        });

        // Si ya tiene un valor al cargar (por ejemplo, en modo update), mapear
        if ($displayInput.val()) {
            $displayInput.trigger('input');
        }
    });

    // ==================== GUARDAR ESTADO Y REDIRIGIR ====================
    // (Para el flujo de "Crear nuevo" sub-entidad - se mantiene igual)
    
    // No hay botón de "Crear nuevo" en el datalist nativo del navegador,
    // pero mantenemos la función por si se necesita invocar manualmente.
    
    function guardarEstadoYRedirigir(entityAction) {
        var formData = {};
        $('form').find('input, textarea, select').each(function() {
            var name = $(this).attr('name');
            if (name) {
                formData[name] = $(this).val();
            }
        });

        var $tempForm = $('<form method="POST" action="?controller=Obra&action=GuardarEstadoObra&entity=' + entityAction + '" style="display:none;"></form>');
        
        $.each(formData, function(key, value) {
            $tempForm.append($('<input type="hidden" name="' + key + '" value="' + (value || '') + '">'));
        });

        $('body').append($tempForm);
        $tempForm.submit();
    }

    // ==================== VALIDACIÓN FRONTEND ====================
    
    // Validar que los campos hidden tengan valor antes de enviar
    $('form').on('submit', function(e) {
        var missingFields = [];
        
        $('input[data-hidden][list]').each(function() {
            var $display = $(this);
            var hiddenFieldName = $display.attr('data-hidden');
            var $hidden = $('input[name="' + hiddenFieldName + '"]');
            
            // Si el campo de display tiene texto pero el hidden está vacío,
            // significa que no seleccionó una opción válida del datalist
            if ($display.val().trim() !== '' && $hidden.val().trim() === '') {
                var label = $display.closest('.col-md-6').find('label').text().replace(':', '').trim();
                missingFields.push(label);
            }
        });

        if (missingFields.length > 0) {
            e.preventDefault();
            alert('Por favor seleccione un valor válido de la lista para: ' + missingFields.join(', '));
            return false;
        }
    });
});
