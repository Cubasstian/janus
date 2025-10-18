<?php require('views/header.php');?>

<link rel="stylesheet" href="dist/css/proceso-moderno.css">

<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
  <div class="col-sm-6"><h1>Proceso - RP <span class="badge badge-secondary">Estados 14 / 15</span></h1></div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="main/inicio/">Inicio</a></li>
            <li class="breadcrumb-item active">RP</li>
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
          <div class="card shadow-lg" style="border: 2px solid #000; border-radius: 12px;">
            <div class="card-header card-header-clean">
              <h3 class="card-title">
                <i class="fas fa-file-invoice-dollar mr-2"></i>Expedir RP
              </h3>
            </div>
            <div class="card-body form-modern">
              <form id="formExpedirRP">
                <div class="alert alert-warning py-1 px-2 d-none" id="alertPrereqExpedirRP"></div>
                <div class="form-group">
                  <label for="numero_rp">Número RP</label>
                  <input type="text" class="form-control" id="numero_rp" name="numero_rp" required>
                </div>
                <div class="form-group">
                  <label for="fecha_rp">Fecha RP</label>
                  <input type="date" class="form-control" id="fecha_rp" name="fecha_rp" required>
                </div>
                <div class="form-group">
                  <label for="observaciones_rp">Observaciones</label>
                  <textarea class="form-control" id="observaciones_rp" name="observaciones_rp" rows="2"></textarea>
                </div>
                <button type="submit" class="btn btn-primary" id="btnExpedir" disabled>Guardar RP</button>
                <small id="ayudaExpedirRP" class="form-text text-muted" style="display:none;">Ya existe un RP expedido.</small>
              </form>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="card card-outline card-success">
            <div class="card-header">Recoger RP</div>
            <div class="card-body">
              <form id="formRecogerRP">
                <div class="alert alert-warning py-1 px-2 d-none" id="alertPrereqRecogerRP"></div>
                <div class="form-group">
                  <label for="fecha_recoger_rp">Fecha de recepción</label>
                  <input type="date" class="form-control" id="fecha_recoger_rp" name="fecha_recoger_rp" required>
                </div>
                <div class="form-group">
                  <label for="observaciones_recoger_rp">Observaciones</label>
                  <textarea class="form-control" id="observaciones_recoger_rp" name="observaciones_recoger_rp" rows="2"></textarea>
                </div>
                <button type="submit" class="btn btn-primary" id="btnRecoger" disabled>Registrar recepción</button>
                <small id="ayudaRecogerRP" class="form-text text-muted" style="display:none;">La recepción del RP ya fue registrada.</small>
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
    // Buscar proceso
    $('#formBuscarProceso').on('submit', function(e){
      e.preventDefault()
      var idp = parseInt($('#idProceso').val(),10)
      if(!idp){ toastr.warning('Ingrese un ID de proceso válido'); return }
      enviarPeticion('procesos', 'getProcesos', {criterio:'id', id: idp}, function(r){
        if(!(r.ejecuto && r.data && r.data.length)){
          toastr.error('No se encontró el proceso'); idProcesoSel = 0
          $('#infoProceso').hide(); $('#btnExpedir,#btnRecoger').prop('disabled', true)
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
            if(detalleProceso.numero_rp){
              $('#numero_rp').val(detalleProceso.numero_rp)
              if(detalleProceso.fecha_rp){ $('#fecha_rp').val(detalleProceso.fecha_rp) }
              if(detalleProceso.observaciones_rp){ $('#observaciones_rp').val(detalleProceso.observaciones_rp) }
              $('#formExpedirRP :input').prop('disabled', true)
              $('#ayudaExpedirRP').show()
            }else{ $('#btnExpedir').prop('disabled', false) }
            if(detalleProceso.fecha_recoger_rp){
              if(detalleProceso.fecha_recoger_rp){ $('#fecha_recoger_rp').val(detalleProceso.fecha_recoger_rp) }
              if(detalleProceso.observaciones_recoger_rp){ $('#observaciones_recoger_rp').val(detalleProceso.observaciones_recoger_rp) }
              $('#formRecogerRP :input').prop('disabled', true)
              $('#ayudaRecogerRP').show()
            }else{ $('#btnRecoger').prop('disabled', false) }
          }else{ $('#btnExpedir,#btnRecoger').prop('disabled', false) }
          $('#infoProceso').show()
        })
      })
    })

    // Expedir RP
    $('#formExpedirRP').on('submit', function(e){
      e.preventDefault()
      if(!idProcesoSel){ toastr.warning('Primero busque y seleccione un proceso'); return }
      var datos = parsearFormulario($(this))
      datos.idProceso = idProcesoSel
      enviarPeticion('procesos','checkPrerequisitos',{accion:'expedirRP', idProceso:idProcesoSel}, function(pr){
        if(pr.ejecuto && (pr.faltantes?.length || pr.pendientes?.length)){
          var msg=[]; if(pr.faltantes.length) msg.push('Faltan: '+pr.faltantes.join(', ')); if(pr.pendientes.length) msg.push('Pendientes: '+pr.pendientes.join(', '));
          $('#alertPrereqExpedirRP').removeClass('d-none').html(msg.join('<br>'));
          return;
        }
        enviarPeticion('procesos', 'expedirRP', datos, function(r){
          if(r.ejecuto){
            Swal.fire({ icon:'success', title:'RP expedido', text: 'Se actualizó el proceso y avanzó el estado.' })
            $('#formExpedirRP :input').prop('disabled', true)
            $('#ayudaExpedirRP').show()
          }else{ mostrarError(r) }
        })
      })
    })

    // Recoger RP
    $('#formRecogerRP').on('submit', function(e){
      e.preventDefault()
      if(!idProcesoSel){ toastr.warning('Primero busque y seleccione un proceso'); return }
      var datos = parsearFormulario($(this))
      datos.idProceso = idProcesoSel
      enviarPeticion('procesos','checkPrerequisitos',{accion:'recogerRP', idProceso:idProcesoSel}, function(pr){
        if(pr.ejecuto && (pr.faltantes?.length || pr.pendientes?.length)){
          var msg=[]; if(pr.faltantes.length) msg.push('Faltan: '+pr.faltantes.join(', ')); if(pr.pendientes.length) msg.push('Pendientes: '+pr.pendientes.join(', '));
          $('#alertPrereqRecogerRP').removeClass('d-none').html(msg.join('<br>'));
          return;
        }
        enviarPeticion('procesos', 'recogerRP', datos, function(r){
          if(r.ejecuto){
            Swal.fire({ icon:'success', title:'RP recibido', text: 'Se actualizó el proceso y avanzó el estado.' })
            $('#formRecogerRP :input').prop('disabled', true)
            $('#ayudaRecogerRP').show()
          }else{ mostrarError(r) }
        })
      })
    })
  }
</script>
</body>
</html>

