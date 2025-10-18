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
        <div class="col-md-7">
          <div class="card card-outline card-warning">
            <div class="card-header d-flex justify-content-between align-items-center">
              <span>Procesos pendientes de recogida</span>
              <div>
                <button class="btn btn-sm btn-outline-primary" id="btnRefrescarLista"><i class="fas fa-sync-alt"></i></button>
              </div>
            </div>
            <div class="card-body p-0">
              <div class="p-2 border-bottom">
                <div class="input-group input-group-sm">
                  <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-search"></i></span></div>
                  <input type="text" id="filtroTabla" class="form-control" placeholder="Filtrar por ID, gerencia o contratista...">
                  <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button" id="btnLimpiarFiltro" title="Limpiar"><i class="fas fa-times"></i></button>
                  </div>
                </div>
              </div>
              <div class="table-responsive" style="max-height:420px;">
                <table class="table table-sm table-hover table-striped mb-0" id="tablaProcesos">
                  <thead class="thead-light">
                    <tr>
                      <th>ID</th>
                      <th>Gerencia</th>
                      <th>Contratista</th>
                      <th>Validación</th>
                      <th>Estado</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
              </div>
              <div class="p-2 small text-muted" id="resumenLista"></div>
            </div>
          </div>
          <div id="panelInfoProceso" class="card card-outline card-info mt-3" style="display:none;">
            <div class="card-header">Detalle proceso seleccionado</div>
            <div class="card-body py-2">
              <ul class="list-unstyled mb-0 small">
                <li><b>Proceso:</b> <span id="lblProceso"></span></li>
                <li><b>Gerencia:</b> <span id="lblGerencia"></span></li>
                <li><b>Contratista:</b> <span id="lblContratista"></span></li>
                <li><b>Estado actual:</b> <span id="lblEstado"></span></li>
                <li><b>Validado:</b> <span id="lblPerfilValidado"></span></li>
                <li><b>Fecha validación:</b> <span id="lblFechaValidacion"></span></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="col-md-5">
          <div class="card card-outline card-warning">
            <div class="card-header">Registrar recogida</div>
            <div class="card-body">
              <form id="formRecoger">
                <div class="form-group">
                  <label for="fecha_recoger_perfil">Fecha de recogida</label>
                  <input type="date" class="form-control" id="fecha_recoger_perfil" name="fecha_recoger_perfil" required>
                </div>
                <div class="form-group">
                  <label for="observaciones_recoger_perfil">Observaciones</label>
                  <textarea class="form-control" id="observaciones_recoger_perfil" name="observaciones_recoger_perfil" rows="3" placeholder="Notas del acto de recoger"></textarea>
                </div>
                <button type="submit" class="btn btn-warning" id="btnGuardar" disabled>Confirmar recogida</button>
                <small id="ayudaRecoger" class="form-text text-muted" style="display:none;">La recogida ya fue registrada.</small>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<?php require('views/footer.php');?>
