<?php require('views/header.php');?>
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6"><h1>Diagnóstico del flujo</h1></div>
        <div class="col-sm-6 text-right">
          <div class="btn-group btn-group-sm" role="group">
            <button class="btn btn-outline-secondary" id="btnRefrescar"><i class="fas fa-sync-alt"></i> Actualizar</button>
            <button class="btn btn-outline-info" id="btnMetrics"><i class="fas fa-chart-bar"></i> Métricas</button>
            <button class="btn btn-outline-warning" id="btnAudit"><i class="fas fa-search-plus"></i> Auditoría</button>
            <button class="btn btn-outline-dark" id="btnHist"><i class="fas fa-history"></i> Histórico</button>
            <button class="btn btn-outline-success" id="btnOverrideStats"><i class="fas fa-flag"></i> Overrides</button>
            <button class="btn btn-outline-danger" id="btnAlerts"><i class="fas fa-bell"></i> Alertas</button>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section class="content">
    <div class="container-fluid">
      <div class="card card-outline card-primary">
        <div class="card-header">Procesos potencialmente atascados</div>
        <div class="card-body p-0">
          <table class="table table-sm table-hover mb-0" id="tablaDiag">
            <thead class="thead-light"><tr><th>Proceso</th><th>Estado</th><th>Acción esperada</th><th>Ir</th><th>Snapshot</th><th>Forzar</th></tr></thead>
            <tbody></tbody>
          </table>
        </div>
        <div class="card-footer py-1 small" id="resumenDiag"></div>
      </div>
      <div class="card card-outline card-danger d-none" id="cardAlerts">
        <div class="card-header d-flex justify-content-between align-items-center">
          <span>Alertas de flujo / gobernanza</span>
          <div class="btn-group btn-group-xs">
            <button class="btn btn-outline-primary" id="btnRefreshAlerts" title="Refrescar"><i class="fas fa-sync-alt"></i></button>
            <button class="btn btn-outline-success" id="btnExportAlerts" title="Exportar CSV"><i class="fas fa-file-csv"></i></button>
            <button class="btn btn-outline-secondary" id="btnCerrarAlerts"><i class="fas fa-times"></i></button>
          </div>
        </div>
        <div class="card-body p-2 small" id="alertsBody">Cargando...</div>
      </div>
      <div class="card card-outline card-secondary d-none" id="cardMetrics">
        <div class="card-header d-flex justify-content-between align-items-center">
          <span>Métricas del flujo</span>
          <div>
            <label class="mb-0 mr-2 small"><input type="checkbox" id="chkIncluyeAbiertos"> incluir estados abiertos</label>
            <button class="btn btn-xs btn-outline-success" id="btnExportMetrics" title="Exportar CSV"><i class="fas fa-file-csv"></i></button>
            <button class="btn btn-xs btn-outline-secondary" id="btnCerrarMetrics"><i class="fas fa-times"></i></button>
          </div>
        </div>
        <div class="card-body p-2">
          <div class="row">
            <div class="col-md-6">
              <h6 class="mb-1">Inventario actual</h6>
              <table class="table table-sm table-striped mb-0" id="tablaInv"><thead><tr><th>Estado</th><th>Cantidad</th><th>Edad prom. (días)</th></tr></thead><tbody></tbody></table>
            </div>
            <div class="col-md-6">
              <h6 class="mb-1">Duración promedio (histórico)</h6>
              <table class="table table-sm table-striped mb-0" id="tablaDur"><thead><tr><th>Estado</th><th>Días prom.</th><th>Muestras</th></tr></thead><tbody></tbody></table>
            </div>
          </div>
          <div class="small text-muted mt-1" id="metricsMeta"></div>
        </div>
      </div>
      <div class="card card-outline card-warning d-none" id="cardAudit">
        <div class="card-header d-flex justify-content-between align-items-center">
          <span>Auditoría de integridad</span>
          <div class="btn-group btn-group-xs">
            <button class="btn btn-xs btn-outline-success" id="btnExportAudit" title="Exportar CSV"><i class="fas fa-file-csv"></i></button>
            <button class="btn btn-xs btn-outline-secondary" id="btnCerrarAudit"><i class="fas fa-times"></i></button>
          </div>
        </div>
        <div class="card-body p-2">
          <div id="auditContent" class="small">Cargando...</div>
        </div>
      </div>
      <div class="card card-outline card-dark d-none" id="cardHistoric">
        <div class="card-header d-flex justify-content-between align-items-center">
          <span>Histórico solicitud <span id="histProceso"></span></span>
          <div class="d-flex align-items-center">
            <label class="mb-0 mr-2 small"><input type="checkbox" id="chkSoloOverrides"> sólo overrides</label>
            <div class="btn-group btn-group-xs mr-2" id="histPagControls">
              <button class="btn btn-outline-secondary" id="histPrev" title="Anterior">&lt;</button>
              <span class="btn btn-outline-light disabled" id="histPaginaInfo">1/1</span>
              <button class="btn btn-outline-secondary" id="histNext" title="Siguiente">&gt;</button>
            </div>
            <button class="btn btn-xs btn-outline-secondary" id="btnCerrarHist"><i class="fas fa-times"></i></button>
          </div>
        </div>
        <div class="card-body p-0">
          <table class="table table-sm table-striped mb-0" id="tablaHist"><thead><tr><th>Fecha</th><th>Usuario</th><th>Estado</th><th>Override</th><th>Motivo override</th></tr></thead><tbody></tbody></table>
        </div>
      </div>
      <div class="card card-outline card-info d-none" id="cardSnapshot">
      <div class="card card-outline card-success d-none" id="cardOverrideStats">
        <div class="card-header d-flex justify-content-between align-items-center">
          <span>Estadísticas de overrides (<span id="ovWinLabel">7</span> días)</span>
          <div class="btn-group btn-group-xs align-items-center">
            <select id="ovDias" class="custom-select custom-select-sm" style="width:auto">
              <option value="7" selected>7d</option>
              <option value="14">14d</option>
              <option value="30">30d</option>
              <option value="60">60d</option>
            </select>
            <button class="btn btn-outline-primary" id="btnRefreshOverrideStats" title="Refrescar"><i class="fas fa-sync-alt"></i></button>
            <button class="btn btn-outline-success" id="btnExportOverrideStats" title="Exportar CSV"><i class="fas fa-file-csv"></i></button>
            <button class="btn btn-outline-warning" id="btnOverrideAnomalies" title="Anomalías"><i class="fas fa-exclamation-triangle"></i></button>
            <button class="btn btn-outline-secondary" id="btnCerrarOverrideStats"><i class="fas fa-times"></i></button>
          </div>
        </div>
        <div class="card-body p-2 small" id="overrideStatsBody">
          <div id="overrideStatsSummary" class="mb-2">Cargando...</div>
          <canvas id="chartOverrides" height="120" class="mb-2" style="display:none"></canvas>
          <div id="overrideStatsDetail"></div>
          <div id="overrideAnomaliesPanel" class="border-top mt-2 pt-2 d-none">
            <h6 class="mb-1"><i class="fas fa-exclamation-triangle text-warning"></i> Anomalías de overrides <small class="text-muted" id="anomMeta"></small></h6>
            <div id="overrideAnomaliesBody" class="small">Cargando...</div>
          </div>
        </div>
      </div>
        <div class="card-header d-flex justify-content-between align-items-center">
          <span>Snapshot proceso <span id="spSnapId"></span></span>
          <button class="btn btn-xs btn-danger" id="btnCerrarSnap"><i class="fas fa-times"></i></button>
        </div>
        <div class="card-body p-0">
          <div class="row no-gutters">
            <div class="col-md-5 border-right">
              <div class="p-2">
                <h6 class="mb-1">Acciones / prerequisitos</h6>
                <ul class="list-unstyled mb-0 small" id="listaAcciones"></ul>
              </div>
            </div>
            <div class="col-md-7">
              <div class="p-2">
                <h6 class="mb-1">Documentos clave</h6>
                <table class="table table-sm table-striped mb-0">
                  <thead><tr><th>Documento</th><th>ID</th><th>Estado</th></tr></thead>
                  <tbody id="tbodyDocs"></tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<?php require('views/footer.php');?>
