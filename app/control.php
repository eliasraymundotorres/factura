<?php 
include_once ('../apifacturacion/controller/ControladorProducto.php');

$objControladorProducto = new ControladorProducto();

$accion = $_GET['accion'];


switch ($accion) {
	case 'UNIDAD':
	  $tipo = $_GET['tipo'];
     
     $mostrar = $objControladorProducto->mostrarUnidades($tipo);

     echo $mostrar;

		break;
	case 'MOD_UNIDAD':
	  $tipo = $_GET['tipo'];
      $unidad = $_GET['i'];
     $mostrar = $objControladorProducto->mostrarUnidades2($tipo,$unidad);

     echo $mostrar;

		break;
	case 'AGREGAR_PRODUCTO':

	  $codigo = $objControladorProducto->codigoCorrelativo();

	   $datos = array(
			'nombre'=>$_GET['nombre'],
			'precio'=>$_GET['precio'],
			'afectacion'=>$_GET['afectacion'],
			'tipo'=>'01',
			'unidad'=>$_GET['unidad'],
			'codigo'=>$codigo,
			'opTipo'=>$_GET['tipo']
		);

	   $mostrar = $objControladorProducto->validarRegistro($datos);

	   echo $mostrar;

		break;
	case 'ACTUALIZAR_PRODUCTO':
	    
	    $datos = array(
			'nombre'=>$_GET['mod_nombre'],
			'precio'=>$_GET['mod_precio'],
			'afectacion'=>$_GET['mod_afectacion'],
			'unidad'=>$_GET['mod_unidad'],
			'codigo'=>$_GET['mod_codigo']
		);

	  $mostrar = $objControladorProducto->actualizarRegistro($datos);

	   echo $mostrar;

		break;
	case 'CODIGO':
		$codigo = $objControladorProducto->codigoCorrelativo();

		echo $codigo;
		break;
	case 'BUSCAR_PRODUCTO':

		$buscar = $_GET['producto'];
       
    $mostrar = $objControladorProducto->buscarProductos($buscar);

    echo $mostrar;

		break;

		case 'ELIMINAR_PRODUCTO':

			$id = $_GET['producto'];
      
      $eliminar = $objControladorProducto->eliminarProducto($id);

      echo $eliminar;

			break;
	case 'EDITAR_PRODUCTO':
		
		$id = $_GET['id'];

		$mostrar = $objControladorProducto->editarProducto($id);

		echo $mostrar;

		break;
	
	default:
		// code...
		break;
}

 ?>