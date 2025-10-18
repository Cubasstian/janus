# Kit UI - Sistema de Diseño Janus

## 📁 Archivo: `dist/css/kit-ui.css`

Sistema de componentes modulares basado en el Kit UI (Anexo 9) para el proyecto Janus. Este archivo debe ser incluido en todas las vistas para mantener consistencia visual.

## 🚀 Instalación

El Kit UI ya está incluido globalmente en `views/header.php`:

```html
<!-- Kit UI - Sistema de Diseño Janus -->
<link rel="stylesheet" href="dist/css/kit-ui.css">
```

**Todas las vistas automáticamente tienen acceso a estos estilos.**

## 🎨 Variables CSS Disponibles

### Colores Principales
```css
--color-primary: #518711;       /* Verde Principal */
--color-secondary: #384E08;     /* Verde Oscuro */
--color-energia: #F98200;       /* Naranja (Hover) */
```

### Colores de Servicios
```css
--color-agua: #48B9EE;          /* Azul Claro */
--color-teleco: #C48DFF;        /* Violeta */
```

### Colores Funcionales
```css
--color-success: #518711;       /* Éxito */
--color-warning: #F8CF3F;       /* Advertencia */
--color-info: #52BFD1;          /* Información */
--color-danger: #E24F4F;        /* Error */
```

### Estructura
```css
--border-radius-pill: 999px;    /* Botones redondeados */
--border-radius-module: 16px;   /* Cards y módulos */
--border-radius-input: 8px;     /* Inputs */
```

## 🧩 Componentes Disponibles

### 1. Botones

```html
<!-- Botón Primario -->
<button class="btn btn-primary">Guardar</button>

<!-- Botón Outline -->
<button class="btn btn-outline-primary">Cancelar</button>

<!-- Botones de Estado -->
<button class="btn btn-success">Éxito</button>
<button class="btn btn-warning">Advertencia</button>
<button class="btn btn-danger">Eliminar</button>
<button class="btn btn-info">Información</button>

<!-- Tamaños -->
<button class="btn btn-primary btn-sm">Pequeño</button>
<button class="btn btn-primary">Normal</button>
<button class="btn btn-primary btn-lg">Grande</button>
```

### 2. Formularios

```html
<div class="form-group">
    <label class="form-label">Nombre</label>
    <input type="text" class="form-control" placeholder="Ingrese su nombre">
</div>

<!-- Estados de validación -->
<input type="email" class="form-control is-invalid">
<div class="invalid-feedback">Email inválido</div>

<input type="text" class="form-control is-valid">
<div class="valid-feedback">Correcto</div>
```

### 3. Cards y Módulos

```html
<div class="card">
    <div class="card-header">
        <h3>Título de la Card</h3>
    </div>
    <div class="card-body">
        <p>Contenido de la card</p>
    </div>
    <div class="card-footer">
        <button class="btn btn-primary">Acción</button>
    </div>
</div>
```

### 4. Badges

```html
<span class="badge badge-primary">Primario</span>
<span class="badge badge-success">Éxito</span>
<span class="badge badge-warning">Advertencia</span>
<span class="badge badge-danger">Error</span>

<!-- Pills (redondeados) -->
<span class="badge badge-pill badge-info">Información</span>
```

### 5. Tablas

```html
<table class="table-kit-ui">
    <thead>
        <tr>
            <th>Columna 1</th>
            <th>Columna 2</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Dato 1</td>
            <td>Dato 2</td>
        </tr>
    </tbody>
</table>
```

## 🎯 Clases de Utilidad

### Colores de Texto
```html
<p class="text-primary">Texto verde principal</p>
<p class="text-secondary">Texto verde oscuro</p>
<p class="text-success">Texto de éxito</p>
<p class="text-warning">Texto de advertencia</p>
<p class="text-danger">Texto de error</p>
<p class="text-info">Texto de información</p>
<p class="text-muted">Texto secundario</p>
```

### Espaciado
```html
<div class="mb-3">Margen inferior 3</div>
<div class="mt-4">Margen superior 4</div>
<div class="p-0">Sin padding</div>
```

### Flexbox
```html
<div class="d-flex align-items-center justify-content-between">
    <span>Izquierda</span>
    <span>Derecha</span>
</div>
```

### Texto
```html
<p class="text-center">Centrado</p>
<p class="font-weight-bold">Negrita</p>
```

## 🔧 Compatibilidad

El Kit UI mantiene compatibilidad con:
- ✅ Bootstrap 4 (AdminLTE)
- ✅ Clases existentes del proyecto
- ✅ Componentes `.btn-action`, `.card-header-clean`

## 💡 Cómo Usar en Nuevas Vistas

### 1. Para vistas simples
No necesitas CSS adicional, solo usa las clases del Kit UI:

```html
<?php require('views/header.php');?>

<div class="content-wrapper">
    <div class="card">
        <div class="card-header">
            <h3>Mi Nueva Vista</h3>
        </div>
        <div class="card-body">
            <button class="btn btn-primary">Acción</button>
        </div>
    </div>
</div>

<?php require('views/footer.php');?>
```

### 2. Para estilos específicos
Solo agrega CSS para elementos únicos de tu vista:

```html
<?php require('views/header.php');?>

<style>
/* Solo estilos específicos de esta vista */
.mi-componente-especial {
    background: var(--color-primary);
    border-radius: var(--border-radius-module);
}
</style>

<!-- Tu contenido HTML -->

<?php require('views/footer.php');?>
```

## ⚡ Beneficios

- ✅ **Consistencia**: Todas las vistas usan los mismos colores y estilos
- ✅ **Mantenibilidad**: Un solo archivo para actualizar el diseño
- ✅ **Performance**: CSS cacheado globalmente
- ✅ **Escalabilidad**: Fácil agregar nuevos componentes
- ✅ **Compatibilidad**: Funciona con el sistema existente

## 🎨 Personalización

Para modificar colores o estilos globales, edita `dist/css/kit-ui.css` y los cambios se aplicarán a toda la aplicación automáticamente.

---

**Archivo creado:** `dist/css/kit-ui.css`  
**Incluido en:** `views/header.php`  
**Disponible en:** Todas las vistas del sistema