<?php require('views/header.php');?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        Gestión de documentos
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="main/inicio/">Inicio</a></li>
                        <li class="breadcrumb-item active">Cargar documentos</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">
                </div>
                <div class="col-md-6">
                    <div class="card card-outline card-success">
                        <div class="card-header">
                            <h3 class="card-title">Documentos generales</h3>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-responsive">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Estado</th>
                                        <th>Revisiones</th>
                                        <th>Motivo</th>
                                        <th>Opciones</th>
                                    </tr>
                                </thead>
                                <tbody id="generales"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-5">
                    <div class="card card-outline card-success">
                        <div class="card-header">
                            <h3 class="card-title">Procesos</h3>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-responsive">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Gerencia</th>
                                        <th>Fecha</th>
                                        <th>Estado</th>
                                        <th>Opciones</th>
                                    </tr>
                                </thead>
                                <tbody id="contratos"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="card card-outline card-success">
                        <div class="card-header">
                            <h3 class="card-title" id="tituloProceso">Documentos del proceso</h3>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-responsive">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Estado</th>
                                        <th>Revisiones</th>
                                        <th>Motivo</th>
                                        <th>Opciones</th>
                                    </tr>
                                </thead>
                                <tbody id="documentosProceso"></tbody>
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
    var user = 0
    function init(info){
        user = info.data.usuario.id
        //Primero cargo los documentos generales
        mostrarDocumentos(1,1,'generales')

        //Cargo los contratos
        enviarPeticion('procesos', 'getProcesos', {criterio: 'ps'}, function(r){
            let fila = ''
            r.data.map(registro => {
                fila += `<tr>
                            <td>${registro.id}</td>
                            <td>${registro.gerencia}</td>
                            <td>${registro.fc}</td>
                            <td>${registro.estado}</td>
                            <td>
                                <button class="btn btn-default" onClick="mostrarDocumentos(0,${registro.id},'documentosProceso')" title="Ver documentación">
                                    Ver documentación <i class="far fa-arrow-alt-circle-right"></i>
                                </button>
                            </td>
                        </tr>`
            })
            $('#contratos').append(fila)
        })
    }

    function mostrarDocumentos(permanente, proceso, elemento){
        $('#tituloProceso').text(`Documentos del proceso #${proceso}`)
        enviarPeticion('documentosTipo', 'select', {info:{is_permanente: permanente}}, function(r){
            let fila = ''
            r.data.map(registro => {
                fila += `<tr>
                            <td>${registro.nombre}</br><small class="text-muted">${registro.inmersos}</small></td>
                            <td id="estado_${registro.id}">
                                <span class="badge bg-secondary">No cargado</span>
                            </td>
                            <td id="conteo_${registro.id}" class="text-center"></td>
                            <td id="obs_${registro.id}"></td>
                            <td>
                                <div style="display: flex; gap: 10px;">
                                    <div style="width: 50px;" id="btnCargar_${registro.id}">
                                        <a class='btn btn-default btn-file'>
                                            <i class='fas fa-upload'></i>
                                            <input type='file' onchange="cargarArchivo(this,${proceso},${registro.id})" accept=".pdf">
                                        </a>
                                    </div>
                                    <div style="width: 50px;" id="btnVer_${registro.id}"></div>
                                </div>
                            </td>
                        </tr>`
            })
            $(`#${elemento}`).html(fila)
            cargarRegistrosDocumentos({criterio: 'generales', contratista: user, proceso: proceso})
        })
    }

    function cargarArchivo(input, proceso, tipo){
        if(comprobarArchivo(input)){
            enviarPeticion('documentos', 'crear', {contratista: user, proceso: proceso, tipo: tipo}, function(r){
                cargarDocumento(input, r.insertId, function(res){
                    if(res.ejecuto == true){
                        //Esto en caso de que cargue el documento
                        enviarPeticion('documentos', 'update', {info: {estado: 1}, id: r.insertId}, function(y){
                            toastr.success(res.msg)
                            cargarRegistrosDocumentos({criterio: 'id', id: r.insertId})
                        })
                    }else{
                        //En caso de que no se deja como si no hubiera cargado
                        enviarPeticion('documentos', 'update', {info: {estado: 0}, id: r.insertId}, function(y){
                            toastr.error(res.msg)
                            cargarRegistrosDocumentos({criterio: 'id', id: r.insertId})
                        })
                    }
                })
            })
        }
    }

    function cargarRegistrosDocumentos(datos){
        enviarPeticion('documentos', 'getDocumentos', datos, function(r){
            r.data.map(registro => {
                $('#estado_'+registro.tipo).html(`<span class="badge bg-${coloresEstadoDocumentos[registro.estado]}">${estadoDocumentos[registro.estado]}</span>`)
                $('#conteo_'+registro.tipo).text(registro.conteo)
                if(registro.estado == 3){
                    $('#obs_'+registro.tipo).text(registro.observaciones)
                }else{
                    $('#obs_'+registro.tipo).text('')
                }                
                if(registro.estado == 0){
                    $('#btnVer_'+registro.tipo).html('')
                }else{
                    $('#btnVer_'+registro.tipo).html(`<button type="button" class="btn btn-default" onClick="downloadDocument(${registro.id})" title="Ver documento">
                                                    <i class="far fa-eye"></i>
                                                </button>`)    
                }
                if(registro.estado == 2){
                    $('#btnCargar_'+registro.tipo).html('')
                }
            })
        })
    }
</script>
</body>
</html>