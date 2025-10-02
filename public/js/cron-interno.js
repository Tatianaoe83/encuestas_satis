/**
 * Cron Interno Automático
 * Este script ejecuta el cron interno automáticamente cuando se carga la página
 */

(function() {
    'use strict';
    
    // Configuración
    const CRON_URL = '/cron-interno/ejecutar';
    const INTERVAL_MINUTES = 5; // Intervalo mínimo entre ejecuciones
    const MAX_RETRIES = 3;
    
    let retryCount = 0;
    let lastExecution = null;
    
    /**
     * Ejecutar cron interno
     */
    async function ejecutarCronInterno() {
        try {
            const response = await fetch(CRON_URL, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            
            const data = await response.json();
            
            if (data.success) {
                console.log('✅ Cron interno ejecutado:', data.message);
                lastExecution = new Date();
                retryCount = 0;
                
                // Log de timers cancelados y recordatorios enviados si los hay
                if (data.data) {
                    if (data.data.timers_cancelados > 0) {
                        console.log(`🕐 ${data.data.timers_cancelados} timers cancelados automáticamente`);
                    }
                    if (data.data.recordatorios_enviados > 0) {
                        console.log(`📨 ${data.data.recordatorios_enviados} recordatorios enviados`);
                    }
                }
            } else {
                console.warn('⚠️ Cron interno no ejecutado:', data.message);
            }
            
        } catch (error) {
            console.error('❌ Error ejecutando cron interno:', error.message);
            retryCount++;
            
            // Reintentar si no se han agotado los intentos
            if (retryCount < MAX_RETRIES) {
                setTimeout(ejecutarCronInterno, 30000); // Reintentar en 30 segundos
            }
        }
    }
    
    /**
     * Verificar si debe ejecutarse el cron
     */
    function debeEjecutarCron() {
        // Si es la primera vez, ejecutar inmediatamente
        if (!lastExecution) {
            return true;
        }
        
        // Verificar si han pasado al menos 5 minutos
        const ahora = new Date();
        const minutosTranscurridos = (ahora - lastExecution) / (1000 * 60);
        
        return minutosTranscurridos >= INTERVAL_MINUTES;
    }
    
    /**
     * Inicializar cron interno
     */
    function inicializarCronInterno() {
        console.log('🚀 Inicializando cron interno automático...');
        
        // Ejecutar inmediatamente si es necesario
        if (debeEjecutarCron()) {
            ejecutarCronInterno();
        }
        
        // Configurar ejecución periódica cada 5 minutos
        setInterval(() => {
            if (debeEjecutarCron()) {
                ejecutarCronInterno();
            }
        }, INTERVAL_MINUTES * 60 * 1000);
        
        // Ejecutar también cuando la página se vuelve visible
        document.addEventListener('visibilitychange', () => {
            if (!document.hidden && debeEjecutarCron()) {
                ejecutarCronInterno();
            }
        });
        
        // Ejecutar cuando el usuario regresa a la página
        window.addEventListener('focus', () => {
            if (debeEjecutarCron()) {
                ejecutarCronInterno();
            }
        });
    }
    
    /**
     * Verificar estado del cron
     */
    async function verificarEstadoCron() {
        try {
            const response = await fetch('/cron-interno/estado', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (response.ok) {
                const data = await response.json();
                console.log('📊 Estado del cron interno:', data.data);
            }
        } catch (error) {
            console.error('Error verificando estado del cron:', error.message);
        }
    }
    
    // Inicializar cuando el DOM esté listo
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', inicializarCronInterno);
    } else {
        inicializarCronInterno();
    }
    
    // Exponer funciones para debugging
    window.CronInterno = {
        ejecutar: ejecutarCronInterno,
        verificarEstado: verificarEstadoCron,
        forzarEjecucion: async () => {
            try {
                const response = await fetch('/cron-interno/forzar', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    }
                });
                
                const data = await response.json();
                console.log('🔄 Cron forzado:', data);
                return data;
            } catch (error) {
                console.error('Error forzando cron:', error.message);
            }
        }
    };
    
})();
