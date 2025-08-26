# Configuración de Twilio para WhatsApp

## Variables de Entorno Requeridas

Para que la funcionalidad de WhatsApp funcione correctamente, necesitas configurar las siguientes variables en tu archivo `.env`:

```env
# Configuración de Twilio
TWILIO_ACCOUNT_SID=your_twilio_account_sid_here
TWILIO_AUTH_TOKEN=your_twilio_auth_token_here
TWILIO_WHATSAPP_FROM=your_twilio_whatsapp_number_here
```

## Cómo Obtener las Credenciales

### 1. TWILIO_ACCOUNT_SID
- Ve a [Twilio Console](https://console.twilio.com/)
- Inicia sesión en tu cuenta
- El Account SID se muestra en el dashboard principal

### 2. TWILIO_AUTH_TOKEN
- En la misma página del dashboard
- Haz clic en "Show" para revelar el Auth Token
- Copia el valor

### 3. TWILIO_WHATSAPP_FROM
- Este debe ser el número de WhatsApp de Twilio que tienes configurado
- Formato: `+1234567890` (sin el prefijo `whatsapp:`)

## Verificar la Configuración

Después de configurar las variables, puedes verificar que todo esté funcionando con:

```bash
php artisan twilio:verificar
```

Este comando verificará:
- Que todas las variables estén configuradas
- Que las credenciales sean válidas
- Que puedas enviar un mensaje de prueba

## Solución de Problemas

### Error: "The to field is required"
Este error generalmente indica:
1. **Variables de entorno no configuradas**: Verifica que todas las variables estén en tu archivo `.env`
2. **Formato incorrecto del número**: Asegúrate de que el número tenga el formato correcto (+52 para México)
3. **Configuración de Twilio incorrecta**: Verifica que las credenciales sean válidas

### Error: "Unauthorized"
- Verifica que `TWILIO_ACCOUNT_SID` y `TWILIO_AUTH_TOKEN` sean correctos
- Asegúrate de que la cuenta de Twilio esté activa

### Error: "Invalid phone number"
- Verifica el formato del número de teléfono
- Asegúrate de incluir el código de país (+52 para México)

## Ejemplo de Número Válido

Para México, el formato debe ser:
```
+529961100930
```

Donde:
- `+52` = código de país de México
- `9961100930` = número de teléfono local
