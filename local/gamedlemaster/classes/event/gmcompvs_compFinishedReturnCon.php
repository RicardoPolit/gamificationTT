<?php

//Evento especial que se trigerea cuando la bandera de apuestas se apagó y se le tienen que devolver a los usuarios sus monedas correspondientes.

namespace local_gamedlemaster\event;

class gmcompvs_compFinishedReturnCon extends \core\event\base {

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
                $rason = $datum['rason'];
            }
        }
        return "Evento para devolver al usuario gamificado: $userid el dinero apostado: $monedas, la razón es: $rason";
    }

}