<?php 
include_once('../../config/conexionPDO.php');

Class Registro 
{
	public function guardarAdmin($datos)
	{
		try{
       $sql = "INSERT INTO empleados (id,nom,ape,doc,estado) values (:id,:nombres,:apellidos,:dni,:estado) ";
        
        $parametros = array(
        	':id'=>1,
            ':dni'=>$datos['doc'],
            ':nombres'=>$datos['nombre'],
            ':apellidos'=>$datos['apellido'],
            ':estado'=>1
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
	public function guardarUser(string $pass)
	{
       try{
       $sql = "INSERT INTO user (password,estado,empleados_id,academico_id) values (:pass,:estado,:e,:a) ";
        
        $parametros = array(
            ':pass'=>$pass,
            ':estado'=>1,
            ':e'=>1,
            ':a'=>date('Y')
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
	public function mostrar()
	{
       try{
       $sql = "SELECT *from empleados where id=:id order by id desc ";
         $parametros = array(':id'=>1);
            global $cnx;
            $pre = $cnx->prepare($sql);
            $pre->execute($parametros);
            // $ok = $pre->fetch();
            return $pre;
        }catch(PDOException $e){
            return $e->getMessage();
        }
	}

	public function guardarTipoUsuario()
	{
		try{
       $sql = "INSERT INTO empleado_tipoemple (id_empleado,id_tipo_empleado,nivel_acceso,academico_id) values (:idE,:idTE,:nivel,:a) ";
        
        $parametros = array(
            ':idE'=>1,
            ':idTE'=>8,
            ':nivel'=>8,
            ':a'=>date('Y')
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

	public function apertura()
	{
		$a = date('Y');
		try{
       $sql = "INSERT INTO academico (id,nombre,estado) values (:id,:nombre,:estado) ";
        
        $parametros = array(
            ':id'=>$a,
            ':nombre'=>$a,
            ':estado'=>'a'
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
}

 ?>