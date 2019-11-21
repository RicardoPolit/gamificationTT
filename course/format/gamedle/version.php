<?php
/*
 * UNIVERSITY:  INSTITUTO POLITECNICO NACIONAL (IPN)
 *              ESCUELA SUPERIOR DE COMPUTO (ESCOM)
 *   SUBJECT:     _
 *   PROFESSOR:   _
 *
 * DESARROLLADORES: Daniel Ortega
 *
 * PRACTICE _:  TITULO DE LA PRACTICA
 *               - DESCRIPCION
 *		
*/

defined('MOODLE_INTERNAL') || die();

$plugin->component = 'format_gamedle';
$plugin->version =  2019052927; // YYYYMMDDHH (year, month, day, 24-hr time)
$plugin->requires = 2014111000; // YYYYMMDDHH (This is the release version for Moodle 2.8)

$plugin->dependencies = array(
    'local_gamedlemaster' => 2019050800 // Should Specify the version
);
