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

/**
 * Serve the files from the MYPLUGIN file areas
 *
 * @param stdClass $course the course object
 * @param stdClass $cm the course module object
 * @param stdClass $context the context
 * @param string $filearea the name of the file area
 * @param array $args extra arguments (itemid, path)
 * @param bool $forcedownload whether or not force download
 * @param array $options additional options affecting the file serving
 * @return bool false if the file not found, just send the file otherwise and do not return anything
 */

// Used for myprofile callback

function block_gmcz_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options=array()) {

}

function block_gmcz_myprofile_navigation(core_user\output\myprofile\tree $tree,
  $user, $iscurrentuser, $course){
}

function block_gmcz_extend_navigation($node, $course, $module, $cm) {
}

?>
