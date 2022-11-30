<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>Facturación | Intrasoft</title>

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- IonIcons -->
  <link rel="stylesheet" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <!-- ALERTIFY CSS -->
  <link rel="stylesheet" type="text/css" href="dist/alertify/css/alertify.css">
  <link rel="stylesheet" type="text/css" href="dist/alertify/css/themes/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="dist/alertify/css/themes/default.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
 <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
 <!-- dropzonejs -->
 <link rel="stylesheet" href="plugins/dropzone/min/dropzone.min.css">

  <!-- ICONOS --->
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
  
   <!-- ALERTIFY JS -->
   <script type="text/javascript" src="dist/alertify/alertify.min.js"></script>
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <link rel="stylesheet" href="dist/css/style.css">
</head>

<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      
    </ul>

    <!-- SEARCH FORM -->
    <form class="form-inline ml-3">
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" type="search" placeholder="Buscar" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" type="submit">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </form>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      
    
      <li class="nav-item">
        <?=$_SESSION['nombre']?>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="javascript:cerrar()" role="button">
          <i class="fas fa-user"></i></a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index.php?bienvenido" class="brand-link">
      <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">Facturación</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="?bienvenido" class="d-block"><?=$_SESSION['nombre']?></a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="?bienvenido" onclick="" class="nav-link <?php if($request=='bienvenido'){ echo 'active'; } ?> ">
              <i class="nav-icon fas fa-file"></i>
              <p>Dashboard</p>
            </a>
          </li>

          <li class="nav-header">Inicio</li>
            
           <li class="nav-item">
              <a href="?catalogos" onclick="" class="nav-link <?php if($request=='catalogos'){ echo 'active'; } ?> ">
                <i class="nav-icon fas fa-file"></i>
                <p>Catálogo</p>
              </a>
            </li>
            <?php if($_SESSION['id']==1) { ?>
            <li class="nav-item">
              <a href="?config" onclick="" class="nav-link <?php if($request=='config'){ echo 'active'; } ?>">
                <i class="nav-icon fas fa-file"></i>
                <p>Configuración</p>
              </a>
            </li>
            <?php } ?>
            <li class="nav-item">
              <a href="?contribuyente" onclick="" class="nav-link <?php if($request=='contribuyente'){ echo 'active'; } ?> ">
                <i class="nav-icon fas fa-home"></i>
                <p>Contribuyente</p>
              </a>
            </li>
    <?php if( $_SESSION['tipo'] != 3 )  { ?>
          <li class="nav-header">Facturación Electrónica</li>

          
          <li class="nav-item">
            <a href="?EnvioFactura" class="nav-link <?php if($request=='EnvioFactura'){ echo 'active'; } ?>">
              <i class="nav-icon fas fa-file"></i>
              <p>Factura</p>
            </a>
          </li>
          
          <li class="nav-item">
            <a href="?EnvioBoleta" class="nav-link <?php if($request=='EnvioBoleta'){ echo 'active'; } ?>">
              <i class="nav-icon fas fa-file"></i>
              <p>Boleta</p>
            </a>
          </li>
          <?php if( strpos($_SESSION['user'], "admin") !== false ) { ?>
          <li class="nav-item">
            <a href="?EnvioNC" class="nav-link <?php if($request=='EnvioNC'){ echo 'active'; } ?>">
              <i class="nav-icon fas fa-file"></i>
              <p>Nota Credito</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="?EnvioND" class="nav-link <?php if($request=='EnvioND'){ echo 'active'; } ?>">
              <i class="nav-icon fas fa-file"></i>
              <p>Nota Debito</p>
            </a>
          </li>
          <?php } ?>
          <li class="nav-item">
            <a href="?EnvioResumen" class="nav-link <?php if($request=='EnvioResumen'){ echo 'active'; } ?>">
              <i class="nav-icon fas fa-list"></i>
              <p>Envío de Resúmenes</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="?EnvioBajas" class="nav-link <?php if($request=='EnvioBajas'){ echo 'active'; } ?>">
              <i class="nav-icon fas fa-list"></i>
              <p>Baja de Facturas</p>
            </a>
          </li> 
    <?php } ?>      
          
          <li class="nav-item <?php if($request=='FacturasBoletas'){ echo 'menu-open'; } ?>">
            <a href="#" class="nav-link <?php if($request=='FacturasBoletas'){ echo 'active'; } ?>">
              <i class="nav-icon far fa-envelope"></i>
              <p>
                Comprobantes
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="?FacturasBoletas" class="nav-link <?php if($request=='FacturasBoletas'){ echo 'active'; } ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Factura y Boleta</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Nota de crédito</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Nota de débito</p>
                </a>
              </li>
            </ul>
          </li>
  
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>