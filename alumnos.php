<?php

class Alumno {
    protected $pk;
    protected $apellido;
    protected $materia;
    protected $nota;
    protected $errores = [];

    public function __construct($pPk, $pApellido, $pMateria, $pNota)
    {
        $this->pk = $pPk;
        $this->apellido = $pApellido;
        $this->materia = $pMateria;
        $this->nota = $pNota;
    }

    public function getPk(){
        return $this->pk;
    }

    public function getErrores(){
        return $this->errores;
    }
    
    public function getApellido(){
        return $this->apellido;
    }

    public function getNota(){
        return $this->nota;
    }

    public function getMateria(){
        return $this->materia;
    }


    public function aproboAlumno(){
        return ($this->nota > 6);
    }

    public function esAlumnoValido(){
        $this->errores = [];
        if(empty($this->apellido)){
            $this->errores[] = 'apellido no puede $algo ser vacío';
        }
        if(empty($this->materia)){
            $this->errores[] = 'materia no puede ser vacío';
        }
        if(!is_numeric($this->nota) || $this->nota>10 || $this->nota<0){
            $this->errores[] = 'nota tiene que ser un número entre 0 y 10';
        }
        return count($this->errores)===0;
    }

    //controlamos si el método imprime o devuelve la info
    public function imprimir(){
        $impresion = <<<EOL
            pk: \t {$this->pk}
            apellido: \t {$this->apellido}
            materia: \t {$this->materia}
            nota: \t {$this->nota}
            regular: \t NO
            aprobó: \t {$this->aproboAlumno()}
            ---------------- \n
        EOL;
        return $impresion;
    }
} 
 