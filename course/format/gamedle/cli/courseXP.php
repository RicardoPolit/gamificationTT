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

include('../../../../config.php');
require_once($CFG->dirroot.'/course/format/gamedle/lib.php');
    
    // function to send responde in JSON format
    function response($correct,$sumErr=false){
    
        $status = $correct ? "OK":"BAD";
        $msg    = $correct ? 'submitSuccess':'submitError';
        $msg    = $sumErr  ? 'msgErrorSum'  : $msg;
        
        $msg = get_string($msg,'format_gamedle');
        
        echo json_encode( array( "status"=>$status, "msg"=>$msg ) );
        exit;
    }

    local_gamedlemaster_log::info($_POST);
    if( !isset($_POST['defaultXP']) ) response(false);
    
    // Getting the needed variables|objects
    $courseid  = $_POST['id'];
    $defaultxp = $_POST['defaultXP'];
    $format    = course_get_format($courseid);
    
    // Checking if format if correct
    if( $format->get_format() === "gamedle" ){
    
        if( $defaultxp === "true" ){
            $format->setDefaultSectionXP();
            response(true);
        }
        
        else { // else if( $defaultxp === "false" ){
            
            $sections = $_POST['data'];
            $totalxp  = (int)get_config('block_gmxp',block_gmxp_core::COURSEXP);
            $sum = 0;
            
            foreach($sections as $xp)
                $sum += $xp;
            
            if($sum === $totalxp){
                foreach($sections as $section => $xp)
                    $format->setSectionXP($section,$xp); 
                response(true);
            }
            else
                response(false, $sumErr=true);
        }
    }
    
    response(false);
?>
