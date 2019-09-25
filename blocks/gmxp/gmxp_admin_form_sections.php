<?php

require(__DIR__.'/../../config.php');
require_once($CFG->libdir.'/adminlib.php');

admin_externalpage_setup('block_gmxp_ext');


// functionality like processing form submissions goes here

class gmxpadminformsections extends moodleform {

    protected $config;

    protected function definition() {

        $mform = $this->_form;

        $config = isset($this->_customdata['config']) ? $this->_customdata['config'] : null;

        $mform->setDisableShortforms(true);

        $mform->addElement('header', 'hdrgen', get_string('nameSecc', 'block_gmxp'));

        $mform->addElement('static','description',get_string('descSections','block_gmxp'),
            get_string('descDescSections','block_gmxp'));

        $mform->addElement('text', 'sections', get_string('sectionscount', 'block_gmxp'));
        $mform->addRule('sections', get_string('required'), 'required');
        $mform->setType('sections', PARAM_INT);


        $mform->addElement('submit', 'updateandpreview', get_string('createSections', 'block_gmxp'));
        $mform->registerNoSubmitButton('updateandpreview');

        $mform->addElement('header', 'hdrsection1', get_string('sectionx', 'block_gmxp', 1));
        $mform->addElement('text', 'sectlvlS_1', get_string('levelstart', 'block_gmxp'));
        $mform->setType('sectlvlS_1', PARAM_INT);
        $mform->addElement('text', 'sectlvlE_1', get_string('levelend', 'block_gmxp'));
        $mform->setType('sectlvlE_1', PARAM_INT);

        $mform->addelement('hidden', 'insertsectionshere');
        $mform->setType('insertsectionshere', PARAM_BOOL);

        $this->add_action_buttons();

    }

    public function definition_after_data() {
        $mform = $this->_form;

        // Ensure that the values are not wrong, the validation on save will catch those problems.
        $levels = max((int) $mform->exportValue('sections'),1);

        // Add the levels.
        for ($i = 2; $i <= $levels; $i++) {
            $el =& $mform->createElement('header', 'hdrsection' . $i, get_string('sectionx', 'block_gmxp', $i));
            $mform->insertElementBefore($el, 'insertsectionshere');

            $el =& $mform->createElement('text', 'sectlvlS_' . $i, get_string('levelstart', 'block_gmxp'));
            $mform->insertElementBefore($el, 'insertsectionshere');
            $mform->setType('sectlvlS_' . $i, PARAM_INT);

            $el =& $mform->createElement('text', 'sectlvlE_' . $i, get_string('levelend', 'block_gmxp'));
            $mform->insertElementBefore($el, 'insertsectionshere');
            $mform->setType('sectlvlE_' . $i, PARAM_INT);
        }
    }

    public function validation($data, $files) {
        $errors = array();
        if ($data['sections'] < 2) {
            $errors['sections'] = get_string('errorsectionsincorrect', 'block_gmxp');
        }

        /*
        // Validating the XP points.
        if (!isset($errors['sections'])) {
            $lastxp = 0;
            for ($i = 2; $i <= $data['levels']; $i++) {
                $key = 'lvlxp_' . $i;
                $xp = isset($data[$key]) ? (int) $data[$key] : -1;
                if ($xp <= 0) {
                    $errors['lvlxp_' . $i] = get_string('invalidxp', 'block_xp');
                } else if ($lastxp >= $xp) {
                    $errors['lvlxp_' . $i] = get_string('errorxprequiredlowerthanpreviouslevel', 'block_xp');
                }
                $lastxp = $xp;
            }
        }*/

        return $errors;
    }

    public function setSections(){

        $rawdata = get_config('block_gmxp','sections');

        if($rawdata != null){

            $jsondata = json_decode($rawdata);

            $newdata = [
                'sections' => $jsondata->sections
            ];
            for ($i = 1; $i <= $jsondata->sections; $i++) {
                $newdata['sectlvlS_' . $i] = $jsondata->sectlvlS->{$i};
                $newdata['sectlvlE_' . $i] = $jsondata->sectlvlE->{$i};
            }

            $this->set_data($newdata);

        }

    }

}

$pageAux = new gmxpadminformsections();

$pageAux->setSections();

if ($pageAux->is_cancelled()) {

    header("Refresh:0; url=http://127.0.1.1/moodle/admin/category.php?category=block_gmxp_category");

} else if ($data = $pageAux->get_data()) {


    $newdata = [
        'sections' => $data->sections,
        'sectlvlS' => [
            '1' => $data->{'sectlvlS_1'}
        ],
        'sectlvlE' => [
            '1' => $data->{'sectlvlE_1'}
        ]
    ];

    for ($i = 2; $i <= $data->sections; $i++) {

        $newdata['sectlvlS'][$i] = $data->{'sectlvlS_' . $i};
        $newdata['sectlvlE'][$i] = $data->{'sectlvlE_' . $i};

    }

        set_config('sections',json_encode($newdata),'block_gmxp');



} else {

        //se procesa si la información no es validada

}

$output = $pageAux->render();



echo $OUTPUT->header();
echo $output;
echo $OUTPUT->footer();
