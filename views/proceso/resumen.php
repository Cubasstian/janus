<?php require('views/header.php');?>
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6"><h1>Proceso - Resumen y Timeline <span id="badgeOverride" class="badge badge-danger d-none" data-toggle="tooltip" title="Transiciones forzadas detectadas">Override</span></h1></div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="main/inicio/">Inicio</a></li>
            <li class="breadcrumb-item active">Resumen proceso</li>
          </ol>
        </div>
      </div>
    </div>
  </section>
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-4">
          <div class="card card-outline card-primary">
            <div class="card-header">Buscar proceso</div>
            <div class="card-body">
              <form id="formBuscarProceso">
                <div class="form-group">
                  <label for="idProceso">ID Proceso</label>
                  <input type="number" class="form-control" id="idProceso" min="1" placeholder="Ej: 123" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Cargar</button>
              </form>
              <div id="infoProceso" style="display:none;">
                <hr>
                <ul class="list-unstyled small">
                  <li><b>Proceso:</b> <span id="lblProceso"></span></li>
                  <li><b>Solicitud:</b> <span id="lblSolicitud"></span></li>
                  <li><b>Estado actual:</b> <span id="lblEstado"></span></li>
                  <li><b>Gerencia:</b> <span id="lblGerencia"></span></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-8">
          <div class="card card-outline card-success">
            <div class="card-header d-flex justify-content-between align-items-center">
              <span>Timeline</span>
              <div class="btn-group btn-group-xs">
                <button class="btn btn-outline-secondary" id="btnExportHistorico" title="Exportar histórico CSV" disabled><i class="fas fa-file-csv"></i></button>
              </div>
            </div>
            <div class="card-body p-0">
              <ul class="list-group list-group-flush" id="timeline"></ul>
            </div>
            <div class="card-footer py-1 small text-muted" id="legendTimeline">
              <strong>Leyenda:</strong>
              <span class="badge badge-danger">OVR</span> override forzado (tooltip muestra detalle) ·
              <span class="badge badge-success">PR</span> prerequisitos completos ·
              <span class="badge badge-warning">PR</span> pendientes aprobación ·
              <span class="badge badge-danger">PR</span> faltan documentos ·
              <span class="badge badge-light">PR</span> cargando
            </div>
          </div>
          <div class="card card-outline card-info">
            <div class="card-header d-flex justify-content-between align-items-center">
              <span>Prerequisitos por acción</span>
              <button class="btn btn-xs btn-outline-secondary" id="btnRefrescarPrereq"><i class="fas fa-sync-alt"></i></button>
            </div>
            <div class="card-body p-0">
              <table class="table table-sm mb-0" id="tablaPrereq">
                <thead class="thead-light"><tr><th>Acción</th><th>Requeridos</th><th>Faltan</th><th>Pendientes</th><th>Estado</th></tr></thead>
                <tbody></tbody>
              </table>
            </div>
            <div class="card-footer py-1 small" id="resumenPrereq"></div>
          </div>
          <div class="card card-outline card-warning">
            <div class="card-header">Documentos</div>
            <div class="card-body">
              <div class="d-flex align-items-center">
                <button type="button" class="btn btn-outline-secondary mr-2" id="btnVerMinuta" disabled>
                  <i class="fas fa-file-pdf"></i> Minuta
                </button>
                <button type="button" class="btn btn-outline-secondary" id="btnVerActa" disabled>
                  <i class="fas fa-file-pdf"></i> Acta de inicio
                </button>
              </div>
              <small class="form-text text-muted">Se habilitan si existen en el sistema.</small>
            </div>
          </div>
          <div class="card card-outline card-secondary">
            <div class="card-header">Detalle Campos Clave</div>
            <div class="card-body p-0">
              <table class="table table-sm table-striped mb-0" id="tablaDetalle">
                <thead><tr><th>Campo</th><th>Valor</th></tr></thead>
                <tbody></tbody>
              </table>
            </div>
          </div>
          <div class="card card-outline card-info">
            <div class="card-header">Histórico de acciones</div>
            <div class="card-body p-0">
              <table class="table table-sm table-hover mb-0" id="tablaHistorico">
                <thead><tr><th>Fecha</th><th>Usuario</th><th>Información</th></tr></thead>
                <tbody></tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<?php require('views/footer.php');?>
