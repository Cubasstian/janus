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
                                <h3 class="card-title mb-0">
                                    <i class="fas fa-list"></i>
                                    Listar Necesidades
                                </h3>
                                <a href="necesidades/gestionar/" class="btn-kit btn-kit-secondary">
                                    <i class="fas fa-plus mr-1"></i>
                                    Nueva Necesidad
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Filtros personalizados -->
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <div class="form-group-kit">
                                        <label for="filtroBusqueda">Buscar por dependencia o profesión</label>
                                        <input type="text" class="input-kit" id="filtroBusqueda" placeholder="Ej: Dependencia, profesión...">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group-kit">
                                        <label for="filtroEstado">Filtrar por Estado</label>
                                        <select class="input-kit" id="filtroEstado">
                                            <option value="">Todos los estados</option>
                                            <option value="Libre">Libre</option>
                                            <option value="Ocupada">Ocupada</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group-kit">
                                        <label for="filtroUnidad">Filtrar por Unidad</label>
                                        <select class="input-kit" id="filtroUnidad">
                                            <option value="">Todas las unidades</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group-kit">
                                        <label>&nbsp;</label>
                                        <div>
                                            <button type="button" class="btn-kit btn-kit-secondary w-100" id="limpiarFiltros">
                                                Limpiar Filtros
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="table-responsive">
                                <table id="tabla" class="data-table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>PACC</th>
                                            <th>Gerencia</th>
                                            <th>Unidad</th>
                                            <th>Profesión</th>
                                            <th>Estado</th>
                                            <th>Vigencia</th>
                                            <th>Presupuesto</th>
                                            <th>Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="contenido"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="modalUsuarios">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalUsuariosTitulo"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formularioUsuarios">
                        <div class="form-group-kit">
                            <label for="fk_gerencias">Gerencia</label>
                            <select class="input-kit" name="fk_gerencias" id="fk_gerencias" required="required"></select>
                        </div>
                        <div class="form-group-kit">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="input-kit" name="nombre" id="nombre" required="required">
                        </div>
                        <div class="form-group-kit">
                            <label for="login">Login</label>
                            <input type="text" class="input-kit" name="login" id="login" required="required">
                        </div>
                        <div class="form-group-kit">
                            <label for="rol">Rol</label>
                            <select class="input-kit" name="rol" id="rol" required="required">
                                <option value="Administrador">Administrador</option>
                                <option value="Revisor">Revisor</option>
                                <option value="UGA">UGA</option>
                                <option value="Financiera">Financiera</option>
                                <option value="GAE">GAE</option>
                                <option value="SaludOcupacional">Salud Ocupacional</option>
                                <option value="GestionHumana">Gestión humana</option>
                                <option value="SecretariaGeneral">Secretaria General</option>
                            </select>
                        </div>
                        <div class="form-group-kit">
                            <label for="estado">Estado</label>
                            <select class="input-kit" name="estado" id="estado" required="required">
                                <option value="Activo">Activo</option>
                                <option value="Cancelado">Cancelado</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn-kit btn-kit-primary" id="botonGuardarUsuarios" form="formularioUsuarios">
                        Guardar
                    </button>
                    <button type="submit" class="btn-kit btn-kit-primary" id="botonActualizarUsuarios" form="formularioUsuarios">
                        Actualizar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal completa de necesidad - AL FINAL -->
