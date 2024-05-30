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

$string['filtername'] = 'Ad-hoc filter for UbiCast Atto plugin';
$string['createontheflyplaylists'] = 'Create playlists';
$string['createontheflyplaylists_desc'] = 'If enabled, whenever multiple media is embedded, a playlist will be displayed.';
$string['defaultapitimeoutsecs'] = 'API default timeout [s]';
$string['defaultapitimeoutsecs_desc'] = 'Timeout for default API calls, in seconds.';
$string['apikey'] = 'API key';
$string['apikey_desc'] = 'API key to get media info from Nudgis Media Server.';
$string['apilocation'] = 'API URL';
$string['apilocation_desc'] = 'Nudgis Media Server API base URL.';
