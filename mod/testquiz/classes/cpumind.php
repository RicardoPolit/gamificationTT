<?php

class mod_testquiz__cpumind {

    function cpuattempt( $quba , $cm){

        global $DB;

        /*$testquiz  = $DB->get_record('testquiz', array('id' => $cm->instance), '*', MUST_EXIST);

        $difficulty = $testquiz->cpudifficulty;*/

        $difficulty = 2;

        $slots = $quba->get_slots();

        foreach ($slots as $i => $slot){

            $questionids[$i] = $quba->get_question($slot)->id;

        }

        $questions = question_load_questions($questionids);

        foreach ($questions as $question) {

            if ($question->qtype == "multichoice" || $question->qtype == "truefalse") {

                foreach ($question->options->answers as $answer) {

                    $answers[] = ["fraction" => $answer->fraction];

                }

                if ($question->qtype == "truefalse" || $question->options->single == 1) {



                    /*$qjson[] = ["question" => $questiontext, "answers" => $answers, "type" => $question->qtype];*/

                } else {



                    /*$qjson[] = ["question" => $questiontext, "answers" => $answers, "type" => $question->qtype,
                        "single" => $question->qtype == "multichoice" && ];*/
                }

            }

        }

    }

}

