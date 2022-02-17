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

class filter_ubicast extends moodle_text_filter {

    private static function get_iframe_url($matches) {
        global $CFG;

        $courseid = $matches[1];
        $mediaid = $matches[2];
        $style = $matches[3];
        if (strpos($style, 'width') === false) {
            $style = 'width: 100%;' . $style;
        }
        if (strpos($style, 'height') === false) {
            $style = 'height: 300px;' . $style;
        }
        $style = 'background-color: #ddd;' . $style;

        $url = $CFG->wwwroot . '/lib/editor/atto/plugins/ubicast/view.php?course=' . $courseid . '&video=' . $mediaid;
        $iframe = '<iframe class="nudgis-iframe" src="' . $url . '" ' . 'style="' . $style . '" ' .
            'frameborder="0" allow="autoplay; encrypted-media" allowfullscreen="allowfullscreen"></iframe>';

        return $iframe;
    }

    /**
     * @param string $text    some HTML content to process.
     * @param array  $options options passed to the filters
     *
     * @return string the HTML content after the filtering has been applied.
     */
    public function filter($text, array $options = array()) {

        if (!is_string($text)) {
            // Non string data can not be filtered anyway.
            return $text;
        }

        $pattern = '/<img[^>]*class="atto_ubicast courseid_([0-9]+)_mediaid_([a-z0-9]+)"[^>]*style="([^"]*)"[^>]*>/';

        $text = preg_replace_callback($pattern, [
                'filter_ubicast',
                'get_iframe_url'
        ], $text);

        return $text;
    }
}
