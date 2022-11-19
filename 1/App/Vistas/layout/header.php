<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Configuracion principal</title>
  <!-- ================== LIBRERIAS DE BOOSTRAP ==================== -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css" >
  <!--===============================================================-->
  <style>
    body{padding-top:100px}
    .abs-center {
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 10vh;
}
  </style>
</head>
<body>

<div class="container">
<?php 
$emisor = $objConsultar->emisor();
$emisor = $emisor->fetch();
?>
<nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-primary">
  <a class="navbar-brand" href="?bienvenido"><?=$emisor['razon_social']?></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

</nav>

<br>

