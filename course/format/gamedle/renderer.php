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
         
        global $PAGE;
        if($PAGE->user_is_editing()){
            // TODO Pass to constants
            $totalxp = (int)get_config('block_gmxp',block_gmxp_core::COURSEXP);
            
            echo'<div id="submitError" class="gmxp-submit-error">'.
                    get_string('msgError','format_gamedle',$totalxp).'
                    <span id="gmxp-sum">'.get_string('msgErrorSum','format_gamedle').'
                        <span id="gmxp-sum-val"></span>
                    </span>
                </div>
                <div class="btn-cancel gmxp-btn">
                    <span data-fieldtype="submit">
                        <input id="defaultXP" class="btn btn-secondary" type="submit" value="'.
                            get_string('btnDefaultXP','format_gamedle').'">
                    </span>
                </div>
                <div class="form-submit gmxp-btn">
                    <span data-fieldtype="submit">
                        <input id="submitXP" class="btn btn-primary" type="submit" value="'.
                            get_string('btnSaveXP','format_gamedle').'">
                    </span>
                </div>
                <div id="submitmsg" style="clear:right;text-align:right;margin-right:30px"></div>';
        }
    }

    protected function section_right_content($section, $course, $onsectionpage){
    
        $input = "";
        $id  = $section->section;
        $exp = $this->format->getSectionXP($section->section);
        $o   = parent::section_right_content($section,$course,$onsectionpage);
        
        global $PAGE;
        if($PAGE->user_is_editing()){        
            $input .= '<div>Mission XP: ';
            
            if($this->format->isExperienceGiven($section->section))
                $input .= '<input type="text" class="gmxp-section" value="'.$exp.'" data-section="'.$id.'" disabled>';
            else
                $input .= '<input type="text" class="gmxp-section" value="'.$exp.'" data-section="'.$id.'">';
            
            $input .= '</div>
                <div id="xpError'. $id.'" class="gmxp-section-error">'.
                    get_string('msgErrorNum','format_gamedle').
                '</div>';
        }
        return $o.$input;
    }
    
    private function debugWebConsole($tag,$object){
        echo("<script>console.log('".$tag.": ".json_encode($object)."');</script>");
    }
}
