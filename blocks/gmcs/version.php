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

$plugin->component = 'block_gmcs';  // Recommended since 2.0.2 (MDL-26035). Required since 3.0 (MDL
$plugin->version =  2019072521;     // YYYYMMDDHH (year, month, day, 24-hr time)
$plugin->requires = 2010112400;     // YYYYMMDDHH (This is the release version for Moodle 2.0)

$plugin->dependencies = array(
    'local_gamedlemaster' => 2019042101 // Should Specify the version
);
?>
