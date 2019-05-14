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

    private $format = null;

    public function print_multiple_section_page($course, $sections, $mods, $modnames, $modnamesused){
    
        $this->format = course_get_format($this->page->course);
        
        parent::print_multiple_section_page($course, $sections, $mods, $modnames, $modnamesused);
        
        /* TODO: Buttons to submmit experience assignment
         *  Button1 = assign by default
         *  Button2 = assign with values given
         */
         
        global $PAGE;
        if($PAGE->user_is_editing())
            echo '
            <div class="form-group fitem btn-cancel" style="float:right;margin:10px">
                <span data-fieldtype="submit">
                    <input id="defaultXP" class="btn btn-secondary" type="submit" value="'.get_string('btnDefaultXP','format_gamedle').'">
                </span>
                <div class="form-control-feedback invalid-feedback"></div>
            </div>
            <div class="form-control-feedback invalid-feedback"></div>
            <div class="form-group fitem form-submit" style="float:right;margin:10px">
                <span data-fieldtype="submit">
                    <input id="submitXP" class="btn btn-primary" type="submit" value="'.get_string('btnSaveXP','format_gamedle').'">
                </span>
                <div class="form-control-feedback invalid-feedback"></div>
            </div>';

    }

    protected function section_right_content($section, $course, $onsectionpage){
    
        $input = "";
        $exp = $this->format->getSectionXP($section->section);
        $o   = parent::section_right_content($section,$course,$onsectionpage);
        
        global $PAGE;
        if($PAGE->user_is_editing())
            $input = '<div>Mission XP: <input type="text" class="sectionXP" value="'.$exp.'" style="text-align:right" data-section="'.$section->section.'"></div>';
        
        return $o.$input;
    }
    
    private function debugWebConsole($tag,$object){
        echo("<script>console.log('".$tag.": ".json_encode($object)."');</script>");
    }
}
