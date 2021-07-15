<?php
require_once 'conexionDB.php';
require_once 'logs.php';
require_once 'hothuesped.php';

class hothabreservas{
	
	const NOMBRE_TABLA = "hothabreservas";
	private $idreserva;
	private $idhabitacion;
	private $revfechaingreso;
	private $revfechasalida;
	private $revcodigoreserva;
	private $revestado;
	private $revmontoreserva;

	private $log;
	private $idInsertado;
	private $huesped;

	public function __construct($idreserva=null){
		$this->log = new logs();
		$this->idreserva = $idreserva;
	}

	public function setIdHabitacion($idhabitacion){
		$this->idhabitacion = $idhabitacion;
	}
	
	public function setRevFechaIngreso($revfechaingreso){
		$this->revfechaingreso = $revfechaingreso;
	}
	public function setRevFechaSalida($revfechasalida){
		$this->revfechasalida = $revfechasalida;
	}
	public function setRevCodigoReserva($revcodigoreserva){
		$this->revcodigoreserva = $revcodigoreserva;
	}
	public function setRevEstado($revestado){
		$this->revestado = $revestado;
	}
	public function setRevMontoReserva($revmontoreserva){
		$this->revmontoreserva = $revmontoreserva;
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
		$response->idhabitacion = $this->idhabitacion;
		$response->revfechaingreso = $this->revfechaingreso;
		$response->revfechasalida = $this->revfechasalida;
		$response->revcodigoreserva = $this->revcodigoreserva;
		$response->revestado = $this->revestado;
		$response->revmontoreserva = $this->revmontoreserva;
		$this->log->setLog("INPUT", $response, __METHOD__, __LINE__);

		$sql = "INSERT INTO ".self::NOMBRE_TABLA." (idhabitacion, revfechaingreso, revfechasalida, revcodigoreserva, revestado,revmontoreserva)";
		$sql .= " VALUES (:idhabitacion, :revfechaingreso, :revfechasalida, :revcodigoreserva, :revestado,:revmontoreserva)";

		$consulta = $con->prepare($sql);
		$consulta->bindParam(':idhabitacion', $this->idhabitacion);
		$consulta->bindParam(':revfechaingreso', $this->revfechaingreso);
		$consulta->bindParam(':revfechasalida', $this->revfechasalida);
		$consulta->bindParam(':revcodigoreserva', $this->revcodigoreserva);
		$consulta->bindParam(':revestado', $this->revestado);
		$consulta->bindParam(':revmontoreserva', $this->revmontoreserva);
		$consulta->execute();
		$this->idInsertado = $con->lastInsertId();
		$con = null;
    }
    public function listarPorHabitacion($idhab){
    	$model = new conexionDB();
		$con = $model->conectar();
		$con->exec ( "SET CHARACTER SET utf8" );

    	$sql = "SELECT * FROM ".self::NOMBRE_TABLA;
    	$sql .= " WHERE hothabreservas.idhabitacion = ".$idhab;
    	$sql .= " ORDER BY revfechaingreso DESC";
    	$consulta = $con->prepare($sql);
		$consulta->execute();

		$listado = $consulta->fetchAll(PDO::FETCH_OBJ);

		$response = array();
		foreach($listado as $lista){
			$obj = new stdClass();
			$obj->idreserva = $lista->idreserva;
			$obj->idhabitacion = $lista->idhabitacion;
			$obj->revfechaingreso = $lista->revfechaingreso;
			$obj->revfechasalida = $lista->revfechasalida;
			$obj->revcodigoreserva = $lista->revcodigoreserva;
			$obj->revmontoreserva = $lista->revmontoreserva;
			$obj->revestado = $lista->revestado;
			$obj->revcantidadhuespedes = $this->getCantidadHuespedes($lista->idreserva);

			$response[] = $obj;
		}

		$this->log->setLog("OUTPUT", $response, __METHOD__, __LINE__);
		$con = null;

		return $response;
    }
    public function listarPorHabitacionFechaActual($idhab){
    	$model = new conexionDB();
    	$con = $model->conectar();
    	$con->exec ( "SET CHARACTER SET utf8" );
    	$fechaActual = date("Y-m-d");
    	$nuevafecha = strtotime ( '-1 day' , strtotime ( $fechaActual ) ) ;
    	$nuevafecha = date ( 'Y-m-j' , $nuevafecha );
    	$sql = "SELECT * FROM ".self::NOMBRE_TABLA;
    	$sql .= " WHERE hothabreservas.idhabitacion = ".$idhab;
    	$sql .= " AND revfechaingreso >= '".$nuevafecha."'";
    	$sql .= " ORDER BY revfechaingreso ASC";
    	$consulta = $con->prepare($sql);
    	$consulta->execute();
    
    	$listado = $consulta->fetchAll(PDO::FETCH_OBJ);
    
    	$response = array();
    	foreach($listado as $lista){
    		$obj = new stdClass();
    		$obj->idreserva = $lista->idreserva;
    		$obj->idhabitacion = $lista->idhabitacion;
    		$obj->revfechaingreso = $lista->revfechaingreso;
    		$obj->revfechasalida = $lista->revfechasalida;
    		$obj->revcodigoreserva = $lista->revcodigoreserva;
    		$obj->revmontoreserva = $lista->revmontoreserva;
    		$obj->revestado = $lista->revestado;
    		$obj->revcantidadhuespedes = $this->getCantidadHuespedes($lista->idreserva);
    
    		$response[] = $obj;
    	}
    
    	$this->log->setLog("OUTPUT", $response, __METHOD__, __LINE__);
    	$con = null;
    
    	return $response;
    }
    public function getCantidadHuespedes($idreserva){
    	$model = new conexionDB();
    	$con = $model->conectar();
    	$con->exec ( "SET CHARACTER SET utf8" );
        	    
    	$sql = "SELECT COUNT(hothuesped.idhuesped) AS cantidad_huespedes FROM hothuesped";
    	$sql .= " WHERE hothuesped.idreserva = ".$idreserva;
    	$consulta = $con->prepare($sql);
    	$consulta->execute();
    	$response = $consulta->fetch(PDO::FETCH_OBJ)->cantidad_huespedes;
    	$con = null;
    	return $response;
    }
    public function cambiarEstado(){
    	$model = new conexionDB();
    	$con = $model->conectar();
    	$con->exec ( "SET CHARACTER SET utf8" );
    
    	$response = new stdClass();
    	$response->idreserva = $this->idreserva;
    	$response->revestado = $this->revestado;
    	
    	$this->log->setLog("INPUT", $response, __METHOD__, __LINE__);
    
    	$sql = "UPDATE ".self::NOMBRE_TABLA." SET revestado = :revestado";
    	$sql .= " WHERE idreserva = :idreserva";
    	$consulta = $con->prepare($sql);
    	$consulta->bindParam(':idreserva', $this->idreserva);
    	$consulta->bindParam(':revestado', $this->revestado);
    	$consulta->execute();
    	$con = null;
    }
    
