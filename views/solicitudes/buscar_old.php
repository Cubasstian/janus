<?php require('views/header.php');?>

<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6"><h1>Solicitudes - Buscar</h1></div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="main/inicio/">Inicio</a></li>
            <li class="breadcrumb-item active">Buscar</li>
          </ol>
        </div>
      </div>
    </div>
  </section>
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col">
          <div class="card card-outline card-success">
            <div class="card-header">
              <form id="formBuscar">
                <div class="form-row align-items-end">
                  <div class="form-group col-md-3">
                    <label for="criterio">Criterio</label>
                    <select class="form-control" id="criterio" name="criterio" required>
                      <option value="todas">Todas</option>
                      <option value="area">Mi área</option>
                      <option value="id">Por ID</option>
                    </select>
                  </div>
                  <div class="form-group col-md-2" id="grupoId" style="display:none;">
                    <label for="idSolicitud">ID Solicitud</label>
                    <input type="number" class="form-control" id="idSolicitud" name="id" min="1" placeholder="Ej: 123">
                  </div>
                  <div class="form-group col-md-3">
                    <label for="estado">Estado (opcional)</label>
                    <select class="form-control" id="estado" name="estado">
                      <option value="">Todos</option>
                      <!-- opciones se llenan en init() desde la variable global 'estados' -->
                    </select>
                  </div>
                  <div class="form-group col-md-2">
                    <button type="submit" class="btn btn-success btn-block" id="btnBuscar">Buscar</button>
                  </div>
                </div>
              </form>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table id="tablaSolicitudes" class="table table-bordered table-striped">
                  <thead>
                    <tr class="text-center">
                      <th>ID</th>
                      <th>Estado</th>
                      <th>Gerencia</th>
                      <th>Dependencia</th>
                      <th>Unidad</th>
                      <th>Profesión</th>
                      <th>Honorarios</th>
                      <th>Presupuesto</th>
                      <th>ID Proceso</th>
                      <th>PS</th>
                      <th>Cédula</th>
                      <th>Teléfono</th>
                      <th>Días</th>
                      <th>Opciones</th>
                    </tr>
                  </thead>
                  <tbody id="contenidoSolicitudes"></tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Modal Detalle -->
  <div class="modal fade" id="modalDetalle" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Detalle de solicitud</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div id="detalleContenido"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

</div>

<?php require('views/footer.php');?>

