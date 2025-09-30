/**
 * Mejoras adicionales para el paginado de DataTables
 * Incluye navegación rápida y funcionalidades avanzadas
 */

// Función para agregar navegación rápida al paginado
function addQuickNavigation(table) {
    // Esperar a que DataTables esté completamente inicializado
    setTimeout(function() {
        var wrapper = table.closest('.dataTables_wrapper');
        var paginateContainer = wrapper.find('.dataTables_paginate');
        
        // Crear contenedor para navegación rápida
        var quickNavContainer = $('<div class="quick-navigation mt-4 flex items-center justify-center gap-2"></div>');
        
        // Input para ir a página específica
        var pageInput = $('<input type="number" class="page-input w-16 px-2 py-1 text-center border border-gray-300 rounded-md focus:border-blue-500 focus:outline-none" placeholder="Página" min="1">');
        
        // Botón para ir a página
        var goButton = $('<button class="go-page-btn px-3 py-1 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors text-sm font-medium">Ir</button>');
        
        // Información de páginas totales
        var totalPages = table.DataTable().page.info().pages;
        var pageInfo = $('<span class="text-sm text-gray-600 ml-2">de ' + totalPages + ' páginas</span>');
        
        // Agregar elementos al contenedor
        quickNavContainer.append(
            $('<span class="text-sm text-gray-600">Ir a página:</span>'),
            pageInput,
            goButton,
            pageInfo
        );
        
        // Insertar después del paginado
        paginateContainer.after(quickNavContainer);
        
        // Evento para el botón "Ir"
        goButton.on('click', function() {
            var pageNumber = parseInt(pageInput.val());
            var currentPage = table.DataTable().page.info().page + 1;
            
            if (pageNumber && pageNumber >= 1 && pageNumber <= totalPages) {
                table.DataTable().page(pageNumber - 1).draw('page');
                pageInput.val('');
            } else {
                // Mostrar mensaje de error
                showPageError('Por favor ingresa un número de página válido (1-' + totalPages + ')');
            }
        });
        
        // Evento para Enter en el input
        pageInput.on('keypress', function(e) {
            if (e.which === 13) {
                goButton.click();
            }
        });
        
        // Actualizar información cuando cambie la página
        table.on('draw.dt', function() {
            var info = table.DataTable().page.info();
            totalPages = info.pages;
            pageInfo.text('de ' + totalPages + ' páginas');
            
            // Limpiar input si está fuera de rango
            var currentValue = parseInt(pageInput.val());
            if (currentValue && (currentValue < 1 || currentValue > totalPages)) {
                pageInput.val('');
            }
        });
        
    }, 100);
}

// Función para mostrar errores de página
function showPageError(message) {
    // Crear elemento de error temporal
    var errorDiv = $('<div class="page-error absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-full bg-red-500 text-white px-4 py-2 rounded-md text-sm font-medium shadow-lg z-50"></div>');
    errorDiv.text(message);
    
    // Agregar al body
    $('body').append(errorDiv);
    
    // Animar entrada
    errorDiv.css({
        'opacity': '0',
        'transform': 'translateX(-50%) translateY(-100%)'
    });
    
    setTimeout(function() {
        errorDiv.css({
            'opacity': '1',
            'transform': 'translateX(-50%) translateY(-120%)',
            'transition': 'all 0.3s ease'
        });
    }, 10);
    
    // Remover después de 3 segundos
    setTimeout(function() {
        errorDiv.css({
            'opacity': '0',
            'transform': 'translateX(-50%) translateY(-100%)',
            'transition': 'all 0.3s ease'
        });
        setTimeout(function() {
            errorDiv.remove();
        }, 300);
    }, 3000);
}

// Función para agregar indicadores de carga mejorados
function addLoadingIndicators(table) {
    // Personalizar el indicador de procesamiento
    table.on('processing.dt', function(e, settings, processing) {
        if (processing) {
            var wrapper = $(settings.nTableWrapper);
            var loadingDiv = wrapper.find('.custom-loading');
            
            if (loadingDiv.length === 0) {
                loadingDiv = $('<div class="custom-loading absolute inset-0 bg-white bg-opacity-90 flex items-center justify-center z-50 rounded-lg"></div>');
                loadingDiv.html(`
                    <div class="text-center">
                        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mb-2"></div>
                        <div class="text-blue-600 font-medium">Cargando datos...</div>
                    </div>
                `);
                wrapper.css('position', 'relative').append(loadingDiv);
            }
            
            loadingDiv.show();
        } else {
            wrapper.find('.custom-loading').hide();
        }
    });
}

// Función para mejorar la accesibilidad del paginado
function improvePaginationAccessibility(table) {
    setTimeout(function() {
        var wrapper = table.closest('.dataTables_wrapper');
        var paginateButtons = wrapper.find('.dataTables_paginate .paginate_button');
        
        // Agregar atributos ARIA
        paginateButtons.each(function() {
            var button = $(this);
            var text = button.text().trim();
            
            if (text.includes('Primero')) {
                button.attr('aria-label', 'Ir a la primera página');
            } else if (text.includes('Último')) {
                button.attr('aria-label', 'Ir a la última página');
            } else if (text.includes('Siguiente')) {
                button.attr('aria-label', 'Ir a la siguiente página');
            } else if (text.includes('Anterior')) {
                button.attr('aria-label', 'Ir a la página anterior');
            } else if (!isNaN(text)) {
                button.attr('aria-label', 'Ir a la página ' + text);
            }
            
            // Agregar role si no existe
            if (!button.attr('role')) {
                button.attr('role', 'button');
            }
        });
        
        // Agregar navegación por teclado
        paginateButtons.on('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                $(this).click();
            }
        });
        
    }, 100);
}

// Función para agregar estadísticas avanzadas
function addAdvancedStats(table) {
    setTimeout(function() {
        var wrapper = table.closest('.dataTables_wrapper');
        var infoContainer = wrapper.find('.dataTables_info');
        
        // Crear contenedor para estadísticas adicionales
        var statsContainer = $('<div class="advanced-stats mt-2 text-xs text-gray-500 text-center"></div>');
        
        function updateStats() {
            var info = table.DataTable().page.info();
            var stats = `
                Página ${info.page + 1} de ${info.pages} | 
                ${info.recordsDisplay} registros mostrados de ${info.recordsTotal} total |
                ${info.recordsFiltered !== info.recordsTotal ? info.recordsFiltered + ' después del filtro' : 'Sin filtros aplicados'}
            `;
            statsContainer.html(stats);
        }
        
        // Insertar después del info
        infoContainer.after(statsContainer);
        
        // Actualizar en cada cambio
        table.on('draw.dt', updateStats);
        updateStats();
        
    }, 100);
}

// Función principal para aplicar todas las mejoras
function enhanceDataTablePagination(tableId) {
    var table = $('#' + tableId);
    
    if (table.length && table.DataTable) {
        // Aplicar todas las mejoras
        addQuickNavigation(table);
        addLoadingIndicators(table);
        improvePaginationAccessibility(table);
        addAdvancedStats(table);
        
        console.log('Mejoras de paginado aplicadas a la tabla:', tableId);
    }
}

// Auto-aplicar mejoras cuando el documento esté listo
$(document).ready(function() {
    // Aplicar mejoras a tablas existentes después de un pequeño delay
    setTimeout(function() {
        if ($('#clientes-table').length && $('#clientes-table').DataTable) {
            enhanceDataTablePagination('clientes-table');
        }
        
        if ($('#tabla-envios').length && $('#tabla-envios').DataTable) {
            enhanceDataTablePagination('tabla-envios');
        }
    }, 500);
});
