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

    <!-- Modal Simple con Kit UI -->
    <div class="modal fade" id="modalPersonas">
        <div class="modal-dialog modal-lg">
            <div class="modal-content modal-kit">
                <div class="modal-header modal-header-kit">
                    <h4 class="modal-title" id="modalPersonasTitulo">
                        <i class="fas fa-user mr-2"></i>
                        <span class="modal-title-text"></span>
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body modal-body-kit">
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
                            <input type="file" class="input-kit-file" id="documento" accept=".pdf" onChange="comprobarArchivo(this)" required="required">
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle mr-1"></i>
                                Solo archivos PDF. Máximo 5MB.
                            </small>
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
                <div class="modal-footer modal-footer-kit">
                    <button type="button" class="btn-kit btn-kit-light" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i>
                        Cancelar
                    </button>
                    <button type="submit" class="btn-kit btn-kit-primary" id="botonGuardarPersonas" form="formularioPersonas">
                        <i class="fas fa-save mr-1"></i>
                        Guardar
                    </button>
                    <button type="submit" class="btn-kit btn-kit-secondary" id="botonActualizarPersonas" form="formularioPersonas">
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
            $('#modalPersonas').modal('show')
        })

        $('.btn-submit').on('click', function(){
            boton = $(this).attr('id')
        })

        $('#formularioPersonas').on('submit', function(e){
            e.preventDefault()
            let datos = parsearFormulario($(this))
            datos.login = datos.cedula
            if(boton == 'botonGuardarPersonas'){
                enviarPeticion('usuarios', 'insert', {info: datos}, function(r){
                    //Primero creo el documento
                    enviarPeticion('documentos', 'crear', {contratista: r.insertId, proceso: 1, tipo: 2}, function(res){
                        cargarDocumento(document.getElementById('documento'), res.insertId, function(y){
                            if(y.ejecuto == true){
                                toastr.success(y.msg)
                            }else{
                                toastr.error(y.msg)
                            }
                        })
                    })
                    cargarRegistros({info: {id: r.insertId}}, 'crear', function(){
                        $('#modalPersonas').modal('hide')
                    })
                })
            }else{                
                enviarPeticion('usuarios', 'update', {info: datos, id: id}, function(r){
                    toastr.success('Se actualizó correctamente')
                    cargarRegistros({info: {id: id}}, 'actualizar', function(){
                        $('#modalPersonas').modal('hide')
                    })
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
            let fila = ''
            let colores = {
                'Activo': 'success',
                'Cancelado': 'danger'
            }
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
            if(accion == 'crear'){
                $('#contenido').html(fila)  // Cambié append() por html() para evitar duplicados    
            }else{
                $('#'+r.data[0].id).replaceWith(fila)
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
            $('#modalPersonas').modal('show')
        })
    }

    function iniciar(idPersona, nombre){
        Swal.fire({
            icon: 'question',
            title: 'Iniciar Contratación',
            html: `¿Está seguro de iniciar un proceso de contratación para <strong>${nombre}</strong>?`,
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-play mr-1"></i>Iniciar Proceso',
            cancelButtonText: '<i class="fas fa-times mr-1"></i>Cancelar',
            confirmButtonColor: '#518711',
            cancelButtonColor: '#6c757d',
            customClass: {
                popup: 'swal2-kit-ui',
                title: 'swal2-kit-title',
                content: 'swal2-kit-content',
                confirmButton: 'swal2-kit-confirm',
                cancelButton: 'swal2-kit-cancel'
            },
            buttonsStyling: false,
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
            confirmButtonColor: '#518711',
            cancelButtonColor: '#6c757d',
            customClass: {
                popup: 'swal2-kit-ui',
                title: 'swal2-kit-title',
                content: 'swal2-kit-content',
                input: 'swal2-kit-input',
                confirmButton: 'swal2-kit-confirm',
                cancelButton: 'swal2-kit-cancel'
            },
            buttonsStyling: false,
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
            }
        })
    }
</script>

<style>
/* Kit UI Modal Styles */
.modal-kit {
    border-radius: var(--border-radius-card);
    border: none;
    box-shadow: var(--shadow-modal);
}

.modal-header-kit {
    background: linear-gradient(135deg, var(--color-primary) 0%, #6b9c2e 100%);
    color: var(--color-text-light);
    border-bottom: none;
    border-radius: var(--border-radius-card) var(--border-radius-card) 0 0;
    padding: 1.25rem 1.5rem;
}

.modal-header-kit .modal-title {
    font-weight: 600;
    font-size: 1.1rem;
    display: flex;
    align-items: center;
}

.modal-header-kit .modal-title i {
    font-size: 1.2rem;
    opacity: 0.9;
}

.modal-header-kit .close {
    color: var(--color-text-light);
    opacity: 0.8;
    text-shadow: none;
    font-size: 1.5rem;
    transition: all var(--transition-quick);
}

.modal-header-kit .close:hover {
    opacity: 1;
    transform: scale(1.1);
}

.modal-body-kit {
    padding: 2rem 1.5rem;
    background-color: var(--color-background-light);
}

.modal-footer-kit {
    background-color: var(--color-background-light);
    border-top: 1px solid var(--color-border-light);
    border-radius: 0 0 var(--border-radius-card) var(--border-radius-card);
    padding: 1.25rem 1.5rem;
    justify-content: space-between;
}

.modal-footer-kit .btn-kit {
    margin-left: 0.5rem;
}

.modal-footer-kit .btn-kit:first-child {
    margin-left: 0;
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

/* SweetAlert2 Kit UI Customization */
.swal2-popup {
    border-radius: var(--border-radius-card) !important;
    box-shadow: var(--shadow-modal) !important;
    font-family: inherit !important;
}

.swal2-title {
    color: var(--color-text-dark) !important;
    font-weight: 600 !important;
    font-size: 1.5rem !important;
}

.swal2-content {
    color: var(--color-text-secondary) !important;
    font-size: 1rem !important;
}

.swal2-confirm {
    background-color: var(--color-primary) !important;
    border: none !important;
    border-radius: var(--border-radius-button) !important;
    font-weight: 600 !important;
    padding: 0.75rem 1.5rem !important;
    font-size: 0.9rem !important;
    transition: all var(--transition-quick) !important;
}

.swal2-confirm:hover {
    background-color: #6b9c2e !important;
    transform: translateY(-1px) !important;
    box-shadow: var(--shadow-subtle) !important;
}

.swal2-confirm:focus {
    box-shadow: 0 0 0 0.2rem rgba(81, 135, 17, 0.5) !important;
}

.swal2-cancel {
    background-color: var(--color-text-secondary) !important;
    border: 1px solid var(--color-border-light) !important;
    border-radius: var(--border-radius-button) !important;
    color: var(--color-text-light) !important;
    font-weight: 600 !important;
    padding: 0.75rem 1.5rem !important;
    font-size: 0.9rem !important;
    transition: all var(--transition-quick) !important;
    margin-right: 0.5rem !important;
}

.swal2-cancel:hover {
    background-color: var(--color-disabled) !important;
    transform: translateY(-1px) !important;
    box-shadow: var(--shadow-subtle) !important;
}

.swal2-cancel:focus {
    box-shadow: 0 0 0 0.2rem rgba(108, 117, 125, 0.5) !important;
}

.swal2-input {
    border: 1px solid var(--color-border-light) !important;
    border-radius: var(--border-radius-input) !important;
    padding: 0.75rem !important;
    font-size: 0.9rem !important;
    transition: all var(--transition-quick) !important;
    margin: 1rem 0 !important;
}

.swal2-input:focus {
    border-color: var(--color-primary) !important;
    box-shadow: 0 0 0 0.2rem rgba(81, 135, 17, 0.25) !important;
    outline: 0 !important;
}

.swal2-icon.swal2-question {
    border-color: var(--color-primary) !important;
    color: var(--color-primary) !important;
}

.swal2-icon.swal2-success {
    border-color: var(--color-primary) !important;
}

.swal2-icon.swal2-success [class^="swal2-success-line"] {
    background-color: var(--color-primary) !important;
}

.swal2-icon.swal2-success .swal2-success-ring {
    border-color: rgba(81, 135, 17, 0.3) !important;
}

/* Progreso timer personalizado */
.swal2-timer-progress-bar {
    background: var(--color-primary) !important;
}
</style>

<script>
$(document).ready(function() {
    init();
});
</script>
</body>
</html>