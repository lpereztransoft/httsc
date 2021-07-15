<?php
namespace AppBundle\Helpers;
use Symfony\Component\HttpFoundation\Request;

class Log {
    public $pathLogs;
    public $nroProceso;

    public function __construct() {
        $this->pathLogs = "/home/omar/Servidor/hoteles_transoft_bo/app/logs/";
        $this->nroProceso = implode('-', array(mt_rand(10, 99), mt_rand(10, 99)));
    }

    public function setLog($descripcion, $input, $metodo, $linea = "", $swJson = false, $parametrosAdicionales = array()) {
        $stringParametros = "";
        if (count($parametrosAdicionales) == 0) {
            $arrayNombre = explode("\\", $metodo);
            $metodo = $arrayNombre[3];
            $arrayNombre = explode(":", $metodo);
            $nombreClase = $arrayNombre[0];
            $nombreMetodo = $arrayNombre[2];
            $nombreArchivo = $nombreClase . "." . $nombreMetodo . "." . date('Y_m_d') . ".log";
        } else {
            if (array_key_exists("nombreArchivo", $parametrosAdicionales)) {
                $nombreArchivo = $parametrosAdicionales["nombreArchivo"] . "." . date('Y_m_d') . ".log";
                unset($parametrosAdicionales["nombreArchivo"]);
            } else {
                $arrayNombre = explode("\\", $metodo);
                $metodo = $arrayNombre[3];
                $arrayNombre = explode(":", $metodo);
                $nombreClase = $arrayNombre[0];
                $nombreMetodo = $arrayNombre[2];
                $nombreArchivo = $nombreClase . "." . $nombreMetodo . "." . date('Y_m_d') . ".log";
            }
            foreach ($parametrosAdicionales as $clave => $value)
                $stringParametros .= strtoupper($clave) . ": " . $value . " ";
        }
        $descripcion = "PROCESO[" . $this->nroProceso . "] " . $descripcion . " METODO " . $metodo;
        if ($linea != "") {
            $descripcion .= " LINEA " . $linea;
        }
        $descripcion .= " [" . date('Y-m-d H:i:s') . "]";
        if ($stringParametros != "")
            $descripcion .= PHP_EOL . $stringParametros;
        if(is_bool($input)){
            if($input == false)
                $input = "FALSE";
            elseif($input == true);
            $input = "TRUE";
        }elseif(is_string($input)){
            $input = trim($input);
        }else{
            if($swJson == true)
                $input = print_r($input, true) . PHP_EOL . PHP_EOL . json_encode($input);
            else
                $input = print_r($input, true);
        }
        error_log($descripcion . PHP_EOL . PHP_EOL . $input . PHP_EOL . PHP_EOL, 3, $this->pathLogs . $nombreArchivo);
    }

    public function setLogException($metodo, $linea = "", $exception, $client = false, $parametrosAdicionales = array()) {
        $descripcion = "EXCEPCION OCURRIDA";
        $stringParametros = "";
        if (count($parametrosAdicionales) == 0) {
            $arrayNombre = explode("\\", $metodo);
            $metodo = $arrayNombre[3];
            $arrayNombre = explode(":", $metodo);
            $nombreClase = $arrayNombre[0];
            $nombreMetodo = $arrayNombre[2];
            $nombreArchivo = $nombreClase . "." . $nombreMetodo . "." . date('Y_m_d') . ".log";
        } else {
            if (array_key_exists("nombreArchivo", $parametrosAdicionales)) {
                $nombreArchivo = $parametrosAdicionales["nombreArchivo"] . "." . date('Y_m_d') . ".log";
                unset($parametrosAdicionales["nombreArchivo"]);
            } else {
                $arrayNombre = explode("\\", $metodo);
                $metodo = $arrayNombre[3];
                $arrayNombre = explode(":", $metodo);
                $nombreClase = $arrayNombre[0];
                $nombreMetodo = $arrayNombre[2];
                $nombreArchivo = $nombreClase . "." . $nombreMetodo . "." . date('Y_m_d') . ".log";
            }
            foreach ($parametrosAdicionales as $clave => $value)
                $stringParametros .= strtoupper($clave) . ": " . $value . " ";
        }
        $descripcion = "PROCESO[" . $this->nroProceso . "] " . $descripcion . " METODO " . $metodo;
        $descripcion .= " LINEA " . $linea;
        $descripcion .= " [" . date('Y-m-d H:i:s') . "]";
        if ($stringParametros != "")
            $descripcion .= PHP_EOL . $stringParametros;
        $descripcion .= PHP_EOL . PHP_EOL . "FILE: " . $exception->getFile();
        $descripcion .= PHP_EOL . "CODE: " . $exception->getCode();
        $descripcion .= PHP_EOL . "LINE: " . $exception->getLine();
        $descripcion .= PHP_EOL . "MESSAGE: " . $exception->getMessage();
        if ($client != false) {
            $descripcion .= PHP_EOL . "LAST REQUEST: " . PHP_EOL . PHP_EOL . $client->__getLastRequest();
            $descripcion .= PHP_EOL . "LAST RESPONSE: " . PHP_EOL . PHP_EOL . $client->__getLastResponse();
        }
        error_log($descripcion . PHP_EOL . PHP_EOL, 3, $this->pathLogs . $nombreArchivo);
    }
}
