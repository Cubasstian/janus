<?php require('views/header.php'); ?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Inicio</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="main/inicio/">Inicio</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
                <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3 id="supSolicitudes">-</h3>
                            <p>Pendientes de ubicar</p>
                        </div>
                        <div class="icon"><i class="fas fa-stopwatch"></i></div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3 id="supDias">-</h3>
                            <p>Días promedio</p>
                        </div>
                        <div class="icon"><i class="far fa-calendar-alt"></i></div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3 id="supTU">-</h3>
                            <p>Por vencerse</p>
                        </div>
                        <div class="icon"><i class="fas fa-tachometer-alt"></i></div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3 id="supPromedio">-</h3>
                            <p>En trámite</p>
                        </div>
                        <div class="icon"><i class="fas fa-tools"></i></div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3 id="supCompletados">-</h3>
                            <p>Completados (%)</p>
                        </div>
                        <div class="icon"><i class="fas fa-check-double"></i></div>
                    </div>
                </div>
            </div>
                    <hr/>
                    <div class="row mb-2">
                        <div class="col-md-3">
                            <label class="small mb-1">Gerencia</label>
                            <select id="filtroGerencia" class="form-control form-control-sm"><option value="">(Todas)</option></select>
                        </div>
                        <div class="col-md-2">
                            <label class="small mb-1">Semanas</label>
                            <input type="number" id="weeksTrend" class="form-control form-control-sm" value="8" min="2" max="52" />
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button id="btnRefrescar" class="btn btn-sm btn-outline-primary btn-block"><i class="fas fa-sync-alt"></i> Refrescar</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-7">
                            <div class="card card-outline card-success">
                                <div class="card-header py-2"><strong>Solicitudes por estado</strong></div>
                                <div class="card-body" style="height:320px">
                                    <canvas id="chartEstados"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="card card-outline card-info">
                                <div class="card-header py-2"><strong>Tendencia semanal</strong></div>
                                <div class="card-body" style="height:320px">
                                    <canvas id="chartTrend"></canvas>
                                </div>
                            </div>
                            <ul id="listaEstados" class="small mt-2 list-unstyled mb-0"></ul>
                        </div>
                    </div>
        </div>
    </section>
</div>

<script type="text/javascript">
// Script mínimo: sólo tarjetas KPI
window.init = function(){
    // Verificar si el usuario es PS y redirigir
    enviarPeticion('helpers', 'getSession', {1:1}, function(sessionData) {
        if (sessionData && sessionData.ejecuto && sessionData.data && sessionData.data.usuario) {
            if (sessionData.data.usuario.rol === 'PS') {
                window.location.href = 'ps/informacion/';
                return;
            }
        }
        
        // Mostrar mensaje de bienvenida SOLO si viene del login
        if(sessionStorage.getItem('mostrarBienvenida') === 'true'){
            const nombreUsuario = sessionStorage.getItem('nombreUsuario') || '';
            if(nombreUsuario){
                toastr.success(`¡Bienvenido ${nombreUsuario}!`);
            }
            // Limpiar flag para que no se muestre en recargas
            sessionStorage.removeItem('mostrarBienvenida');
            sessionStorage.removeItem('nombreUsuario');
        }
        
        // Continuar con la lógica normal para otros roles
        if(window.__cardsInit){ return; }
        window.__cardsInit = true;
        cargarTarjetas();
        cargarGerencias();
        cargarResumenEstados();
        cargarTrend();
        setInterval(cargarTarjetas, 60000);
    });
};
function cargarTarjetas(){
    // Tarjetas siempre globales (no dependen del filtro de gerencia)
    var payload = { porVencerDias:15 };
    // payload.debug = 1; // descomentar para ver SQL en respuesta
    enviarPeticion('solicitudes','getKPIs', payload, function(r){
        // console.debug('KPIs resp', r);
        if(!(r && r.ejecuto && r.data && r.data.length)){
            actualizarTarjetas('-', '-', '-', '-', '-');
            return;
        }
        var k = r.data[0];
        var pendientes = k.pendientesUbicar || 0;
        var diasProm = k.diasPromedio || 0;
        var pv = k.porVencerse || 0;
        var tramite = k.enTramite || 0;
        var completados = parseInt(k.completados||0,10);
        var total = parseInt(k.total||0,10);
        var pct = (total>0)? ((completados*100/total).toFixed(1)): '0.0';
        actualizarTarjetas(pendientes, diasProm, pv, tramite, completados + ' (' + pct + '%)');
    });
}
// ==== GRAFICO ESTADOS ====
var chartEstados=null; var chartTrend=null;

function _bindEventosDash(){
    if(!window.jQuery){ return; }
    if(window.__dashEventsBound){ return; }
    window.__dashEventsBound = true;
    $('#btnRefrescar').on('click', function(){ recargarTodo(); });
    $('#filtroGerencia, #weeksTrend').on('change', function(){ recargarTodo(); });
}

// Esperar jQuery y luego enlazar eventos + ejecutar init (si algún otro script no lo hace)
(function esperarRecursos(){
    if(!window.jQuery || typeof enviarPeticion !== 'function'){
        return setTimeout(esperarRecursos,50);
    }
    _bindEventosDash();
    if(!window.__cardsInit){ window.init(); }
})();

