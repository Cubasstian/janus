# SISTEMA DE TABLA ESTÁNDAR JANUS
## Guía de Implementación para Desarrolladores

### 📋 DESCRIPCIÓN
Sistema unificado para todas las páginas de procesos en Janus que proporciona:
- **Interfaz consistente** con diseño moderno y responsive
- **Filtros avanzados** con búsqueda en tiempo real
- **Tabla estandarizada** con DataTables integrado
- **Funcionalidades reutilizables** para todos los procesos

---

## 🚀 IMPLEMENTACIÓN RÁPIDA

### 1. ESTRUCTURA HTML BÁSICA
```html
<?php require('views/header.php');?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h3 class="card-title">
                        <i class="fas fa-[ICONO]"></i> [TÍTULO DE LA PÁGINA]
                    </h3>
                </div>
                
                <div class="card-body">
                    <!-- PANEL DE FILTROS -->
                    <div class="filter-section-standard">
                        <div class="row align-items-end">
                            <!-- Búsqueda General -->
                            <div class="col-md-3">
                                <label class="form-label-standard">Búsqueda General</label>
                                <input type="text" 
                                       id="filtro-busqueda-estandar" 
                                       class="form-control form-control-standard" 
                                       placeholder="Buscar...">
                            </div>
                            
                            <!-- Filtro por Gerencia -->
                            <div class="col-md-3">
                                <label class="form-label-standard">Gerencia</label>
                                <select id="filtro-gerencia-estandar" 
                                        class="form-control form-control-standard">
                                    <option value="">Todas las gerencias</option>
                                </select>
                            </div>
                            
                            <!-- [FILTROS ADICIONALES AQUÍ] -->
                            
                            <!-- Botón Limpiar -->
                            <div class="col-md-2">
                                <button type="button" 
                                        id="btn-limpiar-filtros-estandar" 
                                        class="btn btn-filter-clear-standard w-100">
                                    <i class="fas fa-eraser"></i> Limpiar
                                </button>
                            </div>
                            
                            <!-- Contador -->
                            <div class="col-md-2 text-right">
                                <span class="form-label-standard d-block">Registros</span>
                                <span id="contador-registros-estandar" class="badge badge-count-standard">0</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- TABLA -->
                    <div class="table-responsive">
                        <table id="tabla-procesos-estandar" class="table table-standard table-striped table-hover">
                            <thead>
                                <tr>
                                    <!-- [DEFINIR COLUMNAS ESPECÍFICAS] -->
                                    <th>ID</th>
                                    <th>Campo 1</th>
                                    <th>Campo 2</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
```

### 2. JAVASCRIPT OBLIGATORIO
```javascript
// FUNCIÓN REQUERIDA: Renderizar cada fila
function renderizarFilaTablaPersonalizada(item) {
    const diasTranscurridos = TablaEstandarJanus.calcularDiasTranscurridos(item.fecha_creacion);
    const badgeDias = TablaEstandarJanus.generarBadgeDias(diasTranscurridos);
    
    return `
        <tr class="table-row-hover-standard" data-id="${item.id}">
            <td>${item.id}</td>
            <td>${item.campo1 || '-'}</td>
            <td>${item.campo2 || '-'}</td>
            <td>
                ${TablaEstandarJanus.generarBotonAccion('Ver', 'btn-info-standard', `verDetalle(${item.id})`, 'fas fa-eye')}
                ${TablaEstandarJanus.generarBotonAccion('Editar', 'btn-warning-standard', `editarRegistro(${item.id})`, 'fas fa-edit')}
            </td>
        </tr>
    `;
}

// INICIALIZACIÓN
$(document).ready(function() {
    TablaEstandarJanus.inicializar();
    cargarDatosPagina();
});

// CARGAR DATOS
function cargarDatosPagina() {
    TablaEstandarJanus.mostrarCarga();
    
    enviarPeticion('[CONTROLADOR]', '[METODO]', {}, function(respuesta) {
        if (respuesta.success && respuesta.data) {
            TablaEstandarJanus.cargarDatos(respuesta.data);
        } else {
            TablaEstandarJanus.cargarDatos([]);
        }
    });
}
```

---

## 🎛️ CONFIGURACIONES AVANZADAS

### Configuración Personalizada
```javascript
TablaEstandarJanus.inicializar({
    rowsPerPage: 50,           // Registros por página
    tablaId: '#mi-tabla',      // ID personalizado de tabla
    // Otras configuraciones...
});
```

### Filtros Adicionales
```javascript
// Agregar filtro personalizado
function configurarEventosPersonalizados() {
    $('#mi-filtro-personalizado').on('change', function() {
        aplicarFiltrosPersonalizados();
    });
}

// Función de filtrado personalizada
function aplicarFiltrosPersonalizados() {
    const textoBusqueda = $('#filtro-busqueda-estandar').val().toLowerCase().trim();
    const gerenciaSeleccionada = $('#filtro-gerencia-estandar').val();
    const miFiltroPers = $('#mi-filtro-personalizado').val();
    
    let datosFiltrados = [...TablaEstandarJanus.datosOriginales];
    
    // Aplicar filtros base
    if (textoBusqueda) {
        datosFiltrados = datosFiltrados.filter(item => {
            return Object.values(item).some(valor => {
                return String(valor).toLowerCase().includes(textoBusqueda);
            });
        });
    }
    
    if (gerenciaSeleccionada) {
        datosFiltrados = datosFiltrados.filter(item => {
            return String(item.gerencia_id) === String(gerenciaSeleccionada);
        });
    }
    
    // Aplicar filtro personalizado
    if (miFiltroPers) {
        datosFiltrados = datosFiltrados.filter(item => {
            return item.campo_especifico === miFiltroPers;
        });
    }
    
    TablaEstandarJanus.renderizarTabla(datosFiltrados);
}

// Sobrescribir función de filtros
TablaEstandarJanus.aplicarFiltros = aplicarFiltrosPersonalizados;
```

