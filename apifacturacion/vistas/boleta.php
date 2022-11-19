<?php 
require_once("apifacturacion/ado/clsEmisor.php");
require_once("apifacturacion/ado/clsCompartido.php");

$objEmisor = new clsEmisor();
$listado = $objEmisor->consultarListaEmisores();

$objCompartido = new clsCompartido();
$monedas = $objCompartido->listarMonedas();

$comprobantes = $objCompartido->listarComprobantes();

$documentos = $objCompartido->listarTipoDocumento();

?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Boleta</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="?bienvenido">Inicio</a></li>
              <li class="breadcrumb-item active">Boleta electrónica</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

<!-- Content Wrapper. Contains page content -->
  <div class="container-fluid">

  <div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title">ENVÍO DE BOLETAS ELECTRÓNICAS</h3>
              </div>
              <div class="card-body">

<form id="frmVenta" name="frmVenta" submit="return false">
<div class="col-md-12">
<div class="row">
	<div class="col-md-3">
		<div class="form-group">
			<label>Boleta Por</label>
			<select class="form-control" id="idemisor" name="idemisor">
				<?php while($fila = $listado->fetch(PDO::FETCH_NAMED)){ ?>
					<option value="<?php echo $fila['id'];?>"><?php echo $fila['razon_social'];?></option>
				<?php } ?>
				<input type="hidden" name="accion" id="accion" value="GUARDAR_VENTA">
				<input type="hidden" name="voucher" id="voucher">
			</select>
		</div>
		<div class="form-group">
			<label>Fecha</label>
			<input class="form-control" type="date" name="fecha_emision" id="fecha_emision" value="<?php echo date('Y-m-d');?>" />
		</div>
		<div class="form-group">
			<label>Moneda</label>
			<select class="form-control" type="date" name="moneda" id="moneda">
				<?php while($fila = $monedas->fetch(PDO::FETCH_NAMED)){ ?>
					<option value="<?php echo $fila['codigo'];?>"><?php echo $fila['descripcion'];?></option>
				<?php } ?>
			</select>
		</div>
	</div>
	<div class="col-md-3">
		<div class="form-group">
			<label>Tipo Comp.</label>
			<select class="form-control" name="tipocomp" id="tipocomp" onchange="ConsultarSerie()">
				<?php 
				foreach ($comprobantes as $k => $fila) {
					?>
					<option value="<?=$fila['codigo']?>"><?=$fila['descripcion']?></option>
				<?php } ?>
			</select>
		</div>	
		<div class="form-group">
			<label>Serie</label>
			<select class="form-control" type="date" name="idserie" id="idserie" onchange="ConsultarCorrelativo()">
				
			</select>
		</div>
		<div class="form-group">
			<label>Correlativo</label>
			<input class="form-control" type="number" name="correlativo" id="correlativo" />
		</div>				
	</div>
	<div class="col-md-3">
		<div class="form-group">
			<label>Tipo Doc.</label>
			<select class="form-control" name="tipodoc" id="tipodoc" onchange="selecciona()">
				<?php while($fila = $documentos->fetch(PDO::FETCH_NAMED)){ ?>
					<option value="<?php echo $fila['codigo'];?>"><?php echo $fila['descripcion'];?></option>
				<?php } ?>
			</select>
			<div style="display:none; color:green; font-size: 10px;" id="txtTipodoc">El campo es obligatorio *</div>
		</div>
		<div class="form-group">
			<label>Nro. Doc</label>
			<div class="input-group">
				<input class="form-control" type="text" name="nrodoc" id="nrodoc" onkeyup="limpiar()" readonly/>
				<div class="input-group-addon">
					<button type="button" class="btn btn-default" id="btnBuscar" onclick="ObtenerDatosEmpresa()" disabled><li class="fa fa-search"></li></button>	
				</div>
			</div>
			<div style="display:none; color:green; font-size: 10px;" id="txtNrodoc">El campo es obligatorio *</div>
		</div>
		<div class="form-group">
			<label>Nombre/Raz. Social</label>
			<input class="form-control" type="text" name="razon_social" id="razon_social" onkeyup="limpiar()" readonly/>
			<div style="display:none; color:green; font-size: 10px;" id="txtRazonSocial">El campo es obligatorio *</div>
		</div>
		<div class="form-group">
			<label>Dirección</label>
			<input class="form-control" type="text" name="direccion" id="direccion" readonly/>
			<input type="hidden" name="ubigeo" id="ubigeo">
		</div>									
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<div class="input-group">
				<input class="form-control" type="text" name="producto" id="producto" onkeyup="BuscarProducto()" placeholder="producto..." />
				<div class="input-group-addon">
					<button type="button" class="btn btn-default" onclick="BuscarProducto()"><li class="fa fa-search"></li></button>	
				</div>
			</div>
		<div class="col-md-12">
    <div class="table-responsive">
			<table class="table table-bordered table-hover">
				<thead>
          <th style="width: 10%">Cod</th>
					<th style="width: 50%">Nombre</th>
					<th style="width: 15%">Cantidad</th>
					<th style="width: 15%">Precio</th>
					<th style="width: 10%">+</th>
				</thead>
				<tbody id="div_productos">
					
				</tbody>
			</table>
      </div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="col-md-12" id="div_carrito">
		</div>
		<div>
      <button type="button" class="btn btn-outline-primary btn-block" onclick="ModalEnvio()"><i class="fa fa-save"></i> S/ <span id="totalboton">0.00</span></button>
      <button type="button" class="btn btn-outline-danger btn-block btn-sm" onclick="CancelarVenta();"><ion-icon name="close-outline"></ion-icon> Cancelar</button>
		</div>
	</div>
