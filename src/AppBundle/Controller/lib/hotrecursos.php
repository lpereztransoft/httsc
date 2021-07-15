<?php
require_once 'conexionDB.php';
require_once 'logs.php';

class hotrecursos
{
	const NOMBRE_TABLA = "hotrecursos";
	public $idrec;
	private $recdsc;
	private $recfecalta;

	private $log;
	private $idInsertado;

	public function __construct($idrec=null){
		$this->log = new logs();
		$this->idrec = $idrec;
	}
	public function setRecDsc($recdsc){
		$this->recdsc = $recdsc;
	}
	public function setRecFecAlta($recfecalta){
		$this->recfecalta = $recfecalta;
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
		$response->recdsc = $this->recdsc;
		$response->recfecalta = $this->recfecalta;
		$this->log->setLog("INPUT", $response, __METHOD__, __LINE__);

		$sql = "INSERT INTO ".self::NOMBRE_TABLA." (recdsc, recfecalta)";
		$sql .= " VALUES (:recdsc, :recfecalta)";
		$consulta = $con->prepare($sql);
		$consulta->bindParam(':recdsc', $this->recdsc);
		$consulta->bindParam(':recfecalta', $this->recfecalta);
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
		$response->idrec = $this->idrec;
		$response->recdsc = $this->recdsc;
		$this->log->setLog("INPUT", $response, __METHOD__, __LINE__);

		$sql = "UPDATE ".self::NOMBRE_TABLA." SET recdsc = :recdsc";
		$sql .= " WHERE idrec = :idrec";
		$consulta = $con->prepare($sql);
		$consulta->bindParam(':idrec', $this->idrec);
		$consulta->bindParam(':recdsc', $this->recdsc);
		$consulta->execute();
		$con = null;
    }
    public function eliminar(){
    	$model = new conexionDB();
		$con = $model->conectar();
		
		$response = new stdClass();
		$response->idrec = $this->idrec;
		$this->log->setLog("INPUT", $response, __METHOD__, __LINE__);

		$sql = "DELETE FROM ".self::NOMBRE_TABLA." WHERE idrec = :idrec";
		$consulta = $con->prepare($sql);
		$consulta->bindParam(':idrec', $this->idrec);
		$consulta->execute();
		$con = null;
    }
}
?>