<script>
  var idProcesoSel = 0, detalleProceso = null
  function init(){
    cargarListaProcesos()
    $('#btnRefrescarLista').on('click', function(){ cargarListaProcesos(); })
    $('#btnLimpiarFiltro').on('click', function(){ $('#filtroTabla').val(''); filtrarTabla(); })
    $('#filtroTabla').on('keyup', debounce(function(){ filtrarTabla(); },200))
    $('#tablaProcesos tbody').on('click','.btn-select', function(){ seleccionarProceso($(this).data('id')); })
    $('#formRecoger').on('submit', function(e){
      e.preventDefault();
      if(!idProcesoSel){ toastr.warning('Seleccione un proceso'); return }
      var datos = parsearFormulario($(this))
      datos.idProceso = idProcesoSel
      enviarPeticion('procesos','recogerPerfil', datos, function(r){
        if(r.ejecuto){
          Swal.fire({icon:'success', title:'Registrado', text:'Se avanzó el estado (9 -> 10).'})
          $('#formRecoger :input').prop('disabled', true)
          $('#ayudaRecoger').show()
          cargarListaProcesos()
        }else{ mostrarError(r) }
      })
    })
  }
  function cargarListaProcesos(){
    $('#tablaProcesos tbody').html('<tr><td colspan="6" class="text-center text-muted">Cargando...</td></tr>')
    enviarPeticion('procesos','getProcesosRecogerPerfil',{1:1}, function(r){
      if(!r.ejecuto){ $('#tablaProcesos tbody').html('<tr><td colspan="6" class="text-center text-danger">Error</td></tr>'); return }
      var rows = r.data||[]
      if(!rows.length){
        $('#tablaProcesos tbody').html('<tr><td colspan="6" class="text-center text-muted">Sin procesos pendientes</td></tr>')
        $('#resumenLista').text('0 procesos');
        return
      }
      var html='';
      rows.forEach(function(p){
        var estTxt = (typeof estados!=='undefined' && estados[p.estado_solicitud]) ? estados[p.estado_solicitud] : p.estado_solicitud
        var valTxt = (p.perfil_validado === '1' || p.perfil_validado === 1) ? 'Aprobado' : (p.perfil_validado === '0' || p.perfil_validado === 0 ? 'No aprobado' : '')
        html += '<tr>'+
          '<td>'+p.id+'</td>'+
          '<td>'+(p.gerencia||'')+'</td>'+
          '<td>'+(p.contratista_nombre ? p.contratista_nombre + (p.cedula? ' ('+p.cedula+')':'' ) : '')+'</td>'+
          '<td>'+(valTxt||'')+'</td>'+
          '<td>'+estTxt+'</td>'+
          '<td class="text-right"><button class="btn btn-xs btn-warning btn-select" data-id="'+p.id+'"><i class="fas fa-hand-paper"></i></button></td>'+
        '</tr>'
      })
      $('#tablaProcesos tbody').html(html)
      $('#resumenLista').text(rows.length+' proceso(s) pendientes')
      filtrarTabla(true)
    })
  }
  function seleccionarProceso(id){
    enviarPeticion('procesos','getProcesoDetalle',{id:id}, function(r){
      if(!(r.ejecuto && r.data && r.data.length)){ toastr.error('No se encontró el proceso'); return }
      var p = r.data[0]; idProcesoSel = p.id
      $('#lblProceso').text(p.id)
      $('#lblGerencia').text(p.gerencia || '')
      $('#lblContratista').text(p.contratista_nombre ? p.contratista_nombre + (p.contratista_cedula? ' ('+p.contratista_cedula+')':'' ) : '')
      var estadoTxt = (typeof estados !== 'undefined' && estados[p.estado]) ? estados[p.estado] : p.estado
      $('#lblEstado').text(estadoTxt || '')
      $('#lblPerfilValidado').text(p.perfil_validado !== null && p.perfil_validado !== '' ? (p.perfil_validado == 1 ? 'Aprobado' : 'No aprobado') : '')
      $('#lblFechaValidacion').text(p.fecha_validacion_perfil || '')
      $('#panelInfoProceso').show()
      $('#formRecoger')[0].reset();
      $('#formRecoger :input').prop('disabled', false)
      $('#ayudaRecoger').hide()
      if(p.fecha_recoger_perfil){
        $('#fecha_recoger_perfil').val(p.fecha_recoger_perfil)
        if(p.observaciones_recoger_perfil){ $('#observaciones_recoger_perfil').val(p.observaciones_recoger_perfil) }
        $('#formRecoger :input').prop('disabled', true)
        $('#ayudaRecoger').show()
      }
    })
  }
  function filtrarTabla(skipFocus){
    var q = ($('#filtroTabla').val()||'').trim().toLowerCase()
    var total = 0, visibles = 0
    var $tbody = $('#tablaProcesos tbody')
    $tbody.find('tr').each(function(){
      var $tr = $(this)
      if($tr.find('td').length <= 1){ return }
      total++
      if(!q){ $tr.show(); visibles++; return }
      var txt = $tr.text().toLowerCase()
      if(txt.indexOf(q)!==-1){ $tr.show(); visibles++; } else { $tr.hide(); }
    })
    if(total===0){ return }
    if(q && visibles===0){ if($tbody.find('tr.no-match').length===0){ $tbody.append('<tr class="no-match"><td colspan="6" class="text-center text-warning">Sin coincidencias</td></tr>') } }
    else{ $tbody.find('tr.no-match').remove() }
    var base = total + ' proceso(s) pendientes'; if(q){ base += ' | '+visibles+' visibles' }
    $('#resumenLista').text(base)
  }
  function debounce(fn, wait){ var t; return function(){ var ctx=this, args=arguments; clearTimeout(t); t=setTimeout(function(){ fn.apply(ctx,args) }, wait) } }
</script>
</body>
</html>

