<?php
require_once 'conexionDB.php';
require_once 'logs.php';

class hothab_hotserhab
{
	const NOMBRE_TABLA = "hothab_hotserhab";
	private $hotidserhab;
	private $hotidhabitacion;

	private $log;

	public function __construct(){
		$this->log = new logs();
	}
	public function setHotIdSerHab($hotidserhab){
		$this->hotidserhab = $hotidserhab;
	}
	public function setHotIdHabitacion($hotidhabitacion){
		$this->hotidhabitacion = $hotidhabitacion;
	}
	public function insertar(){
		$model = new conexionDB();
		$con = $model->conectar();
		$con->exec ( "SET CHARACTER SET utf8" );

		$response = new stdClass();
		$response->hotidserhab = $this->hotidserhab;
		$response->hotidhabitacion = $this->hotidhabitacion;
		$this->log->setLog("INPUT", $response, __METHOD__, __LINE__);

		$sql = "INSERT INTO ".self::NOMBRE_TABLA." (hotidserhab, hotidhabitacion)";
		$sql .= " VALUES (:hotidserhab, :hotidhabitacion)";
		$consulta = $con->prepare($sql);
		$consulta->bindParam(':hotidserhab', $this->hotidserhab);
		$consulta->bindParam(':hotidhabitacion', $this->hotidhabitacion);
		$consulta->execute();
		$con = null;
	}
	public function eliminar(){
		$model = new conexionDB();
		$con = $model->conectar();

		$response = new stdClass();
		$response->hotidserhab = $this->hotidserhab;
		$response->hotidhabitacion = $this->hotidhabitacion;
		$this->log->setLog("INPUT", $response, __METHOD__, __LINE__);

		$sql = "DELETE FROM ".self::NOMBRE_TABLA." WHERE hotidserhab = :hotidserhab AND hotidhabitacion = :hotidhabitacion";
		$consulta = $con->prepare($sql);
		$consulta->bindParam(':hotidserhab', $this->hotidserhab);
		$consulta->bindParam(':hotidhabitacion', $this->hotidhabitacion);
		$consulta->execute();
		$con = null;
	}
	
	public function getIdServicios($idhabitacion){

		$model = new conexionDB();
		$con = $model->conectar();
		$con->exec ( "SET CHARACTER SET utf8" );

		$sql = "SELECT hothab_hotserhab.hotidserhab FROM hothab_hotserhab WHERE hothab_hotserhab.hotidhabitacion = ".$idhabitacion;

		$consulta = $con->prepare($sql);
    	$consulta->execute();
    	$response = $consulta->fetchAll(PDO::FETCH_COLUMN, 0);
		$con = null;

		return $response;

	}
	
}
?>
