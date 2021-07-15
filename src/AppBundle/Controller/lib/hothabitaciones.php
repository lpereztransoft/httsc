<?php
require_once 'conexionDB.php';
require_once 'logs.php';
class hothabitaciones {
	const NOMBRE_TABLA = "hothabitaciones";
	private $idhabitacion;
	private $idhothotel;
	private $habmaxmayor;
	private $habmaxmenor;
	private $habtarifamayor;
	private $habtarifamenor;
	private $habdsc;
	private $habfecdesde;
	private $habfechasta;
	private $habestado;
	private $habfecalta;
	private $idtiphab;
	private $habestadodisp;
	private $log;
	private $idInsertado;
	public function __construct($idhabitacion = null) {
		$this->log = new logs ();
		$this->idhabitacion = $idhabitacion;
	}
	public function setIdTipHab($idtiphab) {
		$this->idtiphab = $idtiphab;
	}
	public function setIdHotHotel($idhothotel) {
		$this->idhothotel = $idhothotel;
	}
	public function setHabMaxMayor($habmaxmayor) {
		$this->habmaxmayor = $habmaxmayor;
	}
	public function setHabMaxMenor($habmaxmenor) {
		$this->habmaxmenor = $habmaxmenor;
	}
	public function setHabTarifaMayor($habtarifamayor) {
		$this->habtarifamayor = $habtarifamayor;
	}
	public function setHabTarifaMenor($habtarifamenor) {
		$this->habtarifamenor = $habtarifamenor;
	}
	public function setHabDsc($habdsc) {
		$this->habdsc = $habdsc;
	}
	public function setHabFecDesde($habfecdesde) {
		$this->habfecdesde = $habfecdesde;
	}
	public function setHabFecHasta($habfechasta) {
		$this->habfechasta = $habfechasta;
	}
	public function setHabEstado($habestado) {
		$this->habestado = $habestado;
	}
	public function setHabEstadoDisp($habestadodisp) {
		$this->habestadodisp = $habestadodisp;
	}
	public function setHabFecAlta($habfecalta) {
		$this->habfecalta = $habfecalta;
	}
	public function getNombreTabla() {
		return self::NOMBRE_TABLA;
	}
	public function getIdUltimaInsercion() {
		return $this->idInsertado;
	}
	public function insertar() {
		$model = new conexionDB ();
		$con = $model->conectar ();
		$con->exec ( "SET CHARACTER SET utf8" );
		
		$response = new stdClass ();
		$response->idhothotel = $this->idhothotel;
		$response->habmaxmayor = $this->habmaxmayor;
		$response->habmaxmenor = $this->habmaxmenor;
		$response->habtarifamayor = $this->habtarifamayor;
		$response->habtarifamenor = $this->habtarifamenor;
		$response->habdsc = $this->habdsc;
		$response->habfecdesde = $this->habfecdesde;
		$response->habfechasta = $this->habfechasta;
		$response->habestado = $this->habestado;
		$response->habestadodisp = $this->habestadodisp;
		$response->habfecalta = $this->habfecalta;
		$this->log->setLog ( "INPUT", $response, __METHOD__, __LINE__ );
		
		$sql = "INSERT INTO " . self::NOMBRE_TABLA . " (idhothotel, habmaxmayor, habmaxmenor, habtarifamayor, habtarifamenor, habdsc, habfecdesde, habfechasta, habestado, habfecalta, idtiphab,habestadodisp)";
		$sql .= " VALUES (:idhothotel, :habmaxmayor, :habmaxmenor, :habtarifamayor, :habtarifamenor, :habdsc, :habfecdesde, :habfechasta, :habestado, :habfecalta, :idtiphab,:habestadodisp)";
		$consulta = $con->prepare ( $sql );
		$consulta->bindParam ( ':idhothotel', $this->idhothotel );
		$consulta->bindParam ( ':habmaxmayor', $this->habmaxmayor );
		$consulta->bindParam ( ':habmaxmenor', $this->habmaxmenor );
		$consulta->bindParam ( ':habtarifamayor', $this->habtarifamayor );
		$consulta->bindParam ( ':habtarifamenor', $this->habtarifamenor );
		$consulta->bindParam ( ':habdsc', $this->habdsc );
		$consulta->bindParam ( ':habfecdesde', $this->habfecdesde );
		$consulta->bindParam ( ':habfechasta', $this->habfechasta );
		$consulta->bindParam ( ':habestado', $this->habestado );
		$consulta->bindParam ( ':habfecalta', $this->habfecalta );
		$consulta->bindParam ( ':idtiphab', $this->idtiphab );
		$consulta->bindParam ( ':habestadodisp', $this->habestadodisp );
		$consulta->execute ();
		$this->idInsertado = $con->lastInsertId ();
		$con = null;
	}
	public function listar() {
		$model = new conexionDB ();
		$con = $model->conectar ();
		$con->exec ( "SET CHARACTER SET utf8" );
		
		$sql = "SELECT * FROM " . self::NOMBRE_TABLA;
		$consulta = $con->prepare ( $sql );
		$consulta->execute ();
		
		$listado = $consulta->fetchAll ( PDO::FETCH_OBJ );
		
		$response = array ();
		
		foreach ( $listado as $lista ) {
			$obj = new stdClass ();
			$obj->idhabitacion = $lista->idhabitacion;
			$obj->idhothotel = $lista->idhothotel;
			$obj->habmaxmayor = $lista->habmaxmayor;
			$obj->habmaxmenor = $lista->habmaxmenor;
			$obj->habtarifamayor = $lista->habtarifamayor;
			$obj->habtarifamenor = $lista->habtarifamenor;
			$obj->habdsc = $lista->habdsc;
			$obj->habfecdesde = $lista->habfecdesde;
			$obj->habfechasta = $lista->habfechasta;
			$obj->habestado = $lista->habestado;
			$obj->habfecalta = $lista->habfecalta;
			$obj->idtiphab = $lista->idtiphab;
			$obj->habtipo = $this->getTipo ( $lista->idtiphab );
			$obj->habestadodisp = $lista->habestadodisp;
			$obj->habcaracteristicas = $this->obtenerCaracteristicas ( $lista->idhabitacion );
			$obj->habservicios = $this->obtenerServicios ( $lista->idhabitacion );
			
			$response [] = $obj;
		}
		
		$this->log->setLog ( "OUTPUT", $response, __METHOD__, __LINE__ );
		$con = null;
		
		return $response;
	}
	public function listarPorHotel($idHotHotel) {
		$model = new conexionDB ();
		$con = $model->conectar ();
		$con->exec ( "SET CHARACTER SET utf8" );
		
		$sql = "SELECT * FROM " . self::NOMBRE_TABLA;
		$sql .= " WHERE idhothotel=" . $idHotHotel;
		$sql .= " AND  habestado != 3 ";
		$consulta = $con->prepare ( $sql );
		$consulta->execute ();
		
		$listado = $consulta->fetchAll ( PDO::FETCH_OBJ );
		
		$response = array ();
		
		foreach ( $listado as $lista ) {
			$obj = new stdClass ();
			$obj->idhabitacion = $lista->idhabitacion;
			$obj->idhothotel = $lista->idhothotel;
			$obj->habmaxmayor = $lista->habmaxmayor;
			$obj->habmaxmenor = $lista->habmaxmenor;
			$obj->habtarifamayor = $lista->habtarifamayor;
			$obj->habtarifamenor = $lista->habtarifamenor;
			$obj->habdsc = $lista->habdsc;
			$obj->habfecdesde = $lista->habfecdesde;
			$obj->habfechasta = $lista->habfechasta;
			$obj->habestado = $lista->habestado;
			$obj->habfecalta = $lista->habfecalta;
			$obj->idtiphab = $lista->idtiphab;
			$obj->habtipo = $this->getTipo ( $lista->idtiphab );
			$obj->habcaracteristicas = $this->obtenerCaracteristicas ( $lista->idhabitacion );
			$obj->habservicios = $this->obtenerServicios ( $lista->idhabitacion );
			$obj->habcantidadreservas = $this->getCantidadReservas ( $lista->idhabitacion );
			
			$response [] = $obj;
		}
		
		$this->log->setLog ( "OUTPUT", $response, __METHOD__, __LINE__ );
		$con = null;
		
		return $response;
	}
	public function listarPorHotelDisponibilidad($idHotHotel) {
		$model = new conexionDB ();
		$con = $model->conectar ();
		$con->exec ( "SET CHARACTER SET utf8" );
		
		$sql = "SELECT hothabitaciones.idhabitacion, hothabitaciones.idtiphab, hothabitaciones.habdsc, hothabitaciones.habestadodisp FROM " . self::NOMBRE_TABLA;
		$sql .= " WHERE hothabitaciones.idhothotel = " . $idHotHotel;
		$sql .= " AND  habestado != 3 ";
		$consulta = $con->prepare ( $sql );
		$consulta->execute ();
		
		$listado = $consulta->fetchAll ( PDO::FETCH_OBJ );
		
		$response = array ();
		
		foreach ( $listado as $lista ) {
			$obj = new stdClass ();
			$obj->idhabitacion = $lista->idhabitacion;
			$obj->habtipo = $this->getTipo ( $lista->idtiphab );
			$obj->habdsc = $lista->habdsc;
			$obj->habestadodisp = $lista->habestadodisp;
			$obj->cantidad_reserva = $this->getCantidadReservasDisponibilidad ( $lista->idhabitacion );
			$obj->reservaid = $this->getReservaPorHabitacion ( $lista->idhabitacion ) ['idreserva'];
			$obj->reservaingreso = $this->getReservaPorHabitacion ( $lista->idhabitacion ) ['revfechaingreso'];
			$obj->reservasalida = $this->getReservaPorHabitacion ( $lista->idhabitacion ) ['revfechasalida'];
			$obj->reservacodigo = $this->getReservaPorHabitacion ( $lista->idhabitacion ) ['revcodigoreserva'];
			$obj->huespedes = $this->getHuespedesPorReserva ( $this->getReservaPorHabitacion ( $lista->idhabitacion ) ['idreserva'] );
			
			$response [] = $obj;
		}
		$this->log->setLog ( "OUTPUT", $response, __METHOD__, __LINE__ );
		$con = null;
		
		return $response;
	}
	public function modificar() {
		$model = new conexionDB ();
		$con = $model->conectar ();
		$con->exec ( "SET CHARACTER SET utf8" );
		
		$response = new stdClass ();
		$response->idhabitacion = $this->idhabitacion;
		$response->habmaxmayor = $this->habmaxmayor;
		$response->habmaxmenor = $this->habmaxmenor;
		$response->habtarifamayor = $this->habtarifamayor;
		$response->habtarifamenor = $this->habtarifamenor;
		$response->habdsc = $this->habdsc;
		$response->habfecdesde = $this->habfecdesde;
		$response->habfechasta = $this->habfechasta;
		$response->habestado = $this->habestado;
		$response->idtiphab = $this->idtiphab;
		$this->log->setLog ( "INPUT", $response, __METHOD__, __LINE__ );
		
		$sql = "UPDATE " . self::NOMBRE_TABLA . " SET habmaxmayor = :habmaxmayor, habmaxmenor = :habmaxmenor, habtarifamayor = :habtarifamayor, habtarifamenor = :habtarifamenor, habdsc = :habdsc, habfecdesde = :habfecdesde, habfechasta = :habfechasta, habestado = :habestado, idtiphab = :idtiphab";
		$sql .= " WHERE idhabitacion = :idhabitacion";
		$sql .= " AND idhothotel = :idhothotel ";
		$consulta = $con->prepare ( $sql );
		$consulta->bindParam ( ':idhothotel', $this->idhothotel );
		$consulta->bindParam ( ':idhabitacion', $this->idhabitacion );
		$consulta->bindParam ( ':habmaxmayor', $this->habmaxmayor );
		$consulta->bindParam ( ':habmaxmenor', $this->habmaxmenor );
		$consulta->bindParam ( ':habtarifamayor', $this->habtarifamayor );
		$consulta->bindParam ( ':habtarifamenor', $this->habtarifamenor );
		$consulta->bindParam ( ':habdsc', $this->habdsc );
		$consulta->bindParam ( ':habfecdesde', $this->habfecdesde );
		$consulta->bindParam ( ':habfechasta', $this->habfechasta );
		$consulta->bindParam ( ':habestado', $this->habestado );
		$consulta->bindParam ( ':idtiphab', $this->idtiphab );
		$consulta->execute ();
		$con = null;
	}
	public function eliminar() {
		$model = new conexionDB ();
		$con = $model->conectar ();
		
		$response = new stdClass ();
		$response->idhabitacion = $this->idhabitacion;
		$this->log->setLog ( "INPUT", $response, __METHOD__, __LINE__ );
		
		// $sql = "DELETE FROM ".self::NOMBRE_TABLA." WHERE idhabitacion = :idhabitacion";
		// $sql .= " AND idhothotel = :idhothotel ";
		
		$sql = "UPDATE " . self::NOMBRE_TABLA . " SET habestado = :habestado";
		$sql .= " WHERE idhabitacion = :idhabitacion";
		$habestado = 3; // Estado Eliminado
		$consulta = $con->prepare ( $sql );
		$consulta->bindParam ( ':idhabitacion', $this->idhabitacion );
		$consulta->bindParam ( ':habestado', $habestado );		
		$consulta->execute ();
		$con = null;
	}
	public function obtenerCaracteristicas($id_habitacion) {
		$model = new conexionDB ();
		$con = $model->conectar ();
		$con->exec ( "SET CHARACTER SET utf8" );
		
		$sql = "SELECT hotcarhab.carhabdsc FROM hothabitaciones LEFT OUTER JOIN (hotcarhab LEFT OUTER JOIN hothab_hotcarhab ON hotcarhab.idcarhab = hothab_hotcarhab.hotidcarhab) ON hothabitaciones.idhabitacion = hothab_hotcarhab.hotidhabitacion";
		$sql .= " WHERE hothabitaciones.idhabitacion = " . $id_habitacion;
		
		$consulta = $con->prepare ( $sql );
		$consulta->execute ();
		$response = $consulta->fetchAll ( PDO::FETCH_COLUMN, 0 );
		
		$con = null;
		
		return $response;
	}
	public function obtenerServicios($id_habitacion){
		$model = new conexionDB();
		$con = $model->conectar();
		$con->exec ( "SET CHARACTER SET utf8" );
	
		$sql = "SELECT hotserhab.serhabdsc FROM hothabitaciones LEFT OUTER JOIN (hotserhab LEFT OUTER JOIN hothab_hotserhab ON hotserhab.idserhab = hothab_hotserhab.hotidserhab) ON hothabitaciones.idhabitacion = hothab_hotserhab.hotidhabitacion";
		$sql .= " WHERE hothabitaciones.idhabitacion = ".$id_habitacion;
	
		$consulta = $con->prepare ( $sql );
		$consulta->execute ();
		$response = $consulta->fetchAll ( PDO::FETCH_COLUMN, 0 );
	
		$con = null;
		return $response;
	}
	public function getTipo($idtipo) {
		$model = new conexionDB ();
		$con = $model->conectar ();
		$con->exec ( "SET CHARACTER SET utf8" );
		
		$sql = "SELECT hottiphab.tipdsc FROM hottiphab WHERE hottiphab.idtiphab = " . $idtipo;
		$consulta = $con->prepare ( $sql );
		$consulta->execute ();
		$response = $consulta->fetch ( PDO::FETCH_OBJ )->tipdsc;
		$con = null;
		return $response;
	}
	public function getCantidadReservas($idhabitacion) {
		$model = new conexionDB ();
		$con = $model->conectar ();
		$con->exec ( "SET CHARACTER SET utf8" );
		
		$sql = "SELECT COUNT(hothabreservas.idreserva) AS cantidad_reservas FROM hothabreservas";
		$sql .= " WHERE hothabreservas.idhabitacion = " . $idhabitacion;
		$consulta = $con->prepare ( $sql );
		$consulta->execute ();
		$response = $consulta->fetch ( PDO::FETCH_OBJ )->cantidad_reservas;
		$con = null;
		return $response;
	}
	public function getCantidadReservasDisponibilidad($idhabitacion) {
		$model = new conexionDB ();
		$con = $model->conectar ();
		$con->exec ( "SET CHARACTER SET utf8" );
		$hoy = date ( 'Y-m-d' );
		$estado = 4;
		$sql = "SELECT COUNT(hothabreservas.idreserva) AS cantidad_reservas FROM hothabreservas";
		$sql .= " WHERE hothabreservas.idhabitacion = " . $idhabitacion . " AND ( ('" . $hoy . "' <= hothabreservas.revfechaingreso) OR ('" . $hoy . "' BETWEEN hothabreservas.revfechaingreso AND hothabreservas.revfechasalida) )";
		$consulta = $con->prepare ( $sql );
		$consulta->execute ();
		$response = $consulta->fetch ( PDO::FETCH_OBJ )->cantidad_reservas;
		$con = null;
		return $response;
	}
	public function getReservaPorHabitacion($idhabitacion) {
		$model = new conexionDB ();
		$con = $model->conectar ();
		$con->exec ( "SET CHARACTER SET utf8" );
		
		$hoy = date ( 'Y-m-d' );
		// $hoy = "2017-05-06";
		$estado = 4; // checkin = ocupado
		             // $estado = 1; //para que funcione
		
		$sql = "SELECT hothabreservas.idreserva, hothabreservas.revfechaingreso, hothabreservas.revfechasalida, hothabreservas.revcodigoreserva FROM hothabreservas";
		$sql .= " WHERE hothabreservas.idhabitacion = " . $idhabitacion . " AND ('" . $hoy . "' BETWEEN hothabreservas.revfechaingreso AND hothabreservas.revfechasalida) AND hothabreservas.revestado = " . $estado;
		
		$consulta = $con->prepare ( $sql );
		$consulta->execute ();
		$response = $consulta->fetch ( PDO::FETCH_ASSOC );
		$con = null;
		return $response;
	}
	public function getHuespedesPorReserva($idreserva) {
		$model = new conexionDB ();
		$con = $model->conectar ();
		$con->exec ( "SET CHARACTER SET utf8" );
		
		$sql = "SELECT * FROM hothuesped WHERE hothuesped.idreserva = " . $idreserva;
		$consulta = $con->prepare ( $sql );
		$consulta->execute ();
		$response = $consulta->fetchAll ( PDO::FETCH_OBJ );
		$con = null;
		return $response;
	}
	public function cambiarEstadoDiponibilidad($idhabitacion, $estadoDisponibilidad) {
		$model = new conexionDB ();
		$con = $model->conectar ();
		$response = new stdClass ();
		$response->idhabitacion = $idhabitacion;
		$this->log->setLog ( "INPUT", $response, __METHOD__, __LINE__ );
		$sql = "UPDATE " . self::NOMBRE_TABLA . " SET habestadodisp = :habestadodisp";
		$sql .= " WHERE idhabitacion = :idhabitacion";
		$consulta = $con->prepare ( $sql );
		$consulta->bindParam ( ':idhabitacion', $idhabitacion );
		$consulta->bindParam ( ':habestadodisp', $estadoDisponibilidad );
		$consulta->execute ();
		$con = null;
	}
	public function verificarBloqueoHabitaciones($idHotHotel) {
		$model = new conexionDB ();
		$con = $model->conectar ();
		$con->exec ( "SET CHARACTER SET utf8" );
		// Fecha Actual
		$fechaActual = date ( "Y-m-d" );
		// Segun el Hotel verificar el bloqueo de habitaciones
		$sql = "SELECT idhabitacion as habitaciones ";
		$sql .= " FROM hothabreservas ";
		$sql .= " WHERE revestado =3 ";
		$sql .= " AND revfechaingreso <= :fechaActual ";
		$sql .= " AND revfechasalida >=  :fechaActual ";
		$sql .= " AND hothabreservas.idhabitacion IN(SELECT idhabitacion FROM hothabitaciones WHERE idhothotel=:idhothotel) ";
		$consulta = $con->prepare ( $sql );
		$consulta->bindParam ( ':fechaActual', $fechaActual );
		$consulta->bindParam ( ':idhothotel', $idHotHotel );
		$consulta->execute ();
		
		$listado = $consulta->fetchAll ( PDO::FETCH_OBJ );
		
		$habitaciones = "";
		foreach ( $listado as $lista ) {
			$habitaciones .= "," . $lista->habitaciones;
		}
		$habitaciones = trim ( substr ( $habitaciones, 1, strlen ( $habitaciones ) ) );
		$this->log->setLog ( "OUTPUT", $habitaciones, __METHOD__, __LINE__ );
		if (empty ( $habitaciones )) {
			// Si es cero entonces ninguna habitaci�n deberia estar como bloqueada tendria que estar libre(0)
			$this->log->setLog ( "OUTPUT", "LIBRE", __METHOD__, __LINE__ );
			$update = " UPDATE hothabitaciones SET habestadodisp=0 where habestadodisp=2 ";
			$consulta = $con->prepare ( $update );
			$consulta->execute ();
		} else {
			// En caso de existir convierte el estado de la o las habitaciones a 2 (bloqueada)
			$this->log->setLog ( "OUTPUT", "BLOQUEO", __METHOD__, __LINE__ );
			$update = " UPDATE hothabitaciones SET habestadodisp=2 WHERE idhabitacion IN ($habitaciones) ";
			$consulta = $con->prepare ( $update );
			$consulta->execute ();
		}
		
		$con = null;
	}
	public function encontrarProximaReserva($idhabitacion) {
		$model = new conexionDB ();
		$con = $model->conectar ();
		$con->exec ( "SET CHARACTER SET utf8" );
		// Buscar fecha proxima en reservas segun fecha de incio por habitacion apartir de la fecha actual
		$fechaActual = date ( "Y-m-d" );
		$fechaProxima = 0;
		while ( $fechaProxima <= 30 ) {
			$sql = " SELECT count(*) as contador from hothabreservas ";
			$sql .= " INNER JOIN hothuesped on hothuesped.idreserva = hothabreservas.idreserva";
			$sql .= " WHERE revfechaingreso=:nuevaFecha and revestado=1 ";
			$sql .= " AND idhabitacion= :idhabitacion ";
			$consulta = $con->prepare ( $sql );
			$consulta->bindParam ( ':nuevaFecha', $fechaActual );
			$consulta->bindParam ( ':idhabitacion', $idhabitacion );
			$consulta->execute ();
			$listado = $consulta->fetchAll ( PDO::FETCH_OBJ );
			
			$habitaciones = "";
			$response = array ();
			foreach ( $listado as $lista ) {
				$contador = $lista->contador;
				if ($contador > 0) {
					$datosReserva = " SELECT revfechaingreso,revfechasalida,revcodigoreserva,huenombre,huetelefono from hothabreservas ";
					$datosReserva .= " INNER JOIN hothuesped on hothuesped.idreserva = hothabreservas.idreserva ";
					$datosReserva .= " WHERE revfechaingreso=:nuevaFecha and revestado=1";
					$datosReserva .= " AND idhabitacion= :idhabitacion ";
					$consulta = $con->prepare ( $datosReserva );
					$consulta->bindParam ( ':nuevaFecha', $fechaActual );
					$consulta->bindParam ( ':idhabitacion', $idhabitacion );
					$consulta->execute ();
					$listadoReserva = $consulta->fetchAll ( PDO::FETCH_OBJ );
					foreach ( $listadoReserva as $reserva ) {
						$obj = new stdClass ();
						$obj->revfechaingreso = $reserva->revfechaingreso;
						$obj->revfechasalida = $reserva->revfechasalida;
						$obj->revcodigoreserva = $reserva->revcodigoreserva;
						$obj->huenombre = $reserva->huenombre;
						$obj->huetelefono = $reserva->huetelefono;
						$response [] = $obj;
						$this->log->setLog ( "OUTPUT", $response, __METHOD__, __LINE__ );
						return $response;
					}
				}
			}
			$fechaProxima = $fechaProxima + 1;
			$nuevafecha = strtotime ( '+1 day', strtotime ( $fechaActual ) );
			$fechaActual = date ( 'Y-m-j', $nuevafecha );
		}
		$con = null;
		return $response;
	}
public function habitacionesDisponibleHotel($idHotel, $habmaxmayor, $habmaxmenor, $fechaLlegada, $fechaSalida) {
		$model = new conexionDB ();
		$con = $model->conectar ();
		$con->exec ( "SET CHARACTER SET utf8" );
		$listaDisponibles = $this->listaHabitacionesDisponibles($idHotel, $habmaxmayor, $habmaxmenor, $fechaLlegada, $fechaSalida);
		$sqlHabitaciones = "SELECT * FROM hothabitaciones ";
		$sqlHabitaciones .= " WHERE idhothotel = $idHotel";
		$sqlHabitaciones .= " AND habestado = 1";
		$sqlHabitaciones .= " AND idhabitacion IN ($listaDisponibles) ";
		$sqlHabitaciones .= " order by habtarifamayor asc ";
		$this->log->setLog ( "OUTPUT", $sqlHabitaciones, __METHOD__, __LINE__ );
		$consultaHab = $con->prepare ( $sqlHabitaciones );
		$consultaHab->execute ();
		$listaHab = $consultaHab->fetchAll ( PDO::FETCH_OBJ );
		$this->log->setLog ( "OUTPUT", $listaHab, __METHOD__, __LINE__ );
		$arrayhabitacion = array ();
		$response = array ();
		foreach ( $listaHab as $lista ) {
			$obj = new stdClass ();
			$obj->idhabitacion = $lista->idhabitacion;
			$obj->idhothotel = $lista->idhothotel;
			$obj->habmaxmayor = $lista->habmaxmayor;
			$obj->habmaxmenor = $lista->habmaxmenor;
			$obj->habtarifamayor = $lista->habtarifamayor;
			$obj->habtarifamenor = $lista->habtarifamenor;
			$obj->habdsc = $lista->habdsc;
			$obj->habfecdesde = $lista->habfecdesde;
			$obj->habfechasta = $lista->habfechasta;
			$obj->habestado = $lista->habestado;
			$obj->habfecalta = $lista->habfecalta;
			$obj->idtiphab = $lista->idtiphab;
			$obj->habtipo = $this->getTipo ( $lista->idtiphab );
			$obj->habcaracteristicas = $this->obtenerCaracteristicas ( $lista->idhabitacion );
			$obj->habservicios = $this->obtenerServicios ( $lista->idhabitacion ); 
			$obj->habcantidadreservas = $this->getCantidadReservas ( $lista->idhabitacion );
			$response [] = $obj;
		}
		return $response;
	}
	private function listaHabitacionesDisponibles($idHotel, $habmaxmayor, $habmaxmenor, $fechaLlegada, $fechaSalida) {
		$model = new conexionDB ();
		$con = $model->conectar ();
		$con->exec ( "SET CHARACTER SET utf8" );
		$response = false;
		$sqlHabitaciones = "SELECT * FROM hothabitaciones ";
		$sqlHabitaciones .= " WHERE idhothotel = :idHotel";
		$sqlHabitaciones .= " AND habestado = 1";
		$sqlHabitaciones .= " AND habmaxmayor >= :habmaxmayor ";
		$sqlHabitaciones .= " AND habmaxmenor >= :habmaxmenor ";
		$sqlHabitaciones .= " AND habfecdesde <= :fechaDesde ";
		$sqlHabitaciones .= " AND habfechasta >= :fechaHasta ";
		$sqlHabitaciones .= " order by habtarifamayor asc ";
		$this->log->setLog ( "OUTPUT", $sqlHabitaciones, __METHOD__, __LINE__ );
		$consultaHab = $con->prepare ( $sqlHabitaciones );
		$consultaHab->bindParam ( ':idHotel', $idHotel );
		$consultaHab->bindParam ( ':habmaxmayor', $habmaxmayor );
		$consultaHab->bindParam ( ':habmaxmenor', $habmaxmenor );
		$consultaHab->bindParam ( ':fechaDesde', $fechaLlegada );
		$consultaHab->bindParam ( ':fechaHasta', $fechaSalida );
		$consultaHab->execute ();
		$listaHab = $consultaHab->fetchAll ( PDO::FETCH_OBJ );
		$this->log->setLog ( "OUTPUT", $listaHab, __METHOD__, __LINE__ );
		$arrayhabitacion = array ();
		$listaHabitaciones="";
		foreach ( $listaHab as $habitacion ) {
			$idhabitacion = $habitacion->idhabitacion;
			// Validacion misma fecha de llegada con la fecha de inicio de una reserva para excluir su disponilidad
			$sqlReservaVal = "SELECT count(*) as contador FROM hothabreservas ";
			$sqlReservaVal .= " WHERE idhabitacion= :idhabitacion ";
			$sqlReservaVal .= " AND revfechaingreso = :revfechaingreso";
			$sqlReservaVal .= " AND revestado IN(1,3,4)"; // Estado reservado, Reservado Por Hotel,check in
			$consultaRevVal = $con->prepare ( $sqlReservaVal );
			$consultaRevVal->bindParam ( ':idhabitacion', $idhabitacion );
			$consultaRevVal->bindParam ( ':revfechaingreso', $fechaLlegada );
			$consultaRevVal->execute ();
			$lstContador = $consultaRevVal->fetchAll ( PDO::FETCH_OBJ );
			$this->log->setLog ( "OUTPUT", $lstContador, __METHOD__, __LINE__ );
			$mesLlegada = substr ( $fechaLlegada, 5, 2 );
			$this->log->setLog ( "OUTPUT", $mesLlegada, __METHOD__, __LINE__ );
			$contador;
			foreach ( $lstContador as $cnt ) {
				$contador = $cnt->contador;
			}
			if ($contador == 0) {
				// Buscar reservas actuales
				$sqlReservas = " SELECT * FROM hothabreservas WHERE idhabitacion= :idhabitacion";
				$sqlReservas .= " AND revestado IN (1,4) "; // Estado reservado, check in
				$sqlReservas .= " AND MONTH(revfechaingreso) = :mesIngreso";
				$consultaRev = $con->prepare ( $sqlReservas );
				$consultaRev->bindParam ( ':idhabitacion', $idhabitacion );
				$consultaRev->bindParam ( ':mesIngreso', $mesLlegada );
				$consultaRev->execute();
				$listaReservas = $consultaRev->fetchAll ( PDO::FETCH_OBJ );
				$this->log->setLog ( "OUTPUT", $listaReservas, __METHOD__, __LINE__ );
				$tamListaReservas = sizeof ( $listaReservas );
				if ($tamListaReservas == 0) {
					$listaHabitaciones .= "," . $idhabitacion;
				}
				foreach ( $listaReservas as $reservas ) {
					$fechaSalidaBD = $reservas->revfechasalida;
					$this->log->setLog ( "OUTPUT", $fechaSalidaBD, __METHOD__, __LINE__ );
					$fechaInicial = $fechaLlegada;
					$this->log->setLog ( "OUTPUT", $fechaInicial, __METHOD__, __LINE__ );
					
					$fechaInicioBD = $reservas->revfechaingreso;
					$fechaFinal = $fechaSalida;
					if ($fechaInicial > $fechaSalidaBD) {
						$this->log->setLog ( "OUTPUT", " Fecha inicial ", __METHOD__, __LINE__ );
						$this->log->setLog ( "OUTPUT", $fechaFinal, __METHOD__, __LINE__ );
						$this->log->setLog ( "OUTPUT", $fechaInicioBD, __METHOD__, __LINE__ );
						if ($fechaFinal > $fechaInicioBD) {
							$listaHabitaciones .= "," . $reservas->idhabitacion;
						}
					}
				}
			}
		}
		$listaHabitaciones = trim ( substr ( $listaHabitaciones, 1, strlen ( $listaHabitaciones ) ) );
		$this->log->setLog ( "OUTPUT", $listaHabitaciones, __METHOD__, __LINE__ );
		$con = null;
		return $listaHabitaciones;
	}
}
?>