<?php 
include_once ('../apifacturacion/ado/Unidad.php');
include_once ('../apifacturacion/ado/Producto.php');


class ControladorProducto 
{
	public function validarRegistro($datos)
	{

       $objProducto = new Producto();
        
        $msj = '';
        $op = $datos['opTipo'];

        if($op==1){
        	if ($datos['unidad']=='') {
        		$msj .= 'error';
        	}
        }
        if ($datos['nombre']=='') {
        	  $msj .= 'error';
        }
        if ($datos['precio']=='') {
        	 $msj .= 'error';
        }
        if ($datos['afectacion']=='') {
        	 $msj .= 'error';
        }
        
        if($msj=='') {
        
         $guardar = $objProducto->agregar($datos);
           $guardar = $guardar ? true : false;
           if ($guardar) {
           	 $msj .= '
                 <div class="alert alert-success alert-dismissible fade show" role="alert">
				  <strong>Muy bien!</strong> Se guardó exitosamente
				  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
				    <span aria-hidden="true">&times;</span>
				  </button>
				</div>
           	 ';
           }
             

          } else {
             	$msj = '
             		<div class="alert alert-danger alert-dismissible fade show" role="alert">
				  <strong>Error!</strong> Hay campos vacios debes ingresar para poder continuar!
				  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
				    <span aria-hidden="true">&times;</span>
				  </button>
				</div>
             	';
             }
        
       return $msj;
	}
	public function actualizarRegistro($datos)
	{

       $objProducto = new Producto();
        
        $msj = '';
        if ($datos['nombre']=='') {
        	  $msj .= 'error';
        }
        if ($datos['precio']=='') {
        	 $msj .= 'error';
        }

        
        if($msj=='') {
        
         $guardar = $objProducto->actualizar($datos);
           $guardar = $guardar ? true : false;
           if ($guardar) {
           	 $msj = 1;
           }
             

          } else {
             	$msj = 0;
             }
        
       return $msj;
	}


	public function codigoCorrelativo()
	{
		$objProducto = new Producto();

       $max = $objProducto->maximo();
		$max = $max->fetch();

		$codigo = $max['num']+1;

		return $codigo;
	}

	public function mostrarUnidades($tipo)
	{
		$objUnidad = new Unidad();
        
        $msj = '';

		if ($tipo==1) {
         
	       $mostrar = $objUnidad->listar();

	       $msj .= '<select class="form-control" id="unidad" name="unidad">';

	       foreach ($mostrar as $k => $value) {
	       	 $msj .= '<option value="'.$value['codigo'].'">'.$value['descripcion'].'</option>';
	       }
	        $msj .= '</select>';
      } else {
      	 $msj .= '<select class="form-control" id="unidad" name="unidad" readonly>
       	            <option value="ZZ" selected>--Seleccione unidad--</option>      
              </select>';
      }

      return $msj;

	}
	public function mostrarUnidades2($tipo,$unidad)
	{
		$objUnidad = new Unidad();
        
        $msj = '';

		if ($tipo==1) {
          $msj .= '<script> $("#mod_unidad").val("'.$unidad.'"); </script>';
	       $mostrar = $objUnidad->listar();

	       $msj .= '<select class="form-control" id="mod_unidad" name="mod_unidad">';

	       foreach ($mostrar as $k => $value) {
	       	 $msj .= '<option value="'.$value['codigo'].'">'.$value['descripcion'].'</option>';
	       }
	        $msj .= '</select>';
      } else {
      	 $msj .= '<select class="form-control" id="mod_unidad" name="mod_unidad" readonly>
       	            <option value="ZZ" selected>--Seleccione unidad--</option>      
              </select>';
      }

      return $msj;

	}

	public function buscarProductos(string $buscar)
	{
       $objProducto = new Producto();

	   $msj = '';
       $mostrar = $objProducto->buscando($buscar);
       foreach ($mostrar as $k => $value) {
       	   $msj .= '

               <tr>
                 <td>'.$value['codigo'].'</td>
                 <td>'.$value['nombre'].'</td>
                 <td>'.$value['precio'].'</td>
                 <td>'.$value['unidad'].'</td>
                 <td>
                    <button class="btn btn-danger" onclick="eliminar('.$value['codigo'].')"><ion-icon name="close-outline"></ion-icon></button>
                    <button class="btn btn-primary" onclick="editarProducto('.$value['codigo'].')"><ion-icon name="create-outline"></ion-icon></button>
                 </td>
               </tr>
       	   ';
       }

       return $msj;
	}

	public function eliminarProducto(int $id)
	{
		$objProducto = new Producto();

		$eliminar = $objProducto->eliminar($id);
	      $eliminar = $eliminar->rowCount();
	      if ($eliminar!=0) {
	      	  $i = 1;
	      } else {
	      	$i = 0;
	      }

	      return $i;
	}

	public function editarProducto(int $id)
	{
		$objProducto = new Producto();
     
     $mostrar = $objProducto->productoID($id);
     $mostrar = $mostrar->fetch();


     return json_encode($mostrar);

	}
}

 ?>