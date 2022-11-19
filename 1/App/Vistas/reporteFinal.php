<?php 
if(!isset($_SESSION['ventaId']) && $_SESSION['ventaId'] == ''){
	header('Location: ?bienvenido');
	exit;
}
$id = $_SESSION['ventaId'];

$venta = $objConsultar->listarVenta($id);

?>

<div class="container">

<?php 
 if($venta->rowCount()!=0){
   $venta = $venta->fetch();

   $nombre = $emisor['ruc'].'-'.$venta['tipocomp'].'-'.$venta['serie'].'-'.$venta['correlativo'];

   $nombre = '../apifacturacion/controlador/cdr/R-'.$nombre.'.XML';

   if(!file_exists($nombre)) {
   	$url = '';
   } else {
   	$url = '';
   }
  ?>
  <h4><p>El comprobante <?=$venta['serie'].'-'.$venta['correlativo']?> fue encontrado, puede descargar los documentos</p></h4>   

<table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Descripción</th>
      <th scope="col">Descargar</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row">1</th>
      <td>Reporte del comprobante <?=$venta['serie'].'-'.$venta['correlativo']?></td>
      <td><a href="javascript:descargar(<?=$venta['id']?>)"><i class="fa-solid fa-down-to-line"></i></a></td>
    </tr>
    <tr>
      <th scope="row">2</th>
      <td>XML del comprobante <?=$venta['serie'].'-'.$venta['correlativo']?></td>
      <td><a href="javascript:DescargarCDR(<?=$venta['id']?>)"><i class="fa-solid fa-down-to-line"></i></a></td>
    </tr>
  </tbody>
</table>
<br>
<a href="?bienvenido" class="btn btn-primary">Regresar</a>

 <?php 




 } else {
?>
 <div class="alert alert-success" role="alert">
  <h4 class="alert-heading">No encontrado!</h4>
  <p>El comprobante no existe en este sistema, verifique nuevamente los datos e ingrese nuevamente.</p>
  <hr>
  <p class="mb-0">Si Ud. cree que los datos del comprobante esta correcto, comuniquese con su proveedor para darle solución!</p>
  <br>
<a href="?bienvenido" class="btn btn-primary">Regresar</a>
</div>
<?php


 }

?>



</div>