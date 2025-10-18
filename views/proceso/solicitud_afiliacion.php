<?php
require_once 'views/header.php';
?>
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Solicitud de afiliación ARL <span class="badge badge-secondary">Estado 12</span> <span id="badgeOverride" class="badge badge-danger d-none" title="Transiciones forzadas en el proceso">Override</span></h1>
          <p class="text-muted small mb-0">Estado 12 &rarr; registra la solicitud para avanzar a Afiliar ARL (13).</p>
        </div>
      </div>
    </div>
  </section>
  <section class="content">
    <div class="container-fluid">
      <div class="card card-outline card-primary">
        <div class="card-header">
          <h3 class="card-title">Procesos pendientes de solicitud</h3>
          <div class="card-tools">
            <button class="btn btn-sm btn-secondary" id="btnRecargar"><i class="fas fa-sync-alt"></i> Recargar</button>
          </div>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-sm table-hover" id="tablaProcesos">
              <thead class="thead-light">
                <tr>
                  <th>ID</th>
                  <th>Gerencia</th>
                  <th>Contratista</th>
                  <th>Documento</th>
                  <th>Acción</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="card d-none" id="cardForm">
        <div class="card-header bg-info">
          <h3 class="card-title">Registrar solicitud afiliación - Proceso <span id="spId"></span></h3>
          <div class="card-tools"><button class="btn btn-xs btn-danger" id="btnCerrarForm"><i class="fas fa-times"></i></button></div>
        </div>
        <div class="card-body">
          <form id="frmSolicitud" autocomplete="off">
            <input type="hidden" name="idProceso" id="idProceso" />
            <div class="form-row">
              <div class="form-group col-md-4">
                <label>Fecha solicitud</label>
                <input type="date" class="form-control" name="fecha_solicitud_afiliacion" required />
              </div>
              <div class="form-group col-md-8">
                <label>Observaciones</label>
                <textarea class="form-control" rows="2" name="observaciones_solicitud_afiliacion" placeholder="Notas internas"></textarea>
              </div>
            </div>
            <div class="alert alert-warning py-2 d-none" id="alertPrereq"></div>
            <div class="text-right">
              <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar y avanzar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</div>
<script>
function cargarListado(){
  $('#tablaProcesos tbody').html('<tr><td colspan="5" class="text-center text-muted">Cargando...</td></tr>');
  // Reutilizamos helper getProcesos con criterio todos y filtramos en frontend (estado 12)
  enviarPeticion('solicitudes','getListado',{estado:12},function(r){
    if(!r.ejecuto){ $('#tablaProcesos tbody').html('<tr><td colspan="5" class="text-danger">Error cargando</td></tr>'); return }
    var html='';
    if(r.data.length==0){ html='<tr><td colspan="5" class="text-center text-muted">Sin procesos en estado 12</td></tr>'; }
    r.data.forEach(function(o){
      if(parseInt(o.estado)!==12) return; // seguridad
      html += '<tr>'+
        '<td>'+o.id+'</td>'+
        '<td>'+(o.gerencia||'')+'</td>'+
        '<td>'+(o.contratista_nombre?o.contratista_nombre+'<br><small>'+o.contratista_cedula+'</small>':'')+'</td>'+
        '<td>'+(o.numero_contrato?('<span class="badge badge-info">Contrato '+o.numero_contrato+'</span>'):'')+'</td>'+
        '<td><button class="btn btn-xs btn-primary" data-id="'+o.id+'"><i class="fas fa-edit"></i></button></td>'+
      '</tr>';
    });
    $('#tablaProcesos tbody').html(html);
  });
}
function mostrarForm(id){
  $('#idProceso').val(id);$('#spId').text(id);$('#cardForm').removeClass('d-none');
  // Chequear prerequisitos visualmente
  $('#alertPrereq').addClass('d-none').empty();
  enviarPeticion('procesos','checkPrerequisitos',{accion:'solicitudAfiliacion', idProceso:id},function(r){
    if(r.ejecuto && (r.faltantes.length||r.pendientes.length)){
      var msg=[]; if(r.faltantes.length) msg.push('Faltan: '+r.faltantes.join(', ')); if(r.pendientes.length) msg.push('Pendientes aprobación: '+r.pendientes.join(', '));
      $('#alertPrereq').removeClass('d-none').html(msg.join('<br>'));
    }
  });
  verificarOverrideGeneric(id, '#badgeOverride');
}
// Interceptar cuando se abre el form
var _obsSolicitud = new MutationObserver(function(m){ if(!$('#cardForm').hasClass('d-none')){ var id = $('#idProceso').val(); if(id){ verificarOverride(id); } } });
$(function(){
  cargarListado();
  $('#btnRecargar').on('click',cargarListado);
  $('#tablaProcesos').on('click','button[data-id]',function(){ mostrarForm($(this).data('id')); });
  $('#btnCerrarForm').on('click',function(){ $('#cardForm').addClass('d-none'); });
  $('#frmSolicitud').on('submit',function(e){
    e.preventDefault();
    var datos = $(this).serializeArray().reduce(function(a,x){a[x.name]=x.value;return a;},{});
    enviarPeticion('procesos','solicitudAfiliacion',datos,function(r){
      if(r.ejecuto){
        toastr.success('Solicitud registrada. Avanzó a Afiliar ARL (13)');
        $('#cardForm').addClass('d-none');
        verificarOverrideGeneric(datos.idProceso, '#badgeOverride', {force:true});
        cargarListado();
      }else{ mostrarError(r); }
    });
  });
});
</script>
<?php
require_once 'views/footer.php';
?>

