<?php require('views/header.php');?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        Personas
                        <button id="botonMostrarModalPersonas" type="button" class="btn-kit btn-kit-primary">
                            <i class="fas fa-plus"></i>
                            Crear
                        </button>
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="solicitudes/missolicitudes/">Inicio</a></li>
                        <li class="breadcrumb-item active">Personas</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="card card-kit">
                        <div class="card-header card-header-clean">
                            <h3 class="card-title">
                                <i class="fas fa-users"></i>
                                Gestión de Personas
                            </h3>
                        </div>
                        <div class="card-body">
                            <!-- Filtros personalizados -->
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <div class="form-group-kit">
                                        <label for="filtroEstado">Filtrar por Estado</label>
                                        <select class="input-kit" id="filtroEstado">
                                            <option value="">Todos los estados</option>
                                            <option value="Activo">Activo</option>
                                            <option value="Cancelado">Cancelado</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group-kit">
                                        <label for="filtroCedula">Buscar por Cédula</label>
                                        <input type="text" class="input-kit" id="filtroCedula" placeholder="Ej: 12345678">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group-kit">
                                        <label for="filtroCodigo">Buscar por Código</label>
                                        <input type="text" class="input-kit" id="filtroCodigo" placeholder="Ej: EMP001">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group-kit">
                                        <label>&nbsp;</label>
                                        <div>
                                            <button type="button" class="btn-kit btn-kit-secondary w-100" id="limpiarFiltros">
                                                <i class="fas fa-eraser"></i>
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
                                            <th>Nombre</th>
                                            <th>Cédula</th>
                                            <th>Correo</th>
                                            <th>Código</th>
                                            <th>Estado</th>
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

    <!-- Modal Simple -->
    <div class="modal fade" id="modalPersonas">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalPersonasTitulo">
                        <i class="fas fa-user mr-2"></i>
                        <span class="modal-title-text"></span>
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formularioPersonas">
                        <div class="form-group-kit">
                            <label for="nombre">Nombre(*)</label>
                            <input type="text" class="input-kit" name="nombre" id="nombre" required="required">
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group-kit">
                                    <label for="cedula">Cédula(*)</label>
                                    <input type="number" class="input-kit" name="cedula" id="cedula" required="required">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group-kit">
                                    <label for="codigo">Código(*)</label>
                                    <input type="number" class="input-kit" name="codigo" id="codigo" required="required">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group-kit">
                                    <label for="telefono">Teléfono</label>
                                    <input type="number" class="input-kit" name="telefono" id="telefono">
                                </div>        
                            </div>
                            <div class="col">
                                <div class="form-group-kit">
                                    <label for="correo">Correo</label>
                                    <input type="email" class="input-kit" name="correo" id="correo">
                                </div>        
                            </div>
                        </div>
                        <div class="form-group-kit">
                            <label>Hoja de vida</label>
                            <!-- Input de archivo (se oculta cuando hay documento) -->
                            <div id="archivoInput">
                                <input type="file" class="input-kit-file" id="documento" accept=".pdf" onChange="comprobarArchivo(this)">
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Solo archivos PDF. Máximo 5MB.
                                </small>
                            </div>
                            <!-- Información del documento actual (solo se muestra al editar) -->
                            <div id="infoDocumento"></div>
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i>
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary btn-submit" id="botonGuardarPersonas" form="formularioPersonas">
                        <i class="fas fa-save mr-1"></i>
                        Guardar
                    </button>
                    <button type="submit" class="btn btn-primary btn-submit" id="botonActualizarPersonas" form="formularioPersonas">
                        <i class="fas fa-edit mr-1"></i>
                        Actualizar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require('views/footer.php');?>

