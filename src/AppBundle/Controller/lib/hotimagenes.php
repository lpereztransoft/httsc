<?php
require_once 'conexionDB.php';
require_once 'logs.php';

class hotimagenes
{
	const NOMBRE_TABLA = "hotimagenes";
	private $idimagen;
	private $hotidhotel;
	private $imgdsc;
	private $imgimagen;
	private $imgfecalta;

	private $log;

	public function __construct($idimagen=null){
		$this->log = new logs();
		$this->idimagen = $idimagen;
	}
	public function setHotIdhotel($hotidhotel){
		$this->hotidhotel = $hotidhotel;
	}
	public function setImgDsc($imgdsc){
		$this->imgdsc = $imgdsc;
	}
	public function setImgImagen($imgimagen){
		$this->imgimagen = $imgimagen;
	}
	public function setImgFecAlta($imgfecalta){
		$this->imgfecalta = $imgfecalta;
	}
	public function getNombreTabla(){
		return self::NOMBRE_TABLA;
	}
	public function insertar(){
		$model = new conexionDB();
		$con = $model->conectar();
		$con->exec ( "SET CHARACTER SET utf8" );

		$response = new stdClass();
		$response->hotidhotel = $this->hotidhotel;
		$response->imgdsc = $this->imgdsc;
		$response->imgimagen = $this->imgimagen;
		$response->imgfecalta = $this->imgfecalta;
		$this->log->setLog("INPUT", $response, __METHOD__, __LINE__);

		$sql = "INSERT INTO ".self::NOMBRE_TABLA." (hotidhotel, imgdsc, imgimagen, imgfecalta)";
		$sql .= " VALUES (:hotidhotel, :imgdsc, :imgimagen, :imgfecalta)";
		$consulta = $con->prepare($sql);
		$consulta->bindParam(':hotidhotel', $this->hotidhotel);
		$consulta->bindParam(':imgdsc', $this->imgdsc);
		$consulta->bindParam(':imgimagen', $this->imgimagen);
		$consulta->bindParam(':imgfecalta', $this->imgfecalta);

		$consulta->execute();

		$con = null;
	}
	public function listar(){
		$model = new conexionDB();
		$con = $model->conectar();
		$con->exec ( "SET CHARACTER SET utf8" );

    	$sql = "SELECT * FROM ".self::NOMBRE_TABLA;
    	$consulta = $con->prepare($sql);
		$consulta->execute();

		$response = $consulta->fetchAll(PDO::FETCH_OBJ);
		$this->log->setLog("OUTPUT", $response, __METHOD__, __LINE__);
		$con = null;

		return $response;
	}
	public function listarPorHotel($idHotel){
		$model = new conexionDB();
		$con = $model->conectar();
		$con->exec ( "SET CHARACTER SET utf8" );
	
		$sql = "SELECT * FROM ".self::NOMBRE_TABLA;
		$sql .= " WHERE hotidhotel = ".$idHotel;
		
		$consulta = $con->prepare($sql);
		$consulta->execute();
	
		$response = $consulta->fetchAll(PDO::FETCH_OBJ);
		$this->log->setLog("OUTPUT", $response, __METHOD__, __LINE__);
		$con = null;
	
		return $response;
	}
	public function modificar(){
		$model = new conexionDB();
		$con = $model->conectar();
		$con->exec ( "SET CHARACTER SET utf8" );

		$response = new stdClass();
		$response->idimagen = $this->idimagen;
		$response->imgdsc = $this->imgdsc;
		$response->imgimagen = $this->imgimagen;
		$this->log->setLog("INPUT", $response, __METHOD__, __LINE__);

		$sql = "UPDATE ".self::NOMBRE_TABLA." SET imgdsc = :imgdsc, imgimagen = :imgimagen";
		$sql .= " WHERE idimagen = :idimagen";
		$consulta = $con->prepare($sql);

		$consulta->bindParam(':idimagen', $this->idimagen);
		$consulta->bindParam(':imgdsc', $this->imgdsc);
		$consulta->bindParam(':imgimagen', $this->imgimagen);

		$consulta->execute();
		$con = null;
	}
	public function eliminar(){
		$model = new conexionDB();
		$con = $model->conectar();

		$response = new stdClass();
		$response->idimagen = $this->idimagen;
		$this->log->setLog("INPUT", $response, __METHOD__, __LINE__);

		$sql = "DELETE FROM ".self::NOMBRE_TABLA." WHERE idimagen = :idimagen and hotidhotel= :hotidhotel";
		$consulta = $con->prepare($sql);
		$consulta->bindParam(':idimagen', $this->idimagen);
		$consulta->bindParam(':hotidhotel', $this->hotidhotel);
		$consulta->execute();
		$con = null;
	}
	public function getImgImagen($id){
		$model = new conexionDB();
		$con = $model->conectar();
		$con->exec ( "SET CHARACTER SET utf8" );
		
		$sql = "SELECT hotimagenes.imgimagen FROM hotimagenes WHERE hotimagenes.idimagen = ".$id;
	
		$consulta = $con->prepare($sql);
		$consulta->execute();
		$response = $consulta->fetch(PDO::FETCH_ASSOC)['imgimagen'];
		$con = null;
	
		return $response;
	}
}
?>