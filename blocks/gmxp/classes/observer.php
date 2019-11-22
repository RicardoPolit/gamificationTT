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

            block_gmxp_dao::bring_experience($userid, $experiencia);

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

            block_gmxp_dao::bring_experience($userid, $experiencia);

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

            block_gmxp_dao::bring_experience($userid, $experiencia);

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

        global $DB;
        global $USER;
        $eventdata = $event->get_data();
        $courseid = $eventdata['courseid'];
        $format   = course_get_format($courseid);

        if ($format->get_format() === "gamedle") {

            $conditions = array( 'course' => $courseid );
            $modcompletion = $DB->get_record('course_module_completion',
                array( 'id' => $eventdata['objectid'] )
            );

            $module = $DB->get_record('course_modules',
                array( 'id' => $modcompletion->coursemoduleid ),
                'section'
            );

            $section = $DB->get_record('course_sections',
                array( 'id' => $module->course )
            );

            // y por cada seccion que no sea la seccion 0 
            if ($section->section != 0) {
                $conditions = array( 'section' => $section->id );
                $modules = $DB->get_records('course_modules', $conditions);

                // Revisa si ya completo todas las actividades de dicha
                // seccion
                $completed = true;
                foreach($modules as $module) {

                    $conditions = array(
                        'coursemoduleid' => $module->id,
                        'userid' => $USER->id
                    );

                    $completion = $DB->get_record('course_modules_completion',
                        $conditions
                    );

                    $completed = $completed && (boolean)$completion;
                    $completed = $completed && $completion->completionstate;
                }

                if($completed) {
                    $format->bring_xp($USER->id,$section->id);
                }
            }
        }
    }

    public static function user_loggedin(core\event\user_loggedin $event) {

        global $USER;
        global $DB;

        $user = $DB->get_record(block_gmxp_dao::TBL_GAMIFIED_USER, array(
            'mdl_id_usuario' => $USER->id
        ));

        $_SESSION['GMXP'] = $user;
    }

    public static function user_loggedout(core\event\user_loggedout $event) {
        local_gamedlemaster_log::info("User logged out");
        unset($_SESSION['GMXP']);
    }
}



