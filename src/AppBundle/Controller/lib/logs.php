<?php

class logs
{
	private $pathLogs;
    private $nroProceso;

    public function __construct(){
    	//$this->pathLogs = '/home/omar/Servidor/hoteles_transoft_bo/app/logs/';
        $this->pathLogs = '/var/sentora/hostdata/transoft/public_html/hoteles_transoft_bo/app/logs/';
    	$this->nroProceso = implode('-', array(mt_rand(10, 99), mt_rand(10, 99)));
    }

	public function setLog( $descripcion, $input, $metodo, $linea = "", $parametrosAdicionales = array() ){
        $stringParametros = "";
        if( count($parametrosAdicionales) == 0 ){
        	$arrayNombre = explode(":", $metodo);
        	$nombreClase = $arrayNombre[0];
            $nombreMetodo = $arrayNombre[2];
            $nombreArchivo = $nombreClase . "." . $nombreMetodo . "." . date('Y_m_d') . ".log";
        }
        else{
        	if( array_key_exists("nombreArchivo", $parametrosAdicionales) ){
                $nombreArchivo = $parametrosAdicionales["nombreArchivo"] . "." . date('Y_m_d') . ".log";
                unset($parametrosAdicionales["nombreArchivo"]);
            }
            else{
                $arrayNombre = explode(":", $metodo);
                $nombreClase = $arrayNombre[0];
                $nombreMetodo = $arrayNombre[2];
                $nombreArchivo = $nombreClase . "." . $nombreMetodo . "." . date('Y_m_d') . ".log";
            }
            foreach( $parametrosAdicionales as $clave => $value )
            	$stringParametros .= strtoupper($clave) . ": " . $value . " ";
        }
        $descripcion = "PROCESO[" . $this->nroProceso . "] " . $descripcion . " METODO " . $metodo;
        if( $linea != "" ){
            $descripcion .= " LINEA " . $linea;
        }
        $descripcion .= " [" . date('Y-m-d H:i:s') . "]";
        if( $stringParametros != "" )
        	$descripcion .= PHP_EOL . $stringParametros;
        if( is_object($input) == true || is_array($input) == true ){
        	$descripcion .= PHP_EOL . PHP_EOL . print_r($input, true);
            //$descripcion .= PHP_EOL . PHP_EOL . json_encode($input);
        }
        elseif( is_bool($input) == true ){
            if( $input == true )
                $descripcion .= PHP_EOL . PHP_EOL . "TRUE";
            else
                $descripcion .= PHP_EOL . PHP_EOL . "FALSE";
        }
        else{
            $descripcion .= PHP_EOL . PHP_EOL . trim($input);
        }
        error_log($descripcion . PHP_EOL . PHP_EOL, 3, $this->pathLogs . $nombreArchivo);
    }
}

?>