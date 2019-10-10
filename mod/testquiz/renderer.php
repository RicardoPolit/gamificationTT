<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * The main renderer for mod_testquiz
 *
 * @package    mod_testquiz
 * @copyright  2016 John Okely <john@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * The main renderer for mod_testquiz
 *
 * @package    mod_testquiz
 * @copyright  2016 John Okely <john@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_testquiz_renderer extends plugin_renderer_base {
    /**
     * Initialises the game and returns its HTML code
     *
     * @param stdClass $testquiz The testquiz to be added
     * @param context $context The context
     * @return string The HTML code of the game
     */
    public function render_game($testquiz, $cm,$userid) {
        global $DB;

        $categoryid = explode(',', $testquiz->questioncategory)[0];

        $questionids = array_keys($DB->get_records('question', array('category' => intval($categoryid)), '', 'id'));

        $context = context_module::instance($cm->id);

        /*$quizobj = quiz::create($cm->instance, $userid);*/

        $quba = question_engine::make_questions_usage_by_activity('mod_testquiz', $context);

        /*return $quizobj->get_quiz()->preferredbehaviour;*/

        $quba->set_preferred_behaviour('deferredfeedback');

        foreach ($questionids as $i => $questionid) {
            $questiondata = question_bank::load_question_data($questionid);
            $question = question_bank::make_question($questiondata);
            $idstoslots[$i] = $quba->add_question($question, $questiondata->maxmark);
        }

        $quba->start_all_questions();

        question_engine::save_questions_usage_by_activity($quba);


        $display = html_writer::start_tag('form',
            array('action' => new moodle_url('/mod/testquiz/processattempt.php',
                array('id' => $quba->get_id())), 'method' => 'post',
                'enctype' => 'multipart/form-data', 'accept-charset' => 'utf-8',
                'id' => 'responseform'));

        /*$this->page->requires->js_init_call('M.core_question_engine.init_form',
            array('#responseform'), false, 'core_question_engine');*/

        $display .= '<input type="hidden" name="slots" value="' . implode(',', $idstoslots) . "\" />\n";
        $display .= '<input type="hidden" name="scrollpos" value="" />';

        $options = new question_display_options();
        $options->marks = question_display_options::MAX_ONLY;
        $options->markdp = 2; // Display marks to 2 decimal places.
        $options->feedback = question_display_options::VISIBLE;
        $options->generalfeedback = question_display_options::HIDDEN;

        $display .= html_writer::start_tag('div');

        foreach ($idstoslots as $displaynumber => $slot) {
            $display .= $quba->render_question($slot, $options, $displaynumber);
        }


        $display .= html_writer::start_tag('div');

        $display .= html_writer::empty_tag('input', array('type' => 'submit', 'name' => 'next',
            'value' => 'Submit', 'class' => 'mod_quiz-next-nav btn btn-primary'));
        $display .= html_writer::end_tag('div');

        $display .= html_writer::end_tag('div');
        $display .= html_writer::end_tag('form');

        return $display;
        /*$categoryid = explode(',', $testquiz->questioncategory)[0];
        $questionids = array_keys($DB->get_records('question', array('category' => intval($categoryid)), '', 'id'));
        $questions = question_load_questions($questionids);

        $this->page->requires->strings_for_js(array(
                'score',
                'emptyquiz',
                'endofgame',
                'spacetostart'
            ), 'mod_testquiz');

        $qjson = [];
        foreach ($questions as $question) {
            if ($question->qtype == "multichoice" || $question->qtype == "truefalse") {
                $questiontext = testquiz_cleanup($question->questiontext);
                $answers = [];
                foreach ($question->options->answers as $answer) {
                    $answertext = testquiz_cleanup($answer->answer);
                    $answers[] = ["text" => $answertext, "fraction" => $answer->fraction];
                }

                // The "single" entry is used by multichoice to determine single or multi answer.
                if ($question->qtype == "truefalse") {
                    $qjson[] = ["question" => $questiontext, "answers" => $answers, "type" => $question->qtype];
                } else {
                    $qjson[] = ["question" => $questiontext, "answers" => $answers, "type" => $question->qtype,
                        "single" => $question->qtype == "multichoice" && $question->options->single == 1];
                }
            }
            if ($question->qtype == "match") {
                $subquestions = [];
                foreach ($question->options->subquestions as $subquestion) {
                    $questiontext = testquiz_cleanup($subquestion->questiontext);
                    $answertext = testquiz_cleanup($subquestion->answertext);
                    $subquestions[] = ["question" => $questiontext, "answer" => $answertext];
                }
                $qjson[] = ["question" => get_string("match", "quiz"), "stems" => $subquestions, "type" => $question->qtype];
            }
        }



        $this->page->requires->js_call_amd('mod_testquiz/testquiz', 'init', array($qjson, $testquiz->id));

        $display = '<div> Hola </div>';*/

        /*$display = '<canvas id="mod_testquiz_game"></canvas>';
        $display .= '<audio id="mod_testquiz_sound_laser" preload="auto">'.
                    '<source src="sound/Laser.wav" type="audio/wav" />'.
                    '</audio>';
        $display .= '<audio id="mod_testquiz_sound_explosion" preload="auto">'.
                    '<source src="sound/Explosion.wav" type="audio/wav" />'.
                    '</audio>';
        $display .= '<audio id="mod_testquiz_sound_deflect" preload="auto">'.
                    '<source src="sound/Deflect.wav" type="audio/wav" />'.
                    '</audio>';
        $display .= '<audio id="mod_testquiz_sound_enemylaser" preload="auto">'.
                    '<source src="sound/EnemyLaser.wav" type="audio/wav" />'.
                    '</audio>';

        $display .= '<div id="button_container">';
        $display .= '<input id="mod_testquiz_fullscreen_button" class= "btn btn-secondary" type="button" value="' .
                    get_string('fullscreen', 'mod_testquiz') . '">';
        $display .= html_writer::checkbox('sound', '', false,
                                          get_string('sound', 'mod_testquiz'),
                                          array('id' => 'mod_testquiz_sound_on'));
        $display .= '</div>';*/

        return $display;
    }

    /**
     * Render the link to access the high scores.
     * @param stdClass $testquiz
     * @return string
     */
    public function render_score_link($testquiz) {

        $url = new moodle_url('/mod/testquiz/scores.php', array('id' => $testquiz->id));
        $scorestring = get_string('scoreslink', 'testquiz');
        $scorestringhelp = get_string('scoreslinkhelp', 'testquiz');
        $display = html_writer::start_tag('div', array('class' => 'testquiz-scores'));
        $display .= html_writer::tag('a', $scorestring, array('title' => $scorestringhelp, 'href' => $url));
        $display .= html_writer::end_tag('div');
        return $display;
    }
}
