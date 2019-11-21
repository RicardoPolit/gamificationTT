<?php
/*
 * UNIVERSITY:  INSTITUTO POLITECNICO NACIONAL (IPN)
 *              ESCUELA SUPERIOR DE COMPUTO (ESCOM)
 *   SUBJECT:     _
 *   PROFESSOR:   _
 *
 * DESARROLLADORES: Daniel Ortega
 *
 * PRACTICE _:  TITULO DE LA PRACTICA
 *               - DESCRIPCION
 *
*/

defined('MOODLE_INTERNAL') || die();




class block_gmxp_observer {

    const PLUGIN = 'block_gmxp';

    const DEFEAT_SYSTEM  = block_gmxp_core::COMPETENCECPUXP;
    const DEFEAT_SYSTEM_ENABLED  = block_gmxp_core::COMPETENCECPU;

    const DEFEAT_USER    = block_gmxp_core::COMPETENCEVSXP;
    const DEFEAT_USER_ENABLED    = block_gmxp_core::COMPETENCEVS;

    const ANSWER_QUESTION    = block_gmxp_core::PREGUNTADIARIAXP;
    const ANSWER_QUESTION_ENABLED    = block_gmxp_core::PREGUNTADIARIA;

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
            $experiencia = get_config(self::PLUGIN, $key);

            $usuariogamificado = $DB->get_record('gmdl_usuario',array('id' => $userid));

            $usuariogamificado->experiencia_actual += $experiencia;

            $DB->update_record('gmdl_usuario',$usuariogamificado);

            $userid = $usuariogamificado->mdl_id_usuario;
            $userto = $DB->get_record('user', array('id' => $userid));

            $info = new stdClass();
            $info->exp = $experiencia;
            $info->typecomp = "competencia vs cpu";
            $info->nombre = $userto->firstname;
            $info->razon = "ganar";

            block_gmxp_messenger::notifica($userto,$info);

            local_gamedlemaster_log::info(
                'Event triggered', 'block_gmxp_observer::competence_won');

        }

    }

    public static function competencevs_won(
        \local_gamedlemaster\event\gmcompvs_compFinishedWon $event) {

        $key = self::DEFEAT_USER_ENABLED;
        $enabled = get_config(self::PLUGIN, $key);

        if($enabled){

            global $DB;

            $data = $event->get_data();
            foreach ($data as $key => $datum){
                if($key == 'other'){
                    $userid = $datum['userid'];
                }
            }

            $key = self::DEFEAT_USER;
            $experiencia = get_config(self::PLUGIN, $key);

            $usuariogamificado = $DB->get_record('gmdl_usuario',array('id' => $userid));

            $usuariogamificado->experiencia_actual += ($experiencia);

            $DB->update_record('gmdl_usuario',$usuariogamificado);

            $userid = $usuariogamificado->mdl_id_usuario;
            $userto = $DB->get_record('user', array('id' => $userid));

            $info = new stdClass();
            $info->exp = $experiencia;
            $info->typecomp = "competencia 1 vs 1";
            $info->nombre = $userto->firstname;
            $info->razon = "ganar";

            block_gmxp_messenger::notifica($userto,$info);

            local_gamedlemaster_log::info(
                'Event triggered', 'block_gmxp_observer::competencevs_won');

        }

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
            $experiencia = get_config(self::PLUGIN, $key);

            $usuariogamificado = $DB->get_record('gmdl_usuario',array('id' => $userid));

            $usuariogamificado->experiencia_actual += $experiencia;

            $DB->update_record('gmdl_usuario',$usuariogamificado);

            $userid = $usuariogamificado->mdl_id_usuario;
            $userto = $DB->get_record('user', array('id' => $userid));

            $info = new stdClass();
            $info->exp = $experiencia;
            $info->typecomp = "pregunta diaria";
            $info->nombre = $userto->firstname;
            $info->razon = "contestar correctamente";

            block_gmxp_messenger::notifica($userto,$info);

            local_gamedlemaster_log::info(
                'Event triggered', 'block_gmxp_observer::daily_question_answered');

        }

    }

    public static function module_completion_updated(
      core\event\course_module_completion_updated $event) {

        global $USER;
        $courseGamified = true;
        if( $courseGamified  &&  $event->relateduserid == $USER->id ){

            $eventdata = $event->get_data();
            local_gamedlemaster_log::info($eventdata);
            if(isset($eventdata['other']['completionstate']) && $eventdata['other']['completionstate'] == 1){

            }
        }
    }

    public static function user_loggedin(core\event\user_loggedin $event) {

        global $USER;
        global $DB;

        $user = $DB->get_record(block_gmxp_dao::TBL_GAMIFIED_USER, array(
            'mdl_id_usuario' => $USER->id
        ));

        $GMXP = array(
            'gamified_user' => $user->id,
            'level' => $user->nivel_actual,
            'experiencia_actual' => $user->experiencia_nivel,
            'experiencia_nivel' => $user->experiencia_actual
        );

        $_SESSION['GMXP'] = $GMXP;
    }

    public static function user_loggedout(core\event\user_loggedout $event) {
        local_gamedlemaster_log::info("User logged out");
        unset($_SESSION['GMXP']);
    }
}



