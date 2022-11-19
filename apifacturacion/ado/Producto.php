<?php 

include_once ('../apifacturacion/ado/conexion.php');

class Producto

{
	public function agregar($datos)
	{
		try{
       $sql = "INSERT INTO producto(codigo,nombre,precio,tipo_precio,codigoafectacion,unidad) values (:codigo,:nombre,:precio,:tipo,:afectacion,:unidad) ";

       $parametros = array(
                 ':codigo'=>$datos['codigo'],
                 ':nombre'=>$datos['nombre'],
                 ':precio'=>$datos['precio'],
                 ':afectacion'=>$datos['afectacion'],
                 ':tipo'=>$datos['tipo'],
                 ':unidad'=>$datos['unidad']
                );
            global $cnx;
            $pre = $cnx->prepare($sql);
            $pre->execute($parametros);
            $ok = $pre->rowCount();
            if ($ok!=0) {
            	return true;
            } else {
            	return false;
            }
            
        }catch(PDOException $e){
            return $e->getMessage();
        }
	}
    public function actualizar($datos)
    {
        try{
       $sql = "UPDATE producto SET nombre=:nombre,precio=:precio,codigoafectacion=:afectacion,unidad=:unidad where codigo=:codigo ";

       $parametros = array(
                 ':codigo'=>$datos['codigo'],
                 ':nombre'=>$datos['nombre'],
                 ':precio'=>$datos['precio'],
                 ':afectacion'=>$datos['afectacion'],
                 ':unidad'=>$datos['unidad']
                );
            global $cnx;
            $pre = $cnx->prepare($sql);
            $pre->execute($parametros);
            $ok = $pre->rowCount();
            if ($ok!=0) {
                return true;
            } else {
                return false;
            }
            
        }catch(PDOException $e){
            return $e->getMessage();
        }
    }
	public function maximo()
	{
		try{
       $sql = "SELECT MAX(codigo) as num from producto limit 1 ";


            global $cnx;
            $pre = $cnx->prepare($sql);
            $pre->execute();
            return $pre;
            
        }catch(PDOException $e){
            return $e->getMessage();
        }
	}
	public function buscando($buscar)
	{
		try{
       $sql = "SELECT * from producto where nombre lIKE :buscar ";

        $parametros = array(':buscar'=>'%'.$buscar.'%');
            global $cnx;
            $pre = $cnx->prepare($sql);
            $pre->execute($parametros);
            return $pre;
            
        }catch(PDOException $e){
            return $e->getMessage();
        }
	}

    public function eliminar($id)
    {
        try{
       $sql = "DELETE FROM producto where codigo=:id ";

        $parametros = array(':id'=>$id);
            global $cnx;
            $pre = $cnx->prepare($sql);
            $pre->execute($parametros);
            return $pre;
            
        }catch(PDOException $e){
            return $e->getMessage();
        }
    }

    public function productoID($id)
    {
        try{
       $sql = "SELECT *FROM producto where codigo=:id ";

        $parametros = array(':id'=>$id);
            global $cnx;
            $pre = $cnx->prepare($sql);
            $pre->execute($parametros);
            return $pre;
            
        }catch(PDOException $e){
            return $e->getMessage();
        }
    }
  
}


?>