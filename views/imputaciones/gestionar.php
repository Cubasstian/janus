<?php require('views/header.php');?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        Imputaciones
                        <button id="botonMostrarModalImputaciones" type="button" class="btn btn-primary">
                            Crear
                        </button>
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="main/inicio/">Inicio</a></li>
                        <li class="breadcrumb-item active">Imputaciones</li>
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
                                <table id="tabla" class="table table-bordered table-sm text-xs">
                                    <thead>
                                        <tr class="text-center">
                                            <th>Imputación</th>
                                            <th width="50%">Máximo</th>
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

    <div class="modal fade" id="modalImputaciones">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalImputacionesTitulo"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formularioImputaciones">
                        <div class="form-group">
                            <label for="imputacion">Imputación</label>
                            <input type="text" class="form-control" name="imputacion" id="imputacion" required="required">
                        </div>
                        <div class="form-group">
                            <label for="maximo" id="mascara">Máximo</label>
                            <input type="number" class="form-control" name="maximo" id="maximo" required="required">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-submit" id="botonGuardarImputaciones" form="formularioImputaciones">Guardar</button>
                    <button type="submit" class="btn btn-secondary btn-submit" id="botonActualizarImputaciones" form="formularioImputaciones">Actualizar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require('views/footer.php');?>

<script type="text/javascript">
    var id = 0
    var boton = ''
    function init(info){
        //Llenar vigencias
        llenarSelect('vigencias', 'select', {info:{estado: 'Activo'}}, 'vigencia', 'vigencia', 1)

        $('#vigencia').on('change', function(){
            vigencia = $(this).val()
            $('#contenido').empty()
            if($(this).val() == ""){
                toastr.error('Debe escoger una vigencia')
            }else{
                cargarRegistros({criterio: 'vigencia', valor: vigencia}, 'crear', function(){
                    enviarPeticion('necesidadesImputaciones', 'getTodas', {vigencia: vigencia}, function(r){
                        let safeSelector = ''
                        let porcentaje = 0
                        r.data.map(registro => {
                            safeSelector = escapeJquerySelector(registro.imputacion);
                            $('#label_'+safeSelector).html(`<b>$${currency($('#barra_'+safeSelector).data('maximo'),0)}</b>/$${currency(registro.comprometido,0)}`)
                            porcentaje = (registro.comprometido / $('#barra_'+safeSelector).data('maximo'))*100
                            $('#barra_'+safeSelector).animate({ width: porcentaje +'%' }, 1000);
                        })
                    })
                })
            }
        })

        //Formatear input
        $('#maximo').on('keyup', function(r){
            $('#mascara').text('Valor: $' + currency($('#maximo').val(),0))
        })

        $('#botonMostrarModalImputaciones').on('click', function(){
            if($('#vigencia').val() == ""){
                toastr.error('Debe escoger una vigencia')
            }else{
                $('#formularioImputaciones')[0].reset()
                $('#modalImputacionesTitulo').text('Nueva imputación vigencia '+ $('#vigencia option:selected').text())
                $('#botonGuardarImputaciones').show()
                $('#botonActualizarImputaciones').hide()
                $('#modalImputaciones').modal('show')
            }
        })

        $('.btn-submit').on('click', function(){
            boton = $(this).attr('id')
        })      

        $('#formularioImputaciones').on('submit', function(e){
            e.preventDefault()
            let datos = parsearFormulario($(this))
            datos.fk_vigencias = vigencia
            if(boton == 'botonGuardarImputaciones'){
                enviarPeticion('imputaciones', 'insert', {info: datos}, function(r){
                    toastr.success('Se creo correctamente')
                    cargarRegistros({criterio: 'id', valor: r.insertId}, 'crear', function(){
                        $('#modalImputaciones').modal('hide')
                    })
                })
            }else{                
                enviarPeticion('imputaciones', 'update', {info: datos, id: id}, function(r){
                    toastr.success('Se actualizó correctamente')
                    cargarRegistros({criterio: 'id', valor: id}, 'actualizar', function(){
                        $('#modalImputaciones').modal('hide')
                    })
                })
            }
        })
    }

    function cargarRegistros(datos, accion, callback){
        enviarPeticion('imputaciones', 'getImputaciones', datos, function(r){
            let fila = ''
            r.data.map(registro => {
                fila += `<tr id=${registro.id}>
                            <td>${registro.imputacion}</td>
                            <td>
                                <div class="progress-group">
                                    <span class="progress-text">&nbsp;</span>
                                    <span id="label_${registro.imputacion}" class="float-right">$${currency(registro.maximo,0)}</span>
                                    <div class="progress progress-sm">
                                        <div id="barra_${registro.imputacion}" class="progress-bar bg-success" style="width: 0%" data-maximo="${registro.maximo}"></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <button class="btn btn-default" onClick="mostrarModalEditarImputaciones(${registro.id})" title="Editar">
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

    function mostrarModalEditarImputaciones(idImputaciones){
        id = idImputaciones
        llenarFormulario('formularioImputaciones', 'imputaciones', 'select', {info:{id: idImputaciones}}, function(r){
            $('#modalImputacionesTitulo').text('Editar Imputación')
            $('#botonGuardarImputaciones').hide()
            $('#botonActualizarImputaciones').show()
            $('#modalImputaciones').modal('show')
        })
    }

    function escapeJquerySelector(id) {
        return id.replace(/([:.\\[\],])/g, '\\$1');
    }
</script>
</body>
</html>