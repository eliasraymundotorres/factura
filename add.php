<?php 
include_once 'apifacturacion/ado/conexion.php';
include_once 'apifacturacion/ado/clsCompartido.php';

$objclsCompartido = new clsCompartido();

$datos = array(
    'nombre'=>$_GET['nombre'],
    'user'=>$_GET['user'],
    'clave'=>$_GET['clave'],
    'estado'=>$_GET['estado'],
    'tipo'=>$_GET['tipo']
);

$guardar = $objclsCompartido->agregarUsuario($datos);
$guardar = $guardar->rowCount();

if($guardar>0) {
    echo 'Se guardó exitosamente';
}
else {
    echo 'No se pudo guardar';
}


?>