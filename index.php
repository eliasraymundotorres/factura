<?php
@session_start(); 
  if($_SESSION['user']=='' or $_SESSION['user']==null){
     header("Location: login/");
  }

 $request = $_SERVER["QUERY_STRING"];

 include_once "apifacturacion/vistas/layout/header.php";
 
   
  switch ($request) {
      case 'bienvenido':
        include_once "apifacturacion/vistas/dash.php";
           break;
      case 'config':
        include_once "apifacturacion/vistas/configuracion.php";
        break;
      case 'contribuyente':
        include_once "apifacturacion/vistas/inicio.php";
        break;
      case 'EnvioFactura':
        include_once "apifacturacion/vistas/factura.php";
        break;
      case 'EnvioBoleta':
        include_once "apifacturacion/vistas/boleta.php";
        break;
      case 'EnvioNC':
        include_once "apifacturacion/vistas/nota_credito.php";
        break;
      case 'EnvioND':
        include_once "apifacturacion/vistas/nota_debito.php";
        break;
      case 'EnvioBajas':
        include_once "apifacturacion/vistas/envio_bajas.php";
        break;
      case 'EnvioResumen':
        include_once "apifacturacion/vistas/envio_resumen.php";
        break;
      case 'catalogos':
        include_once "apifacturacion/vistas/AddProductos.php";
        break;
      case 'FacturasBoletas':
        include_once "apifacturacion/vistas/facturaBoleta.php";
        break;
    
 
      default:
         include_once "apifacturacion/vistas/dash.php";
          break;
  }  
 
 


 ?>




