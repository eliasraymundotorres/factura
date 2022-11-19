<?php
require_once("../ado/clsEmisor.php");
require_once("../ado/clsVenta.php");

$objEmisor = new clsEmisor();
$objVenta = new clsVenta();

$id = $_GET['id'];

$venta = $objVenta->obtenerComprobanteId($id);
$venta = $venta->fetch(PDO::FETCH_NAMED);

$emisor = $objEmisor->obtenerEmisor($venta['idemisor']);
$emisor = $emisor->fetch(PDO::FETCH_NAMED);


$nombre1 = 'R-'.$emisor['ruc'].'-'.$venta['tipocomp'].'-'.$venta['serie'].'-'.$venta['correlativo'];

$nombre = 'cdr/'.$nombre1.'.XML';

// Creamos un instancia de la clase ZipArchive
 $zip = new ZipArchive();
// Creamos y abrimos un archivo zip temporal
 $zip->open($nombre1.".zip",ZipArchive::CREATE);
 // Añadimos un directorio
 $dir = $emisor['ruc'];
 $zip->addEmptyDir($dir);
 // Añadimos un archivo en la raid del zip.
// $zip->addFile($nombre,'R-'.$nombre1.'.XML');
 //Añadimos un archivo dentro del directorio que hemos creado
 $zip->addFile($nombre,$dir.'/'.$nombre1.'.XML');
 // Una vez añadido los archivos deseados cerramos el zip.
 $zip->close();
 // Creamos las cabezeras que forzaran la descarga del archivo como archivo zip.
 header("Content-type: application/octet-stream");
 header("Content-disposition: attachment; filename=".$nombre1.".zip");
 // leemos el archivo creado
 readfile($nombre1.'.zip');
 // Por último eliminamos el archivo temporal creado
 unlink($nombre1.'.zip');//Destruye el archivo temporal
?>