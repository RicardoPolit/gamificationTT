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

require_once($CFG->dirroot. '/course/format/topics/lib.php');

class format_buttons extends format_topics{
	
	/* ATRIBUTES */
	public function course_format_options($foreditform = false) {
	
	    // Static variable in order to no recreate it.
        static $options = false;    
        if($options === false){
            $courseconfig = get_config('moodlecourse');
            
            /* Default options for Topics Format */
            $options = array(
                'hiddensections' => array(
                    'default' => $courseconfig->hiddensections,
                    'type' => PARAM_INT,
                ),
                'coursedisplay' => array(
                    'default' => $courseconfig->coursedisplay,
                    'type' => PARAM_INT,
                ),
            );
            
            /* Added options for Gamedle */
        }
        
        if($foreditform && !isset($options['coursedisplay']['label'])){
        
            /* Default options for Topics Format */
            $optionsEdit = array(
                'hiddensections' => array(
                    'label' => new lang_string('hiddensections'),
                    'element_type' => 'select',
                    'element_attributes' => array( array(
                        0 => new lang_string('hiddensectionscollapsed'),
                        1 => new lang_string('hiddensectionsinvisible'))
                    ),
                    'help' => 'hiddensections',
                    'help_component' => 'moodle',
                ),
                'coursedisplay' => array(
                    'label' => new lang_string('coursedisplay'),
                    'element_type' => 'select',
                    'element_attributes' => array(array(
                        COURSE_DISPLAY_SINGLEPAGE => new lang_string('coursedisplay_single'),
                        COURSE_DISPLAY_MULTIPAGE => new lang_string('coursedisplay_multi'))
                    ),
                    'help' => 'coursedisplay',
                    'help_component' => 'moodle',
                )
            );
            
            /* Added options for Gamedle */
            
            $options = array_merge_recursive($options, $optionsEdit);
        }
        
        return $options;
    }
}

?>