    public function reporteMensualPorHabitacion($idhothotel,$mes,$anio){
    	$model = new conexionDB();
    	$con = $model->conectar();
    	$con->exec ( "SET CHARACTER SET utf8" );
    	$fechaActual = date("Y-m-d");
    	$sql = " SELECT habdsc, tipdsc, SUM( revmontoreserva  ) as revmontoreserva,hothabitaciones.idhabitacion,hothabitaciones.idhothotel";
    	$sql .= " FROM hothabitaciones ";
    	$sql .= " INNER JOIN hottiphab ON hottiphab.idtiphab = hothabitaciones.idtiphab ";
		$sql .= " INNER JOIN hothabreservas ON hothabreservas.idhabitacion = hothabitaciones.idhabitacion";
		$sql .= " AND MONTH(revfechaingreso)=".$mes; 
		$sql .= " AND MONTH(revfechasalida)=".$mes;
		$sql .= " AND YEAR(revfechaingreso) =".$anio;
		$sql .= " AND YEAR(revfechasalida) =".$anio;
		$sql .= " AND revmontoreserva IS NOT NULL  ";
		$sql .= " WHERE idhothotel =".$idhothotel;
		$sql .= " GROUP BY habdsc, tipdsc";
		$this->log->setLog("OUTPUT", $sql, __METHOD__, __LINE__);
    	$consulta = $con->prepare($sql);
    	$consulta->execute();
    
    	$listado = $consulta->fetchAll(PDO::FETCH_OBJ);
    
    	$response = array();
    	foreach($listado as $lista){
    		$obj = new stdClass();
    		$obj->habdsc = $lista->habdsc;
    		$obj->tipdsc = $lista->tipdsc;
    		$obj->revmontoreserva = $lista->revmontoreserva;
    		$obj->idhabitacion = $lista->idhabitacion;
    		$obj->idhotel = $lista->idhothotel;
    		$obj->mes = $mes;
    		$obj->anio = $anio;
    		$response[] = $obj;
    	}
    	$this->log->setLog("OUTPUT", $response, __METHOD__, __LINE__);
    	$con = null;
    
    	return $response;
    }
    public function totalReporteMensual($idhothotel,$mes,$anio){
    	$model = new conexionDB();
    	$con = $model->conectar();
    	$con->exec ( "SET CHARACTER SET utf8" );

    	$sql = " SELECT SUM(revmontoreserva) as total FROM hothabreservas ";
		$sql .= " WHERE MONTH(revfechaingreso)= ".$mes; 
		$sql .= " AND MONTH(revfechasalida)= ".$mes;
		$sql .= " AND YEAR(revfechaingreso) = ".$anio;
		$sql .= " AND YEAR(revfechasalida) = ".$anio;
		$sql .= " AND revmontoreserva is not null ";
		$sql .= " AND idhabitacion IN (SELECT idhabitacion FROM hothabitaciones WHERE idhothotel=$idhothotel)"; 
		$this->log->setLog("OUTPUT", $sql, __METHOD__, __LINE__);
		
    	$consulta = $con->prepare($sql);
    	$consulta->execute();
    	
    	$total= $consulta->fetch(PDO::FETCH_OBJ)->total;
    	$this->log->setLog("OUTPUT", $total, __METHOD__, __LINE__);
    	$con = null;

    	return $total;
    }
    //Reporte Detallado por reserva
    public function reporteMensualDetalleHabitacion($idhothotel,$idhabitacion,$mes,$anio){
    	$model = new conexionDB();
    	$con = $model->conectar();
    	$con->exec ( "SET CHARACTER SET utf8" );
    	$this->huesped = new \hothuesped ();
    	
    	$fechaActual = date("Y-m-d");
    	$sql = " SELECT habdsc, tipdsc, revfechaingreso,revfechasalida,revcodigoreserva, revmontoreserva,revmontocomision,idreserva";
    	$sql .= " FROM hothabitaciones ";
    	$sql .= " INNER JOIN hottiphab ON hottiphab.idtiphab = hothabitaciones.idtiphab ";
    	$sql .= " INNER JOIN hothabreservas ON hothabreservas.idhabitacion = hothabitaciones.idhabitacion";
    	$sql .= " AND MONTH(revfechaingreso)=".$mes;
    	$sql .= " AND MONTH(revfechasalida)=".$mes;
    	$sql .= " AND YEAR(revfechaingreso) =".$anio;
    	$sql .= " AND YEAR(revfechasalida) =".$anio;
    	$sql .= " AND revmontoreserva IS NOT NULL  ";
    	$sql .= " WHERE idhothotel =".$idhothotel;
    	$sql .= " AND hothabitaciones.idhabitacion=".$idhabitacion;
    	$this->log->setLog("OUTPUT", $sql, __METHOD__, __LINE__);
    	$consulta = $con->prepare($sql);
    	$consulta->execute();
    
    	$listado = $consulta->fetchAll(PDO::FETCH_OBJ);
    
    	$response = array();
    	foreach($listado as $lista){
    		$obj = new stdClass();
    		$obj->habdsc = $lista->habdsc;
    		$obj->tipdsc = $lista->tipdsc;
    		$obj->revfechaingreso= $lista->revfechaingreso;
    		$obj->revfechasalida= $lista->revfechasalida;
    		$obj->revcodigoreserva= $lista->revcodigoreserva;
    		$obj->revmontoreserva = $lista->revmontoreserva;
    		$obj->comision = $lista->revmontocomision;
    		$obj->revmontocomision = $lista->revmontoreserva - $lista->revmontocomision;
    		$obj->huespedes = $this->huesped->listarPorReserva($lista->idreserva);
    		$response[] = $obj;
    	}
    	$this->log->setLog("OUTPUT", $response, __METHOD__, __LINE__);
    	$con = null;
    
    	return $response;
    }
     
