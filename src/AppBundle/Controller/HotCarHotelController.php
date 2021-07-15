<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

require_once 'lib/hotcarhotel.php';

class HotCarHotelController extends Controller
{
    public $helper;
    public $log;
    public $caracteristicas;
    private $logs;
    public $session;

    public function form_hotcarhotelAction()
    {
        $this->helper = $this->get("Helper");
        $this->log = $this->get("Log");
        $this->session = new Session();
        $this->caracteristicas = new \hotcarhotel();
        if(empty($this->session->get('Caracteristicas_Hotel')->listar)){
        return $this->render('AppBundle:Index:form_hotcarhotel.html.twig', array(
            "caracteristicas" => $this->caracteristicas->listar(),
            "usuario" => $this->session->get('name'),
        	"email" => $this->session->get('email'),
        	"rol" => $this->session->get('rol'),
            "listar" => "0",
            "insertar" => "0",
            "modificar" => "0",
            "eliminar" => "0",
        ));    
    }else{
        return $this->render('AppBundle:Index:form_hotcarhotel.html.twig', array(
            "caracteristicas" => $this->caracteristicas->listar(),
            "usuario" => $this->session->get('name'),
        	"email" => $this->session->get('email'),
        	"rol" => $this->session->get('rol'),
            "listar" => $this->session->get('Caracteristicas_Hotel')->listar,
            "insertar" => $this->session->get('Caracteristicas_Hotel')->insertar,
            "modificar" => $this->session->get('Caracteristicas_Hotel')->modificar,
            "eliminar" => $this->session->get('Caracteristicas_Hotel')->eliminar    
        ));
        }
    }

    public function insertarCaracteristicaHotelAction(Request $request){
    	$this->session = new Session();	 	
        return $this->render('AppBundle:Index:insertarCaracteristicaHotel.html.twig', array(
            "titulo" => "Caracteristica",
        	"usuario" => $this->session->get('name'),
        	"email" => $this->session->get('email'),
        	"rol" => $this->session->get('rol')        		
        ));
    }
    public function agregarCaracteristicaHotelAction(Request $request){ 
        $this->caracteristicas = new \hotcarhotel();
        $this->caracteristicas->setCarHotDsc($request->request->get('caracteristica'));
        $this->caracteristicas->setCarHotFecAlta(date("Y-m-d"));
        $this->caracteristicas->insertar(); 
        return $this->form_hotcarhotelAction();
        /*return $this->render('AppBundle:Index:form_hotcarhotel.html.twig', array(
            "caracteristicas" => $this->caracteristicas->listar()
        ));*/
    }
    public function modificarCaracteristicaHotelAction(Request $request){    	
    	$this->session = new Session();
        return $this->render('AppBundle:Index:modificarCaracteristicaHotel.html.twig', array(
            "idcar" =>  $request->request->get('idCar'),
            "carDsc" => $request->request->get('carDsc'),
        	"usuario" => $this->session->get('name'),
        	"email" => $this->session->get('email'),
        	"rol" => $this->session->get('rol')
        ));
    }
    public function cambiarCaracteristicaHotelAction(Request $request){ 
        $this->caracteristicas = new \hotcarhotel();
        $idCar = $request->request->get('idCar');
        $carhotdsc = $request->request->get('caracteristica');
        $this->caracteristicas->idcarhotel = $idCar;
        $this->caracteristicas->setCarHotDsc($carhotdsc);
        $this->caracteristicas->modificar();
        return $this->form_hotcarhotelAction();
        /*return $this->render('AppBundle:Index:form_hotcarhotel.html.twig', array(
            "caracteristicas" => $this->caracteristicas->listar()
        ));*/
    }
    public function eliminarCaracteristicaHotelAction(Request $request){ 
        $this->caracteristicas = new \hotcarhotel();
        $idCar = $request->request->get('idCarEliminar');
        $this->caracteristicas->idcarhotel = $idCar;
        $this->caracteristicas->eliminar();
        return $this->form_hotcarhotelAction();
    }
}
