<?php
require_once 'conexionDB.php';
require_once 'logs.php';
class hothoteles
{
	const NOMBRE_TABLA = "hothoteles";
	public $idhotel;
	private $hotnombre;
	private $hotdsc;
	private $hotcoordenadas;
	private $hotdireccion;
	private $hotlocalidad;
	private $hottelefono;
	private $hottelefono2;
	private $hottelefono3;
	private $hotcategoria;
	private $hotestado;
	private $hotpolcancel;
	private $hotfecalta;
	private $hotpublica;
	private $hottipocta;
	private $hotnrocuenta;
	private $hotabonado;
	private $hottipdoc;
	private $hotdocumento;
	private $hotcomplemento;

	private $log;
	private $idInsertado;
	public $usu_hot;

	public function __construct($idhotel=null){
		$this->log = new logs();
		$this->idhotel = $idhotel;
	}
	
	public function setHotNombre($hotnombre){
		$this->hotnombre = $hotnombre;
	}
	public function setHotDsc($hotdsc){
		$this->hotdsc = $hotdsc;
	}
	public function setHotCoordenadas($hotcoordenadas){
		$this->hotcoordenadas = $hotcoordenadas;
	}
	public function setHotDireccion($hotdireccion){
		$this->hotdireccion = $hotdireccion;
	}
	public function setHotLocalidad($hotlocalidad){
		$this->hotlocalidad = $hotlocalidad;
	}
	public function setHotTelefono($hottelefono){
		$this->hottelefono = $hottelefono;
	}
	public function setHotTelefono2($hottelefono2){
		$this->hottelefono2 = $hottelefono2;
	}
	public function setHotTelefono3($hottelefono3){
		$this->hottelefono3 = $hottelefono3;
	}	
	public function setHotCategoria($hotcategoria){
		$this->hotcategoria = $hotcategoria;
	}
	public function setHotEstado($hotestado){
		$this->hotestado = $hotestado;
	}
	public function setHotPolCancel($hotpolcancel){
		$this->hotpolcancel = $hotpolcancel;
	}
	public function setHotFecAlta($hotfecalta){
		$this->hotfecalta = $hotfecalta;
	}
	public function setHotPublica($hotpublica){
		$this->hotpublica = $hotpublica;
	}
	
