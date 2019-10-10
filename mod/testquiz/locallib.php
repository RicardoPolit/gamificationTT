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
 * Internal library of functions for module testquiz
 *
 * All the testquiz specific functions, needed to implement the module
 * logic, should go here. Never include this file from your lib.php!
 *
 * @package    mod_testquiz
 * @copyright  2014 John Okely <john@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/questionlib.php');
require_once($CFG->dirroot.'/lib/completionlib.php');

/**
 * Function to prepare strings to be printed out as JSON.
 *
 * @param stdClass $string The string to be cleaned
 * @return string The string, ready to be printed as JSON
 */
function testquiz_cleanup($string) {
    $string = strip_tags($string);
    $string = preg_replace('/"/', '\"', $string);
    $string = preg_replace('/[\n\r]/', ' ', $string);
    $string = stripslashes($string);
    return $string;
}
/**
 * Function to add the students score to the DB.
 * @param stdClass $testquiz
 * @param float $score
 * @return int
 */
function testquiz_add_highscore($testquiz, $score) {
    global $USER, $DB;

    $cm = get_coursemodule_from_instance('testquiz', $testquiz->id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $context = context_module::instance($cm->id);

    // Write the high score to the DB.
    $record = new stdClass();
    $record->testquizid = $testquiz->id;
    $record->userid = $USER->id;
    $record->score = $score;
    $record->timecreated = time();
    $record->id = $DB->insert_record('testquiz_scores', $record);

    // Trigger the game score added event.
    $event = \mod_testquiz\event\game_score_added::create(array(
        'objectid' => $record->id,
        'context' => $context,
        'other' => array('score' => $score)
    ));

    $event->add_record_snapshot('testquiz', $testquiz);
    $event->add_record_snapshot('testquiz_scores', $record);
    $event->trigger();

    // Update completion state.
    $completion = new completion_info($course);
    if ($completion->is_enabled($cm) == COMPLETION_TRACKING_AUTOMATIC && $testquiz->completionscore) {
        $completion->update_state($cm, COMPLETION_COMPLETE, $record->userid);
    }

    return $record->id;
}

/**
 * Function to record the player starting the testquiz.
 * @param stdClass $testquiz
 * @return boolean
 */
function testquiz_log_game_start($testquiz) {
    global $DB;

    $cm = get_coursemodule_from_instance('testquiz', $testquiz->id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $context = context_module::instance($cm->id);

    // Trigger the game score added event.
    $event = \mod_testquiz\event\game_started::create(array(
        'objectid' => $testquiz->id,
        'context' => $context,
    ));

    $event->add_record_snapshot('testquiz', $testquiz);
    $event->trigger();

    return true;
}


