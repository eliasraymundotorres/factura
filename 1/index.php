<?php
session_start();
 include_once '../apifacturacion/ado/conexion.php';
 include_once 'App/clases/Consultar.php';
$objConsultar = new Consultar();

 $request = $_SERVER["QUERY_STRING"];

 include_once "App/Vistas/layout/header.php";
 
   
  switch ($request) {
      case 'bienvenido':
      session_destroy();
        include_once "App/Vistas/inicio.php";
           break;
      case 'verificado':
        include_once "App/Vistas/reporteFinal.php";
        break;
 
      default:
      session_destroy();
          include_once "App/Vistas/inicio.php";
          break;
  }  
 
 include_once "App/Vistas/layout/footer.php";


 ?>

