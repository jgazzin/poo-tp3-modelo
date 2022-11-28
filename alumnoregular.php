<?php
require_once "./alumnos.php";

class AlumnoRegular extends Alumno{
    protected $anioRegularidad;

    public function __construct($pPk, $pApellido, $pMateria, $pNota, $pAnioRegularidad)
    {
        parent::__construct($pPk, $pApellido, $pMateria, $pNota);
        $this->anioRegularidad = $pAnioRegularidad;
    }

    public function getAnioRegularidad(){
        return $this->anioRegularidad;
    }

    public function aproboAlumno(){
        return ($this->nota > 4);
    }

    public function esAlumnoValido(){
        parent::esAlumnoValido();
        if(!is_numeric($this->anioRegularidad) || $this->anioRegularidad>2022 || $this->anioRegularidad<1900){
            $this->errores[] = 'Anio de regularidad tiene que ser un número entre 1900 y 2022';
        }
        return count($this->errores)===0;
    }

    public function imprimir(){
        $impresion = <<<EOL
            pk: \t {$this->pk}
            apellido: \t {$this->apellido}
            materia: \t {$this->materia}
            nota: \t {$this->nota}
            regular: \t SI
            año regularidad: \t {$this->anioRegularidad}
            aprobó: \t {$this->aproboAlumno()}
            ---------------- \n
        EOL;
        return $impresion;
    }
    
}