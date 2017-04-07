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
 * notifies block
 *
 * @package block_notifies
 * @author  Vikas Sheokand <vikas@virasatsolutions.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @copyright  (C) 2016 onwards Vikas Sheokand  http://virasatsolutions.com/
 *
 */

defined('MOODLE_INTERNAL') || die();
require_login();

$plugin->version   = 2017040600;        // The current plugin version (Date: YYYYMMDDXX).
$plugin->requires  = 2015051900;        // Requires this Moodle version.
$plugin->component = 'block_notifies';  // Full name of the plugin (used for diagnostics).
$plugin->release   = 1;
$plugin->maturity  = MATURITY_STABLE;