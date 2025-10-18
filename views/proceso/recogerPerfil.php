<?php require('views/header.php');?>

<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6"><h1>Proceso - Recoger validación de perfil</h1></div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="main/inicio/">Inicio</a></li>
            <li class="breadcrumb-item active">Recoger validación</li>
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
            <div class="card-header">Registrar recepción</div>
            <div class="card-body">
              <form id="formRecogerPerfil">
                <div class="form-group">
                  <label for="fecha_recoger_perfil">Fecha de recepción</label>
                  <input type="date" class="form-control" id="fecha_recoger_perfil" name="fecha_recoger_perfil" required>
                </div>
                <div class="form-group">
                  <label for="observaciones_recoger_perfil">Observaciones</label>
                  <textarea class="form-control" id="observaciones_recoger_perfil" name="observaciones_recoger_perfil" rows="3" placeholder="Notas de la recepción"></textarea>
                </div>
                <button type="submit" class="btn btn-primary" id="btnGuardar" disabled>Guardar</button>
                <small id="ayudaRecogerPerfil" class="form-text text-muted" style="display:none;">Esta recepción ya fue registrada.</small>
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
          $('#infoProceso').hide(); $('#btnGuardar').prop('disabled', true)
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
            if(detalleProceso.fecha_recoger_perfil){ $('#fecha_recoger_perfil').val(detalleProceso.fecha_recoger_perfil) }
            if(detalleProceso.observaciones_recoger_perfil){ $('#observaciones_recoger_perfil').val(detalleProceso.observaciones_recoger_perfil) }
            if(detalleProceso.fecha_recoger_perfil){
              $('#formRecogerPerfil :input').prop('disabled', true)
              $('#ayudaRecogerPerfil').show()
            }else{
              $('#btnGuardar').prop('disabled', false)
            }
          }else{ $('#btnGuardar').prop('disabled', false) }
          $('#infoProceso').show()
        })
      })
    })

    // Guardar recepción de validación de perfil
    $('#formRecogerPerfil').on('submit', function(e){
      e.preventDefault()
      if(!idProcesoSel){ toastr.warning('Primero busque y seleccione un proceso'); return }
      var datos = parsearFormulario($(this))
      datos.idProceso = idProcesoSel
      enviarPeticion('procesos', 'recogerPerfil', datos, function(r){
        if(r.ejecuto){
          Swal.fire({ icon:'success', title:'Registro guardado', text: 'Se actualizó el proceso y avanzó el estado.' })
          $('#formRecogerPerfil :input').prop('disabled', true)
          $('#ayudaRecogerPerfil').show()
        }else{ mostrarError(r) }
      })
    })
  }
</script>
</body>
</html>

