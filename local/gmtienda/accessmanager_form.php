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
 * Defines the form that limits student's access to attempt a quiz.
 *
 * @package   mod_quiz
 * @copyright 2011 The Open University
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/formslib.php');


/**
 * A form that limits student's access to attempt a quiz.
 *
 * @copyright  2011 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class local_gmtienda_preflight_check_form extends moodleform {

    protected function definition() {
        $mform = $this->_form;
        $this->_form->updateAttributes(array('id' => 'local_gmtienda_preflight_form'));

        foreach ($this->_customdata['hidden'] as $name => $value) {
            if ($name === 'sesskey') {
                continue;
            }
            $mform->addElement('hidden', $name, $value);
            $mform->setType($name, PARAM_INT);
        }

        $this->add_action_buttons(true, 'Comprar');
        $mform->setDisableShortforms();
    }

}
