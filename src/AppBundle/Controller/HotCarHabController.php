<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

require_once 'lib/hotcarhab.php';

class HotCarHabController extends Controller
{
    public $caracteristicas;
    public $session;

    public function listarCaracteristicasHabitacionAction(){
        $this->caracteristicas = new \hotcarhab();
        $this->session = new Session();
        if(empty($this->session->get('Caracteristicas_Habitacion')->listar)){
            return $this->render('AppBundle:Index:listarCaracteristicasHabitacion.html.twig', array(
            "caracteristicas" => $this->caracteristicas->listar(),
            "usuario" => $this->session->get('name'),            
            "email" => $this->session->get('email'),
            "rol" => $this->session->get('rol'),
            "listar" => "0",
            "insertar" => "0",
            "modificar" => "0",
            "eliminar" => "0"
        ));
        }else{
        return $this->render('AppBundle:Index:listarCaracteristicasHabitacion.html.twig', array(
            "caracteristicas" => $this->caracteristicas->listar(),
            "usuario" => $this->session->get('name'),        		
        	"email" => $this->session->get('email'),
        	"rol" => $this->session->get('rol'),
            "listar" => $this->session->get('Caracteristicas_Habitacion')->listar,
            "insertar" => $this->session->get('Caracteristicas_Habitacion')->insertar,
            "modificar" => $this->session->get('Caracteristicas_Habitacion')->modificar,
            "eliminar" => $this->session->get('Caracteristicas_Habitacion')->eliminar
        ));
        }
    }

    public function insertarCaracteristicaHabitacionAction(){
    	$this->session = new Session();
        return $this->render('AppBundle:Index:insertarCaracteristicaHabitacion.html.twig', array(
            "donde" => "formulario de insercion",
        	"usuario" => $this->session->get('name'),
        	"email" => $this->session->get('email'),
        	"rol" => $this->session->get('rol')
        ));
    }

    public function agregarCaracteristicaHabitacionAction(Request $request){

        $this->caracteristicas = new \hotcarhab();
        $this->caracteristicas->setCarHabDsc($request->request->get('caracteristica'));
        $this->caracteristicas->setCarHabFecAlta(date("Y-m-d"));
        $this->caracteristicas->insertar();

        return $this->listarCaracteristicasHabitacionAction();
    }

    public function modificarCaracteristicaHabitacionAction(Request $request){

        $this->caracteristicas = new \hotcarhab();
        $this->session = new Session();
        $info = $request->request->get('info_carhabitacion');
        list($idcaracteristica, $caracteristica) = explode("|", $info);

        return $this->render('AppBundle:Index:modificarCaracteristicaHabitacion.html.twig', array(
            "idcaracteristica" => $idcaracteristica,
            "caracteristica" => $caracteristica,
        	"usuario" => $this->session->get('name'),
        	"email" => $this->session->get('email'),
        	"rol" => $this->session->get('rol')
        ));
    }

    public function cambiarCaracteristicaHabitacionAction(Request $request){
        $this->caracteristicas = new \hotcarhab( $request->request->get('idcaracteristica') );
        $this->caracteristicas->setCarHabDsc($request->request->get('caracteristica'));
        $this->caracteristicas->modificar();
        return $this->listarCaracteristicasHabitacionAction();
    }

    public function eliminarCaracteristicaHabitacionAction(Request $request){
        $this->caracteristicas = new \hotcarhab( $request->request->get('id_eliminar') );
        $this->caracteristicas->eliminar();

        return $this->listarCaracteristicasHabitacionAction();
    }
}
