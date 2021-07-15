<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

require_once 'lib/hotusuario.php';

class IndexController extends Controller
{
    public $helper;
    public $log;
    public $usuarios;
    public $session;

    public function indexAction()
    {
        $this->helper = $this->get("Helper");
        $this->log = $this->get("Log");
        $this->usuarios = new \hotusuario();
        $this->session = new Session();
        $this->session->invalidate();
        return $this->render('AppBundle:Index:index.html.twig', array(
            "saludo" => $this->helper->getHola(),
            //"usuarios" => $this->usuarios->listar(),
            "error" => ""
        ));
    }

    public function registraUsuarioAction(Request $request){
        $this->usuarios = new \hotusuario();
        $this->usuarios->setUsuNombre($request->request->get('nombre'));
        $this->usuarios->setUsuEmail($request->request->get('email'));
        $this->usuarios->setUsupassword($request->request->get('password'));
        $this->usuarios->setUsuEstado(1);
        $this->usuarios->setUsuFechaAlta(date("Y-m-d"));
        $this->usuarios->setUsuFecMod(date("Y-m-d H:i:s"));
        $this->usuarios->insertar();
        return $this->render('AppBundle:Index:registraUsuario.html.twig', array(
            "nombre" => $request->request->get('nombre'),
            "email" => $request->request->get('email'),
            "password" => $request->request->get('password'),
            "fecha_alta" => date("Y-m-d"),
            "fecha_mod" => date("Y-m-d H:i:s")
        ));
    }
}
