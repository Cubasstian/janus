<?php require('views/header.php');?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
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
        <div class="container-fluid">
            <div class="row">   
                <div class="col">
                    <div class="card card-kit">
                        <div class="card-header card-header-clean">
                            <h3 class="card-title">
                                <i class="fas fa-user"></i>
                                Información del Contratista
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="data-table">
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
                    <div class="card card-kit">
                        <div class="card-header card-header-clean">
                            <h3 class="card-title">
                                <i class="fas fa-file-alt"></i>
                                Documentos generales
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="data-table">
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
                </div>
                <div class="col-md-7">
                    <div class="card card-kit">
                        <div class="card-header card-header-clean">
                            <h3 class="card-title">
                                <i class="fas fa-folder-open"></i>
                                Documentos del proceso
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="data-table">
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
            </div>
            <div class="row">
                <div class="col-sm-4"></div>
                <div class="col-4">
                    <button type="button" class="btn-kit btn-kit-primary btn-lg w-100 mb-5" onclick="cerrarRevision()">
                        <i class="fas fa-check mr-2"></i>Finalizar Revisión
                    </button>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="modalRevision">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalRevisionTitulo">
                        <i class="fas fa-clipboard-check mr-2"></i>
                        <span>Registrar revisión</span>
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formularioRevision">
                        <div class="form-group-kit">
                            <label for="estado">Estado</label>
                            <select class="input-kit" name="estado" id="estado" required="required">
                                <option value="">Seleccione...</option>
                                <option value="2">Aceptado</option>
                                <option value="3">Rechazado</option>
                            </select>
                        </div>
                        <div class="form-group-kit" style="display: none;" id="panelMotivo">
                            <label for="observaciones">Motivo <small>(Max 250 caracteres)</small></label>
                            <textarea class="input-kit" rows="3" name="observaciones" maxlength="250"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" form="formularioRevision" id="botonEnviar">
                        <i class="fas fa-save mr-1"></i>
                        Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Visor de Documentos -->
    <div class="modal fade" id="modalVisorPDF" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl" role="document" style="max-width: 95%; height: 90vh;">
            <div class="modal-content" style="height: 100%;">
                <div class="modal-header">
                    <h4 class="modal-title">
                        <i class="fas fa-file-pdf mr-2 text-danger"></i>
                        <span id="nombreDocumento">Visor de Documento</span>
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="height: calc(100% - 120px); padding: 0;">
                    <iframe id="pdfViewer" style="width: 100%; height: 100%; border: none;"></iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i>
                        Cerrar
                    </button>
                    <button type="button" class="btn btn-primary" onclick="descargarDocumentoActual()">
                        <i class="fas fa-download mr-1"></i>
                        Descargar
                    </button>
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
    
    // Definir estados y colores para documentos
    var estadoDocumentos = ['No cargado', 'Pendiente', 'Aceptado', 'Rechazado'];
    var coloresEstadoDocumentos = ['secondary', 'warning', 'success', 'danger'];
    
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
            e.preventDefault()
            $('#botonEnviar').prop('disabled', true);
            let datos = parsearFormulario($(this))
            datos.id = id
            // Si es Aceptado y no hay observaciones, colocar una por defecto
            if((datos.estado+"") === '2' && (!datos.observaciones || datos.observaciones.trim() === '')){
                datos.observaciones = 'Aceptado'
            }
            enviarPeticion('documentos', 'setEstado', datos, function(r){
                if(!(r && r.ejecuto)){
                    toastr.error(r && r.mensajeError ? r.mensajeError : 'No fue posible actualizar el estado del documento')
                    $('#botonEnviar').prop('disabled', false);
                    return
                }
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
                    $('#btnVer_'+registro.tipo).html(`<button type="button" class="btn-kit btn-kit-outline-info btn-sm" onClick="viewDocument(${registro.id})" title="Ver documento">
                                                        <i class="far fa-eye"></i>
                                                    </button>`)
                }
                if(registro.estado == 1){
                    $('#btnRevisar_'+registro.tipo).html(`<button type="button" class="btn-kit btn-kit-outline-success btn-sm" onClick="revisar(${registro.id},${registro.numero})" title="Registrar revisión">
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
        $('#modalRevisionTitulo span').text(`Registrar revisión documento #${numero}`)
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

    // Función para visualizar documento en el navegador
    var documentoActualUrl = null;
    var documentoActualNombre = '';
    
    function viewDocument(documentoId) {
        if (!documentoId) {
            toastr.error('ID de documento no válido');
            return;
        }
        
        // Mostrar loading
        $('#pdfViewer').attr('src', 'about:blank');
        $('#nombreDocumento').text('Cargando documento...');
        $('#modalVisorPDF').modal('show');
        
        // Usar la API para obtener el documento
        enviarPeticion('archivos', 'getDocumento', {id: documentoId}, function(r) {
            if (r.ejecuto && r.file) {
                try {
                    // Decodificar base64 y crear blob
                    const byteCharacters = atob(r.file);
                    const byteNumbers = new Array(byteCharacters.length);
                    for (let i = 0; i < byteCharacters.length; i++) {
                        byteNumbers[i] = byteCharacters.charCodeAt(i);
                    }
                    const byteArray = new Uint8Array(byteNumbers);
                    const blob = new Blob([byteArray], { type: 'application/pdf' });
                    
                    // Crear URL del blob
                    if (documentoActualUrl) {
                        window.URL.revokeObjectURL(documentoActualUrl);
                    }
                    documentoActualUrl = window.URL.createObjectURL(blob);
                    documentoActualNombre = r.nombre || `Documento_${documentoId}.pdf`;
                    
                    // Cargar en el iframe
                    $('#pdfViewer').attr('src', documentoActualUrl);
                    $('#nombreDocumento').text(documentoActualNombre);
                    
                } catch (e) {
                    console.error('Error al procesar el documento:', e);
                    toastr.error('Error al procesar el documento');
                    $('#modalVisorPDF').modal('hide');
                }
            } else {
                toastr.error(r.mensajeError || 'Error al obtener el documento');
                $('#modalVisorPDF').modal('hide');
            }
        });
    }
    
    function descargarDocumentoActual() {
        if (!documentoActualUrl) {
            toastr.error('No hay documento cargado');
            return;
        }
        
        const link = document.createElement('a');
        link.href = documentoActualUrl;
        link.download = documentoActualNombre;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        toastr.success('Descargando documento...');
    }
    
    // Limpiar URL cuando se cierre la modal
    $('#modalVisorPDF').on('hidden.bs.modal', function () {
        if (documentoActualUrl) {
            window.URL.revokeObjectURL(documentoActualUrl);
            documentoActualUrl = null;
        }
        $('#pdfViewer').attr('src', 'about:blank');
    });
</script>
</body>
</html>
