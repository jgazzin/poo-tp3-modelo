<?php
require_once "./ibaseDatos.php";
require_once "./menu.php";
require_once "./utiles.php";

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