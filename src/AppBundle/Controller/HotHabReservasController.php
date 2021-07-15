<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

require_once 'lib/hothabreservas.php';
require_once 'lib/hothabitaciones.php';
require_once 'lib/hothoteles.php';
require_once 'lib/hothuesped.php';
require_once 'lib/logs.php';

class HotHabReservasController extends Controller
{
	public $reservas;
	public $session;
	public $habitacion;
	public $hoteles;
	public $huesped;

	public function listarReservasAction(Request $request){
		$this->reservas = new \hothabreservas();
		$this->session = new Session();		

		//list($idhabitacion, $nombrehabitacion, $tipohabitacion) = explode("|", $request->request->get('info_reserva'));
		list($idhabitacion, $nombrehabitacion, $tipohabitacion,$idHotel) = explode("|", $request->request->get('info_reserva'));
		return $this->render('AppBundle:Index:listarReservas.html.twig', array(					
			"idhabitacion" => $idhabitacion,
			"nombrehabitacion" => $nombrehabitacion,				
			"tipohabitacion" => $tipohabitacion,
			"idHotel" =>$idHotel,
			"reservas" => $this->reservas->listarPorHabitacion( $idhabitacion ),
			"usuario" => $this->session->get('name'),
        	"email" => $this->session->get('email'),
        	"rol" => $this->session->get('rol'),
		));
	}
	public function listarReservasActualesAction(Request $request){
		$this->reservas = new \hothabreservas();
		$this->session = new Session();
	
		list($idhabitacion, $nombrehabitacion, $tipohabitacion, $idHotel) = explode("|", $request->request->get('info_reserva'));		
		return $this->render('AppBundle:Index:listarReservasActual.html.twig', array(
			"idhabitacion" => $idhabitacion,
			"nombrehabitacion" => $nombrehabitacion,
			"tipohabitacion" => $tipohabitacion,
			"idHotel" =>$idHotel,
			"reservas" => $this->reservas->listarPorHabitacionFechaActual( $idhabitacion ),			
			"usuario" => $this->session->get('name'),
			"email" => $this->session->get('email'),
			"rol" => $this->session->get('rol'),
		));
	}
	public function cambiarEstadoReservaAction(Request $request){
		$this->reservas = new \hothabreservas($request->request->get('reserva'));
		$this->habitacion = new \hothabitaciones();
		$this->session = new Session();

		$estado = $request->request->get('estado');
		$this->reservas->setRevEstado($estado);
		$this->reservas->cambiarEstado();
		$idhabitacion = $request->request->get('habitacion');
		$nombrehabitacion = $request->request->get('nombrehabitacion');
		$tipohabitacion = $request->request->get('tipohabitacion');
		$idHotel= $request->request->get('idHotel');
		//Solo en estado Checkin modificar la habitaci�n con estado Ocupada(1)
		if($estado == 4){
			//Checkin
			$this->habitacion->cambiarEstadoDiponibilidad($idhabitacion,1);
		}else{
			//Todas las demas (0) Disponible
			$this->habitacion->cambiarEstadoDiponibilidad($idhabitacion,0);
		}
		
		return $this->render('AppBundle:Index:listarReservasActual.html.twig', array(
				"idhabitacion" => $idhabitacion,
				"nombrehabitacion" => $nombrehabitacion,
				"tipohabitacion" => $tipohabitacion,
				"idHotel" =>$idHotel,
				"reservas" => $this->reservas->listarPorHabitacionFechaActual( $idhabitacion ),
				"usuario" => $this->session->get('name'),
				"email" => $this->session->get('email'),
				"rol" => $this->session->get('rol'),
		));
	}
	 
	public function insertarReservaAction(Request $request){				
		$this->session = new Session();
		
		$idHabitacion_configuraciones=$request->request->get('idhabitacion');
		$idHabitacion_normal=$request->request->get('idHabitacion');
		$idHabitacion="";
		if(!empty($idHabitacion_configuraciones)){
			$idHabitacion = $idHabitacion_configuraciones;
		}elseif(!empty($idHabitacion_normal)){
			$idHabitacion = $idHabitacion_normal;
		}
		return $this->render('AppBundle:Index:insertarReserva.html.twig', array(
			"idhabitacion" => $idHabitacion,
			"nombrehabitacion" => $request->request->get('nombreHabitacion'),
			"tipohabitacion"	=> $request->request->get('tipoHabitacion'),
			"idHotel"	=> $request->request->get('idHotel'),
			"usuario" => $this->session->get('name'),
			"email" => $this->session->get('email'),
			"rol" => $this->session->get('rol')
		));
	}
	
