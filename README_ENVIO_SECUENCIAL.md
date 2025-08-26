# Sistema de Envío Secuencial de Preguntas - WhatsApp

## Descripción

Este sistema permite enviar encuestas de satisfacción por WhatsApp de forma secuencial, enviando una pregunta a la vez y esperando la respuesta antes de enviar la siguiente.

## Características

- ✅ Envío de preguntas una por una
- ✅ Respuestas automáticas según la pregunta actual
- ✅ Flujo conversacional natural
- ✅ Guardado automático de respuestas
- ✅ Mensaje de agradecimiento al completar
- ✅ Logs detallados para debugging

## Flujo de Funcionamiento

1. **Envío inicial**: Se envía solo la primera pregunta
2. **Respuesta del cliente**: El cliente responde la pregunta
3. **Procesamiento**: El sistema guarda la respuesta y envía la siguiente pregunta
4. **Repetición**: Se repite hasta completar las 4 preguntas
5. **Finalización**: Se envía mensaje de agradecimiento

## Preguntas de la Encuesta

### Pregunta 1 (Escala 0-10)
- **Texto**: "En una escala del 0 al 10, ¿qué probabilidad hay de que recomiende proser a un colega o contacto del sector construcción?"
- **Formato de respuesta**: Solo un número del 0 al 10

### Pregunta 2 (Texto libre)
- **Texto**: "¿Cuál es la razón principal de tu calificación?"
- **Formato de respuesta**: Texto libre

### Pregunta 3 (Opciones)
- **Texto**: "¿A qué tipo de obra se destinó este concreto?"
- **Opciones**: Vivienda unifamiliar, Edificio vertical, Obra vial, Obra industrial, Otro
- **Formato de respuesta**: Una de las opciones o descripción libre

### Pregunta 4 (Sugerencias)
- **Texto**: "¿Qué podríamos hacer para mejorar tu experiencia en futuras entregas?"
- **Formato de respuesta**: Texto libre o "N/A"

## Rutas de API

### Webhook Principal
```
POST /api/webhook-twilio
```
- Recibe respuestas reales de Twilio
- Procesa automáticamente y envía siguiente pregunta

### Webhook de Prueba
```
POST /api/webhook-test-clean
```
- Para testing y desarrollo
- Simula respuestas de WhatsApp
- Guarda en base de datos

### Consulta de Estado
```
GET /api/envio/estado?whatsapp_number=1234567890
```
- Consulta el estado actual de un envío
- Muestra pregunta actual y respuestas

## Comandos de Artisan

### Probar Envío Secuencial
```bash
php artisan test:envio-secuencial {envio_id}
```
- Envía la primera pregunta de una encuesta
- Útil para iniciar el flujo

### Simular Respuesta
```bash
php artisan simular:respuesta {envio_id} "respuesta_texto"
```
- Simula una respuesta del cliente
- Prueba el flujo completo sin WhatsApp real

## Estados del Envío

- **`enviado`**: Primera pregunta enviada
- **`en_proceso`**: Cliente respondiendo preguntas
- **`completado`**: Todas las preguntas respondidas
- **`error`**: Error en el envío

## Campos de Base de Datos

### Tabla `envios`
- `pregunta_actual`: Número de pregunta actual (1-4)
- `respuesta_1` a `respuesta_4`: Respuestas del cliente
- `estado`: Estado actual del envío
- `whatsapp_message`: Último mensaje enviado

### Tabla `chat_respuestas`
- `message_sid`: ID del mensaje de Twilio
- `from_number`: Número del cliente
- `body`: Contenido de la respuesta
- `twilio_data`: Datos completos del webhook

## Configuración de Twilio

Asegúrate de tener configurado en `.env`:
```env
TWILIO_ACCOUNT_SID=tu_account_sid
TWILIO_AUTH_TOKEN=tu_auth_token
TWILIO_WHATSAPP_FROM=tu_numero_whatsapp
```

## Ejemplo de Uso

### 1. Crear un envío
```php
$envio = Envio::create([
    'cliente_id' => $cliente->id,
    'estado' => 'pendiente'
]);
```

### 2. Enviar primera pregunta
```bash
php artisan test:envio-secuencial 1
```

### 3. Simular respuestas
```bash
php artisan simular:respuesta 1 "8"
php artisan simular:respuesta 1 "Excelente servicio"
php artisan simular:respuesta 1 "Vivienda unifamiliar"
php artisan simular:respuesta 1 "Más horarios de entrega"
```

### 4. Verificar estado
```bash
curl "http://localhost/api/envio/estado?whatsapp_number=1234567890"
```

## Logs y Debugging

El sistema genera logs detallados en `storage/logs/laravel.log`:
- Envío de preguntas
- Recepción de respuestas
- Procesamiento de webhooks
- Errores y excepciones

## Notas Importantes

- Las respuestas se procesan automáticamente
- El sistema maneja números de WhatsApp con prefijo "whatsapp:"
- Se recomienda probar con el webhook de prueba antes de usar en producción
- Los comandos de Artisan son útiles para testing y debugging

## Solución de Problemas

### Error: "No se encontró envío"
- Verificar que el envío existe
- Confirmar que el número de WhatsApp coincide
- Revisar el estado del envío

### Error: "Error procesando respuesta"
- Verificar logs de Laravel
- Confirmar configuración de Twilio
- Verificar conectividad de base de datos

### Preguntas no se envían automáticamente
- Verificar que el webhook esté configurado correctamente
- Revisar logs de Twilio
- Confirmar que la URL del webhook sea accesible
