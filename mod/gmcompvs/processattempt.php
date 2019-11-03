<?php

require_once(dirname(__FILE__).'/../../config.php');
require_once($CFG->dirroot . '/question/engine/lib.php');
require_once($CFG->dirroot . '/mod/gmcompvs/classes/cpumind.php');

$fechafin = date('Y-m-d H:i:s');

$id  = optional_param('id', "", PARAM_INT);  // Is the param action.
$cm  = optional_param('cm', "", PARAM_INT);  // Is the param action.
$userid = optional_param('userid', "", PARAM_INT);  // Is the param action.
$gmuserid = optional_param('gmuserid', "", PARAM_INT);  // Is the param action.
$participacionid = optional_param('participacionid', "", PARAM_INT);  // Is the param action.
$idredirect  = optional_param('idredirect', "", PARAM_INT);  // Is the param action.
$participacion = $DB->get_record('gmdl_participacion', array('id' => $participacionid), '*', MUST_EXIST);

$timenow = time();

$quba = question_engine::load_questions_usage_by_activity($id);

$userScore = calculateScoreUser($quba,$timenow);

$tiempotardado = $timenow-$participacion->fecha_inicio;

$values = (object)[
    'id' => $participacionid,
    'puntuacion' => $userScore,
    'fecha_fin' => $timenow
];

$DB->update_record('gmdl_participacion',$values);

$sql =  'SELECT {gmdl_participacion}.puntuacion as contrincantepuntuacion, {gmdl_participacion}.gmdl_usuario_id as contrincante, {gmdl_participacion}.fecha_inicio as finicio, {gmdl_participacion}.fecha_fin as ffin, {gmdl_participacion}.id as id';
$sql .= ' FROM {gmdl_participacion}';
$sql .= ' WHERE {gmdl_participacion}.gmdl_usuario_id != '.$gmuserid.' AND';
$sql .= ' {gmdl_participacion}.fecha_inicio IS NOT NUll AND {gmdl_participacion}.fecha_fin IS NOT NULL AND';
$sql .= ' {gmdl_participacion}.gmdl_partida_id = '.$participacion->gmdl_partida_id;

$rows = $DB->get_recordset_sql($sql, null, $limitfrom = 0, $limitnum = 0);

$contrincante = new stdClass();

$contrincante->idusuario = null;

foreach ($rows as $row){
    $contrincante->puntuacion = $row->contrincantepuntuacion;
    $contrincante->idusuario = $row->contrincante;
    $contrincante->tiempotardado = $row->ffin - $row->finicio;
    $contrincante->idparticipacion = $row->id;
}


$gmcompvs  = $DB->get_record('gmcompvs', array('id' => $cm), '*', MUST_EXIST);
$course     = $DB->get_record('course', array('id' => $gmcompvs->course), '*', MUST_EXIST);
$cm         = get_coursemodule_from_instance('gmcompvs', $gmcompvs->id, $course->id, false, MUST_EXIST);

require_login($course, true, $cm);

$context = context_module::instance($cm->id);