	public function agregarReservaAction(Request $request){
		$this->session = new Session();
		$this->reservas = new \hothabreservas();		
		$this->reservas->setIdHabitacion($request->request->get('idhabitacion'));
		$this->reservas->setRevFechaIngreso($request->request->get('fecha_ingreso'));
		$this->reservas->setRevFechaSalida($request->request->get('fecha_salida'));
		$this->reservas->setRevEstado(3);
		$this->reservas->insertar();
		return $this->render('AppBundle:Index:listarReservasActual.html.twig', array(
			"idhabitacion" => $request->request->get('idhabitacion'),
			"nombrehabitacion" => $request->get('nombrehabitacion'),
			"tipohabitacion" => $request->request->get('tipohabitacion'),
			"idHotel" =>$request->request->get('idHotel'),
			"reservas" => $this->reservas->listarPorHabitacionFechaActual( $request->request->get('idhabitacion') ),
			"usuario" => $this->session->get('name'),
			"email" => $this->session->get('email'),
			"rol" => $this->session->get('rol'),
		));
		
	}
	public function reporteMensualAction(Request $request){
		$this->session = new Session();
		$this->hoteles =  new \hothoteles();
		$idUsuario = $this->session->get('idUsuario');
		$listarHoteles = $this->hoteles->listarHotelPorUsuario($idUsuario);
		$mes="";		
		if($request->get('mes')){
			$mes = $request->get('mes');
		}		
		return $this->render('AppBundle:Index:reporteMensual.html.twig', array(
			"hoteles" => $listarHoteles,
			"total" => "",
			"habitaciones" => "",
			"mes" =>  $mes,	
			"usuario" => $this->session->get('name'),
			"email" => $this->session->get('email'),
			"rol" => $this->session->get('rol'),						
		));
		
	}
	public function generarMensualAction(Request $request){
		//Reporte General por Hotel
		$this->session = new Session();
		$this->reservas = new \hothabreservas();
		$this->hoteles =  new \hothoteles();
		$idUsuario = $this->session->get('idUsuario');
		$listarHoteles = $this->hoteles->listarHotelPorUsuario($idUsuario);
		$idHotel = $request->request->get('idHotel');
		$mes = $request->request->get('mes');
		$anio = $request->request->get('anio');
		$vTotal = $this->reservas->totalReporteMensual($idHotel,$mes,$anio);
		$habitaciones = $this->reservas->reporteMensualPorHabitacion($idHotel,$mes,$anio);		
		
		return $this->render('AppBundle:Index:reporteMensual.html.twig', array(
			"hoteles" => $listarHoteles,
			"total" => $vTotal,
			"habitaciones" => $habitaciones,
			"mes" => $mes,
			"idhotel" =>$idHotel,
			"anio" =>$anio,
			"usuario" => $this->session->get('name'),
			"email" => $this->session->get('email'),
			"rol" => $this->session->get('rol'),
		));
	}
	public function generarDetalleMensualAction(Request $request){
		//Reporte Mensual por Habitacion
		$this->session = new Session();
		$this->reservas = new \hothabreservas();
		$this->hoteles =  new \hothoteles();
		$idUsuario = $this->session->get('idUsuario');
		$listarHoteles = $this->hoteles->listarHotelPorUsuario($idUsuario);
		$idHotel = $request->request->get('idHotel');
		$mes = $request->request->get('mes');
		$anio = $request->request->get('anio');
		$idHabitacion= $request->request->get('idHabitacion');
		$nombreHabitacion= $request->request->get('nombreHabitacion');
		$tipoHabitacion= $request->request->get('tipoHabitacion');
		$reservas = $this->reservas->reporteMensualDetalleHabitacion($idHotel,$idHabitacion,$mes,$anio);
		$vTotalHotel = $this->reservas->totalReportePrecioHotel($idHotel,$idHabitacion,$mes,$anio);
		$totalComision = $this->reservas->totalReportePrecioComision($idHotel,$idHabitacion,$mes,$anio);
		$totalVenta = $this->reservas->totalReportePrecioVenta($idHotel,$idHabitacion,$mes,$anio);
		return $this->render('AppBundle:Index:reporteDetalleMensual.html.twig', array(
				"reservas" => $reservas,
				"idhabitacion" => $idHabitacion,
				"nombrehabitacion" => $nombreHabitacion,
				"tipohabitacion" => $tipoHabitacion,
				"totalHotel" => $totalComision,
				"totalComision" => $vTotalHotel,
				"totalVenta"=> $totalVenta,
				"mes"=>$mes,
				"anio"=>$anio,
				"idhotel" =>$idHotel,
				"usuario" => $this->session->get('name'),
				"email" => $this->session->get('email'),
				"rol" => $this->session->get('rol'),
		));
	}
	
