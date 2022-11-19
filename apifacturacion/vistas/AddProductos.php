<?php 
include_once ('apifacturacion/ado/clsCompartido.php');

$objCompartido = new clsCompartido();



 ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Productos & Servicios</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="?bienvenido">Inicio</a></li>
              <li class="breadcrumb-item active">Catalogos</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
        <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">CATÁLOGO DE PRODUCTOS</h3>
              </div>
              <div class="card-body">

     <div class="col-md-12">
      <form id="frProductos">
        <input type="hidden" name="accion" value="AGREGAR_PRODUCTO">
        <div class="form-group row">
          <div class="col-md-2">
            <label for="codigo">Codigo</label>
            <input type="text" class="form-control" id="codigo" name="codigo" disabled>
           </div>
           <div class="col-md-4">
            <label for="nombre">Producto o servicio</label>
            <input type="text" class="form-control" id="nombre" name="nombre" onkeyup="limpiar()" placeholder="Nombre del producto o del servicio">
            <div style="display:none; color:green; font-size: 10px;" id="txtNombre">El campo es obligatorio *</div>
           </div>
           <div class="col-md-3">
            <label for="precio">Precio</label>
            <input type="text" class="form-control" id="precio" name="precio" onkeyup="limpiar()" placeholder="0.00">
            <div style="display:none; color:green; font-size: 10px;" id="txtPrecio">El campo es obligatorio *</div>
           </div>
           <div class="col-md-3">
            <label for="afectacion">Tipo de Afectación</label>
            <select class="form-control" id="afectacion" name="afectacion" onchange="limpiar()">
              <option value="">--Seleccione afectación--</option>
              <?php 
               $mostrar = $objCompartido->listarAfectacion();
                foreach ($mostrar as $key => $value) {
                  echo '<option value="'.$value['codigo'].'">'.$value['descripcion'].'</option>';
                }
               ?>
            </select>
            <div style="display:none; color:green; font-size: 10px;" id="txtAfectacion">El campo es obligatorio *</div>
           </div>
           <div class="col-md-3">
            <label for="tipo">Tipo</label>
            <select class="form-control" id="tipo" name="tipo" onchange="unidad1()">
              <option value="" selected>--Seleccione tipo--</option>
              <option value="1">Bien</option>
              <option value="2">Servicio</option>
            </select>
           </div>
           <div class="col-md-3">
            <label for="unidad">Unidad de medida</label>
              <div id="unidades">
              <select class="form-control" id="unidad" name="unidad" readonly>
                <option value="ZZ">--Seleccione unidad--</option>
              </select>
              </div>
           </div>
        </div>
      </form>
        <div class="form-group row">
          <span class="float-right">
            <button class="btn btn-success" onclick="agregarProducto()"><ion-icon name="add-circle"></ion-icon> Añadir</button>
            <button class="btn btn-danger" onclick="#"><ion-icon name="close-outline"></ion-icon> Cancelar</button>
          </span>
          
        </div>
        <span class="resultado"></span>
      
     </div>
  </div>
</div>

    <div class="card">
    <div class="card-header">
      PRODUCTOS
    </div>
    <div class="card-body">
       <div class="input-group">
        <input class="form-control" type="text" name="producto1" id="producto1" placeholder="producto..." />
        <div class="input-group-addon">
          <button type="button" class="btn btn-default" onclick="BuscarProducto1()"><li class="fa fa-search"></li></button>  
        </div>
      </div>
      <br>
      <table class="table table-bordered">
        <thead>
          <th>Codigo</th>
          <th>Nombre</th>
          <th>Precio</th>
          <th>Unidad</th>
          <th>Opción</th>
        </thead>
        <tbody id="productos">
         
        </tbody>
      </table>
      <br><br>
      
      
      
    </div>
  </div>


  </div>
</div>

