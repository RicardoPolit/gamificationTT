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

    private $sections   = array(); // Ids en tabla course_sections
    private $sectionsXP = array(); // Ids en tabla gmdl_seccion_curso

    public function experienceEnabled(){
        return ($this->get_course()->xpEnabled == 1);
    }
    
    public function createSectionXP($section){

        $id  = $this->getSectionid($section);
        
        global $DB;
        $record = new stdClass();
        $record->mdl_id_seccion_curso   = $id;
        $record->experiencia_de_seccion = 0;
        $DB->insert_record('gmdl_seccion_curso', $record);
    }
    
    public function getSectionXP(int $section){
        
        $id = $this->getSectionXPid($section);
        
        global $DB;
        $where = array(
            'id' => $id
        );
        
        global $DB;
        $exp = $DB->get_record('gmdl_seccion_curso',$where,'experiencia_de_seccion');
        
        return $exp->experiencia_de_seccion;
    }
    
    public function setSectionXP($section,$experiencia){
                
        $sectionid = $this->getSectionid($section);
        $id        = $this->getSectionXPid($section);
        
        global $DB;
        $record = new stdClass();
        $record->id                     = $id;
        $record->mdl_id_seccion_curso   = $sectionid;
        $record->experiencia_de_seccion = $experiencia;
        
        $DB->update_record('gmdl_seccion_curso', $record);
    }
    
    public function delete_section($section, $forcedeleteifnotempty = false) {
        
        global $DB;
        $sectionxp = $section;
        if (!is_object($sectionxp)) {
            $sectionxp = $DB->get_record('course_sections', array('course' => $this->get_courseid(), 'section' => $sectionxp),
                'id,section,sequence,summary');
        }
        
        $succeed = parent::delete_section($section, $forcedeleteifnotempty);
        if( $succeed === true ){
            $where = array( 'mdl_id_seccion_curso'  => $sectionxp->id );
            $DB->delete_records('gmdl_seccion_curso', $where);
            
            $this->sections   = array();
            $this->sectionsXP = array();
        }
        
        return $succeed;
    }

    public function setDefaultSectionXP(){
        
        $numsec  = $this->getSectionNum();
        $totalxp = (int)get_config('block_gmxp','firstExpGiven');
        
        if( $numsec==1 ){ // course only has section 0
            $this->setSectionXP(0,$totalxp);
            return;
        }
        
        $numsec--; // excluding section 0
        $exp = floor($totalxp/$numsec);
        $last = $exp + ($totalxp - ($exp*$numsec));
        
        for($i=1;$i<$numsec;$i++)
            $this->setSectionXP($i,$exp);
            
        $this->setSectionXP($numsec,$last);
    }

    /**
     * @Override Whether this format allows to delete an specific section
     *
     * Do not call this function directly, instead use {@link course_can_delete_section()}
     *
     * @param int|stdClass|section_info $section
     * @return bool
     */
    public function can_delete_section($section) {

        if (is_object($section))
            $sectionnum = $section->section;
        else
            $sectionnum = $section;

        // If sectionXP is 0, then it can be deleted
        return ($this->getSectionXP($sectionnum) == 0);
    }

    public function isExperienceSet(){
        
        $num = $this->getSectionNum();
        
        global $DB;
        for($x=0;$x<$num;$x++){
        
            $where = array(
                'id' => $this->getSectionXPid(0));
                
            $sec = $DB->get_record('gmdl_seccion_curso',$where,'experiencia_de_seccion');
            
            if($sec->experiencia_de_seccion != 0)
                return true;
        }
        return false;
    }

    /**
     * Metodo usado para obtener el numero de secciones del curso
     */
    public function getSectionNum(){
        $where = array(
            'course'  => $this->get_course()->id,
        );
           
        global $DB;
        return $DB->count_records('course_sections', $where);
    }
    
    /** 
     * Metodo usado para nbtiener el id de las seccionesXP
     *
     * El identificador que devuelve este elemento es el
     * que esta presente en la tabla 'gmdl_seccion_curso' 
     *
     * Este metodo evita pedir consultas a la base de datos
     * recurrentemente
     */
    private function getSectionXPid(int $section){
        
        if( isset($this->sectionsXP[$section]) )
            return $this->sectionsXP[$section];
        
        $sectionid = $this->getSectionid($section);
        $where = array(
            'mdl_id_seccion_curso' => $sectionid
        );
        
        global $DB;
        $id = $DB->get_record('gmdl_seccion_curso',$where,'id');
        
        $this->sectionsXP[$section] = $id->id;
        return $this->sectionsXP[$section];
    }
    
    /** 
     * Metodo usado para nbtiener el id de las secciones
     *
     * El identificador que devuelve este elemento es el
     * que esta presente en la tabla 'course_sections' 
     *
     * Este metodo evita pedir consultas a la base de datos
     * recurrentemente
     */
    private function getSectionid(int $section){
        
        if( isset($this->sections[$section]) )
            return $this->sections[$section];
            
        $where = array(
            'course'  => $this->get_course()->id,
            'section' => $section
        );
        
        global $DB;
        $id  = $DB->get_record('course_sections', $where, 'id');
        
        $this->sections[$section] = $id->id;
        return $this->sections[$section];
    }

    /**
     * Definitions of the additional options that this course format uses for course
     *  Please read the docs of format/lib.php file.
     *
     * Topics format uses the following options (inherited):
     * - coursedisplay
     * - hiddensectionss
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
                'label' => new lang_string('hiddenmissions','format_gamedle'),
                'element_type' => 'select',
                'element_attributes' => array(
                    array(
                        0 => new lang_string('hiddenmissionscollapsed','format_gamedle'),
                        1 => new lang_string('hiddenmissionsinvisible','format_gamedle')
                    )
                ),
                'help' => 'hiddenmissions',
                'help_component' => 'format_gamedle',
            );

            $optionsedit['coursedisplay'] = array(
                'label' => new lang_string('coursedisplay'),
                'element_type' => 'select',
                'element_attributes' => array(
                    array(
                        COURSE_DISPLAY_SINGLEPAGE => new lang_string('coursedisplay_single','format_gamedle'),
                        COURSE_DISPLAY_MULTIPAGE => new lang_string('coursedisplay_multi','format_gamedle')
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
