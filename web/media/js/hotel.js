var info_imagen = ""; //variable global para la seleccion de imagenes
var map; //variable global que maneja el mapa

$(document).ready(function() {
	
	/*************************************************************************************/
	/************************************* CALENDARIO ************************************/
	/*************************************************************************************/
	$('#desde').datepicker({
		format: 'yyyy-mm-dd',
		startDate: '0d',
		language: 'es',
		todayHighlight: 'true',
		autoclose: 'true'		
	});
	$('#hasta').datepicker({
		format: 'yyyy-mm-dd',
		startDate: '+1d',
		language: 'es',		
		autoclose: 'true'		
	});	
	$('#desde').datepicker().on("changeDate", function(e) {	
		$('#hasta').datepicker('update','');	
		$('#hasta').datepicker('setStartDate', $(this).datepicker('getDate'));
    });
		
	$('#nac').datepicker({
		format: 'yyyy-mm-dd',
		language: 'es',
		todayHighlight: 'true',
		autoclose: 'true'
	});
	$('.nac').datepicker({
		format: 'yyyy-mm-dd',
		language: 'es',
		todayHighlight: 'true',
		autoclose: 'true'
	});

	/*************************************************************************************/
	/************************************** PAGINADOR ************************************/
	/*************************************************************************************/
	
	//$("#paginador").DataTable();
	$('.paginador').DataTable({
		"paging": true,             //
	    "lengthChange": true,		//mostrar select de numero de registros
	    "searching": true,			//mostrar buscador
	    "ordering": false,			//mostrar flechas de ordenamiento en las cabeceras de cada columna
	    "info": true,				//mostrar informacion de la cantidad de registros abajo	    	    	    
	    "autoWidth": true,			//
	    "lengthMenu": [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, "Todo"]],
	    "language": {
	    	"lengthMenu": "Mostrando _MENU_ registros por p&aacute;gina",	    	
	    	"zeroRecords": "",
	    	"emptyTable": "No se encontraron registros",
            "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",           
            "infoEmpty": "No hay registros disponibles",
            "infoFiltered": "(filtrados de  un total de _MAX_ registros)",
            "sSearch": "Buscar:",
            "oPaginate": {
                "sFirst":    "Primero",
                "sLast":     "&Uacute;ltimo",
                "sNext":     "Siguiente",
                "sPrevious": "Anterior"
            },
	    }
	});			
	
	//Selecciona las imagenes y guardas los ids de las seleccionadas en un input
	$("img").click(function(){
		if( $(this).hasClass("seleccion_imagen") ){
			$(this).removeClass("seleccion_imagen");
			info_imagen = info_imagen.replace($(this).attr('id') + "|", "");
		}
		else {
			$(this).addClass("seleccion_imagen");
			info_imagen += $(this).attr('id') + "|";
		}
		$('input[name="info_img"]').val(info_imagen);
	});

	//Para que funcionen los tooltip en las imagenes
	$('[data-toggle="tooltip"]').tooltip();
	
	//Para que funcionen los popover en las reservas detalladas
	$('[data-toggle="popover"]').popover({
		html: true
	});
	 	 
	//Inicializa el Bootstrap Toogle
	$('.habilitado').bootstrapToggle();
	//Simula los clicks en unos radio buttons a traves de un bootstrap toggle
	$('.habilitado').change(function() {
    	if( $(this).prop('checked') ){
    		$('.si').trigger('click');
    	}
    	else{
    		$('.no').trigger('click');
    	}
    });
	
	// Marca o desmarca todos los checkbox para las caracteristicas	
	$('#select_all_car').change(function() {
    	var checkboxes = $(this).closest('form').find('.select_carateristica');
    	if($(this).is(':checked')) {
        	checkboxes.prop('checked', true);
        	$('#titulo_marcado_car').html("Desmarcar Todo");
    	} else {
        	checkboxes.prop('checked', false);
        	$('#titulo_marcado_car').html("Marcar Todo");
    	}
	});
	// Marca o desmarca todos los checkbox para los servicios
	$('#select_all_ser').change(function() {
    	var checkboxes = $(this).closest('form').find('.select_servicio');
    	if($(this).is(':checked')) {
        	checkboxes.prop('checked', true);
        	$('#titulo_marcado_ser').html("Desmarcar Todo");
    	} else {
        	checkboxes.prop('checked', false);
        	$('#titulo_marcado_ser').html("Marcar Todo");
    	}
	});
		
	//Coloca el id del hotel en un input para enviarlo a la configuracion de las habitaciones
	$("#idHotel").change(function(){		
		$('#idHotHotel').val($(this).val());
	});
	
	//Coloca toda la informacion de una caracteristica de habitacion en un input para enviar
	$(".seleccion_carhab").click(function(){
		var info_carhab = $('input[type=radio]:checked').attr('id');
		$(this).parents("tr").find(".caracteristica").each(function(){
			info_carhab += "|" + $(this).html();
		});
		$('input[name="info_carhabitacion"]').val(info_carhab);
	});
	
	//Coloca toda la informacion de un servicio de habitacion en un input para enviar
	$(".seleccion_serhab").click(function(){
		var info_serhab = $('input[type=radio]:checked').attr('id');
		$(this).parents("tr").find(".servicio").each(function(){
			info_serhab += "|" + $(this).html();
		});
		$('input[name="info_serhabitacion"]').val(info_serhab);
	});

	//Coloca toda la informacion de la habitacion en un input para enviar
	$(".seleccion").click(function() {
		var info_habitacion = $('input[type=radio]:checked').attr('id');
		var info_habitacion_servicios = "";
		
        $(this).parents("tr").find(".habitacion").each(function() {
        	info_habitacion += "|" + $(this).html();
        });
        $(this).parents("tr").find(".tipo").each(function() {
        	info_habitacion += "|" + $(this).attr('id');
        	info_habitacion += "|" + $(this).html();
        });
        $(this).parents("tr").find(".maxmayor").each(function() {
        	info_habitacion += "|" + $(this).html();
        });
        $(this).parents("tr").find(".tarifamayor").each(function() {
        	info_habitacion += "|" + $(this).html();
        });
        $(this).parents("tr").find(".maxmenor").each(function() {
        	info_habitacion += "|" + $(this).html();
        });
        $(this).parents("tr").find(".tarifamenor").each(function() {
        	info_habitacion += "|" + $(this).html();
        });
        $(this).parents("tr").find(".detalle").each(function() {
        	info_habitacion += "|" + $(this).html();
        });
        $(this).parents("tr").find(".desde").each(function() {
        	info_habitacion += "|" + $(this).html();
        });
        $(this).parents("tr").find(".hasta").each(function() {
        	info_habitacion += "|" + $(this).html();
        });
        $(this).parents("tr").find(".estado").each(function() {
        	info_habitacion += "|" + $(this).html();
        });
        $('input[name="info_habitacion"]').val(info_habitacion);
        
        $(this).parents("tr").find(".servicio").each(function(){
        	info_habitacion_servicios = $(this).html();
        });
        $('input[name="info_habitacion_servicios"]').val(info_habitacion_servicios);
        
        $('#reserva').click(function(){
    		var ideliminar = $('input[type=radio]:checked').attr('id');
    		$('input[name="id_eliminar"]').val(ideliminar);
    	});
	});

	//Coloca el id de la habitacion a eliminar en un input para enviar
	$('#eliminacion').click(function(){
		var ideliminar = $('input[type=radio]:checked').attr('id');
		$('input[name="id_eliminar"]').val(ideliminar);
	});	
		
	// Muestra o no el mapa
	$('#agregarmapa').click(function(){
		if($('#contenedor_mapa').hasClass('sr-only')){
			$('#contenedor_coordenadas').removeClass('sr-only');
			$('#contenedor_mapa').removeClass('sr-only');
			$(this).html("  <i class='fa fa-map-marker'></i>  Quitar Mapa");
		}
		else{
			$('input[name="geolocalizacion"]').val("");
			initMap();
			$('#contenedor_coordenadas').addClass('sr-only');
			$('#contenedor_mapa').addClass('sr-only');
			$(this).html("  <i class='fa fa-map-marker'></i>  Agregar Mapa");
		}
	});
	
	$('#modificarmapa').click(function(){
		if( $('#geolocalizacion').val() != "" ){
			$('input[name="geolocalizacion"]').val("");
			initMap();
			$('#contenedor_coordenadas').removeClass('sr-only');
			$('#contenedor_mapa').removeClass('sr-only');
			$(this).html("  <i class='fa fa-map-marker'></i>  Quitar Mapa");
		}
		else{
			if($('#contenedor_mapa').hasClass('sr-only')){
				$('#contenedor_coordenadas').removeClass('sr-only');
				$('#contenedor_mapa').removeClass('sr-only');
				$(this).html("  <i class='fa fa-map-marker'></i>  Quitar Mapa");
			}
			else{
				$('input[name="geolocalizacion"]').val("");
				initMap();
				$('#contenedor_coordenadas').addClass('sr-only');
				$('#contenedor_mapa').addClass('sr-only');
				$(this).html("  <i class='fa fa-map-marker'></i>  Agregar Mapa");
			}
		}

	});
});

function initMap() {
	//Colocando las coordenadas aproximadamente en el centro de bolivia
	var centro = {lat: -16.61092645013157, lng: -64.57763671875};
	var map = new google.maps.Map(document.getElementById('map'), {
		zoom: 5,
		center: centro
	});

	map.addListener('click', function(event){
		console.log("hola");
		$('input[name="geolocalizacion"]').val(event.latLng);
		var marker = new google.maps.Marker({
			position: event.latLng,
			map: map
		});
		//document.getElementById("geolocalizacion").innerHTML = event.latLng;
	});
}
