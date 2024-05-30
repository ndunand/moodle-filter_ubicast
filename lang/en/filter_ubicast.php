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
$string['createontheflyplaylists_desc'] = 'If enabled, whenever multiple media is embedded one after another, a playlist will be displayed.';
$string['defaultapitimeoutsecs'] = 'API default timeout [s]';
$string['defaultapitimeoutsecs_desc'] = 'Timeout for default API calls, in seconds.';
$string['apikey'] = 'API key';
$string['apikey_desc'] = 'API key to get media info from Nudgis Media Server.<br><strong>Note: </strong>the API user has to have enough right to call the api endpoint <code>/medias/get</code> on any media. This is necessary to get the title correctly on the playlist tabs.';
$string['apilocation'] = 'API URL';
$string['apilocation_desc'] = 'Nudgis Media Server API base URL, usually your Nudgis front-end URL with the <code>/api/v2/</code> suffix.';
$string['filtersettings'] = 'New feature: display as playlist';
$string['filtersettings_desc'] = 'The other settings are only required if the feature is enabled, and you wish to display the videos titles on each of the playlist tabs. If you ignore these settings, the playlist tabs will only display numbers in sequence, i.e. <code>1., 2., ...</code>';
