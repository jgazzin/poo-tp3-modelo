<?php
require_once "./ibaseDatos.php";

class BdArray implements IBaseDatos{
    protected $baseDatos;
    protected $erroresBD = [];

    public function __construct($datosIniciales=[])
    {
        $this->baseDatos = $datosIniciales;
    }

    public function insertar($nuevoElemento, $clave=null)
    {
        if($clave===null){
            $clave = time();
        }
        // chequeo que la clave no exista antes de guardarla, aprovechando el método
        if($this->buscarPorClave($clave)!==null){
            $this->erroresBD = ['Clave ingresada duplicada'];
            return;
        }
        $this->baseDatos[$clave] = $nuevoElemento;
        $this->erroresBD = [];
    }

    public function buscarPorClave($clave){
        if(array_key_exists($clave, $this->baseDatos)){
            return $this->baseDatos[$clave];
        }else{
            $this->erroresBD = ['Clave inexistente'];
        }
        return null;
    }

    public function buscarPorApellido($apellido="")
    {
        $resultado = [];
        foreach($this->baseDatos as $alumno){
            if(empty($apellido) || $alumno->getApellido() === $apellido){
                $resultado[] = $alumno;
            }
        }        
        return $resultado;
    }
    public function borrar($clave){
    }

    public function reemplazar($clave, $nuevoElemento){   
    }

    public function getErroresBD(){
        return $this->erroresBD;
    }

}