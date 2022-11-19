<?php 
require_once '../apifacturacion/ado/clsEmisor.php';

$objEmisor = new clsEmisor();

$archivo = $_FILES['file'];

$temp = $archivo['tmp_name'];
$nombre = $archivo['name'];

if(move_uploaded_file($temp, '../apifacturacion/'.$nombre)) {
   $objEmisor->actualizarCertificado($nombre);
}

?>