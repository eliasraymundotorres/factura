$(document).ready(function(){
	mostrar();

  $(document).on('click','#btnGuardar',function(){
  	var dato = $('#addForm').serializeArray();
     $.ajax({
		method: 'POST',
		url: 'includes/ajax.php',
		data: dato
	})
	.done(function(datos){
       $('#alert_message').html(datos);
       mostrar();
	})
  })
  
})

function validar()
{
   var dato = $('#formContrato').serializeArray();
   $.ajax({
   	method: 'POST',
   	url: 'includes/ajax.php',
   	data: dato
   })
   .done(function(datos){
       console.log(datos);

       $('#tcalculo').html(datos);
   })
}

function editar()
{
	$('#fi').attr('disabled',false);
	$('#ff').attr('disabled',false);
	$('#fp').attr('disabled',false);
}

function mostrar()
{
	$.ajax({
		method: 'POST',
		url: 'includes/ajax.php',
		data: {'accion':'MOSTRAR'}
	})
	.done(function(datos){
       $('#tbody').html(datos);
	})
}