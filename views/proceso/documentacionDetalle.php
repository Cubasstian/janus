<?php require('views/header.php');?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Revisar documentación</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="main/inicio/">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="proceso/documentacion/">Documentación</a></li>
                        <li class="breadcrumb-item active">Detalle</li>
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
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Proceso</th>
                                            <th>Gerencia</th>
                                            <th>Nombre</th>
                                            <th>Cédula</th>
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

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-5">
                    <div class="card card-outline card-success">
                        <div class="card-header">
                            <h3 class="card-title">Documentos generales</h3>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-responsive">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Estado</th>
                                        <th>Revisiones</th>
                                        <th>Opciones</th>
                                    </tr>
                                </thead>
                                <tbody id="generales"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="card card-outline card-success">
                        <div class="card-header">
                            <h3 class="card-title">Documentos del proceso</h3>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-responsive">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Estado</th>
                                        <th>Revisiones</th>
                                        <th>Opciones</th>
                                    </tr>
                                </thead>
                                <tbody id="documentosProceso"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4"></div>
                <div class="col-4">
                    <button type="button" class="btn btn-success btn-block btn-lg mb-5" onclick="cerrarRevision()">Finalizar</button>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="modalRevision">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalRevisionTitulo"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formularioRevision">
                        <div class="form-group">
                            <label for="estado">Estado</label>
                            <select class="form-control" name="estado" id="estado" required="required">
                                <option value="">Seleccione...</option>
                                <option value="2">Aceptado</option>
                                <option value="3">Rechazado</option>
                            </select>
                        </div>
                        <div class="form-group" style="display: none;" id="panelMotivo">
                            <label for="observaciones">Motivo</label><small>(Max 250 caracteres)</small>
                            <textarea class="form-control" rows="3" name="observaciones" maxlength="250"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" form="formularioRevision" id="botonEnviar">Guardar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require('views/footer.php');?>

<script src="dist/js/solicitudes.js"></script>
<script type="text/javascript">
    var id = 0
    var idP = 0
    var idS = 0
    var idPS = 0
    function init(info){
        idS = JSON.parse(sessionStorage.getItem('solicitud'))

        //Consulto los datos de la solicitud
        enviarPeticion('solicitudes', 'getSolicitudes', {criterio: 'id', id: idS}, function(r){
            idP = r.data[0].idProceso
            idPS = r.data[0].idPS
            fila = `<tr>
                        <td>${r.data[0].idProceso}</td>
                        <td>${r.data[0].gerencia}</td>
                        <td>${r.data[0].ps}</td>
                        <td>${r.data[0].cedula}</td>
                    </tr>`
            $('#contenido').html(fila)
            //Cargar documentos generales
            mostrarDocumentos(1,1,r.data[0].idPS,'generales')
            //Cargar documentos del proceso
            mostrarDocumentos(0,r.data[0].idProceso,r.data[0].idPS,'documentosProceso')
        })

        $('#estado').on('change', function(){
            if($(this).val() == 2){
                $('#panelMotivo').hide()
            }else{
                $('#panelMotivo').show()
            }
        })

        $('#formularioRevision').on('submit', function(e){
            $('#botonEnviar').prop('disabled', true);
            e.preventDefault()
            let datos = parsearFormulario($(this))
            datos.id = id
            enviarPeticion('documentos', 'setEstado', datos, function(r){
                cargarRegistrosDocumentos({criterio: 'id', id: id})
                $('#modalRevision').modal('hide')
                toastr.success('Se actualizó correctamente')
            })
        })
    }

    function mostrarDocumentos(permanente, proceso, contratista, elemento){
        enviarPeticion('documentosTipo', 'select', {info:{is_permanente: permanente}}, function(r){
            let fila = ''
            r.data.map(registro => {
                fila += `<tr>
                            <td>${registro.numero}. ${registro.nombre}</td>
                            <td id="estado_${registro.id}">
                                <span class="badge bg-secondary">No cargado</span>
                            </td>
                            <td id="conteo_${registro.id}" class="text-center"></td>
                            <td>
                                <div style="display: flex; gap: 10px;">
                                    <div style="width: 50px;" id="btnVer_${registro.id}"></div>
                                    <div style="width: 50px;" id="btnRevisar_${registro.id}"></div>
                                </div>
                            </td>
                        </tr>`
            })
            $(`#${elemento}`).html(fila)
            cargarRegistrosDocumentos({criterio: 'generales', proceso: proceso, contratista: contratista})
        })
    }

    function cargarRegistrosDocumentos(datos){
        enviarPeticion('documentos', 'getDocumentos', datos, function(r){
            r.data.map(registro => {
                $('#estado_'+registro.tipo).html(`<span class="badge bg-${coloresEstadoDocumentos[registro.estado]}">${estadoDocumentos[registro.estado]}</span>`)
                $('#conteo_'+registro.tipo).text(registro.conteo)
                if(registro.estado != 0){
                    $('#btnVer_'+registro.tipo).html(`<button type="button" class="btn btn-default" onClick="downloadDocument(${registro.id})" title="Ver documento">
                                                        <i class="far fa-eye"></i>
                                                    </button>`)
                }
                if(registro.estado == 1){
                    $('#btnRevisar_'+registro.tipo).html(`<button type="button" class="btn btn-default" onClick="revisar(${registro.id},${registro.numero})" title="Registrar revisión">
                                                            <i class="far fa-check-circle"></i>
                                                        </button>`)
                }else{
                    $('#btnRevisar_'+registro.tipo).html('')
                }
            })
        })
    }

    function revisar(idDocumento, numero){
        id = idDocumento
        $('#modalRevisionTitulo').text(`Registrar revisión documento #${numero}`)
        $('#formularioRevision')[0].reset();
        $('#panelMotivo').hide()
        $('#botonEnviar').prop('disabled', false);
        $('#modalRevision').modal('show')
    }

    function cerrarRevision(){
        enviarPeticion('documentos', 'getDocumentos', {criterio: 'generales', proceso: idP, contratista: idPS}, function(r){
            const cantidadOK = r.data.filter(obj => obj.estado === 2).length;
            //if(cantidadOK == 14){
                //Paso la solicitud a crear tercero
                enviarPeticion('solicitudes', 'setEstado', {info: {estado: 3}, id: idS}, function(r){
                    url = 'proceso/documentacion/'
                    window.open(url, '_self')
                })
            /*}else{
                toastr.error("Faltan documentos por aprobar")
            }*/
        })
    }
</script>
</body>
</html>