<div class="modal fade" id="editarProducto" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><strong>Editar el producto</strong></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formActProducto">
          <div class="form-group row">
            <label for="mod_codigo" class="col-form-label col-md-2 text-right">Codigo:</label>
            <div class="col-md-4">
             <input type="text" class="form-control" id="mod_codigo" name="mod_codigo" readonly>
             <input type="hidden" name="accion" value="ACTUALIZAR_PRODUCTO">
           </div>
          </div>
          <div class="form-group row">
            <label for="mod_nombre" class="col-form-label col-md-2 text-right">Nombre:</label>
             <div class="col-md-10">
              <textarea class="form-control" id="mod_nombre" name="mod_nombre" onkeyup="limpiar2()"></textarea>
              <div style="display:none; color:green; font-size: 10px;" id="mod_txtNombre">El campo es obligatorio *</div>
             </div>
             
          </div>
          <div class="form-group row">
            <label for="mod_precio" class="col-form-label col-md-2 text-right">Precio S/.</label>
             <div class="col-md-4">
              <input type="text" class="form-control" name="mod_precio" id="mod_precio" onkeyup="limpiar2()">
              <div style="display:none; color:green; font-size: 10px;" id="mod_txtPrecio">El campo es obligatorio *</div>
             </div>
            <label for="mod_afectacion" class="col-form-label col-md-2 text-right">Tipo Afectación:</label>
             <div class="col-md-4">
              <select class="form-control" id="mod_afectacion" name="mod_afectacion">
                <?php 
               $mostrar1 = $objCompartido->listarAfectacion();
                foreach ($mostrar1 as $key => $value) {
                  echo '<option value="'.$value['codigo'].'">'.$value['descripcion'].'</option>';
                }
               ?>
              </select>
             </div>
          </div>
          <div class="form-group row">
            <label for="mod_tipo" class="col-form-label col-md-2 text-right">Tipo:</label>
             <div class="col-md-4">
              <select class="form-control" id="mod_tipo" name="mod_tipo" onchange="unidad2()">
                <option value="1">Bien</option>
                <option value="2">Servicio</option>
              </select>
             </div>
             <label for="mod_unidad" class="col-form-label col-md-2 text-right">Unidad:</label>
             <div class="col-md-4" id="mod_unidades">
              <select class="form-control" id="mod_unidad" name="mod_unidad">
                <option>[selecione]</option>
              </select>
             </div>
          </div>
        </form>
      </div>
      <div id="msj_errorActualizar"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><ion-icon name="close-outline"></ion-icon> Cerrar</button>
        <button type="button" class="btn btn-primary" onclick="actualizarProducto()"><ion-icon name="refresh-outline"></ion-icon> Actualizar</button>
      </div>
    </div>
  </div>
</div>
<?php 
 include_once "apifacturacion/vistas/layout/footer.php";
?>
<script>


  function codigo()
  {

    var dato = {accion:'CODIGO'}
    $.ajax({
      method: 'GET',
      url: 'app/control.php',
      data: dato
    })
    .done(function(datos){
       $('#codigo').val(datos);
    })
  }
