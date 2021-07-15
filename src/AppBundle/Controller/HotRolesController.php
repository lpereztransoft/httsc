<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

require_once 'lib/hotroles.php';

class HotRolesController extends Controller
{
    public $helper;
    public $log;
    public $roles;
    private $logs;
    public $session;

    public function form_hotrolesAction()
    {
        $this->helper = $this->get("Helper");
        $this->log = $this->get("Log");
        $this->roles = new \hotroles();
        $this->session = new Session();
        if(empty($this->session->get('Roles')->listar)){
            return $this->render('AppBundle:Index:form_hotroles.html.twig', array(
            "roles" => $this->roles->listar(),
            "usuario" => $this->session->get('name'),
            "email" => $this->session->get('email'),
            "rol" => $this->session->get('rol'),
            "listar" => "0",
            "insertar" => "0",
            "modificar" => "0",
            "eliminar" => "0"
        ));
        }else{
            return $this->render('AppBundle:Index:form_hotroles.html.twig', array(
            "roles" => $this->roles->listar(),
            "usuario" => $this->session->get('name'),
            "email" => $this->session->get('email'),
            "rol" => $this->session->get('rol'),
            "listar" => $this->session->get('Roles')->listar,
            "insertar" => $this->session->get('Roles')->insertar,
            "modificar" => $this->session->get('Roles')->modificar,
            "eliminar" => $this->session->get('Roles')->eliminar    
        ));
        }
        
    }

    public function insertarRolAction(Request $request){
    	$this->session = new Session();
        return $this->render('AppBundle:Index:insertarRol.html.twig', array(        	
            "titulo" => "Recurso",
        	"usuario" => $this->session->get('name'),
        	"email" => $this->session->get('email'),
        	"rol" => $this->session->get('rol')
        ));;
    }

    public function agregarRolAction(Request $request){ 
        $this->roles = new \hotroles();
        $this->roles->setRolDsc($request->request->get('rol'));
        $this->roles->setRolFecAlta(date("Y-m-d"));
        $this->roles->insertar(); 
        return $this->form_hotrolesAction();
    }
    public function modificarRolAction(Request $request){
    	$this->session = new Session();
        return $this->render('AppBundle:Index:modificarRol.html.twig', array(
            "idRol" =>  $request->request->get('idRol'),
            "rolDsc" => $request->request->get('rolDsc'),
        	"usuario" => $this->session->get('name'),
        	"email" => $this->session->get('email'),
        	"rol" => $this->session->get('rol')
        ));
    }
    public function cambiarRolAction(Request $request){ 
        $this->roles = new \hotroles();
        $idRol = $request->request->get('idRol');
        $rolDsc = $request->request->get('rol');
        $this->roles->idrol = $idRol;
        $this->roles->setRolDsc($rolDsc);
        $this->roles->modificar();
        return $this->form_hotrolesAction();
    }
    public function eliminarRolAction(Request $request){ 
        $this->roles = new \hotroles();
        $idRol = $request->request->get('idRolEliminar');
        $this->roles->idrol = $idRol;
        $this->roles->eliminar();
        return $this->form_hotrolesAction();
    }
}
