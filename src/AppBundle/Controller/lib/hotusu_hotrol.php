<?php
require_once 'conexionDB.php';
require_once 'logs.php';

class hotusu_hotrol
{
	const NOMBRE_TABLA = "hotusu_hotrol";
	public $hotidusu;
	public $hotidrol;

	private $log;

	public function __construct(){
		$this->log = new logs();
	}
	public function setHotIdUsu($hotidusu){
		$this->hotidusu = $hotidusu;
	}
	public function setHotIdRol($hotidrol){
		$this->hotidrol = $hotidrol;
	}
	public function insertar(){
		$model = new conexionDB();
		$con = $model->conectar();
		$con->exec ( "SET CHARACTER SET utf8" );

		$response = new stdClass();
		$response->hotidusu = $this->hotidusu;
		$response->hotidrol = $this->hotidrol;
		$this->log->setLog("INPUT", $response, __METHOD__, __LINE__);

		$sql = "INSERT INTO ".self::NOMBRE_TABLA." (hotidusu, hotidrol)";
		$sql .= " VALUES (:hotidusu, :hotidrol)";
		$consulta = $con->prepare($sql);
		$consulta->bindParam(':hotidusu', $this->hotidusu);
		$consulta->bindParam(':hotidrol', $this->hotidrol);
		$consulta->execute();
		$con = null;
	}
	public function eliminar(){
		$model = new conexionDB();
		$con = $model->conectar();

		$response = new stdClass();
		$response->hotidusu = $this->hotidusu;
		$response->hotirol = $this->hotidrol;
		$this->log->setLog("INPUT", $response, __METHOD__, __LINE__);

		$sql = "DELETE FROM ".self::NOMBRE_TABLA." WHERE hotidusu = :hotidusu AND hotidrol = :hotidrol";
		$consulta = $con->prepare($sql);
		$consulta->bindParam(':hotidusu', $this->hotidusu);
		$consulta->bindParam(':hotidrol', $this->hotidrol);
		$consulta->execute();
		$con = null;
	}
	public function obtenerRoles($idUsu){
		$model = new conexionDB();
		$con = $model->conectar();
		$con->exec ( "SET CHARACTER SET utf8" );
		
		$sql=" SELECT hotidrol FROM hotusu_hotrol ";
		$sql.=" WHERE hotidusu=".$idUsu;

		$consulta = $con->prepare($sql);
		$consulta->execute();
		$listado= $consulta->fetchAll(PDO::FETCH_OBJ);
		$response="";
		foreach($listado as $lista){
			//$obj = new stdClass();
			//$obj->hotidrol = $lista->hotidrol;
			$response = $lista->hotidrol;
		}
		$this->log->setLog("OUTPUT", $response, __METHOD__, __LINE__);
		$con = null;

		return $response;
	}
	public function eliminarTodo($idUsu){
		$model = new conexionDB();
		$con = $model->conectar();
		$sql = "DELETE FROM ".self::NOMBRE_TABLA." WHERE hotidusu = :hotidusu";
		$consulta = $con->prepare($sql);
		$consulta->bindParam(':hotidusu', $idUsu);
		$consulta->execute();
		$con = null;
	}
}
?>