</div>
</div>
</form>

</div>
</div>
</div>
</div>
<?php
 include_once "apifacturacion/vistas/layout/footer.php";
?>

<script>

   
  $(document).ready(function(){
    $("#tipocomp").val("03");
    $('#nrodoc').val('00000000');
    $('#razon_social').val('Sin documento');
    ConsultarSerie();
});
 function selecciona()
 {
  var tipo = $('#tipodoc').val();
  if(tipo!=0) {
    $('#nrodoc').attr('readonly',false);
    $('#razon_social').attr('readonly',false);
    $('#direccion').attr('readonly',false);

    $('#btnBuscar').attr('disabled',false);
    $('#nrodoc').val('');
    $('#razon_social').val('');

  } else {
    $('#nrodoc').attr('readonly',true);
    $('#razon_social').attr('readonly',true);
    $('#direccion').attr('readonly',true);

    $('#btnBuscar').attr('disabled',true);
    $('#nrodoc').val('00000000');
    $('#razon_social').val('Sin documento');
  }
 }
  function ConsultarSerie(){
      $.ajax({
          method: "POST",
          url: 'apifacturacion/controlador/controlador.php',
          data: {
          	  "accion": "LISTAR_SERIES",
              "tipocomp": $("#tipocomp").val()
            }
      })
      .done(function( text ) {
            json = JSON.parse(text);            
            series = json.series;
            options = '';
            for(i=0;i<series.length;i++){
            	options = options + '<option value="'+series[i].id+'">'+series[i].serie+'</option>';
            }
            $("#idserie").html(options);
            ConsultarCorrelativo();
      });
  }


 // ConsultarSerie();


  function ConsultarCorrelativo(){
      $.ajax({
          method: "POST",
          url: 'apifacturacion/controlador/controlador.php',
          data: {
          	  "accion": "OBTENER_CORRELATIVO",
              "idserie": $("#idserie").val()
            }
      })
      .done(function( text ) {
            $("#correlativo").val(text);
      });
  }

  function ObtenerDatosEmpresa(){
  		var tipodoc = $("#tipodoc").val();

  		if(tipodoc==1){
  			dni();
  		}else if(tipodoc==6){
  			ruc();
  		}
  }
  
  function dni()
{
    if($("#nrodoc").val()!=''){
    $.ajax({
        method: 'GET',
        url: 'app/apiDocumento.php',
        data: {doc:$("#nrodoc").val(),'accion':'DNI'},
        dataType: 'json'
      })
      .done(function(datos){
       // console.log(datos);
        if(datos.nombre==''){
            $('#razon_social').val(datos.nombres);
        } else {
            $('#razon_social').val(datos.nombre);
        }
         
      })
    } else {
        alertify.alert("Atención!","Escriba un número de documento.", function(){
              alertify.message('OK');
        });
    }
  
}
function ruc()
{
    if($("#nrodoc").val()!=''){
    $.ajax({
        method: 'GET',
        url: 'app/apiDocumento.php',
        data: {doc:$("#nrodoc").val(),'accion':'RUC'},
        dataType: 'json'
      })
      .done(function(datos){
       // console.log(datos);
         $('#razon_social').val(datos.nombre);
         $('#direccion').val(datos.direccion);
         $('#ubigeo').val(datos.distrito+' - '+datos.provincia+' - '+datos.departamento);
      })
    } else {
        alertify.alert("Atención!","Escriba un número de documento.", function(){
              alertify.message('OK');
        });
    }
    
}

  
function BuscarProducto(){
    
    if($("#producto").val()!='' || $("#producto").val()!=null) {
       $.ajax({
           method: "GET",
           url: 'app/apiRest.php',
           data: {
               "accion": "BUSCAR_PRODUCTO",
               "filtro": $("#producto").val()
             }
       })
       .done(function( text ) {
           
           json = JSON.parse(text);            
             productos = json.result;
             listado = '';
             for(i=0;i<productos.length;i++){
               listado = listado + '<tr><td>'+productos[i].codigo_02+'</td><td><textarea class="form-control" id="nom'+productos[i].id+'">'+productos[i].descripcion+'</textarea> </td><td><input class="form-control" type="number" style="width:80px" value="1"  id="cant'+productos[i].id+'"> </td><td><input class="form-control" style="width:80px" id="precio'+productos[i].id+'" value="'+productos[i].precio_venta01+'" /></td><td><button type="button" class="btn btn-primary" onclick="guardarProducto(\''+productos[i].id+'\')"><i class="fa fa-plus"></i></button></td></tr>';
             }
             $("#div_productos").html(listado);
             
       });
     } else {
       $("#div_productos").html('');
     }
   }
 
   function AgregarCarrito(codigo,precio,i,nom){
     
       $.ajax({
           method: "POST",
           url: 'apifacturacion/controlador/controlador.php',
           data: {
               "accion": "ADD_PRODUCTO",
               "precio": precio,
               "codigo": codigo,
               "cantidad":i,
               "nombres":nom
             }
       })
       .done(function( html ) {
             $("#div_carrito").html(html);
       });  		
   }
   // funcion para guardar producto buscado del API =====
     function guardarProducto(codigo)
     {
       var i = $('#cant'+codigo).val();
      var nom = $('#nom'+codigo).val();
      var precio = $("#precio"+codigo).val();
       $.ajax({
           method: "GET",
           url: 'app/apiRest.php',
           data: {
               "accion": "CODIGO_PRODUCTO",
               "codigo":codigo
             }
       })
       .done(function( html ) {
            //respuesta
            console.log(html);
            AgregarCarrito(codigo,precio,i,nom);
       });  
     }
   
   //===================================
  function MostrarCarrito(){
      $.ajax({
          method: "POST",
          url: 'apifacturacion/controlador/controlador.php',
          data: {
          	  "accion": "MOSTRAR_CARRITO"
            }
      })
      .done(function( html ) {
            $("#div_carrito").html(html);
      });  		
  }

  function CancelarVenta(codigo){
      $.ajax({
          method: "POST",
          url: 'apifacturacion/controlador/controlador.php',
          data: {
          	  "accion": "CANCELAR_CARRITO"
            }
      })
      .done(function( html ) {
            $("#div_carrito").html(html);
            $('#totalboton').html('0.00');
      });  		
  }
  function CancelarItem(id){
      $.ajax({
          method: "POST",
          url: 'apifacturacion/controlador/controlador.php',
          data: {
              "id": id,
          	  "accion": "CANCELAR_ITEM"
            }
      })
      .done(function( html ) {
        MostrarCarrito();
      });  		
  }

  function GuardarVenta(){
  	var datax = $("#frmVenta").serializeArray();
    var validar = validando1();

    if(validar=='') {
				$.ajax({
			      method: "POST",
			      url: 'apifacturacion/controlador/controlador.php',
            dataType: "JSON",
			      data: datax
			  	})
			  	.done(function( html ) {
			        $("#div_carrito").html(html.mensaje);
              $('#totalboton').html('0.00');
              $('#voucher2').html("<embed src='reportes/tk_impresion.php?id="+html.id+"' type='application/pdf' width='100%' height='600px' />");
              $('#a42').html("<embed src='reportes/comprobante.php?id="+html.id+"' type='application/pdf' width='100%' height='600px' />");
			  	}); 

    }

  }
  
  function validando()
  {
  	var msj = '';

  	if ($('#tipodoc').val()==0) {
      $('#tipodoc').css('border-color','red');
          document.getElementById('txtTipodoc').style.display = 'block';
          msj = 'error';
  	 } 
  		if ($('#razon_social').val()=='') {
  			$('#razon_social').css('border-color','red');
          document.getElementById('txtRazonSocial').style.display = 'block';
          msj = 'error';
  		}
  		if ($('#nrodoc').val()=='') {
  			$('#nrodoc').css('border-color','red');
          document.getElementById('txtNrodoc').style.display = 'block';
          msj = 'error';
  		}
  	

  	return msj;
  }
  // considerar vacio al cliente
  function validando1()
  {
  	return '';
  }

  function limpiar()
  {
    var nombre = $('#razon_social').val();
    var doc = $('#nrodoc').val();

    if (nombre!='') {
      $('#razon_social').css('border-color','#E2E2E2');
      document.getElementById('txtRazonSocial').style.display = 'none';
    }
    if (doc!='') {
      $('#nrodoc').css('border-color','#E2E2E2');
      document.getElementById('txtNrodoc').style.display = 'none';
    }

  }

</script>

</body>
</html>