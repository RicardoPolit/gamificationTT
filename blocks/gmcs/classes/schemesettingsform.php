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
class block_gmcs_schemesettingsform extends local_gamedlemaster_form {

    const PLUGIN = 'block_gmcs';
    const SILVER_TO_GOLD = block_gmcs_core::SILVER_TO_GOLD;

    const DEFEAT_SYSTEM  = block_gmcs_core::DEFEAT_SYSTEM;
    const DEFEAT_USER    = block_gmcs_core::DEFEAT_USER;
    const ANSWER_QUESTION    = block_gmcs_core::ANSWER_QUESTION;

    const DEFEAT_SYSTEM_ENABLED  = block_gmcs_core::DEFEAT_SYSTEM_ENABLED;
    const DEFEAT_USER_ENABLED    = block_gmcs_core::DEFEAT_USER_ENABLED;
    const ANSWER_QUESTION_ENABLED    = block_gmcs_core::ANSWER_QUESTION_ENABLED;

    const DEFEAT_SYSTEM_GROUP  = block_gmcs_core::DEFEAT_SYSTEM_GROUP;
    const DEFEAT_USER_GROUP    = block_gmcs_core::DEFEAT_USER_GROUP;
    const ANSWER_QUESTION_GROUP    = block_gmcs_core::ANSWER_QUESTION_GROUP;

    const CURRENCY_MAX_REGEX_LENGTH = 10;
    const CURRENCY_REGEX = '/^[1-9][0-9]*$/';

    protected function definition() {

        $this->set_title(get_string('SETTINGS_SCHEME', self::PLUGIN));
        $this->set_subtitle(get_string('SETTINGS_SCHEME_HEADER', self::PLUGIN));
        $this->set_description(get_string('SETTINGS_SCHEME_DESC', self::PLUGIN));

        $this->create_definition();
        $this->create_help_messages();
        $this->create_form_restrictions();

        $this->set_default_values();
        $this->add_action_buttons();
    }

    private function create_definition() {

        $mform = $this->_form;

        $mform->addElement('text', self::SILVER_TO_GOLD,
            get_string('SCHEME_SETTING_TEXT_SILVER', self::PLUGIN), array(
            'size' => self::CURRENCY_MAX_REGEX_LENGTH,
            'maxlength' => self::CURRENCY_MAX_REGEX_LENGTH
        ));

        $this->create_group('WIN_USER',self::DEFEAT_USER_ENABLED,self::DEFEAT_USER,self::DEFEAT_USER_GROUP);
        $this->create_group('WIN_SYSTEM',self::DEFEAT_SYSTEM_ENABLED,self::DEFEAT_SYSTEM,self::DEFEAT_SYSTEM_GROUP);
        $this->create_group('QUESTION',self::ANSWER_QUESTION_ENABLED,self::ANSWER_QUESTION,self::ANSWER_QUESTION_GROUP);
    }

    private function create_group($string,$idcheck,$idcr,$idgroup){

        $mform =& $this->_form;
        $group1 = array();
        $group1[] =& $mform->createElement('advcheckbox', $idcheck,
            get_string('SCHEME_SETTING_TEXT_CHECKBOX_ENABLED', self::PLUGIN),
            get_string('SCHEME_SETTING_TEXT_CR_SUPPORT', self::PLUGIN),
            array('group' => 1), array(0, 1));
        $group1[] =& $mform->createElement('text', $idcr,
            get_string('SCHEME_SETTING_TEXT_CR_SUPPORT', self::PLUGIN), array(
                'size' => self::CURRENCY_MAX_REGEX_LENGTH,
                'maxlength' => self::CURRENCY_MAX_REGEX_LENGTH
            ));
        $mform->addGroup($group1, $idgroup,
            get_string('SCHEME_SETTING_TEXT_'.$string.'_GROUP',self::PLUGIN ), array(' '), false);

        $this->create_help_messages_group($string,$idgroup);
        $this->create_form_restrictions_group($idcheck,$idcr);
        $this->set_default_values_group($idcheck,$idcr);

    }

