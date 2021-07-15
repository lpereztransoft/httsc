<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MetadataBag;

// use Symfony\Component\HttpFoundation\Response;
require_once 'lib/hothoteles.php';
require_once 'lib/hotcarhotel.php';
require_once 'lib/hothotel_hotcarhot.php';
require_once 'lib/hotusu_hothot.php';
require_once 'lib/logs.php';
require_once 'lib/hotusuario.php';
require_once 'lib/hotusu_hotrol.php';
class HotHotelController extends Controller {
	public $helper;
	// public $log;
	public $hoteles;
	// private $logs;
	private $log;
	public $caracteristicas;
	public $session;
	public $usuario;
	public function form_hothotelAction() {
		$this->helper = $this->get ( "Helper" );
		// $this->log = $this->get("Log");
		$this->log = new \logs ();
		$this->hoteles = new \hothoteles ();
		$this->caracteristicas = new \hotcarhotel ();
		$this->session = new \hotusuario ();
		$this->session->getSession ();
		$idUsuario = $this->session->getSession ()->get ( 'idUsuario' );
		$maxIdleTime = 60;
		// $this->log->setLog ( "INPUT", time (), __METHOD__, __LINE__ );
		// $this->log->setLog ( "INPUT", $this->session->getSession ()->getMetadataBag ()->getLastUsed (), __METHOD__, __LINE__ );
		if (time () - $this->session->getSession ()->getMetadataBag ()->getLastUsed () > $maxIdleTime) {
			$session->invalidate ();
			// redirige a la p�gina de sesi�n expirada
			return $this->render ( 'AppBundle:Index:index.html.twig', array (
					"saludo" => "",
					"error" => "Expiro la sesión, por favor ingresar al sistema" 
			) );
		} else {
			if (empty ( $this->session->getSession ()->get ( 'Hotel' )->listar )) {
				return $this->render ( 'AppBundle:Index:form_hothotel.html.twig', array (
						"hoteles" => $this->hoteles->listarHotelPorUsuario ( $idUsuario ),
						"caracteristicas" => $this->caracteristicas->listar (),
						"usuario" => $this->session->getSession ()->get ( 'name' ),
						"email" => $this->session->getSession ()->get ( 'email' ),
						"rol" => $this->session->getSession ()->get ( 'rol' ),
						"listar" => "0",
						"insertar" => "0",
						"modificar" => "0",
						"eliminar" => "0" 
				) );
			} else {
				return $this->render ( 'AppBundle:Index:form_hothotel.html.twig', array (
						"hoteles" => $this->hoteles->listarHotelPorUsuario ( $idUsuario ),
						"caracteristicas" => $this->caracteristicas->listar (),
						"usuario" => $this->session->getSession ()->get ( 'name' ),
						"email" => $this->session->getSession ()->get ( 'email' ),
						"rol" => $this->session->getSession ()->get ( 'rol' ),
						"listar" => $this->session->getSession ()->get ( 'Hotel' )->listar,
						"insertar" => $this->session->getSession ()->get ( 'Hotel' )->insertar,
						"modificar" => $this->session->getSession ()->get ( 'Hotel' )->modificar,
						"eliminar" => $this->session->getSession ()->get ( 'Hotel' )->eliminar 
				) );
			}
		}
	}
	public function insertarHotelAction(Request $request) {
		$this->caracteristicas = new \hotcarhotel ();
		$this->session = new Session ();
		return $this->render ( 'AppBundle:Index:insertarHotel.html.twig', array (
				"titulo" => "Hotel",
				"error" =>"",
				"caracteristicas" => $this->caracteristicas->listar (),
				"usuario" => $this->session->get ( 'name' ),
				"email" => $this->session->get ( 'email' ),
				"rol" => $this->session->get ( 'rol' ) 
		) );
	}
	public function agregarHotelAction(Request $request) {
		$this->hoteles = new \hothoteles ();
		$this->log = new \logs ();
		$this->session = new Session ();
		$idUsuario = $this->session->get ( 'idUsuario' );
		// $this->hoteles->setHotIdUsu($idUsuario);
		$this->hoteles->setHotNombre ( $request->request->get ( 'hotel' ) );
		$pNombreHotel = strtoupper ( $request->request->get ( 'hotel' ) );
		$existe = $this->hoteles->verificarExistenciaHotel ( $pNombreHotel );
		if ($existe == false) {
			$this->log->setLog ( "INPUT", " EL HOTEL NO EXISTE ", __METHOD__, __LINE__ );
			$this->hoteles->setHotDsc ( $request->request->get ( 'descripcion' ) );
			$this->hoteles->setHotCoordenadas ( $request->request->get ( 'geolocalizacion' ) );
			$this->hoteles->setHotDireccion ( $request->request->get ( 'direccion' ) );
			$this->hoteles->setHotLocalidad ( $request->request->get ( 'localidad' ) );
			$this->hoteles->setHotTelefono ( $request->request->get ( 'telefono' ) );
			$this->hoteles->setHotTelefono2 ( $request->request->get ( 'telefono2' ) );
			$this->hoteles->setHotTelefono3 ( $request->request->get ( 'telefono3' ) );
			$this->hoteles->setHotCategoria ( $request->request->get ( 'categoria' ) );
			$this->hoteles->setHotEstado ( $request->request->get ( 'estado' ) );
			$this->hoteles->setHotPolCancel ( $request->request->get ( 'politica' ) );
			$this->hoteles->setHotFecAlta ( date ( "Y-m-d" ) );
			$this->hoteles->setHotPublica ( "N" );
			$this->hoteles->setHottipocta ( $request->request->get ( 'tipo' ) );
			$this->hoteles->setHotnrocuenta ( $request->request->get ( 'cuenta' ) );
			$this->hoteles->setHotabonado ( $request->request->get ( 'abonado' ) );
			$this->hoteles->setHottipdoc ( $request->request->get ( 'tipodoc' ) );
			$this->hoteles->setHotdocumento ( $request->request->get ( 'documento' ) );
			$this->hoteles->setHotcomplemento ( $request->request->get ( 'complemento' ) );
			$this->hoteles->insertar ();
			$id = $this->hoteles->getIdUltimaInsercion ();
			
			$c = count ( $request->request->get ( 'caracteristica' ) );
			$array = array ();
			
			for($i = 0; $i < $c; $i ++) {
				$hot_carhot = new \hothotel_hotcarhot ();
				$hot_carhot->setHotCarHotel ( $request->request->get ( 'caracteristica' ) [$i] );
				$hot_carhot->setHotIdHotel ( $id );
				$hot_carhot->insertar ();
			}
			// $this->caracteristicas = new \hotcarhotel();
			// Usuario - Hotel
			$this->usuario = new \hotusu_hothot ();
			$this->usuario->setHotIdUsu ( $idUsuario );
			$this->usuario->setHotidhotel ( $id );
			$this->usuario->insertar ();
			// En caso de que un usuario que no sea el administrador sistema cree el hotel
			// tambien pueda visualizar el usuario administrador de sistema
			if ($idUsuario != 1) {
				// Usuario Adminitrador
				$this->usuario->setHotIdUsu ( 1 );
				$this->usuario->setHotidhotel ( $id );
				$this->usuario->insertar ();
			}
			return $this->form_hothotelAction ();
		} else {
			// Mensaje de error ya Existe el Hotel
			$this->log->setLog ( "INPUT", " EL HOTEL YA EXISTE ", __METHOD__, __LINE__ );
			$this->caracteristicas = new \hotcarhotel ();
			$this->session = new Session ();
			return $this->render ( 'AppBundle:Index:insertarHotel.html.twig', array (
					"titulo" => "Hotel",
					"error" =>" El Hotel ya se encuentra registrado ",
					"caracteristicas" => $this->caracteristicas->listar (),
					"usuario" => $this->session->get ( 'name' ),
					"email" => $this->session->get ( 'email' ),
					"rol" => $this->session->get ( 'rol' )
			) );
		}
	}
	public function modificarHotelAction(Request $request) {
		$this->hoteles = new \hothoteles ();
		$this->caracteristicas = new \hotcarhotel ();
		$this->session = new Session ();
		$idHotelElegido = $request->request->get ( 'idHotel' );
		$datosHotel = $this->hoteles->obtenerDatosHotel ( $idHotelElegido );
		$carHotel = $this->hoteles->obtenerCaracteristicasModificar ( $idHotelElegido );
		
		return $this->render ( 'AppBundle:Index:modificarHotel.html.twig', array (
				"hoteles" => $datosHotel,
				"caracteristicas" => $this->caracteristicas->listarModificarHotel ( $carHotel ),
				"usuario" => $this->session->get ( 'name' ),
				"email" => $this->session->get ( 'email' ),
				"rol" => $this->session->get ( 'rol' ) 
		) );
	}
	public function cambiarHotelAction(Request $request) {
		$this->hoteles = new \hothoteles ();
		$this->session = new Session ();
		$idUsuario = $this->session->get ( 'idUsuario' );
		$idHotel = $request->request->get ( 'idHotel' );
		$hotnombre = $request->request->get ( 'hotel' );
		$this->hoteles->idhotel = $idHotel;
		$this->hoteles->setHotNombre ( $hotnombre );
		$this->hoteles->setHotDsc ( $request->request->get ( 'descripcion' ) );
		$this->hoteles->setHotCoordenadas ( $request->request->get ( 'geolocalizacion' ) );
		$this->hoteles->setHotDireccion ( $request->request->get ( 'direccion' ) );
		$this->hoteles->setHotLocalidad ( $request->request->get ( 'localidad' ) );
		$this->hoteles->setHotTelefono ( $request->request->get ( 'telefono' ) );
		$this->hoteles->setHotTelefono2 ( $request->request->get ( 'telefono2' ) );
		$this->hoteles->setHotTelefono3 ( $request->request->get ( 'telefono3' ) );
		$this->hoteles->setHotCategoria ( $request->request->get ( 'categoria' ) );
		$this->hoteles->setHotEstado ( $request->request->get ( 'estado' ) );
		$this->hoteles->setHotPolCancel ( $request->request->get ( 'politica' ) );
		$this->hoteles->setHotFecAlta ( date ( "Y-m-d" ) );
		$this->hoteles->setHotPublica ( "N" );
		
		$this->hoteles->setHottipocta ( $request->request->get ( 'tipo' ) );
		$this->hoteles->setHotnrocuenta ( $request->request->get ( 'cuenta' ) );
		$this->hoteles->setHotabonado ( $request->request->get ( 'abonado' ) );
		$this->hoteles->setHottipdoc ( $request->request->get ( 'tipodoc' ) );
		$this->hoteles->setHotdocumento ( $request->request->get ( 'documento' ) );
		$this->hoteles->setHotcomplemento ( $request->request->get ( 'complemento' ) );
		
		$this->hoteles->modificar ();
		
		// Caracteristicas
		$hot_carhot = new \hothotel_hotcarhot ();
		// Eliminar todas las caracteristicas
		$hot_carhot->eliminarTodo ( $idHotel );
		$c = count ( $request->request->get ( 'caracteristica' ) );
		$array = array ();
		for($i = 0; $i < $c; $i ++) {
			$hot_carhot->setHotCarHotel ( $request->request->get ( 'caracteristica' ) [$i] );
			$hot_carhot->setHotIdHotel ( $idHotel );
			$hot_carhot->insertar ();
		}
		$this->caracteristicas = new \hotcarhotel ();
		return $this->form_hothotelAction ();
	}
	public function eliminarHotelAction(Request $request) {
		$idhotel = $request->request->get ( 'idHotelEliminar' );
		/*
		// Hotel
		$this->hoteles = new \hothoteles ();
		// Caracteristicas
		$hot_carhot = new \hothotel_hotcarhot ();
		// Eliminar todas las caracteristicas
		$hot_carhot->eliminarTodo ( $idhotel );
		// Eliminar hotel
		$this->hoteles->idhotel = $idhotel;
		$this->hoteles->eliminar ();
		
		$this->caracteristicas = new \hotcarhotel ();
		*/
		$this->hoteles = new \hothoteles ($idhotel);
		$this->hoteles->setHotEstado("3");
		$this->hoteles->cambiarEstadoHotel();
		return $this->form_hothotelAction ();
	}
	
