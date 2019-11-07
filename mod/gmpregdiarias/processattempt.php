<?php

require_once(dirname(__FILE__).'/../../config.php');
require_once($CFG->dirroot . '/question/engine/lib.php');
require_once($CFG->dirroot . '/mod/gmpregdiarias/classes/cpumind.php');


$id  = optional_param('id', "", PARAM_INT);  // Is the param action.
$cm  = optional_param('cm', "", PARAM_INT);  // Is the param action.
$gmuserid = optional_param('gmuserid', "", PARAM_INT);  // Is the param action.
$intentoid = optional_param('intentoid', "", PARAM_INT);  // Is the param action.
$idredirect  = optional_param('idredirect', "", PARAM_INT);  // Is the param action.

$timenow = time();

$quba = question_engine::load_questions_usage_by_activity($id);

$userScore = calculateScoreUser($quba,$timenow);

$values = (object)[
    'id' => $intentoid,
    'calificacion' => (double)$userScore
];

$gmpregdiarias  = $DB->get_record('gmpregdiarias', array('id' => $cm), '*', MUST_EXIST);
$course     = $DB->get_record('course', array('id' => $gmpregdiarias->course), '*', MUST_EXIST);
$cm         = get_coursemodule_from_instance('gmpregdiarias', $gmpregdiarias->id, $course->id, false, MUST_EXIST);

require_login($course, true, $cm);

$context = context_module::instance($cm->id);


$PAGE->set_title(format_string($gmpregdiarias->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($context);

$renderer = $PAGE->get_renderer('mod_gmpregdiarias');

echo $OUTPUT->header();

echo $OUTPUT->heading($gmpregdiarias->name);

echo "<link href='https://fonts.googleapis.com/css?family=Audiowide' rel='stylesheet' type='text/css'>";

echo $renderer->render_results_attempt($userScore);

echo $OUTPUT->footer();

$DB->update_record('gmdl_intento_diario',$values);


if($userScore > 5 ){

    $event = \local_gamedlemaster\event\gmpregdiarias_pregCorrecta::create(array(
        'objectid' => $gmpregdiarias->id,
        'context' => $context,
        'other' => array('userid' => $userid),
    ));

    $event->add_record_snapshot('gmpregdiarias', $gmpregdiarias);
    $event->trigger();

}

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

    $score = (double)0;

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
                                $score += (double) $answer->fraction;
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
                        $score += (double) $answers[$useranswer]->fraction;
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

                                $score += (double) $answer->fraction;

                            }

                        }
                    }
            }
    }
    return (double)($score*10);

}
