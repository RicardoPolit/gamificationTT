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
 * gmcompcpu external API
 *
 * @package    mod_gmcompcpu
 * @category   external
 * @copyright  2018 Stephen Bourget
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @since      Moodle 3.5
 */

defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir . '/externallib.php');
require_once($CFG->dirroot . '/mod/gmcompcpu/locallib.php');

/**
 * gmcompcpu external functions
 *
 * @package    mod_gmcompcpu
 * @category   external
 * @copyright  2018 Stephen Bourget
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @since      Moodle 3.5
 */
class mod_gmcompcpu_external extends external_api {


    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function update_score_parameters() {
        // Update_score_parameters() always return an external_function_parameters().
        // The external_function_parameters constructor expects an array of external_description.
        return new external_function_parameters(
            // An external_description can be: external_value, external_single_structure or external_multiple structure.
            array('gmcompcpuid' => new external_value(PARAM_INT, 'gmcompcpu instance ID'),
                'score' => new external_value(PARAM_INT, 'Player final score'),
                )
        );
    }

    /**
     * The function itself
     * @param int $gmcompcpuid gmcompcpu id.
     * @param float $score player's score.
     * @return string welcome message
     */
    public static function update_score($gmcompcpuid, $score) {

        global $DB;
        $warnings = array();
        $params = self::validate_parameters(self::update_score_parameters(),
                                            array(
                                                'gmcompcpuid' => $gmcompcpuid,
                                                'score' => $score
                                            ));
        if (!$gmcompcpu = $DB->get_record("gmcompcpu", array("id" => $params['gmcompcpuid']))) {
            throw new moodle_exception("invalidcoursemodule", "error");
        }

        $cm = get_coursemodule_from_instance('gmcompcpu', $gmcompcpu->id, 0, false, MUST_EXIST);
        $course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);

        // Validate the context and check capabilities.
        $context = context_module::instance($cm->id);
        self::validate_context($context);

        require_capability('mod/gmcompcpu:view', $context);

        // Record the high score.
        $id = gmcompcpu_add_highscore($gmcompcpu, $score);

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
            array('gmcompcpuid' => new external_value(PARAM_INT, 'gmcompcpu instance ID'))
        );
    }

    /**
     * The function itself
     * @param int $gmcompcpuid gmcompcpu id.
     * @return string welcome message
     */
    public static function start_game($gmcompcpuid) {

        global $DB;
        $warnings = array();
        $params = self::validate_parameters(self::start_game_parameters(),
                                            array('gmcompcpuid' => $gmcompcpuid)
                                            );
        if (!$gmcompcpu = $DB->get_record("gmcompcpu", array("id" => $params['gmcompcpuid']))) {
            throw new moodle_exception("invalidcoursemodule", "error");
        }

        $cm = get_coursemodule_from_instance('gmcompcpu', $gmcompcpu->id, 0, false, MUST_EXIST);
        $course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);

        // Validate the context and check capabilities.
        $context = context_module::instance($cm->id);
        self::validate_context($context);

        require_capability('mod/gmcompcpu:view', $context);

        // Record the game as started.
        $result = gmcompcpu_log_game_start($gmcompcpu);

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
