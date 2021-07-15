<?php
require_once 'conexionDB.php';
require_once 'logs.php';
class hotcarhotel{
	const NOMBRE_TABLA = "hotcarhotel";
	public $idcarhotel;
	private $carhotdsc;
	private $carhotfecalta;

	private $log;
	private $idInsertado;

	public function __construct($idcarhotel=null){
		$this->log = new logs();
		$this->idcarhotel = $idcarhotel;
	}
	public function setCarHotDsc($carhotdsc){
		$this->carhotdsc = $carhotdsc;
	}
	public function setCarHotFecAlta($carhotfecalta){
		$this->carhotfecalta = $carhotfecalta;
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
		$response->carhotdsc = $this->carhotdsc;
		$response->carhotfecalta = $this->carhotfecalta;
		$this->log->setLog("INPUT", $response, __METHOD__, __LINE__);

		$sql = "INSERT INTO ".self::NOMBRE_TABLA." (carhotdsc, carhotfecalta)";
		$sql .= " VALUES (:carhotdsc, :carhotfecalta)";
		$consulta = $con->prepare($sql);
		$consulta->bindParam(':carhotdsc', $this->carhotdsc);
		$consulta->bindParam(':carhotfecalta', $this->carhotfecalta);
		$consulta->execute();
		$this->idInsertado = $con->lastInsertId();
		$con = null;
    }
    public function listar(){
    	$model = new conexionDB();
		$con = $model->conectar();
		$con->exec ( "SET CHARACTER SET utf8" );
		
    	$sql = "SELECT * FROM ".self::NOMBRE_TABLA;
    	$sql .= " order by carhotdsc ";
    	$consulta = $con->prepare($sql);
		$consulta->execute();

		$response = $consulta->fetchAll(PDO::FETCH_OBJ);
		$this->log->setLog("OUTPUT", $response, __METHOD__, __LINE__);
		$con = null;

		return $response;
    }
    public function listarModificarHotel($carHotel){
    	$model = new conexionDB();
		$con = $model->conectar();
		$con->exec ( "SET CHARACTER SET utf8" );

    	$sql = "SELECT * FROM ".self::NOMBRE_TABLA;
    	$sql .= " order by carhotdsc ";
    	$consulta = $con->prepare($sql);
		$consulta->execute();

		$listado= $consulta->fetchAll(PDO::FETCH_OBJ);
		$response = array();
		$this->log->setLog("INPUT", $carHotel, __METHOD__, __LINE__);
		foreach($listado as $lista){
				$obj = new stdClass();
				$obj->idcarhotel = $lista->idcarhotel;
				$obj->carhotdsc = $lista->carhotdsc;
				if(empty($carHotel)){
				$obj->carchecked = false;	
				}else{
					foreach ($carHotel as $hotelCar) {
					if($lista->idcarhotel == $hotelCar->idcarhotel){
						$obj->carchecked = true;
						break;	
					}else{
						$obj->carchecked = false;
					}
			    }
			}
			$response[] = $obj;
		}						

		$this->log->setLog("OUTPUT", $response, __METHOD__, __LINE__);
		$con = null;

		return $response;
    }
    public function modificar(){
    	$model = new conexionDB();
		$con = $model->conectar();
		$con->exec ( "SET CHARACTER SET utf8" );

		$response = new stdClass();
		$response->idcarhotel = $this->idcarhotel;
		$response->carhotdsc = $this->carhotdsc;
		$this->log->setLog("INPUT", $response, __METHOD__, __LINE__);

		$sql = "UPDATE ".self::NOMBRE_TABLA." SET carhotdsc = :carhotdsc";
		$sql .= " WHERE idcarhotel = :idcarhotel";
		$consulta = $con->prepare($sql);
		$consulta->bindParam(':idcarhotel', $this->idcarhotel);
		$consulta->bindParam(':carhotdsc', $this->carhotdsc);
		$consulta->execute();
		$con = null;
    }
    public function eliminar(){
    	$model = new conexionDB();
		$con = $model->conectar();

		$response = new stdClass();
		$response->idcarhotel = $this->idcarhotel;
		$this->log->setLog("INPUT", $response, __METHOD__, __LINE__);

		$sql = "DELETE FROM ".self::NOMBRE_TABLA." WHERE idcarhotel = :idcarhotel";
		$consulta = $con->prepare($sql);
		$consulta->bindParam(':idcarhotel', $this->idcarhotel);
		$consulta->execute();
		$con = null;
    }
}
?>