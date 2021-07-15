<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

require_once 'lib/hotserhab.php';

class HotSerHabController extends Controller
{
    public $servicios;
    public $session;

    public function listarServiciosHabitacionAction(){
        $this->servicios = new \hotserhab();
        $this->session = new Session();
        if(empty($this->session->get('Servicios_Habitacion')->listar)){
            return $this->render('AppBundle:Index:listarServiciosHabitacion.html.twig', array(
            "servicios" => $this->servicios->listar(),
            "usuario" => $this->session->get('name'),            
            "email" => $this->session->get('email'),
            "rol" => $this->session->get('rol'),
            "listar" => "0",
            "insertar" => "0",
            "modificar" => "0",
            "eliminar" => "0"
        ));
        }else{
        return $this->render('AppBundle:Index:listarServiciosHabitacion.html.twig', array(
            "servicios" => $this->servicios->listar(),
            "usuario" => $this->session->get('name'),        		
        	"email" => $this->session->get('email'),
        	"rol" => $this->session->get('rol'),
            "listar" => $this->session->get('Servicios_Habitacion')->listar,
            "insertar" => $this->session->get('Servicios_Habitacion')->insertar,
            "modificar" => $this->session->get('Servicios_Habitacion')->modificar,
            "eliminar" => $this->session->get('Servicios_Habitacion')->eliminar
        ));
        }
    }

    public function insertarServicioHabitacionAction(){
    	$this->session = new Session();
        return $this->render('AppBundle:Index:insertarServicioHabitacion.html.twig', array(
            "donde" => "formulario de insercion",
        	"usuario" => $this->session->get('name'),
        	"email" => $this->session->get('email'),
        	"rol" => $this->session->get('rol')
        ));
    }

    public function agregarServicioHabitacionAction(Request $request){

        $this->servicios = new \hotserhab();
        $this->servicios->setSerHabDsc($request->request->get('servicio'));
        $this->servicios->setSerHabFecAlta(date("Y-m-d"));
        $this->servicios->insertar();

        return $this->listarServiciosHabitacionAction();
    }

    public function modificarServicioHabitacionAction(Request $request){

        $this->servicios = new \hotserhab();
        $this->session = new Session();
        $info = $request->request->get('info_serhabitacion');
        list($idservicio, $servicio) = explode("|", $info);

        return $this->render('AppBundle:Index:modificarServicioHabitacion.html.twig', array(
            "idservicio" => $idservicio,
            "servicio" => $servicio,
        	"usuario" => $this->session->get('name'),
        	"email" => $this->session->get('email'),
        	"rol" => $this->session->get('rol')
        ));
    }

    public function cambiarServicioHabitacionAction(Request $request){
        $this->servicios = new \hotserhab( $request->request->get('idservicio') );
        $this->servicios->setSerHabDsc($request->request->get('servicio'));
        $this->servicios->modificar();
        return $this->listarServiciosHabitacionAction();
    }

    public function eliminarServicioHabitacionAction(Request $request){
        $this->servicios = new \hotserhab( $request->request->get('id_eliminar') );
        $this->servicios->eliminar();

        return $this->listarServiciosHabitacionAction();
    }
}