<script type="text/javascript">
  var dt
  function init(){
    // Llenar select de estados usando variable global 'estados' definida en footer
    if (typeof estados !== 'undefined' && Array.isArray(estados)) {
      for (var i = 1; i < estados.length; i++) {
        if (estados[i]) {
          $('#estado').append(`<option value="${i}">${estados[i]}</option>`)
        }
      }
    }

    // Mostrar u ocultar campo ID según criterio
    $('#criterio').on('change', function(){
      if($(this).val() === 'id'){
        $('#grupoId').show()
        $('#idSolicitud').attr('required', true)
      }else{
        $('#grupoId').hide()
        $('#idSolicitud').removeAttr('required')
      }
    })

    // Buscar
    $('#formBuscar').on('submit', function(e){
      e.preventDefault()
      $('#btnBuscar').prop('disabled', true)
      buscarSolicitudes(function(){
        $('#btnBuscar').prop('disabled', false)
      })
    })
    // Ejecutar búsqueda inicial por defecto
    $('#formBuscar').trigger('submit')
  }

  function buscarSolicitudes(callback){
    var criterio = $('#criterio').val()
    var id = $('#idSolicitud').val()
    var estado = $('#estado').val()
    var datos = { criterio: criterio }
    if(criterio === 'id'){
      if(!id){ toastr.warning('Ingrese un ID de solicitud'); callback && callback(); return }
      datos.id = parseInt(id,10)
    }
    if(estado){ datos.estado = parseInt(estado,10) }

    enviarPeticion('solicitudes', 'getSolicitudes', datos, function(r){
      if(r.ejecuto === true){
        renderTabla(r.data || [])
      }else{
        toastr.error(r.mensajeError || 'Error al consultar solicitudes')
      }
      if(callback) callback()
    })
  }

  function renderTabla(registros){
    var cuerpo = ''
    registros.forEach(function(x){
      var estadoTxt = (typeof estados !== 'undefined' && estados[x.estado]) ? estados[x.estado] : (x.estado || '')
      cuerpo += `<tr>
        <td class="text-center">${x.id}</td>
        <td class="text-center"><span class="badge badge-${(colores && colores[x.estado])?colores[x.estado]:'secondary'}">${estadoTxt}</span></td>
        <td>${x.gerencia}</td>
        <td>${x.dependencia || ''}</td>
        <td>${x.unidad || ''}</td>
        <td>${x.profesion || ''}</td>
        <td class="text-right">${$.number(x.honorarios || 0, 0, ',', '.')}</td>
        <td class="text-right">${$.number(x.presupuesto || 0, 0, ',', '.')}</td>
        <td class="text-center">${x.idProceso}</td>
        <td>${x.ps || ''}</td>
        <td>${x.cedula || ''}</td>
        <td>${x.telefono || ''}</td>
        <td class="text-center">${x.tiempo || 0}</td>
        <td class="text-center">
          <button type="button" class="btn btn-default" title="Ver detalle" onClick="mostrarDetalle(${x.id})">
            <i class="fas fa-eye"></i>
          </button>
        </td>
      </tr>`
    })

    if(dt){
      dt.destroy()
    }
    $('#contenidoSolicitudes').html(cuerpo)
    dt = $('#tablaSolicitudes').DataTable({
      lengthMenu: [50, 100, 200],
      pageLength: 50,
      language:{
        decimal: "",
        emptyTable: "Sin datos para mostrar",
        info: "Mostrando _START_ al _END_ de _TOTAL_ registros",
        infoEmpty: "Mostrando 0 al 0 de 0 registros",
        infoFiltered: "(Filtrado de _MAX_ total registros)",
        lengthMenu: "Mostrar _MENU_ registros",
        loadingRecords: "Cargando...",
        processing: "Procesando...",
        search: "Buscar:",
        zeroRecords: "Ningún registro encontrado",
        paginate: { first: "Primero", last: "Último", next: "Sig", previous: "Ant" }
      }
    })
  }

  function mostrarDetalle(id){
    enviarPeticion('solicitudes', 'getSolicitudAll', {criterio:'id', valor:id}, function(r){
      if(r.ejecuto === true && r.data && r.data.length){
        var d = r.data[0]
        var estadoTxt = (typeof estados !== 'undefined' && estados[d.estado]) ? estados[d.estado] : (d.estado || '')
        var html = `
          <div class="row">
            <div class="col-md-6">
              <ul class="list-unstyled">
                <li><b>ID solicitud:</b> ${d.id}</li>
                <li><b>Gerencia:</b> ${d.gerencia}</li>
                <li><b>Dependencia:</b> ${d.dependencia || ''}</li>
                <li><b>Unidad:</b> ${d.unidad || ''}</li>
                <li><b>Profesión:</b> ${d.profesion || ''}</li>
                <li><b>Objeto:</b> ${d.objeto || ''}</li>
                <li><b>Alcance:</b> ${d.alcance || ''}</li>
              </ul>
            </div>
            <div class="col-md-6">
              <ul class="list-unstyled">
                <li><b>Honorarios:</b> ${$.number(d.honorarios || 0, 0, ',', '.')}</li>
                <li><b>Presupuesto:</b> ${$.number(d.presupuesto || 0, 0, ',', '.')}</li>
                <li><b>PS:</b> ${d.ps || ''} (CC ${d.cedula || ''})</li>
                <li><b>Correo:</b> ${d.correo || ''}</li>
                <li><b>Teléfono:</b> ${d.telefono || ''}</li>
                <li><b>Estado:</b> ${estadoTxt}</li>
                <li><b>Fecha creación:</b> ${d.fecha_creacion || ''}</li>
              </ul>
            </div>
          </div>
        `
        $('#detalleContenido').html(html)
        $('#modalDetalle').modal('show')
      }else{
        toastr.error(r.mensajeError || 'No fue posible obtener el detalle')
      }
    })
  }
</script>
</body>
</html>

