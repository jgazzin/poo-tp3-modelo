<?php

class Utiles {

    public static function pedirInformacion($mensaje, $mayuscula=true, $quitarEspacios=true){
        static::informarUsuario("$mensaje: ".PHP_EOL);
        $entradaUsuario = fgets(STDIN);
        if($mayuscula){
            $entradaUsuario = strtoupper($entradaUsuario);
        }
        if($quitarEspacios){
            $entradaUsuario = trim($entradaUsuario);
        }        
        return $entradaUsuario;
    }

    // https://stackoverflow.com/a/66075475
    public static function informarUsuario($mensaje, $tipoMensaje="i"){
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
               echo $mensaje;
               return;
            }        
            switch ($tipoMensaje) {
                case 'e': //error
                    echo "\033[31m$mensaje \033[0m\n";
                break;
                case 's': //success
                    echo "\033[32m$mensaje \033[0m\n";
                break;
                case 'w': //warning
                    echo "\033[33m$mensaje \033[0m\n";
                break;  
                case 'i': //info
                    echo "\033[36m$mensaje \033[0m\n";
                break;      
                default:
                    echo "sin formato:".$mensaje;
                break;
        }        
    }
}