    private function create_help_messages_group($string,$idgroup) {

        $mform = $this->_form;

        $mform->addHelpButton($idgroup,
            'SCHEME_SETTING_HELP_'.$string.'_GROUP', self::PLUGIN);

    }

    private function create_form_restrictions_group($idcheck,$idcr) {
        $mform = $this->_form;
        $key = $idcr;
        $mform->setType($key, PARAM_TEXT);
        /*$mform->addRule($key, get_string('currency', self::PLUGIN),
            'regex', self::CURRENCY_REGEX, 'client');*/
        $mform->disabledIf($key, $idcheck, 'notchecked');

    }

    private function set_default_values_group($idcheck,$idcr) {
        $mform = $this->_form;

        $key = $idcheck;
        $mform->setDefault($key, get_config(self::PLUGIN, $key));

        $key = $idcr;
        $mform->setDefault($key, get_config(self::PLUGIN, $key));

    }

    private function create_help_messages() {
        $mform = $this->_form;

        $mform->addHelpButton(self::SILVER_TO_GOLD,
            'SCHEME_SETTING_HELP_SILVER', self::PLUGIN);

    }

    private function create_form_restrictions() {
        $mform = $this->_form;

        $key = self::SILVER_TO_GOLD;
        $mform->setType($key, PARAM_TEXT);
        $mform->addRule($key, get_string('required'), 'required', null, 'client');
        $mform->addRule($key, get_string('currency', self::PLUGIN),
            'regex', self::CURRENCY_REGEX, 'client');

    }

    private function set_default_values() {
        $mform = $this->_form;

        $key = self::SILVER_TO_GOLD;
        $mform->setDefault($key, get_config(self::PLUGIN, $key));

    }

    /**
     * Este método realiza del lado del servidor las mismas validaciones que
     * del lado del cliente, hasta el momento no se requiere una validacion
     * extra del lado del servidor
     */
    public function validation($data, $files) {
        $errors = array();

        $key = self::SILVER_TO_GOLD;
        if( !isset($data[$key]) || empty($data[$key]) ) {
            $errors[$key] = get_string('required');

        } else if (!preg_match( self::CURRENCY_REGEX, $data[$key]) ||
          $data[$key] <= 0) {
            $errors[$key] = get_string('currency', self::PLUGIN);
        }

        $key = self::DEFEAT_SYSTEM;
        if( !isset($data[$key]) || empty($data[$key]) ) {
            $errors[$key] = get_string('required');

        } else if (!preg_match( self::CURRENCY_REGEX, $data[$key]) ||
          $data[$key] <= 0) {
            $errors[$key] = get_string('currency', self::PLUGIN);
        }

        $key = self::DEFEAT_USER;
        if( !isset($data[$key]) || empty($data[$key]) ) {
            $errors[$key] = get_string('required');

        } else if (!preg_match( self::CURRENCY_REGEX, $data[$key]) ||
          $data[$key] <= 0) {
            $errors[$key] = get_string('currency', self::PLUGIN);
        }

        $key = self::ANSWER_QUESTION;
        if( !isset($data[$key]) || empty($data[$key]) ) {
            $errors[$key] = get_string('required');

        } else if (!preg_match( self::CURRENCY_REGEX, $data[$key]) ||
          $data[$key] <= 0) {
            $errors[$key] = get_string('currency', self::PLUGIN);
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

        if($keys[self::DEFEAT_SYSTEM_ENABLED] == 0) {
            unset($keys[self::DEFEAT_SYSTEM]);
        }

        if($keys[self::DEFEAT_USER_ENABLED] == 0) {
            unset($keys[self::DEFEAT_USER]);
        }

        if($keys[self::ANSWER_QUESTION_ENABLED] == 0) {
            unset($keys[self::ANSWER_QUESTION]);
        }

        foreach ($keys as $key => $value) {
            set_config($key, $value, self::PLUGIN);
        }
    }

}

?>
