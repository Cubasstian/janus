<?php require('views/header.php');?>

<link rel="stylesheet" href="dist/css/proceso-moderno.css">

<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6"><h1>Proceso - Elaborar minuta <span class="badge badge-secondary">Estado 10</span> <span id="badgeOverride" class="badge badge-danger d-none" title="Transiciones forzadas en el proceso">Override</span></h1></div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="main/inicio/">Inicio</a></li>
            <li class="breadcrumb-item active">Minuta</li>
          </ol>
        </div>
      </div>
    </div>
  </section>
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-6">
          <div class="card shadow-lg" style="border: 2px solid #000; border-radius: 12px;">
            <div class="card-header card-header-clean">
              <h3 class="card-title">
                <i class="fas fa-search mr-2"></i>Buscar Proceso
              </h3>
            </div>
            <div class="card-body form-modern">
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
          <div class="card shadow-lg" style="border: 2px solid #000; border-radius: 12px;">
            <div class="card-header card-header-clean">
              <h3 class="card-title">
                <i class="fas fa-file-contract mr-2"></i>Datos de Minuta
              </h3>
            </div>
            <div class="card-body form-modern">
              <form id="formMinuta">
                <div class="alert alert-warning py-1 px-2 d-none" id="alertPrereqMinuta"></div>
                <div class="form-group">
                  <label for="fecha_minuta">Fecha de minuta</label>
                  <input type="date" class="form-control" id="fecha_minuta" name="fecha_minuta">
                </div>
                <div class="form-group">
                  <label for="observaciones_minuta">Observaciones</label>
                  <textarea class="form-control" id="observaciones_minuta" name="observaciones_minuta" rows="3" placeholder="Notas de la minuta"></textarea>
                </div>
                <div class="d-flex align-items-center">
                  <button type="submit" class="btn btn-primary mr-2" id="btnGuardar" disabled>Guardar minuta</button>
                  <button type="button" class="btn btn-outline-secondary mr-2" id="btnGenerarPDF" disabled>Generar PDF</button>
                  <button type="button" class="btn btn-outline-info" id="btnVerPDF" disabled>Ver PDF</button>
                </div>
                <small class="form-text text-muted" id="ayudaMinuta" style="display:none;">La minuta ya fue registrada. Los datos se muestran en modo solo lectura.</small>
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
        // Traer detalle completo para prefill
        enviarPeticion('procesos','getProcesoDetalle',{id:idProcesoSel}, function(det){
          if(det.ejecuto && det.data && det.data.length){
            detalleProceso = det.data[0]
            $('#alertPrereqMinuta').addClass('d-none').empty();
            enviarPeticion('procesos','checkPrerequisitos',{accion:'minuta', idProceso:idProcesoSel}, function(pr){
              if(pr.ejecuto && (pr.faltantes?.length || pr.pendientes?.length)){
                var msg=[]; if(pr.faltantes.length) msg.push('Faltan: '+pr.faltantes.join(', ')); if(pr.pendientes.length) msg.push('Pendientes: '+pr.pendientes.join(', '));
                $('#alertPrereqMinuta').removeClass('d-none').html(msg.join('<br>'));
              }
            })
            if(detalleProceso.fecha_minuta){ $('#fecha_minuta').val(detalleProceso.fecha_minuta) }
            if(detalleProceso.observaciones_minuta){ $('#observaciones_minuta').val(detalleProceso.observaciones_minuta) }
            if(detalleProceso.fecha_minuta || detalleProceso.observaciones_minuta){
              $('#formMinuta :input').prop('disabled', true)
              $('#ayudaMinuta').show()
              $('#btnGenerarPDF').prop('disabled', false)
            }else{ $('#btnGuardar').prop('disabled', false) }
            if(detalleProceso.fecha_minuta || detalleProceso.observaciones_minuta){ $('#btnGenerarPDF').prop('disabled', false) }
            verificarDocMinuta(false)
            verificarOverrideGeneric(idProcesoSel, '#badgeOverride')
          }else{ $('#btnGuardar').prop('disabled', false); verificarOverrideGeneric(idProcesoSel, '#badgeOverride') }
          $('#infoProceso').show()
        })
      })
    })

    // Guardar Minuta
    $('#formMinuta').on('submit', function(e){
      e.preventDefault()
      if(!idProcesoSel){ toastr.warning('Primero busque y seleccione un proceso'); return }
      var datos = parsearFormulario($(this))
      datos.idProceso = idProcesoSel
      enviarPeticion('procesos','checkPrerequisitos',{accion:'minuta', idProceso:idProcesoSel}, function(pr){
        if(pr.ejecuto && (pr.faltantes?.length || pr.pendientes?.length)){
          var msg=[]; if(pr.faltantes.length) msg.push('Faltan: '+pr.faltantes.join(', ')); if(pr.pendientes.length) msg.push('Pendientes: '+pr.pendientes.join(', '));
          $('#alertPrereqMinuta').removeClass('d-none').html(msg.join('<br>'));
          return;
        }
        enviarPeticion('procesos', 'minuta', datos, function(r){
          if(r.ejecuto){
            Swal.fire({ icon:'success', title:'Minuta guardada', text: 'Se actualizó el proceso y avanzó el estado.' })
            $('#formMinuta :input').prop('disabled', true)
            $('#ayudaMinuta').show()
            $('#btnGenerarPDF').prop('disabled', false)
            verificarOverrideGeneric(idProcesoSel, '#badgeOverride', {force:true})
          }else{ mostrarError(r) }
        })
      })
    })

    // Generar PDF de Minuta
    $('#btnGenerarPDF').on('click', function(){
      if(!idProcesoSel){ toastr.warning('Primero busque y seleccione un proceso'); return }
      toastr.info('Generando PDF...', {timeOut: 0})
      enviarPeticion('procesos','generarMinutaPDF',{idProceso: idProcesoSel}, function(r){
        toastr.clear()
        if(r.ejecuto){
          toastr.success('Minuta generada')
          $('#btnVerPDF').prop('disabled', false).data('docid', r.idDocumento)
          downloadDocument(r.idDocumento)
          verificarOverrideGeneric(idProcesoSel, '#badgeOverride', {force:true})
        }else{ mostrarError(r) }
      })
    })

    // Ver PDF de Minuta
    $('#btnVerPDF').on('click', function(){ const idDoc = $('#btnVerPDF').data('docid'); if(idDoc){ downloadDocument(idDoc) } else { verificarDocMinuta(true) } })

    function verificarDocMinuta(openIfFound){
      if(!detalleProceso){ return }
      const idPS = detalleProceso.contratista; if(!idPS){ return }
      enviarPeticion('documentosTipo','select',{info:{nombre:'Minuta'}}, function(dt){ if(!(dt.ejecuto && dt.data && dt.data.length)) return; const tipoId = dt.data[0].id; enviarPeticion('documentos','select',{info:{contratista:idPS, fk_procesos:idProcesoSel, fk_documentos_tipo: tipoId}}, function(r2){ if(r2.ejecuto && r2.data && r2.data.length){ const idDoc = r2.data[0].id; $('#btnVerPDF').prop('disabled', false).data('docid', idDoc); if(openIfFound){ downloadDocument(idDoc) } } }) })
    }
  }
</script>
</body>
</html>

