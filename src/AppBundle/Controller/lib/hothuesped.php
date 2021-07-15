<?php
require_once 'conexionDB.php';
require_once 'logs.php';

class hothuesped
{
	const NOMBRE_TABLA = "hothuesped";
	private $idhuesped;
	private $idreserva;
	private $huenombre;
	private $hueci;
	private $huetelefono;
	private $hueemail;
	private $huefecnacimiento;

	private $log;
	private $idInsertado;

	public function __construct($idhuesped=null){
		$this->log = new logs();
		$this->idhuesped = $idhuesped;
	}

	public function setIdReserva($idreserva){
		$this->idreserva = $idreserva;
	}
	public function setHueNombre($huenombre){
		$this->huenombre = $huenombre;
	}
	public function setHueCi($hueci){
		$this->hueci = $hueci;
	}
	public function setHueTelefono($huetelefono){
		$this->huetelefono = $huetelefono;
	}
	public function setHueEmail($hueemail){
		$this->hueemail = $hueemail;
	}
	public function setHueFecNacimiento($huefecnacimiento){
		$this->huefecnacimiento = $huefecnacimiento;
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
		$response->idreserva = $this->idreserva;
		$response->huenombre = $this->huenombre;
		$response->hueci = $this->hueci;
		$response->huetelefono = $this->huetelefono;
		$response->hueemail = $this->hueemail;
		$response->huefecnacimiento = $this->huefecnacimiento;
		$this->log->setLog("INPUT", $response, __METHOD__, __LINE__);

		$sql = "INSERT INTO ".self::NOMBRE_TABLA." (idreserva, huenombre, hueci, huetelefono, hueemail, huefecnacimento)";
		$sql .= " VALUES (:idreserva, :huenombre, :hueci, :huetelefono, :hueemail, :huefecnacimento)";

		$consulta = $con->prepare($sql);
		$consulta->bindParam(':idreserva',$this->idreserva);
		$consulta->bindParam(':huenombre',$this->huenombre);
		$consulta->bindParam(':hueci',$this->hueci);
		$consulta->bindParam(':huetelefono',$this->huetelefono);
		$consulta->bindParam(':hueemail',$this->hueemail);
		$consulta->bindParam(':huefecnacimento',$this->huefecnacimiento);

		$consulta->execute();
		$this->idInsertado = $con->lastInsertId();
		$con = null;
    }
    public function listarPorReserva($idres){
    	$model = new conexionDB();
		$con = $model->conectar();
		$con->exec ( "SET CHARACTER SET utf8" );

		$sql = "SELECT * FROM hothuesped WHERE hothuesped.idreserva = ".$idres;
		    	
    	$consulta = $con->prepare($sql);
		$consulta->execute();

		$listado = $consulta->fetchAll(PDO::FETCH_OBJ);

		$response = array();
		foreach($listado as $lista){
			$obj = new stdClass();
			$obj->idhuesped = $lista->idhuesped;
			$obj->idreserva = $lista->idreserva;
			$obj->huenombre = $lista->huenombre;
			$obj->hueci = $lista->hueci;
			$obj->huetelefono = $lista->huetelefono;
			$obj->hueemail = $lista->hueemail;
			$obj->huefecnacimiento = $lista->huefecnacimento;

			$response[] = $obj;
		}

		$this->log->setLog("OUTPUT", $response, __METHOD__, __LINE__);
		$con = null;

		return $response;
    }
}

?>