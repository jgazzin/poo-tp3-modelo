<?php
require_once "./alumnos.php";
require_once "./alumnoregular.php";
require_once "./utiles.php";
require_once "./ibaseDatos.php";

class Menu {
    private $opciones = [
                'A'=>'Cargar datos',
                'B'=>'Borrar datos',
                'M'=>'Modificar',
                'L'=>'Buscar alumnos por apellido',
                'S'=>'Salir'
            ];  

    public function __construct(){
    }

    public function presentarOpciones(){
        
        foreach($this->opciones as $opcion=>$mensaje){
            Utiles::informarUsuario("$opcion - $mensaje".PHP_EOL);
            // echo ("$opcion - $mensaje".PHP_EOL);
        }
    }

    public function ejecutarAccion($opcion, IBaseDatos $bd, &$errores){
        switch($opcion){
            case "A":
                echo ("Ejecutando CARGAR DATOS".PHP_EOL); 
                $this->cargarDatos($bd, $errores);
                break;
            case "B":
                echo "Ejecutando BORRAR DATOS".PHP_EOL; 
                $this->borrarDatos($bd, $errores);
                break;
            case "M":
                echo "Ejecutando MODIFICAR DATOS".PHP_EOL; 
                $this->modificarDatos($bd, $errores);
                break;
            case "L":
                echo "Ejecutando LISTAR DATOS".PHP_EOL; 
                $this->listarDatos($bd);
                break;
            case "S":
                echo "Ejecutando SALIR".PHP_EOL; 
                break;
            default:
                echo "Opción inválida".PHP_EOL;
                break;
        }
    }


    private function listarDatos(IBaseDatos $bd){
        $apellidoBuscado = Utiles::pedirInformacion('Ingrese un nombre a buscar o presione ENTER para todos', false);

        $resultado =  $bd->buscarPorApellido($apellidoBuscado);
        foreach($resultado as $alumno){
            Utiles::informarUsuario($alumno->imprimir());
            // echo($alumno->imprimir());
        }
    }


    private function cargarDatos(IBaseDatos $bd, &$errores){
        $nuevoAlumno = $this->pedirDatosAlumno();
        if($nuevoAlumno->esAlumnoValido()){
            $bd->insertar($nuevoAlumno, $nuevoAlumno->getPk());
            $errores = $bd->getErroresBD();
        }else{
            $errores = $nuevoAlumno->getErrores();
        }
    }



    private function pedirDatosAlumno($alumno=null){
        // si no pasamos una clave, es porque queremos una nueva 
        if($alumno===null){
            $pk = Utiles::pedirInformacion('Ingrese la clave de identificación [Enter para una genérica]');
            if(empty($pk)){
                $pk = time();
            }
        }else{
            $pk = $alumno->getPk();
        }
        $apellido = Utiles::pedirInformacion('Ingrese un nombre');
        $materia = Utiles::pedirInformacion('Ingrese una materia');
        $nota = Utiles::pedirInformacion('Ingrese una nota');
        $regular = Utiles::pedirInformacion('¿El alumno es regular?');
        if($regular==="S"){
            $anioRegularidad = Utiles::pedirInformacion('Ingrese año de regularidad');
            $nuevoAlumno = new AlumnoRegular($pk, $apellido, $materia, $nota, $anioRegularidad);
        }else{
            $nuevoAlumno = new Alumno($pk, $apellido, $materia, $nota);
        }
        // var_dump($nuevoAlumno);
        
        return $nuevoAlumno;
    }



    private function borrarDatos(IBaseDatos $bd, &$errores){
        $this->listarDatos($bd);
        $pk = Utiles::pedirInformacion('Ingrese la clave de identificación a borrar');
        $bd->borrar($pk);
        $errores = $bd->getErroresBD();
    }



    private function modificarDatos(IBaseDatos $bd, &$errores){
        $this->listarDatos($bd);
        $pk = Utiles::pedirInformacion('Ingrese la clave de identificación a modificar');
        $alumnoAnterior = $bd->buscarPorClave($pk);
        //var_dump($alumnoAnterior);

        if($alumnoAnterior===null){
            $errores = $bd->getErroresBD();
            return;
        }
        $alumnoModificado = $this->pedirDatosAlumno($alumnoAnterior);

        if($alumnoModificado->esAlumnoValido()===false){
            $errores = $alumnoModificado->getErrores();
            return;
        }
        $bd->reemplazar($pk, $alumnoModificado);
        $errores = $bd->getErroresBD();
    }

}