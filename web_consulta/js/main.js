$(document)
    .ready(function() {

    	$('#respuesta').hide();
		
		$('.ui.form').form({  
	        num_tarjeta: {
	          identifier  : 'num_tarjeta',
	          rules: [
	            {
	              type   : 'empty',
	              prompt : 'Por favor ingresa un número'
	            },
	            {
	              type   : 'number',
	              prompt : 'Por favor ingresa un valor numérico'
	            }
	          ]
	        }
		    }, { onSuccess: submitForm });        	
		        	
		function submitForm(event) {

			event.preventDefault();
			
		   	var num_tarjeta = $("#num_tarjeta" ).val();

			    $.ajax({
			        url: 'http://saldobip.kinalef.cl/api/tarjeta/'+num_tarjeta,
			        async : false,
			        type: 'GET',
			        dataType: 'json',
			        success: function(data) {

			        	$('#respuesta').show();
			        	
			        	if(data.code != 200){
			        		$('#msg-alerta').append(data.message);
			        		$('#msg-alerta').show();
			        	}
			        	else{
			        		$('#saldo').append(data.saldo);
			        		$('#msg-alerta').hide();
			        	}
			            console.log(data);
			        },
			        error: function( jqXhr, textStatus, errorThrown ){
				        console.log( errorThrown );
				    }
			    });
			}
		});

