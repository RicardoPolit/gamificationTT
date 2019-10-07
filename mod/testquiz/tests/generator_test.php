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
 * mod_testquiz generator tests
 *
 * @package    mod_testquiz
 * @category   test
 * @copyright  2018 Stephen Bourget
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Genarator tests class for mod_testquiz.
 *
 * @package    mod_testquiz
 * @category   test
 * @copyright  2018 Stephen Bourget
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_testquiz_generator_testcase extends advanced_testcase {

    public function test_create_instance() {
        global $DB;
        $this->resetAfterTest();
        $this->setAdminUser();

        $course = $this->getDataGenerator()->create_course();

        $this->assertFalse($DB->record_exists('testquiz', array('course' => $course->id)));
        $testquiz = $this->getDataGenerator()->create_module('testquiz', array('course' => $course));
        $records = $DB->get_records('testquiz', array('course' => $course->id), 'id');
        $this->assertCount(1, $records);
        $this->assertTrue(array_key_exists($testquiz->id, $records));

        $params = array('course' => $course->id, 'name' => 'Another testquiz');
        $testquiz = $this->getDataGenerator()->create_module('testquiz', $params);
        $records = $DB->get_records('testquiz', array('course' => $course->id), 'id');
        $this->assertCount(2, $records);
        $this->assertEquals('Another testquiz', $records[$testquiz->id]->name);
    }

    public function test_create_content() {
        global $DB;
        $this->resetAfterTest();
        $this->setAdminUser();

        $course = $this->getDataGenerator()->create_course();
        $testquiz = $this->getDataGenerator()->create_module('testquiz', array('course' => $course));
        $testquizgenerator = $this->getDataGenerator()->get_plugin_generator('mod_testquiz');

        $playthrough1 = $testquizgenerator->create_content($testquiz);
        $playthrough2 = $testquizgenerator->create_content($testquiz, array('score' => 3550));
        $records = $DB->get_records('testquiz_scores', array('testquizid' => $testquiz->id), 'id');
        $this->assertCount(2, $records);
        $this->assertEquals($playthrough1->id, $records[$playthrough1->id]->id);
        $this->assertEquals($playthrough2->id, $records[$playthrough2->id]->id);
        $this->assertEquals(3550, $records[$playthrough2->id]->score);
    }
}
