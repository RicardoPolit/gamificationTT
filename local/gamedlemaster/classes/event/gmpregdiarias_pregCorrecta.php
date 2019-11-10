<?php


namespace local_gamedlemaster\event;

class gmpregdiarias_pregCorrecta extends \core\event\base {

    protected function init() {
        $this->data['objecttable'] = 'gamedlemaster';
        $this->data['crud'] = 'r';
        $this->data['edulevel'] = self::LEVEL_PARTICIPATING;
    }

    public function get_description() {
        $data = $this->get_data();
        foreach ($data as $key => $datum){
            if($key == 'other'){
                $userid = $datum['userid'];
            }
        }
        return "The user with id '$userid' answered correctly the question of today ";
    }

}