<script>
var historicData = []; var historicPage = 1; var historicPageSize = 25;
function init(){
  cargarDiagnostico();
  $('#btnRefrescar').on('click', cargarDiagnostico);
  $('#btnMetrics').on('click', toggleMetrics);
  $('#btnAudit').on('click', toggleAudit);
  $('#btnHist').on('click', toggleHistoricPanelGlobal);
  $('#tablaDiag').on('click','button[data-snap]', function(){ verSnapshot($(this).data('snap')); })
  $('#tablaDiag').on('click','button[data-force]', function(){ abrirForce($(this).data('force'), $(this).data('estado')); })
  $('#btnCerrarSnap').on('click', function(){ $('#cardSnapshot').addClass('d-none'); })
  $('#btnCerrarMetrics').on('click', function(){ $('#cardMetrics').addClass('d-none'); })
  $('#btnCerrarAudit').on('click', function(){ $('#cardAudit').addClass('d-none'); })
  $('#btnCerrarHist').on('click', function(){ $('#cardHistoric').addClass('d-none'); })
  $('#btnCerrarOverrideStats').on('click', function(){ $('#cardOverrideStats').addClass('d-none'); })
  $('#chkIncluyeAbiertos').on('change', cargarMetrics);
  $('#btnExportMetrics').on('click', exportMetrics);
  $('#btnExportAudit').on('click', exportAudit);
  $('#btnOverrideStats').on('click', toggleOverrideStats);
  $('#btnExportOverrideStats').on('click', exportOverrideStats);
  $('#btnRefreshOverrideStats').on('click', function(){ cargarOverrideStats(true); });
  $('#ovDias').on('change', function(){ cargarOverrideStats(true); });
  $('#btnOverrideAnomalies').on('click', toggleOverrideAnomalies);
  $('#btnAlerts').on('click', toggleAlerts);
  $('#btnCerrarAlerts').on('click', function(){ $('#cardAlerts').addClass('d-none'); });
  $('#btnRefreshAlerts').on('click', function(){ cargarAlertas(true); });
  $('#btnExportAlerts').on('click', exportAlertas);
  iniciarAlertPolling();
  prepararModalForce();
  $('#chkSoloOverrides').on('change', function(){ historicPage=1; rebuildHistoricTable(); });
  $('#histPrev').on('click', function(){ if(historicPage>1){ historicPage--; rebuildHistoricTable(); } });
  $('#histNext').on('click', function(){ var total = calcHistoricFiltered().length; var pages = Math.max(1, Math.ceil(total/historicPageSize)); if(historicPage<pages){ historicPage++; rebuildHistoricTable(); } });
}
function cargarDiagnostico(){
  $('#tablaDiag tbody').html('<tr><td colspan="6" class="text-center text-muted">Cargando...</td></tr>');
  enviarPeticion('procesos','diagnosticoFlujo',{}, function(r){
    if(!r.ejecuto){ $('#tablaDiag tbody').html('<tr><td colspan="6" class="text-center text-danger">Error</td></tr>'); return }
    if(!r.data.length){ $('#tablaDiag tbody').html('<tr><td colspan="6" class="text-center text-success">Sin procesos atascados</td></tr>'); $('#resumenDiag').text('0'); return }
    var h='';
    r.data.forEach(function(p){
      var estadoTxt = (typeof estados!=='undefined' && estados[p.estado])?estados[p.estado]:p.estado;
      h += '<tr>'+
        '<td>'+p.idProceso+'</td>'+
        '<td>'+estadoTxt+'</td>'+
        '<td>'+p.accion+'</td>'+
        '<td><a class="btn btn-xs btn-outline-primary" href="proceso/'+mapAccionVista(p.accion)+'/" target="_blank">Abrir</a></td>'+
        '<td><button class="btn btn-xs btn-info" data-snap="'+p.idProceso+'"><i class="fas fa-search"></i></button></td>'+
        '<td><button class="btn btn-xs btn-danger" data-force="'+p.idProceso+'" data-estado="'+p.estado+'"><i class="fas fa-forward"></i></button></td>'+
      '</tr>';
    });
    $('#tablaDiag tbody').html(h);
    $('#resumenDiag').text(r.data.length+' proceso(s)');
  });
}
function mapAccionVista(a){
  switch(a){
    case 'ciip': return 'ciip';
    case 'evaluarEEP': return 'eep_evaluar';
    case 'validarPerfil': return 'perfil';
    case 'recogerPerfil': return 'recoger_perfil';
    case 'minuta': return 'minuta';
    case 'numerar': return 'numerar';
    case 'solicitudAfiliacion': return 'solicitud_afiliacion';
    case 'afiliarARL': return 'arl';
    case 'expedirRP': return 'rp';
    case 'recogerRP': return 'rp';
    case 'designarSupervisor': return 'supervisor';
    case 'actaInicio': return 'actaInicio';
  }
  return 'resumen';
}
function verSnapshot(id){
  enviarPeticion('procesos','snapshot',{idProceso:id}, function(r){
    if(!r.ejecuto){ mostrarError(r); return }
    $('#spSnapId').text(id);
    cargarHistorico(id); // también abrir histórico
    var acciones='';
    (r.acciones||[]).forEach(function(a){
      acciones += '<li>'+
        '<span class="badge badge-secondary mr-1">'+a.estado+'</span>'+a.accion+
        (a.prerequisitos_ok? ' <span class="text-success">[OK]':' <span class="text-danger">[Falta]')+
        '</span>'+(!a.prerequisitos_ok?'\n<br><small>'+a.mensaje_prereq+'</small>':'')+
        '</li>';
    });
    $('#listaAcciones').html(acciones||'<li class="text-muted">Sin datos</li>');
    var docs='';
    var dmap = r.documentos||{}; var keys = Object.keys(dmap);
    if(!keys.length){ docs='<tr><td colspan="3" class="text-center text-muted">Sin documentos</td></tr>'; }
    else{
      keys.forEach(function(k){ docs += '<tr><td>'+k+'</td><td>'+dmap[k].id+'</td><td>'+estadoDocumentoTexto(dmap[k].estado)+'</td></tr>'; });
    }
    $('#tbodyDocs').html(docs);
    $('#cardSnapshot').removeClass('d-none');
  });
}
function estadoDocumentoTexto(e){
  if(typeof estadoDocumentos!=='undefined' && estadoDocumentos[e]) return estadoDocumentos[e];
  return e;
}
// ----- MÉTRICAS -----
function toggleMetrics(){
  $('#cardMetrics').toggleClass('d-none');
  if(!$('#cardMetrics').hasClass('d-none')) cargarMetrics();
}
function cargarMetrics(){
  $('#tablaInv tbody').html('<tr><td colspan="3" class="text-center text-muted">Cargando...</td></tr>');
  $('#tablaDur tbody').html('<tr><td colspan="3" class="text-center text-muted">Cargando...</td></tr>');
  var inc = $('#chkIncluyeAbiertos').is(':checked')?1:0;
  enviarPeticion('procesos','metricsFlujo',{incluirActualAbierto:inc}, function(r){
    if(!r.ejecuto){ $('#tablaInv tbody').html('<tr><td colspan="3" class="text-center text-danger">Error</td></tr>'); $('#tablaDur tbody').empty(); return }
    var inv='';
    (r.resumenEstados||[]).forEach(function(e){
      var estTxt = (typeof estados!=='undefined' && estados[e.estado])?estados[e.estado]:e.estado;
      inv += '<tr><td>'+estTxt+'</td><td>'+e.cantidad+'</td><td>'+(e.edad_promedio_dias||0)+'</td></tr>';
    });
    if(!inv) inv = '<tr><td colspan="3" class="text-center text-muted">Sin datos</td></tr>';
    $('#tablaInv tbody').html(inv);
    var dur='';
    var dp = r.duracionesPromedio || {}; var keys = Object.keys(dp).sort(function(a,b){return parseInt(a)-parseInt(b)});
    keys.forEach(function(k){
      var estTxt = (typeof estados!=='undefined' && estados[k])?estados[k]:k;
      dur += '<tr><td>'+estTxt+'</td><td>'+dp[k].dias_promedio+'</td><td>'+dp[k].muestras+'</td></tr>';
    });
    if(!dur) dur = '<tr><td colspan="3" class="text-center text-muted">Sin datos</td></tr>';
    $('#tablaDur tbody').html(dur);
    $('#metricsMeta').text('Historial analizado: '+r.limitHistorial+' registros. Abiertos incluidos: '+(r.incluyeAbiertos?'sí':'no'));
  });
}
// ----- AUDITORIA -----
function toggleAudit(){
  $('#cardAudit').toggleClass('d-none');
  if(!$('#cardAudit').hasClass('d-none')) cargarAudit();
}
function cargarAudit(){
  $('#auditContent').html('Cargando...');
  enviarPeticion('procesos','auditoriaIntegridad',{}, function(r){
    if(!r.ejecuto){ $('#auditContent').html('<span class="text-danger">Error</span>'); return }
    var out=''; var issues = r.issues||{}; var total=0;
    Object.keys(issues).forEach(function(k){ var arr=issues[k]||[]; total+=arr.length; out += '<div class="mb-2"><strong>'+k+'</strong> ('+arr.length+')'; if(arr.length){ out+='<ul class="mb-0 small">'; arr.slice(0,50).forEach(function(it){ out+='<li>'+(it.id||it.fk_procesos||JSON.stringify(it))+' estado:'+(it.estado!==undefined?it.estado:'')+'</li>'; }); if(arr.length>50) out+='<li>...('+(arr.length-50)+' más)</li>'; out+='</ul>'; } out+='</div>'; });
    if(!out) out='<span class="text-success">Sin inconsistencias detectadas</span>';
    out = '<div class="mb-1"><strong>Total categorías:</strong> '+Object.keys(issues).length+' | <strong>Registros:</strong> '+total+'</div>'+out;
    $('#auditContent').html(out);
  });
}
// ----- HISTORICO -----
function toggleHistoricPanelGlobal(){
  if($('#cardHistoric').hasClass('d-none')){
    $('#tablaHist tbody').html('<tr><td colspan="5" class="text-center text-muted">Seleccione un proceso (snapshot) para ver su histórico</td></tr>');
  }
  $('#cardHistoric').toggleClass('d-none');
}
function cargarHistorico(idProceso){
  if($('#cardHistoric').hasClass('d-none')) $('#cardHistoric').removeClass('d-none');
  $('#histProceso').text(idProceso);
  $('#tablaHist tbody').html('<tr><td colspan="5" class="text-center text-muted">Cargando...</td></tr>');
  enviarPeticion('procesos','getHistoricoProceso',{idProceso:idProceso}, function(r){
    if(!r.ejecuto){ $('#tablaHist tbody').html('<tr><td colspan="5" class="text-center text-danger">Error</td></tr>'); return }
    if(!r.data.length){ historicData=[]; rebuildHistoricTable(); return }
    historicData = r.data; historicPage = 1; rebuildHistoricTable();
  });
}
function calcHistoricFiltered(){
  var only = $('#chkSoloOverrides').is(':checked');
  if(!only) return historicData.slice();
  return historicData.filter(function(it){ return !!it.override; });
}
function rebuildHistoricTable(){
  if(!historicData.length){ $('#tablaHist tbody').html('<tr><td colspan="5" class="text-center text-muted">Sin registros</td></tr>'); actualizarPager(0); return }
  var arr = calcHistoricFiltered();
  if(!arr.length){ $('#tablaHist tbody').html('<tr><td colspan="5" class="text-center text-warning">Sin overrides</td></tr>'); actualizarPager(0); return }
  var total = arr.length; var pages = Math.max(1, Math.ceil(total / historicPageSize));
  if(historicPage>pages) historicPage = pages;
  var start = (historicPage-1)*historicPageSize; var slice = arr.slice(start, start+historicPageSize);
  var h='';
  slice.forEach(function(it){
    var estTxt = (it.estado && typeof estados!=='undefined' && estados[it.estado])?estados[it.estado]: (it.estado||'');
    h += '<tr'+(it.override?' class="table-danger"':'')+'>'+
      '<td>'+it.fecha+'</td>'+
      '<td>'+it.usuario+'</td>'+
      '<td>'+(it.estado!==null?it.estado:'')+' '+estTxt+'</td>'+
      '<td>'+(it.override?'<span class="badge badge-danger">Sí</span>':'')+'</td>'+
      '<td>'+(it.motivo_override?('<span class="small">'+escapeHtml(it.motivo_override)+'</span>'):'')+'</td>'+
    '</tr>';
  });
  $('#tablaHist tbody').html(h);
  actualizarPager(pages);
}
function actualizarPager(pages){
  if(!pages || pages<1) pages=1;
  $('#histPaginaInfo').text(historicPage+'/'+pages);
  var totalFiltered = calcHistoricFiltered().length;
  $('#histPrev').prop('disabled', historicPage<=1);
  $('#histNext').prop('disabled', historicPage>=pages);
  if(totalFiltered <= historicPageSize){ $('#histPagControls').addClass('d-none'); } else { $('#histPagControls').removeClass('d-none'); }
}
// ----- EXPORTS -----
function exportMetrics(){
  enviarPeticion('procesos','exportMetricsFlujo',{}, function(r){
    if(!r.ejecuto){ mostrarError(r); return }
    descargarBase64(r.csv,'metrics_flujo.csv','text/csv');
  });
}
function exportAudit(){
  enviarPeticion('procesos','exportAuditoriaIntegridad',{}, function(r){
    if(!r.ejecuto){ mostrarError(r); return }
    descargarBase64(r.csv,'auditoria_integridad.csv','text/csv');
  });
}
// ----- OVERRIDE STATS -----
function toggleOverrideStats(){
  $('#cardOverrideStats').toggleClass('d-none');
  if(!$('#cardOverrideStats').hasClass('d-none')) cargarOverrideStats();
}
function cargarOverrideStats(force){
  $('#overrideStatsSummary').html('Cargando...');
  $('#overrideStatsDetail').empty();
  var dias = parseInt($('#ovDias').val(),10)||7;
  enviarPeticion('procesos','overrideStats',{dias:dias, force: force?1:0}, function(r){
    if(!r.ejecuto){ $('#overrideStatsSummary').html('<span class="text-danger">Error</span>'); return }
    var hSummary = '';
    $('#ovWinLabel').text(r.ventanaDias);
    hSummary += '<div class="mb-1"><strong>Ventana ('+r.ventanaDias+'d):</strong> '+r.totalVentana+' ('+r.totalTransicionesVentana+' transiciones, ratio '+(r.ratioOverridesVentana!==null?r.ratioOverridesVentana:'n/d')+') | <strong>24h:</strong> '+r.total24h+' ('+r.totalTransiciones24h+' trans, ratio '+(r.ratioOverrides24h!==null?r.ratioOverrides24h:'n/d')+') | <strong>Total hist:</strong> '+(r.totalHistorico!==null?r.totalHistorico:'(n/d)')+'</div>';
    if(r.ultimo){
      var estTxt = (r.ultimo.estado && typeof estados!=='undefined' && estados[r.ultimo.estado])?estados[r.ultimo.estado]:r.ultimo.estado;
      hSummary += '<div class="mb-1"><strong>Último:</strong> '+r.ultimo.fecha+' P '+r.ultimo.proceso+' E '+r.ultimo.estado+' '+estTxt+' '+r.ultimo.usuario+(r.ultimo.motivo?(' | '+escapeHtml(r.ultimo.motivo)):'')+'</div>';
    }
    $('#overrideStatsSummary').html(hSummary);
    // Distribución por día
    var diasKeys = Object.keys(r.porDia||{});
    if(diasKeys.length){
      // Intentar dibujar chart si Chart.js disponible
      if(typeof Chart!=='undefined'){
        var ctx = document.getElementById('chartOverrides');
        $('#chartOverrides').show();
        var dataVals = diasKeys.map(function(d){ return r.porDia[d]; });
        if(window._chartOverrides){ window._chartOverrides.destroy(); }
        window._chartOverrides = new Chart(ctx, { type:'line', data:{ labels:diasKeys, datasets:[{ label:'Overrides', data:dataVals, borderColor:'#dc3545', backgroundColor:'rgba(220,53,69,.15)', fill:true, tension:.15 }] }, options:{ responsive:true, maintainAspectRatio:false, plugins:{legend:{display:false}} } });
      }
    } else { $('#chartOverrides').hide(); }
    var hDet='';
    if(diasKeys.length){ hDet += '<div class="mb-1"><strong>Por día</strong>: '+diasKeys.map(function(d){return d+': '+r.porDia[d]; }).join(' | ')+'</div>'; }
    var estKeys = Object.keys(r.porEstado||{}); if(estKeys.length){ hDet += '<div class="mb-1"><strong>Por estado</strong>: '+estKeys.map(function(e){ var et=(typeof estados!=='undefined'&&estados[e])?estados[e]:e; return e+' '+et+': '+r.porEstado[e]; }).join(' | ')+'</div>'; }
    var usrKeys = Object.keys(r.topUsuarios||{}); if(usrKeys.length){ hDet += '<div class="mb-1"><strong>Top usuarios</strong>: '+usrKeys.slice(0,10).map(function(u){ return u+': '+r.topUsuarios[u]; }).join(' | ')+(usrKeys.length>10?(' ...('+(usrKeys.length-10)+' más)'):'')+'</div>'; }
    if(!hDet) hDet='<span class="text-muted">Sin datos</span>';
    $('#overrideStatsDetail').html(hDet);
  });
}
function toggleOverrideAnomalies(){
  $('#overrideAnomaliesPanel').toggleClass('d-none');
  if(!$('#overrideAnomaliesPanel').hasClass('d-none')) cargarOverrideAnomalies();
}
function cargarOverrideAnomalies(){
  $('#overrideAnomaliesBody').html('Cargando...');
  var dias = parseInt($('#ovDias').val(),10)||7;
  enviarPeticion('procesos','overrideAnomalies',{dias:dias}, function(r){
    if(!r.ejecuto){ $('#overrideAnomaliesBody').html('<span class="text-danger">Error</span>'); return }
    $('#anomMeta').text('('+r.dias+'d umbral '+r.thresholdDia+'/día)');
    if(!r.anomalies.length){ $('#overrideAnomaliesBody').html('<span class="text-success">Sin anomalías</span>'); return }
    var h='<table class="table table-sm table-bordered mb-0"><thead><tr><th>Usuario</th><th>Overrides</th><th>Prom/día</th></tr></thead><tbody>';
    r.anomalies.forEach(function(a){ h+='<tr><td>'+a.usuario+'</td><td>'+a.overrides+'</td><td>'+a.promedio_dia+'</td></tr>'; });
    h+='</tbody></table>';
    $('#overrideAnomaliesBody').html(h);
  });
}
function exportOverrideStats(){
  enviarPeticion('procesos','exportOverrideStats',{}, function(r){
    if(!r.ejecuto){ mostrarError(r); return }
    descargarBase64(r.csv,'override_stats.csv','text/csv');
  });
}
// ----- ALERTAS -----
function toggleAlerts(){
  $('#cardAlerts').toggleClass('d-none');
  if(!$('#cardAlerts').hasClass('d-none')) cargarAlertas();
}
function cargarAlertas(force){
  $('#alertsBody').html('Cargando...');
  enviarPeticion('procesos','alertasFlujo',{}, function(r){
    if(!r.ejecuto){ $('#alertsBody').html('<span class="text-danger">Error: '+(r.mensajeError||'')+'</span>'); return }
    var h='';
    var overallBadge = badgeEstadoSistema(r.overall);
    h += '<div class="mb-2">Estado general: '+overallBadge+'</div>';
    var alerts = r.alerts||[]; if(!alerts.length){ h+='<div class="text-success">Sin alertas activas</div>'; }
    else{
      h += '<table class="table table-sm table-striped mb-1"><thead><tr><th>Severidad</th><th>Código</th><th>Mensaje</th><th>Valor</th><th>Umbral</th></tr></thead><tbody>';
      alerts.forEach(function(a){
        h+='<tr>'+\
          '<td>'+badgeSeveridad(a.severidad)+'</td>'+\
          '<td>'+a.code+'</td>'+\
          '<td>'+escapeHtml(a.mensaje)+'</td>'+\
          '<td>'+(a.valor!==undefined?a.valor:'')+'</td>'+\
          '<td>'+(a.umbral!==undefined?a.umbral:'')+'</td>'+\
        '</tr>';
      });
      h+='</tbody></table>';
    }
    // Thresholds list
    if(r.thresholds){
      var th = Object.keys(r.thresholds).map(function(k){ return k+': '+r.thresholds[k]; }).join(' | ');
      h+='<div class="text-muted small">Umbrales: '+th+'</div>';
    }
    $('#alertsBody').html(h);
  });
}
function badgeSeveridad(s){
  var cls='secondary'; if(s==='danger') cls='danger'; else if(s==='warning') cls='warning'; else if(s==='info') cls='info'; else if(s==='ok') cls='success';
  return '<span class="badge badge-'+cls+'">'+s+'</span>';
}
function badgeEstadoSistema(s){
  if(s==='ok') return '<span class="badge badge-success">OK</span>'; if(s==='warning') return '<span class="badge badge-warning">WARNING</span>'; if(s==='degraded') return '<span class="badge badge-danger">DEGRADED</span>'; return '<span class="badge badge-secondary">'+s+'</span>';
}
function exportAlertas(){
  enviarPeticion('procesos','exportAlertasFlujo',{}, function(r){
    if(!r.ejecuto){ mostrarError(r); return }
    descargarBase64(r.csv,'alertas_flujo.csv','text/csv');
  });
}
// Poll ligero cada 60s para estado general de alertas; muestra indicador en botón
var _alertPollTimer=null; var _lastOverall='ok';
function iniciarAlertPolling(){
  if(_alertPollTimer) clearTimeout(_alertPollTimer);
  function cycle(){
    enviarPeticion('procesos','alertasFlujo',{simple:1}, function(r){
      if(r.ejecuto){
        var overall = r.overall || 'ok';
        if(overall!==_lastOverall){ _lastOverall = overall; }
        actualizarBadgeBotonAlertas(overall, r.alerts? r.alerts.length:0);
      }
      _alertPollTimer = setTimeout(cycle, 60000);
    });
  }
  cycle();
}
function actualizarBadgeBotonAlertas(overall, count){
  var btn = $('#btnAlerts'); btn.find('.badge-alert').remove();
  if(overall==='ok' && !count) return;
  var cls='badge-secondary'; if(overall==='warning') cls='badge-warning'; else if(overall==='degraded') cls='badge-danger'; else if(count>0) cls='badge-info';
  var text = (overall==='ok')?count:(overall==='degraded'?'!':(overall==='warning'?'?':overall.charAt(0)));
  btn.append(' <span class="badge badge-alert '+cls+'" style="position:relative;top:-2px;">'+text+'</span>');
}
function descargarBase64(b64, filename, mime){
  try{
    var a = document.createElement('a');
    a.href = 'data:'+(mime||'application/octet-stream')+';base64,'+b64;
    a.download = filename || 'download.dat';
    document.body.appendChild(a); a.click(); document.body.removeChild(a);
  }catch(e){ toastr.error('No se pudo descargar'); }
}
// ----- FORCE TRANSICION -----
function prepararModalForce(){
  if($('#modalForce').length) return; // evitar duplicado
  var html = '<div class="modal fade" id="modalForce" tabindex="-1"><div class="modal-dialog modal-sm"><div class="modal-content">'+
    '<div class="modal-header py-2"><h6 class="modal-title">Forzar transición</h6><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button></div>'+
    '<div class="modal-body">'+
      '<form id="formForce">'+
        '<input type="hidden" id="forceProceso">'+
        '<div class="form-group mb-2"><label class="small mb-0">Estado destino</label><select class="form-control form-control-sm" id="forceEstado"></select></div>'+
        '<div class="form-group mb-1"><label class="small mb-0">Motivo</label><textarea class="form-control form-control-sm" id="forceMotivo" rows="3" maxlength="300" placeholder="Explique la razón del override (mín 5 caracteres)"></textarea></div>'+
        '<div class="form-group mb-1"><label class="small mb-0">Confirmación</label><input type="text" class="form-control form-control-sm" id="forceConfirm" placeholder="Escriba FORZAR" maxlength="10"></div>'+
      '</form>'+
      '<div class="alert alert-warning py-1 px-2 small mb-1"><i class="fas fa-exclamation-triangle"></i> Se registrará en el histórico. Acción irreversible.</div>'+
      '<div class="small text-muted" id="forceThrottleInfo"></div>'+
    '</div>'+
    '<div class="modal-footer py-2"><button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancelar</button><button type="button" id="btnDoForce" class="btn btn-sm btn-danger">Forzar</button></div>'+
  '</div></div></div>';
  $('body').append(html);
  $('#btnDoForce').on('click', ejecutarForce);
  construirOpcionesEstados();
}
function construirOpcionesEstados(){
  var sel = $('#forceEstado'); sel.empty();
  if(typeof estados==='undefined'){ sel.append('<option value="">(sin mapa)</option>'); return }
  Object.keys(estados).sort(function(a,b){return parseInt(a)-parseInt(b)}).forEach(function(k){ sel.append('<option value="'+k+'">'+k+' - '+estados[k]+'</option>'); });
}
function abrirForce(idProceso, estadoActual){
  if(typeof estados==='undefined'){ toastr.error('Mapa de estados no cargado'); return }
  $('#forceProceso').val(idProceso);
  $('#forceEstado').val(estadoActual);
  $('#forceMotivo').val('');
  $('#modalForce').modal('show');
  actualizarForceStatus();
}
function ejecutarForce(){
  var id = parseInt($('#forceProceso').val(),10); var dest = parseInt($('#forceEstado').val(),10); var mot = $('#forceMotivo').val().trim();
  var conf = $('#forceConfirm').val().trim().toUpperCase();
  if(conf !== 'FORZAR'){ toastr.error('Debe escribir FORZAR para confirmar'); return }
  if(!id || !dest){ toastr.error('Datos incompletos'); return }
  if(mot.length < 5){ toastr.error('Motivo muy corto'); return }
  // Verificación previa rápida de throttle antes de enviar
  $('#btnDoForce').prop('disabled', true).text('Enviando...');
  enviarPeticion('procesos','forceTransicion',{idProceso:id, estadoDestino:dest, motivo:mot}, function(r){
    $('#btnDoForce').prop('disabled', false).text('Forzar');
    if(!r.ejecuto){ mostrarError(r); return }
    toastr.success('Transición forzada');
    $('#modalForce').modal('hide');
    cargarDiagnostico();
    cargarHistorico(id); // refrescar histórico tras override
    if(r.throttle){ $('#forceThrottleInfo').text('Overrides usados esta hora: '+r.throttle.usadosHora+'/'+r.throttle.limiteHora); }
  });
}
// Actualiza estado de throttle al abrir modal y cada pocos segundos mientras esté visible
function actualizarForceStatus(){
  if(!$('#modalForce').is(':visible')) return; // evitar llamadas innecesarias
  enviarPeticion('procesos','forceTransicionStatus',{}, function(r){
    if(!r.ejecuto){ $('#forceThrottleInfo').text('No se pudo obtener estado overrides'); return }
    var txt = 'Overrides usados última hora: '+r.usadosUltimaHora+'/'+r.limiteHora+' | ';    
    if(!r.puedeEjecutar){
      if(r.segundosCooldownRestantes>0){
        txt += 'Cooldown '+r.segundosCooldownRestantes+'s';
      }else if(r.usadosUltimaHora>=r.limiteHora){
        txt += 'Límite por hora alcanzado';
      }
      $('#btnDoForce').prop('disabled', true);
    }else{
      txt += 'Disponible';
      $('#btnDoForce').prop('disabled', false);
    }
    $('#forceThrottleInfo').text(txt);
  });
  // Reprogramar actualización cada 5s mientras el modal siga abierto
  setTimeout(function(){ actualizarForceStatus(); }, 5000);
}
</script>
</body>
</html>
