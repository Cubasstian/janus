<?php require('views/header.php'); ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Buscar Solicitudes</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/">Inicio</a></li>
            <li class="breadcrumb-item active">Buscar solicitudes</li>
          </ol>
        </div>
      </div>
    </div>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      
      <!-- Filtros de búsqueda -->
      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">Filtros de búsqueda</h3>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="criterio">Buscar por:</label>
                <select class="form-control" id="criterio">
                  <option value="gerencia">Gerencia</option>
                  <option value="dependencia">Dependencia</option>
                  <option value="ps">PS</option>
                  <option value="cedula">Cédula</option>
                  <option value="profesion">Profesión</option>
                  <option value="idProceso">ID proceso</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="estado">Estado:</label>
                <select class="form-control" id="estado">
                  <option value="">Todos los estados</option>
                  <!-- opciones se llenan en init() desde la variable global 'estados' -->
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <button type="button" class="btn btn-primary" onclick="buscarSolicitudes()">
                <i class="fas fa-search"></i> Buscar
              </button>
              <button type="button" class="btn btn-secondary ml-2" onclick="limpiarBusqueda()">
                <i class="fas fa-eraser"></i> Limpiar
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Tabla de resultados -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Resultados de búsqueda</h3>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table id="tablaSolicitudes" class="tabla-procesos-estandar">
              <thead>
                <tr>
                  <th class="text-center">ID</th>
                  <th class="text-center">Estado</th>
                  <th>Gerencia</th>
                  <th>Dependencia</th>
                  <th>Unidad</th>
                  <th>Profesión</th>
                  <th class="text-right">Honorarios</th>
                  <th class="text-right">Presupuesto</th>
                  <th class="text-center">ID Proceso</th>
                  <th>PS</th>
                  <th>Cédula</th>
                  <th>Teléfono</th>
                  <th class="text-center">Tiempo</th>
                  <th class="text-center">Acciones</th>
                </tr>
              </thead>
              <tbody id="contenidoSolicitudes">
                <!-- Los datos se cargan dinámicamente -->
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </section>
</div>

