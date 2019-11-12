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

defined('MOODLE_INTERNAL') || die();

class block_gmcs_observer {

    const PLUGIN = 'block_gmcs';
    const DEFEAT_SYSTEM  = block_gmcs_core::DEFEAT_SYSTEM;
    const DEFEAT_USER    = block_gmcs_core::DEFEAT_USER;
    const ANSWER_QUESTION    = block_gmcs_core::ANSWER_QUESTION;

    const DEFEAT_SYSTEM_ENABLED  = block_gmcs_core::DEFEAT_SYSTEM_ENABLED;
    const DEFEAT_USER_ENABLED    = block_gmcs_core::DEFEAT_USER_ENABLED;
    const ANSWER_QUESTION_ENABLED    = block_gmcs_core::ANSWER_QUESTION_ENABLED;

    public static function competencecpu_won(
        \local_gamedlemaster\event\gmcompcpu_compFinishedWon $event) {

        $key = self::DEFEAT_SYSTEM_ENABLED;
        $enabled = get_config(self::PLUGIN, $key);

        if($enabled){

            global $DB;

            $data = $event->get_data();
            foreach ($data as $key => $datum){
                if($key == 'other'){
                    $userid = $datum['userid'];
                    $dificultad = $datum['dificultad'];
                }
            }

            $key = self::DEFEAT_SYSTEM;
            $monedas = get_config(self::PLUGIN, $key);

            $usuariogamificado = $DB->get_record('gmdl_usuario',array('id' => $userid));

            $usuariogamificado->monedas_plata += $monedas;

            $DB->update_record('gmdl_usuario',$usuariogamificado);

            $userid = $usuariogamificado->mdl_id_usuario;
            $userto = $DB->get_record('user', array('id' => $userid));

            $info = new stdClass();
            $info->monedas = $monedas;
            $info->typecomp = "competencia vs cpu";
            $info->nombre = $userto->firstname;
            $info->razon = "ganar";

            block_gmcs_messenger::notifica($userto,$info);

            local_gamedlemaster_log::info(
                'Event triggered', 'block_gmcs_observer::competence_won');

        }


    }

    public static function competencevs_won(
        \local_gamedlemaster\event\gmcompvs_compFinishedWon $event) {

        $key = self::DEFEAT_USER_ENABLED;
        $enabled = get_config(self::PLUGIN, $key);

        global $DB;

        $data = $event->get_data();
        foreach ($data as $key => $datum){
            if($key == 'other'){
                $userid = $datum['userid'];
                $monedasapostadas = $datum['monedas'];
            }
        }

        $monedas = 0;

        if($enabled){
            $key = self::DEFEAT_USER;
            $monedas = get_config(self::PLUGIN, $key);
        }

        $usuariogamificado = $DB->get_record('gmdl_usuario',array('id' => $userid));

        $usuariogamificado->monedas_plata += ($monedas+$monedasapostadas);

        $DB->update_record('gmdl_usuario',$usuariogamificado);

        $userid = $usuariogamificado->mdl_id_usuario;
        $userto = $DB->get_record('user', array('id' => $userid));

        $info = new stdClass();
        $info->monedas = $monedas+$monedasapostadas;
        $info->typecomp = "competencia 1 vs 1";
        $info->nombre = $userto->firstname;
        $info->razon = "ganar";

        block_gmcs_messenger::notifica($userto,$info);

        local_gamedlemaster_log::info(
            'Event triggered', 'block_gmcs_observer::competencevs_won');

    }

    public static function competencevsreturn_won(
        \local_gamedlemaster\event\gmcompvs_compFinishedReturnCon $event) {

        global $DB;

        $data = $event->get_data();
        foreach ($data as $key => $datum){
            if($key == 'other'){
                $userid = $datum['userid'];
                $monedasapostadas = $datum['monedas'];
                $rason = $datum['rason'];
            }
        }

        $usuariogamificado = $DB->get_record('gmdl_usuario',array('id' => $userid));

        $usuariogamificado->monedas_plata += ($monedasapostadas);

        $DB->update_record('gmdl_usuario',$usuariogamificado);

        $userid = $usuariogamificado->mdl_id_usuario;
        $userto = $DB->get_record('user', array('id' => $userid));

        $info = new stdClass();
        $info->monedas = $monedasapostadas;
        $info->typecomp = "competencia 1 vs 1";
        $info->nombre = $userto->firstname;
        $info->razon = $rason;

        block_gmcs_messenger::notifica($userto,$info);

        local_gamedlemaster_log::info(
            'Event triggered', 'block_gmcs_observer::competencevsreturn_won');
    }

    public static function daily_question_answered(
        \local_gamedlemaster\event\gmpregdiarias_pregCorrecta $event) {

        $key = self::ANSWER_QUESTION_ENABLED;
        $enabled = get_config(self::PLUGIN, $key);

        if($enabled){

            global $DB;

            $data = $event->get_data();
            foreach ($data as $key => $datum){
                if($key == 'other'){
                    $userid = $datum['userid'];
                }
            }

            $key = self::ANSWER_QUESTION;
            $monedas = get_config(self::PLUGIN, $key);

            $usuariogamificado = $DB->get_record('gmdl_usuario',array('id' => $userid));

            $usuariogamificado->monedas_plata += $monedas;

            $DB->update_record('gmdl_usuario',$usuariogamificado);

            $userid = $usuariogamificado->mdl_id_usuario;
            $userto = $DB->get_record('user', array('id' => $userid));

            $info = new stdClass();
            $info->monedas = $monedas;
            $info->typecomp = "pregunta diaria";
            $info->nombre = $userto->firstname;
            $info->razon = "contestar correctamente";

            block_gmcs_messenger::notifica($userto,$info);

            local_gamedlemaster_log::info(
                'Event triggered', 'block_gmcs_observer::daily_question_answered');

        }


    }
}

