<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

require_once 'lib/hothabitaciones.php';
require_once 'lib/hottiphab.php';
require_once 'lib/hotcarhab.php';
require_once 'lib/hotserhab.php';
require_once 'lib/hothab_hotcarhab.php';
require_once 'lib/hothab_hotserhab.php';
require_once 'lib/hothoteles.php';
require_once 'lib/logs.php';
class HotHabitacionesController extends Controller {
	public $habitacion;
	public $tipos;
	public $caracteristicas;
	public $servicios;
	public $session;
	public $hoteles;
	public $huespedes;
	private $log;
	
	// Configuraciones de la habitacion
	public function listarHabitacionesAction(Request $request) {
		$this->habitacion = new \hothabitaciones ();
		$this->session = new Session ();
		$this->hoteles = new \hothoteles ();
		$idUsuario = $this->session->get ( 'idUsuario' );
		$listarHoteles = $this->hoteles->listarHotelPorUsuario ( $idUsuario );
		
		return $this->render ( 'AppBundle:Index:listarHabitaciones.html.twig', array (
				"habitaciones" => $this->habitacion->listarPorHotel ( $request->request->get ( 'idHotHotel' ) ),
				"usuario" => $this->session->get ( 'name' ),
				"email" => $this->session->get ( 'email' ),
				"rol" => $this->session->get ( 'rol' ),
				"listar" => $this->session->get ( 'Habitaciones' )->listar,
				"insertar" => $this->session->get ( 'Habitaciones' )->insertar,
				"modificar" => $this->session->get ( 'Habitaciones' )->modificar,
				"eliminar" => $this->session->get ( 'Habitaciones' )->eliminar,
				"hoteles" => $listarHoteles,
				"idHotHotel" => $request->request->get ( 'idHotHotel' ) 
		) );
	}
	public function listarHabitacionesDisponibilidadAction(Request $request) {
		$this->habitacion = new \hothabitaciones ();
		$this->session = new Session ();
		$this->hoteles = new \hothoteles ();
		$idUsuario = $this->session->get ( 'idUsuario' );
    	if(!empty($idUsuario)){
			$listarHoteles = $this->hoteles->listarHotelPorUsuario ( $idUsuario );
			
			// Usuario con varios hoteles
			if (count ( $listarHoteles ) > 1) {
				$hotelID = $request->request->get ( 'idHotel' );
				$this->habitacion->verificarBloqueoHabitaciones ( $hotelID );
				return $this->render ( 'AppBundle:Index:listarHabitacionesDisponibilidad.html.twig', array (
						"habitaciones" => $this->habitacion->listarPorHotelDisponibilidad ( $hotelID ),
						"usuario" => $this->session->get ( 'name' ),
						"email" => $this->session->get ( 'email' ),
						"rol" => $this->session->get ( 'rol' ),
						"idHotHotel" => trim ( $hotelID ),
						"hoteles" => $listarHoteles 
				) );
			} 			// Usuario con un hotel
			// Ingresa desde el menu izquierdo
			else {
				foreach ( $listarHoteles as $hotel ) {
					$hotelID = $hotel->idhotel;
				}
				$this->habitacion->verificarBloqueoHabitaciones ( $hotelID );
				return $this->render ( 'AppBundle:Index:listarHabitacionesDisponibilidad.html.twig', array (
						"habitaciones" => $this->habitacion->listarPorHotelDisponibilidad ( $hotelID ),
						"usuario" => $this->session->get ( 'name' ),
						"email" => $this->session->get ( 'email' ),
						"rol" => $this->session->get ( 'rol' ),
						"idHotHotel" => trim ( $hotelID ),
						"hoteles" => $listarHoteles 
				) );
			}
		}else{
			return $this->render('AppBundle:Index:index.html.twig', array(
					"saludo" => "",
					//"usuarios" => $this->usuarios->listar(),
					"error" => "Expiro la sesión, por favor ingresar al sistema"
			));
		}
	}
	public function insertarHabitacionAction(Request $request) {
		$this->caracteristicas = new \hotcarhab ();
		$this->servicios = new \hotserhab ();
		$this->tipos = new \hottiphab ();
		$this->session = new Session ();
		$idHotHotel = $request->request->get ( 'idHotHotel' );
		return $this->render ( 'AppBundle:Index:insertarHabitacion.html.twig', array (
				"caracteristicas" => $this->caracteristicas->listar (),
				"servicios" => $this->servicios->listar (),
				"tipos" => $this->tipos->listar (),
				"idHotHotel" => $idHotHotel,
				"usuario" => $this->session->get ( 'name' ),
				"email" => $this->session->get ( 'email' ),
				"rol" => $this->session->get ( 'rol' ) 
		) );
	}
	public function agregarHabitacionAction(Request $request) {
		$this->habitacion = new \hothabitaciones ();
		$this->session = new Session ();
		$this->hoteles = new \hothoteles ();
		$idUsuario = $this->session->get ( 'idUsuario' );
		$listarHoteles = $this->hoteles->listarHotelPorUsuario ( $idUsuario );
		$tamListarHoteles = sizeof ( $this->hoteles->listarHotelPorUsuario ( $idUsuario ) );
		// Insertar
		$this->habitacion->setIdHotHotel ( $request->request->get ( 'idHotHotel' ) );
		$this->habitacion->setHabMaxMayor ( $request->request->get ( 'nromayor' ) );
		$this->habitacion->setHabMaxMenor ( $request->request->get ( 'nromenor' ) );
		$this->habitacion->setHabTarifaMayor ( $request->request->get ( 'tarifamayor' ) );
		$this->habitacion->setHabTarifaMenor ( $request->request->get ( 'tarifamenor' ) );
		$this->habitacion->setHabDsc ( $request->request->get ( 'habitacion' ) );
		$this->habitacion->setHabFecDesde ( $request->request->get ( 'fecha_desde' ) );
		$this->habitacion->setHabFecHasta ( $request->request->get ( 'fecha_hasta' ) );
		$this->habitacion->setHabEstado ( $request->request->get ( 'estado' ) );
		$this->habitacion->setHabFecAlta ( date ( "Y-m-d" ) );
		$this->habitacion->setIdTipHab ( $request->request->get ( 'tipo' ) );
		$this->habitacion->insertar ();
		$id = $this->habitacion->getIdUltimaInsercion ();
		
		$c = count ( $request->request->get ( 'caracteristica' ) );
		$array = array ();
		
		for($i = 0; $i < $c; $i ++) {
			$hab_carhab = new \hothab_hotcarhab ();
			$hab_carhab->setHotIdCarHab ( $request->request->get ( 'caracteristica' ) [$i] );
			$hab_carhab->setHotIdHabitacion ( $id );
			$hab_carhab->insertar ();
		}
		
		$c = count ( $request->request->get ( 'servicio' ) );
		$array = array ();
		
		for($i = 0; $i < $c; $i ++) {
			$hab_serhab = new \hothab_hotserhab ();
			$hab_serhab->setHotIdSerHab ( $request->request->get ( 'servicio' ) [$i] );
			$hab_serhab->setHotIdHabitacion ( $id );
			$hab_serhab->insertar ();
		}
		
		// Llamar al listar principal de habitaciones
		if (empty ( $this->session->get ( 'Habitaciones' )->listar )) {
			return $this->render ( 'AppBundle:Index:listarHabitaciones.html.twig', array (
					"habitaciones" => $this->habitacion->listar (),
					"usuario" => $this->session->get ( 'name' ),
					"email" => $this->session->get ( 'email' ),
					"rol" => $this->session->get ( 'rol' ),
					"listar" => "0",
					"insertar" => "0",
					"modificar" => "0",
					"eliminar" => "0",
					"hoteles" => $listarHoteles,
					"tamHoteles" => $tamListarHoteles,
					"idHotHotel" => "0" 
			) );
		} else {
			if ($tamListarHoteles > 1) {
				return $this->render ( 'AppBundle:Index:listarHabitaciones.html.twig', array (
						"habitaciones" => $this->habitacion->listarPorHotel ( 0 ),
						"usuario" => $this->session->get ( 'name' ),
						"email" => $this->session->get ( 'email' ),
						"rol" => $this->session->get ( 'rol' ),
						"listar" => $this->session->get ( 'Habitaciones' )->listar,
						"insertar" => $this->session->get ( 'Habitaciones' )->insertar,
						"modificar" => $this->session->get ( 'Habitaciones' )->modificar,
						"eliminar" => $this->session->get ( 'Habitaciones' )->eliminar,
						"hoteles" => $listarHoteles,
						"tamHoteles" => $tamListarHoteles,
						"idHotHotel" => "0" 
				) );
			} else {
				$idHotel;
				foreach ( $listarHoteles as $hoteles ) {
					$idHotel = $hoteles->idhotel;
				}
				return $this->render ( 'AppBundle:Index:listarHabitaciones.html.twig', array (
						"habitaciones" => $this->habitacion->listarPorHotel ( $idHotel ),
						"usuario" => $this->session->get ( 'name' ),
						"email" => $this->session->get ( 'email' ),
						"rol" => $this->session->get ( 'rol' ),
						"listar" => $this->session->get ( 'Habitaciones' )->listar,
						"insertar" => $this->session->get ( 'Habitaciones' )->insertar,
						"modificar" => $this->session->get ( 'Habitaciones' )->modificar,
						"eliminar" => $this->session->get ( 'Habitaciones' )->eliminar,
						"hoteles" => $listarHoteles,
						"tamHoteles" => $tamListarHoteles,
						"idHotHotel" => $request->request->get ( 'idHotHotel' ) 
				) );
			}
		}
	}
	public function modificarHabitacionAction(Request $request) {
		$this->caracteristicas = new \hotcarhab ();
		$this->servicios = new \hotserhab ();
		$this->tipos = new \hottiphab ();
		$this->session = new Session ();
		$info = $request->request->get ( 'info_habitacion' );
		list ( $idhabitacion, $habitacion, $idtipo, $nombretipo, $maxmayor, $tarifamayor, $maxmenor, $tarifamenor, $detalle, $desde, $hasta, $estado ) = explode ( "|", $info );
		$arraydetalle = explode ( "<br>", $detalle );
		array_pop ( $arraydetalle ); // array_pop() - Elimina el ultimo elemento del array
		
		$arrayservicios = explode ( "<br>", $request->request->get ( 'info_habitacion_servicios' ) );
		array_pop ( $arrayservicios );
		
		$idHotHotel = $request->request->get ( 'idHotHotel' );
		return $this->render ( 'AppBundle:Index:modificarHabitacion.html.twig', array (
				"idhabitacion" => $idhabitacion,
				"habitacion" => $habitacion,
				"idtipo" => $idtipo,
				"nombretipo" => $nombretipo,
				"maxmayor" => $maxmayor,
				"tarifamayor" => $tarifamayor,
				"maxmenor" => $maxmenor,
				"tarifamenor" => $tarifamenor,
				"detalles" => $arraydetalle,
				"detallesservicios" => $arrayservicios,
				"desde" => $desde,
				"hasta" => $hasta,
				"estado" => trim ( strip_tags ( $estado ) ),
				"caracteristicas" => $this->caracteristicas->listar (),
				"servicios" => $this->servicios->listar (),
				"tipos" => $this->tipos->listar (),
				"idHotHotel" => $idHotHotel,
				"usuario" => $this->session->get ( 'name' ),
				"email" => $this->session->get ( 'email' ),
				"rol" => $this->session->get ( 'rol' ) 
		) );
	}
	public function cambiarHabitacionAction(Request $request) {
		$this->habitacion = new \hothabitaciones ( $request->request->get ( 'idhabitacion' ) );
		$this->habitacion->setIdHotHotel ( $request->request->get ( 'idHotHotel' ) );
		$this->habitacion->setHabMaxMayor ( $request->request->get ( 'nromayor' ) );
		$this->habitacion->setHabMaxMenor ( $request->request->get ( 'nromenor' ) );
		$this->habitacion->setHabTarifaMayor ( $request->request->get ( 'tarifamayor' ) );
		$this->habitacion->setHabTarifaMenor ( $request->request->get ( 'tarifamenor' ) );
		$this->habitacion->setHabDsc ( $request->request->get ( 'habitacion' ) );
		$this->habitacion->setHabFecDesde ( $request->request->get ( 'fecha_desde' ) );
		$this->habitacion->setHabFecHasta ( $request->request->get ( 'fecha_hasta' ) );
		$this->habitacion->setHabEstado ( $request->request->get ( 'estado' ) );
		$this->habitacion->setIdTipHab ( $request->request->get ( 'tipo' ) );
		$this->habitacion->modificar ();
		
		$hab_carhab = new \hothab_hotcarhab ();
		$caracteristicas_anteriores = $hab_carhab->getIdCaracteristicas ( $request->request->get ( 'idhabitacion' ) );
		$c = count ( $caracteristicas_anteriores );
		for($i = 0; $i < $c; $i ++) {
			$hab_carhab->setHotIdCarHab ( $caracteristicas_anteriores [$i] );
			$hab_carhab->setHotIdHabitacion ( $request->request->get ( 'idhabitacion' ) );
			$hab_carhab->eliminar ();
		}
		
		$c = count ( $request->request->get ( 'caracteristica' ) );
		for($i = 0; $i < $c; $i ++) {
			$hab_carhab->setHotIdCarHab ( $request->request->get ( 'caracteristica' ) [$i] );
			$hab_carhab->setHotIdHabitacion ( $request->request->get ( 'idhabitacion' ) );
			$hab_carhab->insertar ();
		}
		
		$hab_serhab = new \hothab_hotserhab ();
		$servicios_anteriores = $hab_serhab->getIdServicios ( $request->request->get ( 'idhabitacion' ) );
		$c = count ( $servicios_anteriores );
		for($i = 0; $i < $c; $i ++) {
			$hab_serhab->setHotIdSerHab ( $servicios_anteriores [$i] );
			$hab_serhab->setHotIdHabitacion ( $request->request->get ( 'idhabitacion' ) );
			$hab_serhab->eliminar ();
		}
		
		$c = count ( $request->request->get ( 'servicio' ) );
		for($i = 0; $i < $c; $i ++) {
			$hab_serhab->setHotIdSerHab ( $request->request->get ( 'servicio' ) [$i] );
			$hab_serhab->setHotIdHabitacion ( $request->request->get ( 'idhabitacion' ) );
			$hab_serhab->insertar ();
		}
		
		return $this->listarHabitacionesAction ( $request );
	}
	public function eliminarHabitacionAction(Request $request) {
		//$hab_carhab = new \hothab_hotcarhab ();
		/*
		 * $caracteristicas_anteriores = $hab_carhab->getIdCaracteristicas($request->request->get('id_eliminar'));
		 * $c = count($caracteristicas_anteriores);
		 * for( $i = 0; $i < $c; $i++ ){
		 * $hab_carhab->setHotIdCarHab($caracteristicas_anteriores[$i]);
		 * $hab_carhab->setHotIdHabitacion($request->request->get('id_eliminar'));
		 * $hab_carhab->eliminar();
		 * }
		 */
		// SE MODIFICA EL ESTADO PARA QUE YA NO APAREZCA EN EL LISTAR
		$this->habitacion = new \hothabitaciones ( $request->request->get ( 'id_eliminar' ) );
		// $this->habitacion->setIdHotHotel($request->request->get('idHotHotel'));
		$this->habitacion->eliminar ();
		return $this->listarHabitacionesAction ( $request );
		
		/*
		 * return $this->render('AppBundle:Index:listarHabitaciones.html.twig', array(
		 * "habitaciones" => $this->habitacion->listar()
		 * ));
		 */
	}
	public function buscarDisponibilidadAction(Request $request) {
		$this->habitacion = new \hothabitaciones ();
		$this->hoteles = new \hothoteles ();
		$this->session = new Session ();
		$idUsuario = $this->session->get ( 'idUsuario' );
		$listarHoteles = $this->hoteles->listarHotelPorUsuario ( $idUsuario );
		
		// Variables de entrada
		$idHotel = $request->request->get ( 'idHotel' );
		$fecha_desde = $request->request->get ( 'fecha_desde' );
		$fecha_hasta = $request->request->get ( 'fecha_hasta' );
		$nroadultos = $request->request->get ( 'nroadultos' );
		$nromenores = $request->request->get ( 'nromenores' );
		
		// Calculo de la diferencia entre fechas
		// 86400 = 1 dia en segundos
		$cantidad_dias = 0;
		if ($fecha_desde != "" && $fecha_hasta != "") {
			$cantidad_dias = (strtotime ( $fecha_desde ) - strtotime ( $fecha_hasta )) / 86400;
			$cantidad_dias = abs ( $cantidad_dias );
			$cantidad_dias = floor ( $cantidad_dias );
		}
		
		// Usuario con varios hoteles
		if (count ( $listarHoteles ) > 1) {
			return $this->render ( 'AppBundle:Index:buscarDisponibilidad.html.twig', array (
					"hoteles" => $listarHoteles,
					"usuario" => $this->session->get ( 'name' ),
					"email" => $this->session->get ( 'email' ),
					"rol" => $this->session->get ( 'rol' ),
					"habitaciones_disponibles" => $this->habitacion->habitacionesDisponibleHotel ( $idHotel, $nroadultos, $nromenores, $fecha_desde, $fecha_hasta ),
					"idHotel" => $idHotel,
					"fecha_desde" => $fecha_desde,
					"fecha_hasta" => $fecha_hasta,
					"nroadultos" => $nroadultos,
					"nromenores" => $nromenores,
					"cantidad_dias" => $cantidad_dias 
			) );
		} else {
			foreach ( $listarHoteles as $hotel ) {
				$hotelID = $hotel->idhotel;
			}
			return $this->render ( 'AppBundle:Index:buscarDisponibilidad.html.twig', array (
					"hoteles" => $listarHoteles,
					"idHotHotel" => trim ( $hotelID ),
					"usuario" => $this->session->get ( 'name' ),
					"email" => $this->session->get ( 'email' ),
					"rol" => $this->session->get ( 'rol' ),
					"habitaciones_disponibles" => $this->habitacion->habitacionesDisponibleHotel ( $idHotel, $nroadultos, $nromenores, $fecha_desde, $fecha_hasta ),
					"idHotel" => $idHotel,
					"fecha_desde" => $fecha_desde,
					"fecha_hasta" => $fecha_hasta,
					"nroadultos" => $nroadultos,
					"nromenores" => $nromenores,
					"cantidad_dias" => $cantidad_dias 
			) );
		}
	}
	public function calendarioAction(Request $request) {
		$this->habitacion = new \hothabitaciones ();
		$this->session = new Session ();
		$this->hoteles = new \hothoteles ();
		$idUsuario = $this->session->get ( 'idUsuario' );
		$hotelID = $request->request->get ( 'idHotHotel' );
		//$listarHoteles = $this->hoteles->listarHotelPorUsuario ( $idUsuario );
		//$hotelID;
		//foreach ( $listarHoteles as $hotel ) {
		//	$hotelID = $hotel->idhotel;
		//}
		if(empty($idUsuario)){
			return $this->render('AppBundle:Index:index.html.twig', array(
					"saludo" => "",
					"error" => "Expiro la sesión, por favor ingresar al sistema"
			));
		}else{
			return $this->render ( 'AppBundle:Index:calendario.html.twig', array (
				"usuario" => $this->session->get ( 'name' ),
				"email" => $this->session->get ( 'email' ),
				"rol" => $this->session->get ( 'rol' ),
				"hotelId" => $hotelID,
				"fechaReserva" =>'2017-07-01'
			) );
		}
	}
}
