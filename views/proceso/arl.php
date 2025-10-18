<?php require('views/header.php');?>

<link rel="stylesheet" href="dist/css/proceso-moderno.css">

<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
  <div class="col-sm-6"><h1>Proceso - Afiliar ARL <span class="badge badge-secondary">Estado 13</span> <span id="badgeOverride" class="badge badge-danger d-none" title="Transiciones forzadas detectadas">Override</span></h1></div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="main/inicio/">Inicio</a></li>
            <li class="breadcrumb-item active">ARL</li>
          </ol>
        </div>
      </div>
    </div>
  </section>
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-6">
          <div class="card card-modern">
            <div class="card-header card-header-modern">
              <i class="fas fa-search mr-2"></i>Buscar Proceso
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
          <div class="card card-outline card-success">
            <div class="card-header">Datos de afiliación</div>
            <div class="card-body">
              <form id="formARL">
                <div class="alert alert-warning py-1 px-2 d-none" id="alertPrereqARL"></div>
                <div class="form-group">
                  <label for="fecha_arl">Fecha de afiliación</label>
                  <input type="date" class="form-control" id="fecha_arl" name="fecha_arl" required>
                </div>
                <div class="form-group">
                  <label for="observaciones_arl">Observaciones</label>
                  <textarea class="form-control" id="observaciones_arl" name="observaciones_arl" rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn-primary" id="btnGuardar" disabled>Guardar afiliación</button>
                <small id="ayudaARL" class="form-text text-muted" style="display:none;">La afiliación ya fue registrada.</small>
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
          toastr.error('No se encontró el proceso'); idProcesoSel = 0
          $('#infoProceso').hide(); $('#btnGuardar').prop('disabled', true); $('#badgeOverride').addClass('d-none')
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
            if(detalleProceso.fecha_arl){ $('#fecha_arl').val(detalleProceso.fecha_arl) }
            if(detalleProceso.observaciones_arl){ $('#observaciones_arl').val(detalleProceso.observaciones_arl) }
            if(detalleProceso.fecha_arl){ $('#formARL :input').prop('disabled', true); $('#ayudaARL').show() } else { $('#btnGuardar').prop('disabled', false) }
          } else { $('#btnGuardar').prop('disabled', false) }
          $('#infoProceso').show()
          verificarOverrideGeneric(idProcesoSel, '#badgeOverride')
        })
      })
    })

    $('#formARL').on('submit', function(e){
      e.preventDefault()
      if(!idProcesoSel){ toastr.warning('Primero busque y seleccione un proceso'); return }
      var datos = parsearFormulario($(this))
      datos.idProceso = idProcesoSel
      enviarPeticion('procesos','checkPrerequisitos',{accion:'afiliarARL', idProceso:idProcesoSel}, function(pr){
        if(pr.ejecuto && (pr.faltantes?.length || pr.pendientes?.length)){
          var msg=[]; if(pr.faltantes.length) msg.push('Faltan: '+pr.faltantes.join(', ')); if(pr.pendientes.length) msg.push('Pendientes: '+pr.pendientes.join(', '));
          $('#alertPrereqARL').removeClass('d-none').html(msg.join('<br>'));
          return;
        }
        enviarPeticion('procesos', 'afiliarARL', datos, function(r){
          if(r.ejecuto){
            Swal.fire({ icon:'success', title:'Afiliación guardada', text: 'Se actualizó el proceso y avanzó el estado.' })
            $('#formARL :input').prop('disabled', true)
            $('#ayudaARL').show()
            verificarOverrideGeneric(idProcesoSel, '#badgeOverride', {force:true})
          }else{ mostrarError(r) }
        })
      })
    })
  }
</script>
</body>
</html>

