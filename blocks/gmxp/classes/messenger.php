<?php

require_once($CFG->dirroot . '/message/lib.php');

class block_gmxp_messenger{

     public static function notifica($recipient,$a){

// Prepare the message.
        $eventdata = new \core\message\message();
        $eventdata->courseid          = 1;
        $eventdata->component         = 'block_gmxp';
        $eventdata->name              = 'expgiven';
        $eventdata->notification      = 1;

        $eventdata->userfrom          = core_user::get_noreply_user();
        $eventdata->userto            = $recipient;
        $eventdata->subject           = get_string('expgivensubject', 'block_gmxp', $a);
        $eventdata->fullmessage       = get_string('expgivenbody', 'block_gmxp', $a);
        $eventdata->fullmessageformat = FORMAT_PLAIN;
        $eventdata->fullmessagehtml   = '';

        $eventdata->smallmessage      = get_string('expgivensmall', 'block_gmxp', $a);
        /*$eventdata->contexturl        = new moodle_url('/local/gmtienda/perfilgamificado.php'); //$a->url
        $eventdata->contexturlname    = 'Perfil gamificado'; //$a->quizname*/
        /*$eventdata->customdata        = [
            'cmid' => $a->quizcmid,
            'instance' => $a->quizid,
            'attemptid' => $a->attemptid,
        ];*/

// ... and send it.
        return message_send($eventdata);
    }

     public static function notifica_gral($recipient, $subject, $smallmessage, $fullmessage){

// Prepare the message.
        $eventdata = new \core\message\message();
        $eventdata->courseid          = 1;
        $eventdata->component         = 'block_gmxp';
        $eventdata->name              = 'expgiven';
        $eventdata->notification      = 1;

        $eventdata->userfrom          = core_user::get_noreply_user();
        $eventdata->userto            = $recipient;
        $eventdata->subject           = $subject;
        $eventdata->fullmessage       = $fullmessage;

        $eventdata->fullmessageformat = FORMAT_PLAIN;
        $eventdata->fullmessagehtml   = '';

        $eventdata->smallmessage      = $smallmessage;

        return message_send($eventdata);
    }

}
