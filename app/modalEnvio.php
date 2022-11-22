
<div class="modal fade" id="modalEnvio" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content bg-primary">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Atención!</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

          <p>¿Desea emitir el comprobante?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Salir <ion-icon name="close-circle-outline"></ion-icon></button>
        <button type="button" class="btn btn-warning" onclick="enviarFactura()">Enviar <ion-icon name="enter-outline"></ion-icon></button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="comprobantes">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
		<div class="modal-header">
			<h4 class="modal-title">Comprobantes de envio</h4>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<div class="modal-body">
		   <div class="card card-primary card-outline card-outline-tabs">
              <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="custom-tabs-four-home-tab" data-toggle="pill" href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home" aria-selected="true">Voucher</a>
                  </li>

                </ul>
              </div>
              <div class="card-body">
                <div class="tab-content" id="custom-tabs-four-tabContent">
                  <div class="tab-pane fade show active" id="custom-tabs-four-home" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
                     <div id="voucher2"></div>
                  </div>

                </div>
              </div>
              <!-- /.card -->
		</div>
		 <div class="modal-footer justify-content-between">
			<button type="button" class="btn btn-default" data-dismiss="modal">Salir</button>
		 </div>
		</div>
		<!-- /.modal-content -->
  </div>
</div>
<!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade" id="modalReenvio" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Reenviar un Comprobante</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        

      	<div class="card">
				  <div class="card-body">
				    <p class="card-text">
				    	<input type="hidden" name="ventaID" id="ventaID">
				    	<ul class="list-group">
							  <li class="list-group-item"><strong>Comprobante:</strong>			 <span class="clsComprobante"></span></li>
							  <li class="list-group-item"><strong>Fecha de Emisión:</strong>  <span class="clsEmision"></span></li>
							  <li class="list-group-item"><strong>Total:	S/ </strong> <span class="clsTotal"></span></li>
							</ul>
				    </p>
				    <button type="button" class="btn btn-success" onclick="ReenviarFactura()"><ion-icon name="refresh-outline"></ion-icon> Enviar</button>
				  </div>
				  <div id="mensajeEnvio"></div>
		   </div>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Salir</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalImprimir" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Seleccione la opción de envio</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        

      	<div class="card">
		  <div class="card-body">
		    <h5 class="card-title">Como desea imprimir</h5>
		    <p class="card-text">
		    	<input type="hidden" name="idImprimir" id="idImprimir">
		    	<div class="form-check">
				  <input class="form-check-input" type="radio" name="impresa" id="impresa" value="1" checked>
				  <label class="form-check-label col-md-4" for="impresa">
				    Ticketera
				  </label>

				  <input class="form-check-input" type="radio" name="impresa" id="impresa" value="2">
				  <label class="form-check-label col-md-4" for="impresa">
				    Formato A4
				  </label>
				</div>
		    </p>
		    
		  </div>
		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Salir</button>
        <button type="button" class="btn btn-primary" onclick="imprimirFactura()">Imprimir</button>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="EnvioCDR" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">CDR - XML</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        

      	<div class="card">
				  <div class="card-body">
				    <p class="card-text">
				    	<input type="hidden" name="ventaID" id="ventaID">
				    	<ul class="list-group">
							  <li class="list-group-item"><strong>Comprobante:</strong>			 <span class="clsComprobante"></span></li>
							  <li class="list-group-item"><strong>Fecha de Emisión:</strong>  <span class="clsEmision"></span></li>
							  <li class="list-group-item"><strong>Total:	S/ </strong> <span class="clsTotal"></span></li>
							</ul>
				    </p>
				    <button type="button" class="btn btn-warning" onclick="DescargarCDR()"><ion-icon name="document-text-outline"></ion-icon> Descargar</button>
				    <p class="card-text">Si no existe el CDR, genere un GetStatus</p>
				    <button type="button" class="btn btn-success" onclick="EnviarGetStatus()"><ion-icon name="refresh-outline"></ion-icon> GetStatus</button>
				  </div>
				  <div id="mensajeGetStatus"></div>
		   </div>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Salir</button>
      </div>
    </div>
  </div>
</div>