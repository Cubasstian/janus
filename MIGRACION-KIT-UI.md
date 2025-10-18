# Script de Migración al Kit UI

## Estado Actual

### ✅ **YA FUNCIONA (Sin cambios necesarios):**
El Kit UI está incluido globalmente en `views/header.php`, por lo que **TODAS las vistas ya tienen acceso** a:
- Variables CSS del Kit UI
- Componentes (botones, cards, formularios)
- Clases de utilidad

### 📊 **Archivos Actualizados:**

1. **`dist/css/kit-ui.css`** - ✅ Creado y activo
2. **`views/header.php`** - ✅ Incluye Kit UI globalmente  
3. **`dist/css/proceso-moderno.css`** - ✅ Actualizado con variables Kit UI
4. **`views/proceso/supervisor.php`** - ✅ Limpiado de CSS duplicado
5. **`views/necesidades/listar.php`** - ✅ Simplificado

### 🔄 **Archivos que se pueden optimizar (opcional):**

Las siguientes vistas tienen CSS local que **duplica** funcionalidad del Kit UI:

#### **Proceso (con :root duplicado):**
- `views/proceso/rp.php`
- `views/proceso/minuta.php` 
- `views/proceso/actaInicio.php`
- `views/proceso/arl.php`
- `views/proceso/eep_evaluar.php`
- `views/proceso/crearTercero.php`
- `views/proceso/fr.php`

#### **Otras vistas (con <style> local):**
- `views/proceso/ocuparNecesidad.php`
- `views/configuracion/honorarios.php`
- `views/configuracion/ciudades.php`
- `views/configuracion/usuarios.php`
- `views/configuracion/vigencias.php`
- `views/imputaciones/gestionar.php`
- `views/personas/gestionar.php`

## 🚀 **Recomendaciones:**

### **Opción 1: Usar como está (Recomendado)**
- ✅ **Todas las vistas ya funcionan** con Kit UI
- ✅ **No hay conflictos** - CSS local se sobrepone cuando es necesario
- ✅ **Zero downtime** - no se rompe nada

### **Opción 2: Optimización gradual**
Puedes limpiar vistas una por una cuando las edites:

1. **Remover variables duplicadas**:
   ```css
   <!-- REMOVER -->
   <style>
   :root {
       --accent-color: #007bff;
   }
   </style>
   ```

2. **Cambiar colores hardcoded por variables**:
   ```css
   <!-- ANTES -->
   background: #007bff;
   
   <!-- DESPUÉS -->
   background: var(--color-primary);
   ```

3. **Usar clases del Kit UI**:
   ```html
   <!-- ANTES -->
   <button style="background: #007bff;">Botón</button>
   
   <!-- DESPUÉS -->
   <button class="btn btn-primary">Botón</button>
   ```

## ✅ **Conclusión:**

**El Kit UI YA ESTÁ FUNCIONANDO en todo el sistema.** No necesitas hacer nada más. Las optimizaciones son opcionales y se pueden hacer gradualmente.

### **Para nuevas vistas:**
```html
<?php require('views/header.php');?>
<!-- Ya tienes acceso completo al Kit UI -->
<button class="btn btn-primary">¡Funciona!</button>
<?php require('views/footer.php');?>
```

### **Para vistas existentes:**
- ✅ Ya funcionan con Kit UI
- ✅ CSS local se mantiene para casos específicos
- 🔄 Se pueden optimizar cuando se editen (opcional)