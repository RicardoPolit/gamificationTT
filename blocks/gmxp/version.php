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

/*
 * Read the docs:
 *   https://docs.moodle.org/dev/version.php
 */

$plugin->component = 'block_gmxp';  // Recommended since 2.0.2 (MDL-26035). Required since 3.0 (MDL-48494)
$plugin->version =  2019050211;     // YYYYMMDDHH (year, month, day, 24-hr time)
$plugin->requires = 2010112400;     // YYYYMMDDHH (This is the release version for Moodle 2.0)

$plugin->dependencies = array(
    'local_gamedlemaster' => 2019042101 // Should Specify the version
);
