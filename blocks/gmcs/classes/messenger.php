<?php

require_once($CFG->dirroot . '/message/lib.php');

class block_gmcs_messenger{

     public static function notifica($recipient,$a){

// Prepare the message.
        $eventdata = new \core\message\message();
        $eventdata->courseid          = 1;
        $eventdata->component         = 'block_gmcs';
        $eventdata->name              = 'coinsgiven';
        $eventdata->notification      = 1;

        $eventdata->userfrom          = core_user::get_noreply_user();
        $eventdata->userto            = $recipient;
        $eventdata->subject           = get_string('coinsgivensubject', 'block_gmcs', $a);
        $eventdata->fullmessage       = get_string('coinsgivenbody', 'block_gmcs', $a);
        $eventdata->fullmessageformat = FORMAT_PLAIN;
        $eventdata->fullmessagehtml   = '';

        $eventdata->smallmessage      = get_string('coinsgivensmall', 'block_gmcs', $a);
        $eventdata->contexturl        = new moodle_url('/local/gmtienda/perfilgamificado.php'); //$a->url
        $eventdata->contexturlname    = 'Perfil gamificado'; //$a->quizname
        /*$eventdata->customdata        = [
            'cmid' => $a->quizcmid,
            'instance' => $a->quizid,
            'attemptid' => $a->attemptid,
        ];*/

// ... and send it.
        return message_send($eventdata);

    }

}