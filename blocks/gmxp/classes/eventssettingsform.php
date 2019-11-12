<?php
/*
 * UNIVERSITY:  INSTITUTO POLITECNICO NACIONAL (IPN)
 *              ESCUELA SUPERIOR DE COMPUTO (ESCOM)
 *
 *   PROJECT:     Gamedle
 *   PROFESSOR:   Sandra Bautista, Andres Catalan.
 *
 * DESARROLLADORES: Daniel Ortega (GitLab/Github @DanielOrtegaZ)
*/

class block_gmxp_eventssettingsform extends local_gamedlemaster_form {

    const PLUGIN = 'block_gmxp';
    const COMPETENCECPU = block_gmxp_core::COMPETENCECPU;
    const COMPETENCECPUXP = block_gmxp_core::COMPETENCECPUXP;
    const COMPETENCECPUGROUP = block_gmxp_core::COMPETENCECPUGROUP;

    const COMPETENCEVS = block_gmxp_core::COMPETENCEVS;
    const COMPETENCEVSXP = block_gmxp_core::COMPETENCEVSXP;
    const COMPETENCEVSGROUP = block_gmxp_core::COMPETENCEVSGROUP;

    const PREGUNTADIARIA = block_gmxp_core::PREGUNTADIARIA;
    const PREGUNTADIARIAXP = block_gmxp_core::PREGUNTADIARIAXP;
    const PREGUNTADIARIAGROUP = block_gmxp_core::PREGUNTADIARIAGROUP;

    const XP_REGEX = '/^([0-9]){1,9}$/';
    const XP_MAX_REGEX_LENGTH = 9;

    protected function definition() {

        $mform = $this->_form; // Inherited from moodle form

        $this->set_title(get_string('SETTINGS_EVENTS', self::PLUGIN));
        $this->set_subtitle(get_string('SETTINGS_EVENTS_HEADER', self::PLUGIN));
        $this->set_description(get_string('SETTINGS_EVENTS_DESC', self::PLUGIN));

        $this->create_definition();

        $this->add_action_buttons();
    }

    private function create_definition() {

        $this->create_group('COMPCPU',self::COMPETENCECPU,self::COMPETENCECPUXP,self::COMPETENCECPUGROUP);

        $this->create_group('COMPVS',self::COMPETENCEVS,self::COMPETENCEVSXP,self::COMPETENCEVSGROUP);

        $this->create_group('PREGDIARIA',self::PREGUNTADIARIA,self::PREGUNTADIARIAXP,self::PREGUNTADIARIAGROUP);

    }

    private function create_group($string,$idcheck,$idxp,$idgroup){

        $mform =& $this->_form;
        $group1 = array();
        $group1[] =& $mform->createElement('advcheckbox', $idcheck,
            get_string('EVENTS_SETTING_TEXT_'.$string, self::PLUGIN),
            get_string('EVENTS_SETTING_TEXT_XP_SUPPORT', self::PLUGIN),
            array('group' => 1), array(0, 1));
        $group1[] =& $mform->createElement('text', $idxp,
            get_string('EVENTS_SETTING_TEXT_XP', self::PLUGIN), array(
                'size' => self::XP_MAX_REGEX_LENGTH,
                'maxlength' => self::XP_MAX_REGEX_LENGTH
            ));
        $mform->addGroup($group1, $idgroup,
            get_string('EVENTS_SETTING_TEXT_'.$string.'_GROUP',self::PLUGIN ), array(' '), false);

        $this->create_help_messages($string,$idgroup);
        $this->create_form_restrictions($idcheck,$idxp);
        $this->set_default_values($idcheck,$idxp);

    }

    private function create_help_messages($string,$idgroup) {

        $mform = $this->_form;

        $mform->addHelpButton($idgroup,
            'EVENTS_SETTING_HELP_'.$string.'_GROUP', self::PLUGIN);

    }

    private function create_form_restrictions($idcheck,$idxp) {

        $mform = $this->_form;
        $errormsg = get_string('integer', self::PLUGIN);

        $key = $idxp;
        $mform->setType($key, PARAM_INT);
        // $mform->addRule($key, $errormsg, 'nonzero', null, 'client');
        // $mform->addRule($key, $errormsg, 'regex', self::XP_REGEX, 'client');
        $mform->disabledIf($key, $idcheck, 'notchecked');

    }

    private function set_default_values($idcheck,$idxp) {

        $mform = $this->_form;

        $key = $idcheck;
        $mform->setDefault($key, get_config(self::PLUGIN, $key));

        $key = $idxp;
        $mform->setDefault($key, get_config(self::PLUGIN, $key));

    }

    /**
     * Este método realiza del lado del servidor las mismas validaciones que
     * del lado del cliente, hasta el momento no se requiere una validacion
     * extra del lado del servidor
     */
    public function validation($data, $files) {
        $errors = array();
        $xp_incorrect = get_string('integer', self::PLUGIN);

        // TODO Validate self::COMPETENCECPU & parent::VALIDATE(SESSKEY)i

        if ($data[self::COMPETENCECPU]) {

            $key = self::COMPETENCECPUXP;
            if ( !isset($data[$key]) ) {
                $errors[$key] = get_config('required');

            } else if (
            !preg_match( self::XP_REGEX, $data[$key]) || $data[$key] <= 0 ) {
                $errors[$key] = $xp_incorrect;
            }
        }

        // TODO PASS TO PARENT
        $this->has_error = false;
        if (!empty($errors)) {
            $this->has_error = true;
        }
        return $errors;
    }

    /**
     * Este método actualiza las configuraciones de la
     * base de datos con los valores enviados en el formulacion
     *
     * El objeto (stdClass) de entrada se convierte en un arreglo
     * donde cada clave es el identificador de la configuracion
     * del plugin y el valor de dicha clave es el nuevo valor de
     * la configuracion
     */
    public function submit_changes(stdClass $changes) {

        $keys = get_object_vars($changes);
        unset($keys['submitbutton']);

        if($keys[self::COMPETENCECPU] == 0) {
            unset($keys[self::COMPETENCECPUXP]);
        }

        if($keys[self::COMPETENCEVS] == 0) {
            unset($keys[self::COMPETENCEVSXP]);
        }

        if($keys[self::PREGUNTADIARIA] == 0) {
            unset($keys[self::PREGUNTADIARIAXP]);
        }

        foreach ($keys as $key => $value) {
            set_config($key, $value, self::PLUGIN);
        }
    }

}

?>
