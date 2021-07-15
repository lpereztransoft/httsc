<?php
require_once 'conexionDB.php';
require_once 'logs.php';

class hothab_hotcarhab
{
	const NOMBRE_TABLA = "hothab_hotcarhab";
	private $hotidcarhab;
	private $hotidhabitacion;

	private $log;

	public function __construct(){
		$this->log = new logs();
	}
	public function setHotIdCarHab($hotidcarhab){
		$this->hotidcarhab = $hotidcarhab;
	}
	public function setHotIdHabitacion($hotidhabitacion){
		$this->hotidhabitacion = $hotidhabitacion;
	}
	public function insertar(){
		$model = new conexionDB();
		$con = $model->conectar();
		$con->exec ( "SET CHARACTER SET utf8" );

		$response = new stdClass();
		$response->hotidcarhab = $this->hotidcarhab;
		$response->hotidhabitacion = $this->hotidhabitacion;
		$this->log->setLog("INPUT", $response, __METHOD__, __LINE__);

		$sql = "INSERT INTO ".self::NOMBRE_TABLA." (hotidcarhab, hotidhabitacion)";
		$sql .= " VALUES (:hotidcarhab, :hotidhabitacion)";
		$consulta = $con->prepare($sql);
		$consulta->bindParam(':hotidcarhab', $this->hotidcarhab);
		$consulta->bindParam(':hotidhabitacion', $this->hotidhabitacion);
		$consulta->execute();
		$con = null;
	}
	public function eliminar(){
		$model = new conexionDB();
		$con = $model->conectar();

		$response = new stdClass();
		$response->hotidcarhab = $this->hotidcarhab;
		$response->hotidhabitacion = $this->hotidhabitacion;
		$this->log->setLog("INPUT", $response, __METHOD__, __LINE__);

		$sql = "DELETE FROM ".self::NOMBRE_TABLA." WHERE hotidcarhab = :hotidcarhab AND hotidhabitacion = :hotidhabitacion";
		$consulta = $con->prepare($sql);
		$consulta->bindParam(':hotidcarhab', $this->hotidcarhab);
		$consulta->bindParam(':hotidhabitacion', $this->hotidhabitacion);
		$consulta->execute();
		$con = null;
	}
	public function getIdCaracteristicas($idhabitacion){

		$model = new conexionDB();
		$con = $model->conectar();
		$con->exec ( "SET CHARACTER SET utf8" );

		$sql = "SELECT hothab_hotcarhab.hotidcarhab FROM hothab_hotcarhab WHERE hothab_hotcarhab.hotidhabitacion = ".$idhabitacion;

		$consulta = $con->prepare($sql);
    	$consulta->execute();
    	$response = $consulta->fetchAll(PDO::FETCH_COLUMN, 0);
		$con = null;

		return $response;

	}
}
?>