    public function totalReportePrecioHotel($idhothotel,$idhabitacion,$mes,$anio){
    	$model = new conexionDB();
    	$con = $model->conectar();
    	$con->exec ( "SET CHARACTER SET utf8" );
    
    	$sql = " SELECT SUM(revmontocomision) as total FROM hothabreservas ";
    	$sql .= " WHERE MONTH(revfechaingreso)= ".$mes;
    	$sql .= " AND MONTH(revfechasalida)= ".$mes;
    	$sql .= " AND YEAR(revfechaingreso) = ".$anio;
    	$sql .= " AND YEAR(revfechasalida) = ".$anio;
    	$sql .= " AND revmontoreserva is not null ";
    	$sql .= " AND idhabitacion =".$idhabitacion;
    	$this->log->setLog("OUTPUT", $sql, __METHOD__, __LINE__);
    
    	$consulta = $con->prepare($sql);
    	$consulta->execute();
    	 
    	$total= $consulta->fetch(PDO::FETCH_OBJ)->total;
    	$this->log->setLog("OUTPUT", $total, __METHOD__, __LINE__);
    	$con = null;
    
    	return $total;
    }
    public function totalReportePrecioComision($idhothotel,$idhabitacion,$mes,$anio){
    	$model = new conexionDB();
    	$con = $model->conectar();
    	$con->exec ( "SET CHARACTER SET utf8" );
    
    	$sql = " SELECT SUM(revmontoreserva-revmontocomision) as total FROM hothabreservas ";
    	$sql .= " WHERE MONTH(revfechaingreso)= ".$mes;
    	$sql .= " AND MONTH(revfechasalida)= ".$mes;
    	$sql .= " AND YEAR(revfechaingreso) = ".$anio;
    	$sql .= " AND YEAR(revfechasalida) = ".$anio;
    	$sql .= " AND revmontoreserva is not null ";
    	$sql .= " AND idhabitacion =".$idhabitacion;
    	$this->log->setLog("OUTPUT", $sql, __METHOD__, __LINE__);
    
    	$consulta = $con->prepare($sql);
    	$consulta->execute();
    
    	$total= $consulta->fetch(PDO::FETCH_OBJ)->total;
    	$this->log->setLog("OUTPUT", $total, __METHOD__, __LINE__);
    	$con = null;
    
    	return $total;
    }
    public function totalReportePrecioVenta($idhothotel,$idhabitacion,$mes,$anio){
    	$model = new conexionDB();
    	$con = $model->conectar();
    	$con->exec ( "SET CHARACTER SET utf8" );
    
    	$sql = " SELECT SUM(revmontoreserva) as total FROM hothabreservas ";
    	$sql .= " WHERE MONTH(revfechaingreso)= ".$mes;
    	$sql .= " AND MONTH(revfechasalida)= ".$mes;
    	$sql .= " AND YEAR(revfechaingreso) = ".$anio;
    	$sql .= " AND YEAR(revfechasalida) = ".$anio;
    	$sql .= " AND revmontoreserva is not null ";
    	$sql .= " AND idhabitacion =".$idhabitacion;
    	$this->log->setLog("OUTPUT", $sql, __METHOD__, __LINE__);
    
    	$consulta = $con->prepare($sql);
    	$consulta->execute();
    
    	$total= $consulta->fetch(PDO::FETCH_OBJ)->total;
    	$this->log->setLog("OUTPUT", $total, __METHOD__, __LINE__);
    	$con = null;
    
    	return $total;
    }
}

?>