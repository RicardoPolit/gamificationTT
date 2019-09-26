<?php

require(__DIR__.'/../../config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->dirroot . '/blocks/gmxp/includes/colourpicker.php');

\MoodleQuickForm::registerElementType('customcert_colourpicker',
    $CFG->dirroot . '/blocks/gmxp/includes/colourpicker.php', 'MoodleQuickForm_customcert_colourpicker');

admin_externalpage_setup('block_gmxp_ext_general');

$context = context_system::instance();
// functionality like processing form submissions goes here

class gmxpadminfromgeneral extends moodleform {

    protected $config;

    protected function definition() {

        $mform = $this->_form;

        $config = isset($this->_customdata['config']) ? $this->_customdata['config'] : null;

        $mform->setDisableShortforms(true);

        $mform->addElement('header', 'headerconfigLevels', get_string('headerconfigLevels', 'block_gmxp'));

        $mform->addElement('static','description',get_string('descGeneral','block_gmxp'),
            get_string('descconfigLevels','block_gmxp'));

        $mform->addElement('advcheckbox', 'isActive', get_string('checkboxisActive', 'block_gmxp'),
            get_string('checkboxisActivedesc', 'block_gmxp'), array('group' => 1), array(0, 1));

        $options = array(
            '0' => get_string('typeOfIncrementB','block_gmxp'),
            '1' => get_string('typeOfIncrementA','block_gmxp')
        );
        $select = $mform->addElement('select', 'typeOfIncrement' , get_string('typeOfIncrement','block_gmxp'), $options);

        $select->setSelected('1');

        $mform->addElement('text', 'numValorIncrement', get_string('titleIncre', 'block_gmxp'));
        $mform->addRule('numValorIncrement', get_string('required'), 'required');
        $mform->addHelpButton('numValorIncrement', 'descIncre' , 'block_gmxp');
        $mform->setDefault('numValorIncrement',get_string('valueNumIncrementDefault','block_gmxp'));

        $mform->addElement('text', 'firstExpRequiried', get_string('titleFirstExpRequired', 'block_gmxp'));
        $mform->addRule('firstExpRequiried', get_string('required'), 'required');
        $mform->addHelpButton('firstExpRequiried', 'descFirstExpRequired' , 'block_gmxp');
        $mform->setDefault('firstExpRequiried','10');

        $mform->addElement('text', 'firstExpGiven', get_string('titleExpGiven', 'block_gmxp'));
        $mform->addRule('firstExpGiven', get_string('required'), 'required');
        $mform->addHelpButton('firstExpGiven', 'descExpGiven' , 'block_gmxp');
        $mform->setDefault('firstExpGiven','100');

        $mform->addElement('header', 'headerconfigLevelsFormat', get_string('headerconfigLevelsFormat', 'block_gmxp'));

        $mform->addElement('static','description',get_string('descGeneral','block_gmxp'),
            get_string('headerconfigLevelsFormatDesc','block_gmxp'));

        $mform->addElement('text', 'defaultLevelsName', get_string('titleDefName', 'block_gmxp'));
        $mform->addRule('defaultLevelsName', get_string('required'), 'required');
        $mform->addHelpButton('defaultLevelsName', 'descDefName' , 'block_gmxp');
        $mform->setDefault('defaultLevelsName',get_string('defaultDefName','block_gmxp'));

        $mform->addElement('text', 'defaultLevelsMessage', get_string('titleDefMessage', 'block_gmxp'));
        $mform->addRule('defaultLevelsMessage', get_string('required'), 'required');
        $mform->addHelpButton('defaultLevelsMessage', 'descDefMessage' , 'block_gmxp');
        $mform->setDefault('defaultLevelsMessage',get_string('defaultDefMessage','block_gmxp'));

        $mform->addElement('textarea', 'defaultLevelsDescription', get_string('titleDefDescription', 'block_gmxp'), 'wrap="virtual" rows="5" cols="40"');
        $mform->addRule('defaultLevelsDescription', get_string('required'), 'required');
        $mform->addHelpButton('defaultLevelsDescription', 'descDefDescription' , 'block_gmxp');
        $mform->setDefault('defaultLevelsDescription',get_string('defaultDefDescription','block_gmxp'));

        $mform->addElement('customcert_colourpicker', 'defaultColorPickerLevels', get_string('defColorPick', 'block_gmxp'));
        $mform->setType('defaultColorPickerLevels', PARAM_RAW); // Need to validate that this is a valid colour.
        $mform->setDefault('defaultColorPickerLevels', '#0000FF');
        $mform->addRule('defaultColorPickerLevels', get_string('required'), 'required');
        $mform->addHelpButton('defaultColorPickerLevels', 'defColorPickdesc', 'block_gmxp');

        $mform->addElement('customcert_colourpicker', 'defaultColorPickerProgressBar', get_string('defColorPickProgressBar', 'block_gmxp'));
        $mform->setType('defaultColorPickerProgressBar', PARAM_RAW); // Need to validate that this is a valid colour.
        $mform->setDefault('defaultColorPickerProgressBar', '#0000FF');
        $mform->addRule('defaultColorPickerProgressBar', get_string('required'), 'required');
        $mform->addHelpButton('defaultColorPickerProgressBar', 'defColorPickdescProgressBar', 'block_gmxp');

        $mform->addElement('filemanager', 'imageDefecto', get_string('defaultLevelsImage', 'block_gmxp'), null,
            array('subdirs' => 0, 'maxbytes' => 1000000, 'areamaxbytes' => 10485760, 'maxfiles' => 1,
                'accepted_types' => array('.jpg','.png'), 'return_types'=> FILE_INTERNAL | FILE_EXTERNAL));
        $mform->addRule('imageDefecto', get_string('required'), 'required');
        $mform->addHelpButton('imageDefecto', 'defaultLevelsImageDesc', 'block_gmxp');

        $this->add_action_buttons();

    }

    function validateTwoDecimals($number)
    {
        if(preg_match('/^[0-9]+\.[0-9]$/', $number))
            return true;
        else
            return false;
    }

    function validateInteger($number)
    {
        if(preg_match('/^[0-9]+$/', $number))
            return true;
        else
            return false;
    }

    function validateHexaColor($color){

        if(preg_match('/^#[A-Fa-f0-9]{6}$/', $color))
            return true;
        else
            return false;
    }

    public function validation($data, $files) {
        $errors = array();

        //Block validating increment value

        if($data['numValorIncrement'] === '1.1 or 1'){

            $errors['numValorIncrement'] = get_string('errorValorIncrement1','block_gmxp');

        }

        if($data['typeOfIncrement']=='0'){

            if(!$this->validateInteger($data['numValorIncrement'])) {

                $errors['numValorIncrement'] = get_string('errorValorIncrement4','block_gmxp');

            }else if( (int)$data['numValorIncrement'] < (int)get_string('valueDownIntLevels','block_gmxp') || (int)$data['numValorIncrement'] > (int)get_string('valueUpIntLevels','block_gmxp') ){

                $errors['numValorIncrement'] = get_string('errorValorIncrement5','block_gmxp');

            }

        }else{

            if(!$this->validateTwoDecimals($data['numValorIncrement'])) {

                $errors['numValorIncrement'] = get_string('errorValorIncrement2','block_gmxp');

            }else if( (float)$data['numValorIncrement'] < (float)get_string('valueDownFloatLevels','block_gmxp') || (float)$data['numValorIncrement'] > (float)get_string('valueUpFloatLevels','block_gmxp') ){

                $errors['numValorIncrement'] = get_string('errorValorIncrement3','block_gmxp');

            }

        }

        //Block validating first level exp

        if(!$this->validateInteger($data['firstExpRequiried'])) {

            $errors['firstExpRequiried'] = get_string('errorFirstLevelExp1','block_gmxp');

        }else if( (int)$data['firstExpRequiried'] < (int)get_string('valueExpRequiredFirstLevelDown','block_gmxp') || (int)$data['firstExpRequiried'] > (int)get_string('valueExpRequiredFirstLevelUp','block_gmxp') ){

            $errors['firstExpRequiried'] = get_string('errorFirstLevelExp2','block_gmxp');

        }

        //Block validating exp per course

        if(!$this->validateInteger($data['firstExpGiven'])) {

            $errors['firstExpGiven'] = get_string('errorExpCourse1','block_gmxp');

        }else if( (int)$data['firstExpGiven'] < (int)get_string('valueExpGivenDown','block_gmxp') || (int)$data['firstExpGiven'] > (int)get_string('valueExpGivenUp','block_gmxp') ){

            $errors['firstExpGiven'] = get_string('errorExpCourse2','block_gmxp');

        }

        //Block validating that the first level exp is less than the exp per course

        if($this->validateInteger($data['firstExpGiven']) && $this->validateInteger($data['firstExpRequiried']) ){

            if((int)$data['firstExpGiven'] <= (int)$data['firstExpRequiried']){

                $errors['firstExpGiven'] = get_string('errorExpCourse3','block_gmxp');
                $errors['firstExpRequiried'] = get_string('errorFirstLevelExp3','block_gmxp');

            }

        }

        //Block validating the default levels name

        if( strlen(utf8_decode($data['defaultLevelsName'])) > (int)get_string('valueUpStringNameLevels','block_gmxp') )
            $errors['defaultLevelsName'] = get_string('errorLevelsName1','block_gmxp');


        //Block validating the default congratulations message

        if( strlen(utf8_decode($data['defaultLevelsMessage'])) > (int)get_string('valueUpStringMessageLevels','block_gmxp') )
            $errors['defaultLevelsMessage'] = get_string('errorLevelsMessage1','block_gmxp');

        //Block validating the default levels description

        if( strlen(utf8_decode($data['defaultLevelsDescription'])) > (int)get_string('valueUpStringDescLevels','block_gmxp') )
            $errors['defaultLevelsDescription'] = get_string('errorLevelsDesc1','block_gmxp');

        //Block validating the color level number

        if(!$this->validateHexaColor($data['defaultColorPickerLevels']))
            $errors['defaultColorPickerLevels'] = get_string('errorColor','block_gmxp');

        //Block validating the color progress bar

        if(!$this->validateHexaColor($data['defaultColorPickerProgressBar']))
            $errors['defaultColorPickerProgressBar'] = get_string('errorColor','block_gmxp');

        return $errors;
    }

    public function setSettings(){

        $entry = new stdClass;

        $draftitemid = file_get_submitted_draft_itemid('imageDefecto');

        file_prepare_draft_area($draftitemid, \context_system::instance()->id, 'block_gmxp', 'level_images_simplehtml', 0,
            array('subdirs' => 0, 'maxbytes' => 1000000, 'areamaxbytes' => 10485760, 'maxfiles' => 1,
                'accepted_types' => array('.jpg','.png'), 'return_types'=> FILE_INTERNAL | FILE_EXTERNAL));

        $entry->imageDefecto = $draftitemid;

        $entry->isActive = get_config('block_gmxp','isActive');

        $entry->typeOfIncrement = get_config('block_gmxp','typeOfIncrement');

        $entry->numValorIncrement = get_config('block_gmxp','numValorIncrement');

        $entry->firstExpRequiried = get_config('block_gmxp','firstExpRequiried');

        $entry->firstExpGiven = get_config('block_gmxp','firstExpGiven');

        $entry->defaultLevelsName = get_config('block_gmxp','defaultLevelsName');

        $entry->defaultLevelsMessage = get_config('block_gmxp','defaultLevelsMessage');

        $entry->defaultLevelsDescription = get_config('block_gmxp','defaultLevelsDescription');

        $entry->defaultColorPickerLevels = get_config('block_gmxp','defaultColorPickerLevels');

        $entry->defaultColorPickerProgressBar = get_config('block_gmxp','defaultColorPickerProgressBar');

        $this->set_data($entry);

    }

}

$pageAux = new gmxpadminfromgeneral();

$entry = $pageAux->setSettings();

$flag = 0;
if ($pageAux->is_cancelled()) {
    $flag = -3;
    header("Refresh:0; url=http://127.0.1.1/moodle/admin/category.php?category=block_gmxp_category");

} else if ($data = $pageAux->get_data()) {

    $flag = 1;

    set_config('isActive',$data->isActive,'block_gmxp');
    set_config('typeOfIncrement',$data->typeOfIncrement,'block_gmxp');
    set_config('numValorIncrement',$data->numValorIncrement,'block_gmxp');
    set_config('firstExpRequiried',$data->firstExpRequiried,'block_gmxp');
    set_config('firstExpGiven',$data->firstExpGiven,'block_gmxp');
    set_config('defaultLevelsName',$data->defaultLevelsName,'block_gmxp');
    set_config('defaultLevelsMessage',$data->defaultLevelsMessage,'block_gmxp');
    set_config('defaultLevelsDescription',$data->defaultLevelsDescription,'block_gmxp');
    set_config('defaultColorPickerLevels',$data->defaultColorPickerLevels,'block_gmxp');
    set_config('defaultColorPickerProgressBar',$data->defaultColorPickerProgressBar,'block_gmxp');

    file_save_draft_area_files($data->imageDefecto, $context->id, 'block_gmxp', 'level_images_simplehtml',0);

    $fs = get_file_storage();

    $files = $fs->get_area_files(
        $context->id,
        'block_gmxp', 'level_images_simplehtml',
        0,
        'itemid, filepath, filename',
        false);

    $file = array_values($files)[0];

    set_config('imageDefecto','/'.$file->get_filename(),'block_gmxp');


} else if($pageAux->is_submitted()&&!$pageAux->is_validated()) {

    $flag = -1;

    //se procesa si la información no es validada

}

$output = $pageAux->render();



echo $OUTPUT->header();

if($flag == -1){

    echo $OUTPUT->notification(get_string('errorTopMessage','block_gmxp'));

}else if ($flag == 1){

    echo $OUTPUT->notification(get_string('successTopMessage','block_gmxp'), 'notifysuccess');

}

echo $output;
echo $OUTPUT->footer();

