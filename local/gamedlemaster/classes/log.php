<?php
/*
 * UNIVERSITY:  INSTITUTO POLITECNICO NACIONAL (IPN)
 *              ESCUELA SUPERIOR DE COMPUTO (ESCOM)
 *
 *   PROJECT:     Gamedle
 *   PROFESSOR:   Sandra Bautista, Andres Catalan.
 *
 * DESARROLLADORES: Daniel Ortega (GitLab/Github @DanielOrtegaZ)
*/

class local_gamedlemaster_log {

    const ANSI_RESET = "\e[0m";
    const ANSI_BLACK = "\e[0;30";
    const ANSI_DARK_GREY = "\e[1;30";
    const ANSI_RED = "\e[0;31";
    const ANSI_LIGHT_RED = "\e[1;31";
    const ANSI_GREEN = "\e[0;32";
    const ANSI_LIGHT_GREEN = "\e[1;32";
    const ANSI_BROWN = "\e[0;33";
    const ANSI_YELLOW = "\e[1;33";
    const ANSI_BLUE = "\e[0;34";
    const ANSI_LIGHT_BLUE = "\e[1;34";
    const ANSI_MAGENTA = "\e[0;35";
    const ANSI_LIGHT_MAGENTA = "\e[1;35";
    const ANSI_CYAN = "\e[0;36";
    const ANSI_LIGHT_CYAN = "\e[1;36";
    const ANSI_LIGHT_GREY = "\e[0;37";
    const ANSI_WHITE = "\e[1;37";
    
    const ANSI_BLACK_BACKGROUND = ";40";
    const ANSI_RED_BACKGROUND = ";41";
    const ANSI_GREEN_BACKGROUND = ";42";
    const ANSI_YELLOW_BACKGROUND = ";43";
    const ANSI_BLUE_BACKGROUND = ";44";
    const ANSI_MAGENTA_BACKGROUND = ";45";
    const ANSI_CYAN_BACKGROUND = ";46";
    const ANSI_LIGHT_GREY_BACKGROUND = ";47";

    public static function info($arg, string $key = '') {
        local_gamedlemaster_log::log(local_gamedlemaster_log::ANSI_BLUE, $arg, $key);
    }

    public static function success($arg, string $key = '') {
        local_gamedlemaster_log::log(local_gamedlemaster_log::ANSI_GREEN, $arg, $key);
    }

    public static function warning($arg, string $key = '') {
        local_gamedlemaster_log::log(local_gamedlemaster_log::ANSI_YELLOW, $arg, $key);
    }

    public static function error($arg, string $key = '') {
        local_gamedlemaster_log::log(local_gamedlemaster_log::ANSI_RED, $arg, $key);
    }

    private static function log( string $color, $arg, string $key) {

        if(!defined('STDOUT'))
            define('STDOUT', fopen('php://stdout', 'wb'));

        fwrite(STDOUT, "GAMEDLE: ".local_gamedlemaster_log::ANSI_BLACK.
                       local_gamedlemaster_log::ANSI_YELLOW_BACKGROUND."m {$key} ".
                       local_gamedlemaster_log::ANSI_RESET."\n");

        fwrite(STDOUT, $color.'m'.print_r($arg, true).
               local_gamedlemaster_log::ANSI_RESET."\n");
    }
}

?>
