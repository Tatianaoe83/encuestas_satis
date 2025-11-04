/**
 * Script para solucionar problemas de carga de datos en móviles
 * Fuerza la recarga de datos cuando se detecta que no se han cargado correctamente
 */

(function() {
    'use strict';
    
    // Configuración
    const RETRY_DELAY = 1000; // 1 segundo
    const MAX_RETRIES = 3;
    const DATA_CHECK_DELAY = 500; // 500ms después de que se carga la página
    
    let retryCount = 0;
    
    /**
     * Verificar si los datos se han cargado correctamente
     */
    function verificarDatosCargados() {
        // Verificar si hay elementos con datos en el dashboard
        const elementosConDatos = [
            '.text-2xl', // Números principales
            '.text-3xl', // Números grandes
            'table tbody tr', // Filas de tabla
            '.space-y-3', // Elementos de lista
            '.grid' // Elementos de grid
        ];
        
        let datosEncontrados = 0;
        
        elementosConDatos.forEach(selector => {
            const elementos = document.querySelectorAll(selector);
            elementos.forEach(elemento => {
                // Verificar si el elemento tiene contenido visible
                if (elemento.textContent.trim() && 
                    elemento.offsetHeight > 0 && 
                    elemento.offsetWidth > 0) {
                    datosEncontrados++;
                }
            });
        });
        
        return datosEncontrados > 0;
    }
    
    /**
     * Forzar recarga de la página
     */
    function forzarRecarga() {
        if (retryCount < MAX_RETRIES) {
            retryCount++;
      
            
            // Agregar parámetro de timestamp para evitar caché
            const url = new URL(window.location);
            url.searchParams.set('_t', Date.now());
            
            window.location.href = url.toString();
        } else {
            console.warn('⚠️ Máximo de reintentos alcanzado para carga de datos');
        }
    }
    
    /**
     * Mostrar indicador de carga
     */
    function mostrarIndicadorCarga() {
        const indicador = document.getElementById('mobile-loading-indicator');
        if (indicador) {
            indicador.classList.remove('-translate-y-full');
        }
    }
    
    /**
     * Ocultar indicador de carga
     */
    function ocultarIndicadorCarga() {
        const indicador = document.getElementById('mobile-loading-indicator');
        if (indicador) {
            indicador.classList.add('-translate-y-full');
        }
    }
    
    /**
     * Verificar y recargar si es necesario
     */
    function verificarYCargar() {
        // Mostrar indicador de carga
        mostrarIndicadorCarga();
        
        // Esperar un poco para que se carguen los datos
        setTimeout(() => {
            if (!verificarDatosCargados()) {
             
                forzarRecarga();
            } else {
            
                ocultarIndicadorCarga();
            }
        }, DATA_CHECK_DELAY);
    }
    
    /**
     * Detectar si es un dispositivo móvil
     */
    function esDispositivoMovil() {
        return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ||
               window.innerWidth <= 768;
    }
    
    /**
     * Inicializar verificación de datos
     */
    function inicializar() {
        // Solo ejecutar en dispositivos móviles
        if (!esDispositivoMovil()) {
            return;
        }
        
     
        
        // Verificar cuando el DOM esté listo
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', verificarYCargar);
        } else {
            verificarYCargar();
        }
        
        // También verificar cuando la página se vuelve visible
        document.addEventListener('visibilitychange', () => {
            if (!document.hidden) {
                setTimeout(verificarYCargar, 100);
            }
        });
        
        // Verificar cuando la ventana obtiene foco
        window.addEventListener('focus', () => {
            setTimeout(verificarYCargar, 100);
        });
    }
    
    // Inicializar cuando el script se carga
    inicializar();
    
    // Exponer funciones para debugging
    window.MobileDataReload = {
        verificar: verificarYCargar,
        forzarRecarga: forzarRecarga,
        esMovil: esDispositivoMovil
    };
    
})();
