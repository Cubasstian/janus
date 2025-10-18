<?php require('views/header.php');?>

<!-- CSS del Sistema de Tabla Estándar Janus -->
<link rel="stylesheet" href="dist/css/tabla-procesos-estandar.css">

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        <i class="fas fa-sort-numeric-up text-primary mr-2"></i>
                        Numerar Contrato
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="main/inicio/">Inicio</a></li>
                        <li class="breadcrumb-item active">Numerar Contrato</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-dark text-white">
                            <h3 class="card-title">
                                <i class="fas fa-sort-numeric-up"></i> Numeración de Contratos
                            </h3>
                            <p class="text-sm mb-0">Procesos en estado 9 pendientes de numeración de contrato.</p>
                        </div>
                
                        <div class="card-body">
                            <!-- PANEL DE FILTROS ESTÁNDAR -->
                            <div class="filter-section-standard">
                                <div class="row align-items-end">
                                    <!-- Búsqueda General -->
                                    <div class="col-md-4">
                                        <label class="form-label-standard">Búsqueda General</label>
                                        <input type="text" 
                                               id="filtro-busqueda-estandar" 
                                               class="form-control form-control-standard" 
                                               placeholder="Buscar por ID, gerencia, contratista...">
                                    </div>
                                    
                                    <!-- Filtro por Gerencia -->
                                    <div class="col-md-3">
                                        <label class="form-label-standard">Gerencia</label>
                                        <select id="filtro-gerencia-estandar" 
                                                class="form-control form-control-standard">
                                            <option value="">Todas las gerencias</option>
                                        </select>
                                    </div>
                                    
                                    <!-- Botón Limpiar -->
                                    <div class="col-md-2">
                                        <button type="button" 
                                                id="btn-limpiar-filtros-estandar" 
                                                class="btn btn-filter-clear-standard w-100">
                                            <i class="fas fa-eraser"></i> Limpiar
                                        </button>
                                    </div>
                                    
                                    <!-- Contador -->
                                    <div class="col-md-3 text-right">
                                        <span class="form-label-standard d-block">Registros Encontrados</span>
                                        <span id="contador-registros-estandar" class="badge badge-count-standard">0</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- TABLA ESTÁNDAR -->
                            <div class="table-responsive">
                                <table id="tabla-procesos-estandar" class="table table-standard table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID Proceso</th>
                                            <th>Gerencia</th>
                                            <th>Contratista</th>
                                            <th>Cédula</th>
                                            <th>Fecha Creación</th>
                                            <th>Días Transcurridos</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Los datos se cargarán dinámicamente -->
                                    </tbody>
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
$(document).ready(function() {
    console.log('DOM listo, inicializando numerar contrato...');
    
    // Cargar datos inmediatamente
    cargarDatosManualmente();

    // Función optimizada para cargar datos
    function cargarDatosManualmente() {
        console.log('Cargando datos de numerar contrato...');
        
        // Mostrar indicador de carga
        if ($('#tabla-procesos-estandar tbody').length) {
            $('#tabla-procesos-estandar tbody').html('<tr><td colspan="8" class="text-center"><i class="fas fa-spinner fa-spin"></i> Cargando datos...</td></tr>');
        }
        
        // Cargar datos filtrados por estado 9 (numerar contrato)
        enviarPeticion('solicitudes', 'getSolicitudes', {criterio:'porEstado', estado:9}, function(respuesta) {
            console.log('Respuesta estado 9:', respuesta);
            
            if (respuesta.ejecuto && respuesta.data && respuesta.data.length > 0) {
                console.log('Datos encontrados:', respuesta.data.length);
                renderizarDatos(respuesta.data);
            } else {
                console.log('Sin datos con estado 9, intentando filtro cliente...');
                cargarTodosYFiltrar();
            }
        });
    }
    
    // Función de respaldo para filtrar en cliente
    function cargarTodosYFiltrar() {
        enviarPeticion('solicitudes', 'getSolicitudes', {criterio:'todas'}, function(respuesta) {
            if (respuesta.ejecuto && respuesta.data && respuesta.data.length > 0) {
                const datosEstado9 = respuesta.data.filter(item => item.estado == 9 || item.estado === '9');
                
                if (datosEstado9.length > 0) {
                    renderizarDatos(datosEstado9);
                } else {
                    mostrarMensajeSinDatos('No hay procesos pendientes de numeración (estado 9)');
                }
            } else {
                mostrarMensajeSinDatos('No hay procesos disponibles');
            }
        });
    }
    
    function mostrarMensajeSinDatos(mensaje) {
        $('#tabla-procesos-estandar tbody').html(`<tr><td colspan="8" class="text-center text-muted">${mensaje}</td></tr>`);
        $('#contador-registros-estandar').text('0');
    }
    
    function renderizarDatos(datos) {
        console.log('Renderizando', datos.length, 'registros');
        
        // Verificar que existe la tabla
        if (!$('#tabla-procesos-estandar').length) {
            console.error('Tabla no encontrada');
            return;
        }
        
        // Destruir DataTable si existe
        if ($.fn.DataTable.isDataTable('#tabla-procesos-estandar')) {
            $('#tabla-procesos-estandar').DataTable().destroy();
        }
        
        let html = '';
        datos.forEach(function(item, index) {
            const procesoId = item.idProceso || item.id;
            const gerencia = item.gerencia || 'Sin asignar';
            const contratista = item.contratista_nombre || item.ps || 'Sin asignar';
            const cedula = item.contratista_cedula || item.cedula || 'Sin cédula';
            const fechaCreacion = item.fecha_creacion || 'Sin fecha';
            const tiempo = item.tiempo || 0;
            
            // Color del badge según días
            let colorBadge = 'success';
            if (tiempo > 7) colorBadge = 'danger';
            else if (tiempo > 3) colorBadge = 'warning';
            
            html += `
                <tr>
                    <td class="text-center font-weight-bold">#${procesoId}</td>
                    <td>${gerencia}</td>
                    <td>${contratista}</td>
                    <td class="text-center">${cedula}</td>
                    <td class="text-center">${fechaCreacion}</td>
                    <td class="text-center">
                        <span class="badge badge-${colorBadge}">${tiempo} días</span>
                    </td>
                    <td class="text-center">
                        <span class="badge badge-warning">Pendiente Numeración</span>
                    </td>
                    <td class="text-center">
                        <button class="btn btn-primary btn-sm" onclick="abrirModalNumerar(${item.id || procesoId}, '${contratista}')">
                            <i class="fas fa-sort-numeric-up"></i> Numerar
                        </button>
                    </td>
                </tr>
            `;
        });
        
        $('#tabla-procesos-estandar tbody').html(html);
        $('#contador-registros-estandar').text(datos.length);
        
        // Reinicializar DataTable
        $('#tabla-procesos-estandar').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
            },
            "pageLength": 25,
            "order": [[ 0, "desc" ]],
            "processing": false,
            "deferRender": true,
            "stateSave": false
        });
        
        console.log('Tabla poblada con', datos.length, 'registros');
    }

    // Función para abrir modal de numeración
    function abrirModalNumerar(idProceso, nombreContratista) {
        Swal.fire({
            title: 'Numerar Contrato',
            html: `
                <div class="text-left">
                    <div class="form-group">
                        <label for="numero_contrato"><strong>Número de Contrato:</strong></label>
                        <input type="text" 
                               id="numero_contrato" 
                               class="form-control" 
                               placeholder="Ej: 2025-001" 
                               required>
                        <small class="text-muted">Ingrese el número único del contrato</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="fecha_numeracion"><strong>Fecha de Numeración:</strong></label>
                        <input type="date" 
                               id="fecha_numeracion" 
                               class="form-control" 
                               value="${new Date().toISOString().split('T')[0]}" 
                               required>
                    </div>
                    
                    <div class="form-group">
                        <label for="observaciones_numeracion"><strong>Observaciones (opcional):</strong></label>
                        <textarea id="observaciones_numeracion" 
                                  class="form-control" 
                                  rows="3" 
                                  placeholder="Observaciones adicionales sobre la numeración..."></textarea>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Proceso:</strong> #${idProceso}<br>
                        <strong>Contratista:</strong> ${nombreContratista}
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-save"></i> Asignar Número',
            cancelButtonText: '<i class="fas fa-times"></i> Cancelar',
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            width: '600px',
            preConfirm: () => {
                const numeroContrato = document.getElementById('numero_contrato').value;
                const fechaNumeracion = document.getElementById('fecha_numeracion').value;
                const observaciones = document.getElementById('observaciones_numeracion').value;
                
                if (!numeroContrato || !fechaNumeracion) {
                    Swal.showValidationMessage('Por favor complete los campos obligatorios');
                    return false;
                }
                
                return {
                    idProceso: idProceso,
                    numero_contrato: numeroContrato,
                    fecha_numeracion: fechaNumeracion,
                    observaciones_numeracion: observaciones,
                    accion: 'numerarContrato'
                };
            }
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                console.log('Datos para numerar:', result.value);
                
                enviarPeticion('procesos', 'updateProceso', result.value, function(r) {
                    if (r.ejecuto) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: `Contrato numerado correctamente: ${result.value.numero_contrato}`,
                            confirmButtonColor: '#28a745'
                        });
                        
                        // Recargar datos
                        cargarDatosManualmente();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: r.mensaje || 'No se pudo numerar el contrato',
                            confirmButtonColor: '#dc3545'
                        });
                    }
                });
            }
        });
    }

    // Hacer función global
    window.abrirModalNumerar = abrirModalNumerar;
});
</script>
</body>
</html>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card shadow-lg" style="border: 2px solid #000; border-radius: 12px;">
            <div class="card-header card-header-clean">
              <h3 class="card-title">
                <i class="fas fa-hashtag mr-2"></i>Asignar Número de Contrato
              </h3>
            </div>
            <div class="card-body form-modern">
              <form id="formNumerar">
                <div class="alert alert-warning py-1 px-2 d-none" id="alertPrereqNumerar"></div>
                <div class="form-group">
                  <label for="numero_contrato">Número de contrato</label>
                  <input type="text" class="form-control" id="numero_contrato" name="numero_contrato" required>
                </div>
                <div class="form-group">
                  <label for="fecha_numeracion">Fecha de numeración (opcional)</label>
                  <input type="date" class="form-control" id="fecha_numeracion" name="fecha_numeracion">
                </div>
                <button type="submit" class="btn btn-primary" id="btnNumerar" disabled>Guardar numeración</button>
                <small id="ayudaNumerar" class="form-text text-muted" style="display:none;">El número de contrato ya fue asignado. Formulario bloqueado.</small>
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
    $('#formBuscarProceso').on('submit', function(e){
      e.preventDefault()
      var idp = parseInt($('#idProceso').val(),10)
      if(!idp){ toastr.warning('Ingrese un ID de proceso válido'); return }
      enviarPeticion('procesos', 'getProcesos', {criterio:'id', id: idp}, function(r){
        if(r.ejecuto && r.data && r.data.length){
          var p = r.data[0]
          idProcesoSel = p.id
          $('#lblProceso').text(p.id)
          $('#lblGerencia').text(p.gerencia || '')
          var estadoTxt = (typeof estados !== 'undefined' && estados[p.estado]) ? estados[p.estado] : p.estado
            $('#lblEstado').text(estadoTxt || '')
          enviarPeticion('procesos','getProcesoDetalle',{id:idProcesoSel}, function(det){
            if(det.ejecuto && det.data && det.data.length){
              detalleProceso = det.data[0]
              if(detalleProceso.numero_contrato){
                $('#numero_contrato').val(detalleProceso.numero_contrato)
                if(detalleProceso.fecha_numeracion){ $('#fecha_numeracion').val(detalleProceso.fecha_numeracion) }
                $('#formNumerar :input').prop('disabled', true)
                $('#ayudaNumerar').show()
              }else{ $('#btnNumerar').prop('disabled', false) }
            }else{ $('#btnNumerar').prop('disabled', false) }
            $('#infoProceso').show()
            verificarOverrideGeneric(idProcesoSel, '#badgeOverride')
          })
        }else{
          toastr.error('No se encontró el proceso')
          $('#infoProceso').hide(); $('#btnNumerar').prop('disabled', true); $('#badgeOverride').addClass('d-none')
        }
      })
    })
    $('#formNumerar').on('submit', function(e){
      e.preventDefault()
      if(!idProcesoSel){ toastr.warning('Primero busque y seleccione un proceso'); return }
      var datos = parsearFormulario($(this))
      datos.idProceso = idProcesoSel
      enviarPeticion('procesos','checkPrerequisitos',{accion:'numerar', idProceso:idProcesoSel}, function(pr){
        if(pr.ejecuto && (pr.faltantes?.length || pr.pendientes?.length)){
          var msg=[]; if(pr.faltantes.length) msg.push('Faltan: '+pr.faltantes.join(', ')); if(pr.pendientes.length) msg.push('Pendientes: '+pr.pendientes.join(', '));
          $('#alertPrereqNumerar').removeClass('d-none').html(msg.join('<br>'));
          return;
        }
        enviarPeticion('procesos', 'numerar', datos, function(r){
          if(r.ejecuto){
            Swal.fire({ icon:'success', title:'Numeración guardada', text: 'Se actualizó el proceso y avanzó el estado.' })
            $('#formNumerar :input').prop('disabled', true)
            $('#ayudaNumerar').show()
            verificarOverrideGeneric(idProcesoSel, '#badgeOverride', {force:true})
          }else{ mostrarError(r) }
        })
      })
    })
  }
</script>
</body>
</html>

