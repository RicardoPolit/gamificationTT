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
 * Unit tests for lib.php
 *
 * @package    mod_testquiz
 * @category   test
 * @copyright  2015 Stephen Bourget
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/mod/testquiz/locallib.php');

/**
 * Unit tests for testquiz events.
 *
 * @package    mod_testquiz
 * @category   test
 * @copyright  2015 Stephen Bourget
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_testquiz_event_testcase extends advanced_testcase {

    /**
     * Test setup.
     */
    public function setUp() {
        $this->resetAfterTest();
    }

    /**
     * Test the course_module_viewed event.
     */
    public function test_course_module_viewed() {
        global $DB;
        // There is no proper API to call to trigger this event, so what we are
        // doing here is simply making sure that the events returns the right information.

        $course = $this->getDataGenerator()->create_course();
        $testquiz = $this->getDataGenerator()->create_module('testquiz', array('course' => $course->id));

        $dbcourse = $DB->get_record('course', array('id' => $course->id));
        $dbtestquiz = $DB->get_record('testquiz', array('id' => $testquiz->id));
        $context = context_module::instance($testquiz->cmid);

        $event = \mod_testquiz\event\course_module_viewed::create(array(
            'objectid' => $dbtestquiz->id,
            'context' => $context,
        ));

        $event->add_record_snapshot('course', $dbcourse);
        $event->add_record_snapshot('testquiz', $dbtestquiz);

        // Triggering and capturing the event.
        $sink = $this->redirectEvents();
        $event->trigger();
        $events = $sink->get_events();
        $this->assertCount(1, $events);
        $event = reset($events);

        // Checking that the event contains the expected values.
        $this->assertInstanceOf('\mod_testquiz\event\course_module_viewed', $event);
        $this->assertEquals(CONTEXT_MODULE, $event->contextlevel);
        $this->assertEquals($testquiz->cmid, $event->contextinstanceid);
        $this->assertEquals($testquiz->id, $event->objectid);
        $expected = array($course->id, 'testquiz', 'view', 'view.php?id=' . $testquiz->cmid,
            $testquiz->id, $testquiz->cmid);

        $this->assertEquals(new moodle_url('/mod/testquiz/view.php', array('id' => $testquiz->cmid)), $event->get_url());
        $this->assertEventContextNotUsed($event);
    }

    /**
     * Test the course_module_instance_list_viewed event.
     */
    public function test_course_module_instance_list_viewed() {
        // There is no proper API to call to trigger this event, so what we are
        // doing here is simply making sure that the events returns the right information.

        $course = $this->getDataGenerator()->create_course();

        $event = \mod_testquiz\event\course_module_instance_list_viewed::create(array(
            'context' => context_course::instance($course->id)
        ));

        // Triggering and capturing the event.
        $sink = $this->redirectEvents();
        $event->trigger();
        $events = $sink->get_events();
        $this->assertCount(1, $events);
        $event = reset($events);

        // Checking that the event contains the expected values.
        $this->assertInstanceOf('\mod_testquiz\event\course_module_instance_list_viewed', $event);
        $this->assertEquals(CONTEXT_COURSE, $event->contextlevel);
        $this->assertEquals($course->id, $event->contextinstanceid);
        $expected = array($course->id, 'testquiz', 'view all', 'index.php?id='.$course->id, '');
        $this->assertEventLegacyLogData($expected, $event);
        $this->assertEventContextNotUsed($event);
    }

    /**
     * Test the score_added event.
     */
    public function test_score_added() {

        $this->setAdminUser();
        $course = $this->getDataGenerator()->create_course();
        $testquiz = $this->getDataGenerator()->create_module('testquiz', array('course' => $course));
        $context = context_module::instance($testquiz->cmid);
        $score = mt_rand (0, 50000);

        $sink = $this->redirectEvents();
        $result = testquiz_add_highscore($testquiz, $score);

        $events = $sink->get_events();
        $this->assertCount(1, $events);
        $event = reset($events);

        // Checking that the event contains the expected values.
        $this->assertInstanceOf('\mod_testquiz\event\game_score_added', $event);
        $this->assertEquals(CONTEXT_MODULE, $event->contextlevel);
        $this->assertEquals($testquiz->cmid, $event->contextinstanceid);
        $this->assertEquals($score, $event->other['score']);
    }

    /**
     * Test the game_started event.
     */
    public function test_game_started() {

        $this->setAdminUser();
        $course = $this->getDataGenerator()->create_course();
        $testquiz = $this->getDataGenerator()->create_module('testquiz', array('course' => $course));
        $context = context_module::instance($testquiz->cmid);

        $sink = $this->redirectEvents();
        $result = testquiz_log_game_start($testquiz);

        $events = $sink->get_events();
        $this->assertCount(1, $events);
        $event = reset($events);

        // Checking that the event contains the expected values.
        $this->assertInstanceOf('\mod_testquiz\event\game_started', $event);
        $this->assertEquals(CONTEXT_MODULE, $event->contextlevel);
        $this->assertEquals($testquiz->cmid, $event->contextinstanceid);
    }

    /**
     * Test the game_scores_viewed event.
     */
    public function test_game_scores_viewed() {
        // There is no proper API to call to trigger this event, so what we are
        // doing here is simply making sure that the events returns the right information.

        $this->setAdminUser();
        $course = $this->getDataGenerator()->create_course();
        $testquiz = $this->getDataGenerator()->create_module('testquiz', array('course' => $course));
        $context = context_module::instance($testquiz->cmid);

        $testquizgenerator = $this->getDataGenerator()->get_plugin_generator('mod_testquiz');
        $scores = $testquizgenerator->create_content($testquiz);

        $event = \mod_testquiz\event\game_scores_viewed::create(array(
            'objectid' => $testquiz->id,
            'context' => $context
        ));

        $sink = $this->redirectEvents();
        $event->trigger();
        $events = $sink->get_events();
        $this->assertCount(1, $events);
        $event = reset($events);

        // Checking that the event contains the expected values.
        $this->assertInstanceOf('\mod_testquiz\event\game_scores_viewed', $event);
        $this->assertEquals(CONTEXT_MODULE, $event->contextlevel);
        $this->assertEquals($testquiz->cmid, $event->contextinstanceid);
    }
}
