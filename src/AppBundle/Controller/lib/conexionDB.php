<?php

require_once 'logs.php';

class conexionDB{

	private $data_base_type;
	private $host;
	private $data_base_name;
	private $user;
	private $password;


	public function __construct(){
		$this->data_base_type = 'mysql';
		$this->host = 'localhost';
		$this->data_base_name = 'transoft_hoteles';
		$this->user = 'root';
		$this->password = 'mysql';
	}

	public function conectar(){
		$obj = new stdClass();
		$obj->data_base_type = $this->data_base_type;
		$obj->host = $this->host;
		$obj->data_base_name = $this->data_base_name;
		$obj->user = $this->user;
		$obj->password = $this->password;

		try{
			return new PDO($this->data_base_type.':host='.$this->host.';dbname='.$this->data_base_name, $this->user, $this->password);
		}
		catch(PDOException $e){
			$log = new logs();
			$log->setLog("PDO-ERROR: {/ ".$e->getMessage()." /}", $obj, __METHOD__, __LINE__);
			exit;
		}
	}
}

//$c = new conexionDB();
//echo "<pre>".print_r($c->conectar(),true)."</pre>";

?>