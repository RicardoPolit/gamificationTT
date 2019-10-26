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


MoodleQuickForm::registerElementType('customcert_colourpicker',
    $CFG->dirroot . '/blocks/gmxp/includes/colourpicker.php',
    'MoodleQuickForm_customcert_colourpicker');

/**
 * 
 * Docs: https://docs.moodle.org/dev/lib/formslib.php_Form_Definition
 */
class block_gmxp_schemesettingsform extends moodleform {

    const PLUGIN = 'block_gmxp';
    const LINEAL = '0';
    const PERCENTUAL = '1';

    protected function definition() {

        $mform = $this->_form; // Inherited from moodle form
        // $mform->setDisableShortforms(true); // TODO CHECK WHY?

        $this->create_definition();
        // $this->create_help_messages();
        $this->create_form_restrictions();

        $this->set_default_values();
        $this->add_action_buttons();
    }

    private function create_definition() {

        $mform = $this->_form;

        $mform->addElement('select',
            get_string('SYS_SETTINGS_SCHEME_INCREMENT', self::PLUGIN),
            get_string('SCHEME_SETTING_TEXT_INCREMENT', self::PLUGIN),
            array(
                self::LINEAL => get_string('SCHEME_SETTING_TEXT_LINEAL', self::PLUGIN),
                self::PERCENTUAL => get_string('SCHEME_SETTING_TEXT_PERCENTUAL', self::PLUGIN)
            ));

        $mform->addElement('text',
            get_string('SYS_SETTINGS_SCHEME_VALUE_L', self::PLUGIN),
            get_string('SCHEME_SETTING_TEXT_LINEAL', self::PLUGIN));

        $mform->addElement('text',
            get_string('SYS_SETTINGS_SCHEME_VALUE_P', self::PLUGIN),
            get_string('SCHEME_SETTING_TEXT_PERCENTUAL', self::PLUGIN));
        
        $mform->addElement('text',
            get_string('SYS_SETTINGS_SCHEME_LEVELXP', self::PLUGIN),
            get_string('SCHEME_SETTING_TEXT_LEVELXP', self::PLUGIN));

        $mform->addElement('text',
            get_string('SYS_SETTINGS_SCHEME_COURSEXP', self::PLUGIN),
            get_string('SCHEME_SETTING_TEXT_COURSEXP', self::PLUGIN));
    }

    private function create_help_messages() {

        $mform = $this->_form;

        $mform->addHelpButton(
            get_string('SYS_SETTINGS_VISUAL_TITLE', self::PLUGIN),
            'VISUAL_SETTING_HELP_TITLE', self::PLUGIN);
    }

    private function create_form_restrictions() {

        $mform = $this->_form;

        $select = get_string('SYS_SETTINGS_SCHEME_INCREMENT', self::PLUGIN);

        $key = get_string('SYS_SETTINGS_SCHEME_VALUE_L', self::PLUGIN);
        $mform->setType($key, PARAM_INT);
        $mform->disabledIf($key, $select, 'eq', self::PERCENTUAL);

        $key = get_string('SYS_SETTINGS_SCHEME_VALUE_P', self::PLUGIN);
        $mform->setType($key, PARAM_INT);
        $mform->disabledIf($key, $select, 'eq', self::LINEAL);

        $key = get_string('SYS_SETTINGS_SCHEME_LEVELXP', self::PLUGIN);
        $mform->setType($key, PARAM_INT);
        $key = get_string('SYS_SETTINGS_SCHEME_COURSEXP', self::PLUGIN);
        $mform->setType($key, PARAM_INT);
        /*
        $key = get_string('SYS_SETTINGS_VISUAL_TITLE', self::PLUGIN);
        $mform->setType($key, PARAM_TEXT);
        $mform->addRule($key, get_string('required'), 'required', null, 'client');
        $mform->addRule($key, get_string('maxlength', self::PLUGIN,
            self::TITLE_MAX_LENGTH), 'maxlength', self::TITLE_MAX_LENGTH, 'client');

        $key = get_string('SYS_SETTINGS_VISUAL_DESCRIPTION', self::PLUGIN);
        $mform->setType($key, PARAM_TEXT);
        $mform->addRule($key, get_string('required'), 'required', null, 'client');
        $mform->addRule($key, get_string('maxlength', self::PLUGIN,
            self::DESC_MAX_LENGTH), 'maxlength', self::DESC_MAX_LENGTH, 'client');

        $key = get_string('SYS_SETTINGS_VISUAL_MESSAGE', self::PLUGIN);
        $mform->setType($key, PARAM_TEXT);
        $mform->addRule($key, get_string('required'), 'required', null, 'client');
        $mform->addRule($key, get_string('maxlength', self::PLUGIN,
            self::MSG_MAX_LENGTH), 'maxlength', self::MSG_MAX_LENGTH, 'client');

        // Message of error in color field
        $colorErr = get_string('color', self::PLUGIN);

        $key = get_string('SYS_SETTINGS_VISUAL_COLORLVL', self::PLUGIN);
        $mform->setType($key, PARAM_TEXT);
        $mform->addRule($key, get_string('required'), 'required', null, 'client');
        $mform->addRule($key, $colorErr, 'regex', self::COLOR_REGEX, 'client');

        $key = get_string('SYS_SETTINGS_VISUAL_COLORBAR', self::PLUGIN);
        $mform->setType($key, PARAM_TEXT);
        $mform->addRule($key, get_string('required'), 'required', null, 'client');
        $mform->addRule($key, $colorErr, 'regex', self::COLOR_REGEX, 'client');

        $key = get_string('SYS_SETTINGS_VISUAL_IMAGE', self::PLUGIN);
        // $mform->setType($key, PARAM_TEXT);
        */
    }

    private function set_default_values() {

        $mform = $this->_form;

        $key = get_string('SYS_SETTINGS_SCHEME_INCREMENT', self::PLUGIN);
        $mform->setDefault($key, get_config(self::PLUGIN, $key));
        $increment = get_config(self::PLUGIN, $key);

        $key = get_string('SYS_SETTINGS_SCHEME_VALUE', self::PLUGIN);
        $value = get_config(self::PLUGIN, $key);

        if ($increment == self::LINEAL) {
            $lineal = $value;
            $percentual = 0.0;

        } else { // self::PERCENTUAL
            $lineal = 0;
            $percentual = $value;
        }

        $key = get_string('SYS_SETTINGS_SCHEME_VALUE_L', self::PLUGIN);
        $mform->setDefault($key, $lineal);

        $key = get_string('SYS_SETTINGS_SCHEME_VALUE_P', self::PLUGIN);
        $mform->setDefault($key, $percentual);

        $key = get_string('SYS_SETTINGS_SCHEME_LEVELXP', self::PLUGIN);
        $mform->setDefault($key, get_config(self::PLUGIN, $key));

        $key = get_string('SYS_SETTINGS_SCHEME_COURSEXP', self::PLUGIN);
        $mform->setDefault($key, get_config(self::PLUGIN, $key));
    }

    /**
     * Este método realiza del lado del servidor las mismas validaciones que
     * del lado del cliente, hasta el momento no se requiere una validacion
     * extra del lado del servidor
     */
    public function validation($data, $files) {
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
    }

}

?>
