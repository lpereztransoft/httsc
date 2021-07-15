<?php
require_once 'conexionDB.php';
require_once 'logs.php';

class hothotel_hotcarhot
{
	const NOMBRE_TABLA = "hothotel_hotcarhot";

	private $hotidHotel;
	private $hotcarhotel;

	private $log;

	public function __construct(){
		$this->log = new logs();
	}

	public function setHotIdHotel($hotidHotel){
		$this->hotidHotel = $hotidHotel;
	}
	public function setHotCarHotel($hotcarhotel){
		$this->hotcarhotel = $hotcarhotel;
	}

	public function insertar(){
		$model = new conexionDB();
		$con = $model->conectar();
		$con->exec ( "SET CHARACTER SET utf8" );
		
		$response = new stdClass();
		$response->hotidHotel = $this->hotidHotel;
		$response->hotcarhotel = $this->hotcarhotel;
		$this->log->setLog("INPUT", $response, __METHOD__, __LINE__);

		$sql = "INSERT INTO ".self::NOMBRE_TABLA." (hotidHotel, hotcarhotel)";
		$sql .= " VALUES (:hotidHotel, :hotcarhotel)";
		$consulta = $con->prepare($sql);
		$consulta->bindParam(':hotidHotel', $this->hotidHotel);
		$consulta->bindParam(':hotcarhotel', $this->hotcarhotel);
		$consulta->execute();
		$con = null;
	}
	public function eliminar(){
		$model = new conexionDB();
		$con = $model->conectar();

		$response = new stdClass();
		$response->hotidHotel = $this->hotidHotel;
		$response->hotcarhotel = $this->hotcarhotel;
		$this->log->setLog("INPUT", $response, __METHOD__, __LINE__);

		$sql = "DELETE FROM ".self::NOMBRE_TABLA." WHERE hotidHotel = :hotidHotel AND hotcarhotel = :hotcarhotel";
		$consulta = $con->prepare($sql);
		$consulta->bindParam(':hotidHotel', $this->hotidHotel);
		$consulta->bindParam(':hotcarhotel', $this->hotcarhotel);
		$consulta->execute();
		$con = null;
	}
	public function eliminarTodo($hotidHotel){
		$model = new conexionDB();
		$con = $model->conectar();

		$response = new stdClass();
		$response->hotidHotel = $this->hotidHotel;
		$response->hotcarhotel = $this->hotcarhotel;
		$this->log->setLog("INPUT", $response, __METHOD__, __LINE__);

		$sql = "DELETE FROM ".self::NOMBRE_TABLA." WHERE hotidHotel = :hotidHotel";
		$consulta = $con->prepare($sql);
		$consulta->bindParam(':hotidHotel', $hotidHotel);
		$consulta->execute();
		$con = null;
	}

}
?>