	public function publicarHotelAction(Request $request) {
		$this->log = new \logs ();
		$idHotel = $request->request->get ( 'idHotel' );
		$hotpublica = $request->request->get ( 'hotelPublicar' );
		//$this->log->setLog ( "INPUT", $idHotel, __METHOD__, __LINE__ );
		//$this->log->setLog ( "INPUT", $hotpublica, __METHOD__, __LINE__ );
		$this->hoteles = new \hothoteles ($idHotel);
		$hotPublicaCambia;
		if($hotpublica == 1){
			$hotPublicaCambia = 0;
		}else{
			$hotPublicaCambia = 1;
		}
		$this->hoteles->setHotPublica($hotPublicaCambia);
		$this->hoteles->cambiarPublicacionHotel();
		return $this->form_hothotelAction ();
	}
	public function registrarHotelAction(Request $request) {
		$this->caracteristicas = new \hotcarhotel ();
		$this->session = new Session ();
		return $this->render ( 'AppBundle:Index:registrarHotel.html.twig', array (
				"titulo" => "Hotel",
				"error" =>"",
				"caracteristicas" => $this->caracteristicas->listar ()				
		) );
	}
	public function insertarHotelUsuarioAction(Request $request){
		//Insertar registros de Hotel
		$this->hoteles = new \hothoteles ();
		$this->log = new \logs ();
		$this->hoteles->setHotNombre ( $request->request->get ( 'hotel' ) );
		$pNombreHotel = strtoupper ( $request->request->get ( 'hotel' ) );
		$existe = $this->hoteles->verificarExistenciaHotel ( $pNombreHotel );
		$idhotel;
		if ($existe == false) {
			$this->log->setLog ( "INPUT", " EL HOTEL NO EXISTE ", __METHOD__, __LINE__ );
			$this->hoteles->setHotDsc ( $request->request->get ( 'descripcion' ) );
			$this->hoteles->setHotCoordenadas ( $request->request->get ( 'geolocalizacion' ) );
			$this->hoteles->setHotDireccion ( $request->request->get ( 'direccion' ) );
			$this->hoteles->setHotLocalidad ( $request->request->get ( 'localidad' ) );
			$this->hoteles->setHotTelefono ( $request->request->get ( 'telefono' ) );
			$this->hoteles->setHotTelefono2 ( $request->request->get ( 'telefono2' ) );
			$this->hoteles->setHotTelefono3 ( $request->request->get ( 'telefono3' ) );
			$this->hoteles->setHotCategoria ( $request->request->get ( 'categoria' ) );
			$this->hoteles->setHotEstado ( $request->request->get ( 'estado' ) );
			$this->hoteles->setHotPolCancel ( $request->request->get ( 'politica' ) );
			$this->hoteles->setHotFecAlta ( date ( "Y-m-d" ) );
			$this->hoteles->setHotPublica ( "N" );
			$this->hoteles->insertar ();
			//Recuperar idhotel 
			$idhotel = $this->hoteles->getIdUltimaInsercion ();
			
			//Caracteristicas	
			$c = count ( $request->request->get ( 'caracteristica' ) );
			$array = array ();
			for($i = 0; $i < $c; $i ++) {
				$hot_carhot = new \hothotel_hotcarhot ();
				$hot_carhot->setHotCarHotel ( $request->request->get ( 'caracteristica' ) [$i] );
				$hot_carhot->setHotIdHotel ( $idhotel );
				$hot_carhot->insertar ();
			}
		} else {
			// Mensaje de error ya Existe el Hotel
			$this->log->setLog ( "INPUT", " EL HOTEL YA EXISTE ", __METHOD__, __LINE__ );
			return $this->render('AppBundle:Index:index.html.twig', array(
					"saludo" => "",
					"error" => " El Hotel ya se encuentra registrado "
			));
		}
		//Insertar Usuario
		$hotusuario = new \hotusuario();
		$hotusuario->setUsuNombre($request->request->get('usuario'));
		$hotusuario->setUsuEmail($request->request->get('email'));
		$password = password_hash($request->request->get('pswd'), PASSWORD_DEFAULT);
		$hotusuario->setUsuCi($request->request->get('ci'));
		$hotusuario->setUsuTelefono($request->request->get('telefono'));
		$hotusuario->setUsuFechaNac($request->request->get('nacimiento'));
		$hotusuario->setUsuPassword($password);
		$hotusuario->setUsuEstado("1");
		$hotusuario->setUsuFechaAlta(date("Y-m-d"));
		$hotusuario->setUsuFecMod(date("Y-m-d H:i:s"));
		$hotusuario->insertar();
		//Recuperar idUsuario
		$idUsuario = $hotusuario->getIdUltimaInsercion();
		// Roles
		$rol_usu = new \hotusu_hotrol();
		$rol_usu->hotidusu = $idUsuario;
		$rol_usu->hotidrol = 1;
		$rol_usu->insertar();
		//Insertar en la tabla(nuevo usuario) hotusu_hothot
		$this->usuario = new \hotusu_hothot ();
		$this->usuario->setHotIdUsu ( $idUsuario );
		$this->usuario->setHotidhotel ( $idhotel );
		$this->usuario->insertar ();
		//Insertar hotusu_hothot con Administrador General
		$this->usuario->setHotIdUsu ( 1 );
		$this->usuario->setHotidhotel ( $idhotel );
		$this->usuario->insertar ();
		
		return $this->render('AppBundle:Index:index.html.twig', array(
            "saludo" => "",
            "error" => " Bienvenido al sistema, por favor ingrese su email y contraseña"
        ));
	}
}
