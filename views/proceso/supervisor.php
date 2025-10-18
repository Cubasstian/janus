<?php require('views/header.php');?>

<link rel="stylesheet" href="dist/css/proceso-moderno.css">

<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
  <div class="col-sm-6"><h1>Proceso - Designar supervisor <span class="badge badge-secondary">Estado 15</span></h1></div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="main/inicio/">Inicio</a></li>
            <li class="breadcrumb-item active">Supervisor</li>
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
            <div class="card-header">Asignar supervisor</div>
            <div class="card-body">
              <form id="formSupervisor">
                <div class="alert alert-warning py-1 px-2 d-none" id="alertPrereqSupervisor"></div>
                <div class="form-group">
                  <label for="fk_supervisor">Supervisor</label>
                  <select class="form-control" id="fk_supervisor" name="fk_supervisor" required></select>
                </div>
                <div class="form-group">
                  <label for="fecha_supervisor">Fecha designación (opcional)</label>
                  <input type="date" class="form-control" id="fecha_supervisor" name="fecha_supervisor">
                </div>
                <button type="submit" class="btn btn-primary" id="btnAsignar" disabled>Guardar</button>
                <small id="ayudaSupervisor" class="form-text text-muted" style="display:none;">El supervisor ya fue asignado para este proceso.</small>
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
    // Llenar lista de supervisores (usuarios no PS, activos)
    enviarPeticion('usuarios', 'getUsuarios', {criterio:'todos'}, function(r){
      $('#fk_supervisor').html('<option value="">Seleccione...</option>')
      if(r.ejecuto && r.data){
        r.data.forEach(function(u){
          $('#fk_supervisor').append(`<option value="${u.id}">${u.nombre} (${u.rol})</option>`)
        })
      }
    })

    // Buscar proceso
    $('#formBuscarProceso').on('submit', function(e){
      e.preventDefault()
      var idp = parseInt($('#idProceso').val(),10)
      if(!idp){ toastr.warning('Ingrese un ID de proceso válido'); return }
      enviarPeticion('procesos', 'getProcesos', {criterio:'id', id: idp}, function(r){
        if(!(r.ejecuto && r.data && r.data.length)){
          toastr.error('No se encontró el proceso')
          $('#infoProceso').hide(); idProcesoSel = 0
          $('#btnAsignar').prop('disabled', true)
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
            if(detalleProceso.fk_supervisor){
              $('#fk_supervisor').val(detalleProceso.fk_supervisor)
              if(detalleProceso.fecha_supervisor){ $('#fecha_supervisor').val(detalleProceso.fecha_supervisor) }
              $('#formSupervisor :input').prop('disabled', true)
              $('#ayudaSupervisor').show()
            }else{
              $('#btnAsignar').prop('disabled', false)
            }
          }else{
            $('#btnAsignar').prop('disabled', false)
          }
          $('#infoProceso').show()
        })
      })
    })

    // Guardar designación
    $('#formSupervisor').on('submit', function(e){
      e.preventDefault()
      if(!idProcesoSel){ toastr.warning('Primero busque y seleccione un proceso'); return }
      var datos = parsearFormulario($(this))
      datos.idProceso = idProcesoSel
      enviarPeticion('procesos','checkPrerequisitos',{accion:'designarSupervisor', idProceso:idProcesoSel}, function(pr){
        if(pr.ejecuto && (pr.faltantes?.length || pr.pendientes?.length)){
          var msg=[]; if(pr.faltantes.length) msg.push('Faltan: '+pr.faltantes.join(', ')); if(pr.pendientes.length) msg.push('Pendientes: '+pr.pendientes.join(', '));
          $('#alertPrereqSupervisor').removeClass('d-none').html(msg.join('<br>'));
          return;
        }
        enviarPeticion('procesos', 'designarSupervisor', datos, function(r){
          if(r.ejecuto){
            Swal.fire({ icon:'success', title:'Supervisor asignado', text: 'Se actualizó el proceso y avanzó el estado.' })
            $('#formSupervisor :input').prop('disabled', true)
            $('#ayudaSupervisor').show()
          }else{ mostrarError(r) }
        })
      })
    })
  }
</script>
</body>
</html>

