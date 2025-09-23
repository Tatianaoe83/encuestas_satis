# 📋 Encuesta Web - Sistema de Encuestas de Satisfacción

## 🎯 Descripción

Se ha implementado una funcionalidad que permite a los clientes responder las encuestas de satisfacción directamente desde el navegador web, sin necesidad de WhatsApp.

## 🚀 Características

### ✨ Funcionalidades Principales
- **Interfaz web moderna y responsive** con diseño atractivo
- **Progreso visual** de la encuesta con barra de progreso
- **Validación en tiempo real** de las respuestas
- **Guardado automático** de respuestas en la base de datos
- **Estados de encuesta** (pendiente, en proceso, completada)
- **Manejo de errores** con mensajes informativos

### 📱 Diseño Responsive
- Optimizado para dispositivos móviles y desktop
- Interfaz intuitiva y fácil de usar
- Animaciones suaves y transiciones elegantes

## 🔗 URLs de Acceso

### Estructura de URLs
```
https://tu-dominio.com/encuesta/{ID_ENVIO}
```

### Ejemplo
```
https://encuestas-satis.local/encuesta/1
```

## 📊 Estructura de la Encuesta

### Pregunta 1: Escala 1-10 (5 subpreguntas)
1. **1.1** Calidad del producto
2. **1.2** Puntualidad de entrega  
3. **1.3** Trato del asesor comercial
4. **1.4** Precio
5. **1.5** Rapidez en programación

### Pregunta 2: Recomendación (Sí/No)
- ¿Recomendarías a Konkret?

### Pregunta 3: Comentarios (Solo si Pregunta 2 = "No")
- ¿Podrías contarnos qué aspectos crees que podríamos mejorar?

## 🛠️ Implementación Técnica

### Archivos Creados/Modificados

#### Controlador
- `app/Http/Controllers/EncuestaController.php` - Lógica de negocio

#### Vistas
- `resources/views/encuesta/mostrar.blade.php` - Interfaz principal
- `resources/views/encuesta/completada.blade.php` - Página de finalización
- `resources/views/encuesta/error.blade.php` - Manejo de errores

#### Rutas
- `routes/web.php` - Rutas agregadas:
  - `GET /encuesta/{idenvio}` - Mostrar encuesta
  - `POST /encuesta/{idenvio}/responder` - Procesar respuesta

#### Interfaz de Usuario
- `resources/views/envios/index.blade.php` - Botón "Enlace Web" agregado

### Base de Datos
Utiliza la tabla `envios` existente con los siguientes campos:
- `respuesta_1_1` a `respuesta_1_5` - Respuestas escala 1-10
- `respuesta_2` - Respuesta Sí/No
- `respuesta_3` - Comentarios adicionales
- `promedio_respuesta_1` - Promedio calculado automáticamente
- `pregunta_actual` - Pregunta actual del proceso
- `estado` - Estado de la encuesta

## 🎨 Características de la Interfaz

### Diseño Visual
- **Gradientes modernos** en colores azul/púrpura
- **Iconos FontAwesome** para mejor UX
- **Animaciones CSS** suaves y profesionales
- **Estados interactivos** (hover, selected, disabled)

### Experiencia de Usuario
- **Barra de progreso** visual
- **Validación en tiempo real** de respuestas
- **Mensajes de confirmación** y error
- **Loading states** durante el procesamiento
- **Responsive design** para todos los dispositivos

## 📋 Cómo Usar

### Para Administradores
1. Ve a la sección "Gestión de Envíos"
2. Busca el envío deseado
3. Haz clic en el botón "Enlace Web" (ícono de enlace)
4. El enlace se copia automáticamente al portapapeles
5. Comparte el enlace con el cliente

### Para Clientes
1. Abre el enlace recibido en cualquier navegador
2. Completa las preguntas una por una
3. Las respuestas se guardan automáticamente
4. Al finalizar, verás una página de confirmación

## 🔒 Seguridad

### Validaciones Implementadas
- **Validación de envío existente** - Verifica que el envío existe
- **Validación de respuestas** - Formato correcto según tipo de pregunta
- **Protección CSRF** - Token de seguridad en formularios
- **Manejo de errores** - Respuestas apropiadas para errores

### Estados de Encuesta
- **Pendiente** - Encuesta no iniciada
- **En proceso** - Encuesta parcialmente completada
- **Completado** - Encuesta finalizada
- **Error** - Problemas de carga o validación

## 🚀 Ventajas

### Para el Negocio
- **Mayor tasa de respuesta** - Interfaz más accesible
- **Mejor experiencia** - Diseño profesional y moderno
- **Flexibilidad** - No depende solo de WhatsApp
- **Accesibilidad** - Funciona en cualquier dispositivo

### Para los Clientes
- **Fácil de usar** - Interfaz intuitiva
- **Rápido** - Respuestas inmediatas
- **Confiable** - Guardado automático
- **Accesible** - No requiere aplicación específica

## 🔧 Mantenimiento

### Monitoreo
- Revisar logs de errores en `storage/logs/laravel.log`
- Verificar estado de encuestas en la base de datos
- Monitorear rendimiento de la aplicación

### Actualizaciones Futuras
- Posibilidad de personalizar preguntas
- Integración con más canales de comunicación
- Análisis avanzado de respuestas
- Notificaciones por email

## 📞 Soporte

Para problemas o dudas sobre la funcionalidad de encuestas web:
1. Verificar que el enlace sea correcto
2. Revisar logs de la aplicación
3. Contactar al equipo de desarrollo

---

**Desarrollado para Konkret** - Sistema de Encuestas de Satisfacción
