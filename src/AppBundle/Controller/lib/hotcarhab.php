<?php
require_once 'conexionDB.php';
require_once 'logs.php';
class hotcarhab{
	const NOMBRE_TABLA = "hotcarhab";
	private $idcarhab;
	private $carhabdsc;
	private $carhabfecalta;

	private $log;
	private $idInsertado;

	public function __construct($idcarhab=null){
		$this->log = new logs();
		$this->idcarhab = $idcarhab;
	}
	public function setCarHabDsc($carhabdsc){
		$this->carhabdsc = $carhabdsc;
	}
	public function setCarHabFecAlta($carhabfecalta){
		$this->carhabfecalta = $carhabfecalta;
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
		$response->carhabdsc = $this->carhabdsc;
		$response->carhabfecalta = $this->carhabfecalta;
		$this->log->setLog("INPUT", $response, __METHOD__, __LINE__);

		$sql = "INSERT INTO ".self::NOMBRE_TABLA." (carhabdsc, carhabfecalta)";
		$sql .= " VALUES (:carhabdsc, :carhabfecalta)";
		$consulta = $con->prepare($sql);
		$consulta->bindParam(':carhabdsc', $this->carhabdsc);
		$consulta->bindParam(':carhabfecalta', $this->carhabfecalta);
		$consulta->execute();
		$this->idInsertado = $con->lastInsertId();
		$con = null;
    }
    public function listar(){
    	$model = new conexionDB();
		$con = $model->conectar();
		$con->exec ( "SET CHARACTER SET utf8" );

    	$sql = "SELECT * FROM ".self::NOMBRE_TABLA;
    	$sql .= " order by  carhabdsc";
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
		$response->idcarhab = $this->idcarhab;
		$response->carhabdsc = $this->carhabdsc;
		$this->log->setLog("INPUT", $response, __METHOD__, __LINE__);

		$sql = "UPDATE ".self::NOMBRE_TABLA." SET carhabdsc = :carhabdsc";
		$sql .= " WHERE idcarhab = :idcarhab";
		$consulta = $con->prepare($sql);
		$consulta->bindParam(':idcarhab', $this->idcarhab);
		$consulta->bindParam(':carhabdsc', $this->carhabdsc);
		$consulta->execute();
		$con = null;
    }
    public function eliminar(){
    	$model = new conexionDB();
		$con = $model->conectar();

		$response = new stdClass();
		$response->idcarhab = $this->idcarhab;
		$this->log->setLog("INPUT", $response, __METHOD__, __LINE__);

		$sql = "DELETE FROM ".self::NOMBRE_TABLA." WHERE idcarhab = :idcarhab";
		$consulta = $con->prepare($sql);
		$consulta->bindParam(':idcarhab', $this->idcarhab);
		$consulta->execute();
		$con = null;
    }
}

?>