<?php
require_once "./alumnos.php";
require_once "./alumnoregular.php";

require_once "./ibaseDatos.php";
require_once "./bdPDO.php";
require_once "./bdArray.php";

require_once "./utiles.php";
require_once "./menu.php";


class Ejercicio {
    private $menu;
    private $bd;

    public function __construct(IBaseDatos $bd){
        $this->menu = new Menu();
        $this->bd = $bd;
    }

    public function iniciarEjercicio(){
        do {
            $this->menu->presentarOpciones();
            $opcion = Utiles::pedirInformacion('Elija una opción');
            $errores = [];
            $this->menu->ejecutarAccion($opcion, $this->bd, $errores);
            if(count($errores)>0){
                echo ("Ocurrieron los siguientes errores: ".PHP_EOL);
                foreach($errores as $error){
                    echo($error.PHP_EOL);
                }
            }
        }while($opcion!=="S");
    }
}


// inicio del ejercicio
$opcionBD = Utiles::pedirInformacion('Elija la fuente de la BD 1- Arreglos | 2- PDO');

switch($opcionBD){
    case "1":
            $pk1 = time();
            $alu1 = new Alumno($pk1, 'ape1', 'ipoo', 10);
            $pk2 = time()+1;
            $alu2 = new AlumnoRegular($pk2, 'ape2', 'ipoo', 5, 2012);
            $inicio = [$pk1=>$alu1, $pk2=>$alu2];


            $bdArray = new BdArray($inicio);    
            $ejercicio = new Ejercicio($bdArray);     
            $ejercicio->iniciarEjercicio();
        break;
    case "2":
        $bdPDO = new BdPDO('sqlite:'.__DIR__."/sqlite.db");
        $ejercicio = new Ejercicio($bdPDO);
        $ejercicio->iniciarEjercicio();
        break;
    default:
        Utiles::informarUsuario('Opción inválida', "e");
}