<?php

require_once(dirname(__FILE__).'/../../config.php');
require_once($CFG->dirroot . '/question/engine/lib.php');


$id  = optional_param('id', "", PARAM_INT);  // Is the param action.
$cm  = optional_param('cm', "", PARAM_INT);  // Is the param action.
$userid = optional_param('userid', "", PARAM_INT);  // Is the param action.

$timenow = time();

$quba = question_engine::load_questions_usage_by_activity($id);

$userScore = calculateScoreUser($quba,$timenow);


function processAnswer( $dbanswer ){

    foreach ( $dbanswer as $i => $rows ){

        $answer[$i] = new stdClass();
        $answer[$i]->id = $rows->id;
        $answer[$i]->fraction = $rows->fraction;
        $answer[$i]->answer = $rows->answer;

    }

    return $answer;

}



function calculateScoreUser($quba,$timenow){

    global $DB;

    $score = 0;

    $quba->process_all_actions($timenow);

    foreach ($quba->get_slots() as $slot){
        echo '<br>';
        $qa = $quba->get_question_attempt($slot);
        $dbanswers = $DB->get_records('question_answers', array('question' => $qa->get_question()->id));
        $answers = processAnswer($dbanswers);
        echo $qa->get_question()->id;
        echo '  '.$qa->get_question()->get_type_name();
        echo '<br>';

        if($qa->get_question()->get_type_name() == 'multichoice'){

            $order = explode(',',$qa->get_step_iterator()[0]->get_all_data()['_order']);
            $useranswers = explode(',',$qa->get_step_iterator()[1]->get_all_data()['answer']);

            foreach ($useranswers as $useranswer){

                foreach ($answers as $answer){

                    if($order[$useranswer] == $answer->id){

                        $score += 100 * $answer->fraction;

                    }

                    echo 'Esto es getrecords: id: '.$answer->id.' fraction: '.$answer->fraction.' answer: '.$answer->answer;
                    echo '<br>';
                }

            }



            echo json_encode($order);
            echo '<br>';
            echo json_encode($useranswers);

        }else{

            $useranswer = $qa->get_step_iterator()[1]->get_all_data()['answer'];

            foreach ($answers as $answer){

                if(strtolower($useranswer) == strtolower($answer->answer)){

                    $score += 100 * $answer->fraction;

                }

                echo 'Esto es getrecords: id: '.$answer->id.' fraction: '.$answer->fraction.' answer: '.$answer->answer;
                echo '<br>';
            }

            echo '<br>';
            echo json_encode($useranswer);

        }


        echo '<br>';
        echo $score;

    }

    return $score;

}