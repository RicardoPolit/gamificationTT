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

        /* TODO: Add button for save experience points configurations */
    }

    protected function section_right_content($section, $course, $onsectionpage) {

        global $PAGE;
        $o = parent::section_right_content($section,$course,$onsectionpage);

        $input = "";
        if($PAGE->user_is_editing())
            $input = "<divº>Mission XP: <input type='text' placeholder='1000'></divº>";
        
        return $o.$input;
    }
}
