<?php 
ini_set('date.timezone','America/Lima');
try{

	$manejador = "mysql";
	$servidor = "localhost";
	$usuario = "root"; // usuario con acceso a la base de datos, generalmente root
	//$pass = "d1847cb8900b0935f6ce9553c3c902e7516947e7a89ad887";// aquí coloca la clave de la base de datos del servidor o hosting
	$pass = "";
	$base = "factura"; //nombre de la base de datos
	$cadena = "$manejador:host=$servidor;dbname=$base";
	$cnx = new PDO($cadena, $usuario, $pass, array(PDO::ATTR_PERSISTENT => "true", PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));

}catch(Exception $ex){
	echo "Error de acceso, informelo a la brevedad.";
	exit;
}

?>