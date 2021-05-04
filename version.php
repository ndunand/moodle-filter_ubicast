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
 * Atto text editor integration version file.
 *
 * @package    filter_ubicast
 * @copyright  2021 UbiCast {@link https://www.ubicast.eu}
 * @author     Nicolas Dunand <nicolas.dunand@unil.ch>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$plugin->version = 2021050400;
$plugin->requires = 2019052000; // Moodle 3.7.
$plugin->component = 'filter_ubicast';
$plugin->maturity = MATURITY_STABLE;
$plugin->release = '1.2 (Build: 2021050400)';
$plugin->dependencies = array(
    'atto_ubicast' => 2021010100, // To have atto_ubicast offering the atto_ubicast/usefilter setting.
    'mod_ubicast'  => 2021010100, // To have mod_ubicast/media_selector.js use input[name=mediaimg].
);
