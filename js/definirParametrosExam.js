$(document).ready(function(){

	$(".puntosTemaForm").change(function() {
		let sumaPuntos = 0;
		$( ".puntosTemaForm" ).each(function() {
		  console.log($( this ).val());
		  sumaPuntos += parseInt($( this ).val(), 10);
		});
		if($( "#maximoPuntos" ).val() != sumaPuntos){
			$("#botonGuardar").attr("class", "btn btn-primary disabled");
	    $("#botonGuardar").attr("disabled", true);
	    $("#mensajePuntosPorTema").show();
	    $("#mensajePuntosPorTema").text("La cantidad de puntos por tema tiene que coincidir con el total de puntos").addClass('badge badge-pill badge-danger');
		}
		else{
			$("#botonGuardar").attr("class", "btn btn-primary active");
	    $("#botonGuardar").attr("disabled", false);
	    $("#mensajePuntosPorTema").hide();
		}
	});


	$('#formParametros').submit(function(event) {
        var funcion = "updateParametrosAsig";
        var jsonNuevo = new Object();
        jsonNuevo["numeroTemas"] = $("#numeroTemas").attr("value");
        jsonNuevo["maximoPuntos"] = $("#maximoPuntos").attr("value");
        $( ".puntosTemaForm" ).each(function() {
		  jsonNuevo[$( this ).attr("id")] = $(this).val();
		});
		console.log(jsonNuevo);
		var idAsig = $('h1').attr("idAsig");
        var form_data = $(this).serialize();
      $.ajax({
          type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
          url         : 'definirParametrosExamProcesamiento.php', // the url where we want to POST
          data        : form_data + '&funcion=' + funcion + '&jsonParametros=' + jsonNuevo + "&idAsig=" + idAsig, // our data object
          success: function(respuesta) {
                if(respuesta){
                    console.log(respuesta);
                    console.log("hola");
                    //location.reload();
                }
                else{
                	console.log("falla");
                    //alert("Fallo al borrar");
                    //location.reload();
                }
             }
      })
        event.preventDefault();

    });


});