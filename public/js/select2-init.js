// Inicialización global de Select2
$(document).ready(function() {
    // Inicializar Select2 para selects de clientes
    $('.select2-cliente').select2({
        theme: 'bootstrap-5',
        placeholder: 'Seleccionar cliente',
        allowClear: true,
        width: '100%',
        language: 'es',
        minimumInputLength: 0,
        templateResult: function(data) {
            if (data.loading) return data.text;
            if (!data.id) return data.text;
            return $('<span>' + data.text + '</span>');
        }
    });

    // Inicializar Select2 para selects de estado
    $('.select2-estado').select2({
        theme: 'bootstrap-5',
        placeholder: 'Seleccionar estado',
        allowClear: true,
        width: '100%',
        language: 'es',
        minimumInputLength: 0
    });

    // Inicializar Select2 para selects de tipo de obra
    $('.select2-tipo-obra').select2({
        theme: 'bootstrap-5',
        placeholder: 'Seleccionar tipo de obra',
        allowClear: true,
        width: '100%',
        language: 'es',
        minimumInputLength: 0
    });

    // Función para mantener valores después de errores de validación
    function restoreSelect2Values() {
        // Restaurar valor para cliente_id
        if (typeof clienteIdValue !== 'undefined' && clienteIdValue) {
            $('.select2-cliente').val(clienteIdValue).trigger('change');
        }
        
        // Restaurar valor para estado
        if (typeof estadoValue !== 'undefined' && estadoValue) {
            $('.select2-estado').val(estadoValue).trigger('change');
        }
        
        // Restaurar valor para tipo de obra
        if (typeof tipoObraValue !== 'undefined' && tipoObraValue) {
            $('.select2-tipo-obra').val(tipoObraValue).trigger('change');
        }
    }

    // Ejecutar restauración de valores
    restoreSelect2Values();
});

// Función para limpiar Select2
function clearSelect2(selector) {
    $(selector).val(null).trigger('change');
}

// Función para establecer valor en Select2
function setSelect2Value(selector, value) {
    $(selector).val(value).trigger('change');
} 