<!-- Modal para mostrar detalle -->
<div class="modal fade" id="modalDetalle" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Detalle de solicitud</h4>
        <button type="button" class="close" data-dismiss="modal">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="detalleContenido">
        <!-- El contenido se carga dinámicamente -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<script>
  var dt = null;

  // Asegurar que jQuery esté disponible y encapsular todo el código
  $(document).ready(function(){
    // Verificar que todas las dependencias estén cargadas
    if (typeof $ === 'undefined') {
      console.error('jQuery no está cargado');
      return;
    }
    
    if (typeof $.number === 'undefined') {
      console.error('Plugin jQuery Number no está cargado');
      return;
    }
    
    init();
  });

  function init(){
    // Llenar select de estados usando variable global 'estados' definida en footer
    if (typeof estados !== 'undefined' && Array.isArray(estados)) {
      for (var i = 1; i < estados.length; i++) {
        if (estados[i]) {
          $('#estado').append(`<option value="${i}">${estados[i]}</option>`);
        }
      }
    }

    // Inicializar tabla vacía
    initTablaEstandar();
    
    // Cargar datos iniciales
    buscarSolicitudes();
  }

  // Función auxiliar para formatear números
  function formatNumber(num) {
    if (typeof $.number !== 'undefined') {
      return $.number(num || 0, 0, ',', '.');
    } else {
      // Fallback si no está disponible el plugin
      return (num || 0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
  }

  function initTablaEstandar() {
    if (dt) {
      dt.destroy();
    }
    
    dt = $('#tablaSolicitudes').DataTable({
      lengthMenu: [25, 50, 100, 200],
      pageLength: 50,
      order: [[0, 'desc']], // Ordenar por ID descendente
      language: {
        decimal: "",
        emptyTable: "No hay solicitudes que mostrar",
        info: "Mostrando _START_ al _END_ de _TOTAL_ solicitudes",
        infoEmpty: "Mostrando 0 al 0 de 0 solicitudes",
        infoFiltered: "(Filtrado de _MAX_ total solicitudes)",
        lengthMenu: "Mostrar _MENU_ solicitudes",
        loadingRecords: "Cargando...",
        processing: "Procesando...",
        search: "Buscar en tabla:",
        zeroRecords: "No se encontraron solicitudes que coincidan",
        paginate: {
          first: "Primero",
          last: "Último", 
          next: "Siguiente",
          previous: "Anterior"
        }
      },
      responsive: true,
      scrollX: true,
      fixedHeader: true,
      columnDefs: [
        { targets: [0, 1, 8, 12, 13], className: 'text-center' },
        { targets: [6, 7], className: 'text-right' },
        { targets: 13, orderable: false, searchable: false }
      ]
    });
  }

  function buscarSolicitudes(callback) {
    var criterio = $('#criterio').val();
    var estado = $('#estado').val();
    
    var datos = { criterio: criterio };
    
    if (estado) {
      datos.estado = estado;
    }

    enviarPeticion('solicitudes', 'getSolicitudes', datos, function(r){
      if(r.ejecuto === true){
        renderTabla(r.data || []);
      } else {
        toastr.error(r.mensajeError || 'Error al consultar solicitudes');
        renderTabla([]);
      }
      if(callback) callback();
    });
  }

  function renderTabla(registros){
    var cuerpo = '';
    
    registros.forEach(function(x){
      var estadoTxt = (typeof estados !== 'undefined' && estados[x.estado]) ? estados[x.estado] : (x.estado || '');
      var colorEstado = (typeof colores !== 'undefined' && colores[x.estado]) ? colores[x.estado] : 'secondary';
      
      cuerpo += `<tr>
        <td class="text-center">${x.id}</td>
        <td class="text-center">
          <span class="badge badge-${colorEstado}">${estadoTxt}</span>
        </td>
        <td>${x.gerencia || ''}</td>
        <td>${x.dependencia || ''}</td>
        <td>${x.unidad || ''}</td>
        <td>${x.profesion || ''}</td>
        <td class="text-right">$${formatNumber(x.honorarios)}</td>
        <td class="text-right">$${formatNumber(x.presupuesto)}</td>
        <td class="text-center">${x.idProceso || ''}</td>
        <td>${x.ps || ''}</td>
        <td>${x.cedula || ''}</td>
        <td>${x.telefono || ''}</td>
        <td class="text-center">${x.tiempo || 0} días</td>
        <td class="text-center">
          <button type="button" class="btn btn-sm btn-outline-primary" 
                  title="Ver detalle" onClick="mostrarDetalle(${x.id})">
            <i class="fas fa-eye"></i>
          </button>
        </td>
      </tr>`;
    });

    // Actualizar tabla
    dt.clear();
    if (cuerpo) {
      $('#contenidoSolicitudes').html(cuerpo);
      dt.rows.add($('#contenidoSolicitudes tr')).draw();
    } else {
      dt.draw();
    }
  }

  function limpiarBusqueda() {
    $('#criterio').val('gerencia');
    $('#estado').val('');
    buscarSolicitudes();
  }

  function mostrarDetalle(id){
    enviarPeticion('solicitudes', 'getSolicitudAll', {criterio:'id', valor:id}, function(r){
      if(r.ejecuto === true && r.data && r.data.length){
        var d = r.data[0];
        var estadoTxt = (typeof estados !== 'undefined' && estados[d.estado]) ? estados[d.estado] : (d.estado || '');
        var colorEstado = (typeof colores !== 'undefined' && colores[d.estado]) ? colores[d.estado] : 'secondary';
        
        var html = `
          <div class="row">
            <div class="col-md-6">
              <div class="info-box">
                <span class="info-box-icon bg-primary"><i class="fas fa-file-alt"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Información básica</span>
                  <ul class="list-unstyled mt-2">
                    <li><strong>ID solicitud:</strong> ${d.id}</li>
                    <li><strong>ID proceso:</strong> ${d.idProceso || 'N/A'}</li>
                    <li><strong>Estado:</strong> <span class="badge badge-${colorEstado}">${estadoTxt}</span></li>
                    <li><strong>Fecha creación:</strong> ${d.fecha_creacion || 'N/A'}</li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="info-box">
                <span class="info-box-icon bg-success"><i class="fas fa-building"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Organización</span>
                  <ul class="list-unstyled mt-2">
                    <li><strong>Gerencia:</strong> ${d.gerencia || 'N/A'}</li>
                    <li><strong>Dependencia:</strong> ${d.dependencia || 'N/A'}</li>
                    <li><strong>Unidad:</strong> ${d.unidad || 'N/A'}</li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-6">
              <div class="info-box">
                <span class="info-box-icon bg-warning"><i class="fas fa-briefcase"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Descripción del trabajo</span>
                  <ul class="list-unstyled mt-2">
                    <li><strong>Profesión:</strong> ${d.profesion || 'N/A'}</li>
                    <li><strong>Objeto:</strong> ${d.objeto || 'N/A'}</li>
                    <li><strong>Alcance:</strong> ${d.alcance || 'N/A'}</li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fas fa-dollar-sign"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Información financiera</span>
                  <ul class="list-unstyled mt-2">
                    <li><strong>Honorarios:</strong> $${formatNumber(d.honorarios)}</li>
                    <li><strong>Presupuesto:</strong> $${formatNumber(d.presupuesto)}</li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-12">
              <div class="info-box">
                <span class="info-box-icon bg-secondary"><i class="fas fa-user"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Información del PS</span>
                  <ul class="list-unstyled mt-2">
                    <li><strong>Nombre:</strong> ${d.ps || 'N/A'}</li>
                    <li><strong>Cédula:</strong> ${d.cedula || 'N/A'}</li>
                    <li><strong>Correo:</strong> ${d.correo || 'N/A'}</li>
                    <li><strong>Teléfono:</strong> ${d.telefono || 'N/A'}</li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        `;
        
        $('#detalleContenido').html(html);
        $('#modalDetalle').modal('show');
      } else {
        toastr.error(r.mensajeError || 'No fue posible obtener el detalle');
      }
    });
  }
</script>

<?php require('views/footer.php'); ?>