<?php
// This file is part of notifies block.
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
 *  notifies block
 *
 * @package block_notifies
 * @author  Vikas Sheokand <vikas@virasatsolutions.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright  (C) 2016 onwards Vikas Sheokand  http://virasatsolutions.com/
 *
 */

defined('MOODLE_INTERNAL') || die();
require_login();

/**
 * The block_notifies_edit_form custom block class
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @copyright (C) 2016 onwards Vikas Sheokand  http://virasatsolutions.com/
 */
class block_notifies_edit_form extends block_edit_form {

    /**
     * this is moodle block config form form
     * @param object $mform The object variable of moodle form
     * @return none
     */
    protected function specific_definition($mform) {

        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        $mform->addElement('text', 'config_title', get_string('blocktitle', 'block_notifies'),
        array('placeholder' => 'Enter new Title'));
        $mform->setType('config_title', PARAM_RAW);

        $mform->addElement('text', 'config_furl', get_string('forumurl', 'block_notifies'),
        array('placeholder' => 'Enter new URL'));
        $mform->setType('config_furl', PARAM_RAW);

    }
}
