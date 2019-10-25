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
 * The main renderer for mod_gmcompcpu
 *
 * @package    mod_gmcompcpu
 * @copyright  2016 John Okely <john@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * The main renderer for mod_gmcompcpu
 *
 * @package    mod_gmcompcpu
 * @copyright  2016 John Okely <john@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_gmcompcpu_renderer extends plugin_renderer_base {
    /**
     * Initialises the game and returns its HTML code
     *
     * @param stdClass $gmcompcpu The gmcompcpu to be added
     * @param context $context The context
     * @return string The HTML code of the game
     */
    public function render_game($gmcompcpu, $context) {
        global $DB;

        $categoryid = explode(',', $gmcompcpu->questioncategory)[0];
        $questionids = array_keys($DB->get_records('question', array('category' => intval($categoryid)), '', 'id'));
        $questions = question_load_questions($questionids);

        $this->page->requires->strings_for_js(array(
                'score',
                'emptyquiz',
                'endofgame',
                'spacetostart'
            ), 'mod_gmcompcpu');

        $qjson = [];
        foreach ($questions as $question) {
            if ($question->qtype == "multichoice" || $question->qtype == "truefalse") {
                $questiontext = gmcompcpu_cleanup($question->questiontext);
                $answers = [];
                foreach ($question->options->answers as $answer) {
                    $answertext = gmcompcpu_cleanup($answer->answer);
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
                    $questiontext = gmcompcpu_cleanup($subquestion->questiontext);
                    $answertext = gmcompcpu_cleanup($subquestion->answertext);
                    $subquestions[] = ["question" => $questiontext, "answer" => $answertext];
                }
                $qjson[] = ["question" => get_string("match", "quiz"), "stems" => $subquestions, "type" => $question->qtype];
            }
        }

        $this->page->requires->js_call_amd('mod_gmcompcpu/gmcompcpu', 'init', array($qjson, $gmcompcpu->id));

        $display = '<canvas id="mod_gmcompcpu_game"></canvas>';
        $display .= '<audio id="mod_gmcompcpu_sound_laser" preload="auto">'.
                    '<source src="sound/Laser.wav" type="audio/wav" />'.
                    '</audio>';
        $display .= '<audio id="mod_gmcompcpu_sound_explosion" preload="auto">'.
                    '<source src="sound/Explosion.wav" type="audio/wav" />'.
                    '</audio>';
        $display .= '<audio id="mod_gmcompcpu_sound_deflect" preload="auto">'.
                    '<source src="sound/Deflect.wav" type="audio/wav" />'.
                    '</audio>';
        $display .= '<audio id="mod_gmcompcpu_sound_enemylaser" preload="auto">'.
                    '<source src="sound/EnemyLaser.wav" type="audio/wav" />'.
                    '</audio>';

        $display .= '<div id="button_container">';
        $display .= '<input id="mod_gmcompcpu_fullscreen_button" class= "btn btn-secondary" type="button" value="' .
                    get_string('fullscreen', 'mod_gmcompcpu') . '">';
        $display .= html_writer::checkbox('sound', '', false,
                                          get_string('sound', 'mod_gmcompcpu'),
                                          array('id' => 'mod_gmcompcpu_sound_on'));
        $display .= '</div>';

        return $display;
    }

    /**
     * Render the link to access the high scores.
     * @param stdClass $gmcompcpu
     * @return string
     */
    public function render_score_link($gmcompcpu) {

        $url = new moodle_url('/mod/gmcompcpu/scores.php', array('id' => $gmcompcpu->id));
        $scorestring = get_string('scoreslink', 'gmcompcpu');
        $scorestringhelp = get_string('scoreslinkhelp', 'gmcompcpu');
        $display = html_writer::start_tag('div', array('class' => 'gmcompcpu-scores'));
        $display .= html_writer::tag('a', $scorestring, array('title' => $scorestringhelp, 'href' => $url));
        $display .= html_writer::end_tag('div');
        return $display;
    }
}
