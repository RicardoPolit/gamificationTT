<?php


namespace local_gamedlemaster\event;

class gmcompcpu_compFinishedWon extends \core\event\base {

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
                $dificultad = $datum['dificultad'];
            }
        }
        return "The user with id '$userid' got a new high score with dificulty $dificultad";
    }

}