<div class="modal fade" id="modalDetalleNecesidad" tabindex="-1" aria-labelledby="modalDetalleNecesidadLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetalleNecesidadLabel">
                    <i class="fas fa-eye mr-2"></i>
                    Detalles de la Necesidad #<span id="detalle-id-titulo"></span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Primera fila: Información básica -->
                <div class="row mb-3">
                    <div class="col-md-2">
                        <div class="form-group-kit">
                            <label>ID:</label>
                            <p id="detalle-id" class="font-weight-bold text-primary"></p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group-kit">
                            <label>PACC:</label>
                            <p id="detalle-pacc"></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group-kit">
                            <label>Gerencia:</label>
                            <p id="detalle-gerencia"></p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group-kit">
                            <label>Estado:</label>
                            <span id="detalle-estado" class="badge badge-pill"></span>
                        </div>
                    </div>
                </div>
                
                <!-- Segunda fila: Detalles profesionales y presupuestales -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="form-group-kit">
                            <label>Unidad:</label>
                            <p id="detalle-unidad"></p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group-kit">
                            <label>Profesión:</label>
                            <p id="detalle-profesion"></p>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group-kit">
                            <label>Vigencia:</label>
                            <p id="detalle-vigencia"></p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group-kit">
                            <label>Honorarios:</label>
                            <p id="detalle-honorarios" class="font-weight-bold text-success"></p>
                        </div>
                    </div>
                </div>
                
                <!-- Tercera fila: Descripciones -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group-kit">
                            <label>Definición Técnica:</label>
                            <div style="max-height: 150px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px; background-color: #f8f9fa;">
                                <p id="detalle-definicion" class="mb-0"></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group-kit">
                            <label>Objeto:</label>
                            <div style="max-height: 150px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px; background-color: #f8f9fa;">
                                <p id="detalle-objeto" class="mb-0"></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group-kit">
                            <label>Alcance:</label>
                            <div style="max-height: 150px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px; background-color: #f8f9fa;">
                                <p id="detalle-alcance" class="mb-0"></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group-kit">
                            <label>Conocimientos:</label>
                            <div style="max-height: 150px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 10px; border-radius: 4px; background-color: #f8f9fa;">
                                <p id="detalle-conocimientos" class="mb-0"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i>Cerrar
                </button>
                <a id="detalle-editar-link" href="#" class="btn btn-primary">
                    <i class="fas fa-edit mr-1"></i>Editar Necesidad
                </a>
            </div>
        </div>
    </div>
</div>

<?php require('views/footer.php');?>

