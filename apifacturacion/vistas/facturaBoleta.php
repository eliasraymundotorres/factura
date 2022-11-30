<?php 
include_once 'apifacturacion/ado/clsVenta.php';

$objVenta = new clsVenta();

if(isset($_POST['fi']) && isset($_POST['ff']) && $_POST['fi']!='' && $_POST['ff']){
  $fechainicio = explode('/',$_POST['fi']);
  $fi=$fechainicio[2].'-'.$fechainicio[1].'-'.$fechainicio[0];
  $fechafinal = explode('/',$_POST['ff']);
  $ff=$fechafinal[2].'-'.$fechafinal[1].'-'.$fechafinal[0];
} else {
  $fi='';
  $ff='';
}
 ?>

<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">

<div class="content-wrapper">
<br>
         <div class="col-md-12">
            <form method="post" action="?FacturasBoletas">
                 <div class="form-group row">
              			<label class="label-control col-md-2 float-right text-right">Desde</label>
              			<div class="col-md-3">
              				<input type="text" class="form-control" name="fi" id="fi">
              			</div>
              			<label class="label-control col-md-2 text-right">Hasta</label>
              			<div class="col-md-3">
              				<input type="text" class="form-control" name="ff" id="ff">
              			</div>
              			<div class="col-md-2">
              				<button class="btn btn-success" >Filtrar</button>
              			</div>
              			
              		</div>
              	</form>	
         </div>
    <?php if( $_SESSION['tipo'] == 3 ) { ?>     
         <div class="col-md-3">
           <form method="post" action="?FacturasBoletas">
            <div class="form-group">
              <label>Usuarios</label>
              <div class="input-group">
                <select class="custom-select" id="user" name="user">
                  <option selected>[Seleccione usuario]</option>
                  <?php 
                    $listar = $objVenta->listaUsuarios();
                    foreach ($listar as $k => $usuario) {
                      echo '
                          <option value="'.$usuario['id'].'">'.$usuario['nombre'].'</option>
                      ';
                    }
                  ?>
                </select>
                <div class="input-group-append">
                  <button class="btn btn-outline-secondary"><i class="fa fa-search"></i></button>
                </div>
              </div>
            </div>
           </form>
         </div>
    <?php } ?>   
        <div class="card">
              <div class="card-header">
                <h3 class="card-title">Comprobantes enviados a SUNAT</h3>
                <p class="card-text">
                	Estados Sunat: <ion-icon name="checkmark-outline"></ion-icon> Aceptado <ion-icon name="ban-outline"></ion-icon> Rechazado <ion-icon name="arrow-redo-outline"></ion-icon> Pendiente <ion-icon name="close-outline"></ion-icon> Comunicacion de baja(Anulado) <ion-icon name="construct-outline"></ion-icon> Problemas de conexión
                </p>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
              	
              		
              	
                <table id="boletas" class="table table-bordered table-striped table-hover">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Cod Venta</th>
                      <th>Fecha de Emisión</th>
                      <th>Cliente/Razón Social</th>
                      <th>Número</th>
                      <th>Moneda</th>
                      <th>Total</th>
                      <th>Saldo</th>
                      <th>Estado</th>
                      <th>Descargar</th>
                      <th>Acciones</th>
                   </tr>
                  </thead>
                  <tbody>
                    <?php 
                    if(isset($_POST['fi']) && isset($_POST['ff']) && $_POST['fi']!='' && $_POST['ff']){
                      $mostrar = $objVenta->mostrarVentasPorFecha($fi,$ff);
                     } elseif (isset($_POST['user'])) {
                        $mostrar = $objVenta->mostrarVentasPorUsuario($_POST['user']);
                     } else {
                        $mostrar = $objVenta->mostrarVentas();
                     }
                    foreach ($mostrar as $k => $value) {
                      $estado = $value['feestado'];
                      switch ($estado) {
                        case '1'://Enviado correctamente
                          $e = '<ion-icon name="checkmark-outline"></ion-icon>';
                          $saldo = $value['total'];
                          break;
                        case '2':// Rechazado
                          $e = '<ion-icon name="ban-outline"></ion-icon>';
                          $saldo = 0.00;
                          break;
                        case '0': //Pendiente
                          $e = '<ion-icon name="arrow-redo-outline"></ion-icon>';
                          $saldo = 0.00;
                          break;
                        case '3'://Problemas de conexion
                          $e = '<ion-icon name="construct-outline"></ion-icon>';
                          $saldo = 0.00;
                          break;
                        case '4'://Anulado
                          $e = '<ion-icon name="close-outline"></ion-icon>';
                          $saldo = 0.00;
                          break;
                        default:
                          $e = '';
                          $saldo = 0.00;
                          break;
                      }
                    
                     
                  echo '
                       <tr>
                            <td>'.($k+1).'</td>
                            <td>'.$value['id'].'</td>
                            <td>'.date('d/m/Y', strtotime($value['fecha_emision'])).'</td>
                            <td>'.$value['razon_social'].'<br>'.$value['nrodoc'].'</td>
                            <td>'.$value['serie'].'-'.$value['correlativo'].'</td>
                            <td>'.$value['codmoneda'].'</td>
                            <td>'.$value['total'].'</td>
                            <td>'.$saldo.'</td>
                            <td>'.$e.'</td>
                            <td></td>
                            <td></td>
                       </tr>';
                      } 
                   ?>

                  </tbody>
                  <tfoot>
                  <tr>
                    <th>#</th>
                    <th>Cod Venta</th>
                    <th>Fecha de Emisión</th>
                    <th>Cliente/Razón Social</th>
                    <th>Número</th>
                    <th>Moneda</th>
                    <th>Total</th>
                    <th>Saldo</th>
                    <th>Estado</th>
                    <th>Descargar</th>
                    <th>Acciones</th>
                  </tr>
                  </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
    </div>
            <!-- /.card -->

  </div>
</div>
<?php 
 include_once "apifacturacion/vistas/layout/footer.php";
?>


</body>
</html>