	public function reservarHuespedAction(Request $request){
		$this->reservas = new \hothabreservas();
		$this->session = new Session();
	
		//list($idhabitacion, $nombrehabitacion, $tipohabitacion,$idHotel) = explode("|", $request->request->get('info_reserva'));
		$idhabitacion=$request->request->get('idhabitacion');
		$nombrehabitacion=$request->request->get('habdsc');
		$tipohabitacion=$request->request->get('tipo');
		$idHotel=$request->request->get('idHotel');
		$fechaEntrada=$request->request->get('desde');
		$fechaSalida=$request->request->get('hasta');
		$nroAdultos=$request->request->get('nroadultos');
		$nroNinos=$request->request->get('nromenores');
		$total=$request->request->get('total');
		$total_huespedes = ($nroAdultos + $nroNinos)-1;//Comineza desde cero el for
		// Generar codigo de reserva
		$reserva = "ABCDEFGHIJKMNPQRSTUVWXZ";
		$codReserva = "";
		for($num3 = 0; $num3 < 5; $num3 ++) {
			$codReserva .= substr ( $reserva, rand ( 0, 22 ), 1 );
		}
		return $this->render('AppBundle:Index:insertarReservaHuesped.html.twig', array(
				"idhabitacion" => $idhabitacion,
				"nombrehabitacion" => $nombrehabitacion,
				"tipohabitacion" => $tipohabitacion,
				"idHotel" =>$idHotel,
				"fechaEntrada" => $fechaEntrada,
				"fechaSalida" => $fechaSalida,
				"codigoReserva" =>$codReserva,
				"total_huespedes" =>$total_huespedes,
				"total"=>$total,
				"reservas" => $this->reservas->listarPorHabitacion( $idhabitacion ),
				"usuario" => $this->session->get('name'),
				"email" => $this->session->get('email'),
				"rol" => $this->session->get('rol'),
		));
	}
	public function agregarReservaHuespedAction(Request $request){
		$this->reservas = new \hothabreservas();
		//RESERVA
		$this->reservas->setIdHabitacion($request->request->get('idhabitacion'));
		$this->reservas->setRevFechaIngreso($request->request->get('fecha_ingreso'));
		$this->reservas->setRevFechaSalida($request->request->get('fecha_salida'));
		$this->reservas->setRevCodigoReserva($request->request->get('reserva'));
		$this->reservas->setRevMontoReserva($request->request->get('tarifa'));
		$this->reservas->setRevEstado(1);
		$this->reservas->insertar();
		$idReserva=$this->reservas->getIdUltimaInsercion();
		//HUESPEDES
		$this->huesped = new \hothuesped();
		$nroHuespedes=$request->request->get('nrohuesped');
		for ($i = 0; $i <= $nroHuespedes; $i++) {
			$nombre=$request->request->get('nombre'.$i);
			$email=$request->request->get('email'.$i);
			$cedula=$request->request->get('cedula'.$i);
			$telefono=$request->request->get('telefono'.$i);
			$fecha_nac=$request->request->get('fecha_nac'.$i);
			$this->huesped->setIdReserva($idReserva);
			$this->huesped->setHueNombre($nombre);
			$this->huesped->setHueCi($cedula); 
			$this->huesped->setHueTelefono($telefono);
			$this->huesped->setHueEmail($email); 
			$this->huesped->setHueFecNacimiento($fecha_nac);
			$this->huesped->insertar();
		}
		//Llamar pantalla disponibilidad
		$this->habitacion = new \hothabitaciones();
		$this->hoteles =  new \hothoteles();
		$this->session = new Session();
		$idUsuario = $this->session->get('idUsuario');
		$listarHoteles = $this->hoteles->listarHotelPorUsuario($idUsuario);
		 
		$idHotel = "";
		$fecha_desde = "";
		$fecha_hasta = "";
		$nroadultos = "";
		$nromenores = "";
		// Usuario con varios hoteles
		if( count($listarHoteles) > 1 ){
			return $this->render('AppBundle:Index:buscarDisponibilidad.html.twig', array(
					"hoteles" => $listarHoteles,
					"usuario" => $this->session->get('name'),
					"email" => $this->session->get('email'),
					"rol" => $this->session->get('rol'),
					"habitaciones_disponibles" => $this->habitacion->habitacionesDisponibleHotel($idHotel, $nroadultos, $nromenores, $fecha_desde, $fecha_hasta),
					"idHotel" => $idHotel,
					"fecha_desde" => $fecha_desde,
					"fecha_hasta" => $fecha_hasta,
					"nroadultos" => $nroadultos,
					"nromenores" => $nromenores
			));
		}
		else{
			foreach( $listarHoteles as $hotel ){
				$hotelID = $hotel->idhotel;
			}
			return $this->render('AppBundle:Index:buscarDisponibilidad.html.twig', array(
					"hoteles" => $listarHoteles,
					"idHotHotel" => trim($hotelID),
					"usuario" => $this->session->get('name'),
					"email" => $this->session->get('email'),
					"rol" => $this->session->get('rol'),
					"habitaciones_disponibles" => $this->habitacion->habitacionesDisponibleHotel($idHotel, $nroadultos, $nromenores, $fecha_desde, $fecha_hasta),
					"idHotel" => $idHotel,
					"fecha_desde" => $fecha_desde,
					"fecha_hasta" => $fecha_hasta,
					"nroadultos" => $nroadultos,
					"nromenores" => $nromenores
			));
		}
	}
}

?>