<script type="text/javascript">
    var id
    var boton
    function init(info){
        //LLenar causales
        
        // Cargar unidades para el filtro
        enviarPeticion('dependencias', 'select', {info:{estado: 'Activo'}}, function(response) {
            $('#filtroUnidad').empty().append('<option value="">Todas las unidades</option>');
            if(response.ejecuto && response.data) {
                // Crear un Set para unidades únicas
                const unidadesUnicas = new Set();
                response.data.forEach(function(item) {
                    if(item.unidad && item.unidad.trim() !== '') {
                        unidadesUnicas.add(item.unidad);
                    }
                });
                
                // Convertir a array y ordenar
                Array.from(unidadesUnicas).sort().forEach(function(unidad) {
                    $('#filtroUnidad').append(`<option value="${unidad}">${unidad}</option>`);
                });
            }
        });
        
        //Cargar registro
        cargarRegistros({criterio:'todas'}, 'crear', function(){
            // Verificar si DataTable ya existe y destruirlo
            if ($.fn.DataTable.isDataTable('#tabla')) {
                $('#tabla').DataTable().destroy();
            }
            
            $("#tabla").DataTable({
                "lengthMenu": [ 50, 100, 200 ],
                "pageLength": 50,
                "scrollX": false,
                "autoWidth": false,
                "dom": "rtip", // Solo mostrar tabla (r), información (i) y paginación (p)
                "columnDefs": [
                    { "width": "8%", "targets": [0] }, // ID
                    { "width": "12%", "targets": [1] }, // PACC
                    { "width": "15%", "targets": [2] }, // Gerencia
                    { "width": "20%", "targets": [3] }, // Unidad
                    { "width": "15%", "targets": [4] }, // Profesión
                    { "width": "10%", "targets": [5] }, // Estado
                    { "width": "8%", "targets": [6] }, // Vigencia
                    { "width": "10%", "targets": [7] }, // Presupuesto
                    { "width": "8%", "targets": [8] }, // Opciones
                    { "orderable": false, "targets": [8] } // Sin ordenar Opciones
                ],
                "language":{
                    "decimal":        "",
                    "emptyTable":     "Sin datos para mostrar",
                    "info":           "Mostrando _START_ al _END_ de _TOTAL_ registros",
                    "infoEmpty":      "Mostrando 0 to 0 of 0 entries",
                    "infoFiltered":   "(Filtrado de _MAX_ total registros)",
                    "infoPostFix":    "",
                    "thousands":      ".",
                    "lengthMenu":     "Mostrar _MENU_ registros",
                    "loadingRecords": "Cargando...",
                    "processing":     "Procesando...",
                    "search":         "Buscar:",
                    "zeroRecords":    "Ningún registro encontrado",
                    "paginate": {
                        "first":      "Primero",
                        "last":       "Último",
                        "next":       "Sig",
                        "previous":   "Ant"
                    },
                    "aria": {
                        "sortAscending":  ": activate to sort column ascending",
                        "sortDescending": ": activate to sort column descending"
                    }
                },
                "initComplete": function() {
                    // Ya no necesitamos actualizar el placeholder porque usamos nuestro propio filtro
                    console.log('DataTable inicializada correctamente');
                }
            });
            
            setupCustomFilters();
        })  

        $('#botonMostrarModalUsuarios').on('click', function(){
            console.log('Botón crear usuario clickeado'); // Debug
            $('#formularioUsuarios')[0].reset()
            $('#modalUsuariosTitulo').text('Nuevo usuario')
            $('#botonGuardarUsuarios').show()
            $('#botonActualizarUsuarios').hide()
            
            // Intentar múltiples métodos para abrir el modal
            try {
                // Método 1: Bootstrap modal estándar
                $('#modalUsuarios').modal('show');
                
                // Método 2: Fallback manual si Bootstrap falla
                setTimeout(function() {
                    if (!$('#modalUsuarios').hasClass('show')) {
                        console.log('Bootstrap modal falló, usando fallback manual');
                        $('#modalUsuarios').addClass('show').css('display', 'block');
                        $('body').addClass('modal-open');
                        if ($('.modal-backdrop').length === 0) {
                            $('body').append('<div class="modal-backdrop fade show"></div>');
                        }
                    }
                }, 100);
                
            } catch (error) {
                console.error('Error abriendo modal:', error);
                // Fallback de emergencia
                $('#modalUsuarios').addClass('show').css('display', 'block');
                $('body').addClass('modal-open');
            }
            
            console.log('Intentos de apertura de modal completados'); // Debug
        })

        // Event listeners para botones específicos
        $('#botonGuardarUsuarios, #botonActualizarUsuarios').on('click', function(){
            boton = $(this).attr('id')
        })

        $('#formularioUsuarios').on('submit', function(e){
            e.preventDefault()
            let datos = parsearFormulario($(this))
            if(boton == 'botonGuardarUsuarios'){
                enviarPeticion('usuarios', 'insert', {info: datos}, function(r){
                    toastr.success('Se creo correctamente')
                    cargarRegistros({criterio: 'id', id:r.insertId}, 'crear', function(){
                        $('#modalUsuarios').modal('hide')
                    })
                })
            }else{                
                enviarPeticion('usuarios', 'update', {info: datos, id: id}, function(r){
                    toastr.success('Se actualizó correctamente')
                    cargarRegistros({criterio: 'id', id: id}, 'actualizar', function(){
                        $('#modalUsuarios').modal('hide')
                    })
                })
            }
        })
    }

    function setupCustomFilters() {
        let table = $('#tabla').DataTable();
        
        // Filtro por búsqueda general  
        $('#filtroBusqueda').on('keyup', function() {
            let valor = this.value;
            table.search(valor).draw();
        });
        
        // Filtro por Estado
        $('#filtroEstado').on('change', function() {
            let valor = this.value;
            if (valor === '') {
                table.column(4).search('').draw();
            } else {
                table.column(4).search(valor).draw();
            }
        });
        
        // Filtro por Unidad  
        $('#filtroUnidad').on('change', function() {
            let valor = this.value;
            table.column(3).search(valor).draw(); // Columna 3 es Unidad
        });
        
        // Botón limpiar filtros
        $('#limpiarFiltros').on('click', function() {
            $('#filtroBusqueda').val('');
            $('#filtroEstado').val('');
            $('#filtroUnidad').val('');
            table.search('').columns().search('').draw();
            $('.dataTables_filter input').val('');
            toastr.info('Filtros limpiados');
        });
    }

    function cargarRegistros(datos, accion, callback){
        enviarPeticion('necesidades', 'getNecesidades', datos, function(r){
            let fila = ''
            let colores = {
                'Libre': 'success',
                'Ocupada': 'warning'
            }
            if(r && r.data && Array.isArray(r.data)) {
                r.data.map(registro => {
                    let estado = registro.estado || 'Sin estado';
                    fila += `<tr id=${registro.id}>
                                <td>
                                    <span class="font-weight-bold text-primary">${registro.id}</span>
                                </td>
                                <td>${registro.pacc || ''}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-building text-primary mr-2"></i>
                                        <span>${registro.gerencia || ''}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-sitemap text-info mr-2"></i>
                                        <span>${registro.unidad || ''}</span>
                                    </div>
                                </td>
                                <td>${registro.profesion || ''}</td>
                                <td class="text-center">
                                    <span class="badge badge-${colores[estado] || 'secondary'} badge-pill">
                                        ${estado}
                                    </span>
                                </td>
                                <td class="text-center">${registro.vigencia || ''}</td>
                                <td class="text-right">$${currency(registro.presupuesto || 0, 0)}</td>
                                <td class="text-center">
                                    <button class="btn-kit btn-kit-outline-dark btn-sm" onclick="verDetalleNecesidad(${registro.id})" title="Ver detalle">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>`
                })
            }
            if(accion == 'crear'){
                $('#contenido').html(fila)   
            }else{
                $('#'+r.data[0].id).replaceWith(fila)
            }
            
            $('[data-toggle="tooltip"]').tooltip()
            callback()
        })
    }

    function mostrarModalEditarUsuarios(idUsuarios){
        console.log('Abriendo modal para editar usuario:', idUsuarios); // Debug
        id = idUsuarios
        llenarFormulario('formularioUsuarios', 'usuarios', 'select', {info:{id: idUsuarios}}, function(r){
            $('#modalUsuariosTitulo').text('Editar usuario')
            $('#botonGuardarUsuarios').hide()
            $('#botonActualizarUsuarios').show()
            $('#password').prop('disabled',true)
            $('#password').prop('required',false)
            
            // Abrir modal con fallback
            try {
                $('#modalUsuarios').modal('show');
                
                setTimeout(function() {
                    if (!$('#modalUsuarios').hasClass('show')) {
                        console.log('Bootstrap modal falló en editar, usando fallback manual');
                        $('#modalUsuarios').addClass('show').css('display', 'block');
                        $('body').addClass('modal-open');
                        if ($('.modal-backdrop').length === 0) {
                            $('body').append('<div class="modal-backdrop fade show"></div>');
                        }
                    }
                }, 100);
                
            } catch (error) {
                console.error('Error abriendo modal de edición:', error);
                $('#modalUsuarios').addClass('show').css('display', 'block');
                $('body').addClass('modal-open');
            }
        })
    }

    function iniciar(idPersona, nombre){
        Swal.fire({
            icon: 'question',
            title: 'Confirmación',
            html: `Esta seguro de iniciar proceso para ${nombre}`,
            showCancelButton: true,
            confirmButtonText: 'Aceptar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if(result.value){                
                enviarPeticion('procesos', 'crear', {contratista: idPersona}, function(r){
                    Swal.fire({
                            icon: 'success',
                            title: 'Confimación',
                            text: `Se creo correctamente el proceso número #${r.insertId}`
                        })
                })
            }
        })
    }

    function cambiarClave(idUsuario, nombre){
        Swal.fire({
            title: 'Nueva clave para '+ nombre,
            input: 'text',            
            showCancelButton: true,
            inputValidator: (value) =>{
                if(!value){
                    return 'Escriba la nueva contraseña'
                }else{
                    enviarPeticion('usuarios', 'setPassword', {info: {password: value}, id: idUsuario}, function(r){
                        toastr.success('La contraseña se cambio correctamente')
                    })
                }
            }
        })
    }

    // Función de formateo de números
    function currency(num, decimales) {
        if (typeof $.number !== 'undefined') {
            return $.number(num || 0, decimales || 0, ',', '.');
        } else {
            var valor = (num || 0).toString();
            return valor.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }
    }

    // Función para mostrar detalles de necesidad en modal
    function verDetalleNecesidad(idNecesidad) {
        enviarPeticion('necesidades', 'getNecesidades', {criterio: 'id', valor: idNecesidad}, function(r){
            if(r && r.ejecuto && r.data && r.data.length > 0) {
                const necesidad = r.data[0];
                
                // Llenar todos los datos de la necesidad
                $('#detalle-id-titulo').text(necesidad.id);
                $('#detalle-id').text(necesidad.id);
                $('#detalle-pacc').text(necesidad.pacc || 'No especificado');
                $('#detalle-gerencia').text(necesidad.gerencia || 'No especificado');
                $('#detalle-unidad').text(necesidad.unidad || 'No especificado');
                $('#detalle-profesion').text(necesidad.profesion || 'No especificado');
                $('#detalle-vigencia').text(necesidad.vigencia || 'No especificado');
                
                // Estado con badge colorido
                const estado = necesidad.estado || 'Sin estado';
                const estadoColor = estado === 'Libre' ? 'success' : (estado === 'Ocupada' ? 'warning' : 'secondary');
                $('#detalle-estado').removeClass().addClass(`badge badge-${estadoColor} badge-pill`).text(estado);
                
                // Montos formateados
                $('#detalle-honorarios').text('$' + currency(necesidad.honorarios || 0, 0));
                
                // Textos largos
                $('#detalle-definicion').text(necesidad.definicion_tecnica || 'No especificado');
                $('#detalle-objeto').text(necesidad.objeto || 'No especificado');
                $('#detalle-alcance').text(necesidad.alcance || 'No especificado');
                $('#detalle-conocimientos').text(necesidad.conocimientos || 'No especificado');
                
                // Link para editar
                $('#detalle-editar-link').attr('href', 'necesidades/gestionar/?id=' + necesidad.id);
                
                // Modal con backdrop correcto
                $('#modalDetalleNecesidad').modal('show');
            } else {
                toastr.error('No se pudieron cargar los detalles de la necesidad');
            }
        });
    }
