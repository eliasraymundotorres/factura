

function actualizar()
{
  document.querySelector(".captcha-image").src = 'assets/captcha.php?' + Date.now();
}

$(document).ready(function(){


$(document).on('click','#btnConsultar',function(){
    var dato = $('#addForm').serializeArray();

     $.ajax({
        method: 'POST',
        url: 'includes/ajax.php',
        data: dato,
        beforeSend: function(obj){
          $('#btnConsultar').html('Consultando...');
        }
  })
  .done(function(datos){
      // console.log(datos);
      if(datos==1){
        window.location.href = "?verificado";
      } else {
        $('#reporte').html(datos);
        $('#btnConsultar').html('Consultar');
     }
  })
  
  })

})

function descargar(id)
{
  
  var params  = 'width=350px';
  params += ', height=720px';
  params += ', top=25, left=600%';
  params += ', fullscreen=yes';
  padre = window.open('../reportes/tk_impresion.php?id='+id, 'OPK', params);
  padre.focus();
           
}
function DescargarCDR(id)
{
  

  $.ajax({
    method: 'POST',
    url: '../apifacturacion/controlador/controlador.php',
    data: {id:id,'accion':'CDR'}
    })
    .done(function(datos){ 
     // console.log(datos);
       if(datos==1){
         window.open('../apifacturacion/controlador/cdr.php?id='+id,'Ventana de descarga');
       } else {
          
            alert("El CDR de este comprobante no existe. Comuniquese con su proveedor");
          
            
       }
    })
  
}