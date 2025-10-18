<?php require('views/header.php');?>

<div class="content-wrapper">
    <section class="content" style="padding: 30px 20px;">
        <div class="container-fluid">
            <!-- Documentos Generales -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card card-kit shadow-sm">
                        <div class="card-header card-header-clean d-flex justify-content-between align-items-center">
                            <h3 class="card-title mb-0">
                                <i class="fas fa-file-alt mr-2" style="color: #ff6c00;"></i>
                                <span style="font-weight: 600;">Documentos Generales</span>
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table data-table table-hover mb-0">
                                    <thead style="background: #f8f9fa;">
                                        <tr>
                                            <th style="border-top: none;"><i class="fas fa-tag mr-1"></i> Nombre</th>
                                            <th style="border-top: none; text-align: center;"><i class="fas fa-flag mr-1"></i> Estado</th>
                                            <th style="border-top: none; text-align: center;"><i class="fas fa-history mr-1"></i> Revisiones</th>
                                            <th style="border-top: none;"><i class="fas fa-comment-alt mr-1"></i> Motivo</th>
                                            <th style="border-top: none; text-align: center;"><i class="fas fa-cogs mr-1"></i> Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="generales">
                                        <tr class="skeleton-loader">
                                            <td colspan="5" class="text-center py-4">
                                                <div class="spinner-border text-success" role="status">
                                                    <span class="sr-only">Cargando...</span>
                                                </div>
                                                <p class="mt-2 mb-0 text-muted">Cargando documentos...</p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Procesos y Documentos del Proceso -->
            <div class="row">
                <div class="col-lg-5 mb-4">
                    <div class="card card-kit shadow-sm h-100">
                        <div class="card-header card-header-clean">
                            <h3 class="card-title mb-0">
                                <i class="fas fa-tasks mr-2" style="color: #ff6c00;"></i>
                                <span style="font-weight: 600;">Mis Procesos</span>
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                                <table class="table data-table table-hover mb-0">
                                    <thead style="background: #f8f9fa; position: sticky; top: 0; z-index: 10;">
                                        <tr>
                                            <th style="border-top: none;"><i class="fas fa-hashtag mr-1"></i> ID</th>
                                            <th style="border-top: none;"><i class="fas fa-building mr-1"></i> Gerencia</th>
                                            <th style="border-top: none;"><i class="fas fa-calendar mr-1"></i> Fecha</th>
                                            <th style="border-top: none; text-align: center;"><i class="fas fa-info-circle mr-1"></i> Estado</th>
                                            <th style="border-top: none; text-align: center;"><i class="fas fa-eye mr-1"></i> Ver</th>
                                        </tr>
                                    </thead>
                                    <tbody id="contratos">
                                        <tr class="skeleton-loader">
                                            <td colspan="5" class="text-center py-4">
                                                <div class="spinner-border text-success" role="status">
                                                    <span class="sr-only">Cargando...</span>
                                                </div>
                                                <p class="mt-2 mb-0 text-muted">Cargando procesos...</p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7 mb-4">
                    <div class="card card-kit shadow-sm h-100">
                        <div class="card-header card-header-clean">
                            <h3 class="card-title mb-0" id="tituloProceso">
                                <i class="fas fa-folder-open mr-2" style="color: #ff6c00;"></i>
                                <span style="font-weight: 600;">Documentos del Proceso</span>
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                                <table class="table data-table table-hover mb-0">
                                    <thead style="background: #f8f9fa; position: sticky; top: 0; z-index: 10;">
                                        <tr>
                                            <th style="border-top: none;"><i class="fas fa-file mr-1"></i> Documento</th>
                                            <th style="border-top: none; text-align: center;"><i class="fas fa-flag mr-1"></i> Estado</th>
                                            <th style="border-top: none; text-align: center;"><i class="fas fa-redo mr-1"></i> Rev.</th>
                                            <th style="border-top: none;"><i class="fas fa-comment mr-1"></i> Observaciones</th>
                                            <th style="border-top: none; text-align: center;"><i class="fas fa-tools mr-1"></i> Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="documentosProceso">
                                        <tr>
                                            <td colspan="5" class="text-center py-5 text-muted">
                                                <i class="fas fa-mouse-pointer fa-3x mb-3" style="color: #ddd;"></i>
                                                <p class="mb-0">Selecciona un proceso para ver sus documentos</p>
                                            </td>
                                        </tr>
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

