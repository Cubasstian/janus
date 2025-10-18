<?php require('views/header.php');?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <!-- Header limpio sin títulos ni breadcrumbs -->
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-kit">
                        <div class="card-header card-header-clean">
                            <div class="d-flex justify-content-between align-items-center">
                                <h3 class="card-title mb-0" id="titulo">
                                    <i class="fas fa-star"></i>
                                    Gestionar Necesidad
                                </h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs nav-tabs-kit mb-4" id="necesidadTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link nav-link-kit active" id="informacion-tab" data-toggle="tab" data-target="#informacion" type="button" role="tab" aria-controls="informacion" aria-selected="true">
                                        <i class="fas fa-info-circle mr-2"></i>
                                        Información General
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link nav-link-kit" id="imputaciones-tab" data-toggle="tab" data-target="#imputaciones" type="button" role="tab" aria-controls="imputaciones" aria-selected="false">
                                        <i class="fas fa-calculator mr-2"></i>
                                        Imputaciones Presupuestales
                                    </button>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content" id="necesidadTabContent">
                                <!-- TAB 1: Información General -->
                                <div class="tab-pane fade show active" id="informacion" role="tabpanel" aria-labelledby="informacion-tab">
                                    <form id="formularioNecesidades">
                                        <!-- Fila 1: PACC, Vigencia, Gerencia -->
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group-kit">
                                                    <label for="pacc">PACC(*)</label>
                                                    <input type="text" class="input-kit" name="pacc" id="pacc" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group-kit">
                                                    <label for="fk_vigencias">Vigencia</label>
                                                    <select class="input-kit" name="fk_vigencias" id="fk_vigencias" required></select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group-kit">
                                                    <label for="fk_gerencias">Gerencia</label>
                                                    <select class="input-kit" name="fk_gerencias" id="fk_gerencias" required></select>
                                                </div>
                                            </div>
                                        </div>
                                
                                        <!-- Fila 2: Dependencia, Unidad, Profesión -->
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group-kit">
                                                    <label for="dependencia">Dependencia</label>
                                                    <select class="input-kit" id="dependencia" required></select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group-kit">
                                                    <label for="fk_dependencias">Unidad</label>
                                                    <select class="input-kit" name="fk_dependencias" id="fk_dependencias" required></select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group-kit">
                                                    <label for="profesion">Profesión</label>
                                                    <input type="text" class="input-kit" name="profesion" id="profesion" required>
                                                </div>
                                            </div>
                                        </div>
                                
                                        <!-- Fila 3: Honorarios, Presupuesto -->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group-kit">
                                                    <label for="honorarios">Honorarios</label>
                                                    <input type="number" class="input-kit" name="honorarios" id="honorarios" required>
                                                    <small id="mascaraH" class="form-text text-muted">Honorarios/mes</small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group-kit">
                                                    <label for="presupuesto">Presupuesto</label>
                                                    <input type="number" class="input-kit" name="presupuesto" id="presupuesto" required>
                                                    <small id="mascaraP" class="form-text text-muted">Presupuesto total</small>
                                                </div>
                                            </div>
                                        </div>
                                
                                        <!-- Campos de texto completos -->
                                        <div class="form-group-kit">
                                            <label for="definicion_tecnica">Definición Técnica</label>
                                            <textarea class="input-kit" name="definicion_tecnica" id="definicion_tecnica" rows="3"></textarea>
                                        </div>
                                
                                        <div class="form-group-kit">
                                            <label for="objeto">Objeto</label>
                                            <textarea class="input-kit" name="objeto" id="objeto" rows="3"></textarea>
                                        </div>
                                
                                        <div class="form-group-kit">
                                            <label for="alcance">Alcance</label>
                                            <textarea class="input-kit" name="alcance" id="alcance" rows="3"></textarea>
                                        </div>
                                
                                        <div class="form-group-kit">
                                            <label for="conocimientos">Conocimientos</label>
                                            <textarea class="input-kit" name="conocimientos" id="conocimientos" rows="3"></textarea>
                                        </div>
                                
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="d-flex justify-content-between">
                                            <a href="necesidades/listar/" class="btn-kit btn-kit-secondary">
                                                <i class="fas fa-arrow-left"></i> Volver
                                            </a>
                                            <div>
                                                <button type="button" class="btn-kit btn-kit-info" onclick="switchToImputaciones()">
                                                    Siguiente: Imputaciones <i class="fas fa-arrow-right"></i>
                                                </button>
                                                <button type="submit" class="btn-kit btn-kit-primary">
                                                    <i class="fas fa-save"></i> Guardar Necesidad
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                    </form>
                                </div>

                                <!-- TAB 2: Imputaciones Presupuestales -->
                                <div class="tab-pane fade" id="imputaciones" role="tabpanel" aria-labelledby="imputaciones-tab">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h5 class="mb-0">
                                                    <i class="fas fa-calculator text-primary mr-2"></i>
                                                    Imputaciones Presupuestales
                                                </h5>
                                                <button type="button" id="btnAgregarImputacion" class="btn-kit btn-kit-secondary" data-toggle="modal" data-target="#modalImputaciones" disabled title="Seleccione una vigencia para habilitar">
                                                    <i class="fas fa-plus"></i> Agregar Imputación
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table class="table data-table">
                                                    <thead>
                                                        <tr>
                                                            <th>Imputación</th>
                                                            <th class="text-right">Valor</th>
                                                            <th class="text-center">Acciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tablaImputaciones">
                                        <!-- Contenido dinámico -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn-kit btn-kit-secondary" onclick="switchToInformacion()">
                                    <i class="fas fa-arrow-left"></i> Volver a Información
                                </button>
                                <button type="button" class="btn-kit btn-kit-primary" onclick="guardarNecesidad()">
                                    <i class="fas fa-save"></i> Guardar Necesidad Completa
                                </button>
                            </div>
                        </div>
                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal para agregar imputaciones -->
