<?php
require_once "./ibaseDatos.php";
require_once "./alumnos.php";
require_once "./alumnoregular.php";

class BdPDO implements IBaseDatos {
    protected $bd;
    protected $erroresBD = [];

    public function __construct($conexion){
        $bd = new PDO($conexion);
        // activamos el modo de notificación de errores para que nos avise si ocurre algo
        $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->bd = $bd;
    }
    
    public function insertar($nuevoElemento, $clave=null){
    }

    public function buscarPorClave($clave){
        // buscar por clave es similar a buscar por apellido
        // pero con la diferencia de:
        // * $clave no debería ser nulo
        // * no se devuelve un array, sino un solo objeto
        // * observar lo que hace la versión de BdArray en este método cuando la búsqueda
        //   no da resultados
    }

    public function buscarPorApellido($apellido){
        $where = "";
        
        if(!empty($apellido)){
            $where = "where apellido = '$apellido'";
        }

        $resultados = $this->bd->query("SELECT * FROM persona ".$where);
        $lista = [];
        foreach ($resultados as $fila) {
            // Esta lógica podría estar en una función aparte, o en un método
            // de las clases
            if($fila['regular']=="1"){
                $nuevo = new AlumnoRegular($fila['ID'], $fila['apellido'], $fila['materia'], $fila['nota'], $fila['anio']);
            }else{
                $nuevo = new Alumno($fila['ID'], $fila['apellido'], $fila['materia'], $fila['nota']);
            }
            $lista[] = $nuevo;
        }
        return $lista;        

    }
    public function borrar($clave){
    }

    public function reemplazar($clave, $elementoNuevo){
        
    }

    public function getErroresBD(){
        return $this->erroresBD;
    }

}