	public function setHottipocta($hottipocta){
		$this->hottipocta = $hottipocta;
	}
	public function setHotnrocuenta($hotnrocuenta){
		$this->hotnrocuenta = $hotnrocuenta;
	}
	public function setHotabonado($hotabonado){
		$this->hotabonado = $hotabonado;
	}
	public function setHottipdoc($hottipdoc){
		$this->hottipdoc = $hottipdoc;
	}
	public function setHotdocumento($hotdocumento){
		$this->hotdocumento = $hotdocumento;
	}
	public function setHotcomplemento($hotcomplemento){
		$this->hotcomplemento = $hotcomplemento;
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
    	$response->hotnombre = $this->hotnombre;
    	$response->hotdsc = $this->hotdsc;
    	$response->hotcoordenadas = $this->hotcoordenadas;
    	$response->hotdireccion = $this->hotdireccion;
    	$response->hotlocalidad = $this->hotlocalidad;
    	$response->hottelefono = $this->hottelefono;
    	$response->hotcategoria = $this->hotcategoria;
    	$response->hotestado = $this->hotestado;
    	$response->hotpolcancel = $this->hotpolcancel;
    	$response->hotfecalta = $this->hotfecalta;
    	$response->hotpublica = $this->hotpublica;
		//Cambios cuenta bancaria
		$response->hottipocta = $this->hottipocta;
		$response->hotnrocuenta = $this->hotnrocuenta;
		$response->hotabonado = $this->hotabonado;
		$response->hottipdoc = $this->hottipdoc;
		$response->hotdocumento = $this->hotdocumento;
		$response->hotcomplemento = $this->hotcomplemento;
		
		$this->log->setLog("INPUT", $response, __METHOD__, __LINE__);

		$sql = "INSERT INTO ".self::NOMBRE_TABLA." (hotnombre, hotdsc, hotcoordenadas, hotdireccion, hotlocalidad, hottelefono, hottelefono2, hottelefono3, hotcategoria, hotestado, hotpolcancel, hotfecalta, hotpublica,hottipocta,hotnrocuenta,hotabonado,hottipdoc,hotdocumento,hotcomplemento)";
		$sql .= " VALUES (:hotnombre, :hotdsc, :hotcoordenadas, :hotdireccion, :hotlocalidad, :hottelefono, :hottelefono2, :hottelefono3, :hotcategoria, :hotestado, :hotpolcancel, :hotfecalta, :hotpublica,";
		$sql .= " :hottipocta, :hotnrocuenta, :hotabonado, :hottipdoc, :hotdocumento, :hotcomplemento)";
		$consulta = $con->prepare($sql);
		$consulta->bindParam(':hotnombre', $this->hotnombre);
		$consulta->bindParam(':hotdsc', $this->hotdsc);
		$consulta->bindParam(':hotcoordenadas', $this->hotcoordenadas);
		$consulta->bindParam(':hotdireccion', $this->hotdireccion);
		$consulta->bindParam(':hotlocalidad', $this->hotlocalidad);
		$consulta->bindParam(':hottelefono', $this->hottelefono);
		$consulta->bindParam(':hottelefono2', $this->hottelefono2);
		$consulta->bindParam(':hottelefono3', $this->hottelefono3);		
		$consulta->bindParam(':hotcategoria', $this->hotcategoria);
		$consulta->bindParam(':hotestado', $this->hotestado);
		$consulta->bindParam(':hotpolcancel', $this->hotpolcancel);
		$consulta->bindParam(':hotfecalta', $this->hotfecalta);
		$consulta->bindParam(':hotpublica', $this->hotpublica);
		$consulta->bindParam(':hottipocta', $this->hottipocta);
		$consulta->bindParam(':hotnrocuenta', $this->hotnrocuenta);
		$consulta->bindParam(':hotabonado', $this->hotabonado);
		$consulta->bindParam(':hottipdoc', $this->hottipdoc);
		$consulta->bindParam(':hotdocumento', $this->hotdocumento);
		$consulta->bindParam(':hotcomplemento', $this->hotcomplemento);
		$consulta->execute();
		$this->idInsertado = $con->lastInsertId();
		$con = null;

    }
    public function verificarExistenciaHotel($hotnombre){
    	$model = new conexionDB();
    	$con = $model->conectar();
    	$con->exec ( "SET CHARACTER SET utf8" );
    	$sql  = " SELECT  UPPER(hotnombre) as hotnombre FROM ".self::NOMBRE_TABLA;
    	$sql .= " WHERE hotestado = 1 ";
    	$consulta = $con->prepare($sql);
    	$consulta->execute();
    	$listado= $consulta->fetchAll(PDO::FETCH_OBJ);
    	$response=false;
    	foreach($listado as $lista){
    		if($lista->hotnombre == $hotnombre){
    			$response = true;
    			return $response;
    		}
    	}
    	return $response;
    }
    public function listar(){
    	$model = new conexionDB();
		$con = $model->conectar();
		$con->exec ( "SET CHARACTER SET utf8" );

    	$sql = "SELECT * FROM ".self::NOMBRE_TABLA;
    	$consulta = $con->prepare($sql);
		$consulta->execute();

		$listado= $consulta->fetchAll(PDO::FETCH_OBJ);
		$response = array();
		foreach($listado as $lista){
			$obj = new stdClass();
			$obj->idhotel = $lista->idHotel;
			$obj->hotnombre = $lista->hotnombre;
			$obj->hotdsc = $lista->hotdsc;
			$obj->hotcoordenadas = $lista->hotcoordenadas;
			$obj->hotdireccion = $lista->hotdireccion;
			$obj->hotlocalidad = $lista->hotlocalidad;
			$obj->hottelefono = $lista->hottelefono;
			$obj->hottelefono2 = $lista->hottelefono2;
			$obj->hottelefono3 = $lista->hottelefono3;
			$obj->hotcategoria = $lista->hotcategoria;
			$obj->hotestado = $lista->hotestado;
			$obj->hotfecalta = $lista->hotfecalta;
			$obj->hotpublica = $lista->hotpublica;
			
			$obj->hottipocta = $lista->hottipocta;
			$obj->hotnrocuenta = $lista->hotnrocuenta;
			$obj->hotabonado = $lista->hotabonado;
			$obj->hottipdoc = $lista->hottipdoc;
			$obj->hotdocumento = $lista->hotdocumento;
			$obj->hotcomplemento = $lista->hotcomplemento;
			
			$obj->hotpolcancel = $this->obtenerPoliticaCancelacion($lista->hotpolcancel);
			$obj->hotcaracteristicas = $this->obtenerCaracteristicas($lista->idHotel);
			$response[] = $obj;
		}

		$this->log->setLog("OUTPUT", $response, __METHOD__, __LINE__);
		$con = null;

		return $response;
    }
    public function listarHotelPorUsuario($idUsu){
    	$model = new conexionDB();
		$con = $model->conectar();
		$con->exec ( "SET CHARACTER SET utf8" );

    	$sql = " SELECT * FROM ".self::NOMBRE_TABLA;
    	$sql .=" INNER JOIN `hotusu_hothot` ON `hothoteles`.`idHotel` = `hotusu_hothot`.`hotidhotel` ";
    	$sql .=" WHERE `hotusu_hothot`.`hotidusu`= ".$idUsu;
    	$sql .=" AND `hothoteles`.`hotestado` != 3 ";
    	$this->log->setLog("INPUT", $sql, __METHOD__, __LINE__);
    	$consulta = $con->prepare($sql);
		$consulta->execute();

		$listado= $consulta->fetchAll(PDO::FETCH_OBJ);
		$response = array();
		foreach($listado as $lista){
			$obj = new stdClass();
			$obj->idhotel = $lista->idHotel;
			$obj->hotnombre = $lista->hotnombre;
			$obj->hotdsc = $lista->hotdsc;
			$obj->hotcoordenadas = $lista->hotcoordenadas;
			$obj->hotdireccion = $lista->hotdireccion;
			$obj->hotlocalidad = $lista->hotlocalidad;
			$obj->hottelefono = $lista->hottelefono;
			$obj->hottelefono2 = $lista->hottelefono2;
			$obj->hottelefono3 = $lista->hottelefono3;
			$obj->hotcategoria = $lista->hotcategoria;
			$obj->hotestado = $lista->hotestado;
			$obj->hotfecalta = $lista->hotfecalta;
			$obj->hotpolcancel = $this->obtenerPoliticaCancelacion($lista->hotpolcancel);
			$obj->hotcaracteristicas = $this->obtenerCaracteristicas($lista->idHotel);
			$obj->hotpublica = $lista->hotpublica;
			
			$obj->hottipocta = $lista->hottipocta;
			$obj->hotnrocuenta = $lista->hotnrocuenta;
			$obj->hotabonado = $lista->hotabonado;
			$obj->hottipdoc = $lista->hottipdoc;
			$obj->hotdocumento = $lista->hotdocumento;
			$obj->hotcomplemento = $lista->hotcomplemento;
			$response[] = $obj;
		}

		$this->log->setLog("OUTPUT", $response, __METHOD__, __LINE__);
		$con = null;

		return $response;
    }
    public function obtenerCaracteristicas($id_hotel){
    	$model = new conexionDB();
		$con = $model->conectar();
		$con->exec ( "SET CHARACTER SET utf8" );

		$sql=" SELECT hotcarhotel.carhotdsc FROM hotcarhotel ";
		$sql.=" INNER JOIN hothotel_hotcarhot WHERE hothotel_hotcarhot.hotcarhotel = hotcarhotel.idcarhotel ";
		$sql.=" AND hothotel_hotcarhot.hotidHotel=".$id_hotel;
		$sql .= " order by carhotdsc ";
		
		$consulta = $con->prepare($sql);
		$consulta->execute();
		$response = $consulta->fetchAll(PDO::FETCH_COLUMN, 0);

		$con = null;

		return $response;
    }
    public function obtenerCaracteristicasModificar($id_hotel){
    	$model = new conexionDB();
		$con = $model->conectar();
		$con->exec ( "SET CHARACTER SET utf8" );

		$sql=" SELECT hotcarhotel.idcarhotel,hotcarhotel.carhotdsc FROM hotcarhotel ";
		$sql.=" INNER JOIN hothotel_hotcarhot WHERE hothotel_hotcarhot.hotcarhotel = hotcarhotel.idcarhotel ";
		$sql.=" AND hothotel_hotcarhot.hotidHotel=".$id_hotel;

		$consulta = $con->prepare($sql);
		$consulta->execute();
		$listado= $consulta->fetchAll(PDO::FETCH_OBJ);
		$response = array();
		foreach($listado as $lista){
			$obj = new stdClass();
			$obj->idcarhotel = $lista->idcarhotel;
			$obj->carhotdsc = $lista->carhotdsc;
			$response[] = $obj;
		}
		$this->log->setLog("OUTPUT", $response, __METHOD__, __LINE__);
		$con = null;

		return $response;
    }
    
