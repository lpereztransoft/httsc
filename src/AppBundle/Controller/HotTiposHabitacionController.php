<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

require_once 'lib/hottiphab.php';

class HotTiposHabitacionController extends Controller
{
    public $helper;
    public $log;
    public $tiphab;
    private $logs;
    public $session;

    public function form_hottiposhabAction()
    {
        $this->helper = $this->get("Helper");
        $this->log = $this->get("Log");
        $this->tiphab = new \hottiphab();
        $this->session = new Session();
        $idUsuario = $this->session->get('idUsuario');
    	if(empty($this->session->get('Tipo_Habitacion')->listar)){
            return $this->render('AppBundle:Index:form_hottiposhab.html.twig', array(            
            "tipos" => $this->tiphab->listar(),
            "usuario" => $this->session->get('name'),
            "email" => $this->session->get('email'),            
            "rol" => $this->session->get('rol'),
            "listar" => "0",
            "insertar" => "0",
            "modificar" => "0",
            "eliminar" => "0"
        ));
        }else{
           return $this->render('AppBundle:Index:form_hottiposhab.html.twig', array(            
            "tipos" => $this->tiphab->listar(),
            "usuario" => $this->session->get('name'),
        	"email" => $this->session->get('email'),        	
        	"rol" => $this->session->get('rol'),
            "listar" => $this->session->get('Tipo_Habitacion')->listar,
            "insertar" => $this->session->get('Tipo_Habitacion')->insertar,
            "modificar" => $this->session->get('Tipo_Habitacion')->modificar,
            "eliminar" => $this->session->get('Tipo_Habitacion')->eliminar
        ));
        }
    }

    public function insertarTipoHabAction(Request $request){
    	$this->session = new Session();
        return $this->render('AppBundle:Index:insertarTipHab.html.twig', array(
            "titulo" => "Tipo Habitacion",
        	"usuario" => $this->session->get('name'),
        	"email" => $this->session->get('email'),
        	"rol" => $this->session->get('rol')
        ));
    }

    public function agregarTipoHabAction(Request $request){ 
        $this->tiphab = new \hottiphab();
        $this->tiphab->setTipDsc($request->request->get('tipo'));
        $this->tiphab->setTipFecAlta(date("Y-m-d"));
        $this->tiphab->insertar(); 
        return $this->form_hottiposhabAction();
    }
    public function modificarTipoHabAction(Request $request){
    	$this->session = new Session();
        return $this->render('AppBundle:Index:modificarTipHab.html.twig', array(
            "idTipHab" =>  $request->request->get('idTipHab'),
            "tipDsc" => $request->request->get('tipDsc'),
        	"usuario" => $this->session->get('name'),
        	"email" => $this->session->get('email'),
        	"rol" => $this->session->get('rol')
        ));
    }
    public function cambiarTipoHabAction(Request $request){ 
        $this->tiphab = new \hottiphab();
        $idTipHab = $request->request->get('idTipHab');
        $tipDsc = $request->request->get('tipo');
        $this->tiphab->idtiphab = $idTipHab;
        $this->tiphab->setTipDsc($tipDsc);
        $this->tiphab->modificar();
        return $this->form_hottiposhabAction();
    }
    public function eliminarTipoHabAction(Request $request){ 
        $this->tiphab = new \hottiphab();
        $idTipHab = $request->request->get('idTipHabEliminar');
        $this->tiphab->idtiphab = $idTipHab;
        $this->tiphab->eliminar();
        return $this->form_hottiposhabAction();
    }
}
