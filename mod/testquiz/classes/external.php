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
 * testquiz external API
 *
 * @package    mod_testquiz
 * @category   external
 * @copyright  2018 Stephen Bourget
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @since      Moodle 3.5
 */

defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir . '/externallib.php');
require_once($CFG->dirroot . '/mod/testquiz/locallib.php');

/**
 * testquiz external functions
 *
 * @package    mod_testquiz
 * @category   external
 * @copyright  2018 Stephen Bourget
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @since      Moodle 3.5
 */
class mod_testquiz_external extends external_api {


    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function update_score_parameters() {
        // Update_score_parameters() always return an external_function_parameters().
        // The external_function_parameters constructor expects an array of external_description.
        return new external_function_parameters(
            // An external_description can be: external_value, external_single_structure or external_multiple structure.
            array('testquizid' => new external_value(PARAM_INT, 'testquiz instance ID'),
                'score' => new external_value(PARAM_INT, 'Player final score'),
                )
        );
    }

    /**
     * The function itself
     * @param int $testquizid testquiz id.
     * @param float $score player's score.
     * @return string welcome message
     */
    public static function update_score($testquizid, $score) {

        global $DB;
        $warnings = array();
        $params = self::validate_parameters(self::update_score_parameters(),
                                            array(
                                                'testquizid' => $testquizid,
                                                'score' => $score
                                            ));
        if (!$testquiz = $DB->get_record("testquiz", array("id" => $params['testquizid']))) {
            throw new moodle_exception("invalidcoursemodule", "error");
        }

        $cm = get_coursemodule_from_instance('testquiz', $testquiz->id, 0, false, MUST_EXIST);
        $course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);

        // Validate the context and check capabilities.
        $context = context_module::instance($cm->id);
        self::validate_context($context);

        require_capability('mod/testquiz:view', $context);

        // Record the high score.
        $id = testquiz_add_highscore($testquiz, $score);

        return $id;
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function update_score_returns() {
        return new external_value(PARAM_INT, 'id of score entry');
    }



    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function start_game_parameters() {
        // Update_score_parameters() always return an external_function_parameters().
        // The external_function_parameters constructor expects an array of external_description.
        return new external_function_parameters(
            // An external_description can be: external_value, external_single_structure or external_multiple structure.
            array('testquizid' => new external_value(PARAM_INT, 'testquiz instance ID'))
        );
    }

    /**
     * The function itself
     * @param int $testquizid testquiz id.
     * @return string welcome message
     */
    public static function start_game($testquizid) {

        global $DB;
        $warnings = array();
        $params = self::validate_parameters(self::start_game_parameters(),
                                            array('testquizid' => $testquizid)
                                            );
        if (!$testquiz = $DB->get_record("testquiz", array("id" => $params['testquizid']))) {
            throw new moodle_exception("invalidcoursemodule", "error");
        }

        $cm = get_coursemodule_from_instance('testquiz', $testquiz->id, 0, false, MUST_EXIST);
        $course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);

        // Validate the context and check capabilities.
        $context = context_module::instance($cm->id);
        self::validate_context($context);

        require_capability('mod/testquiz:view', $context);

        // Record the game as started.
        $result = testquiz_log_game_start($testquiz);

        return $result;
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function start_game_returns() {
        return new external_value(PARAM_BOOL, 'Result of logging game start');
    }

}
