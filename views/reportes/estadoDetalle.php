<?php require('views/header.php');?>
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6"><h1>Detalle por Estado</h1></div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="main/inicio/">Inicio</a></li>
            <li class="breadcrumb-item active">Detalle estado</li>
          </ol>
        </div>
      </div>
    </div>
  </section>
  <section class="content">
    <div class="container-fluid">
      <div class="card card-outline card-success">
        <div class="card-header d-flex flex-wrap align-items-center">
          <span class="mr-3">Listado (máx 200) - <strong id="lblEstadoSel">-</strong></span>
          <button class="btn btn-sm btn-outline-secondary mr-3" id="btnVolver" title="Volver al dashboard"><i class="fas fa-arrow-left"></i></button>
          <div class="form-inline ml-auto">
            <label class="mr-2 mb-1">Ámbito:</label>
            <select id="filtroAmbito" class="form-control form-control-sm mb-1 mr-2">
              <option value="global">Global</option>
              <option value="gerencia">Mi gerencia</option>
            </select>
            <select id="filtroGerencia" class="form-control form-control-sm mb-1 mr-2 d-none"></select>
            <label class="mr-2 mb-1">Desde:</label>
            <input type="date" id="fechaDesde" class="form-control form-control-sm mb-1 mr-2" />
            <label class="mr-2 mb-1">Hasta:</label>
            <input type="date" id="fechaHasta" class="form-control form-control-sm mb-1 mr-2" />
            <div class="btn-group mb-1 mr-2" role="group">
              <button type="button" class="btn btn-xs btn-outline-primary preset-date" data-range="7" title="Últimos 7 días">7d</button>
              <button type="button" class="btn btn-xs btn-outline-primary preset-date" data-range="30" title="Últimos 30 días">30d</button>
              <button type="button" class="btn btn-xs btn-outline-primary preset-date" data-range="90" title="Últimos 90 días">90d</button>
            </div>
            <input type="text" id="filtroProfesion" placeholder="Profesión contiene..." class="form-control form-control-sm mb-1 mr-2" style="width:170px" />
            <button id="btnFiltrar" class="btn btn-sm btn-outline-success mb-1" title="Filtrar"><i class="fas fa-filter"></i></button>
            <button id="btnLimpiar" class="btn btn-sm btn-outline-secondary mb-1 ml-1" title="Limpiar"><i class="fas fa-eraser"></i></button>
            <button id="btnExport" class="btn btn-sm btn-outline-primary mb-1 ml-2" title="Exportar CSV"><i class="fas fa-file-csv"></i></button>
            <button id="btnExportXlsx" class="btn btn-sm btn-outline-success mb-1 ml-1" title="Exportar Excel"><i class="fas fa-file-excel"></i></button>
          </div>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-sm table-striped" id="tablaDetalle">
              <thead class="thead-light">
                <tr>
                  <th>ID</th>
                  <th>Fecha creación</th>
                  <th>Días sin cambio</th>
                  <th>Gerencia</th>
                  <th>Dependencia</th>
                  <th>Unidad</th>
                  <th>Profesión</th>
                  <th>PS</th>
                  <th>Cédula</th>
                  <th>Honorarios</th>
                  <th>Presupuesto</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<?php require('views/footer.php');?>
