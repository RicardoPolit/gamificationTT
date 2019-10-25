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
 * mod_gmcompcpu generator tests
 *
 * @package    mod_gmcompcpu
 * @category   test
 * @copyright  2018 Stephen Bourget
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Genarator tests class for mod_gmcompcpu.
 *
 * @package    mod_gmcompcpu
 * @category   test
 * @copyright  2018 Stephen Bourget
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_gmcompcpu_generator_testcase extends advanced_testcase {

    public function test_create_instance() {
        global $DB;
        $this->resetAfterTest();
        $this->setAdminUser();

        $course = $this->getDataGenerator()->create_course();

        $this->assertFalse($DB->record_exists('gmcompcpu', array('course' => $course->id)));
        $gmcompcpu = $this->getDataGenerator()->create_module('gmcompcpu', array('course' => $course));
        $records = $DB->get_records('gmcompcpu', array('course' => $course->id), 'id');
        $this->assertCount(1, $records);
        $this->assertTrue(array_key_exists($gmcompcpu->id, $records));

        $params = array('course' => $course->id, 'name' => 'Another gmcompcpu');
        $gmcompcpu = $this->getDataGenerator()->create_module('gmcompcpu', $params);
        $records = $DB->get_records('gmcompcpu', array('course' => $course->id), 'id');
        $this->assertCount(2, $records);
        $this->assertEquals('Another gmcompcpu', $records[$gmcompcpu->id]->name);
    }

    public function test_create_content() {
        global $DB;
        $this->resetAfterTest();
        $this->setAdminUser();

        $course = $this->getDataGenerator()->create_course();
        $gmcompcpu = $this->getDataGenerator()->create_module('gmcompcpu', array('course' => $course));
        $gmcompcpugenerator = $this->getDataGenerator()->get_plugin_generator('mod_gmcompcpu');

        $playthrough1 = $gmcompcpugenerator->create_content($gmcompcpu);
        $playthrough2 = $gmcompcpugenerator->create_content($gmcompcpu, array('score' => 3550));
        $records = $DB->get_records('gmcompcpu_scores', array('gmcompcpuid' => $gmcompcpu->id), 'id');
        $this->assertCount(2, $records);
        $this->assertEquals($playthrough1->id, $records[$playthrough1->id]->id);
        $this->assertEquals($playthrough2->id, $records[$playthrough2->id]->id);
        $this->assertEquals(3550, $records[$playthrough2->id]->score);
    }
}
