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
                                    <i class="fas fa-users-cog"></i>
                                    Gestión de Usuarios del Sistema
                                </h3>
                                <button id="botonMostrarModalUsuarios" type="button" class="btn-kit btn-kit-secondary">
                                    <i class="fas fa-plus mr-1"></i>
                                    Crear Usuario
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Filtros personalizados -->
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <div class="form-group-kit">
                                        <label for="filtroRol">Filtrar por Rol</label>
                                        <select class="input-kit" id="filtroRol">
                                            <option value="">Todos los roles</option>
                                            <option value="Administrador">Administrador</option>
                                            <option value="Revisor">Revisor</option>
                                            <option value="UGA">UGA</option>
                                            <option value="Financiera">Financiera</option>
                                            <option value="GAE">GAE</option>
                                            <option value="SaludOcupacional">Salud Ocupacional</option>
                                            <option value="GestionHumana">Gestión Humana</option>
                                            <option value="SecretariaGeneral">Secretaria General</option>
                                        </select>
                                    </div>
                                </div>
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
                                        <label for="filtroGerencia">Filtrar por Gerencia</label>
                                        <select class="input-kit" id="filtroGerencia">
                                            <option value="">Todas las gerencias</option>
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
                                            <th>Nombre</th>
                                            <th>Login</th>
                                            <th>Rol</th>
                                            <th>Gerencia</th>
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

<?php require('views/footer.php');?>

<script type="text/javascript">
    var id
    var boton
    function init(info){
        //LLenar causales
        llenarSelect('gerencias', 'select', {info:{estado: 'activo'}, orden: 'nombre'}, 'fk_gerencias', 'nombre', 1)
        llenarSelect('gerencias', 'select', {info:{estado: 'activo'}, orden: 'nombre'}, 'filtroGerencia', 'nombre', '')

        //Cargar registro
        cargarRegistros({criterio:'todos'}, 'crear', function(){
            // Verificar si DataTable ya existe y destruirlo
            if ($.fn.DataTable.isDataTable('#tabla')) {
                $('#tabla').DataTable().destroy();
            }
            
            $("#tabla").DataTable({
                "lengthMenu": [ 50, 100, 200 ],
                "pageLength": 50,
                "scrollX": false,
                "autoWidth": false,
                "columnDefs": [
                    { "width": "25%", "targets": [0] }, // Nombre
                    { "width": "20%", "targets": [1, 3] }, // Login, Gerencia
                    { "width": "15%", "targets": [2] }, // Rol
                    { "width": "10%", "targets": [4, 5] }, // Estado, Opciones
                    { "orderable": false, "targets": [5] } // Sin ordenar Opciones
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
                    $('.dataTables_filter input').attr('placeholder', 'Buscar usuarios...');
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
        
        // Filtro por Rol
        $('#filtroRol').on('change', function() {
            let valor = this.value;
            if (valor === '') {
                table.column(2).search('').draw();
            } else {
                table.column(2).search('^' + valor + '$', true, false).draw();
            }
        });
        
        // Filtro por Estado
        $('#filtroEstado').on('change', function() {
            let valor = this.value;
            if (valor === '') {
                table.column(4).search('').draw();
            } else {
                table.column(4).search('^' + valor + '$', true, false).draw();
            }
        });
        
        // Filtro por Gerencia
        $('#filtroGerencia').on('change', function() {
            let valor = this.value;
            if (valor === '') {
                table.column(3).search('').draw();
            } else {
                table.column(3).search(valor, false, false).draw();
            }
        });
        
        // Botón limpiar filtros
        $('#limpiarFiltros').on('click', function() {
            $('#filtroRol').val('');
            $('#filtroEstado').val('');
            $('#filtroGerencia').val('');
            table.search('').columns().search('').draw();
            $('.dataTables_filter input').val('');
            toastr.info('Filtros limpiados');
        });
    }

    function cargarRegistros(datos, accion, callback){
        enviarPeticion('usuarios', 'getUsuarios', datos, function(r){
            let fila = ''
            let colores = {
                'Activo': 'success',
                'Cancelado': 'danger'
            }
            r.data.map(registro => {
                fila += `<tr id=${registro.id}>
                            <td>${registro.nombre}</td>
                            <td>${registro.login}</td>
                            <td>
                                <span class="badge badge-info">${registro.rol}</span>
                            </td>
                            <td>${registro.gerencia}</td>
                            <td class="text-center">
                                <span class="badge badge-${colores[registro.estado]}">
                                    ${registro.estado}
                                </span>
                            </td>
                            <td class="text-center">                                
                                <button class="btn btn-outline-dark btn-action" onClick="mostrarModalEditarUsuarios(${registro.id})" title="Editar usuario" data-toggle="tooltip">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                        </tr>`
            })            
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
</script>

<style>
/* Estilos específicos solo para la página de usuarios */

/* Contenedor de filtros personalizado */
.card-body .row.mb-4 {
    background-color: var(--color-background-light);
    padding: 1.5rem;
    border-radius: var(--border-radius-input);
    border: 1px solid var(--color-border-light);
    margin-bottom: 2rem !important;
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

/* Badges específicos para usuarios */
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