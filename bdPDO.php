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
        $ape1 = $nuevoElemento->getApellido();
        $mat1 = $nuevoElemento->getMateria();
        $nota1 = $nuevoElemento->getNota();
        if ($nuevoElemento instanceof Alumno) {
            $regular1 = 0;
            $anio1=null;
        } else {
            $regular1 = 1;
            $anio1 = $nuevoElemento->getAnioRegularidad();
        }
        // echo $ape1 . "\n";

        $consultaInsertar = "insert into persona (apellido, materia, nota, regular, anio)
            values (:apellido, :materia, :nota, :regular, :anio)";

        $sentencia = $this->bd->prepare($consultaInsertar);
        $sentencia->bindValue(":apellido",$ape1);
        $sentencia->bindValue(":materia", $mat1);
        $sentencia->bindValue(":nota", $nota1);
        $sentencia->bindValue(":regular", $regular1);
        $sentencia->bindValue(":anio", $anio1);

        $resultado = $sentencia->execute();

    }




    public function buscarPorClave($clave){

        $where = "where id = '$clave'";
        $resultados = $this->bd->query("SELECT * FROM persona ".$where);

        $lista = [];
        foreach ($resultados as $fila) {

            if($fila['regular']=="1"){
                $nuevo = new AlumnoRegular($fila['ID'], $fila['apellido'], $fila['materia'], $fila['nota'], $fila['anio']);
            }else{
                $nuevo = new Alumno($fila['ID'], $fila['apellido'], $fila['materia'], $fila['nota']);
            }
            $lista[$fila['ID']] = $nuevo;
        }
        // var_dump($nuevo);
        return $nuevo; 

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
        $alumnoBorrrar = $this->buscarPorClave($clave);
        // var_dump($alumnoBorrrar);
        if(empty($alumnoBorrrar)) {
            $this->erroresBD = ['Clave no existe'];
            return;
        }
        $consultaBorrar = "DELETE from persona where id = :id";
        $sentencia = $this->bd->prepare($consultaBorrar);
        $sentencia->bindValue(":id", $clave);
        $resultado = $sentencia->execute();

    }


    public function reemplazar($clave, $elementoNuevo){
        // echo $clave;
        // var_dump($elementoNuevo);
        if($elementoNuevo->esAlumnoValido()){
            Utiles::informarUsuario("Es alumno válido".PHP_EOL);
            $this->insertar($elementoNuevo, $clave);
        } else{
            $this->erroresBD = ['Datos de alumno no válidos'];
        }
 
    }


    public function getErroresBD(){
        return $this->erroresBD;
    }

}
