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
 * Prints a particular instance of testquiz
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    mod_testquiz
 * @copyright  2014 John Okely <john@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
require_once($CFG->dirroot . '/mod/testquiz/locallib.php');

$id = optional_param('id', 0, PARAM_INT); // Either course_module ID, or ...
$n  = optional_param('q', 0, PARAM_INT);  // ...testquiz instance ID - it should be named as the first character of the module.

if ($id) {
    $cm         = get_coursemodule_from_id('testquiz', $id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $testquiz  = $DB->get_record('testquiz', array('id' => $cm->instance), '*', MUST_EXIST);
} else if ($n) {
    $testquiz  = $DB->get_record('testquiz', array('id' => $n), '*', MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $testquiz->course), '*', MUST_EXIST);
    $cm         = get_coursemodule_from_instance('testquiz', $testquiz->id, $course->id, false, MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}

require_login($course, true, $cm);
$context = context_module::instance($cm->id);

// Trigger module viewed event.
$event = \mod_testquiz\event\course_module_viewed::create(array(
    'objectid' => $testquiz->id,
    'context' => $context,
));

$event->add_record_snapshot('course', $course);
$event->add_record_snapshot('course_modules', $cm);
$event->add_record_snapshot('testquiz', $testquiz);
$event->trigger();

// Mark as viewed.
$completion = new completion_info($course);
$completion->set_module_viewed($cm);

// Print the page header.

$PAGE->set_url('/mod/testquiz/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($testquiz->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($context);
$PAGE->set_focuscontrol('mod_testquiz_game');
$renderer = $PAGE->get_renderer('mod_testquiz');

// Output starts here.
echo $OUTPUT->header();

if ($testquiz->intro) {
    echo $OUTPUT->box(format_module_intro('testquiz', $testquiz, $cm->id), 'generalbox mod_introbox', 'testquizintro');
}

// Output header and directions.
echo $OUTPUT->heading_with_help(get_string('modulename', 'mod_testquiz'), 'howtoplay', 'mod_testquiz');

// Game here.
echo "<link href='https://fonts.googleapis.com/css?family=Audiowide' rel='stylesheet' type='text/css'>";
echo $renderer->render_game($testquiz, $cm->instance,$USER->id);
echo "<div class=fontloader>Loading game</div>";

// Display link to view student scores.
if (has_capability('mod/testquiz:viewallscores', $context)) {
    echo $renderer->render_score_link($testquiz);
}

// Finish the page.
echo $OUTPUT->footer();