<script type="text/javascript">
  var idProcesoSel = 0, idSolicitudSel = 0, detalleProceso=null
  var accionesFlujo = ['validarPerfil','minuta','numerar','solicitudAfiliacion','afiliarARL','expedirRP','recogerRP','designarSupervisor','actaInicio']
  var overridesHistorico = []
  var mapaEstadoAccion = { 6:'ciip',7:'evaluarEEP',8:'validarPerfil',9:'recogerPerfil',10:'minuta',11:'numerar',12:'solicitudAfiliacion',13:'afiliarARL',14:'expedirRP',15:'recogerRP',16:'designarSupervisor',17:'actaInicio' }
  var cachePrereqEstados = {} // estado -> {ok:boolean, faltantes:int, pendientes:int}
  function init(){
    $('#formBuscarProceso').on('submit', function(e){
      e.preventDefault()
      let idp = parseInt($('#idProceso').val(),10)
      if(!idp){ toastr.warning('Ingrese un ID válido'); return }
      // 1. Proceso base
      enviarPeticion('procesos','getProcesos',{criterio:'id',id:idp}, function(r){
        if(!(r.ejecuto && r.data && r.data.length)){
          toastr.error('No se encontró el proceso'); return
        }
        let p = r.data[0]; idProcesoSel = p.id
        $('#lblProceso').text(p.id)
        $('#lblGerencia').text(p.gerencia || '-')
        let estadoTxt = (typeof estados!=='undefined' && estados[p.estado])?estados[p.estado]:p.estado
        $('#lblEstado').text(estadoTxt)
        // 2. Detalle proceso
        enviarPeticion('procesos','getProcesoDetalle',{id:idProcesoSel}, function(det){
          if(det.ejecuto && det.data && det.data.length){
            detalleProceso = det.data[0]
            idSolicitudSel = detalleProceso.idSolicitud
            $('#lblSolicitud').text(idSolicitudSel)
            construirTablaDetalle(detalleProceso)
            cargarHistoricoAcciones(idSolicitudSel)
            detectarDocumentos(detalleProceso)
            cargarPrerequisitos()
            verificarOverridesProceso(idProcesoSel)
            $('#btnExportHistorico').prop('disabled', false).off('click').on('click', function(){ if(idProcesoSel){ exportarHistoricoProceso(idProcesoSel); } });
            $('#infoProceso').show()
            precargarPrereqEstados()
          }
        })
      })
    })
  $('#btnRefrescarPrereq').on('click', function(){ if(idProcesoSel){ cargarPrerequisitos(); precargarPrereqEstados(); } })
    $(function(){ $('[data-toggle="tooltip"]').tooltip(); })
  }
  function verificarOverridesProceso(id){
    overridesHistorico = []
    if(!id){ $('#badgeOverride').addClass('d-none'); return }
    enviarPeticion('procesos','getHistoricoProceso',{idProceso:id}, function(r){
      if(r.ejecuto){
        overridesHistorico = (r.data||[]).filter(x=>x.override)
        if(overridesHistorico.length){
          var ult = overridesHistorico[overridesHistorico.length-1]
          $('#badgeOverride').removeClass('d-none').attr('title', 'Último override: '+(ult.fecha||'')+' por '+(ult.usuario||'')+(ult.motivo_override? (' | '+ult.motivo_override):'')).tooltip('dispose').tooltip()
        } else { $('#badgeOverride').addClass('d-none') }
        construirTimeline(detalleProceso) // reconstruir con marcas
      }
    })
  }
  function construirTablaDetalle(dp){
    const campos = {
      'fecha_eep':'Fecha EEP','resultado_eep':'Resultado EEP','fecha_validacion_perfil':'Fecha validación perfil','perfil_validado':'Perfil validado','fecha_recoger_perfil':'Fecha recoger perfil','fecha_minuta':'Fecha minuta','numero_contrato':'Número contrato','fecha_numeracion':'Fecha numeración','fecha_arl':'Fecha ARL','numero_rp':'Número RP','fecha_rp':'Fecha RP','fecha_recoger_rp':'Fecha recoger RP','fk_supervisor':'Supervisor','fecha_supervisor':'Fecha supervisor','numero_acta_inicio':'Número acta inicio','fecha_acta_inicio':'Fecha acta inicio'
    }
    let tbody = $('#tablaDetalle tbody').empty()
    Object.keys(campos).forEach(c=>{ if(dp[c]!==null && dp[c]!=='' ){ tbody.append(`<tr><td>${campos[c]}</td><td>${dp[c]}</td></tr>`) } })
    if(tbody.children().length===0){ tbody.append('<tr><td colspan="2" class="text-center text-muted">Sin datos</td></tr>') }
  }
  function construirTimeline(dp){
    let tl = $('#timeline').empty()
    let estadosLen = estados.length || 0
    for(let i=1;i<estadosLen;i++){
      let actual = (dp.estado == i)
      let claseBase = 'list-group-item'
      let clase
      if(i < dp.estado){ clase = claseBase + ' list-group-item-success' }
      else if(actual){ clase = claseBase + ' list-group-item-' + (colores[i]||'primary') }
      else { clase = claseBase }
      // Marcar override si algún override dejó proceso en este estado
      let relacionados = overridesHistorico.filter(o=> parseInt(o.estado)==i)
      let flag = ''
      if(relacionados.length){
        // tooltip enumerando todos los overrides que aterrizaron en este estado
        let detalle = relacionados.map(o=> (o.fecha||'')+' - '+(o.usuario||'')+(o.motivo_override?(' => '+o.motivo_override.replace(/"/g,'\"')):'')).join('\n')
        flag = `<span class="ml-1 badge badge-danger" data-toggle="tooltip" data-placement="top" title="${detalle}">OVR${relacionados.length>1?('('+relacionados.length+')'):''}</span>`
      }
      // Badge de prerequisitos (solo para estados que corresponden a una acción futura o actual)
      let badgePrereq = ''
      if(mapaEstadoAccion[i]){
        let pr = cachePrereqEstados[i]
        if(pr){
          if(pr.ok){ badgePrereq = ' <span class="badge badge-success ml-1" title="Prerequisitos completos">PR</span>' }
          else if(pr.faltantes>0){ badgePrereq = ` <span class="badge badge-danger ml-1" title="Faltan ${pr.faltantes} prerequisito(s)">PR</span>` }
          else if(pr.pendientes>0){ badgePrereq = ` <span class="badge badge-warning ml-1" title="Pendientes aprobación: ${pr.pendientes}">PR</span>` }
        }else{
          badgePrereq = ' <span class="badge badge-light ml-1" title="Cargando prerequisitos...">PR</span>'
        }
      }
      tl.append(`<li class="${clase}"><span class="badge badge-secondary mr-1">${i}</span>${estados[i]} ${flag}${badgePrereq}</li>`)
    }
    // Re inicializar tooltips para nuevos nodos
    if(typeof $!=='undefined'){ $('#timeline [data-toggle="tooltip"]').tooltip(); }
  }
  // Pre-cargar prerequisitos de acciones mapeadas a estados para pintar timeline
  function precargarPrereqEstados(){
    cachePrereqEstados = {}
    var entries = Object.entries(mapaEstadoAccion)
    var pendientes = entries.length, completados = 0
    entries.forEach(function(pair){
      var estado = parseInt(pair[0],10); var accion = pair[1]
      enviarPeticion('procesos','checkPrerequisitos',{accion:accion, idProceso:idProcesoSel}, function(r){
        var falt = r.faltantes ? r.faltantes.filter(x=>x).length : 0
        var pend = r.pendientes ? r.pendientes.filter(x=>x).length : 0
        var ok = (falt===0 && pend===0 && (r.faltantes||r.pendientes))
        cachePrereqEstados[estado] = { ok: ok, faltantes: falt, pendientes: pend }
        completados++
        if(completados===pendientes){ construirTimeline(detalleProceso) }
      })
    })
  }
  function cargarHistoricoAcciones(idSolicitud){
    enviarPeticion('solicitudesHistorico','getHistorico',{solicitud:idSolicitud}, function(r){
      let tb = $('#tablaHistorico tbody').empty()
      if(r.ejecuto && r.data && r.data.length){
        r.data.forEach(h=>{ tb.append(`<tr><td>${h.fecha_creacion}</td><td>${h.nombre}</td><td>${h.informacion}</td></tr>`) })
      }else{ tb.append('<tr><td colspan="3" class="text-center text-muted">Sin registros</td></tr>') }
    })
  }
  // Detectar documentos existentes (Minuta y Acta de inicio)
  function detectarDocumentos(dp){
    const idPS = dp.contratista
    if(!idPS){ return }
    // Resolver tipo Minuta
    enviarPeticion('documentosTipo','select',{info:{nombre:'Minuta'}}, function(dt){
      if(dt.ejecuto && dt.data && dt.data.length){ buscarDoc(dt.data[0].id, 'btnVerMinuta') }
    })
    // Resolver tipo Acta de inicio (variantes de capitalización)
    enviarPeticion('documentosTipo','select',{info:{nombre:'Acta de inicio'}}, function(dt){
      if(dt.ejecuto && dt.data && dt.data.length){
        buscarDoc(dt.data[0].id, 'btnVerActa')
      }else{
        enviarPeticion('documentosTipo','select',{info:{nombre:'Acta de Inicio'}}, function(dt2){
          if(dt2.ejecuto && dt2.data && dt2.data.length){ buscarDoc(dt2.data[0].id, 'btnVerActa') }
        })
      }
    })

    function buscarDoc(tipoId, btnId){
      enviarPeticion('documentos','select',{info:{contratista:idPS, fk_procesos:idProcesoSel, fk_documentos_tipo: tipoId}}, function(r2){
        if(r2.ejecuto && r2.data && r2.data.length){
          const idDoc = r2.data[0].id
          $('#'+btnId).prop('disabled', false).data('docid', idDoc)
        }
      })
    }
  }
  // Click handlers para abrir PDFs si están detectados
  $(document).on('click', '#btnVerMinuta', function(){
    const idDoc = $(this).data('docid'); if(idDoc){ downloadDocument(idDoc) }
  })
  $(document).on('click', '#btnVerActa', function(){
    const idDoc = $(this).data('docid'); if(idDoc){ downloadDocument(idDoc) }
  })

  function cargarPrerequisitos(){
    let tbody = $('#tablaPrereq tbody').empty()
    if(!idProcesoSel){ tbody.append('<tr><td colspan="5" class="text-center text-muted">Sin proceso</td></tr>'); return }
    let pendientesTot = 0, faltantesTot = 0, totalAcc = 0, completas = 0
    let requests = 0, terminados = 0
    accionesFlujo.forEach(acc=>{
      requests++
      enviarPeticion('procesos','checkPrerequisitos',{accion:acc, idProceso:idProcesoSel}, function(r){
        terminados++
        totalAcc++
        let falt = r.faltantes ? r.faltantes.filter(x=>x).length : 0
        let pend = r.pendientes ? r.pendientes.filter(x=>x).length : 0
        faltantesTot += falt; pendientesTot += pend
        let estado = 'Sin requisitos'
        let badge = 'secondary'
        if(falt===0 && pend===0 && (r.faltantes||r.pendientes)) { estado='Cumplidos'; badge='success'; completas++; }
        else if(falt===0 && pend>0){ estado='Pendientes aprobación'; badge='warning'; }
        else if(falt>0){ estado='Faltan'; badge='danger'; }
        tbody.append(`<tr><td>${acc}</td><td>${(r.faltantes?r.faltantes.length+(r.pendientes?r.pendientes.length:0): (r.pendientes?r.pendientes.length: ( (falt+pend)>0?(falt+pend):'-')))}</td><td>${falt||'-'}</td><td>${pend||'-'}</td><td><span class="badge badge-${badge}">${estado}</span></td></tr>`)
        if(terminados===requests){
          $('#resumenPrereq').text(`${completas}/${totalAcc} acciones sin pendientes | Faltantes: ${faltantesTot} | Pendientes aprobación: ${pendientesTot}`)
          if(tbody.children().length===0){ tbody.append('<tr><td colspan="5" class="text-center text-muted">Sin datos</td></tr>') }
        }
      })
    })
  }
</script>
</body>
</html>