$PAGE->set_title(format_string($gmcompvs->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($context);
$renderer = $PAGE->get_renderer('mod_gmcompvs');

echo $OUTPUT->header();

echo $OUTPUT->heading($gmcompvs->name);

echo "<link href='https://fonts.googleapis.com/css?family=Audiowide' rel='stylesheet' type='text/css'>";

/*echo $sql;*/

if($contrincante->idusuario != null) {

    if ($tiempotardado > $contrincante->tiempotardado) {
        $participacionid = $contrincante->idparticipacion;
        $contrincante->puntuacion += (int)($contrincante->puntuacion / 5);
        $userScoreUpdate = $contrincante->puntuacion;
    } else {
        $userScore += (int)($userScore / 5);
        $userScoreUpdate = $userScore;
    }

    $values = (object)[
        'id' => $participacionid,
        'puntuacion' => $userScoreUpdate
    ];

    $DB->update_record('gmdl_participacion', $values);

    if( $userScore > $contrincante->puntuacion )
        $userid = $gmuserid;
    else
        $userid = $contrincante->idusuario;

    $vistausuario = $renderer->render_results_attempt($userScore,$contrincante->puntuacion);

    /*$event = \local_gamedlemaster\event\gmcompvs_compFinishedWon::create(array(
        'objectid' => $gmcompvs->id,
        'context' => $context,
        'other' => array('userid' => $userid),
    ));

    $event->add_record_snapshot('gmcompvs', $gmcompvs);
    $event->trigger();*/

}else {

    $vistausuario = $renderer->render_wait_page();

}

echo $vistausuario;

echo $OUTPUT->footer();


function processAnswer( $dbanswer ){

    foreach ( $dbanswer as $i => $rows ){

        $answer[$i] = new stdClass();
        $answer[$i]->id = $rows->id;
        $answer[$i]->fraction = $rows->fraction;
        $answer[$i]->answer = $rows->answer;

    }

    return $answer;

}


function calculateScoreUser($quba, $timenow){

    global $DB;

    $score = 0;

    $quba->process_all_actions($timenow);

    //Itera entre todas las preguntas del intento

    foreach ($quba->get_slots() as $slot){
        $qa = $quba->get_question_attempt($slot);
        $dbanswers = $DB->get_records('question_answers', array('question' => $qa->get_question()->id));
        $question = $DB->get_record('question', array('id' => $qa->get_question()->id));
        $answers = processAnswer($dbanswers);

        //Se trata de manera diferente las preguntas tipo multichoice

        if($qa->get_question()->get_type_name() == 'multichoice')
            {
                try
                    {
                        $single = $DB->get_record('qtype_multichoice_options',array('questionid' => $qa->get_question()->id ),'single');
                        $order = explode(',',$qa->get_step_iterator()[0]->get_all_data()['_order']);

                        if($single->single == 1)
                            {
                                $useranswers = explode(',',$qa->get_step_iterator()[1]->get_all_data()['answer']);
                            }
                        else
                            {
                                $useranswers = [];
                                $useranswersAux = $qa->get_step_iterator()[1]->get_all_data();
                                $i = 0;
                                foreach ($useranswersAux as $useranswer)
                                    {
                                        if($useranswer == 1)
                                            {
                                                $useranswers[] = $i;
                                            }
                                        $i++;
                                    }
                            }
                    }
                catch (Exception $e)
                    {
                        $useranswers = null;
                    }

                if($useranswers != null){
                    foreach ($useranswers as $useranswer){
                        foreach ($answers as $answer){
                            if($order[$useranswer] == $answer->id){
                                $score += (100*$question->defaultmark) * $answer->fraction;
                            }
                        }
                    }
                }
            }
        else if($qa->get_question()->get_type_name() == 'truefalse')
            {

                try
                    {
                        $useranswer = $qa->get_step_iterator()[1]->get_all_data()['answer'];
                    }
                catch (Exception $e)
                    {
                        $useranswer = null;
                    }

                if(!($useranswer === null))
                    {
                        if($useranswer)
                            {
                                $useranswer = 0;
                            }
                        else
                            {
                                $useranswer = 1;
                            }
                        $answers = array_values($answers);
                        $score += (100*$question->defaultmark) * $answers[$useranswer]->fraction;
                    }
            }
        else
            {
                try
                    {
                        $useranswer = $qa->get_step_iterator()[1]->get_all_data()['answer'];
                    }
                catch (Exception $e)
                    {
                        $useranswer = null;
                    }

                if(!($useranswer === null))
                    {


                        foreach ($answers as $answer) {

                            /*echo 'useranswer: '.strtolower($useranswer).' answer: '.strtolower($answer->answer).' comparacion: ';
                            echo strtolower($useranswer) == strtolower($answer->answer);
                            echo '<br>';*/

                            if (strtolower($useranswer) == strtolower($answer->answer)) {

                                $score += (100*$question->defaultmark) * $answer->fraction;

                            }

                        }
                    }
            }
    }

    return $score;

}
