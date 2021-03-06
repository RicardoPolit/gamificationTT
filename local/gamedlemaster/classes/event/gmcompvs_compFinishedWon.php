<?php


namespace local_gamedlemaster\event;

class gmcompvs_compFinishedWon extends \core\event\base {

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
                $monedas = $datum['monedas'];
            }
        }
        return "Evento ganó el usuario: $userid una competencia 1vs1, se le debe de dar experiencia y las monedas: $monedas";
    }

}