<script type="text/javascript">
    var id
    var boton
    
    function init(info){
        //Cargar registro
        cargarRegistros({info:{rol:'PS'}}, 'crear', function(){
            // Verificar si DataTable ya existe y destruirlo si es necesario
            if ($.fn.DataTable.isDataTable('#tabla')) {
                $('#tabla').DataTable().destroy();
            }
            
            $("#tabla").DataTable({
                "lengthMenu": [ 50, 100, 200 ],
                "pageLength": 50,
                "scrollX": false, // Desactivar scroll horizontal
                "autoWidth": false, // Desactivar ancho automático
                "columnDefs": [
                    { 
                        "width": "25%", 
                        "targets": [0, 2] // Nombre y Correo
                    },
                    { 
                        "width": "15%", 
                        "targets": [1, 5] // Cédula y Opciones
                    },
                    { 
                        "width": "10%", 
                        "targets": [3, 4] // Código y Estado
                    },
                    { 
                        "orderable": false, 
                        "targets": [5] // Desactivar ordenamiento en Opciones
                    }
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
                    // Agregar placeholder al input de búsqueda
                    $('.dataTables_filter input').attr('placeholder', 'Buscar personas...');
                    
                    // Mejorar el texto del filtro de longitud
                    $('.dataTables_length label').html(
                        $('.dataTables_length label').html().replace('Mostrar', 'Mostrar')
                    );
                }
            });
            
            // Configurar filtros personalizados
            setupCustomFilters();
            
            // Inicializar tooltips después de cargar la tabla
            $('[data-toggle="tooltip"]').tooltip();
        })  

        $('#botonMostrarModalPersonas').on('click', function(){
            $('#formularioPersonas')[0].reset()
            $('#modalPersonasTitulo .modal-title-text').text('Nueva persona')
            $('#botonGuardarPersonas').show()
            $('#botonActualizarPersonas').hide()
            $('#password').prop('disabled',false)
            $('#password').prop('required',true)
            // Limpiar información de documento al crear nuevo usuario
            $('#infoDocumento').html('')
            // Mostrar input de archivo para crear
            $('#archivoInput').show()
            $('#modalPersonas').modal('show')
        })

        $('.btn-submit').on('click', function(){
            boton = $(this).attr('id')
        })

        $('#formularioPersonas').on('submit', function(e){
            e.preventDefault()
            let datos = parsearFormulario($(this))
            datos.login = datos.cedula
            datos.rol = 'PS' // Agregar rol requerido para personas
            
            if(boton == 'botonGuardarPersonas'){
                enviarPeticion('usuarios', 'insert', {info: datos}, function(r){
                    if(r && r.ejecuto && r.insertId) {
                        // Verificar si hay un archivo seleccionado
                        let archivoInput = document.getElementById('documento');
                        if(archivoInput && archivoInput.files && archivoInput.files.length > 0) {
                            // Usar la nueva función cargarArchivo
                            cargarArchivo(archivoInput, r.insertId, 1, 2, function(archivoExitoso){
                                // Siempre recargar la tabla, independientemente del resultado del archivo
                                cargarRegistros({info:{rol:'PS'}}, 'crear', function(){
                                    $('#modalPersonas').modal('hide')
                                })
                                
                                if(archivoExitoso) {
                                    toastr.success('Usuario creado y documento subido correctamente')
                                } else {
                                    toastr.warning('Usuario creado pero hubo un problema con el documento')
                                }
                            })
                        } else {
                            toastr.success('Usuario creado correctamente')
                            // Recargar toda la tabla si no hay archivo
                            cargarRegistros({info:{rol:'PS'}}, 'crear', function(){
                                $('#modalPersonas').modal('hide')
                            })
                        }
                    } else {
                        toastr.error('Error al crear usuario: ' + (r.mensajeError || 'Error desconocido'))
                    }
                })
            }else{                
                enviarPeticion('usuarios', 'update', {info: datos, id: id}, function(r){
                    // Validar respuesta del API - puede venir con ejecuto o con status 200
                    if((r && r.ejecuto) || (r && r.status === 200)) {
                        // Verificar si hay un archivo seleccionado para actualizar
                        let archivoInput = document.getElementById('documento');
                        if(archivoInput && archivoInput.files && archivoInput.files.length > 0) {
                            // Usar la función cargarArchivo para subir nuevo documento
                            cargarArchivo(archivoInput, id, 1, 2, function(archivoExitoso){
                                // Siempre recargar la tabla, independientemente del resultado del archivo
                                cargarRegistros({info:{rol:'PS'}}, 'crear', function(){
                                    $('#modalPersonas').modal('hide')
                                })
                                
                                if(archivoExitoso) {
                                    toastr.success('Usuario actualizado y documento subido correctamente')
                                } else {
                                    toastr.warning('Usuario actualizado pero hubo un problema con el documento')
                                }
                            })
                        } else {
                            toastr.success('Se actualizó correctamente')
                            // Recargar toda la tabla con los parámetros correctos
                            cargarRegistros({info:{rol:'PS'}}, 'crear', function(){
                                $('#modalPersonas').modal('hide')
                            })
                        }
                    } else {
                        toastr.error('Error al actualizar usuario: ' + (r.mensajeError || 'Error desconocido'))
                    }
                })
            }
        })
    }

    // Función para configurar filtros personalizados
    function setupCustomFilters() {
        // Variable para almacenar la instancia de DataTable
        let table = $('#tabla').DataTable();
        
        // Filtro por Estado
        $('#filtroEstado').on('change', function() {
            let valor = this.value;
            if (valor === '') {
                table.column(4).search('').draw(); // Columna 4 es Estado
            } else {
                table.column(4).search('^' + valor + '$', true, false).draw();
            }
        });
        
        // Filtro por Cédula
        $('#filtroCedula').on('keyup', function() {
            table.column(1).search(this.value).draw(); // Columna 1 es Cédula
        });
        
        // Filtro por Código
        $('#filtroCodigo').on('keyup', function() {
            table.column(3).search(this.value).draw(); // Columna 3 es Código
        });
        
        // Botón limpiar filtros
        $('#limpiarFiltros').on('click', function() {
            // Limpiar todos los filtros personalizados
            $('#filtroEstado').val('');
            $('#filtroCedula').val('');
            $('#filtroCodigo').val('');
            
            // Limpiar filtros de DataTables
            table.search('').columns().search('').draw();
            
            // Limpiar también el filtro global de DataTables
            $('.dataTables_filter input').val('');
            
            // Mostrar mensaje de confirmación
            toastr.info('Filtros limpiados');
        });
    }

    function cargarRegistros(datos, accion, callback){
        enviarPeticion('usuarios', 'select', datos, function(r){
            console.log('Respuesta cargarRegistros:', r); // Debug log
            let fila = ''
            let colores = {
                'Activo': 'success',
                'Cancelado': 'danger'
            }
            
            // Verificar que la respuesta sea válida - manejar diferentes formatos
            if (r && r.ejecuto && r.data && Array.isArray(r.data)) {
                // Formato estándar con ejecuto y data
                r.data.map(registro => {
                    fila += `<tr id=${registro.id}>
                                <td>${registro.nombre}</td>
                                <td>${registro.cedula}</td>
                                <td>${registro.correo}</td>
                                <td>${registro.codigo}</td>
                                <td class="text-center">
                                    <span class="badge badge-${colores[registro.estado]}">
                                        ${registro.estado}
                                    </span>
                                </td>
                                <td class="text-center">                                
                                    <button type="button" class="btn btn-outline-dark btn-action" onClick="mostrarModalEditarPersonas(${registro.id})" title="Editar" data-toggle="tooltip">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-info btn-action" onClick="mostrarDocumento(${registro.id},1,2)" title="Ver hoja de vida" data-toggle="tooltip">
                                        <i class="fas fa-file-pdf"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-success btn-action" onClick="iniciar(${registro.id},'${registro.nombre}')" title="Iniciar contratación" data-toggle="tooltip">
                                        <i class="fas fa-play"></i>
                                    </button>                                
                                    <button type="button" class="btn btn-outline-warning btn-action" onClick="cambiarClave(${registro.id},'${registro.nombre}')" title="Cambiar clave" data-toggle="tooltip">
                                        <i class="fas fa-key"></i>
                                    </button>
                                </td>
                            </tr>`
                })
            } else if (r && r.status === 200 && Array.isArray(r)) {
                // Formato alternativo donde la respuesta es directamente un array
                r.map(registro => {
                    fila += `<tr id=${registro.id}>
                                <td>${registro.nombre}</td>
                                <td>${registro.cedula}</td>
                                <td>${registro.correo}</td>
                                <td>${registro.codigo}</td>
                                <td class="text-center">
                                    <span class="badge badge-${colores[registro.estado]}">
                                        ${registro.estado}
                                    </span>
                                </td>
                                <td class="text-center">                                
                                    <button type="button" class="btn btn-outline-dark btn-action" onClick="mostrarModalEditarPersonas(${registro.id})" title="Editar" data-toggle="tooltip">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-info btn-action" onClick="mostrarDocumento(${registro.id},1,2)" title="Ver hoja de vida" data-toggle="tooltip">
                                        <i class="fas fa-file-pdf"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-success btn-action" onClick="iniciar(${registro.id},'${registro.nombre}')" title="Iniciar contratación" data-toggle="tooltip">
                                        <i class="fas fa-play"></i>
                                    </button>                                
                                    <button type="button" class="btn btn-outline-warning btn-action" onClick="cambiarClave(${registro.id},'${registro.nombre}')" title="Cambiar clave" data-toggle="tooltip">
                                        <i class="fas fa-key"></i>
                                    </button>
                                </td>
                            </tr>`
                })
            } else {
                // Mostrar mensaje de error o tabla vacía
                console.error('Error en cargarRegistros:', r);
                fila = `<tr>
                            <td colspan="6" class="text-center">
                                <i class="fas fa-exclamation-triangle text-warning"></i><br>
                                No se pudieron cargar los datos o no hay registros disponibles
                            </td>
                        </tr>`;
            }            
            if(accion == 'crear'){
                $('#contenido').html(fila)  // Cambié append() por html() para evitar duplicados    
            }else{
                // Solo intentar reemplazar si tenemos datos válidos
                if ((r && r.ejecuto && r.data && Array.isArray(r.data) && r.data.length > 0) || 
                    (r && r.status === 200 && Array.isArray(r) && r.length > 0)) {
                    let primerRegistro = r.data ? r.data[0] : r[0];
                    $('#'+primerRegistro.id).replaceWith(fila)
                } else {
                    // Si no hay datos válidos, recargar toda la tabla
                    $('#contenido').html(fila)
                }
            }
            
            // Inicializar tooltips después de cargar datos
            $('[data-toggle="tooltip"]').tooltip();
            
            callback()
        })
    }

    function mostrarModalEditarPersonas(idPersona){
        id = idPersona
        llenarFormulario('formularioPersonas', 'usuarios', 'select', {info:{id: idPersona}}, function(r){
            $('#modalPersonasTitulo .modal-title-text').text('Editar persona')
            $('#botonGuardarPersonas').hide()
            $('#botonActualizarPersonas').show()
            $('#password').prop('disabled',true)
            $('#password').prop('required',false)
            
            // Cargar información del documento existente
            cargarInfoDocumento(idPersona);
            
            $('#modalPersonas').modal('show')
        })
    }

    // Función para cargar información del documento en el modal de edición
    function cargarInfoDocumento(contratista) {
        console.log('Cargando info documento para contratista:', contratista);
        enviarPeticion('documentos', 'getDocumentos', {
            criterio: 'generales', 
            contratista: contratista,
            proceso: 1
        }, function(r) {
            console.log('Respuesta info documento:', r);
            const infoDocumento = $('#infoDocumento');
            const archivoInput = $('#archivoInput');
            
            if (r && r.ejecuto && r.data && r.data.length > 0) {
                // Filtrar por tipo=2 (hoja de vida)
                const documentos = r.data.filter(doc => doc.tipo == 2);
                console.log('Documentos filtrados para edición:', documentos);
                
                if (documentos.length > 0) {
                    const documento = documentos[0];
                    console.log('Documento info encontrado:', documento);
                    
                    if (documento.estado > 0) {
                        // Hay documento cargado - ocultar input y mostrar opciones
                        archivoInput.hide();
                        infoDocumento.html(`
                            <div class="documento-existente">
                                <div class="alert alert-success mb-2">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    <strong>Documento actual cargado</strong>
                                </div>
                                <div class="btn-group-documento">
                                    <button type="button" class="btn btn-info btn-sm" onclick="viewDocument(${documento.id})">
                                        <i class="fas fa-eye mr-1"></i>Ver documento
                                    </button>
                                    <button type="button" class="btn btn-warning btn-sm" onclick="mostrarInputReemplazo()">
                                        <i class="fas fa-sync-alt mr-1"></i>Reemplazar
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="eliminarDocumento(${documento.id}, ${contratista})">
                                        <i class="fas fa-trash mr-1"></i>Eliminar
                                    </button>
                                </div>
                            </div>
                        `);
                    } else {
                        // Hay registro pero sin archivo - mostrar input
                        archivoInput.show();
                        infoDocumento.html(`
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <strong>Documento:</strong> Hay un registro pero no se ha cargado archivo
                            </div>
                        `);
                    }
                } else {
                    // No hay documento tipo 2 - mostrar input
                    archivoInput.show();
                    infoDocumento.html(`
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle mr-2"></i>
                            <strong>Información:</strong> No hay hoja de vida cargada. Puede seleccionar un archivo.
                        </div>
                    `);
                }
            } else {
                // No hay documentos - mostrar input
                archivoInput.show();
                console.log('No se encontró documento en modal de edición');
                infoDocumento.html(`
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Información:</strong> No hay documentos para este usuario. Puede seleccionar un archivo.
                    </div>
                `);
            }
        });
    }

    // Función para mostrar input de reemplazo
    function mostrarInputReemplazo() {
        $('#archivoInput').show();
        $('#infoDocumento').html(`
            <div class="alert alert-warning">
                <i class="fas fa-info-circle mr-2"></i>
                Seleccione un nuevo archivo para reemplazar el actual
            </div>
        `);
    }

    // Función para eliminar documento
    function eliminarDocumento(documentoId, contratista) {
        Swal.fire({
            icon: 'warning',
            title: '¿Eliminar documento?',
            html: '¿Está seguro de eliminar este documento? Esta acción no se puede deshacer.',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-trash mr-1"></i>Eliminar',
            cancelButtonText: '<i class="fas fa-times mr-1"></i>Cancelar',
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d'
        }).then((result) => {
            if (result.isConfirmed) {
                // Eliminar el documento
                enviarPeticion('documentos', 'delete', {id: documentoId}, function(r) {
                    if (r && (r.ejecuto || r.status === 200)) {
                        toastr.success('Documento eliminado correctamente');
                        // Recargar la info del documento
                        cargarInfoDocumento(contratista);
                    } else {
                        toastr.error('Error al eliminar el documento: ' + (r.mensajeError || 'Error desconocido'));
                    }
                });
            }
        });
    }

    function iniciar(idPersona, nombre){
        Swal.fire({
            icon: 'question',
            title: 'Iniciar Contratación',
            html: `¿Está seguro de iniciar un proceso de contratación para <strong>${nombre}</strong>?`,
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-play mr-1"></i>Iniciar Proceso',
            cancelButtonText: '<i class="fas fa-times mr-1"></i>Cancelar',
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            focusConfirm: false,
            allowOutsideClick: false
        }).then((result) => {
            if(result.value){                
                enviarPeticion('procesos', 'crear', {contratista: idPersona}, function(r){
                    Swal.fire({
                        icon: 'success',
                        title: 'Proceso Creado',
                        html: `Se creó correctamente el proceso de contratación número <strong>#${r.insertId}</strong>`,
                        confirmButtonText: '<i class="fas fa-check mr-1"></i>Entendido',
                        confirmButtonColor: '#518711',
                        customClass: {
                            popup: 'swal2-kit-ui',
                            title: 'swal2-kit-title',
                            content: 'swal2-kit-content',
                            confirmButton: 'swal2-kit-confirm'
                        },
                        buttonsStyling: false,
                        timer: 3000,
                        timerProgressBar: true
                    })
                })
            }
        })
    }

    function cambiarClave(idPersona, nombre){
        Swal.fire({
            title: `Cambiar Contraseña`,
            html: `Nueva contraseña para <strong>${nombre}</strong>`,
            input: 'password',
            inputPlaceholder: 'Ingrese la nueva contraseña...',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-key mr-1"></i>Cambiar Clave',
            cancelButtonText: '<i class="fas fa-times mr-1"></i>Cancelar',
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            focusConfirm: false,
            allowOutsideClick: false,
            inputAttributes: {
                autocapitalize: 'off',
                autocorrect: 'off',
                autocomplete: 'new-password'
            },
            inputValidator: (value) => {
                if(!value || value.trim() === ''){
                    return 'Por favor escriba la nueva contraseña'
                }
                if(value.length < 4){
                    return 'La contraseña debe tener al menos 4 caracteres'
                }
                return null
            },
            preConfirm: (password) => {
                return new Promise((resolve) => {
                    enviarPeticion('usuarios', 'setPassword', {info: {password: password}, id: idPersona}, function(r){
                        if(r && r.ejecuto) {
                            resolve(password)
                        } else {
                            Swal.showValidationMessage('Error al cambiar la contraseña')
                        }
                    })
                })
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    icon: 'success',
                    title: 'Contraseña Actualizada',
                    html: `La contraseña de <strong>${nombre}</strong> se cambió correctamente`,
                    confirmButtonText: '<i class="fas fa-check mr-1"></i>Entendido',
                    confirmButtonColor: '#28a745',
                    timer: 3000,
                    timerProgressBar: true
                })
            }
        })
    }

    // Función para comprobar archivos (copiada de ps/documentos)
    function comprobarArchivo(input) {
        if (!input.files || input.files.length === 0) {
            toastr.error('Por favor seleccione un archivo');
            return false;
        }
        
        const file = input.files[0];
        const maxSize = 5 * 1024 * 1024; // 5MB
        
        if (file.size > maxSize) {
            toastr.error('El archivo es muy grande. Máximo 5MB permitido');
            input.value = '';
            return false;
        }
        
        const allowedTypes = ['application/pdf'];
        if (!allowedTypes.includes(file.type)) {
            toastr.error('Solo se permiten archivos PDF');
            input.value = '';
            return false;
        }
        
        return true;
    }

    // Función para cargar archivo (similar a ps/documentos)
    function cargarArchivo(input, contratista, proceso, tipo, callback){
        if (!contratista || contratista <= 0) {
            toastr.error('Error: ID de contratista no válido');
            return;
        }
        
        if (!proceso || proceso <= 0) {
            toastr.error('Error: Proceso no válido para cargar archivos');
            return;
        }
        
        if (!tipo || tipo <= 0) {
            toastr.error('Error: Tipo de documento no válido');
            return;
        }
        
        if(comprobarArchivo(input)){
            toastr.info('Subiendo archivo...');
            enviarPeticion('documentos', 'crear', {contratista: contratista, proceso: proceso, tipo: tipo}, function(r){
                console.log('Respuesta documentos/crear:', r);
                console.log('insertId:', r.insertId, 'ejecuto:', r.ejecuto, 'status:', r.status);
                
                // Validar respuesta - puede venir con ejecuto o con status 200
                // Pero NECESITA tener insertId
                if (r && r.insertId && ((r.ejecuto) || (r.status === 200))) {
                    console.log('ID del documento creado:', r.insertId);
                    cargarDocumento(input, r.insertId, function(res){
                        if(res.ejecuto == true){
                            // Actualizar el estado del documento a 1 (cargado)
                            enviarPeticion('documentos', 'update', {info: {estado: 1}, id: r.insertId}, function(y){
                                toastr.success(res.msg || 'Archivo cargado correctamente')
                                if(callback) callback(true);
                            })
                        } else {
                            toastr.error(res.msg || 'Error al cargar archivo')
                            if(callback) callback(false);
                        }
                    })
                } else {
                    console.error('Error API - falta insertId:', r);
                    toastr.error('Error: El documento se creó pero no se recibió el ID. Respuesta: ' + JSON.stringify(r))
                    if(callback) callback(false);
                }
            })
        } else {
            if(callback) callback(false);
        }
    }

    // Función para cargar documento (copiada de ps/documentos)
    function cargarDocumento(input, documentoId, callback) {
        if (!input.files || input.files.length === 0) {
            callback({ejecuto: false, msg: 'No se seleccionó archivo'});
            return;
        }
        
        console.log('Iniciando carga de documento:', {
            documentoId: documentoId,
            fileName: input.files[0].name,
            fileSize: input.files[0].size,
            fileType: input.files[0].type
        });
        
        const formData = new FormData();
        formData.append('file', input.files[0]);
        formData.append('id', documentoId);
        
        $.ajax({
            url: 'controllers/archivos.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log('Respuesta cruda del servidor:', response);
                console.log('Tipo de respuesta:', typeof response);
                
                try {
                    let result;
                    if (typeof response === 'string') {
                        console.log('Parseando string JSON...');
                        result = JSON.parse(response);
                    } else {
                        console.log('Respuesta ya es objeto...');
                        result = response;
                    }
                    console.log('Resultado parseado:', result);
                    callback(result);
                } catch (e) {
                    console.error('Error al parsear respuesta:', e);
                    console.error('Respuesta que causó el error:', response);
                    
                    toastr.error('Error al procesar respuesta del servidor');
                    callback({
                        ejecuto: false, 
                        msg: 'Error al procesar respuesta'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Error AJAX:', {xhr, status, error, responseText: xhr.responseText});
                callback({ejecuto: false, msg: 'Error al subir el archivo: ' + error});
            }
        });
    }

    // Función para mostrar documento (ver hoja de vida)
    function mostrarDocumento(contratista, proceso, tipo) {
        console.log('Buscando documento para:', {contratista, proceso, tipo});
        
        // Usar el mismo criterio que ps/documentos
        enviarPeticion('documentos', 'getDocumentos', {
            criterio: 'generales', 
            contratista: contratista,
            proceso: proceso
        }, function(r) {
            console.log('Respuesta búsqueda documento (criterio generales):', r);
            if (r && r.ejecuto && r.data && r.data.length > 0) {
                // Filtrar por tipo en JavaScript
                const documentos = r.data.filter(doc => doc.tipo == tipo);
                console.log('Documentos filtrados:', documentos);
                
                if (documentos.length > 0) {
                    const documento = documentos[0];
                    console.log('Documento encontrado:', documento);
                    if (documento.estado > 0) { // Si el documento está cargado
                        viewDocument(documento.id);
                    } else {
                        toastr.warning('El documento existe pero no está cargado (estado: ' + documento.estado + ')');
                    }
                } else {
                    toastr.warning('No se encontró documento con tipo=' + tipo);
                }
            } else {
                console.log('No se encontró ningún documento para este contratista');
                toastr.warning('No se encontró hoja de vida para este usuario');
            }
        });
    }

    // Función para visualizar documento en el navegador
    function viewDocument(documentoId) {
        if (!documentoId) {
            toastr.error('ID de documento no válido');
            return;
        }
        
        // Usar la API para obtener el documento
        enviarPeticion('archivos', 'getDocumento', {id: documentoId}, function(r) {
            if (r.ejecuto && r.file) {
                try {
                    // Convertir base64 a blob
                    const byteCharacters = atob(r.file);
                    const byteNumbers = new Array(byteCharacters.length);
                    for (let i = 0; i < byteCharacters.length; i++) {
                        byteNumbers[i] = byteCharacters.charCodeAt(i);
                    }
                    const byteArray = new Uint8Array(byteNumbers);
                    const blob = new Blob([byteArray], {type: 'application/pdf'});
                    
                    // Crear URL y abrir en nueva pestaña
                    const url = window.URL.createObjectURL(blob);
                    window.open(url, '_blank');
                    
                    // Limpiar el URL después de un tiempo
                    setTimeout(() => {
                        window.URL.revokeObjectURL(url);
                    }, 1000);
                } catch (e) {
                    toastr.error('Error al procesar el archivo');
                    console.error('Error al procesar archivo:', e);
                }
            } else {
                toastr.error(r.mensajeError || 'Error al obtener el documento');
            }
        });
    }

    // Función para descargar documento (mantener por si se necesita)
    function downloadDocument(documentoId) {
        if (!documentoId) {
            toastr.error('ID de documento no válido');
            return;
        }
        
        // Usar la API para obtener el documento
        enviarPeticion('archivos', 'getDocumento', {id: documentoId}, function(r) {
            if (r.ejecuto && r.file) {
                try {
                    // Convertir base64 a blob
                    const byteCharacters = atob(r.file);
                    const byteNumbers = new Array(byteCharacters.length);
                    for (let i = 0; i < byteCharacters.length; i++) {
                        byteNumbers[i] = byteCharacters.charCodeAt(i);
                    }
                    const byteArray = new Uint8Array(byteNumbers);
                    const blob = new Blob([byteArray], {type: 'application/pdf'});
                    
                    // Crear URL y descargar
                    const url = window.URL.createObjectURL(blob);
                    const link = document.createElement('a');
                    link.href = url;
                    link.download = `hoja_de_vida_${documentoId}.pdf`;
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    window.URL.revokeObjectURL(url);
                } catch (e) {
                    toastr.error('Error al procesar el archivo');
                    console.error('Error al procesar archivo:', e);
                }
            } else {
                toastr.error(r.mensajeError || 'Error al obtener el documento');
            }
        });
    }
</script>

<style>
/* Kit UI Variables */
:root {
    --color-primary: #518711;
    --color-primary-dark: #3d6608;
    --color-primary-light: #6b9c2e;
    --color-text-light: #ffffff;
    --color-text-dark: #2c3e50;
    --color-text-secondary: #6c757d;
    --color-border: #dde2e5;
    --color-border-light: #e9ecef;
    --color-disabled: #95a5a6;
    --color-background: #f8f9fa;
    --border-radius-card: 12px;
    --border-radius-button: 8px;
    --shadow-card: 0 4px 6px rgba(0, 0, 0, 0.1);
    --shadow-modal: 0 10px 40px rgba(0, 0, 0, 0.2);
    --transition-quick: 0.2s ease;
}

/* Modal Styles - Estilo Bootstrap estándar */
.modal-content {
    border-radius: 8px;
    border: none;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
}

.modal-header {
    border-bottom: 1px solid #dee2e6;
    padding: 1rem 1.5rem;
}

.modal-title {
    font-weight: 600;
    font-size: 1.25rem;
    color: #333;
}

.modal-title i {
    color: #007bff;
}

.modal-body {
    padding: 1.5rem;
}

.modal-footer {
    border-top: 1px solid #dee2e6;
    padding: 1rem 1.5rem;
}

/* Estilos para documento existente */
.documento-existente {
    margin-top: 0.5rem;
}

.btn-group-documento {
    display: flex;
    gap: 0.5rem;
    margin-top: 0.5rem;
}

.btn-group-documento .btn {
    flex: 1;
    font-size: 0.85rem;
    padding: 0.5rem 0.75rem;
    border-radius: 6px;
    transition: all 0.2s ease;
}

.btn-group-documento .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.documento-existente .alert {
    margin-bottom: 0.5rem;
    padding: 0.75rem;
    font-size: 0.9rem;
}



/* File input específico */
.input-kit-file {
    width: 100%;
    padding: 0.5rem 0.75rem;
    border: 2px dashed var(--color-border-light);
    border-radius: var(--border-radius-input);
    background-color: var(--color-background-light);
    color: var(--color-text-dark);
    font-size: 0.9rem;
    transition: all var(--transition-quick);
    cursor: pointer;
}

.input-kit-file:hover {
    border-color: var(--color-primary);
    background-color: rgba(var(--color-primary-rgb), 0.05);
}

.input-kit-file:focus {
    border-color: var(--color-primary);
    box-shadow: 0 0 0 0.2rem rgba(81, 135, 17, 0.25);
    outline: 0;
}

/* Helper text para file input */
.form-text {
    font-size: 0.8rem;
    margin-top: 0.5rem;
    color: var(--color-text-secondary);
}

.form-text i {
    margin-right: 0.25rem;
    opacity: 0.7;
}

/* Botones del modal - estilo Bootstrap estándar */
#botonActualizarPersonas,
#botonGuardarPersonas {
    transition: all 0.2s ease;
}

#botonActualizarPersonas:hover,
#botonGuardarPersonas:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

/* SweetAlert2 - Estilo Bootstrap estándar */
.swal2-popup {
    border-radius: 8px !important;
    font-family: inherit !important;
}
</style>

<script>
$(document).ready(function() {
    init();
});
</script>
</body>
</html>