/* Seccion de guardar productos */
  function agregarProducto()
  {
    var dato = $('#frProductos').serialize();
    var valor = validar();

    if(valor==''){
    
    $.ajax({
      method: 'GET',
      url: 'app/control.php',
      data: dato
    })
    .done(function(datos){
      console.log(datos);
     $('.resultado').html(datos);
     codigo();
    })
   }
  }

  function validar()
  {
    var msj = '';
    var nombre = $('#nombre').val();
    if (nombre=='') {
      $('#nombre').css('border-color','red');
      document.getElementById('txtNombre').style.display = 'block';
      msj = 'error';
    }
    var precio = $('#precio').val();
    if (precio=='') {
      $('#precio').css('border-color','red');
      document.getElementById('txtPrecio').style.display = 'block';
      msj = 'error';
    }
    var afectacion = $('#afectacion').val();
    if (afectacion=='') {
      $('#afectacion').css('border-color','red');
      document.getElementById('txtAfectacion').style.display = 'block';
      msj = 'error';
    }

    return msj;
  }
  function limpiar()
  {
    var nombre = $('#nombre').val();
    var precio = $('#precio').val();
    var afectacion = $('#afectacion').val();

    if (nombre!='') {
      $('#nombre').css('border-color','#E2E2E2');
      document.getElementById('txtNombre').style.display = 'none';
    }
    if (precio!='') {
      $('#precio').css('border-color','#E2E2E2');
      document.getElementById('txtPrecio').style.display = 'none';
    }
    if (afectacion!='') {
      $('#afectacion').css('border-color','#E2E2E2');
       document.getElementById('txtAfectacion').style.display = 'none';
    }
  }

  function unidad1()
  {
    var tipo = $('#tipo').val();

    $.ajax({
      method: 'GET',
      url: 'app/control.php',
      data: {tipo:tipo,accion:'UNIDAD'}
    })
    .done(function(datos){
      $('#unidades').html(datos);
      
    })
  }
  /***********************************************/
  /* Seccion de actualizar productos */
  function actualizarProducto()
  {
    var dato = $('#formActProducto').serialize();
    var valor = validar2();

    if(valor==''){
    
    $.ajax({
      method: 'GET',
      url: 'app/control.php',
      data: dato
    })
    .done(function(datos){
      console.log(datos);
      if(datos==1) {
     $('#editarProducto').modal('hide');
     BuscarProducto1();
       alertify
            .alert("Ok","El producto se actualizo correctamente!", function(){
              alertify.message('OK');
            });
       }
       else {
          $('#msj_errorActualizar').html('<div class="alert alert-danger" role="alert">No se pudo actualizar el producto, intente nuevamente!</div>');

          setTimeout(function(){
                         $('#msj_errorActualizar').hide();
                      },3000);
       }
    })

   }
  }
  function validar2()
  {
    var msj = '';
    var nombre = $('#mod_nombre').val();
    if (nombre=='') {
      $('#mod_nombre').css('border-color','red');
      document.getElementById('mod_txtNombre').style.display = 'block';
      msj = 'error';
    }
    var precio = $('#mod_precio').val();
    if (precio=='') {
      $('#mod_precio').css('border-color','red');
      document.getElementById('mod_txtPrecio').style.display = 'block';
      msj = 'error';
    }

    return msj;
  }
  function limpiar2()
  {
    var nombre = $('#mod_nombre').val();
    var precio = $('#mod_precio').val();

    if (nombre!='') {
      $('#mod_nombre').css('border-color','#E2E2E2');
      document.getElementById('mod_txtNombre').style.display = 'none';
    }
    if (precio!='') {
      $('#mod_precio').css('border-color','#E2E2E2');
      document.getElementById('mod_txtPrecio').style.display = 'none';
    }

  }

  function unidad2(i)
  {
    var tipo = $('#mod_tipo').val();

    $.ajax({
      method: 'GET',
      url: 'app/control.php',
      data: {tipo:tipo,i:i,accion:'MOD_UNIDAD'}
    })
    .done(function(datos){
      $('#mod_unidades').html(datos);
      
    })
  }
/*******************************************************/
  function BuscarProducto1()
  {
    var producto = $('#producto1').val();
    
    $.ajax({
      method: 'GET',
      url: 'app/control.php',
      data: {producto:producto,accion:'BUSCAR_PRODUCTO'}
    })
    .done(function(datos){
      
      $('#productos').html(datos);
      
    })
  }
  function eliminar(id)
  {
    
    $.ajax({
      method: 'GET',
      url: 'app/control.php',
      data: {producto:id,accion:'ELIMINAR_PRODUCTO'}
    })
    .done(function(datos){
      
      BuscarProducto1();
      
    })
  }
  function editarProducto(id)
  {
    $('#editarProducto').modal('show');
     $.ajax({
      method: 'GET',
      url: 'app/control.php',
      dataType: 'json',
      data: {id:id,accion:'EDITAR_PRODUCTO'}
    })
    .done(function(datos){
      //console.log(datos);
      $('#mod_codigo').val(datos.codigo);
      $('#mod_nombre').val(datos.nombre);
      $('#mod_precio').val(datos.precio);
      $('#mod_afectacion').val(datos.codigoafectacion);
      if (datos.unidad!='ZZ') {
        $('#mod_tipo').val(1);
      } else {
        $('#mod_tipo').val(2);
      }
       unidad2(datos.unidad);
      
    })

    
  }
</script>

</body>

</html>