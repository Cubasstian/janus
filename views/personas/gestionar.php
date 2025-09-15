<?php require('views/header.php');?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        Personas
                        <button id="botonMostrarModalPersonas" type="button" class="btn btn-success">
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
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="card card-outline card-success">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="tabla" class="table table-bordered">
                                    <thead>
                                        <tr class="text-center">
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

    <div class="modal fade" id="modalPersonas">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalPersonasTitulo"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formularioPersonas">
                        <div class="form-group">
                            <label for="nombre">Nombre(*)</label>
                            <input type="text" class="form-control" name="nombre" id="nombre" required="required">
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="cedula">Cédula(*)</label>
                                    <input type="number" class="form-control" name="cedula" id="cedula" required="required">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="codigo">Código(*)</label>
                                    <input type="numer" class="form-control" name="codigo" id="codigo" required="required">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="telefono">Teléfono</label>
                                    <input type="numer" class="form-control" name="telefono" id="telefono">
                                </div>        
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="correo">Correo</label>
                                    <input type="email" class="form-control" name="correo" id="correo">
                                </div>        
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Hoja de vida</label>
                            <input type="file" class="form-control-file" id="documento" accept=".pdf" onChange="comprobarArchivo(this)" required="required">
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
                    <button type="submit" class="btn btn-success btn-submit" id="botonGuardarPersonas" form="formularioPersonas">Guardar</button>
                    <button type="submit" class="btn btn-secondary btn-submit" id="botonActualizarPersonas" form="formularioPersonas">Actualizar</button>
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

        $('#botonMostrarModalPersonas').on('click', function(){
            $('#formularioPersonas')[0].reset()
            $('#modalPersonasTitulo').text('Nueva persona')
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
                            <td>                                
                                <button type="button" class="btn btn-default" onClick="mostrarModalEditarPersonas(${registro.id})" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-default" onClick="mostrarDocumento(${registro.id},1,2)" title="Ver hoja de vida">
                                    <i class="fas fa-file-import"></i>
                                </button>
                                <button type="button" class="btn btn-default" onClick="iniciar(${registro.id},'${registro.nombre}')" title="Iniciar contratación">
                                    <i class="fas fa-play-circle"></i>
                                </button>                                
                                <button type="button" class="btn btn-default" onClick="cambiarClave(${registro.id},'${registro.nombre}')" title="Cambiar clave">
                                    <i class="fas fa-key"></i>
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

    function mostrarModalEditarPersonas(idPersona){
        id = idPersona
        llenarFormulario('formularioPersonas', 'usuarios', 'select', {info:{id: idPersona}}, function(r){
            $('#modalPersonasTitulo').text('Editar persona')
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
            title: 'Confirmación',
            html: `Esta seguro de iniciar un proceso de contratación para ${nombre}`,
            showCancelButton: true,
            confirmButtonText: 'Aceptar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if(result.value){                
                enviarPeticion('procesos', 'crear', {contratista: idPersona}, function(r){
                    Swal.fire({
                            icon: 'success',
                            title: 'Confimación',
                            text: `Se creo correctamente el proceso de contratación número #${r.insertId}`
                        })
                })
            }
        })
    }

    function cambiarClave(idPersona, nombre){
        Swal.fire({
            title: 'Nueva clave para '+ nombre,
            input: 'text',            
            showCancelButton: true,
            inputValidator: (value) =>{
                if(!value){
                    return 'Escriba la nueva contraseña'
                }else{
                    enviarPeticion('usuarios', 'setPassword', {info: {password: value}, id: idPersona}, function(r){
                        toastr.success('La contraseña se cambio correctamente')
                    })
                }
            }
        })
    }
</script>
</body>
</html>