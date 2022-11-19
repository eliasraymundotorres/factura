<?php 
include_once 'apifacturacion/ado/clsCompartido.php';

$objCompartido = new clsCompartido();
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Configuración & soporte</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="?bienvenido">Inicio</a></li>
              <li class="breadcrumb-item active">Configuración</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
<div class="row">
  <div class="col-md-6">
     <div class="card card-success">
              <div class="card-header">
                <h3 class="card-title">SOPORTE DE SERIES Y NUMERACIÓN</h3>
              </div>
              <div class="card-body">
               <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Comprobante</label>
                          <select class="form-control" name="tipo" id="tipo">
                          <option value="" disabled selected>[seleccione]</option>
                            <?php
                            $tipo = $objCompartido->listarComprobantes();
                            foreach ($tipo as $value) {
                                echo '
                                   <option value="'.$value['codigo'].'">'.$value['descripcion'].'</option>
                                ';
                            }
                            ?>
                            
                          </select>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Serie</label>
                        <input class="form-control" type="text" name="series" id="series">
                    </div>
                </div>
               </div>
               <div class="row">
                 <p class="text-right">
                    <button class="btn btn-primary" onclick="agregarTipo()"><i class="fa fa-plus"></i> Agregar</button>
                 </p>
               </div>
              <table class="table table-sm">
                  <thead>
                    <tr>
                      <th style="width: 50%">Tipo Comp</th>
                      <th style="width: 15%">Serie</th>
                      <th style="width: 15%">Numeración</th>
                      <th style="width: 20%">Opción</th>
                    </tr>
                  </thead>
                  <tbody id="html_series">
                    
                  </tbody>
                </table>
            </div>
     </div>
     
  </div>

  <div class="col-12 col-sm-6">
            <div class="card card-primary card-tabs">
              <div class="card-header p-0 pt-1">
                <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">Voucher</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">A4</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-one-messages-tab" data-toggle="pill" href="#custom-tabs-one-messages" role="tab" aria-controls="custom-tabs-one-messages" aria-selected="false">A5</a>
                  </li>
                </ul>
              </div>
              <div class="card-body">
                <div class="tab-content" id="custom-tabs-one-tabContent">
                  <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                    <div class="row">
                        <div class="col-md-4">
                          <div class="input-group mb-3">
                            <input type="text" class="form-control" style="font-size: 20px; text-align:center; font-weight: bold;" name="ancho" id="ancho" onblur="espesor()" placeholder="Ancho del voucher">
                            <div class="input-group-append">
                              <span class="input-group-text">mm</span>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-8">
                          <span><strong>El ancho es entre:</strong> 54mm < <strong>ancho</strong> < 81 mm </span>
                        </div>
                     </div>
                      <div class="row" id="pdfvista">
                         
                      </div>
                  </div>
                  <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
                     <div class="row">
                        <embed src="reportes/a4.pdf" type="application/pdf" width="100%" height="600px" />
                    </div>
                  </div>
                  <div class="tab-pane fade" id="custom-tabs-one-messages" role="tabpanel" aria-labelledby="custom-tabs-one-messages-tab">
                  <div class="row">
                        <embed src="reportes/a4.pdf" type="application/pdf" width="100%" height="600px" />
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.card -->
            </div>

          </div>

  </div> <!-- fin de row -->

  <div class="row">
    <div class="col-md-6">
     <div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title">CERTIFICADO DIGITAL</h3>
              </div>
              <div class="card-body">
                 
              <div id="actions" class="row">
                  <div class="col-lg-6">
                    <div class="btn-group w-100">
                      <span class="btn btn-success col fileinput-button">
                        <i class="fas fa-plus"></i>
                        <span>Subir certificado</span>
                      </span>
                    </div>
                  </div>
                  <div class="col-lg-6 d-flex align-items-center">
                    <div class="fileupload-process w-100">
                      <div id="total-progress" class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                        <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>
                      </div>
                    </div>
                  </div>
                  
                </div>

                <div class="table table-striped files" id="previews">
                  <div id="template" class="row mt-2">
                    <div class="col-auto">
                        <span class="preview"><img src="data:," alt="" data-dz-thumbnail /></span>
                    </div>
                    <div class="col d-flex align-items-center">
                        <p class="mb-0">
                          <span class="lead" data-dz-name></span>
                          (<span data-dz-size></span>)
                        </p>
                        <strong class="error text-danger" data-dz-errormessage></strong>
                    </div>
                    <div class="col-4 d-flex align-items-center">
                        <div class="progress progress-striped active w-100" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                          <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>
                        </div>
                    </div>
                    <div class="col-auto d-flex align-items-center">
                      <div class="btn-group">
                        <button class="btn btn-primary start">
                          <i class="fas fa-upload"></i>
                          <span>Subir</span>
                        </button>
                        <button data-dz-remove class="btn btn-warning cancel">
                          <i class="fas fa-times-circle"></i>
                          <span>Cancelar</span>
                        </button>
                        <button data-dz-remove class="btn btn-danger delete">
                          <i class="fas fa-trash"></i>
                          <span>Eliminar</span>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
                 <br>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                       <label>Clave del certificado (.pfx):</label>
                        <div class="input-group input-group">
                          <input type="text" class="form-control" name="clave" id="clave">
                          <span class="input-group-append">
                            <button type="button" class="btn btn-info btn-flat" onclick="guardarClave()"> <ion-icon name="checkbox"></ion-icon></button>
                          </span>
                      </div>
                    </div> 
                  </div>
                  <div class="col-lg-6" id="siexiste">
                        
                  </div> 
                </div>

                <div class="row">
                  <div class="form-group">
                    <div class="custom-control custom-switch">
                      <input type="checkbox" class="custom-control-input" id="servidor" name="servidor" onchange="actualizaServidor()">
                      <label class="custom-control-label" for="servidor">Enviar de manera oficial</label>
                    </div>
                  </div>
                </div>

            </div>

            
          </div>
          
          

        </div>

        <div class="col-md-6">
        <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">USUARIO SECUNDARIO</h3>
              </div>
              <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                          <!-- text input -->
                          <div class="form-group">
                            <label>Usuario</label>
                            <input type="text" class="form-control" name="usecundario" id="usecundario" placeholder="Usuario">
                          </div>
                        </div>
                        <div class="col-sm-6">
                          <div class="form-group">
                            <label>Contraseña</label>
                            <input type="text" class="form-control" name="csecundario" id="csecundario" placeholder="Contraseña">
                          </div>
                        </div>
                    </div>
                    <div class="row">
                      <button class="btn btn-primary" onclick="usuarioSecundario()"><ion-icon name="checkbox"></ion-icon> Actualizar</button>
                    </div>
            </div>
        </div>
      </div>

    </div>
    
  
