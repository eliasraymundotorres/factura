<?php 
require_once("apifacturacion/ado/clsEmisor.php");
require_once("apifacturacion/ado/clsCompartido.php");
require_once("apifacturacion/ado/clsVenta.php");

$objEmisor = new clsEmisor();
$objCompartido = new clsCompartido();
$objVenta = new clsVenta();

$serie = $objCompartido->verificarSerie();
$serie = $serie->rowCount();

$emisor = $objEmisor->consultarListaEmisores();
$emisor = $emisor->fetch();

if (file_exists('apifacturacion/'.$emisor['certificado']) && !empty($emisor['certificado'])) {
  $certificado = 'checked';
} else {
  $certificado = '';
}
if (!empty($emisor['clave_certificado'])) {
  $clave_certificado = 'checked';
} else {
  $clave_certificado = '';
}
if (!empty($emisor['usuario_sol'])) {
  $usuario_sol = 'checked';
} else {
  $usuario_sol = '';
}
if (!empty($emisor['clave_sol'])) {
  $clave_sol = 'checked';
} else {
  $clave_sol = '';
}
if ($emisor['servidor']==1) {
  $servidor = 'checked';
} else {
  $servidor = '';
}

/** Ultimo dia del mes actual **/
function ultimoDia() { 
  $month = date('m');
  $year = date('Y');
  $day = date("d", mktime(0,0,0, $month+1, 0, $year));

  return date('Y-m-d', mktime(0,0,0, $month, $day, $year));
};

/** Primer dia del mes actual **/
function primerDia() {
  $month = date('m');
  $year = date('Y');
  return date('Y-m-d', mktime(0,0,0, $month, 1, $year));
}

$reporteVentas = $objVenta->reportePorEstado(1,primerDia(),ultimoDia());
$reporteVentas = $reporteVentas->fetch();
$reporteLimite = $objVenta->reporteCantidadEnvio(1,primerDia(),ultimoDia());
$reporteLimite = $reporteLimite->rowCount();
$reporteLimite1 = $objVenta->reporteCantidadEnvio(4,primerDia(),ultimoDia());
$reporteLimite1 = $reporteLimite1->rowCount();

$reportePendiente = $objVenta->reporteCantidadEnvio(0,primerDia(),ultimoDia());
$reportePendiente = $reportePendiente->rowCount();
$reportePendiente1 = $objVenta->reporteCantidadEnvio(2,primerDia(),ultimoDia());
$reportePendiente1 = $reportePendiente1->rowCount();
$reportePendiente2 = $objVenta->reporteCantidadEnvio(3,primerDia(),ultimoDia());
$reportePendiente2 = $reportePendiente2->rowCount();
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3>S/ <?=number_format($reporteVentas['monto'],2,'.',',')?></h3>

                <p>Ventas este mes</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a href="?EnvioBoleta" class="small-box-footer">Más información <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3><?=round(($reporteLimite+$reporteLimite1)*100/$emisor['limitecomp'])?><sup style="font-size: 20px">%</sup></h3>
                <p>Enviados en el mes</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <a href="#" class="small-box-footer">Más información <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3><?=$reporteLimite1?></h3>

                <p>Comprobantes anulados</p>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
              <a href="#" class="small-box-footer">Más información <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3><?=($reportePendiente+$reportePendiente1+$reportePendiente2)?></h3>

                <p>Boletas por enviar en el mes</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              <a href="?EnvioResumen" class="small-box-footer">Más información <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
        </div>
        <!-- /.row -->
        
        <!-- Main row -->
        <div class="row">
          
       
          
          <!-- right col (We are only adding the ID to make the widgets sortable)-->
          <section class="col-lg-12 connectedSortable">


            <!-- solid sales graph -->
            <div class="card bg-gradient-info">
              <div class="card-header border-0">
                <h3 class="card-title">
                  <i class="fas fa-th mr-1"></i>
                  Ventas en gráfico
                </h3>

                <div class="card-tools">
                  <button type="button" class="btn bg-info btn-sm" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn bg-info btn-sm" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <canvas class="chart" id="line-chart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <!-- /.card-body -->
              <div class="card-footer bg-transparent">
                <div class="row">
                  <div class="col-4 text-center">
                    <input type="text" class="knob" data-readonly="true" value="30" data-width="60" data-height="60"
                           data-fgColor="#39CCCC">

                    <div class="text-white">Pedidos por correo</div>
                  </div>
                  <!-- ./col -->
                  <div class="col-4 text-center">
                    <input type="text" class="knob" data-readonly="true" value="50" data-width="60" data-height="60"
                           data-fgColor="#39CCCC">

                    <div class="text-white">En línea</div>
                  </div>
                  <!-- ./col -->
                  <div class="col-4 text-center">
                    <input type="text" class="knob" data-readonly="true" value="30" data-width="60" data-height="60"
                           data-fgColor="#39CCCC">

                    <div class="text-white">En el almacén</div>
                  </div>
                  <!-- ./col -->
                </div>
                <!-- /.row -->
              </div>
              <!-- /.card-footer -->
            </div>
            <!-- /.card -->
           
             <!-- TO DO List -->
             <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="ion ion-clipboard mr-1"></i>
                  Opciones configurada en el sistema (Si estan tachadas están instaladas)
                </h3>


              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <ul class="todo-list" data-widget="todo-list">
                  <li>

                    <div  class="icheck-primary d-inline ml-2">
                      <input type="checkbox" value="" name="todo1" id="todoCheck1" disabled <?=$certificado?>>
                      <label for="todoCheck1"></label>
                    </div>
                    <!-- todo text -->
                    <span class="text">Certificado digital</span>

                  </li>
                  <li>
                    <div  class="icheck-primary d-inline ml-2">
                      <input type="checkbox" value="" name="todo2" id="todoCheck2" disabled <?=$clave_certificado?>>
                      <label for="todoCheck2"></label>
                    </div>
                    <span class="text">Clave de certificado digital</span>
                  </li>
                  <li>
                    <div  class="icheck-primary d-inline ml-2">
                      <input type="checkbox" value="" name="todo3" id="todoCheck3" disabled <?=$servidor?>
                      <label for="todoCheck3"></label>
                    </div>
                    <span class="text">Servidor oficial a sunat</span>
                  </li>
                  <li>
                    <div  class="icheck-primary d-inline ml-2">
                      <input type="checkbox" value="" name="todo4" id="todoCheck4" disabled <?=$usuario_sol?>>
                      <label for="todoCheck4"></label>
                    </div>
                    <span class="text">Usuario secundario</span>
                  </li>
                  <li>
                    <div  class="icheck-primary d-inline ml-2">
                      <input type="checkbox" value="" name="todo5" id="todoCheck5" disabled <?=$clave_sol?>>
                      <label for="todoCheck5"></label>
                    </div>
                    <span class="text">Clave secundario</span>
                  </li>
                  <li>
                    <div  class="icheck-primary d-inline ml-2">
                      <input type="checkbox" value="" name="todo6" id="todoCheck6" disabled <?=$serie>0 ? 'checked' : ''?> >
                      <label for="todoCheck6"></label>
                    </div>
                    <span class="text">Series registradas</span>
                  </li>
                </ul>
              </div>

            </div>
            <!-- /.card -->
            
          </section>
          <!-- right col -->
        </div>
        <!-- /.row (main row) -->
      </div><!-- /.container-fluid -->
</div>
<?php 
include_once "apifacturacion/vistas/layout/footer.php";
?>
<script>

</script>


</body>

</html>