<div class="modal fade" id="modalImputaciones">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Agregar Imputación</h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="formularioImputaciones">
                <div class="modal-body">
                    <div class="form-group-kit">
                        <label for="imputacion">Imputación</label>
                        <select class="input-kit" name="imputacion" id="imputacion" required></select>
                    </div>
                    <div class="form-group-kit">
                        <label for="valor">Valor</label>
                        <input type="number" class="input-kit" name="valor" id="valor" required>
                        <small id="mascaraI" class="form-text text-muted">Valor de la imputación</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn-kit btn-kit-primary">Agregar</button>
                    <button type="button" class="btn-kit btn-kit-secondary" data-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require('views/footer.php');?>

<script>
var id = 0;
var variables = {};
var imputaciones = [];
var guardandoNecesidad = false; // Bandera para prevenir guardado múltiple

// Función para formatear números como currency
function currency(num, decimales) {
    if (typeof $.number !== 'undefined') {
        return $.number(num || 0, decimales || 0, ',', '.');
    } else {
        // Fallback si no está disponible el plugin
        var valor = (num || 0).toString();
        return valor.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
}

$(document).ready(function(){
    init();
});

function init(){
    // Verificar si se está editando (hay ID en la URL)
    const urlParams = new URLSearchParams(window.location.search);
    const idNecesidad = urlParams.get('id');
    
    if(idNecesidad) {
        // Modo edición
        $('#titulo').text(`Editar Necesidad #${idNecesidad}`);
        cargarNecesidadParaEditar(idNecesidad);
    } else {
        // Modo creación
        inicializarModoCreacion();
    }
}

function inicializarModoCreacion() {
    // Obtener datos de sesión primero
    enviarPeticion('helpers', 'getSession', {}, function(sessionData){
        console.log('Datos de sesión:', sessionData);
        
        // Intentar obtener variables de sessionStorage con validación
        try {
            variables = JSON.parse(sessionStorage.getItem('variables')) || {};
        } catch(e) {
            variables = {};
        }

        // Validar que existan las propiedades necesarias
        if (!variables.gerencia || !variables.vigencia) {
            // Si hay usuario en sesión, usar su gerencia
            if (sessionData.ejecuto && sessionData.data && sessionData.data.usuario && sessionData.data.usuario.fk_gerencias) {
                variables.gerencia = { 
                    id: sessionData.data.usuario.fk_gerencias, 
                    nombre: sessionData.data.usuario.gerencia_nombre || 'Mi Gerencia' 
                };
            } else {
                // Fallback: usar gerencia 2 (en lugar de 1) por si tiene más datos
                variables.gerencia = { 
                    id: 2, 
                    nombre: 'Gerencia por defecto' 
                };
            }
            
            variables.vigencia = { 
                id: new Date().getFullYear(), 
                nombre: new Date().getFullYear() 
            };
        }

        $('#titulo').text(`Crear necesidad gerencia ${variables.gerencia.nombre} vigencia ${variables.vigencia.nombre}`);

        // Llenar selects
        const gerenciaId = variables.gerencia.id || 2;
        console.log('Variables completas:', variables);
        console.log('Gerencia ID:', gerenciaId);
        
        // Cargar gerencias primero
        llenarSelect('gerencias', 'getGerencias', {criterio: 'rol'}, 'fk_gerencias', 'nombre', 1, 'Seleccione gerencia...', 'id');
        
        // Cargar vigencias
        llenarSelect('vigencias', 'select', {info:{estado: 'Activo'}}, 'fk_vigencias', 'vigencia', 1, 'Seleccione vigencia...', 'id');
        
        // Cargar dependencias de la gerencia por defecto
        llenarSelect('dependencias', 'getDep', {gerencia: gerenciaId}, 'dependencia', 'dependencia', 1, 'Seleccione dependencia...', 'dependencia');
        
        // Cargar imputaciones según vigencia y gerencia
        setTimeout(function(){
            const vigenciaActual = variables.vigencia.id || new Date().getFullYear();
            cargarImputaciones(gerenciaId, vigenciaActual);
        }, 1000);
        
        // Establecer valores por defecto después de cargar
        setTimeout(function(){
            $('#fk_gerencias').val(gerenciaId).trigger('change');
        }, 500);
        
        // Evento cambio de gerencia
        $('#fk_gerencias').on('change', function(){
            const nuevaGerenciaId = $(this).val();
            console.log('Cambio de gerencia a:', nuevaGerenciaId);
            
            // Actualizar variables
            variables.gerencia.id = nuevaGerenciaId;
            
            // Limpiar y recargar dependencias
            $('#dependencia').empty().append('<option value="">Seleccione dependencia...</option>');
            $('#fk_dependencias').empty().append('<option value="">Seleccione unidad...</option>');
            
            if(nuevaGerenciaId) {
                llenarSelect('dependencias', 'getDep', {gerencia: nuevaGerenciaId}, 'dependencia', 'dependencia', 1, 'Seleccione dependencia...', 'dependencia');
                
                // Recargar imputaciones para la nueva gerencia
                const vigenciaId = $('#fk_vigencias').val() || variables.vigencia.id;
                cargarImputaciones(nuevaGerenciaId, vigenciaId);
            }
        });
        
        // Evento cambio de vigencia
        $('#fk_vigencias').on('change', function(){
            const vigenciaId = $(this).val();
            const gerenciaId = $('#fk_gerencias').val() || variables.gerencia.id;
            
            // Habilitar/deshabilitar botón de agregar imputación
            if(vigenciaId) {
                $('#btnAgregarImputacion').prop('disabled', false);
                $('#btnAgregarImputacion').removeClass('btn-kit-disabled');
                $('#btnAgregarImputacion').attr('title', 'Agregar nueva imputación');
            } else {
                $('#btnAgregarImputacion').prop('disabled', true);
                $('#btnAgregarImputacion').addClass('btn-kit-disabled');
                $('#btnAgregarImputacion').attr('title', 'Seleccione una vigencia para habilitar');
            }
            
            if(vigenciaId && gerenciaId) {
                cargarImputaciones(gerenciaId, vigenciaId);
            }
        });
        
        // Evento cambio de dependencia
        $('#dependencia').on('change', function(){
            const gerenciaId = $('#fk_gerencias').val() || variables.gerencia.id || 2;
            const depSeleccionada = $(this).val();
            console.log('Cambio de dependencia a:', depSeleccionada, 'en gerencia:', gerenciaId);
            
            // Limpiar unidades
            $('#fk_dependencias').empty().append('<option value="">Seleccione unidad...</option>');
            
            if(depSeleccionada && gerenciaId) {
                llenarSelect('dependencias', 'getUnidades', {gerencia: gerenciaId, dep: depSeleccionada}, 'fk_dependencias', 'unidad', 1, 'Seleccione unidad...', 'id');
            }
        });

        // Resto de la inicialización...
        setupEventHandlers();
    });
}

function cargarNecesidadParaEditar(idNecesidad) {
    console.log('Cargando necesidad para editar:', idNecesidad);
    
    // Cargar datos de la necesidad
    enviarPeticion('necesidades', 'getNecesidades', {criterio: 'id', valor: idNecesidad}, function(response){
        if(response.ejecuto && response.data && response.data.length > 0) {
            const necesidad = response.data[0];
            console.log('Datos de necesidad cargados:', necesidad);
            
            // Cargar todos los selects primero
            Promise.all([
                cargarSelectPromise('gerencias', 'getGerencias', {criterio: 'rol'}, 'fk_gerencias', 'nombre', 'id'),
                cargarSelectPromise('vigencias', 'select', {info:{estado: 'Activo'}}, 'fk_vigencias', 'vigencia', 'id')
            ]).then(() => {
                // Llenar campos básicos
                $('#pacc').val(necesidad.pacc || '');
                $('#fk_vigencias').val(necesidad.fk_vigencias || '');
                $('#fk_gerencias').val(necesidad.fk_gerencias || '');
                $('#profesion').val(necesidad.profesion || '');
                $('#estado').val(necesidad.estado || '');
                $('#honorarios').val(necesidad.honorarios || '');
                $('#presupuesto').val(necesidad.presupuesto || '');
                $('#definicion_tecnica').val(necesidad.definicion_tecnica || '');
                $('#objeto').val(necesidad.objeto || '');
                $('#alcance').val(necesidad.alcance || '');
                $('#conocimientos').val(necesidad.conocimientos || '');
                
                // Actualizar máscaras de dinero
                $('#mascaraH').text('Honorarios/mes: $' + currency(necesidad.honorarios || 0, 0));
                $('#mascaraP').text('Presupuesto: $' + currency(necesidad.presupuesto || 0, 0));
                
                // Cargar dependencias de la gerencia seleccionada
                if(necesidad.fk_gerencias) {
                    cargarSelectPromise('dependencias', 'getDep', {gerencia: necesidad.fk_gerencias}, 'dependencia', 'dependencia', 'dependencia').then(() => {
                        $('#dependencia').val(necesidad.dependencia || '');
                        
                        // Cargar unidades de la dependencia seleccionada
                        if(necesidad.dependencia) {
                            cargarSelectPromise('dependencias', 'getUnidades', {gerencia: necesidad.fk_gerencias, dep: necesidad.dependencia}, 'fk_dependencias', 'unidad', 'id').then(() => {
                                $('#fk_dependencias').val(necesidad.fk_dependencias || '');
                            });
                        }
                    });
                }
                
                // Cargar imputaciones
                if(necesidad.fk_gerencias && necesidad.fk_vigencias) {
                    cargarImputaciones(necesidad.fk_gerencias, necesidad.fk_vigencias);
                    
                    // Cargar imputaciones existentes de la necesidad
                    cargarImputacionesExistentes(idNecesidad);
                }
                
                // Agregar campo hidden con el ID para actualización
                if($('#id_necesidad').length === 0) {
                    $('#formularioNecesidades').append('<input type="hidden" name="id" id="id_necesidad" value="' + idNecesidad + '">');
                } else {
                    $('#id_necesidad').val(idNecesidad);
                }
                
                // Cambiar texto del botón de guardar
                $('#botonGuardarNecesidades').text('Actualizar Necesidad');
                
                // Configurar eventos
                setupEventHandlers();
                
                toastr.success('Necesidad cargada para edición');
            });
        } else {
            toastr.error('No se pudo cargar la necesidad');
            console.error('Error cargando necesidad:', response);
        }
    });
}

function cargarImputacionesExistentes(idNecesidad) {
    // Consultar imputaciones de la necesidad
    enviarPeticion('necesidadesImputaciones', 'select', {
        info: { fk_necesidades: idNecesidad }
    }, function(response) {
        if(response.ejecuto && response.data && response.data.length > 0) {
            // Limpiar array de imputaciones
            imputaciones = [];
            
            // Agregar cada imputación al array
            response.data.forEach(function(imp) {
                imputaciones.push({
                    imputacion: imp.imputacion,
                    nombre: imp.imputacion, // Usar el mismo valor para nombre
                    valor: parseFloat(imp.valor)
                });
            });
            
            // Actualizar la tabla visual
            actualizarTablaImputaciones();
            console.log('Imputaciones cargadas:', imputaciones);
        }
    });
}

function cargarImputacionesExistentes(idNecesidad) {
    console.log('Cargando imputaciones existentes para necesidad:', idNecesidad);
    
    enviarPeticion('necesidadesImputaciones', 'select', {info: {fk_necesidades: idNecesidad}}, function(response) {
        if(response.ejecuto && response.data && response.data.length > 0) {
            // Limpiar array de imputaciones
            imputaciones = [];
            
            // Agregar imputaciones existentes al array
            response.data.forEach(function(item) {
                imputaciones.push({
                    imputacion: item.imputacion,
                    nombre: item.imputacion, // Usar el mismo valor para nombre por ahora
                    valor: parseFloat(item.valor)
                });
            });
            
            // Actualizar la tabla visual
            actualizarTablaImputaciones();
            
            console.log('Imputaciones cargadas:', imputaciones);
        } else {
            console.log('No se encontraron imputaciones para la necesidad');
        }
    });
}

function cargarSelectPromise(controlador, metodo, parametros, selectId, textoField, valorField) {
    return new Promise((resolve) => {
        enviarPeticion(controlador, metodo, parametros, function(response) {
            const select = $('#' + selectId);
            select.empty();
            
            if (selectId !== 'fk_vigencias') {
                select.append('<option value="">Seleccione...</option>');
            }
            
            if(response.ejecuto && response.data) {
                response.data.forEach(function(item) {
                    const valor = item[valorField];
                    const texto = item[textoField];
                    select.append(`<option value="${valor}">${texto}</option>`);
                });
            }
            resolve();
        });
    });
}

function setupEventHandlers(){
    // Remover eventos anteriores para evitar duplicados
    $('#formularioNecesidades').off('submit');
    $('#formularioImputaciones').off('submit');
    $('#honorarios').off('keyup');
    $('#presupuesto').off('keyup');
    $('#valor').off('keyup');
    
    // Formatear inputs
    $('#honorarios').on('keyup', function(){
        $('#mascaraH').text('Honorarios/mes: $' + currency($('#honorarios').val(), 0));
    });
    
    $('#presupuesto').on('keyup', function(){
        $('#mascaraP').text('Presupuesto: $' + currency($('#presupuesto').val(), 0));
    });
    
    $('#valor').on('keyup', function(){
        $('#mascaraI').text('Valor: $' + currency($('#valor').val(), 0));
    });

    // Eventos de formularios
    $('#formularioNecesidades').on('submit', function(e){
        e.preventDefault();
        guardarNecesidad();
    });
    
    $('#formularioImputaciones').on('submit', function(e){
        e.preventDefault();
        agregarImputacion();
    });
}

function guardarNecesidad(){
    // Prevenir múltiples guardados simultáneos
    if(guardandoNecesidad) {
        console.log('Ya hay un guardado en proceso...');
        return;
    }
    
    guardandoNecesidad = true;
    
    // Verificar si es edición o creación
    const idNecesidad = $('#id_necesidad').val();
    const esEdicion = idNecesidad && idNecesidad !== '';
    
    // Deshabilitar botón de envío temporalmente
    const textoBoton = esEdicion ? 'Actualizando...' : 'Guardando...';
    const textoOriginal = esEdicion ? 'Actualizar Necesidad' : 'Crear Necesidad';
    $('button[type="submit"]', '#formularioNecesidades').prop('disabled', true).text(textoBoton);
    
    var datos = {
        info: {
            pacc: $('#pacc').val(),
            fk_dependencias: $('#fk_dependencias').val(),
            profesion: $('#profesion').val(),
            definicion_tecnica: $('#definicion_tecnica').val(),
            objeto: $('#objeto').val(),
            alcance: $('#alcance').val(),
            conocimientos: $('#conocimientos').val(),
            honorarios: $('#honorarios').val(),
            presupuesto: $('#presupuesto').val(),
            fk_vigencias: $('#fk_vigencias').val()
        },
        imputaciones: imputaciones
    };

    // Agregar ID si es edición
    if(esEdicion) {
        datos.id = idNecesidad;
    }

    console.log('Datos a enviar:', datos);
    console.log('Es edición:', esEdicion);
    
    // Validar que hay al menos una imputación
    if(!imputaciones || imputaciones.length === 0) {
        toastr.error('Debe agregar al menos una imputación presupuestal');
        guardandoNecesidad = false; // Resetear bandera
        $('button[type="submit"]', '#formularioNecesidades').prop('disabled', false).text(textoOriginal);
        return;
    }

    const metodo = esEdicion ? 'actualizar' : 'crear';
    const mensajeExito = esEdicion ? 'Necesidad actualizada correctamente' : 'Necesidad creada correctamente';

    enviarPeticion('necesidades', metodo, datos, function(r){
        console.log('Respuesta ' + metodo + ' necesidad:', r);
        guardandoNecesidad = false; // Resetear bandera al completar
        
        // Rehabilitar botón de envío
        $('button[type="submit"]', '#formularioNecesidades').prop('disabled', false).text(textoOriginal);
        
        if(r.ejecuto === true){
            toastr.success(mensajeExito);
            
            if(!esEdicion) {
                // Solo limpiar formulario si es creación nueva
                $('#formularioNecesidades')[0].reset();
                imputaciones = [];
                actualizarTablaImputaciones();
            } else {
                // Si es edición, redirigir a la lista o mostrar mensaje
                setTimeout(function() {
                    window.location.href = 'necesidades/listar/';
                }, 1500);
            }
        } else {
            toastr.error(r.mensajeError || 'Error al guardar la necesidad');
        }
    });
}

function agregarImputacion(){
    var imputacion = $('#imputacion').val();
    var valor = $('#valor').val();
    
    if(imputacion && valor){
        var nombreImputacion = $('#imputacion option:selected').text();
        
        imputaciones.push({
            imputacion: imputacion,
            nombre: nombreImputacion,
            valor: parseFloat(valor)
        });
        
        $('#formularioImputaciones')[0].reset();
        $('#modalImputaciones').modal('hide');
        actualizarTablaImputaciones();
    }
}

function actualizarTablaImputaciones(){
    var contenido = '';
    
    imputaciones.forEach(function(item, index){
        contenido += `<tr>
            <td>${item.nombre}</td>
            <td class="text-right">$${currency(item.valor, 0)}</td>
            <td class="text-center">
                <button type="button" class="btn-kit btn-kit-outline-danger btn-sm" onclick="eliminarImputacion(${index})" title="Eliminar imputación">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>`;
    });
    
    $('#tablaImputaciones').html(contenido);
}

function eliminarImputacion(index){
    imputaciones.splice(index, 1);
    actualizarTablaImputaciones();
}

function cargarImputaciones(gerenciaId, vigenciaId) {
    console.log('Cargando imputaciones para gerencia:', gerenciaId, 'vigencia:', vigenciaId);
    
    if(!gerenciaId || !vigenciaId) {
        console.warn('Faltan parámetros para cargar imputaciones');
        return;
    }
    
    // Usar getImputacionesAplican que filtra por gerencia y vigencia
    enviarPeticion('imputaciones', 'getImputacionesAplican', {
        gerencia: gerenciaId,
        vigencia: vigenciaId
    }, function(response) {
        console.log('Respuesta imputaciones:', response);
        
        $('#imputacion').empty().append('<option value="">Seleccione imputación...</option>');
        
        if(response.ejecuto && response.data && response.data.length > 0) {
            response.data.forEach(function(item) {
                $('#imputacion').append(`<option value="${item.imputacion}" data-maximo="${item.maximo}">${item.imputacion}</option>`);
            });
        } else {
            console.warn('No se encontraron imputaciones para gerencia', gerenciaId, 'vigencia', vigenciaId);
        }
    });
}

// Funciones para navegación entre tabs
function switchToImputaciones() {
    // Verificar que se haya seleccionado una vigencia antes de cambiar
    const vigencia = $('#fk_vigencias').val();
    if (!vigencia) {
        toastr.warning('Por favor seleccione una vigencia antes de agregar imputaciones');
        return;
    }
    
    // Cambiar a la tab de imputaciones usando jQuery
    $('#imputaciones-tab').tab('show');
}

function switchToInformacion() {
    // Cambiar a la tab de información usando jQuery
    $('#informacion-tab').tab('show');
}

function guardarNecesidad() {
    // Ejecutar el submit del formulario
    $('#formularioNecesidades').submit();
}

// Inicializar tabs con Bootstrap
$(document).ready(function() {
    // Activar funcionalidad de tabs
    $('#necesidadTabs button[data-toggle="tab"]').on('click', function (e) {
        e.preventDefault();
        $(this).tab('show');
    });
    
    // Evento cuando se muestra una tab
    $('#necesidadTabs button[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        var target = $(e.target).attr("data-target");
        console.log('Tab cambiada a:', target);
    });
});
</script>