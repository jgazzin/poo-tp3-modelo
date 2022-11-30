<?php
require_once "./alumnos.php";
require_once "./alumnoregular.php";

require_once "./ibaseDatos.php";
require_once "./bdPDO.php";
require_once "./bdArray.php";

require_once "./utiles.php";
require_once "./menu.php";
require_once "./ejercicio.php";

// inicia el ejercicio
$opcionBD = Utiles::pedirInformacion('Elija la fuente de la BD 1- Arreglos | 2- PDO');

switch($opcionBD){
    case "1":
            $pk1 = time();
            $alu1 = new Alumno($pk1, 'ape1', 'ipoo', 10);
            $pk2 = time()+1;
            $alu2 = new AlumnoRegular($pk2, 'ape2', 'ipoo', 2, 2012);
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