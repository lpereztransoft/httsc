<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

require_once 'lib/hotrecursos.php';

class HotRecursosController extends Controller
{
    public $helper;
    public $log;
    public $recursos;
    private $logs;
    public $session;

    public function form_hotrecursosAction()
    {
        $this->helper = $this->get("Helper");
        $this->log = $this->get("Log");
        $this->session = new Session();
        $this->recursos = new \hotrecursos();
        if(empty($this->session->get('Recursos')->listar)){
            return $this->render('AppBundle:Index:form_hotrecursos.html.twig', array(
            "recursos" => $this->recursos->listar(),
            "usuario" => $this->session->get('name'),
            "email" => $this->session->get('email'),
            "rol" => $this->session->get('rol'),
            "listar" => "0",
            "insertar" => "0",
            "modificar" => "0",
            "eliminar" => "0"
        ));
        }else{
            return $this->render('AppBundle:Index:form_hotrecursos.html.twig', array(
            "recursos" => $this->recursos->listar(),
            "usuario" => $this->session->get('name'),
            "email" => $this->session->get('email'),
            "rol" => $this->session->get('rol'),
            "listar" => $this->session->get('Recursos')->listar,
            "insertar" => $this->session->get('Recursos')->insertar,
            "modificar" => $this->session->get('Recursos')->modificar,
            "eliminar" => $this->session->get('Recursos')->eliminar    
        ));
        }
        
    }

    public function insertarRecursosAction(Request $request){
    	$this->session = new Session();
        return $this->render('AppBundle:Index:insertarRecursos.html.twig', array(
            "titulo" => "Recurso",
        	"usuario" => $this->session->get('name'),
        	"email" => $this->session->get('email'),
        	"rol" => $this->session->get('rol')
        ));;
    }

    public function agregarRecursoAction(Request $request){ 
        $this->recursos = new \hotrecursos();
        $this->recursos->setRecDsc($request->request->get('recurso'));
        $this->recursos->setRecFecAlta(date("Y-m-d"));
        $this->recursos->insertar(); 
        return $this->form_hotrecursosAction();
    }
    public function modificarRecursosAction(Request $request){ 
    	$this->session = new Session();
        return $this->render('AppBundle:Index:modificarRecursos.html.twig', array(
            "idRec" =>  $request->request->get('idRec'),
            "recDsc" => $request->request->get('recDsc'),
        	"usuario" => $this->session->get('name'),
        	"email" => $this->session->get('email'),
        	"rol" => $this->session->get('rol')
        ));
    }
    public function cambiarRecursoAction(Request $request){ 
        $this->recursos = new \hotrecursos();
        $idRec = $request->request->get('idRec');
        $recDsc = $request->request->get('recurso');
        $this->recursos->idrec = $idRec;
        $this->recursos->setRecDsc($recDsc);
        $this->recursos->modificar();
        return $this->form_hotrecursosAction();
    }
    public function eliminarRecursoAction(Request $request){ 
        $this->recursos = new \hotrecursos();
        $idRec = $request->request->get('idRecEliminar');
        $this->recursos->idrec = $idRec;
        $this->recursos->eliminar();
        return $this->form_hotrecursosAction();
    }
}
