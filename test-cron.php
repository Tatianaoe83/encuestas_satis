<?php
/**
 * Archivo de prueba para verificar que el cron funciona en HostGator
 * 
 * INSTRUCCIONES:
 * 1. Sube este archivo a tu directorio raíz en HostGator
 * 2. Configura un cron job temporal:
 *    * * * * * /usr/bin/php /home/tu_usuario/public_html/test-cron.php
 * 3. Espera 1-2 minutos y revisa si se creó el archivo cron-test.txt
 * 4. Si funciona, puedes eliminar este archivo y configurar el cron real
 */

// Crear el archivo de prueba
$archivo = __DIR__ . '/cron-test.txt';
$timestamp = date('Y-m-d H:i:s');
$contenido = "Cron ejecutado exitosamente: {$timestamp}\n";

// Intentar escribir al archivo
if (file_put_contents($archivo, $contenido, FILE_APPEND | LOCK_EX)) {
    echo "✅ Cron funcionando correctamente - {$timestamp}\n";
} else {
    echo "❌ Error escribiendo archivo de prueba - {$timestamp}\n";
}

// Mostrar información del sistema
echo "PHP Version: " . PHP_VERSION . "\n";
echo "Current Directory: " . __DIR__ . "\n";
echo "File Owner: " . get_current_user() . "\n";

// Verificar si Laravel está disponible
if (file_exists(__DIR__ . '/artisan')) {
    echo "✅ Laravel detectado - artisan encontrado\n";
} else {
    echo "❌ Laravel no detectado - artisan no encontrado\n";
}

// Verificar permisos de escritura
if (is_writable(__DIR__)) {
    echo "✅ Permisos de escritura OK\n";
} else {
    echo "❌ Sin permisos de escritura\n";
}
?>
