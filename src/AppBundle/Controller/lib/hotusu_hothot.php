<?php
require_once 'conexionDB.php';
require_once 'logs.php';

class hotusu_hothot
{
	const NOMBRE_TABLA = "hotusu_hothot";
	public $hotidusu;
	public $hotidhotel;

	private $log;

	public function __construct(){
		$this->log = new logs();
	}
	public function setHotIdUsu($hotidusu){
		$this->hotidusu = $hotidusu;
	}
	public function setHotidhotel($hotidhotel){
		$this->hotidhotel = $hotidhotel;
	}
	public function insertar(){
		$model = new conexionDB();
		$con = $model->conectar();
		$con->exec ( "SET CHARACTER SET utf8" );

		$response = new stdClass();
		$response->hotidusu = $this->hotidusu;
		$response->hotidhotel = $this->hotidhotel;
		$this->log->setLog("INPUT", $response, __METHOD__, __LINE__);

		$sql = "INSERT INTO ".self::NOMBRE_TABLA." (hotidusu, hotidhotel)";
		$sql .= " VALUES (:hotidusu, :hotidhotel)";
		$consulta = $con->prepare($sql);
		$consulta->bindParam(':hotidusu', $this->hotidusu);
		$consulta->bindParam(':hotidhotel', $this->hotidhotel);
		$consulta->execute();
		$con = null;
	}
	public function eliminar(){
		$model = new conexionDB();
		$con = $model->conectar();

		$response = new stdClass();
		$response->hotidusu = $this->hotidusu;
		$response->hotirol = $this->hotidhotel;
		$this->log->setLog("INPUT", $response, __METHOD__, __LINE__);

		$sql = "DELETE FROM ".self::NOMBRE_TABLA." WHERE hotidusu = :hotidusu AND hotidhotel = :hotidhotel";
		$consulta = $con->prepare($sql);
		$consulta->bindParam(':hotidusu', $this->hotidusu);
		$consulta->bindParam(':hotidhotel', $this->hotidhotel);
		$consulta->execute();
		$con = null;
	}
	public function obtenerRoles($idUsu){
		$model = new conexionDB();
		$con = $model->conectar();
		$con->exec ( "SET CHARACTER SET utf8" );
		
		$sql=" SELECT hotidhotel FROM hotusu_hothot ";
		$sql.=" WHERE hotidusu=".$idUsu;

		$consulta = $con->prepare($sql);
		$consulta->execute();
		$listado= $consulta->fetchAll(PDO::FETCH_OBJ);
		$response="";
		foreach($listado as $lista){
			//$obj = new stdClass();
			//$obj->hotidhotel = $lista->hotidhotel;
			$response = $lista->hotidhotel;
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
	public function obtenerHotel($idUsu){
		$model = new conexionDB();
		$con = $model->conectar();
		$con->exec ( "SET CHARACTER SET utf8" );
		
		$sql=" SELECT hotidhotel FROM hotusu_hothot ";
		$sql.=" WHERE hotidusu=".$idUsu;

		$consulta = $con->prepare($sql);
		$consulta->execute();
		$listado= $consulta->fetchAll(PDO::FETCH_OBJ);
		$response= array();
		foreach($listado as $lista){
			//$obj = new stdClass();
			//$obj->hotidhotel = $lista->hotidhotel;
			$response[] = $lista->hotidhotel;
		}
		$this->log->setLog("OUTPUT", $response, __METHOD__, __LINE__);
		$con = null;

		return $response;
	}
	public function obtenerUsuarios($idUsu){
		$model = new conexionDB();
		$con = $model->conectar();
		$con->exec ( "SET CHARACTER SET utf8" );
	
		$sql =" SELECT hotidusu FROM hotusu_hothot as usuario ";
		$sql.=" WHERE usuario.hotidhotel IN (SELECT hotidhotel FROM hotusu_hothot as hotel ";
		$sql.=" WHERE hotel.hotidusu=$idUsu) ";
		$sql.=" AND usuario.hotidusu <> 1 ";
		$consulta = $con->prepare($sql);
		$consulta->execute();
		$listado= $consulta->fetchAll(PDO::FETCH_OBJ);
		$response= "";
		foreach($listado as $lista){
			//$obj = new stdClass();
			//$obj->hotidhotel = $lista->hotidhotel;
			$response.= ",".$lista->hotidusu;
		}
		$response = trim ( substr ( $response, 1, strlen ( $response ) ) );
		
		$this->log->setLog("OUTPUT", $response, __METHOD__, __LINE__);
		$con = null;
	
		return $response;
	}
	
}
?>
