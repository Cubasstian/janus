<?php require('views/header.php');?>

<link rel="stylesheet" href="dist/css/proceso-moderno.css">

<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
  <div class="col-sm-6"><h1>Proceso - Acta de inicio <span class="badge badge-secondary">Estado 16</span> <span id="badgeOverride" class="badge badge-danger d-none" title="Transiciones forzadas">Override</span></h1></div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="main/inicio/">Inicio</a></li>
            <li class="breadcrumb-item active">Acta de inicio</li>
          </ol>
        </div>
      </div>
    </div>
  </section>
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-6">
          <div class="card card-outline card-success">
            <div class="card-header">Buscar proceso</div>
            <div class="card-body">
              <form id="formBuscarProceso">
                <div class="form-row align-items-end">
                  <div class="form-group col-md-6">
                    <label for="idProceso">ID Proceso</label>
                    <input type="number" class="form-control" id="idProceso" min="1" placeholder="Ej: 123" required>
                  </div>
                  <div class="form-group col-md-4">
                    <button type="submit" class="btn btn-success btn-block" id="btnBuscar">Buscar</button>
                  </div>
                </div>
              </form>
              <div id="infoProceso" style="display:none;">
                <hr>
                <ul class="list-unstyled">
                  <li><b>Proceso:</b> <span id="lblProceso"></span></li>
                  <li><b>Gerencia:</b> <span id="lblGerencia"></span></li>
                  <li><b>Estado actual:</b> <span id="lblEstado"></span></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card card-outline card-success">
            <div class="card-header">Datos del acta</div>
            <div class="card-body">
              <form id="formActaInicio">
                <div class="alert alert-warning py-1 px-2 d-none" id="alertPrereqActa"></div>
                <div class="form-group">
                  <label for="numero_acta_inicio">Número de acta (opcional)</label>
                  <input type="text" class="form-control" id="numero_acta_inicio" name="numero_acta_inicio" placeholder="Ej: AI-2025-001">
                </div>
                <div class="form-group">
                  <label for="fecha_acta_inicio">Fecha de acta</label>
                  <input type="date" class="form-control" id="fecha_acta_inicio" name="fecha_acta_inicio">
                </div>
                <div class="form-group">
                  <label for="observaciones_acta_inicio">Observaciones</label>
                  <textarea class="form-control" id="observaciones_acta_inicio" name="observaciones_acta_inicio" rows="3" placeholder="Notas del acta"></textarea>
                </div>
                <div class="d-flex align-items-center">
                  <button type="submit" class="btn btn-primary mr-2" id="btnGuardar" disabled>Guardar acta</button>
                  <button type="button" class="btn btn-outline-secondary mr-2" id="btnGenerarPDF" disabled>Generar PDF</button>
                  <button type="button" class="btn btn-outline-info" id="btnVerPDF" disabled>Ver PDF</button>
                </div>
                <small id="ayudaActa" class="form-text text-muted" style="display:none;">El acta de inicio ya fue registrada.</small>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<?php require('views/footer.php');?>

