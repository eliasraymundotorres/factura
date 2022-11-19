<?php 
include_once ('apifacturacion/ado/clsEmisor.php');
include_once ('apifacturacion/ado/clsCompartido.php');

$objEmisor = new clsEmisor();
$objCompartido = new clsCompartido();

$contrato = $objCompartido->fechaContrato();
$contrato = $contrato->fetch();
 ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Contribuyente</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="?bienvenido">Inicio</a></li>
              <li class="breadcrumb-item active">Contribuyente</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
  <!-- Content Wrapper. Contains page content -->
  <div class="container-fluid">

    <center><h1>COMPROBANTE ELECTRÓNICO</h1></center>
     
  
	  <div class="card">
	  <div class="card-header">
	    DATOS DE LA EMPRESA
	  </div>
	  <div class="card-body">
	  	<div class="table-responsive">
	    <table class="table table-bordered">
	    	<thead>
	    		<th>Codigo</th>
	    		<th>Ruc</th>
	    		<th>Razón social</th>
	    		<th>Tipo</th>
	    		<th>Ubigeo</th>
	    		<th>Pais</th>
	    		<th>Dirección</th>
	    		<th>Departamento</th>
	    		<th>Provincia</th>
	    		<th>Distrito</th>
	    		<th>Nombre Comercial</th>
	    	</thead>
	    	<tbody>
	    		<?php 
                 $mostrar = $objEmisor->obtenerEmisor(1);
                 foreach ($mostrar as $k => $value) {
                 	echo '
                 	  <tr>
                 	    <td>'.($k+1).'</td>
                 	    <td>'.$value['ruc'].'</td>
                 	    <td>'.$value['razon_social'].'</td>
                 	    <td>'.$value['tipodoc'].'</td>
                 	    <td>'.$value['ubigeo'].'</td>
                 	    <td>'.$value['pais'].'</td>
                 	    <td>'.$value['direccion'].'</td>
                 	    <td>'.$value['departamento'].'</td>
                 	    <td>'.$value['provincia'].'</td>
                 	    <td>'.$value['distrito'].'</td>
                 	    <td>'.$value['nombre_comercial'].'</td>
                 	  </tr>
                 	';
                 }
	    		 ?>
	    	</tbody>
	    </table>
	  </div>
	    <br><br>
	    	<h5 class="card-title">DATOS DEL CERTIFICADO</h5>
	    	<p class="card-text">
	    	Fecha de vencimiento: ......
	       </p>

	       <br>
	       <h5 class="card-title">LICENCIA DEL SISTEMA</h5>
	        <p class="card-text">
	    	   <strong>Fecha de Instalación:</strong> <?=date('d/m/Y',strtotime($contrato['fecha_inicio']))?> <br>
	    	   <strong>Fecha de Vencimiento:</strong> <?=date('d/m/Y',strtotime($contrato['fecha_final']))?>
	       </p>
	    
	  </div>
	</div>


  </div>

</div>
  <!-- /.content-wrapper -->

  <?php 
  include_once "apifacturacion/vistas/layout/footer.php";
  ?>
  
  </body>
  </html>