function recargarTodo(){ cargarTarjetas(); cargarResumenEstados(); cargarTrend(); }

function cargarGerencias(){
    enviarPeticion('gerencias','getGerencias',{ criterio:'rol' }, function(r){
        if(!(r && r.ejecuto)) return;
        var sel=$('#filtroGerencia');
        sel.find('option:not(:first)').remove();
        (r.data||[]).forEach(function(g){ sel.append('<option value="'+g.id+'">'+g.nombre+'</option>'); });
    });
}

function baseFiltro(){
    var g=$('#filtroGerencia').val();
    var out={};
    if(g){ out.gerenciaId = parseInt(g,10); }
    return out;
}

function cargarResumenEstados(){
    var payload = baseFiltro();
    enviarPeticion('solicitudes','getResumenEstados', payload, function(r){
        if(!(r && r.ejecuto)) return;
        var data=r.data||[]; var labels=[]; var valores=[]; var total=0; var listaHtml='';
        data.forEach(function(row){
            var est=parseInt(row.estado,10); var cant=parseInt(row.cantidad,10)||0; labels.push(nombreEstado(est)); valores.push(cant); total+=cant;
        });
        data.forEach(function(row,idx){
            var cant=valores[idx]; var pct= total>0 ? ( (cant*100/total).toFixed(1)+'%' ) : '0%';
            listaHtml+='<li><span class="badge badge-secondary mr-1">'+cant+'</span>'+labels[idx]+' <span class="text-muted">('+pct+')</span></li>';
        });
        $('#listaEstados').html(listaHtml || '<li class="text-muted">Sin datos</li>');
        renderChartEstados(labels,valores);
    });
}

    // Mapeo de estados a nombres legibles
    function nombreEstado(e){
        var map = {
            1:'Pendiente ubicación',
            2:'Solicitud creada',
            3:'Revisión documentos',
            4:'CDP',
            5:'Contratación',
            6:'Examen preocupacional',
            7:'Aprobación jurídica',
            8:'Firma contrato',
            9:'Inducción',
            10:'Inicio actividades',
            11:'Seguimiento',
            12:'Evaluación',
            13:'Prórroga',
            14:'Liquidación',
            15:'Informe final',
            16:'Cierre trámite',
            17:'Completado',
            18:'Finalizado'
        };
        return map[e] || ('Estado '+e);
    }

function renderChartEstados(labels,valores){
    if(typeof Chart==='undefined'){ setTimeout(function(){ renderChartEstados(labels,valores); },400); return; }
    var ctx=document.getElementById('chartEstados'); if(!ctx) return; ctx=ctx.getContext('2d');
    if(chartEstados){ chartEstados.destroy(); }
    var colors = generarColores(labels.length);
    chartEstados=new Chart(ctx,{ type:'bar', data:{ labels:labels, datasets:[{ label:'Cantidad', data:valores, backgroundColor:colors.map(c=>c+'66'), borderColor:colors, borderWidth:1 }] }, options:{ responsive:true, maintainAspectRatio:false, scales:{ yAxes:[{ ticks:{ beginAtZero:true, precision:0 } }] } }});
}

function cargarTrend(){
    var payload = baseFiltro(); payload.weeks = parseInt($('#weeksTrend').val(),10)||8;
    enviarPeticion('solicitudes','getTrendSemanal', payload, function(r){
        if(!(r && r.ejecuto)) return;
        var rows=r.data||[]; var labels=[],nuevas=[],comp=[];
        rows.slice().reverse().forEach(function(row){ labels.push(row.semana); nuevas.push(parseInt(row.nuevas||0,10)); comp.push(parseInt(row.completadas||0,10)); });
        renderChartTrend(labels,nuevas,comp);
    });
}

function renderChartTrend(labels,nuevas,comp){
    if(typeof Chart==='undefined'){ setTimeout(function(){ renderChartTrend(labels,nuevas,comp); },400); return; }
    var ctx=document.getElementById('chartTrend'); if(!ctx) return; ctx=ctx.getContext('2d');
    if(chartTrend){ chartTrend.destroy(); }
    chartTrend=new Chart(ctx,{ type:'line', data:{ labels:labels, datasets:[ { label:'Nuevas', data:nuevas, borderColor:'#0d6efd', backgroundColor:'#0d6efd33', fill:true, lineTension:.25, pointRadius:2 }, { label:'Completadas', data:comp, borderColor:'#198754', backgroundColor:'#19875433', fill:true, lineTension:.25, pointRadius:2 } ] }, options:{ responsive:true, maintainAspectRatio:false, legend:{ display:true }, scales:{ yAxes:[{ ticks:{ beginAtZero:true, precision:0 } }], xAxes:[{ display:false }] } }});
}

function generarColores(n){ const base=['#0d6efd','#198754','#dc3545','#ffc107','#0dcaf0','#6f42c1','#fd7e14','#20c997','#6610f2','#1982c4','#6a4c93','#ff6b6b']; const out=[]; for(let i=0;i<n;i++){ out.push(base[i%base.length]); } return out; }
function actualizarTarjetas(a,b,c,d,e){
    $('#supSolicitudes').text(a);
    $('#supDias').text(b);
    $('#supTU').text(c);
    $('#supPromedio').text(d);
    $('#supCompletados').text(e);
}
</script>

<?php require('views/footer.php'); ?>
