<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;


require_once 'lib/hotimagenes.php';

class HotImagenesController extends Controller
{
	public $imagen;
	public $session;

	public function listarImagenesAction(Request $request){
		$this->imagen = new \hotimagenes();
		$this->session = new Session();
		$idHotel = $request->request->get('idHotel');
		$hotelNombre = $request->request->get('hotelNombre');
		return $this->render('AppBundle:Index:listarImagenes.html.twig', array(
        	"imagenes" => $this->imagen->listarPorHotel($idHotel),
			"idHotel" => $idHotel,
			"usuario" => $this->session->get('name'),
			"email" => $this->session->get('email'),
			"rol" => $this->session->get('rol'),
			"nombre" => $hotelNombre,
        ));
	}
	public function insertarImagenAction(Request $request){
		$this->session = new Session();
		$idHotel = $request->request->get('idHotel');
		$hotelNombre = $request->request->get('hotelNombre');

		return $this->render('AppBundle:Index:insertarImagen.html.twig', array(
			"usuario" => $this->session->get('name'),
			"email" => $this->session->get('email'),
			"rol" => $this->session->get('rol'),
			"idHotel" => $idHotel,
			"nombre" => $hotelNombre,
			"mensaje_error" => ""
		));
	}

	public function agregarImagenAction(Request $request){
		/*
		getClientOriginalName()  // Obtiene el nombre del archivo
		getClientOriginalExtension() //Obtiene la extension del archivo
		getClientSize() // Obtiene el tamaño del archivo
		*/
		/*
		$idHotel = $request->request->get('idHotel');
		$hotelNombre = $request->request->get('hotelNombre');
		$directorio = "images/";
		foreach ( $request->files->get('images') as $img ) {
			$nombre_imagen = uniqid("IMG_").".".$img->getClientOriginalExtension();
			$img->move($directorio,$nombre_imagen);
			$this->imagen = new \hotimagenes();
			$this->imagen->setHotIdhotel($idHotel);
			$this->imagen->setImgDsc( $request->request->get('descripcion') );
			$this->imagen->setImgImagen($directorio.$nombre_imagen);
			$this->imagen->setImgFecAlta(date("Y-m-d"));
			$this->imagen->insertar();
		}
		return $this->listarImagenesAction($request);
		*/

		$idHotel = $request->request->get('idHotel');
		$hotelNombre = $request->request->get('hotelNombre');
		$directorio = "images/";
		$img = $request->files->get('image');

		$this->session = new Session();
		$mensaje_error = "";

		//getCLienteSize() -> devuelve en bytes
		//1048576 es un 1 MB
		if( $img->getClientSize() <= 1048576 ){
			$nombre_imagen = uniqid("IMG_").".".$img->getClientOriginalExtension();
			$img->move($directorio, $nombre_imagen);
			$this->imagen = new \hotimagenes();
			$this->imagen->setHotIdhotel($idHotel);
			$this->imagen->setImgDsc( $request->request->get('descripcion') );
			$this->imagen->setImgImagen($directorio.$nombre_imagen);
			$this->imagen->setImgFecAlta(date("Y-m-d"));
			$this->imagen->insertar();
			return $this->listarImagenesAction($request);
		}

		else{
			return $this->render('AppBundle:Index:insertarImagen.html.twig', array(
				"usuario" => $this->session->get('name'),
				"email" => $this->session->get('email'),
				"rol" => $this->session->get('rol'),
				"idHotel" => $idHotel,
				"nombre" => $hotelNombre,
				"mensaje_error" => "El tamaño de la imagen excede a lo permitido. Por favor seleccione otra imagen..."
			));
		}
	}
	public function eliminarImagenesAction(Request $request){
		$idHotel = $request->request->get('idHotel');
		$hotelNombre = $request->request->get('hotelNombre');
		$info_images = explode("|", $request->request->get('info_img'));
		$c = count($info_images) - 1;
		for( $i = 0; $i < $c; $i++ ){
			$this->imagen = new \hotimagenes($info_images[$i]);
			unlink($this->imagen->getImgImagen($info_images[$i]));
			$this->imagen->setHotIdhotel($idHotel);
			$this->imagen->eliminar();
		}
		return $this->listarImagenesAction($request);
	}
}