<!-- Modal para visualizar documentos PDF -->
<div class="modal fade" id="modalVisorPDF" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style="max-width: 95vw; width: 95vw; height: 95vh; margin: 2.5vh auto;">
        <div class="modal-content" style="height: 100%; border-radius: 12px; overflow: hidden; box-shadow: 0 10px 40px rgba(0,0,0,0.2);">
            <div class="modal-header" style="background: linear-gradient(135deg, #518711 0%, #28a745 100%); border: none; padding: 20px 30px;">
                <h5 class="modal-title" style="color: #fff; font-weight: 600; font-size: 1.25rem;">
                    <i class="far fa-file-pdf mr-2" style="color: #ff6c00;"></i>
                    Visualizador de Documentos
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #fff; opacity: 1; text-shadow: none;">
                    <span aria-hidden="true" style="font-size: 2rem;">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding: 0; height: calc(95vh - 140px); overflow: hidden; background: #f5f5f5;">
                <iframe id="pdfViewer" style="width: 100%; height: 100%; border: none;"></iframe>
            </div>
            <div class="modal-footer" style="border-top: 2px solid #e0e0e0; padding: 15px 30px; background: #fafafa;">
                <button type="button" class="btn btn-kit btn-kit-outline-secondary" data-dismiss="modal" style="padding: 8px 24px; font-weight: 500;">
                    <i class="fas fa-times mr-1"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<?php require('views/footer.php');?>

<style>
/* Bordes verdes oscuro en todas las cards */
.card-kit {
    border: 2px solid #295c1e !important;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08) !important;
    transition: border-color 0.3s, box-shadow 0.3s;
}
.card-kit:hover {
    border-color: #43b94a !important;
    box-shadow: 0 8px 24px rgba(67,185,74,0.12) !important;
}
/* Animaciones y transiciones suaves */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.card-kit {
    animation: fadeIn 0.4s ease-out;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.card-kit:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.1) !important;
}

/* Mejora de tablas */
.data-table {
    font-size: 0.9rem;
}

.data-table thead th {
    font-weight: 600;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #555;
    padding: 15px 12px;
    white-space: nowrap;
}

.data-table tbody tr {
    transition: all 0.2s ease;
    cursor: pointer;
}

.data-table tbody tr:hover {
    background-color: #f8fdf9 !important;
    box-shadow: 0 2px 8px rgba(40, 167, 69, 0.1);
    border-left: 3px solid #28a745;
}

.data-table tbody td {
    padding: 14px 12px;
    vertical-align: middle;
    border-bottom: 1px solid #f0f0f0;
}

/* Evitar scroll horizontal */
.table-responsive {
    overflow-x: auto;
    overflow-y: visible;
}

.card-body {
    overflow: hidden;
}

/* Badges modernos con animación */
.badge {
    padding: 6px 12px;
    font-weight: 500;
    border-radius: 20px;
    font-size: 0.75rem;
    letter-spacing: 0.3px;
    transition: all 0.3s ease;
}