---

## 🎨 CLASES CSS DISPONIBLES

### Elementos de Formulario
- `.form-control-standard` - Campos de entrada modernos
- `.form-label-standard` - Etiquetas estandarizadas
- `.filter-section-standard` - Panel de filtros

### Botones
- `.btn-action-standard` - Botón base
- `.btn-success-standard` - Botón verde (éxito)
- `.btn-warning-standard` - Botón amarillo (advertencia)
- `.btn-danger-standard` - Botón rojo (peligro)
- `.btn-info-standard` - Botón azul (información)
- `.btn-filter-clear-standard` - Botón limpiar filtros

### Tabla
- `.table-standard` - Tabla estandarizada
- `.table-row-hover-standard` - Filas con hover
- `.badge-dias-standard` - Badge para días transcurridos
- `.badge-count-standard` - Badge contador

---

## 🔧 FUNCIONES ÚTILES DEL SISTEMA

### Manejo de Fechas
```javascript
// Calcular días transcurridos
const dias = TablaEstandarJanus.calcularDiasTranscurridos('2024-01-01');

// Formatear fecha
const fechaFormateada = TablaEstandarJanus.formatearFecha('2024-01-01 10:30:00');

// Generar badge de días con colores
const badgeDias = TablaEstandarJanus.generarBadgeDias(15);
```

### Generación de Botones
```javascript
// Botón básico
const boton = TablaEstandarJanus.generarBotonAccion(
    'Texto',                    // Texto del botón
    'btn-success-standard',     // Clase CSS
    'miFuncion(123)',          // Función onclick
    'fas fa-check'             // Icono (opcional)
);
```

### Control de Datos
```javascript
// Cargar datos en la tabla
TablaEstandarJanus.cargarDatos(arrayDatos);

// Mostrar estado de carga
TablaEstandarJanus.mostrarCarga();

// Limpiar todos los filtros
TablaEstandarJanus.limpiarFiltros();

// Actualizar contador
TablaEstandarJanus.actualizarContador(25);
```

---

## 📁 EJEMPLOS DE IMPLEMENTACIÓN

### 1. Página Simple (Solo tabla básica)
```html
<!-- Ver: templates/plantilla-tabla-estandar.html -->
```

### 2. Página con Modal (Como EEP)
```html
<!-- Ver: views/proceso/eep_evaluar_nuevo.php -->
```

### 3. Página con Filtros Múltiples
```javascript
// Agregar en el HTML filtros adicionales
<div class="col-md-2">
    <label class="form-label-standard">Estado</label>
    <select id="filtro-estado" class="form-control form-control-standard">
        <option value="">Todos</option>
        <option value="activo">Activo</option>
        <option value="inactivo">Inactivo</option>
    </select>
</div>

// Configurar en JavaScript
$('#filtro-estado').on('change', function() {
    aplicarFiltrosPersonalizados();
});
```

---

## ✅ CHECKLIST DE IMPLEMENTACIÓN

- [ ] HTML estructura básica implementada
- [ ] Función `renderizarFilaTablaPersonalizada()` definida
- [ ] Función `cargarDatosPagina()` implementada
- [ ] IDs estándar utilizados (`#tabla-procesos-estandar`, etc.)
- [ ] Inicialización del sistema en `$(document).ready()`
- [ ] Filtros adicionales configurados (si aplica)
- [ ] Funciones de acción implementadas
- [ ] Estilos personalizados agregados (si aplica)
- [ ] Pruebas de funcionalidad realizadas

---

## 🚨 NOTAS IMPORTANTES

1. **IDs Obligatorios**: Usar los IDs estándar para que el sistema funcione automáticamente
2. **Función Renderizado**: La función `renderizarFilaTablaPersonalizada()` es OBLIGATORIA
3. **Datos Backend**: Asegurar que el backend retorne array de objetos en `respuesta.data`
4. **Responsive**: El sistema es responsive por defecto
5. **Performance**: DataTables maneja automáticamente la paginación

---

## 🔄 MIGRACIÓN DE PÁGINAS EXISTENTES

### Pasos para convertir página existente:
1. Reemplazar estructura HTML con plantilla estándar
2. Mantener lógica de negocio específica
3. Adaptar función de renderizado
4. Configurar filtros adicionales
5. Probar funcionalidad completa

### Ejemplo de Migración:
```javascript
// ANTES (código específico)
function cargarTablaPersonalizada() {
    // Lógica específica compleja...
}

// DESPUÉS (usando sistema estándar)
function cargarDatosPagina() {
    TablaEstandarJanus.mostrarCarga();
    enviarPeticion('controlador', 'metodo', {}, function(respuesta) {
        TablaEstandarJanus.cargarDatos(respuesta.data);
    });
}
```

---

## 📞 SOPORTE

Para dudas o problemas con la implementación:
1. Revisar esta documentación
2. Consultar ejemplos en `templates/` y `views/proceso/eep_evaluar_nuevo.php`
3. Verificar consola del navegador para errores JavaScript
4. Asegurar que los archivos CSS/JS estén incluidos correctamente