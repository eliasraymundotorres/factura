<div class="abs-center">

<div class="col-sm-5">

<div class="card">
  <div class="card-header text-center"><h2>Buscar Comprobante</h2></div>
    <div class="card-body">
      <form id="addForm">
        <div class="form-group row">
          <div class="col-sm-12">
          <label for="" class="col-form-label">Tipo de comprobante</label> 
          <select class="form-control" name="tipocomp" id="tipocomp">
            <option value="" selected disabled>--Elegir--</option>
            <?php 
            $mostrar = $objConsultar->listarComprobantes();
            foreach ($mostrar as  $value) {
              echo '<option value="'.$value['codigo'].'">'.$value['descripcion'].'</option>';
            }
            ?>
            
            
          </select>
        </div>
        </div>
        <div class="form-group row">
         <div class="col-sm-6">
        <label for="serie" class="col-form-label">Serie</label> 
       
          <input type="text" class="form-control" name="serie" id="serie" required>
        </div>
        <div class="col-sm-6">
        <label for="numeracion" class="col-form-label">Numeracion</label> 

          <input type="number" class="form-control" name="numeracion" id="numeracion" required>
        </div>
      </div>
      <div class="form-group row">
        <div class="col-sm-6">
        <label for="fecha_emision" class="col-form-label">Fecha de emisión</label> 
       
          <input type="date" class="form-control" name="fecha_emision" id="fecha_emision" value="<?=date('Y-m-d')?>" required>
        </div>
        <div class="col-sm-6">
        <label for="total" class="col-form-label">Total</label> 

          <input type="text" class="form-control" name="total" id="total" required>
        </div>
      </div>
      <div class="form-group row">
       <div class="abs-center">
        <div class="col-sm-12">
           <label for="" class="form-label">Captcha</label> <br>
           <img src="assets/captcha.php" alt="CAPTCHA" class="captcha-image"><i class="fa-solid fa-arrows-rotate" onclick="actualizar()" style="font-size: 30px;"></i>
          <br><br>
          <input type="text" class="form-control" id="captcha" name="captcha" pattern="[A-Z]{6}" required>
        </div>

      </div>
      </div>
      <input type="hidden" name="accion" value="CONSULTAR">
      </form>

  <p class="float-right"><button type="submit" class="btn btn-primary" id="btnConsultar"> Consultar</button></p>
  


      
      </div>
      <div id="reporte"></div>
    </div>
  </div>

  
</div>
