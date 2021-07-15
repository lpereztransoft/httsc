<?php
require_once 'conexionDB.php';
require_once 'logs.php';

class hotroles
{
	const NOMBRE_TABLA = "hotroles";
	public  $idrol;
	private $roldsc;
	private $rolfecalta;

	private $log;
	private $idInsertado;

	public function __construct($idrol=null){
		$this->log = new logs();
		$this->idrol = $idrol;
	}
	public function setRolDsc($roldsc){
		$this->roldsc = $roldsc;
	}
	public function setRolFecAlta($rolfecalta){
		$this->rolfecalta = $rolfecalta;
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
		$response->roldsc = $this->roldsc;
		$response->rolfecalta = $this->rolfecalta;
		$this->log->setLog("INPUT", $response, __METHOD__, __LINE__);

		$sql = "INSERT INTO ".self::NOMBRE_TABLA." (roldsc, rolfecalta)";
		$sql .= " VALUES (:roldsc, :rolfecalta)";
		$consulta = $con->prepare($sql);
		$consulta->bindParam(':roldsc', $this->roldsc);
		$consulta->bindParam(':rolfecalta', $this->rolfecalta);
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
	public function listarRolesSinGeneral($idUsuario){
		$model = new conexionDB();
		$con = $model->conectar();
		$con->exec ( "SET CHARACTER SET utf8" );
		if($idUsuario == 1){
			$sql = "SELECT * FROM ".self::NOMBRE_TABLA;
		}else{
    		$sql = "SELECT * FROM ".self::NOMBRE_TABLA;
    		$sql .= " WHERE idrol != 3 ";
    	}
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
		$response->idrol = $this->idrol;
		$response->roldsc = $this->roldsc;
		$this->log->setLog("INPUT", $response, __METHOD__, __LINE__);

		$sql = "UPDATE ".self::NOMBRE_TABLA." SET roldsc = :roldsc WHERE idrol = :idrol";
		$consulta = $con->prepare($sql);
		$consulta->bindParam(':idrol', $this->idrol);
		$consulta->bindParam(':roldsc', $this->roldsc);
		$consulta->execute();
		$con = null;
	}
	public function eliminar(){
		$model = new conexionDB();
		$con = $model->conectar();

		$response = new stdClass();
		$response->idrol = $this->idrol;
		$this->log->setLog("INPUT", $response, __METHOD__, __LINE__);

		$sql = "DELETE FROM ".self::NOMBRE_TABLA." WHERE idrol = :idrol";
		$consulta = $con->prepare($sql);
		$consulta->bindParam(':idrol', $this->idrol);
		$consulta->execute();
		$con = null;
	}
}

?>