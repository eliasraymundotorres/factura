<?php
require_once("apifacturacion/ado/clsEmisor.php");
require_once("apifacturacion/ado/clsVenta.php");

$objVenta = new clsVenta();
$objEmisor = new clsEmisor();


$listado = $objEmisor->consultarListaEmisores();

$listadoBoletas = $objVenta->listarComprobantePorTipo("03");
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Envio de Resumenes</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="?bienvenido">Inicio</a></li>
              <li class="breadcrumb-item active">Resumenes</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

<!-- Content Wrapper. Contains page content -->
  <div class="container-fluid">

  <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">ENVÍO DE RESUMENES ELECTRÓNICAS</h3>
              </div>
              <div class="card-body">
<br>
   <div class="form-group row">
			<label class="col-md-2 text-right">Desde:</label>
			<div class="col-md-3">
				<input type="text" class="form-control" name="fi" id="fi">
			</div>
			<label class="col-md-2 text-right">hasta:</label>
			<div class="col-md-3">
				<input type="text" class="form-control" name="ff" id="ff">
			</div>
			<div class="col-md-2">
				<button class="btn btn-primary" onclick="buscar('03')">Generar</button>
			</div>
		</div>


<form id="frmResumen" name="frmResumen" submit="return false">
	<div class="col-md-12">
			<div class="form-group row">
			<label class="col-md-2 text-right">Facturar Por</label>
			 <div class="col-md-3">
				<select class="form-control" id="idemisor" name="idemisor">
					<?php while($fila = $listado->fetch(PDO::FETCH_NAMED)){ ?>
						<option value="<?php echo $fila['id'];?>"><?php echo $fila['razon_social'];?></option>
					<?php } ?>
				</select>
			</div>
			<label class="col-md-2 text-right">Condición</label>
			 <div class="col-md-3">
				<select class="form-control" id="condicion" name="condicion">
					<option value="1">Envio de Resumen</option>
					<option value="2">Actualizar envio</option>
					<option value="3">Dar de baja</option>
				</select>
			</div>
		</div>
		
	</div>
	<input type="hidden" name="accion" value="ENVIO_RESUMEN" />
	<input type="hidden" name="ids" id="ids" value="0" />
<table class="table table-bordered table-hover">
	<thead>
		<tr>
			<th>*</th>
			<th>ID</th>
			<th>Fecha</th>
			<th>Serie</th>
			<th>Correlativo</th>
		</tr>
	</thead>
	<tbody id="html_resumen">
		<?php while($fila = $listadoBoletas->fetch(PDO::FETCH_NAMED)){ ?>
		<tr>
			<td><input type="checkbox" name="documento[]" value="<?php echo $fila['id'];?>" onclick="Marcar(this, '<?php echo $fila['id'];?>')" />
			</td>
			<td><?php echo $fila['id'];?></td>
			<td><?php echo $fila['fecha_emision'];?></td>
			<td><?php echo $fila['serie'];?></td>
			<td><?php echo $fila['correlativo'];?></td>
		</tr>
		<?php } ?>
	</tbody>
</table>
</form>
<div align="right" class="col-md-6">
    <button type="button" class="btn btn-outline-primary btn-block" onclick="EnviarResumenComprobantes()"><i class="fa fa-save"></i> Enviar Comprobantes</button>
</div>
<div id="divResultado">
	
</div>

</div>
</div>
</div>
</div>
<?php 
 include_once "apifacturacion/vistas/layout/footer.php";
?>
<script>
	$(function(){
		$("#fi").datepicker();
        $('#ff').datepicker();
	})
	function EnviarResumenComprobantes() 
	{
	  	var datax = $("#frmResumen").serializeArray();

		$.ajax({
	      method: "POST",
	      url: 'apifacturacion/controlador/controlador.php',
	      data: datax
	  	})
	  	.done(function( html ) {
	        $("#divResultado").html(html);
	  	}); 		
	}

	function Marcar(element,idcomprobante)
	{
		ids = $("#ids").val();
		if($(element).is(':checked')){
			ids = ids+','+idcomprobante+'.0';
			$("#ids").val(ids);
		}else{
			ids = ids.replace(','+idcomprobante+'.0','');
			$("#ids").val(ids);
		}
	}
	function buscar(id)
	{
		if($('#fi').val()!='' && $('#ff').val()!=''){
    var fi = $('#fi').val().split('/');
    var fin = fi[2]+'-'+fi[1]+'-'+fi[0];
    var ff = $('#ff').val().split('/');
    var ffn = ff[2]+'-'+ff[1]+'-'+ff[0];
    $.ajax({
	      method: "POST",
	      url: 'apifacturacion/controlador/controlador.php',
	      data: {
	      	fi:fin,
	      	ff:ffn,
	      	tipo:id,
	      	'accion':'MUESTRA_BAJAS'
	      }
	  	})
	  	.done(function( html ) {
	        $("#html_resumen").html(html);
	  	}); 	
    }
	}

</script>

</body>
</html>