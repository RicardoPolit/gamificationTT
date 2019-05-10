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
require_once($CFG->dirroot.'/course/format/topics/renderer.php');

class format_gamedle_renderer extends format_topics_renderer{

    public function print_multiple_section_page($course, $sections, $mods, $modnames, $modnamesused){
        parent::print_multiple_section_page($course, $sections, $mods, $modnames, $modnamesused);
        
        /* TODO: Buttons to submmit experience assignment
         *  Button1 = assign by default
         *  Button2 = assign with values given
         */
        
        if(isset($_SESSION['Gamedle']['format']))
        $this->debugWebConsole("GFR",$_SESSION['Gamedle']['format']);
    }
    
    private function debugWebConsole($tag,$object){
        echo("<script>console.log('".$tag.": ".json_encode($object)."');</script>");
    }

    protected function section_right_content($section, $course, $onsectionpage) {

        global $PAGE;
        $o = parent::section_right_content($section,$course,$onsectionpage);

        $input = "";
        if($PAGE->user_is_editing())
            $input = "<div>Mission XP: <input type='text' placeholder='".get_config('block_gmxp','firstExpGiven')."'></div>";
        
        return $o.$input;
    }
}