</div>

</div>
<?php 
include_once "apifacturacion/vistas/layout/footer.php";
?>


<script>
  $(document).ready(function(){
    listar();
    reportePDFVista();
    ancho();
    VerCertificado();
    mostrarClave();
    mostrarUsuarioSecundario();
    verServidor();
  })
  function listar()
  {
    $.ajax({
      method: "POST",
      url: 'apifacturacion/controlador/controlador.php',
      data:{'accion':'LISTAR_EDITAR_SERIES'}
    })
    .done(function(datos){
      $('#html_series').html(datos);
    })
  }
  function actualizaServidor()
  {
    if(document.getElementById("servidor").checked)
    {
       var servidor = 1;
    } else {
       var servidor = 0;
    }
    $.ajax({
      method: "POST",
      url: 'apifacturacion/controlador/controlador.php',
      data:{servidor:servidor,'accion':'ACTUALIZAR_SERVIDOR'}
    })
    .done(function(datos){
      verServidor();
    })
  }
  function verServidor()
  {
    $.ajax({
      method: "POST",
      url: 'apifacturacion/controlador/controlador.php',
      data:{'accion':'VERIFICAR_SERVIDOR'}
    })
    .done(function(datos){
       if(datos==1){
        document.getElementById("servidor").checked = true;
       } else {
        document.getElementById("servidor").checked = false;
       }
    })
  }
  function guardarClave()
  {
    $.ajax({
      method: "POST",
      url: 'apifacturacion/controlador/controlador.php',
      data:{clave:$('#clave').val(),'accion':'GUARDAR_CLAVE_DIGITAL'}
    })
    .done(function(datos){
      mostrarClave();
      alertify.message('Se grabo correctamente la clave');
    })
  }
  function usuarioSecundario()
  {
    $.ajax({
      method: "POST",
      url: 'apifacturacion/controlador/controlador.php',
      data:{usuario:$('#usecundario').val(),clave:$('#csecundario').val(),'accion':'GUARDAR_USUARIO_SECUNDARIO'}
    })
    .done(function(datos){
      mostrarUsuarioSecundario();
      alertify.message('Se grabo correctamente el usuario y clave secundario');
    })
  }
  function mostrarClave()
  {
    $.ajax({
      method: "POST",
      url: 'apifacturacion/controlador/controlador.php',
      data:{'accion':'MOSTRAR_CLAVE_DIGITAL'}
    })
    .done(function(datos){
      $('#clave').val(datos);
    })
  }
  function VerCertificado()
  {
    $.ajax({
      method: "POST",
      url: 'apifacturacion/controlador/controlador.php',
      data:{'accion':'VERIFICAR_CERTIFICADO'}
    })
    .done(function(datos){
      $('#siexiste').html(datos);
    })
  }
  function mostrarUsuarioSecundario()
  {
    $.ajax({
      method: "POST",
      url: 'apifacturacion/controlador/controlador.php',
      dataType: 'json',
      data:{'accion':'MOSTRAR_USUARIO_SECUNDARIO'}
    })
    .done(function(datos){
      $('#usecundario').val(datos.usuario_sol);
      $('#csecundario').val(datos.clave_sol);
    })
  }
  function agregarTipo()
  {
    $.ajax({
      method: "POST",
      url: 'apifacturacion/controlador/controlador.php',
      data:{serie:$('#series').val(),tipo:$('#tipo').val(),'accion':'AGREGAR_SERIES_LISTA'}
    })
    .done(function(datos){
      listar();
      alertify.message('Se registro una serie');
    })
  }
  function edit(id)
  {
    $.ajax({
      method: "POST",
      url: 'apifacturacion/controlador/controlador.php',
      data:{id:id,serie:$('#serieEdit'+id).val(),numero:$('#num'+id).val(),'accion':'EDITAR_SERIES_GUARDAR'}
    })
    .done(function(datos){
      listar();
      
      alertify.message('Se actualizó la serie');
            
    })
  }
  function deleteSeries(id)
  {
    $.ajax({
      method: "POST",
      url: 'apifacturacion/controlador/controlador.php',
      data:{id:id,'accion':'ELIMINAR_SERIES_LISTA'}
    })
    .done(function(datos){
      listar();
      
      alertify.message('Se eliminó la serie correctamente');
            
    })
  }
  function reportePDFVista()
  {
    $('#pdfvista').html('<embed src="reportes/reportedemo.php" type="application/pdf" width="100%" height="600px" />');
  }
  function ancho()
  {
    $.ajax({
      method: "POST",
      url: 'apifacturacion/controlador/controlador.php',
      data:{'accion':'ANCHO'}
    })
    .done(function(datos){
      
       $('#ancho').val(datos);      
    })
  }
  function espesor()
  {
    let a = document.getElementById('ancho').value;
    if (a>=55 && a<=80) {
       
      $.ajax({
        method: "POST",
        url: 'apifacturacion/controlador/controlador.php',
        data:{ancho:a,'accion':'EDITAR_ANCHO'}
      })
      .done(function(datos){
        ancho();
        reportePDFVista();
      })

    } else {
      ancho();
    }
  }
