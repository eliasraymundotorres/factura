$(document).ready(function(){
    codigo();

  })

$(function () {
  var electronica = $("#boletas").DataTable({
                     "columnDefs": [
                     {
                       "targets":-1,
                       "data":null,
                       "defaultContent":'<div class="btn-group" role="group"><button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><ion-icon name="grid-outline"></ion-icon></button><div class="dropdown-menu" aria-labelledby="btnGroupDrop1"><button class="dropdown-item" title="Imprimir" id="btnImprimir"><ion-icon name="print-outline"></ion-icon></button><button class="dropdown-item" title="Reenviar" id="btnReenviar"><ion-icon name="arrow-redo-outline"></ion-icon></button><button class="dropdown-item" title="Anulación"><ion-icon name="ban-outline"></ion-icon></button><button class="dropdown-item" title="Nota de Credito" id="btnNC"><ion-icon name="document-text-outline"></ion-icon></button><button class="dropdown-item" title="Nota de Debito" id="btnND"><ion-icon name="document-text-outline"></ion-icon></button></div></div>'
                     },
                      {
                       "targets":-2,
                       "data":null,
                       "defaultContent":"<button type='button' class='btn btn-default' id='btnXML'><ion-icon name='document-lock-outline'></ion-icon></button> <button type='button' class='btn btn-warning' id='btnCDR'><ion-icon name='document-text-outline'></ion-icon></button>"
                      }
                     ],
                    "responsive": true, "lengthChange": false, "autoWidth": false,
                    "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
                    "language": {
                      "sProcessing":    "Procesando...",
                      "sLengthMenu":    "Mostrar _MENU_ registros",
                      "sZeroRecords":   "No se encontraron resultados",
                      "sEmptyTable":    "Ningún dato disponible en esta tabla",
                      "sInfo":          "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                      "sInfoEmpty":     "Mostrando registros del 0 al 0 de un total de 0 registros",
                      "sInfoFiltered":  "(filtrado de un total de _MAX_ registros)",
                      "sInfoPostFix":   "",
                      "sSearch":        "Buscar:",
                      "sUrl":           "",
                      "sInfoThousands":  ",",
                      "sLoadingRecords": "Cargando...",
                      "oPaginate": {
                          "sFirst":    "Primero",
                          "sLast":    "Último",
                          "sNext":    "Siguiente",
                          "sPrevious": "Anterior"
                      },
                      "oAria": {
                          "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                          "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                      },
                      "buttons":{
                        "copy": "Copiar",
                        "print": "Imprimir",
                        "colvis": "Columna Visible"
                      }
                   }
                  }).buttons().container().appendTo('#boletas_wrapper .col-md-6:eq(0)');
    
   $.datepicker.regional['es'] = {
       closeText: 'Cerrar',
       prevText: '< Ant',
       nextText: 'Sig >',
       currentText: 'Hoy',
       monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
       monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
       dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
       dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
       dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
       weekHeader: 'Sm',
       dateFormat: 'dd/mm/yy',
       firstDay: 1,
       isRTL: false,
       showMonthAfterYear: false,
       yearSuffix: ''
       };
       $.datepicker.setDefaults($.datepicker.regional['es']); 

  $("#fi").datepicker();
  $('#ff').datepicker();

  });

var fila;

$(document).on('click','#btnFechas',function(){
    
    var fi = $('#fi').val();
    var ff = $('#ff').val();

    /*
    $('#boletas').DataTable().destroy();
    fetch(fi,ff);
    */
    alert(fi+' '+ff);
    
});

$(document).on('click','#btnReenviar',function(){
  
     fila = $(this).closest('tr');
    var venta_id = fila.find('td:eq(1)').text();
    var comprobante = fila.find('td:eq(4)').text();
    var fecha = fila.find('td:eq(2)').text();
    var total = fila.find('td:eq(6)').text();
    
    $.ajax({
    method: 'POST',
    url: 'apifacturacion/controlador/controlador.php',
    data: {id:venta_id,'accion':'COMPROBAR_EMISION'}
    })
    .done(function(datos){
      console.log(datos);
       if (datos==0) {
          $('#modalReenvio').modal('show');

            $('.clsComprobante').html(comprobante);
            $('.clsEmision').html(fecha);
            $('.clsTotal').html(total);
            $('#ventaID').val(venta_id);
            $('#mensajeEnvio').html('');
       } else {
         alertify
            .alert("Aviso!","Este comprobante fue enviada correctamente.", function(){
              alertify.message('OK');
            });
       }
    })
   

});
$(document).on('click','#btnCDR',function(){
  
     fila = $(this).closest('tr');
    var venta_id = fila.find('td:eq(1)').text();
    var comprobante = fila.find('td:eq(4)').text();
    var fecha = fila.find('td:eq(2)').text();
    var total = fila.find('td:eq(6)').text();
    /*
    $.ajax({
    method: 'POST',
    url: 'apifacturacion/controlador/controlador.php',
    data: {id:venta_id,'accion':'GETSTATUSCDR'}
    })
    .done(function(datos){ */
     // console.log(datos);
      // if (datos==0) {
          $('#EnvioCDR').modal('show');

            $('.clsComprobante').html(comprobante);
            $('.clsEmision').html(fecha);
            $('.clsTotal').html(total);
            $('#ventaID').val(venta_id);
            $('#mensajeGetStatus').html('');
      /* } else {
         alertify
            .alert("Aviso!","Este comprobante fue enviada correctamente.", function(){
              alertify.message('OK');
            });
       } 
    })*/
   

});

