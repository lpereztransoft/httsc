<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

require_once 'lib/hothuesped.php';
require_once 'lib/logs.php';

class HotHuespedController extends Controller
{
	public $huespedes;
	public $session;

	public function listarHuespedesAction(Request $request){
		$this->huespedes = new \hothuesped();
		$this->session = new Session();
		
		list($id, $ingreso, $salida, $codigo, $nombrehabitacion, $tipohabitacion,$monto,$idHotel) = explode("|", $request->request->get('info_reserva'));
		return $this->render('AppBundle:Index:listarHuespedes.html.twig', array(
			"idhabitacion" => $request->request->get('idhabitacion'),
			"nombrehabitacion" => $nombrehabitacion,
			"tipohabitacion" => $tipohabitacion,
			"monto" => $monto,
			"idHotel" => $idHotel,
			"huespedes" => $this->huespedes->listarPorReserva( $id ),			
			"ingreso" => $ingreso,
			"salida" => $salida,
			"codigo" => $codigo,
			"usuario" => $this->session->get('name'),
        	"email" => $this->session->get('email'),
        	"rol" => $this->session->get('rol'),
		));
	}
	public function busquedaHuespedesAction(Request $request){
		
	}
}

?>