.badge:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.bg-success { background: linear-gradient(135deg, #28a745, #20c997) !important; }
.bg-warning { background: linear-gradient(135deg, #ffc107, #ff9800) !important; }
.bg-danger { background: linear-gradient(135deg, #dc3545, #c82333) !important; }
.bg-secondary { background: linear-gradient(135deg, #6c757d, #5a6268) !important; }
.bg-info { background: linear-gradient(135deg, #17a2b8, #138496) !important; }

/* Botones modernos */
.btn-kit {
    border-radius: 8px;
    font-weight: 500;
    padding: 8px 16px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 2px 4px rgba(0,0,0,0.08);
}

.btn-kit:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.btn-kit:active {
    transform: translateY(0);
}

.btn-kit-outline-info {
    border: 2px solid #17a2b8;
    color: #17a2b8;
}

.btn-kit-outline-info:hover {
    background: linear-gradient(135deg, #17a2b8, #138496);
    border-color: #17a2b8;
    color: white;
}

.btn-kit-outline-success {
    border: 2px solid #28a745;
    color: #28a745;
}

.btn-kit-outline-success:hover {
    background: linear-gradient(135deg, #28a745, #20c997);
    border-color: #28a745;
    color: white;
}

.btn-kit-outline-danger {
    border: 2px solid #dc3545;
    color: #dc3545;
}

.btn-kit-outline-danger:hover {
    background: linear-gradient(135deg, #dc3545, #c82333);
    border-color: #dc3545;
    color: white;
}

/* Skeleton loader */
.skeleton-loader {
    animation: pulse 1.5s ease-in-out infinite;
}

/* Scroll suave para tablas */
.table-responsive::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

.table-responsive::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.table-responsive::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #28a745, #20c997);
    border-radius: 10px;
}

.table-responsive::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #20c997, #28a745);
}

/* Shadow suave para cards */
.shadow-sm {
    box-shadow: 0 2px 8px rgba(0,0,0,0.08) !important;
}

/* Spinner personalizado */
.spinner-border {
    animation: spinner-border 0.75s linear infinite;
}

/* Input file styling */
.btn-file {
    position: relative;
    overflow: hidden;
    display: inline-block;
    cursor: pointer;
}

.btn-file input[type=file] {
    position: absolute;
    top: 0;
    right: 0;
    min-width: 100%;
    min-height: 100%;
    font-size: 100px;
    text-align: right;
    filter: alpha(opacity=0);
    opacity: 0;
    outline: none;
    background: white;
    cursor: pointer;
    display: block;
}

/* Tooltips mejorados */
[title] {
    position: relative;
}

/* Iconos con pulso */
@keyframes iconPulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

.fa-file-alt, .fa-tasks, .fa-folder-open {
    animation: iconPulse 2s ease-in-out infinite;
}

/* Estados responsivos */
@media (max-width: 768px) {
    .card-kit {
        margin-bottom: 15px;
    }
    
    .data-table {
        font-size: 0.8rem;
    }
    
    .data-table thead th {
        font-size: 0.75rem;
        padding: 10px 8px;
    }
}

/* Efecto de carga */
.loading-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255,255,255,0.9);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    backdrop-filter: blur(2px);
}
    min-height: 100%;
    font-size: 100px;
    text-align: right;
    filter: alpha(opacity=0);
    opacity: 0;
    outline: none;
    background: white;
    cursor: inherit;
    display: block;
}

.btn-action {
    border-radius: 6px;
    font-weight: 500;
    transition: all 0.2s ease;
    border-width: 1.5px;
    margin: 0 2px;
}

.btn-action:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}
</style>

<script type="text/javascript">
    var user = 0
    // Variable para prevenir múltiples inicializaciones
    var isInitialized = false;
    
    // Definir estados y colores de documentos
    const estadoDocumentos = {
        0: 'No cargado',
        1: 'Cargado',
        2: 'Aprobado',
        3: 'Rechazado'
    };
    
    const coloresEstadoDocumentos = {
        0: 'secondary',
        1: 'warning', 
        2: 'success',
        3: 'danger'
    };
    
    function init(info){
        // Prevenir múltiples ejecuciones - verificación más temprana
        if (isInitialized) {
            return;
        }
        
        // Marcar inmediatamente para prevenir ejecuciones concurrentes
        isInitialized = true;
        
        // Verificar que enviarPeticion existe
        if (typeof enviarPeticion !== 'function') {
            isInitialized = false; // Reset si falla
            setTimeout(function() {
                init(info); // Reintentar después de un delay
            }, 100);
            return;
        }
        
        // Obtener los datos de sesión frescos
        var ajaxRequest = enviarPeticion('helpers', 'getSession', {1:1}, function(sessionData) {
            
            // Verificar la respuesta de la sesión
            if (!sessionData || !sessionData.ejecuto) {
                console.error('Error al obtener sesión:', sessionData);
                toastr.error('Error al verificar la sesión');
                setTimeout(() => {
                    window.location.href = 'main/login/';
                }, 2000);
                return;
            }
            
            // Verificar si hay datos de usuario
            if (!sessionData.data || sessionData.data.length === 0) {
                toastr.error('Sesión expirada');
                isInitialized = false; // Reset para permitir reintento
                setTimeout(() => {
                    window.location.href = 'main/login/';
                }, 2000);
                return;
            }
            
            // Obtener usuario de la sesión
            const usuario = sessionData.data.usuario;
            if (!usuario) {
                toastr.error('Error: No se encontró información del usuario');
                isInitialized = false; // Reset para permitir reintento
                setTimeout(() => {
                    window.location.href = 'main/login/';
                }, 2000);
                return;
            }
            
            // Verificar que es un PS
            if (usuario.rol !== 'PS') {
                toastr.warning(`Acceso denegado: Solo usuarios PS pueden acceder. Su rol actual es: ${usuario.rol}`);
                isInitialized = false; // Reset para permitir reintento
                setTimeout(() => {
                    window.location.href = 'main/inicio/';
                }, 3000);
                return;
            }
            
            // Todo está bien, inicializar la aplicación
            user = usuario.id;
            console.log('Usuario PS inicializado correctamente:', user, 'Nombre:', usuario.nombre);
            
            // Mostrar mensaje de bienvenida SOLO si viene del login
            if(sessionStorage.getItem('mostrarBienvenida') === 'true'){
                const nombreUsuario = sessionStorage.getItem('nombreUsuario') || usuario.nombre;
                toastr.success(`¡Bienvenido ${nombreUsuario}!`);
                // Limpiar flag para que no se muestre en recargas
                sessionStorage.removeItem('mostrarBienvenida');
                sessionStorage.removeItem('nombreUsuario');
            }
            
            // Cargar los datos - asegurar que user está definido
            if (user && user > 0) {
                console.log('Iniciando carga de datos con user:', user);
                mostrarDocumentos(1,1,'generales');
                
                // Añadir un pequeño delay para asegurar que todo esté listo
                setTimeout(() => {
                    console.log('Ejecutando cargarProcesos() con user:', user);
                    cargarProcesos();
                }, 100);
            } else {
                console.error('User no válido para cargar procesos:', user);
                toastr.error('Error: ID de usuario no válido');
            }

        });
        
        // Solo agregar .fail() si el objeto Ajax existe
        if (ajaxRequest && typeof ajaxRequest.fail === 'function') {
            ajaxRequest.fail(function(xhr, status, error) {
                console.error('Error AJAX al obtener sesión:', error, xhr);
                toastr.error('Error de conexión al verificar la sesión');
                setTimeout(() => {
                    window.location.href = 'main/login/';
                }, 2000);
            });
        }
    }    // Función para ejecutar después de que la página esté completamente cargada
    $(document).ready(function() {
        console.log('Document ready - verificando si user está definido:', user);
        
        // Si por alguna razón user no está definido después de 2 segundos, mostrar error
        setTimeout(() => {
            if (!user || user <= 0) {
                console.log('User aún no definido después de 2s, puede ser problema de inicialización');
                $('#contratos').html('<tr><td colspan="5" class="text-center text-warning">Cargando procesos...<br><small>Si no aparecen, use el botón "Recargar"</small></td></tr>');
            }
        }, 2000);
    });
    
    function cargarProcesos() {
        // Validar que user esté definido
        if (!user || user <= 0) {
            toastr.error('Error: Usuario no válido para cargar procesos');
            $('#contratos').html('<tr><td colspan="5" class="text-center text-danger">Error: Usuario no válido</td></tr>');
            return;
        }
        
        var cargarProcesosRequest = enviarPeticion('procesos', 'getProcesos', {criterio: 'ps', userId: user}, function(r){
            if (r && r.ejecuto === true) {
                let fila = '';
                
                // Verificar si hay datos
                if (r.data && Array.isArray(r.data) && r.data.length > 0) {
                    r.data.forEach((registro) => {
                        fila += `<tr>
                                    <td>${registro.id || 'N/A'}</td>
                                    <td>${registro.gerencia || 'Sin gerencia'}</td>
                                    <td>${registro.fc || 'Sin fecha'}</td>
                                    <td>${registro.estado || 'Sin estado'}</td>
                                    <td>
                                        <button class="btn btn-kit btn-kit-outline-success btn-sm" onClick="mostrarDocumentos(0,${registro.id},'documentosProceso')" title="Ver documentación">
                                            Ver documentación <i class="far fa-arrow-alt-circle-right"></i>
                                        </button>
                                    </td>
                                </tr>`;
                    });
                    
                    console.log(`Procesos cargados: ${r.data.length}`);
                } else {
                    fila = '<tr><td colspan="5" class="text-center text-muted">No hay procesos disponibles</td></tr>';
                }
                
                $('#contratos').html(fila);
                
            } else {
                toastr.error(r?.mensajeError || 'Error al cargar los procesos');
                $('#contratos').html('<tr><td colspan="5" class="text-center text-danger">Error al cargar procesos</td></tr>');
            }
            
        });
        
        // Manejo de errores de manera segura
        if (cargarProcesosRequest && typeof cargarProcesosRequest.fail === 'function') {
            cargarProcesosRequest.fail(function(xhr, status, error) {
                toastr.error('Error de conexión al cargar los procesos');
                $('#contratos').html('<tr><td colspan="5" class="text-center text-danger">Error de conexión</td></tr>');
            });
        }
        
    }

    function mostrarDocumentos(permanente, proceso, elemento){
        if (proceso) {
            $('#tituloProceso').text(`Documentos del proceso #${proceso}`)
        }
        
        var ajaxRequest = enviarPeticion('documentosTipo', 'select', {info:{is_permanente: permanente}}, function(r){
            if (!r.ejecuto) {
                toastr.error(r.mensajeError || 'Error al cargar los tipos de documentos');
                return;
            }
            
            let fila = ''
            if (r.data && r.data.length > 0) {
                r.data.map(registro => {
                    fila += `<tr>
                                <td>${registro.nombre}<br><small class="text-muted">${registro.inmersos || ''}</small></td>
                                <td id="estado_${registro.id}">
                                    <span class="badge bg-secondary">No cargado</span>
                                </td>
                                <td id="conteo_${registro.id}" class="text-center"></td>
                                <td id="obs_${registro.id}"></td>
                                <td>
                                    <div style="display: flex; gap: 5px;">
                                        <div style="width: 50px;" id="btnCargar_${registro.id}">
                                            <a class='btn btn-kit btn-kit-outline-success btn-sm btn-file' title="Cargar archivo">
                                                <i class='fas fa-upload'></i>
                                                <input type='file' onchange="cargarArchivo(this,${proceso},${registro.id})" accept=".pdf">
                                            </a>
                                        </div>
                                        <div style="display: flex; gap: 5px;" id="btnVer_${registro.id}"></div>
                                    </div>
                                </td>
                            </tr>`
                })
            } else {
                fila = '<tr><td colspan="5" class="text-center text-muted">No hay tipos de documentos configurados</td></tr>';
            }
            
            $(`#${elemento}`).html(fila)
            
            // Solo cargar documentos si hay datos y usuario válido
            if (user && r.data && r.data.length > 0) {
                cargarRegistrosDocumentos({criterio: 'generales', contratista: user, proceso: proceso})
            }
        });
        
        // Manejo de errores de manera segura
        if (ajaxRequest && typeof ajaxRequest.fail === 'function') {
            ajaxRequest.fail(function(xhr, status, error) {
                toastr.error('Error de conexión al cargar los tipos de documentos');
            });
        }
    }

    function cargarArchivo(input, proceso, tipo){
        // Verificar que las variables necesarias estén definidas
        if (!user || user <= 0) {
            toastr.error('Error: Usuario no válido para cargar archivos');
            return;
        }
        
        if (!proceso || proceso <= 0) {
            toastr.error('Error: Proceso no válido para cargar archivos');
            return;
        }
        
        if (!tipo || tipo <= 0) {
            toastr.error('Error: Tipo de documento no válido');
            return;
        }
        
        if(comprobarArchivo(input)){
            toastr.info('Subiendo archivo...');
            enviarPeticion('documentos', 'crear', {contratista: user, proceso: proceso, tipo: tipo}, function(r){
                if (r && r.ejecuto && r.insertId) {
                    cargarDocumento(input, r.insertId, function(res){
                        if(res.ejecuto == true){
                            //Esto en caso de que cargue el documento
                            enviarPeticion('documentos', 'update', {info: {estado: 1}, id: r.insertId}, function(y){
                                toastr.success(res.msg || 'Archivo cargado correctamente')
                                cargarRegistrosDocumentos({criterio: 'id', id: r.insertId})
                            })
                        }else{
                            //En caso de que no se deja como si no hubiera cargado
                            enviarPeticion('documentos', 'update', {info: {estado: 0}, id: r.insertId}, function(y){
                                toastr.error(res.msg || 'Error al cargar el archivo')
                                cargarRegistrosDocumentos({criterio: 'id', id: r.insertId})
                            })
                        }
                    })
                } else {
                    toastr.error(r?.mensajeError || 'Error al crear el registro de documento');
                }
            })
        }
    }

    function cargarRegistrosDocumentos(datos){
        try {
            const request = enviarPeticion('documentos', 'getDocumentos', datos, function(r){
                if (!r || !r.ejecuto) {
                    // No mostrar error aquí porque puede ser normal que no haya documentos
                    return;
                }
                
                if (r.data && r.data.length > 0) {
                    r.data.map(registro => {
                        const estadoText = estadoDocumentos[registro.estado] || 'Desconocido';
                        const estadoColor = coloresEstadoDocumentos[registro.estado] || 'secondary';
                        
                        $('#estado_'+registro.tipo).html(`<span class="badge bg-${estadoColor}">${estadoText}</span>`)
                        $('#conteo_'+registro.tipo).text(registro.conteo || '')
                        
                        if(registro.estado == 3){
                            $('#obs_'+registro.tipo).text(registro.observaciones || '')
                        }else{
                            $('#obs_'+registro.tipo).text('')
                        }                
                        
                        if(registro.estado == 0){
                            $('#btnVer_'+registro.tipo).html('')
                        }else{
                            const htmlBotones = `
                                <button type="button" class="btn btn-kit btn-kit-outline-info btn-sm" onclick="verDocumentoModal(${registro.id})" title="Ver documento">
                                    <i class="far fa-eye"></i>
                                </button>
                                <button type="button" class="btn btn-kit btn-kit-outline-danger btn-sm ml-1" onclick="eliminarDocumento(${registro.id}, ${registro.tipo})" title="Eliminar documento">
                                    <i class="far fa-trash-alt"></i>
                                </button>
                            `;
                            console.log('Generando botones para documento:', registro.id, 'tipo:', registro.tipo);
                            console.log('HTML botones:', htmlBotones);
                            $('#btnVer_'+registro.tipo).html(htmlBotones);
                        }
                        
                        if(registro.estado == 2){
                            $('#btnCargar_'+registro.tipo).html('<span class="text-success"><i class="fas fa-check"></i></span>')
                        }
                    })
                }
            });
            
            // Verificar si el request tiene el método fail antes de usarlo
            if (request && typeof request.fail === 'function') {
                request.fail(function(xhr, status, error) {
                    // Error silencioso para evitar spam de mensajes
                });
            }
        } catch (error) {
            // Error silencioso
        }
    }

    // Función para comprobar archivos
    function comprobarArchivo(input) {
        if (!input.files || input.files.length === 0) {
            toastr.error('Por favor seleccione un archivo');
            return false;
        }
        
        const file = input.files[0];
        const maxSize = 5 * 1024 * 1024; // 5MB
        
        if (file.size > maxSize) {
            toastr.error('El archivo es muy grande. Máximo 5MB permitido');
            input.value = '';
            return false;
        }
        
        const allowedTypes = ['application/pdf'];
        if (!allowedTypes.includes(file.type)) {
            toastr.error('Solo se permiten archivos PDF');
            input.value = '';
            return false;
        }
        
        return true;
    }

    // Función para cargar documento
    function cargarDocumento(input, documentoId, callback) {
        if (!input.files || input.files.length === 0) {
            callback({ejecuto: false, msg: 'No se seleccionó archivo'});
            return;
        }
        
        console.log('Iniciando carga de documento:', {
            documentoId: documentoId,
            fileName: input.files[0].name,
            fileSize: input.files[0].size,
            fileType: input.files[0].type
        });
        
        const formData = new FormData();
        formData.append('file', input.files[0]);
        formData.append('id', documentoId);
        
        $.ajax({
            url: 'controllers/archivos.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log('Respuesta cruda del servidor:', response);
                console.log('Tipo de respuesta:', typeof response);
                
                try {
                    let result;
                    if (typeof response === 'string') {
                        console.log('Parseando string JSON...');
                        result = JSON.parse(response);
                    } else {
                        console.log('Respuesta ya es objeto...');
                        result = response;
                    }
                    console.log('Resultado parseado:', result);
                    callback(result);
                } catch (e) {
                    console.error('Error al parsear respuesta:', e);
                    console.error('Respuesta que causó el error:', response);
                    
                    toastr.error('Error al procesar respuesta del servidor');
                    callback({
                        ejecuto: false, 
                        msg: 'Error al procesar respuesta'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Error AJAX:', {xhr, status, error, responseText: xhr.responseText});
                callback({ejecuto: false, msg: 'Error al subir el archivo: ' + error});
            }
        });
    }

    // Función para ver documento en modal
    function verDocumentoModal(documentoId) {
        if (!documentoId) {
            toastr.error('ID de documento no válido');
            return;
        }
        
        // Usar la API para obtener el documento
        enviarPeticion('archivos', 'getDocumento', {id: documentoId}, function(r) {
            if (r.ejecuto && r.file) {
                try {
                    // Convertir base64 a blob
                    const byteCharacters = atob(r.file);
                    const byteNumbers = new Array(byteCharacters.length);
                    for (let i = 0; i < byteCharacters.length; i++) {
                        byteNumbers[i] = byteCharacters.charCodeAt(i);
                    }
                    const byteArray = new Uint8Array(byteNumbers);
                    const blob = new Blob([byteArray], {type: 'application/pdf'});
                    
                    // Crear URL y mostrar en iframe
                    const url = window.URL.createObjectURL(blob);
                    $('#pdfViewer').attr('src', url);
                    $('#modalVisorPDF').modal('show');
                    
                    // Limpiar URL cuando se cierre la modal
                    $('#modalVisorPDF').on('hidden.bs.modal', function () {
                        window.URL.revokeObjectURL(url);
                        $('#pdfViewer').attr('src', '');
                    });
                } catch (e) {
                    toastr.error('Error al procesar el archivo');
                    console.error('Error al procesar archivo:', e);
                }
            } else {
                toastr.error(r.mensajeError || 'Error al obtener el documento');
            }
        });
    }

    // Función para eliminar documento
    function eliminarDocumento(documentoId, tipoDocumento) {
        console.log('=== ELIMINAR DOCUMENTO LLAMADO ===');
        console.log('DocumentoId:', documentoId);
        console.log('TipoDocumento:', tipoDocumento);
        
        if (!documentoId) {
            toastr.error('ID de documento no válido');
            return;
        }
        
        Swal.fire({
            title: '<span style="font-weight:700;font-size:1.3rem;color:#295c1e;">¿Eliminar documento?</span>',
            text: "Esta acción no se puede deshacer",
            iconHtml: '<div class="swal2-custom-icon"><i class="fas fa-exclamation"></i></div>',
            background: '#f8f9fa',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-trash"></i> Sí, eliminar',
            cancelButtonText: '<i class="fas fa-times"></i> Cancelar',
            customClass: {
                popup: 'swal2-kit-modal',
                title: 'swal2-kit-title',
                htmlContainer: 'swal2-kit-text',
                confirmButton: 'btn btn-kit btn-kit-outline-danger',
                cancelButton: 'btn btn-kit btn-kit-outline-success',
                actions: 'swal2-kit-actions',
                icon: 'swal2-kit-icon',
            },
            buttonsStyling: false
        }).then((result) => {
            console.log('SweetAlert result:', result);
            
            // Compatibilidad con diferentes versiones de SweetAlert2
            const confirmado = result.isConfirmed || result.value === true;
            console.log('Confirmado:', confirmado);
            
            if (confirmado) {
                console.log('Usuario confirmó eliminación');
                
                // Mostrar mensaje de carga
                toastr.info('Eliminando documento...');
                
                // Llamar al controlador para eliminar
                console.log('Llamando a enviarPeticion con id:', documentoId);
                enviarPeticion('documentos', 'delete', {id: documentoId}, function(r) {
                    console.log('=== RESPUESTA DEL SERVIDOR ===');
                    console.log('Respuesta completa:', r);
                    console.log('r.ejecuto:', r ? r.ejecuto : 'undefined');
                    console.log('r.mensaje:', r ? r.mensaje : 'undefined');
                    console.log('r.mensajeError:', r ? r.mensajeError : 'undefined');
                    
                    if (r && r.ejecuto) {
                        toastr.success(r.mensaje || 'Documento eliminado correctamente');
                        
                        // Recargar documentos generales
                        console.log('Recargando documentos generales...');
                        cargarRegistrosDocumentos({criterio: 'generales', contratista: user, proceso: 0});
                        
                        // Recargar los documentos del proceso actual si hay uno seleccionado
                        const procesoActual = $('#tituloProceso').text().match(/#(\d+)/);
                        if (procesoActual && procesoActual[1]) {
                            console.log('Recargando documentos del proceso:', procesoActual[1]);
                            cargarRegistrosDocumentos({criterio: 'generales', contratista: user, proceso: procesoActual[1]});
                        }
                        // Limpiar visualmente el documento eliminado
                        $('#btnVer_' + tipoDocumento).html('');
                        $('#estado_' + tipoDocumento).html('<span class="badge bg-secondary">No cargado</span>');
                        $('#conteo_' + tipoDocumento).text('');
                        $('#obs_' + tipoDocumento).text('');
                        // Restaurar botón de cargar si estaba oculto
                        if ($('#btnCargar_' + tipoDocumento + ' span').length > 0) {
                            $('#btnCargar_' + tipoDocumento).html(`
                                <a class='btn btn-kit btn-kit-outline-success btn-sm btn-file' title="Cargar archivo">
                                    <i class='fas fa-upload'></i>
                                    <input type='file' onchange="cargarArchivo(this,${procesoActual ? procesoActual[1] : ''},${tipoDocumento})" accept=".pdf">
                                </a>
                            `);
                        }
                    } else {
                        console.error('Error al eliminar:', r);
                        toastr.error(r.mensajeError || 'Error al eliminar el documento');
                    }
                });
            } else {
                console.log('Usuario canceló eliminación');
            }
        });

        // CSS para personalizar la modal SweetAlert2 con el kit UI
        if (!document.getElementById('swal2-kit-style')) {
            const style = document.createElement('style');
            style.id = 'swal2-kit-style';
            style.innerHTML = `
            .swal2-kit-modal {
                border-radius: 16px !important;
                box-shadow: 0 8px 32px rgba(67,185,74,0.10) !important;
                border: 2px solid #295c1e !important;
                padding-top: 18px !important;
            }
            .swal2-kit-title {
                font-family: inherit !important;
                font-weight: 700 !important;
                color: #295c1e !important;
                margin-bottom: 0.5rem !important;
            }
            .swal2-kit-text {
                color: #444 !important;
                font-size: 1.05rem !important;
                font-weight: 500 !important;
                margin-bottom: 1.2rem !important;
            }
            .swal2-kit-actions {
                margin-top: 1.2rem !important;
                gap: 12px !important;
            }
            .swal2-kit-icon {
                margin-bottom: 18px !important;
            }
            .swal2-custom-icon {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 64px;
                height: 64px;
                background: linear-gradient(135deg, #43b94a 60%, #295c1e 100%);
                color: #fff;
                border-radius: 50%;
                font-size: 2.5rem;
                margin: 0 auto 10px auto;
                box-shadow: 0 2px 12px rgba(67,185,74,0.18);
                border: 3px solid #fff;
            }
            .swal2-kit-modal .btn-kit {
                min-width: 120px;
                font-size: 1rem;
                font-weight: 600;
                padding: 10px 22px;
                border-radius: 8px;
            }
            .swal2-kit-modal .btn-kit-outline-danger {
                border: 2px solid #dc3545;
                color: #dc3545;
                background: #fff;
            }
            .swal2-kit-modal .btn-kit-outline-danger:hover {
                background: linear-gradient(135deg, #dc3545, #c82333);
                color: #fff;
            }
            .swal2-kit-modal .btn-kit-outline-success {
                border: 2px solid #28a745;
                color: #28a745;
                background: #fff;
            }
            .swal2-kit-modal .btn-kit-outline-success:hover {
                background: linear-gradient(135deg, #28a745, #20c997);
                color: #fff;
            }
            `;
            document.head.appendChild(style);
        }
    }

    // Función para descargar documento (mantener por compatibilidad)
    function downloadDocument(documentoId) {
        if (!documentoId) {
            toastr.error('ID de documento no válido');
            return;
        }
        
        // Usar la API para obtener el documento
        enviarPeticion('archivos', 'getDocumento', {id: documentoId}, function(r) {
            if (r.ejecuto && r.file) {
                try {
                    // Convertir base64 a blob
                    const byteCharacters = atob(r.file);
                    const byteNumbers = new Array(byteCharacters.length);
                    for (let i = 0; i < byteCharacters.length; i++) {
                        byteNumbers[i] = byteCharacters.charCodeAt(i);
                    }
                    const byteArray = new Uint8Array(byteNumbers);
                    const blob = new Blob([byteArray], {type: 'application/pdf'});
                    
                    // Crear URL y descargar
                    const url = window.URL.createObjectURL(blob);
                    const link = document.createElement('a');
                    link.href = url;
                    link.download = `documento_${documentoId}.pdf`;
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    window.URL.revokeObjectURL(url);
                } catch (e) {
                    toastr.error('Error al procesar el archivo');
                    console.error('Error al procesar archivo:', e);
                }
            } else {
                toastr.error(r.mensajeError || 'Error al obtener el documento');
            }
        });
    }
</script>
</body>
</html>
