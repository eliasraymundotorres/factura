<?php
session_start();
include_once ('../apifacturacion/ado/Producto.php');

$objProducto = new Producto();

$accion = $_GET['accion'];

switch ($accion) {
    case 'BUSCAR_PRODUCTO':
        $producto = $_GET['filtro'];
        if($_SESSION['id']==1) {
            $is = 1;
        } else {
            $is = 0;
        }
        // Iniciar llamada a API
        $curl = curl_init();

        // Buscar producto
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://apis.grupotecom.com/hrhv/productos?is_product='.$is.'&producto=' . $producto,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 2,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        if(!empty($producto) or $producto!='' or $producto!=null) {
        // Datos listos para usar
        $persona = json_decode($response);
        
        echo json_encode($persona);
        }
        break;
    case 'CODIGO_PRODUCTO':

        $producto = $_GET['codigo'];

        // Iniciar llamada a API
        $curl = curl_init();

        // Buscar producto
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://apis.grupotecom.com/hrhv/productos?codigo=' . $producto,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 2,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        // Datos listos para usar
        $persona = json_decode($response);
       
        $datos = array(
            'codigo'=>$persona->result[0]->id,
            'nombre'=>$persona->result[0]->descripcion,
            'precio'=>$persona->result[0]->precio_venta01,
            'afectacion'=>$persona->result[0]->tipo_afectacion,
            'tipo'=>'01',
            'unidad'=>$persona->result[0]->unidad_medida
        );
        $comprobar = $objProducto->productoID($producto);
        if($comprobar->rowCount()==0) {
            $objProducto->agregar($datos);
        } else {
            $objProducto->actualizar($datos);
        }
        
        break;
    default:
        # code...
        break;
}

?>