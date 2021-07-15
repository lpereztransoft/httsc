<?php
require_once 'conexionDB.php';
require_once 'logs.php';

class hottiphab
{
	const NOMBRE_TABLA = "hottiphab";
	public  $idtiphab;
	private $tipdsc;
	private $tipfecalta;

	private $log;
	private $idInsertado;

	public function __construct($idtiphab=null){
		$this->log = new logs();
		$this->idtiphab = $idtiphab;
	}
	public function setTipDsc($tipdsc){
		$this->tipdsc = $tipdsc;
	}
	public function setTipFecAlta($tipfecalta){
		$this->tipfecalta = $tipfecalta;
	}
	public function getNombreTabla(){
		return self::NOMBRE_TABLA;
	}
	public function getIdUltimaInsercion(){
        return $this->idInsertado;
    }
    public function insertar(){
		$model = new conexionDB();
		$con = $model->conectar();
		$con->exec ( "SET CHARACTER SET utf8" );

		$response = new stdClass();
		$response->tipdsc = $this->tipdsc;
		$response->tipfecalta = $this->tipfecalta;
		$this->log->setLog("INPUT", $response, __METHOD__, __LINE__);

		$sql = "INSERT INTO ".self::NOMBRE_TABLA." (tipdsc, tipfecalta)";
		$sql .= " VALUES (:tipdsc, :tipfecalta)";
		$consulta = $con->prepare($sql);
		$consulta->bindParam(':tipdsc', $this->tipdsc);
		$consulta->bindParam(':tipfecalta', $this->tipfecalta);
		$consulta->execute();
		$this->idInsertado = $con->lastInsertId();
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
	public function modificar(){
		$model = new conexionDB();
		$con = $model->conectar();
		$con->exec ( "SET CHARACTER SET utf8" );
		
		$response = new stdClass();
		$response->idtiphab = $this->idtiphab;
		$response->tipdsc = $this->tipdsc;
		$this->log->setLog("INPUT", $response, __METHOD__, __LINE__);

		$sql = "UPDATE ".self::NOMBRE_TABLA." SET tipdsc = :tipdsc WHERE idtiphab = :idtiphab";
		$consulta = $con->prepare($sql);
		$consulta->bindParam(':idtiphab', $this->idtiphab);
		$consulta->bindParam(':tipdsc', $this->tipdsc);
		$consulta->execute();
		$con = null;
	}
	public function eliminar(){
		$model = new conexionDB();
		$con = $model->conectar();

		$response = new stdClass();
		$response->idtiphab = $this->idtiphab;
		$this->log->setLog("INPUT", $response, __METHOD__, __LINE__);

		$sql = "DELETE FROM ".self::NOMBRE_TABLA." WHERE idtiphab = :idtiphab";
		$consulta = $con->prepare($sql);
		$consulta->bindParam(':idtiphab', $this->idtiphab);
		$consulta->execute();
		$con = null;
	}
}

?>