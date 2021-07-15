<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

require_once 'lib/hotrol_hotrec.php';
require_once 'lib/hotrecursos.php';

class HotRecRolController extends Controller
{
    public $helper;
    public $log;
    public $recursos;
    public $hotrol_hotrec;
    private $logs;
    public $session;

    public function form_hotrecursos_rolesAction(Request $request)
    {
        $this->helper = $this->get("Helper");
        $this->log = $this->get("Log");
        $idRol = $request->request->get('idRol');
        $this->hotrol_hotrec = new \hotrol_hotrec();
        $this->session = new Session();

        return $this->render('AppBundle:Index:form_hotrecursos_roles.html.twig', array(
            "recursos" => $this->hotrol_hotrec->listarRecursos($idRol),
            "idRol" =>$idRol,
        	"usuario" => $this->session->get('name'),
        	"email" => $this->session->get('email'),
        	"rol" => $this->session->get('rol')
        ));
    }
    public function insertarRecRolAction(Request $request){
        $this->recursos = new \hotrecursos();
        $idRolIns = $request->request->get('idRolInsertar');
        $this->session = new Session();

        return $this->render('AppBundle:Index:insertarRecRol.html.twig', array(
            "recursos" => $this->recursos->listar(),
            "idRolIns" => $idRolIns,
        	"usuario" => $this->session->get('name'),
        	"email" => $this->session->get('email'),
        	"rol" => $this->session->get('rol')
        ));
    }
    public function agregarRecRolAction(Request $request){ 
        $this->hotrol_hotrec = new \hotrol_hotrec();
        $this->hotrol_hotrec->setHotIdRol($request->request->get('idRol'));
        $this->hotrol_hotrec->setHotIdRec($request->request->get('recurso'));
        $this->hotrol_hotrec->setListar($request->request->get('listar'));
        $this->hotrol_hotrec->setInsertar($request->request->get('insertar'));
        $this->hotrol_hotrec->setModificar($request->request->get('modificar'));
        $this->hotrol_hotrec->setEliminar($request->request->get('eliminar'));
        $this->hotrol_hotrec->setCambiarEstado(0);
        $this->hotrol_hotrec->insertar(); 
        return $this->form_hotrecursos_rolesAction($request);
    }
    
    public function modificarRecRolAction(Request $request){
        $this->recursos = new \hotrecursos();
        $idRolMod = $request->request->get('idRol');
        $idRec= $request->request->get('idRec');
        $recDsc= $request->request->get('recDsc');
        $idListar = $request->request->get('idListar');
        $idInsertar = $request->request->get('idInsertar');
        $idModificar = $request->request->get('idModificar');
        $idEliminar = $request->request->get('idEliminar');
        $this->session = new Session();
        return $this->render('AppBundle:Index:modificarRecRol.html.twig', array(
            "recursos" => $this->recursos->listar(),
             "idRolMod" => $idRolMod,
             "idRec" => $idRec,
             "recDsc" => $recDsc,
             "idListar" => $idListar,
             "idInsertar" => $idInsertar,
             "idModificar" => $idModificar,
             "idEliminar" => $idEliminar,
             "usuario" => $this->session->get('name'),
             "email" => $this->session->get('email'),
             "rol" => $this->session->get('rol')
        ));
    }
    
    public function cambiarRecRolAction(Request $request){ 
        $this->hotrol_hotrec = new \hotrol_hotrec();
        $this->hotrol_hotrec->setHotIdRol($request->request->get('idRolMod'));
        $this->hotrol_hotrec->setHotIdRec($request->request->get('recurso'));
        $this->hotrol_hotrec->setListar($request->request->get('listar'));
        $this->hotrol_hotrec->setInsertar($request->request->get('insertar'));
        $this->hotrol_hotrec->setModificar($request->request->get('modificar'));
        $this->hotrol_hotrec->setEliminar($request->request->get('eliminar'));
        $this->hotrol_hotrec->setCambiarEstado(0);
        $this->hotrol_hotrec->modificar();
        $this->session = new Session(); 
        return $this->render('AppBundle:Index:form_hotrecursos_roles.html.twig', array(
            "recursos" => $this->hotrol_hotrec->listarRecursos($request->request->get('idRolMod')),
            "idRol" =>$request->request->get('idRolMod'),
        	"usuario" => $this->session->get('name'),
        	"email" => $this->session->get('email'),
        	"rol" => $this->session->get('rol')
        ));
    }
    public function eliminarRecRolAction(Request $request){ 
        $this->hotrol_hotrec = new \hotrol_hotrec();
        $this->hotrol_hotrec->setHotIdRol($request->request->get('idRolEliminar'));
        $this->hotrol_hotrec->setHotIdRec($request->request->get('idRec'));
        $this->hotrol_hotrec->eliminar();
        $this->session = new Session();
        return $this->render('AppBundle:Index:form_hotrecursos_roles.html.twig', array(
            "recursos" => $this->hotrol_hotrec->listarRecursos($request->request->get('idRolEliminar')),
            "idRol" =>$request->request->get('idRolEliminar'), 
            "usuario" => $this->session->get('name'),
            "email" => $this->session->get('email'),
            "rol" => $this->session->get('rol')
        ));
    }
}
