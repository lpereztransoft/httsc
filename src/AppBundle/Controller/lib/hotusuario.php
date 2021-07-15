<?php
require_once 'conexionDB.php';
require_once 'logs.php';
require_once 'hotusu_hothot.php';

use Symfony\Component\HttpFoundation\Session\Session;
class hotusuario
{
	const NOMBRE_TABLA = "hotusuario";
	public $idusu;
	private $usunombre;
	private $usuemail;
	private $usupassword;
	private $usuestado;
	private $usufechaalta;
	private $usufecmod;
	private $usuci;
	private $usutelefono;
	private $usufechanac;

	private $log;
	private $idInsertado;
	public $session;
	protected $_session;
	private $hotusu_hothot;

	public function __construct($idusu=null){
		$this->log = new logs();
        $this->idusu = $idusu;
        $this->hotusu_hothot = new hotusu_hothot();
    }
	public function setUsuNombre($usunombre){
		$this->usunombre = $usunombre;
	}

	public function setUsuEmail($usuemail){
		$this->usuemail = $usuemail;
	}
	public function setUsuPassword($usupassword){
		$this->usupassword = $usupassword;
	}
	public function setUsuEstado($usuestado){
		$this->usuestado = $usuestado;
	}
	public function setUsuCi($usuci){
		$this->usuci = $usuci;
	}
	public function setUsuTelefono($usutelefono){
		$this->usutelefono = $usutelefono;
	}
	public function setUsuFechaNac($usufechanac){
		$this->usufechanac = $usufechanac;
	}
	public function setUsuFechaAlta($usufechaalta){
		$this->usufechaalta = $usufechaalta;
	}
	public function setUsuFecMod($usufecmod){
		$this->usufecmod = $usufecmod;
	}
	public function getNombreTabla(){
		return self::NOMBRE_TABLA;
	}
	public function getIdUltimaInsercion(){
        return $this->idInsertado;
    }
    public function getSession()
    {
    	if ( !$this->_session )
    		$this->_session  =  new Session();
    		return $this->_session;
    }
    public function insertar(){
		$model = new conexionDB();
		$con = $model->conectar();
		$con->exec ( "SET CHARACTER SET utf8" );

		$response = new stdClass();
		$response->usunombre = $this->usunombre;
		$response->usuemail = $this->usuemail;
		$response->usupassword = $this->usupassword;
		$response->usuestado = $this->usuestado;
		$response->usuci = $this->usuci;
		$response->usutelefono = $this->usutelefono;
		$response->usufechanac = $this->usufechanac;
		$response->usufechaalta = $this->usufechaalta;
		$response->usufecmod = $this->usufecmod;
		

		$this->log->setLog("INPUT", $response, __METHOD__, __LINE__);

		$sql = "INSERT INTO ".self::NOMBRE_TABLA." (usunombre, usuemail, usupassword, usuestado, usufechaalta, usufecmod,usuci,usutelefono,usufechanac)";
		$sql .= " VALUES (:usunombre, :usuemail, :usupassword, :usuestado, :usufechaalta, :usufecmod,:usuci,:usutelefono,:usufechanac)";
		$consulta = $con->prepare($sql);
		$consulta->bindParam(':usunombre', $this->usunombre);
		$consulta->bindParam(':usuemail', $this->usuemail);
		$consulta->bindParam(':usupassword', $this->usupassword);
		$consulta->bindParam(':usuestado', $this->usuestado);
		$consulta->bindParam(':usufechaalta',$this->usufechaalta);
		$consulta->bindParam(':usufecmod', $this->usufecmod);
		$consulta->bindParam(':usuci', $this->usuci);
		$consulta->bindParam(':usutelefono', $this->usutelefono);
		$consulta->bindParam(':usufechanac', $this->usufechanac);
		$consulta->execute();
		$this->idInsertado = $con->lastInsertId();
		$con = null;
	}
	public function listar($idusuario){
		$model = new conexionDB();
		$con = $model->conectar();
		$con->exec ( "SET CHARACTER SET utf8" );
		
		if( $idusuario == 1 ){
    	$sql = "SELECT * FROM ".self::NOMBRE_TABLA;
    	}else{
    	$usuarios = $this->hotusu_hothot->obtenerUsuarios($idusuario);
    	$sql = "SELECT * FROM ".self::NOMBRE_TABLA;	
    	//$sql .= " WHERE idusu <> 1";  
    	$sql .= " WHERE idusu IN($usuarios)";
    	}
    	$sql .= " order by usunombre ";
    	$consulta = $con->prepare($sql);
		$consulta->execute();

		$listado = $consulta->fetchAll(PDO::FETCH_OBJ);
		$response = array();
		foreach($listado as $lista){
			$obj = new stdClass();
			$obj->idusu = $lista->idusu;
			$obj->usunombre = $lista->usunombre;
			$obj->usuemail = $lista->usuemail;
			$obj->usupassword = $lista->usupassword;
			$obj->usuestado = $lista->usuestado;
			$obj->usufechaalta = $lista->usufechaalta;
			$obj->usufecmod = $lista->usufecmod;
			$obj->usuci = $lista->usuci;
			$obj->usutelefono = $lista->usutelefono;
			$obj->usufechanac = $lista->usufechanac;
			$obj->usurol = $this->obtenerRol($obj->idusu);
			
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
		$response->idusu = $this->idusu;
		$response->usunombre = $this->usunombre;
		$response->usuemail = $this->usuemail;
		$response->usupassword = $this->usupassword;
		$response->usuestado = $this->usuestado;
		
		$response->usuci = $this->usuci;
		$response->usutelefono = $this->usutelefono;
		$response->usufechanac = $this->usufechanac;
		
		$response->usufecmod = $this->usufecmod;
		$this->log->setLog("INPUT", $response, __METHOD__, __LINE__);

		
		$sql = " UPDATE ".self::NOMBRE_TABLA." SET usunombre = :usunombre, usuemail = :usuemail, usupassword = :usupassword,";
		$sql .= " usuestado = :usuestado, usuci = :usuci,usutelefono= :usutelefono,usufechanac= :usufechanac,usufecmod = :usufecmod";
		$sql .= " WHERE idusu = :idusu";
		$consulta = $con->prepare($sql);
		$consulta->bindParam(':idusu', $this->idusu);
		$consulta->bindParam(':usunombre', $this->usunombre);
		$consulta->bindParam(':usuemail', $this->usuemail);
		$consulta->bindParam(':usupassword', $this->usupassword);
		$consulta->bindParam(':usuestado', $this->usuestado);
		$consulta->bindParam(':usuci', $this->usuci);
		$consulta->bindParam(':usutelefono', $this->usutelefono);
		$consulta->bindParam(':usufechanac', $this->usufechanac);
		$consulta->bindParam(':usufecmod', $this->usufecmod);
		$consulta->execute();
		$con = null;
	}
	public function eliminar(){
		$model = new conexionDB();
		$con = $model->conectar();

		$response = new stdClass();
		$response->idusu = $this->idusu;
		$this->log->setLog("INPUT", $response, __METHOD__, __LINE__);

		$sql = "DELETE FROM ".self::NOMBRE_TABLA." WHERE idusu = :idusu";
		$consulta = $con->prepare($sql);
		$consulta->bindParam(':idusu', $this->idusu);
		$consulta->execute();
		$con = null;
	}
	public function verificarDatosUsuario($usuEmail,$usuPassword){
		$correcto = false;
		$model = new conexionDB();
		$con = $model->conectar();
		$con->exec ( "SET CHARACTER SET utf8" );
		$this->log->setLog("INPUT", $usuEmail, __METHOD__, __LINE__);
		$sql = "SELECT * FROM ".self::NOMBRE_TABLA." WHERE usuemail = :usuemail";
		$consulta = $con->prepare($sql);
		$consulta->bindParam(':usuemail', $usuEmail);
		$consulta->execute();
		$listado = $consulta->fetchAll(PDO::FETCH_OBJ);
		if (empty($listado)) {
			return false;
		}
		$usupasswordBd='';
		//$this->session = new Session();
		$this->getSession();
		foreach($listado as $lista){
			$usupasswordBd = $lista->usupassword;
			/*$this->session->set('idUsuario', $lista->idusu);
			$this->session->set('name', $lista->usunombre);			
			$this->session->set('email', $lista->usuemail);			
			$this->session->set('rol', $this->obtenerRol($lista->idusu));
			$this->session->set('rolId',$this->obtenerRolId($lista->idusu));*/
			$this->getSession()->set('idUsuario', $lista->idusu);
			$this->getSession()->set('name', $lista->usunombre);
			$this->getSession()->set('email', $lista->usuemail);
			$this->getSession()->set('rol', $this->obtenerRol($lista->idusu));
			$this->getSession()->set('rolId',$this->obtenerRolId($lista->idusu));
		}
		//Accesos del usuario
		$consultaRecursos = " SELECT  hotrecursos.idrec,hotrecursos.recdsc,`hotrol_hotrec`.`listar`,`hotrol_hotrec`.`insertar`,`hotrol_hotrec`.`modificar`,`hotrol_hotrec`.`eliminar` ";
		$consultaRecursos .= " FROM `hotusuario` ";
		$consultaRecursos .= " INNER JOIN `hotusu_hotrol` on hotidusu = idusu ";
		$consultaRecursos .= " INNER JOIN `hotroles` on idrol = hotidrol ";
		$consultaRecursos .= " INNER JOIN `hotrol_hotrec` on hotrol_hotrec.hotidrol = idrol ";
		$consultaRecursos .= " INNER JOIN `hotrecursos` on hotrecursos.idrec = hotrol_hotrec.hotidrec ";
		$consultaRecursos .= " WHERE usuemail = :usuemail";
		$consul = $con->prepare($consultaRecursos);
		$consul->bindParam(':usuemail', $usuEmail);
		$consul->execute();
		$listadoRec = $consul->fetchAll(PDO::FETCH_OBJ);
		if (empty($listadoRec)) {
			//No tiene recursos asignados
			return false;
		}else{
			foreach($listadoRec as $recurso){
				$objeto = new stdClass();
				$recdsc = $recurso->recdsc;
				$objeto->listar = $recurso->listar;
				$objeto->insertar=$recurso->insertar;
				$objeto->modificar=$recurso->modificar;
				$objeto->eliminar=$recurso->eliminar;
				$this->getSession()->set($recdsc,$objeto);
				//$this->log->setLog("OUTPUT", $this->session, __METHOD__, __LINE__);
			}
		}

		//
		$this->log->setLog("INPUT", $usupasswordBd, __METHOD__, __LINE__);
		$correcto = password_verify($usuPassword,$usupasswordBd);
		$this->log->setLog("OUTPUT", $correcto, __METHOD__, __LINE__);
		$con = null;
		return $correcto;
	}
	
	public function obtenerRol($idusuario){
		$model = new conexionDB();
		$con = $model->conectar();
		$con->exec ( "SET CHARACTER SET utf8" );		
		$sql = "SELECT hotroles.roldsc FROM hotusuario LEFT OUTER JOIN (hotroles LEFT OUTER JOIN hotusu_hotrol ON hotroles.idrol = hotusu_hotrol.hotidrol) ON hotusuario.idusu = hotusu_hotrol.hotidusu";
		$sql .= " WHERE hotusuario.idusu = ".$idusuario;
		$consulta = $con->prepare($sql);
		$consulta->execute();
		$response = $consulta->fetch(PDO::FETCH_OBJ)->roldsc;
		$con = null;
		return $response;		
	}
	public function obtenerRolId($idusuario){
		$model = new conexionDB();
		$con = $model->conectar();
		$con->exec ( "SET CHARACTER SET utf8" );
		$sql = "SELECT hotroles.idrol FROM hotusuario LEFT OUTER JOIN (hotroles LEFT OUTER JOIN hotusu_hotrol ON hotroles.idrol = hotusu_hotrol.hotidrol) ON hotusuario.idusu = hotusu_hotrol.hotidusu";
		$sql .= " WHERE hotusuario.idusu = ".$idusuario;
		$consulta = $con->prepare($sql);
		$consulta->execute();
		$response = $consulta->fetch(PDO::FETCH_OBJ)->idrol;
		$con = null;
		return $response;
	}
	public function obtenerHotelesPorUsuario($idUsuario){
		$model = new conexionDB();
		$con = $model->conectar();
		$con->exec ( "SET CHARACTER SET utf8" );
		$sql = "SELECT `idHotel`,`hotnombre` FROM `hothoteles` ";
		$sql .= " INNER JOIN `hotusu_hothot` ON `hotusu_hothot`.`hotidhotel`= `hothoteles`.`idHotel` ";
		$sql .= " WHERE `hotusu_hothot`.`hotidusu`=".$idUsuario;
		$sql .= " AND `hothoteles`.`hotestado`=1";
		$consulta = $con->prepare($sql);
		$consulta->execute();

		$response = $consulta->fetchAll(PDO::FETCH_OBJ);
		$this->log->setLog("OUTPUT", $response, __METHOD__, __LINE__);
		$con = null;

		return $response;
	}
}
?>