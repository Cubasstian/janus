<?php require('views/header.php');?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        Topes
                        <button id="botonMostrarModalTopes" type="button" class="btn btn-primary">
                            Nuevo
                        </button>
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="solicitudes/missolicitudes/">Inicio</a></li>
                        <li class="breadcrumb-item active">Topes</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col-sm-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="vigencia">Vigencia</label>
                                <select class="form-control" id="vigencia"></select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-9">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="tabla" class="table table-bordered">
                                    <thead>
                                        <tr class="text-center">
                                            <th>Vigencia</th>
                                            <th>Gerencia aportante</th>
                                            <th>Gerencia ejecutora</th>
                                            <th>Valor</th>
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

    <div class="modal fade" id="modalTopes">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalTopesTitulo"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formularioTopes">
                        <div class="form-group">
                            <label for="gerencia_aportante">Gerencia aportante</label>
                            <select class="form-control" name="gerencia_aportante" id="gerencia_aportante" required="required"></select>
                        </div>
                        <div class="form-group">
                            <label for="gerencia_ejecutora">Gerencia ejecutora</label>
                            <select class="form-control" name="gerencia_ejecutora" id="gerencia_ejecutora" required="required"></select>
                        </div>
                        <div class="form-group">
                            <label for="valor" id="mascara">Valor</label>
                            <input type="number" class="form-control" name="valor" id="valor" required="required">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-submit" id="botonGuardarTopes" form="formularioTopes">Guardar</button>
                    <button type="submit" class="btn btn-secondary btn-submit" id="botonActualizarTopes" form="formularioTopes">Actualizar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require('views/footer.php');?>

<script type="text/javascript">
    var id = 0
    var boton = ''
    var vigencia = 0
    function init(info){
        //Llenar vigencias
        llenarSelect('vigencias', 'select', {info:{estado: 'Activo'}}, 'vigencia', 'vigencia', 1)
        //LLenar gerencias
        llenarSelect('gerencias', 'select', {info:{estado: 'activo'}, orden: 'id'}, 'gerencia_aportante', 'nombre', 1)
        llenarSelect('gerencias', 'select', {info:{estado: 'activo'}, orden: 'id'}, 'gerencia_ejecutora', 'nombre', 1)

        $('#vigencia').on('change', function(){
            vigencia = $(this).val()
            $('#contenido').empty()
            if($(this).val() == ""){
                toastr.error('Debe escoger una vigencia')
            }else{
                cargarRegistros({criterio: 'vigencia', valor: vigencia}, 'crear', function(){})    
            }
        })

        //Formatear input
        $('#valor').on('keyup', function(r){
            $('#mascara').text('Valor: $' + currency($('#valor').val(),0))
        })

        $('#botonMostrarModalTopes').on('click', function(){
            if($('#vigencia').val() == ""){
                toastr.error('Debe escoger una vigencia')
            }else{
                $('#formularioTopes')[0].reset()
                $('#modalTopesTitulo').text('Nuevo tope vigencia '+ $('#vigencia option:selected').text())
                $('#botonGuardarTopes').show()
                $('#botonActualizarTopes').hide()
                $('#modalTopes').modal('show')
            }
        })

        $('.btn-submit').on('click', function(){
            boton = $(this).attr('id')
        })      

        $('#formularioTopes').on('submit', function(e){
            e.preventDefault()
            let datos = parsearFormulario($(this))
            datos.fk_vigencias = vigencia
            if(boton == 'botonGuardarTopes'){
                enviarPeticion('topes', 'insert', {info: datos}, function(r){
                    toastr.success('Se creo correctamente')
                    cargarRegistros({criterio: 'id', valor: r.insertId}, 'crear', function(){
                        $('#modalTopes').modal('hide')
                    })
                })
            }else{                
                enviarPeticion('topes', 'update', {info: datos, id: id}, function(r){
                    toastr.success('Se actualizó correctamente')
                    cargarRegistros({criterio: 'id', valor: id}, 'actualizar', function(){
                        $('#modalTopes').modal('hide')
                    })
                })
            }
        })
    }

    function cargarRegistros(datos, accion, callback){
        enviarPeticion('topes', 'getTopes', datos, function(r){
            let fila = ''
            r.data.map(registro => {
                fila += `<tr id=${registro.id}>
                            <td>${registro.vigencia}</td>
                            <td>${registro.gerenciaAportante}</td>
                            <td>${registro.gerenciaEjecutora}</td>
                            <td class="text-right">$${currency(registro.valor,0)}</td>
                            <td>
                                <button class="btn btn-default" onClick="mostrarModalEditarTopes(${registro.id},${registro.vigencia})" title="Editar">
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

    function mostrarModalEditarTopes(idTopes){
        id = idTopes        
        llenarFormulario('formularioTopes', 'topes', 'select', {info:{id: idTopes}}, function(r){
            $('#modalTopesTitulo').text('Editar tope')
            $('#botonGuardarTopes').hide()
            $('#botonActualizarTopes').show()
            $('#modalTopes').modal('show')
        })
    }
</script>
</body>
</html>