    public function obtenerPoliticaCancelacion($hotpolcancel){
    	
		$response;
	switch ($hotpolcancel) {
    case 1:
        $response ="48 horas antes con penalidad de una noche de estadia";
        break;
    case 2:
        $response ="72 horas antes con penalidad de una noche de estadia";
        break;
    case 3:
        $response = "48 horas antes con penalidad de reserva completa";
        break;
    case 4:
        $response = "72 horas antes con penalidad de reserva completa";
        break;
     case 5:
        $response = "Perdida de 50% del total de la reserva";
        break;
     case 6:
       	$response = "Perdida completa de la reserva";
       	break;
	 case 7:
       	$response = "24 horas antes con penalidad de reserva completa";
       	break;	
	}
		return $response;
}
	public function obtenerDatosHotel($idHotelElegido){
    	$model = new conexionDB();
		$con = $model->conectar();
		$con->exec ( "SET CHARACTER SET utf8" );

    	$sql = "SELECT * FROM ".self::NOMBRE_TABLA;
    	$sql.= " where idHotel = ".$idHotelElegido;

    	$this->log->setLog("SQL ", $sql, __METHOD__, __LINE__);

    	$consulta = $con->prepare($sql);
		$consulta->execute();

		$listado= $consulta->fetchAll(PDO::FETCH_OBJ);
		$response = array();
		foreach($listado as $lista){
			$obj = new stdClass();
			$obj->idhotel = $lista->idHotel;
			$obj->hotnombre = $lista->hotnombre;
			$obj->hotdsc = $lista->hotdsc;
			$obj->hotcoordenadas = $lista->hotcoordenadas;
			$obj->hotdireccion = $lista->hotdireccion;
			$obj->hotlocalidad = $lista->hotlocalidad;
			$obj->hottelefono = $lista->hottelefono;
			$obj->hottelefono2 = $lista->hottelefono2;
			$obj->hottelefono3 = $lista->hottelefono3;
			$obj->hotcategoria = $lista->hotcategoria;
			$obj->hotestado = $lista->hotestado;
			$obj->hotpolcancelid = $lista->hotpolcancel;
			$obj->hotpolcancel = $this->obtenerPoliticaCancelacion($lista->hotpolcancel);
			$obj->hotcaracteristicas = $this->obtenerCaracteristicas($lista->idHotel);
			$obj->hotpublica = $lista->hotpublica;
			
			$obj->hottipocta = $lista->hottipocta;
			$obj->hotnrocuenta = $lista->hotnrocuenta;
			$obj->hotabonado = $lista->hotabonado;
			$obj->hottipdoc = $lista->hottipdoc;
			$obj->hotdocumento = $lista->hotdocumento;
			$obj->hotcomplemento = $lista->hotcomplemento;
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
		$response->idhotel = $this->idhotel;
    	$response->hotnombre = $this->hotnombre;
    	$response->hotdsc = $this->hotdsc;
    	$response->hotcoordenadas = $this->hotcoordenadas;
    	$response->hotdireccion = $this->hotdireccion;
    	$response->hotlocalidad = $this->hotlocalidad;
    	$response->hottelefono = $this->hottelefono;
    	$response->hottelefono2 = $this->hottelefono2;
    	$response->hottelefono3 = $this->hottelefono3;
    	$response->hotcategoria = $this->hotcategoria;
    	$response->hotestado = $this->hotestado;
    	$response->hotpolcancel = $this->hotpolcancel;
    	//$response->hotpublica = $this->hotpublica;
		$response->hottipocta = $this->hottipocta;
		$response->hotnrocuenta = $this->hotnrocuenta;
		$response->hotabonado = $this->hotabonado;
		$response->hottipdoc = $this->hottipdoc;
		$response->hotdocumento = $this->hotdocumento;
		$response->hotcomplemento = $this->hotcomplemento;
		$this->log->setLog("INPUT", $response, __METHOD__, __LINE__);

		$sql = "UPDATE ".self::NOMBRE_TABLA." SET hotnombre = :hotnombre, hotdsc = :hotdsc, hotcoordenadas = :hotcoordenadas, hotdireccion = :hotdireccion, hotlocalidad = :hotlocalidad, hottelefono = :hottelefono, hottelefono2 = :hottelefono2, hottelefono3 = :hottelefono3, hotcategoria = :hotcategoria, hotestado = :hotestado, hotpolcancel = :hotpolcancel, hottipocta = :hottipocta, hotnrocuenta = :hotnrocuenta, hotabonado = :hotabonado, hottipdoc = :hottipdoc, hotdocumento = :hotdocumento, hotcomplemento = :hotcomplemento"; //hotpublica = :hotpublica";
		$sql .= " WHERE idhotel = :idhotel";

		$consulta = $con->prepare($sql);
		$consulta->bindParam(':idhotel', $this->idhotel);
		$consulta->bindParam(':hotnombre', $this->hotnombre);
		$consulta->bindParam(':hotdsc', $this->hotdsc);
		$consulta->bindParam(':hotcoordenadas', $this->hotcoordenadas);
		$consulta->bindParam(':hotdireccion', $this->hotdireccion);
		$consulta->bindParam(':hotlocalidad', $this->hotlocalidad);
		$consulta->bindParam(':hottelefono', $this->hottelefono);
		$consulta->bindParam(':hottelefono2', $this->hottelefono2);
		$consulta->bindParam(':hottelefono3', $this->hottelefono3);
		$consulta->bindParam(':hotcategoria', $this->hotcategoria);
		$consulta->bindParam(':hotestado', $this->hotestado);
		$consulta->bindParam(':hotpolcancel', $this->hotpolcancel);
		
		$consulta->bindParam(':hottipocta', $this->hottipocta);
		$consulta->bindParam(':hotnrocuenta', $this->hotnrocuenta);
		$consulta->bindParam(':hotabonado', $this->hotabonado);
		$consulta->bindParam(':hottipdoc', $this->hottipdoc);
		$consulta->bindParam(':hotdocumento', $this->hotdocumento);
		$consulta->bindParam(':hotcomplemento', $this->hotcomplemento);
		
		//$consulta->bindParam(':hotpublica', $this->hotpublica);

		$consulta->execute();
		$con = null;
    }
    
    public function cambiarEstadoHotel(){
    	$model = new conexionDB();
    	$con = $model->conectar();
    	$con->exec ( "SET CHARACTER SET utf8" );
    
    	$response = new stdClass();
    	$response->idhotel = $this->idhotel;
    	$response->hotestado = $this->hotestado;
    	$this->log->setLog("INPUT", $response, __METHOD__, __LINE__);
    
    	$sql = "UPDATE ".self::NOMBRE_TABLA." SET hotestado = :hotestado ";
    	$sql .= " WHERE idhotel = :idhotel";
    
    	$consulta = $con->prepare($sql);
    	$consulta->bindParam(':idhotel', $this->idhotel);
    	$consulta->bindParam(':hotestado', $this->hotestado);
    	$consulta->execute();
    	$con = null;
    }
    public function cambiarPublicacionHotel(){
    	$model = new conexionDB();
    	$con = $model->conectar();
    	$con->exec ( "SET CHARACTER SET utf8" );
    
    	$response = new stdClass();
    	$response->idhotel = $this->idhotel;
    	$response->hotpublica = $this->hotpublica;
    	$this->log->setLog("INPUT", $response, __METHOD__, __LINE__);
    
    	$sql = "UPDATE ".self::NOMBRE_TABLA." SET hotpublica = :hotpublica ";
    	$sql .= " WHERE idhotel = :idhotel";
    
    	$consulta = $con->prepare($sql);
    	$consulta->bindParam(':idhotel', $this->idhotel);
    	$consulta->bindParam(':hotpublica', $this->hotpublica);
    	$consulta->execute();
    	$con = null;
    }
    public function eliminar(){
    	$model = new conexionDB();
		$con = $model->conectar();

		$response = new stdClass();
		$response->idhotel = $this->idhotel;
		$this->log->setLog("INPUT", $response, __METHOD__, __LINE__);

		$sql = "DELETE FROM ".self::NOMBRE_TABLA." WHERE idhotel = :idhotel";
		$consulta = $con->prepare($sql);
		$consulta->bindParam(':idhotel', $this->idhotel);
		$consulta->execute();
		$con = null;
    }
}

?>