// Comienzo del código de demostración de DropzoneJS
  Dropzone.autoDiscover = false

  // Obtenga la plantilla HTML y elimínela del documento
  var previewNode = document.querySelector("#template")
  previewNode.id = ""
  var previewTemplate = previewNode.parentNode.innerHTML
  previewNode.parentNode.removeChild(previewNode)

  var myDropzone = new Dropzone(document.body, { // Haz que todo el cuerpo sea una zona de descenso
    url: "app/subir.php", // Set the url
    acceptedFiles : '.pfx',
    thumbnailWidth: 80,
    thumbnailHeight: 80,
    parallelUploads: 20,
    previewTemplate: previewTemplate,
    autoQueue: false, // Asegúrese de que los archivos no estén en cola hasta que se agreguen manualmente
    previewsContainer: "#previews", // Defina el contenedor para mostrar las vistas previas
    clickable: ".fileinput-button" // Defina el elemento que debe usarse como disparador de clic para seleccionar archivos.

  })

  myDropzone.on("addedfile", function(file) {
    // Conectar el botón de inicio
    file.previewElement.querySelector(".start").onclick = function() { myDropzone.enqueueFile(file) }
  })

  // Actualizar la barra de progreso total
  myDropzone.on("totaluploadprogress", function(progress) {
    document.querySelector("#total-progress .progress-bar").style.width = progress + "%"
  })

  myDropzone.on("sending", function(file) {
    // Muestra la barra de progreso total cuando comienza la carga
    document.querySelector("#total-progress").style.opacity = "1"
    // Y desactivar el botón de inicio
    file.previewElement.querySelector(".start").setAttribute("disabled", "disabled")
    VerCertificado();
  })

  // Ocultar la barra de progreso total cuando ya no se carga nada
  myDropzone.on("queuecomplete", function(progress) {
    document.querySelector("#total-progress").style.opacity = "0"
  })
  // Configurar los botones para todas las transferencias
  // The "add files" button doesn't need to be setup because the config
  // `clickable` has already been specified.
  document.querySelector("#actions .start").onclick = function() {
    myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED))
  }
  document.querySelector("#actions .cancel").onclick = function() {
    myDropzone.removeAllFiles(true)
  }

</script>


</body>
</html>