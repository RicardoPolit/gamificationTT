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
    const CURRENCY_MAX_REGEX_LENGTH = 10;
    const CURRENCY_REGEX = '/^[1-9][0-9]*$/';

    protected function definition() {

        $mform = $this->_form; // Inherited from moodle form

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
        // TODO The default value must be get from the database
        // configurations
    }

    /**
     * Este método realiza del lado del servidor las mismas validaciones que
     * del lado del cliente, hasta el momento no se requiere una validacion
     * extra del lado del servidor
     */
    public function validation($data, $files) {
        $errors = array();
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
        local_gamedlemaster_log::info($changes);
    }

}

?>