function EnviarGetStatus()
{
  
    $.ajax({
    method: 'POST',
    url: 'apifacturacion/controlador/controlador.php',
    data: {id:$('#ventaID').val(),'accion':'GETSTATUSCDR'}
    })
    .done(function(datos){ 
     // console.log(datos);
       if (datos!=0) {

            $('#mensajeGetStatus').html(datos);
            
       } else {
         alertify
            .alert("Aviso!","El CDR de este comprobante ya existe. click en Descargar o el comprobante aún no fue aceptado", function(){
              alertify.message('OK');
            });
       } 
    })
}
function DescargarCDR()
{
  

  $.ajax({
    method: 'POST',
    url: 'apifacturacion/controlador/controlador.php',
    data: {id:$('#ventaID').val(),'accion':'CDR'}
    })
    .done(function(datos){ 
     // console.log(datos);
       if(datos==1){
         window.open('apifacturacion/controlador/cdr.php?id='+$('#ventaID').val(),'Ventana de descarga');
       } else {
          alertify
            .alert("Aviso!","El CDR de este comprobante no existe. click en getStatus", function(){
              alertify.message('OK');
            });
       }
    })
  
}

$(document).on('click','#btnImprimir',function(){
    fila = $(this).closest('tr');
    var venta_id = fila.find('td:eq(1)').text();
    $('#idImprimir').val(venta_id);

   $('#modalImprimir').modal('show');

    
})

function imprimirFactura()
{
   let activoFijo = $('input[name="impresa"]:checked').val();
   let id = $('#idImprimir').val();

   if (activoFijo==1) {
            var params  = 'width=350px';
                    params += ', height=720px';
                    params += ', top=25, left=600%';
                    params += ', fullscreen=yes';
                    padre = window.open('./reportes/tk_impresion.php?id='+id, 'OPK', params);
                    padre.focus();
   } else {
       window.open('./reportes/comprobante.php?id='+id,'_blank');
   }
    
}

function ReenviarFactura()
{
  var id = $('#ventaID').val();

  $.ajax({
    method: 'POST',
    url: 'apifacturacion/controlador/controlador.php',
    data: {id:id,'accion':'REENVIAR_FACTURA'},
    beforeSend: function(objeto){
        $('#mensajeEnvio').html('<center>Enviando, espere por favor...</center>');
      },
    success: function(datos) { 
      $('#mensajeEnvio').html(datos);
      } 
  })
 

}
 function ModalEnvio()
  {
    
    $.ajax({
      method: 'POST',
      url: 'apifacturacion/controlador/controlador.php',
      data: {'accion':'SESION_CARRITO'}
    })
    .done(function(datos){
      console.log(datos);
      if(datos==1){
          $('#modalEnvio').modal('show');    
      } else {
        alertify
            .alert("Aviso!","La venta no cuenta con productos o ya fue enviada!.", function(){
              alertify.message('OK');
            });
      }
    })
      
  }
  function enviarFactura()
  {
    /*
    let activoFijo = $('input[name="impresa"]:checked').val();
    $('#voucher').val(activoFijo); */

    GuardarVenta();
    
    $('#modalEnvio').modal('hide');

    $('#comprobantes').modal('show');
  }
  

//============ Cerrar sesion =============
function cerrar()
{
  alertify.confirm("¡Atención!","¿Esta seguro de cerrar el sistema?.",
  function(){
    window.location.href="login/app/cerrar.php";
  },
  function(){
    alertify.error('El sistema no se cerró!');
  }
   );
  
}

//================================

function number_format(number, decimals, dec_point, thousands_point) {

  if (number == null || !isFinite(number)) {
      throw new TypeError("number is not valid");
  }

  if (!decimals) {
      var len = number.toString().split('.').length;
      decimals = len > 1 ? len : 0;
  }

  if (!dec_point) {
      dec_point = '.';
  }

  if (!thousands_point) {
      thousands_point = ',';
  }

  number = parseFloat(number).toFixed(decimals);

  number = number.replace(".", dec_point);

  var splitNum = number.split(dec_point);
  splitNum[0] = splitNum[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousands_point);
  number = splitNum.join(dec_point);

  return number;
}