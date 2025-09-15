<?php require('views/header.php');?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        Usuarios
                        <button id="botonMostrarModalUsuarios" type="button" class="btn btn-primary">
                            Crear
                        </button>
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="main/inicio/">Inicio</a></li>
                        <li class="breadcrumb-item active">Usuarios</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="card card-outline card-success">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="tabla" class="table table-bordered table-striped">
                                    <thead>
                                        <tr class="text-center">
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
                        <div class="form-group">
                            <label for="rol">Gerencia</label>
                            <select class="form-control" name="fk_gerencias" id="fk_gerencias" required="required"></select>
                        </div>
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control" name="nombre" id="nombre" required="required">
                        </div>
                        <div class="form-group">
                            <label for="login">login</label>
                            <input type="text" class="form-control" name="login" id="login" required="required">
                        </div>
                        <div class="form-group">
                            <label for="rol">Rol</label>
                            <select class="form-control" name="rol" id="rol" required="required">
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
                        <div class="form-group">
                            <label for="estado">Estado</label>
                            <select class="form-control" name="estado" id="estado" required="required">
                                <option value="Activo">Activo</option>
                                <option value="Cancelado">Cancelado</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-submit" id="botonGuardarUsuarios" form="formularioUsuarios">Guardar</button>
                    <button type="submit" class="btn btn-secondary btn-submit" id="botonActualizarUsuarios" form="formularioUsuarios">Actualizar</button>
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

        //Cargar registro
        cargarRegistros({criterio:'todos'}, 'crear', function(){
            $("#tabla").DataTable({
                "lengthMenu": [ 50, 100, 200 ],
                "pageLength": 50,
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
                }
            })
        })  

        $('#botonMostrarModalUsuarios').on('click', function(){
            $('#formularioUsuarios')[0].reset()
            $('#modalUsuariosTitulo').text('Nueva usuario')
            $('#botonGuardarUsuarios').show()
            $('#botonActualizarUsuarios').hide()
            $('#modalUsuarios').modal('show')
        })

        $('.btn-submit').on('click', function(){
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
                            <td>${registro.rol}</td>
                            <td>${registro.gerencia}</td>
                            <td class="text-center">
                                <span class="badge badge-${colores[registro.estado]}">
                                    ${registro.estado}
                                </span>
                            </td>
                            <td>                                
                                <button class="btn btn-default" onClick="mostrarModalEditarUsuarios(${registro.id})" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                        </tr>`
            })            
            if(accion == 'crear'){
                $('#contenido').append(fila)    
            }else{
                $('#'+r.data[0].id).replaceWith(fila)
            }
            callback()
        })
    }

    function mostrarModalEditarUsuarios(idUsuarios){
        id = idUsuarios
        llenarFormulario('formularioUsuarios', 'usuarios', 'select', {info:{id: idUsuarios}}, function(r){
            $('#modalUsuariosTitulo').text('Editar persona')
            $('#botonGuardarUsuarios').hide()
            $('#botonActualizarUsuarios').show()
            $('#password').prop('disabled',true)
            $('#password').prop('required',false)
            $('#modalUsuarios').modal('show')
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
</body>
</html>