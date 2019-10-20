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
class block_gmxp_visualsettingsform extends moodleform {

    const PLUGIN = 'block_gmxp';
    protected function definition() {

        $mform = $this->_form; // Inherited from moodle form
        $mform->setDisableShortforms(true); // TODO CHECK WHY?

        $this->create_definition();
        $this->create_help_messages();
        $this->create_form_restrictions();

        $this->set_default_values();
        $this->add_action_buttons();
    }

    private function create_definition() {

        $mform = $this->_form;

        $mform->addElement('text',
            get_string('SYS_SETTINGS_VISUAL_TITLE', self::PLUGIN),
            get_string('VISUAL_SETTING_TEXT_TITLE', self::PLUGIN));

        $mform->addElement('textarea',
            get_string('SYS_SETTINGS_VISUAL_DESCRIPTION', self::PLUGIN),
            get_string('VISUAL_SETTING_TEXT_DESCRIPTION', self::PLUGIN)
        );

        $mform->addElement('text',
            get_string('SYS_SETTINGS_VISUAL_MESSAGE', self::PLUGIN),
            get_string('VISUAL_SETTING_TEXT_MESSAGE', self::PLUGIN)
        );

        $mform->addElement('customcert_colourpicker',
            get_string('SYS_SETTINGS_VISUAL_COLORLVL', self::PLUGIN),
            get_string('VISUAL_SETTING_TEXT_COLORLVL', self::PLUGIN)
        );

        $mform->addElement('customcert_colourpicker',
            get_string('SYS_SETTINGS_VISUAL_COLORBAR', self::PLUGIN),
            get_string('VISUAL_SETTING_TEXT_COLORBAR', self::PLUGIN)
        );

        $mform->addElement('filepicker',
            get_string('SYS_SETTINGS_VISUAL_IMAGE', self::PLUGIN),
            get_string('VISUAL_SETTING_TEXT_IMAGE', self::PLUGIN)
        );

    }

    private function create_help_messages() {

        $mform = $this->_form;
    }

    private function create_form_restrictions() {

        $mform = $this->_form;

        $key = get_string('SYS_SETTINGS_VISUAL_TITLE', self::PLUGIN);
        $mform->setType($key, PARAM_TEXT);
        $mform->addRule($key, get_string('required'), 'required', null, 'client');
        $mform->addRule($key, get_string('maxlength', self::PLUGIN, 30),
            'maxlength', 30, 'client');

        $key = get_string('SYS_SETTINGS_VISUAL_DESCRIPTION', self::PLUGIN);
        $mform->setType($key, PARAM_TEXT);

        $key = get_string('SYS_SETTINGS_VISUAL_MESSAGE', self::PLUGIN);
        $mform->setType($key, PARAM_TEXT);

        $key = get_string('SYS_SETTINGS_VISUAL_COLORLVL', self::PLUGIN);
        $mform->setType($key, PARAM_TEXT);

        $key = get_string('SYS_SETTINGS_VISUAL_COLORBAR', self::PLUGIN);
        $mform->setType($key, PARAM_TEXT);

        $key = get_string('SYS_SETTINGS_VISUAL_IMAGE', self::PLUGIN);
        // $mform->setType($key, PARAM_TEXT);
    }

    private function set_default_values() {

        $mform = $this->_form;

        $key = get_string('SYS_SETTINGS_VISUAL_TITLE', self::PLUGIN);
        $mform->setDefault($key, get_config(self::PLUGIN, $key));

        $key = get_string('SYS_SETTINGS_VISUAL_DESCRIPTION', self::PLUGIN);
        $mform->setDefault($key, get_config(self::PLUGIN, $key));

        $key = get_string('SYS_SETTINGS_VISUAL_MESSAGE', self::PLUGIN);
        $mform->setDefault($key, get_config(self::PLUGIN, $key));

        $key = get_string('SYS_SETTINGS_VISUAL_COLORLVL', self::PLUGIN);
        $mform->setDefault($key, get_config(self::PLUGIN, $key));

        $key = get_string('SYS_SETTINGS_VISUAL_COLORBAR', self::PLUGIN);
        $mform->setDefault($key, get_config(self::PLUGIN, $key));
    }

    public function validation($data, $files) {
        return array();
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
    public function submitChanges(stdClass $changes) {

        // Obtenemos el arreglo y removemos las entradas que no
        // representan una configuración del plugin.
        $keys = get_object_vars($changes);
        unset($keys['submitbutton']);

        foreach ($keys as $key => $value) {
            local_gamedlemaster_log::info(
              "set_config('{$key}', {$value}, PLUGIN",'GMXP Visual Settings');

            //set_config($key, $value, self::PLUGIN);
        }
    }
}

?>
