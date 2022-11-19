<?php 
session_start();
include_once '../../apifacturacion/ado/conexion.php';
include_once '../App/clases/Consultar.php';
//include_once '../App/Controlador/ControladorRegistro.php';

//$objRegistro = new Registro();
//$objControladorRegistro = new ControladorRegistro();

$objConsultar = new Consultar();

$accion = $_POST['accion'];

switch ($accion) {
	case 'CONSULTAR':
    if(isset($_POST['captcha']) && $_POST['captcha'] == $_SESSION['captcha_text']) {
     $datos = array(
       'tipocomp'=>$_POST['tipocomp'],
       'serie'=>$_POST['serie'],
       'numeracion'=>$_POST['numeracion'],
       'fecha_emision'=>$_POST['fecha_emision'],
       'total'=>$_POST['total']
       );
       
       if(!empty($_POST['tipocomp']) && !empty($_POST['serie']) && !empty($_POST['numeracion']) && !empty($_POST['fecha_emision']) && !empty($_POST['total']))
       {
          $comprobar = $objConsultar->Comprobando($datos);
          if($comprobar->rowCount()!=0){
            $comprobar = $comprobar->fetch();
            $id = $comprobar['id'];
           $_SESSION['ventaId'] = $id;
          } else {
            $_SESSION['ventaId'] = 0;  
          }
          
          echo 1;


       } else {
        echo '
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <strong>Error!</strong> Ingrese datos válidos para verificar el comprobante!
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
        ';
       }
     
     } else {
      echo '
          <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>Error captcha!</strong> El texto de validación es incorrecta, ingrese uno nuevo.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
      ';
     }

   break;
	default:
		# code...
		break;
}


 ?>