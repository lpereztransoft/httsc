<?php
require_once 'conexionDB.php';
require_once 'logs.php';

class hotrol_hotrec
{
	const NOMBRE_TABLA = "hotrol_hotrec";
	private $listar;
	private $insertar;
	private $modificar;
	private $eliminar;
	private $cambiarEstado;
	public $hotidrol;
	public $hotidrec;

	private $log;

	public function __construct(){
		$this->log = new logs();
	}
	public function setListar($listar){
		$this->listar = $listar;
	}
	public function setInsertar($insertar){
		$this->insertar = $insertar;
	}
	public function setModificar($modificar){
		$this->modificar = $modificar;
	}
	public function setEliminar($eliminar){
		$this->eliminar = $eliminar;
	}
	public function setCambiarEstado($cambiarEstado){
		$this->cambiarEstado = $cambiarEstado;
	}
	public function setHotIdRol($hotidrol){
		$this->hotidrol = $hotidrol;
	}
	public function setHotIdRec($hotidrec){
		$this->hotidrec = $hotidrec;
	}

	public function insertar(){
		$model = new conexionDB();
		$con = $model->conectar();
		$con->exec ( "SET CHARACTER SET utf8" );

		$response = new stdClass();
		$response->hotidrol = $this->hotidrol;
		$response->hotidrec = $this->hotidrec;
		$response->listar = $this->listar;
		$response->insertar = $this->insertar;
		$response->modificar = $this->modificar;
		$response->eliminar = $this->eliminar;
		$response->cambiarEstado = $this->cambiarEstado;
		$this->log->setLog("INPUT", $response, __METHOD__, __LINE__);

		$sql = "INSERT INTO ".self::NOMBRE_TABLA." (hotidrol,hotidrec,listar, insertar, modificar, eliminar, cambiarEstado)";
		$sql .= " VALUES (:hotidrol,:hotidrec,:listar, :insertar, :modificar, :eliminar, :cambiarEstado)";
		$consulta = $con->prepare($sql);

        $consulta->bindParam(':hotidrol', $this->hotidrol);
        $consulta->bindParam(':hotidrec', $this->hotidrec);
		$consulta->bindParam(':listar', $this->listar);
		$consulta->bindParam(':insertar', $this->insertar);
		$consulta->bindParam(':modificar', $this->modificar);
		$consulta->bindParam(':eliminar', $this->eliminar);
		$consulta->bindParam(':cambiarEstado', $this->cambiarEstado);

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
	public function modificar(){
		$model = new conexionDB();
		$con = $model->conectar();
		$con->exec ( "SET CHARACTER SET utf8" );

		$response = new stdClass();
		$response->listar = $this->listar;
		$response->insertar = $this->insertar;
		$response->modificar = $this->modificar;
		$response->eliminar = $this->eliminar;
		$response->cambiarEstado = $this->cambiarEstado;
		$response->hotidrol = $this->hotidrol;
		$response->hotidrec = $this->hotidrec;
		$this->log->setLog("INPUT", $response, __METHOD__, __LINE__);

		$sql = "UPDATE ".self::NOMBRE_TABLA." SET listar = :listar, insertar = :insertar, modificar = :modificar, eliminar = :eliminar, cambiarEstado = :cambiarEstado";
		$sql .= " WHERE hotidrol = :hotidrol AND hotidrec = :hotidrec";
		$consulta = $con->prepare($sql);
		$consulta->bindParam(':listar', $this->listar);
		$consulta->bindParam(':insertar', $this->insertar);
		$consulta->bindParam(':modificar', $this->modificar);
		$consulta->bindParam(':eliminar', $this->eliminar);
		$consulta->bindParam(':cambiarEstado', $this->cambiarEstado);
		$consulta->bindParam(':hotidrol', $this->hotidrol);
		$consulta->bindParam(':hotidrec', $this->hotidrec);
		$consulta->execute();
		$con = null;
	}
	public function eliminar(){
		$model = new conexionDB();
		$con = $model->conectar();

		$response = new stdClass();
		$response->hotidrol = $this->hotidrol;
		$response->hotirec = $this->hotidrec;
		$this->log->setLog("INPUT", $response, __METHOD__, __LINE__);

		$sql = "DELETE FROM ".self::NOMBRE_TABLA." WHERE hotidrol = :hotidrol AND hotidrec = :hotidrec";
		$consulta = $con->prepare($sql);
		$consulta->bindParam(':hotidrol', $this->hotidrol);
		$consulta->bindParam(':hotidrec', $this->hotidrec);
		$consulta->execute();
		$con = null;
	}
	public function listarRecursos($idRol){
		$model = new conexionDB();
		$con = $model->conectar();
		$con->exec ( "SET CHARACTER SET utf8" );
		
    	$sql = "SELECT hotidrol,hotidrec,listar,insertar,modificar,eliminar,recdsc FROM ".self::NOMBRE_TABLA;
    	$sql .= " INNER JOIN hotrecursos on idrec=hotidrec";
    	$sql .= " WHERE hotidrol=".$idRol;
    	$consulta = $con->prepare($sql);
		$consulta->execute();

		$response = $consulta->fetchAll(PDO::FETCH_OBJ);
		$this->log->setLog("OUTPUT", $response, __METHOD__, __LINE__);
		$con = null;

		return $response;
	}
}
?>