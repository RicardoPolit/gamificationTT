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

    // Exiting if undesired behave
    if( !isset($_SESSION['Gamedle']['format'])   ) response(false);
    if( $_SESSION['Gamedle']['format'] === false ) response(false);
    if( !isset($_POST['defaultXP']) ) response(false);
    
    // Getting the needed variables|objects
    $courseid  = $_SESSION['Gamedle']['format'];
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
            $totalxp  = (int)get_config('block_gmxp','firstExpGiven');
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