<script type="text/javascript">
  var idProcesoSel = 0, detalleProceso = null
  function init(){
    $('#formBuscarProceso').on('submit', function(e){
      e.preventDefault()
      var idp = parseInt($('#idProceso').val(),10)
      if(!idp){ toastr.warning('Ingrese un ID de proceso válido'); return }
      enviarPeticion('procesos', 'getProcesos', {criterio:'id', id: idp}, function(r){
        if(!(r.ejecuto && r.data && r.data.length)){
          toastr.error('No se encontró el proceso')
          $('#infoProceso').hide(); idProcesoSel = 0
          $('#btnGuardar').prop('disabled', true)
          $('#btnGenerarPDF').prop('disabled', true)
          $('#btnVerPDF').prop('disabled', true)
          $('#badgeOverride').addClass('d-none')
          return
        }
        var p = r.data[0]
        idProcesoSel = p.id
        $('#lblProceso').text(p.id)
        $('#lblGerencia').text(p.gerencia || '')
        var estadoTxt = (typeof estados !== 'undefined' && estados[p.estado]) ? estados[p.estado] : p.estado
        $('#lblEstado').text(estadoTxt || '')
        enviarPeticion('procesos','getProcesoDetalle',{id:idProcesoSel}, function(det){
          if(det.ejecuto && det.data && det.data.length){
            detalleProceso = det.data[0]
            if(detalleProceso.numero_acta_inicio){ $('#numero_acta_inicio').val(detalleProceso.numero_acta_inicio) }
            if(detalleProceso.fecha_acta_inicio){ $('#fecha_acta_inicio').val(detalleProceso.fecha_acta_inicio) }
            if(detalleProceso.observaciones_acta_inicio){ $('#observaciones_acta_inicio').val(detalleProceso.observaciones_acta_inicio) }
            if(detalleProceso.fecha_acta_inicio || detalleProceso.numero_acta_inicio){
              $('#formActaInicio :input').prop('disabled', true)
              $('#ayudaActa').show()
              $('#btnGenerarPDF').prop('disabled', false)
            }else{ $('#btnGuardar').prop('disabled', false) }
            verificarDocActa(false)
            verificarOverrideGeneric(idProcesoSel, '#badgeOverride')
          }else{ $('#btnGuardar').prop('disabled', false); verificarOverrideGeneric(idProcesoSel, '#badgeOverride') }
          $('#infoProceso').show()
        })
      })
    })

    $('#formActaInicio').on('submit', function(e){
      e.preventDefault()
      if(!idProcesoSel){ toastr.warning('Primero busque y seleccione un proceso'); return }
      var datos = parsearFormulario($(this))
      datos.idProceso = idProcesoSel
      enviarPeticion('procesos','checkPrerequisitos',{accion:'actaInicio', idProceso:idProcesoSel}, function(pr){
        if(pr.ejecuto && (pr.faltantes?.length || pr.pendientes?.length)){
          var msg=[]; if(pr.faltantes.length) msg.push('Faltan: '+pr.faltantes.join(', ')); if(pr.pendientes.length) msg.push('Pendientes: '+pr.pendientes.join(', '));
          $('#alertPrereqActa').removeClass('d-none').html(msg.join('<br>'));
          return;
        }
        enviarPeticion('procesos', 'actaInicio', datos, function(r){
          if(r.ejecuto){
            Swal.fire({ icon:'success', title:'Acta guardada', text: 'Se actualizó el proceso y avanzó el estado.' })
            $('#formActaInicio :input').prop('disabled', true)
            $('#ayudaActa').show()
            $('#btnGenerarPDF').prop('disabled', false)
            verificarOverrideGeneric(idProcesoSel, '#badgeOverride', {force:true})
          }else{ mostrarError(r) }
        })
      })
    })

    $('#btnGenerarPDF').on('click', function(){
      if(!idProcesoSel){ toastr.warning('Primero busque y seleccione un proceso'); return }
      toastr.info('Generando PDF...', {timeOut: 0})
      enviarPeticion('procesos','generarActaInicioPDF',{idProceso: idProcesoSel}, function(r){
        toastr.clear()
        if(r.ejecuto){
          toastr.success('Acta de inicio generada')
          $('#btnVerPDF').prop('disabled', false).data('docid', r.idDocumento)
          downloadDocument(r.idDocumento)
          verificarOverrideGeneric(idProcesoSel, '#badgeOverride', {force:true})
        }else{ mostrarError(r) }
      })
    })

    $('#btnVerPDF').on('click', function(){
      const idDoc = $('#btnVerPDF').data('docid')
      if(idDoc){ downloadDocument(idDoc) }
      else{ verificarDocActa(true) }
    })

    function verificarDocActa(openIfFound){
      if(!detalleProceso){ return }
      const idPS = detalleProceso.contratista
      if(!idPS){ return }
      enviarPeticion('documentosTipo','select',{info:{nombre:'Acta de inicio'}}, function(dt){
        if(!(dt.ejecuto && dt.data && dt.data.length)){
          enviarPeticion('documentosTipo','select',{info:{nombre:'Acta de Inicio'}}, function(dt2){
            if(!(dt2.ejecuto && dt2.data && dt2.data.length)) return
            const tipoId2 = dt2.data[0].id
            buscarDoc(tipoId2, openIfFound)
          })
          return
        }
        const tipoId = dt.data[0].id
        buscarDoc(tipoId, openIfFound)
      })
      function buscarDoc(tipoId, open){
        enviarPeticion('documentos','select',{info:{contratista:idPS, fk_procesos:idProcesoSel, fk_documentos_tipo: tipoId}}, function(r2){
          if(r2.ejecuto && r2.data && r2.data.length){
            const idDoc = r2.data[0].id
            $('#btnVerPDF').prop('disabled', false).data('docid', idDoc)
            if(open){ downloadDocument(idDoc) }
          }
        })
      }
    }
  }
</script>
</body>
</html>

