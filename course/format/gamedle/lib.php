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

/**
 * Course format options can be accessed as:
 * $this->get_course()->OPTIONNAME (inside the format class)
 * course_get_format($course)->get_course()->OPTIONNAME (outside of format class)
 */

class format_gamedle extends format_topics {

    /**
     * Definitions of the additional options that this course format uses for course
     *  Please read the docs of format/lib.php file.
     *
     * Topics format uses the following options (inherited):
     * - coursedisplay
     * - hiddensections
     *
     * All course options are returned by calling:
     * $this->get_format_options();
     *
     * @param bool $foreditform
     * @return array of options
     */
    public function course_format_options($foreditform = false) {
        static $options = false;
        if ($options === false) { // Create the options for first time
            $courseconfig = get_config('moodlecourse');

            $options = array();
            $options['hiddensections'] = array(
                'default' => $courseconfig->hiddensections,
                'type' => PARAM_INT,
            );
            $options['coursedisplay'] = array(
                'default' => $courseconfig->coursedisplay,
                'type' => PARAM_INT,
            );
            $options['xpEnabled'] = array(
                'default' => 1,
                'type' => PARAM_INT,
            );
            
        } // Create the elements of the form in course edir/settings
        if ($foreditform && !isset($options['coursedisplay']['label'])) {
            $optionsedit = array();

            $optionsedit['hiddensections'] = array(
                'label' => new lang_string('hiddensections'),
                'element_type' => 'select',
                'element_attributes' => array(
                    array(
                        0 => new lang_string('hiddensectionscollapsed'),
                        1 => new lang_string('hiddensectionsinvisible')
                    )
                ),
                'help' => 'hiddensections',
                'help_component' => 'moodle',
            );

            $optionsedit['coursedisplay'] = array(
                'label' => new lang_string('coursedisplay'),
                'element_type' => 'select',
                'element_attributes' => array(
                    array(
                        COURSE_DISPLAY_SINGLEPAGE => new lang_string('coursedisplay_single'),
                        COURSE_DISPLAY_MULTIPAGE => new lang_string('coursedisplay_multi')
                    )
                ),
                'help' => 'coursedisplay',
                'help_component' => 'moodle',
            );

            $optionsedit['xpEnabled'] = array(
                'label' => new lang_string('optionXP','format_gamedle'),
                'element_type' => 'advcheckbox',
                'element_attributes' => array(
                    new lang_string('optionXP_desc','format_gamedle'),
                ),
                'help' => 'optionXP',
                'help_component' => 'format_gamedle',
            );

            $options = array_merge_recursive($options, $optionsedit);
        }
        return $options;
    }

    /**
     * @Override to perform extra validation of the format options
     *  Please read the docs of format/lib.php file.
     *
     * @param array $data array of ("fieldname"=>value) of submitted data
     * @param array $files array of uploaded files "element_name"=>tmp_file_path
     * @param array $errors errors already discovered in edit form validation
     * @return array of "element_name"=>"error_description" if there are errors,
     *         or an empty array if everything is OK.
     *         Do not repeat errors from $errors param here
     */
    public function edit_form_validation($data, $files, $errors) {
    
        /* TODO: Perform extra validation
         *  and return in case of error detected
         */
        return array();
    }
}

?>