</script>

<style>
/* Estilos específicos solo para la página de necesidades */

/* Fix para z-index de la tabla - solucion completa */
.content-wrapper {
    position: relative;
    z-index: 1 !important;
}

.card {
    position: relative;
    z-index: 2 !important;
}

.card-body {
    position: relative;
    z-index: 3 !important;
}

.dataTables_wrapper {
    position: relative;
    z-index: 10 !important;
}

.table-responsive {
    z-index: 10 !important;
    position: relative;
}

#tabla {
    position: relative;
    z-index: 15 !important;
}

/* Asegurar que los dropdowns de DataTables estén encima */
.dataTables_length select,
.dataTables_filter input {
    z-index: 20 !important;
    position: relative;
}

/* Ocultar o estilizar elementos de DataTables que no coinciden con Kit UI */
.dataTables_length {
    display: none !important; /* Ocultar el selector de "Mostrar X registros" */
}

.dataTables_filter {
    display: none !important; /* Ocultar el input de búsqueda por defecto */
}

.dataTables_info {
    font-size: 0.9em;
    color: var(--color-text-secondary, #6c757d);
    margin-top: 10px;
}

.dataTables_paginate {
    margin-top: 15px;
}

/* Estilizar los botones de paginación para que coincidan con Kit UI */
.dataTables_paginate .paginate_button {
    border: 1px solid var(--color-border-light, #dee2e6) !important;
    background: var(--color-background-light, #ffffff) !important;
    color: var(--color-text-dark, #333333) !important;
    border-radius: var(--border-radius-input, 4px) !important;
    margin: 0 2px !important;
    padding: 6px 12px !important;
}

.dataTables_paginate .paginate_button:hover {
    background: var(--color-primary, #007bff) !important;
    color: var(--color-text-light, #ffffff) !important;
}

.dataTables_paginate .paginate_button.current {
    background: var(--color-primary, #007bff) !important;
    color: var(--color-text-light, #ffffff) !important;
}

/* Margen superior para evitar superposición con el menú */
.content {
    margin-top: 20px !important;
    padding-top: 20px !important;
}

/* Forzar que toda la página esté por encima del sidebar pero por debajo de modales */
.content-wrapper {
    position: relative;
    z-index: 1 !important;
}

.content-wrapper * {
    position: relative;
}

/* Jerarquía específica de z-index */
.card {
    z-index: 2 !important;
}

.card-body {
    z-index: 3 !important;
}

.dataTables_wrapper {
    z-index: 10 !important; /* Mayor que los filtros */
}

#tabla {
    z-index: 12 !important; /* Mayor que los filtros */
}

/* CSS limpio - modal con z-index correcto */

/* Modal con z-index superior al sidebar */
.modal {
    z-index: 1055 !important;
}

.modal-backdrop {
    z-index: 1050 !important;
}

/* Contenedor de filtros personalizado */
.card-body .row.mb-4 {
    background-color: var(--color-background-light);
    padding: 1.5rem;
    border-radius: var(--border-radius-input);
    border: 1px solid var(--color-border-light);
    margin-bottom: 2rem !important;
    position: relative;
    z-index: 1 !important; /* Reducir para que la tabla tenga prioridad */
}

/* Asegurar que los selects dentro de los filtros tengan z-index correcto */
.card-body .row.mb-4 select,
.card-body .row.mb-4 .input-kit {
    position: relative;
    z-index: 5 !important; /* Reducir para que no interfiera con la tabla */
}

/* Corregir z-index específicamente para el select de unidades */
#filtroUnidad {
    position: relative !important;
    z-index: 5 !important; /* Reducir z-index para que no se superponga */
}

/* Limitar la altura del dropdown y agregar scroll */
#filtroUnidad {
    max-height: 200px !important; /* Limitar altura del select */
}

/* Si es un select múltiple o con muchas opciones, agregar scroll */
.input-kit select {
    max-height: 200px;
    overflow-y: auto;
}

/* Asegurar que el dropdown no se superponga con la tabla */
.form-group-kit select {
    position: relative;
    z-index: 5 !important;
}

/* Asegurar que los dropdowns de los selects se muestren correctamente */
.card-body .row.mb-4 select:focus,
.card-body .row.mb-4 .input-kit:focus {
    z-index: 20 !important;
}

/* Prevenir overflow que pueda ocultar dropdowns */
.card-body,
.card {
    overflow: visible !important;
}

.row.mb-4 {
    overflow: visible !important;
}

/* Asegurar que el contenedor no recorte los elementos */
.form-group-kit {
    position: relative;
    overflow: visible !important;
}

/* Botones de acción específicos de esta tabla */
.btn-action {
    margin: 0 0.125rem;
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
    border-radius: var(--border-radius-input);
    transition: all var(--transition-quick);
    border-width: 1px;
}

.btn-action:hover {
    transform: translateY(-1px);
    box-shadow: var(--shadow-subtle);
}

.btn-outline-dark {
    color: var(--color-text-dark);
    border-color: var(--color-text-dark);
    background-color: transparent;
}

.btn-outline-dark:hover {
    background-color: var(--color-text-dark);
    color: var(--color-text-light);
}

/* Badges específicos para necesidades */
.badge-success {
    background-color: var(--color-primary);
    color: var(--color-text-light);
}

.badge-danger {
    background-color: var(--color-danger);
    color: var(--color-text-light);
}

.badge-info {
    background-color: var(--color-info);
    color: var(--color-text-light);
}
</style>
</body>
</html>