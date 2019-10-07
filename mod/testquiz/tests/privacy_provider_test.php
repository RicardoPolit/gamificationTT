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
 * Privacy provider tests.
 *
 * @package    mod_testquiz
 * @copyright  2018 Stephen Bourget
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use core_privacy\local\metadata\collection;
use core_privacy\local\request\deletion_criteria;
use mod_testquiz\privacy\provider;

defined('MOODLE_INTERNAL') || die();

/**
 * Privacy provider tests class.
 *
 * @package    mod_testquiz
 * @copyright  2018 Stephen Bourget
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_testquiz_privacy_provider_testcase extends \core_privacy\tests\provider_testcase {
    /** @var stdClass The student object. */
    protected $student;

    /** @var stdClass The testquiz object. */
    protected $testquiz;

    /** @var stdClass The course object. */
    protected $course;

    /**
     * {@inheritdoc}
     */
    protected function setUp() {
        $this->resetAfterTest();

        global $DB;
        $generator = $this->getDataGenerator();
        $course = $generator->create_course();
        $testquiz = $this->getDataGenerator()->create_module('testquiz', array('course' => $course));

        // Create a testquiz activity.
        $testquizgenerator = $this->getDataGenerator()->get_plugin_generator('mod_testquiz');

        // Create a student which will make a testquiz.
        $student = $generator->create_user();
        $studentrole = $DB->get_record('role', ['shortname' => 'student']);
        $generator->enrol_user($student->id,  $course->id, $studentrole->id);

        // Have the student play through the game.
        $playthrough = $testquizgenerator->create_content($testquiz, array('userid' => $student->id, 'score' => '9999'));

        $this->student = $student;
        $this->testquiz = $testquiz;
        $this->course = $course;
    }

    /**
     * Test for provider::get_metadata().
     */
    public function test_get_metadata() {
        $collection = new collection('mod_testquiz');
        $newcollection = provider::get_metadata($collection);
        $itemcollection = $newcollection->get_collection();
        $this->assertCount(1, $itemcollection);

        $table = reset($itemcollection);
        $this->assertEquals('testquiz_scores', $table->get_name());

        $privacyfields = $table->get_privacy_fields();
        $this->assertArrayHasKey('testquizid', $privacyfields);
        $this->assertArrayHasKey('score', $privacyfields);
        $this->assertArrayHasKey('userid', $privacyfields);
        $this->assertArrayHasKey('timecreated', $privacyfields);

        $this->assertEquals('privacy:metadata:testquiz_scores', $table->get_summary());
    }

    /**
     * Test for provider::get_contexts_for_userid().
     */
    public function test_get_contexts_for_userid() {
        $cm = get_coursemodule_from_instance('testquiz', $this->testquiz->id);

        $contextlist = provider::get_contexts_for_userid($this->student->id);
        $this->assertCount(1, $contextlist);
        $contextforuser = $contextlist->current();
        $cmcontext = context_module::instance($cm->id);
        $this->assertEquals($cmcontext->id, $contextforuser->id);
    }

    /**
     * Test for provider::export_user_data().
     */
    public function test_export_for_context() {
        $cm = get_coursemodule_from_instance('testquiz', $this->testquiz->id);
        $cmcontext = context_module::instance($cm->id);

        // Export all of the data for the context.
        $this->export_context_data_for_user($this->student->id, $cmcontext, 'mod_testquiz');
        $writer = \core_privacy\local\request\writer::with_context($cmcontext);
        $this->assertTrue($writer->has_any_data());
    }

    /**
     * Test for provider::delete_data_for_all_users_in_context().
     */
    public function test_delete_data_for_all_users_in_context() {
        global $DB;

        $testquiz = $this->testquiz;
        $generator = $this->getDataGenerator();
        $testquizgenerator = $this->getDataGenerator()->get_plugin_generator('mod_testquiz');
        $cm = get_coursemodule_from_instance('testquiz', $this->testquiz->id);

        // Create another student who will play the testquiz activity.
        $student = $generator->create_user();
        $studentrole = $DB->get_record('role', ['shortname' => 'student']);
        $generator->enrol_user($student->id, $this->course->id, $studentrole->id);
        $playthrough = $testquizgenerator->create_content($testquiz, array('userid' => $student->id, 'score' => '100001'));

        // Before deletion, we should have 2 responses.
        $count = $DB->count_records('testquiz_scores', ['testquizid' => $testquiz->id]);
        $this->assertEquals(2, $count);

        // Delete data based on context.
        $cmcontext = context_module::instance($cm->id);
        provider::delete_data_for_all_users_in_context($cmcontext);

        // After deletion, the testquiz answers for that testquiz activity should have been deleted.
        $count = $DB->count_records('testquiz_scores', ['testquizid' => $testquiz->id]);
        $this->assertEquals(0, $count);
    }

    /**
     * Test for provider::delete_data_for_user().
     */
    public function test_delete_data_for_user_() {
        global $DB;

        $testquiz = $this->testquiz;
        $generator = $this->getDataGenerator();
        $cm1 = get_coursemodule_from_instance('testquiz', $this->testquiz->id);

        // Create a second testquiz activity.
        $params = array('course' => $this->course->id, 'name' => 'Another testquiz');
        $plugingenerator = $generator->get_plugin_generator('mod_testquiz');
        $testquiz2 = $plugingenerator->create_instance($params);
        $plugingenerator->create_instance($params);
        $cm2 = get_coursemodule_from_instance('testquiz', $testquiz2->id);

        // Make a playthrough for the first student in the 2nd testquiz activity.
        $playthrough = $plugingenerator->create_content($testquiz2, array('userid' => $this->student->id, 'score' => '100'));

        // Create another student who will play the first testquiz activity.
        $otherstudent = $generator->create_user();
        $studentrole = $DB->get_record('role', ['shortname' => 'student']);
        $generator->enrol_user($otherstudent->id, $this->course->id, $studentrole->id);
        $playthrough2 = $plugingenerator->create_content($testquiz, array('userid' => $otherstudent->id, 'score' => '999'));

        // Before deletion, we should have 2 responses.
        $count = $DB->count_records('testquiz_scores', ['testquizid' => $testquiz->id]);
        $this->assertEquals(2, $count);

        // Now delete the user's data.
        $context1 = context_module::instance($cm1->id);
        $context2 = context_module::instance($cm2->id);
        $contextlist = new \core_privacy\local\request\approved_contextlist($this->student, 'testquiz',
            [context_system::instance()->id, $context1->id, $context2->id]);
        provider::delete_data_for_user($contextlist);

        // After deletion, the testquiz answers for the first student should have been deleted.
        $count = $DB->count_records('testquiz_scores', ['testquizid' => $testquiz->id, 'userid' => $this->student->id]);
        $this->assertEquals(0, $count);

        // Confirm that we only have one testquiz answer available.
        $testquizscores = $DB->get_records('testquiz_scores');
        $this->assertCount(1, $testquizscores);
        $lastresponse = reset($testquizscores);

        // And that it's the other student's response.
        $this->assertEquals($otherstudent->id, $lastresponse->userid);
    }
}
