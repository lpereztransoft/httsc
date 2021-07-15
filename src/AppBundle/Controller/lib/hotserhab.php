<?php
require_once 'conexionDB.php';
require_once 'logs.php';
class hotserhab{
	const NOMBRE_TABLA = "hotserhab";
	private $idserhab;
	private $serhabdsc;
	private $serhabfecalta;

	private $log;
	private $idInsertado;

	public function __construct($idserhab=null){
		$this->log = new logs();
		$this->idserhab = $idserhab;
	}
	public function setSerHabDsc($serhabdsc){
		$this->serhabdsc = $serhabdsc;
	}
	public function setSerHabFecalta($serhabfecalta){
		$this->serhabfecalta = $serhabfecalta;
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
		$response->serhabdsc = $this->serhabdsc;
		$response->serhabfecalta = $this->serhabfecalta;
		$this->log->setLog("INPUT", $response, __METHOD__, __LINE__);

		$sql = "INSERT INTO ".self::NOMBRE_TABLA." (serhabdsc, serhabfecalta)";
		$sql .= " VALUES (:serhabdsc, :serhabfecalta)";
		$consulta = $con->prepare($sql);
		$consulta->bindParam(':serhabdsc', $this->serhabdsc);
		$consulta->bindParam(':serhabfecalta', $this->serhabfecalta);
		$consulta->execute();
		$this->idInsertado = $con->lastInsertId();
		$con = null;
    }
    public function listar(){
    	$model = new conexionDB();
		$con = $model->conectar();
		$con->exec ( "SET CHARACTER SET utf8" );

    	$sql = "SELECT * FROM ".self::NOMBRE_TABLA;
    	$sql .= " order by  serhabdsc";
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
		$response->idserhab = $this->idserhab;
		$response->serhabdsc = $this->serhabdsc;
		$this->log->setLog("INPUT", $response, __METHOD__, __LINE__);

		$sql = "UPDATE ".self::NOMBRE_TABLA." SET serhabdsc = :serhabdsc";
		$sql .= " WHERE idserhab = :idserhab";
		
		$consulta = $con->prepare($sql);
		$consulta->bindParam(':idserhab', $this->idserhab);
		$consulta->bindParam(':serhabdsc', $this->serhabdsc);
		$consulta->execute();
		$con = null;
    }
    public function eliminar(){
    	$model = new conexionDB();
		$con = $model->conectar();

		$response = new stdClass();
		$response->idserhab = $this->idserhab;
		$this->log->setLog("INPUT", $response, __METHOD__, __LINE__);

		$sql = "DELETE FROM ".self::NOMBRE_TABLA." WHERE idserhab = :idserhab";
		$consulta = $con->prepare($sql);
		$consulta->bindParam(':idserhab', $this->idserhab);
		$consulta->execute();
		$con = null;
    }
}

?>