<script>
let dt=null; let estadoSel = null; let gerenciasCache=[];
function init(){
  // Recuperar estado desde sessionStorage (puesto por dashboard)
  try{ const e = sessionStorage.getItem('filtroEstado'); if(e){ estadoSel = parseInt(e,10); } }catch(err){}
  if(!estadoSel){ toastr.info('No se indicó un estado, regresando al inicio'); setTimeout(()=>window.location.href='main/inicio/',1500); return }
  if(typeof estados !== 'undefined' && estados[estadoSel]){ $('#lblEstadoSel').text(estados[estadoSel]+' (Estado '+estadoSel+')'); }
  configurarTabla();
  $('#btnVolver').on('click', ()=> window.location.href='main/inicio/');
  $('#btnFiltrar').on('click', ()=> cargarDatos());
  $('#btnLimpiar').on('click', ()=>{ $('#fechaDesde').val(''); $('#fechaHasta').val(''); $('#filtroProfesion').val(''); $('#filtroGerencia').val(''); cargarDatos(); });
  $('#btnExport').on('click', exportarCSV);
  $('#btnExportXlsx').on('click', exportarXlsx);
  $('#filtroAmbito, #fechaDesde, #fechaHasta, #filtroGerencia').on('change', ()=> cargarDatos());
  $('#filtroProfesion').on('keyup', debounce(()=> cargarDatos(), 400));
  $('.preset-date').on('click', function(){
     const days = parseInt($(this).data('range'),10); const end = new Date(); const start = new Date(); start.setDate(end.getDate()- (days-1));
     $('#fechaHasta').val(end.toISOString().substring(0,10));
     $('#fechaDesde').val(start.toISOString().substring(0,10));
     cargarDatos();
  });
  detectarAdminYCargarGerencias();
  cargarDatos();
}
function payloadBase(){
  const soloGer = $('#filtroAmbito').val()==='gerencia'?1:0;
  const sd=$('#fechaDesde').val(); const ed=$('#fechaHasta').val();
  const p={ estado: estadoSel, soloGerencia: soloGer };
  if(sd && ed){ p.startDate=sd; p.endDate=ed; }
  const gsel = $('#filtroGerencia').val(); if(gsel){ p.gerenciaId = parseInt(gsel,10); }
  const prof = $('#filtroProfesion').val(); if(prof){ p.profesionLike = prof; }
  return p;
}
function configurarTabla(){
  dt = $('#tablaDetalle').DataTable({
    paging:true, searching:true, info:true, destroy:true, order:[[0,'desc']],
    language:{ url: 'plugins/datatables/i18n/Spanish.json' },
    columns:[
      { data:'id' },
      { data:'fecha_creacion' },
      { data:'dias_desde_cambio' },
      { data:'gerencia' },
      { data:'dependencia' },
      { data:'unidad' },
      { data:'profesion' },
      { data:'ps' },
      { data:'cedula' },
      { data:'honorarios', render:(d)=> $.number(d,0,',','.') },
      { data:'presupuesto', render:(d)=> $.number(d,0,',','.') }
    ],
    createdRow: function(row, data){
      const d = parseInt(data.dias_desde_cambio||0,10);
      if(d >= 30){ $(row).addClass('table-danger'); }
      else if(d >= 15){ $(row).addClass('table-warning'); }
      else if(d >= 7){ $(row).addClass('table-info'); }
    }
  });
}
function cargarDatos(){
  const payload = payloadBase();
  enviarPeticion('solicitudes','getSolicitudesDashboard', payload, function(r){
    if(!(r && r.ejecuto)){
      toastr.error(r && r.mensajeError ? r.mensajeError : 'Error consultando');
      return;
    }
    dt.clear(); dt.rows.add(r.data || []); dt.draw();
  });
}
function exportarCSV(){
  const payload = payloadBase();
  enviarPeticion('solicitudes','exportSolicitudesEstado', payload, function(r){
    if(!(r && r.ejecuto && r.csv)){ toastr.error('No se pudo exportar'); return }
    try{
      const blobStr = atob(r.csv);
      const url = URL.createObjectURL(new Blob([blobStr], {type:'text/csv;charset=utf-8;'}));
      const a = document.createElement('a'); a.href = url;
      const fecha = new Date().toISOString().substring(0,10);
      a.download = 'solicitudes_estado_'+estadoSel+'_'+fecha+'.csv';
      document.body.appendChild(a); a.click();
      setTimeout(()=>{ URL.revokeObjectURL(url); a.remove(); }, 300);
    }catch(e){ toastr.error('Error generando archivo'); }
  });
}
function exportarXlsx(){
  const payload = payloadBase();
  enviarPeticion('solicitudes','exportSolicitudesEstadoXlsx', payload, function(r){
    if(!(r && r.ejecuto && r.xlsx)){ toastr.error('No se pudo exportar'); return }
    try{
      const bin = atob(r.xlsx);
      const len = bin.length; const buf = new Uint8Array(len); for(let i=0;i<len;i++){ buf[i]=bin.charCodeAt(i); }
      const blob = new Blob([buf], {type:'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'});
      const url = URL.createObjectURL(blob);
      const a = document.createElement('a'); a.href=url; const fecha = new Date().toISOString().substring(0,10);
      a.download = 'solicitudes_estado_'+estadoSel+'_'+fecha+'.xlsx'; document.body.appendChild(a); a.click();
      setTimeout(()=>{ URL.revokeObjectURL(url); a.remove(); },300);
    }catch(e){ toastr.error('Error generando archivo'); }
  });
}
function detectarAdminYCargarGerencias(){
  // Usa variable de sesión ya cargada en footer (r) -> no disponible aquí directamente; pedimos helper.
  enviarPeticion('helpers','getSession',{1:1}, function(resp){
     if(!(resp && resp.data && resp.data.usuario)) return;
     if(resp.data.usuario.rol === 'Administrador'){
        $('#filtroGerencia').removeClass('d-none');
        cargarGerencias();
     }
  });
}
function cargarGerencias(){
  if(gerenciasCache.length){ poblarGerencias(); return }
  enviarPeticion('gerencias','getGerencias',{criterio:'rol'}, function(r){
     if(r && r.ejecuto){ gerenciasCache = r.data || []; poblarGerencias(); }
  });
}
function poblarGerencias(){
  const sel = $('#filtroGerencia'); sel.empty(); sel.append('<option value="">(Gerencia)</option>');
  gerenciasCache.forEach(g=> sel.append(`<option value="${g.id}">${g.nombre}</option>`));
}
function debounce(fn, wait){ let t; return function(){ clearTimeout(t); t=setTimeout(()=>fn.apply(this, arguments), wait); } }
</script>
</body>
</html>

