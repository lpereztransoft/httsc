<?php
namespace AppBundle\Controller;

ini_set ( "SMTP", "mailsrv.transoft.bo" );
ini_set ( "sendmail_from", "ventas@mail.transoft.bo" );
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;


require_once 'lib/hotusuario.php';
require_once 'lib/hotroles.php';
require_once 'lib/hotusu_hotrol.php';
require_once 'lib/hothoteles.php';
require_once 'lib/hotcarhotel.php';
require_once 'lib/hotusu_hothot.php';
require_once 'lib/hothabitaciones.php';
require_once 'lib/logs.php';

class HotUsuariosController extends Controller
{
    public $helper;
    public $usuarios;
    public $log;
    public $roles;
    public $rol_usu;
    public $usu_hot;
    public $hoteles;
    public $caracteristicas;
    public $session;
    public $habitacion;

    public function form_hotusuariosAction()
    {
        $this->helper = $this->get("Helper");
        //$this->log = $this->get("Log");
        $this->usuarios = new \hotusuario();
        $this->session = new Session();
        $idUsuario = $this->session->get('idUsuario');
        if(empty($this->session->get('Usuarios')->listar)){
          return $this->render('AppBundle:Index:form_hotusuarios.html.twig', array(
            "usuarios" => $this->usuarios->listar($idUsuario),
            "usuario" => $this->session->get('name'),
          	"email" => $this->session->get('email'),
          	"rol" => $this->session->get('rol'),
            "listar" => "0",
            "insertar" => "0",
            "modificar" => "0",
            "eliminar" => "0"
        ));    
        }else{
        return $this->render('AppBundle:Index:form_hotusuarios.html.twig', array(
            "usuarios" => $this->usuarios->listar($idUsuario),
            "usuario" => $this->session->get('name'),
        	"email" => $this->session->get('email'),
        	"rol" => $this->session->get('rol'),
            "listar" => $this->session->get('Usuarios')->listar,
            "insertar" => $this->session->get('Usuarios')->insertar,
            "modificar" => $this->session->get('Usuarios')->modificar,
            "eliminar" => $this->session->get('Usuarios')->eliminar    
        ));
        }
    }
    public function insertarUsuarioAction(Request $request){
        $this->usuarios = new \hotusuario();
        $this->roles = new \hotroles();
        $this->session = new Session();
        $idUsuario = $this->session->get('idUsuario');
        $hoteles = $this->usuarios->obtenerHotelesPorUsuario($idUsuario);
        return $this->render('AppBundle:Index:insertarUsuario.html.twig', array(
            "titulo" => "Recurso",
            "roles" =>$this->roles->listarRolesSinGeneral($idUsuario),
        	"usuario" => $this->session->get('name'),
        	"email" => $this->session->get('email'),
        	"rol" => $this->session->get('rol'),
            "hoteles" => $hoteles
        ));;
    }
    public function agregarUsuarioAction(Request $request){ 
        $this->usuarios = new \hotusuario();
        $this->usuarios->setUsuNombre($request->request->get('usuario'));
        $this->usuarios->setUsuEmail($request->request->get('email'));
        $password = password_hash($request->request->get('pswd'), PASSWORD_DEFAULT);
        $this->usuarios->setUsuCi($request->request->get('ci'));
        $this->usuarios->setUsuTelefono($request->request->get('telefono'));
        $this->usuarios->setUsuFechaNac($request->request->get('nacimiento'));
        $this->usuarios->setUsuPassword($password);
        $this->usuarios->setUsuEstado("1");
        $this->usuarios->setUsuFechaAlta(date("Y-m-d"));
        $this->usuarios->setUsuFecMod(date("Y-m-d H:i:s"));
        $this->usuarios->insertar(); 
        $id = $this->usuarios->getIdUltimaInsercion();
        // Roles
        $this->rol_usu = new \hotusu_hotrol();
        $this->rol_usu->hotidusu = $id;
        $this->rol_usu->hotidrol = $request->request->get('rol');
        $this->rol_usu->insertar();
        //Hoteles
       /* $this->usu_hot = new \hotusu_hothot();
        $idHotel = $request->request->get('hoteles');
        $this->usu_hot->setHotIdUsu($id);
        $this->usu_hot->setHotidhotel($idHotel);
        $this->usu_hot->insertar();*/
        $c = count( $request->request->get('hotel') );
        $array = array();
        //Hoteles
        for( $i = 0; $i < $c; $i++ ){
        	$this->usu_hot = new \hotusu_hothot();
        	$this->usu_hot->setHotIdUsu($id);
        	$this->usu_hot->setHotidhotel( $request->request->get('hotel')[$i] );
        	$this->usu_hot->insertar();
        }
        return $this->form_hotusuariosAction();
    }
    public function modificarUsuarioAction(Request $request){ 
        $this->roles = new \hotroles();
        $this->rol_usu = new \hotusu_hotrol();
        $this->usu_hot = new \hotusu_hothot();
        $this->usuarios = new \hotusuario();
        $this->session = new Session();
        $idUsuario = $this->session->get('idUsuario');
        $hoteles = $this->usuarios->obtenerHotelesPorUsuario($idUsuario);
        //Obtener los hoteles que tiene el acceso el usuario
        $hotelesAcceso = $this->usu_hot->obtenerHotel($request->request->get('idUsu'));
        $this->session = new Session();
        return $this->render('AppBundle:Index:modificarUsuario.html.twig', array(
            "idUsu" =>  $request->request->get('idUsu'),
            "usuNombre" => $request->request->get('usuNombre'),
            "usuEmail" => $request->request->get('usuEmail'),
            "usuEstado"=> $request->request->get('usuEstado'),
        	"usuCi"=> $request->request->get('usuCi'),
        	"usuTelefono"=> $request->request->get('usuTelefono'),
        	"usuNacimiento"=> $request->request->get('usuNacimiento'),	
            "rolBD" =>  $this->rol_usu->obtenerRoles($request->request->get('idUsu')),
            "hotBD" => $this->usu_hot->obtenerHotel($request->request->get('idUsu')),
            "hoteles" => $hoteles,
        	"hotelesAcceso" => $hotelesAcceso,
            "roles" =>$this->roles->listarRolesSinGeneral($idUsuario),
        	"usuario" => $this->session->get('name'),
        	"email" => $this->session->get('email'),
        	"rol" => $this->session->get('rol')
        ));
    }
    public function cambiarUsuarioAction(Request $request){ 
        $this->usuarios = new \hotusuario();
        
        $this->usuarios->idusu = $request->request->get('idUsu');
        $this->usuarios->setUsuNombre($request->request->get('usuario'));
        $this->usuarios->setUsuEmail($request->request->get('email'));
        $password = password_hash($request->request->get('pswd'), PASSWORD_DEFAULT);
        $this->usuarios->setUsuPassword($password);
        $this->usuarios->setUsuEstado($request->request->get('habilitado'));
        $this->usuarios->setUsuCi($request->request->get('ci'));
        $this->usuarios->setUsuTelefono($request->request->get('telefono'));
        $this->usuarios->setUsuFechaNac($request->request->get('nacimiento'));
        $this->usuarios->setUsuFecMod(date("Y-m-d H:i:s"));
        $this->usuarios->modificar();
        //Rol
        $this->rol_usu = new \hotusu_hotrol();
        $this->rol_usu->eliminarTodo($request->request->get('idUsu'));
        //Insertar
        $this->rol_usu->hotidusu = $request->request->get('idUsu');
        $this->rol_usu->hotidrol = $request->request->get('rol');
        $this->rol_usu->insertar();
        //Elminar Accesos a hoteles
        $this->usu_hot = new \hotusu_hothot();
        $this->usu_hot->eliminarTodo($request->request->get('idUsu'));
        //Insertar Acceso de Hoteles
        $c = count( $request->request->get('hotel') );
        for( $i = 0; $i < $c; $i++ ){
        	$this->usu_hot->setHotidhotel($request->request->get('hotel')[$i]);
        	$this->usu_hot->setHotIdUsu($request->request->get('idUsu'));
        	$this->usu_hot->insertar();
        }
        return $this->form_hotusuariosAction();
    }
    public function eliminarUsuarioAction(Request $request){ 
        $idUsu = $request->request->get('idUsuEliminar');
        //Roles
        $this->rol_usu = new \hotusu_hotrol();
        $this->rol_usu->eliminarTodo($idUsu);
        //Hoteles
        $this->usu_hot = new \hotusu_hothot();
        $this->usu_hot->eliminarTodo($idUsu);
        //Usuario
        $this->usuarios = new \hotusuario();
        $this->usuarios->idusu = $idUsu;
        $this->usuarios->eliminar();
        return $this->form_hotusuariosAction();
    }
    public function validarLoginAction(Request $request){
        $this->usuarios = new \hotusuario();
        $email = $request->request->get('email');
        $contrasena = $request->request->get('contrasena');
        $correcto =$this->usuarios->verificarDatosUsuario($email,$contrasena);
        if($correcto){
        	$this->session = new Session();
        	$idUsuario = $this->session->get('idUsuario');
        	$email = $this->session->get('email');
        	$rol = $this->session->get('rol');
        	$rolId = $this->session->get('rolId');
        	//Si es recepcionista
        	if($rolId == 2){
        		//Entra al listar de habitaciones
        		$this->hoteles = new \hothoteles();
        		$this->habitacion =  new \hothabitaciones();
        		$listarHoteles =$this->hoteles->listarHotelPorUsuario($idUsuario);
        		$tamListarHoteles= sizeof($listarHoteles);
        		if(empty($this->session->get('Habitaciones')->listar)){
        			//return $this->render('AppBundle:Index:listarHabitaciones.html.twig', array(
        			return $this->render('AppBundle:Index:listarHabitacionesDisponibilidad.html.twig', array(
        					"habitaciones" => $this->habitacion->listar(),
        					"usuario" => $this->session->get('name'),
        					"email" => $this->session->get('email'),
        					"rol" => $this->session->get('rol'),
        					"listar" => "0",
        					"insertar" => "0",
        					"modificar" => "0",
        					"eliminar" => "0",
        					"hoteles"  =>  $listarHoteles,
        					"tamHoteles" => $tamListarHoteles
        			));
        		}else{
        			if($tamListarHoteles > 1){
        				$hotelID = $request->request->get('idHotel');
        				if(empty($hotelID)){
        					//return $this->render('AppBundle:Index:listarHabitaciones.html.twig', array(
        					return $this->render('AppBundle:Index:listarHabitacionesDisponibilidad.html.twig', array(
        							"habitaciones" => $this->habitacion->listarPorHotelDisponibilidad(0),
        							"usuario" => $this->session->get('name'),
        							"email" => $this->session->get('email'),
        							"rol" => $this->session->get('rol'),
        							"listar" => $this->session->get('Habitaciones')->listar,
        							"insertar" => $this->session->get('Habitaciones')->insertar,
        							"modificar" => $this->session->get('Habitaciones')->modificar,
        							"eliminar" => $this->session->get('Habitaciones')->eliminar,
        							"hoteles"  =>  $listarHoteles,
        							"tamHoteles" => $tamListarHoteles,
        							"idHotHotel" => 0
        					));
        				}else{
        					//return $this->render('AppBundle:Index:listarHabitaciones.html.twig', array(
        					return $this->render('AppBundle:Index:listarHabitacionesDisponibilidad.html.twig', array(
        							"habitaciones" => $this->habitacion->listarPorHotelDisponibilidad($hotelID),
        							"usuario" => $this->session->get('name'),
        							"email" => $this->session->get('email'),
        							"rol" => $this->session->get('rol'),
        							"listar" => $this->session->get('Habitaciones')->listar,
        							"insertar" => $this->session->get('Habitaciones')->insertar,
        							"modificar" => $this->session->get('Habitaciones')->modificar,
        							"eliminar" => $this->session->get('Habitaciones')->eliminar,
        							"hoteles"  =>  $listarHoteles,
        							"tamHoteles" => $tamListarHoteles,
        							"idHotHotel" => trim($hotelID)
        					));
        				}
        		
        			}else{
        				$idHotel;
        				foreach ($listarHoteles as $hoteles) {
        					$idHotel = $hoteles->idhotel;
        				}
        				//return $this->render('AppBundle:Index:listarHabitaciones.html.twig', array(
        				return $this->render('AppBundle:Index:listarHabitacionesDisponibilidad.html.twig', array(
        						"habitaciones" => $this->habitacion->listarPorHotelDisponibilidad($idHotel),
        						"usuario" => $this->session->get('name'),
        						"email" => $this->session->get('email'),
        						"rol" => $this->session->get('rol'),
        						"listar" => $this->session->get('Habitaciones')->listar,
        						"insertar" => $this->session->get('Habitaciones')->insertar,
        						"modificar" => $this->session->get('Habitaciones')->modificar,
        						"eliminar" => $this->session->get('Habitaciones')->eliminar,
        						"hoteles"  =>  $listarHoteles,
        						"tamHoteles" => $tamListarHoteles,
        						"idHotHotel" => trim($idHotel)
        				));
        			}
        		}
        	}else{
        	//Otro Rol
            $this->hoteles = new \hothoteles();
            $this->caracteristicas = new \hotcarhotel();
          
        return $this->render('AppBundle:Index:form_hothotel.html.twig', array(
            "hoteles" => $this->hoteles->listarHotelPorUsuario($idUsuario),
            "caracteristicas" => $this->caracteristicas->listar(),
             "usuario" => $this->session->get('name'),
             "listar" => $this->session->get('Hotel')->listar,
             "insertar" => $this->session->get('Hotel')->insertar,
             "modificar" => $this->session->get('Hotel')->modificar,
             "eliminar" => $this->session->get('Hotel')->eliminar,
             "email" => $email,        	 
        	 "rol" => $rol
        ));
        	}
        }else{
           return $this->render('AppBundle:Index:index.html.twig', array(
            "error" => "Contraseña o email incorrecto, vuelva a intentarlo "
            ));
        }
        
    }
     public function contactosAction(){
     	$this->session = new Session();
     	return $this->render('AppBundle:Index:form_contactos.html.twig', array(
     			"usuario" => $this->session->get('name'),
     			"email" => $this->session->get('email'),
     			"rol" => $this->session->get('rol'),
     			"mensaje" => ""
     	));;
     }
     public function enviarCorreoAction(Request $request){
     	$this->session = new Session();
     	$this->log = new \logs ();
     	$nombre = $request->request->get('nombre');
     	$apellido = $request->request->get('apellido');
     	$correoElectronico = $request->request->get('correoElectronico');
     	$telefono = $request->request->get('telefono');
     	$asunto = $request->request->get('asunto');
     	$input = ( object ) array (
     			"nombre" => $nombre,
     			"apellido" => $apellido,
     			"correoElectronico" => $correoElectronico,
     			"telefono" => $telefono,
     			"asunto" => $asunto
     	);
     	$this->log->setLog ( "INPUT", $input, __METHOD__, __LINE__ );
     	$mensaje = $this->enviarEmail($nombre, $apellido, $correoElectronico, $telefono,$asunto);
     	return $this->render('AppBundle:Index:form_contactos.html.twig', array(
     			"usuario" => $this->session->get('name'),
     			"email" => $this->session->get('email'),
     			"rol" => $this->session->get('rol'),
     			"mensaje" => $mensaje
     			
     	));;
     }
     
     
     public function enviarEmail($nombre, $apellido, $correoElectronico, $telefono,$asunto) {
     	//$filename = $nroReserva . ".pdf";
     	//$path = "/var/www/v2/hotel/voucher/";
     	//$file = $path . $filename;
     	//$file_size = filesize ( $file );
     	//$handle = fopen ( $file, "r" );
     	//$content = fread ( $handle, $file_size );
     	//fclose ( $handle );
     	//$content = chunk_split ( base64_encode ( $content ) );
     	$uid = md5 ( uniqid ( time () ) );
     	$eol = PHP_EOL;
     	$subject = " Contacto ".$nombre." ".$apellido." Telefono ".$telefono." Email ".$correoElectronico;
     	$messagenew = $asunto;
     	$mail = "tpaz@mail.transoft.bo";
     	$from_mail = "ventas@mail.transoft.bo";
     	$replyto = "tpaz@mail.transoft.bo";
     	$mailto = trim ( $mail );
     	//$mailto2 = "jtapia@transoft.bo";
     	$header = "From: " . $from_mail . "\n";
     	$header .= "Reply-To: " . $replyto . "\n";
     	$header .= "MIME-Version: 1.0\n";
     	$header .= "Content-Type: multipart/mixed; boundary=\"" . $uid . "\"\n\n";
     	$emessage = "--" . $uid . "\n";
     	$emessage .= "Content-type:text/plain; charset=iso-8859-1\n";
     	$emessage .= "Content-Transfer-Encoding: 7bit\n\n";
     	$emessage .= $messagenew . "\n\n";
     	//$emessage .= "--" . $uid . "\n";
     	//$emessage .= "Content-Type: application/octet-stream; name=\"" . $filename . "\"\n";
     	//$emessage .= "Content-Transfer-Encoding: base64\n";
     	//$emessage .= "Content-Disposition: attachment; filename=\"" . $filename . "\"\n\n";
     	//$emessage .= $content . "\n\n";
     	//$emessage .= "--" . $uid . "--";
     	$paramCorreo = ( object ) array (
     			"mailto" => $mailto,
     			"subject" => $subject,
     			"emessage" => $emessage,
     			"header" => $header
     	);
     	error_log ( "IP:" . $_SERVER ['REMOTE_ADDR'] . "--Parametros Correo [" . date ( 'Y-m-d H:i:s' ) . "]\r\n" . print_r ( $paramCorreo, true ) . "\r\n", 3, '/var/log/hotel/saleCancel.' . date ( 'Ymd' ) . '.log' );
     	if (mail ( $mailto, $subject, $emessage, $header )) {
     		//if (mail ( $mailto2, $subject, $emessage, $header )) {
     			return "Correo enviado satisfactoriamente";
     		//}
     	} else {
     		return "Error al enviar correo";
     	}
     }
}
