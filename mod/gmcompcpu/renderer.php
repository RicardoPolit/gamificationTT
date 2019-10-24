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
    public function render_questions($gmcompcpu, $cm,$userid) {
        global $DB;

        $categoryid = explode(',', $gmcompcpu->questioncategory)[0];

        $questionids = array_keys($DB->get_records('question', array('category' => intval($categoryid)), '', 'id'));

        $context = context_module::instance($cm->id);

        /*$quizobj = quiz::create($cm->instance, $userid);*/

        $quba = question_engine::make_questions_usage_by_activity('mod_gmcompcpu', $context);

        /*return $quizobj->get_quiz()->preferredbehaviour;*/

        $quba->set_preferred_behaviour('deferredfeedback');

        foreach ($questionids as $questionid) {
            $questiondata = question_bank::load_question_data($questionid);

            if($questiondata->qtype == "multichoice" || $questiondata->qtype == "truefalse" || $questiondata->qtype == "shortanswer" || $questiondata->qtype == "numerical"){
                $question = question_bank::make_question($questiondata);
                $idstoslots[] = $quba->add_question($question, $questiondata->maxmark);
            }

        }

        $quba->start_all_questions();

        question_engine::save_questions_usage_by_activity($quba);


        $display = html_writer::start_tag('form',
            array('action' => new moodle_url('/mod/gmcompcpu/processattempt.php',
                array('id' => $quba->get_id(),'cm' => $cm->instance,'userid' => $userid)), 'method' => 'post',
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
    }

}
