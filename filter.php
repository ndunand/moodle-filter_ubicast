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

    protected $pattern;


    /**
     * Setup page with filter requirements and other prepare stuff.
     *
     * @param moodle_page $page The page we are going to add requirements to.
     * @param context $context The context which contents are going to be filtered.
     */
    public function setup($page, $context) {
        $this->pattern = '/<img[^>]*class="atto_ubicast courseid_([0-9]+)_mediaid_([a-z0-9]+)"[^>]*style="([^"]*)"[^>]*>/';
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

        if (strpos($text, 'atto_ubicast') === false) {
            return $text;
        }

        // Embed several consecutive Ubicast videos as a playlist, if the feature is activated.
        $isplaylist = false;

        if (get_config('filter_ubicast', 'createontheflyplaylists')) {
            $count = substr_count($text, 'class="atto_ubicast');
            if ($count > 1) {
                list($isplaylist, $text) = self::embedmany($text);
            }
        }
        if (!$isplaylist) {
            $text = preg_replace_callback($this->pattern, [
                    'filter_ubicast',
                    'get_iframe_html'
            ], $text);
        }

        return $text;
    }

    private static function get_iframe_html($matches) {
        global $CFG;

        $courseid = $matches[1];
        $mediaid = $matches[2];
        $style = $matches[3];
        if (!str_contains($style, 'width')) {
            $style = 'width: 100%;' . $style;
        }
        if (!str_contains($style, 'height')) {
            $style = 'height: 300px;' . $style;
        }
        $style = 'background-color: #ddd;' . $style;

        $url = $CFG->wwwroot . '/lib/editor/atto/plugins/ubicast/view.php?course=' . $courseid . '&video=' . $mediaid;
        $iframe =
                '<iframe class="nudgis-iframe" src="' . $url . '" ' . 'style="' . $style . '" ' . 'frameborder="0" allow="autoplay; encrypted-media" allowfullscreen="allowfullscreen"></iframe>';

        return $iframe;
    }

    private function embedmany($text) {
        global $DB, $PAGE;

        static $jsinserted = 0;

        $entries = array();
        $nextstop = 0;

        while (strpos($text, '<img class="atto_ubicast', $nextstop) !== false) {
            $nextstart = strpos($text, '<img class="atto_ubicast', $nextstop);
            if (!count($entries)) {
                // We want to replace videos with a playlist from the first entry that will be part of the playlist,
                // which might possible be the current one.
                $start = $nextstart;
            }
            if (count($entries)) {
                $textinbetween =
                        trim(str_replace('&nbsp;', '', strip_tags(substr($text, $nextstop, ($nextstart - $nextstop)))));
                if (strlen($textinbetween) > 1) {
                    // Check that there is no actual text content in between. If there is, it's not to be a playlist.
                    $this->isplaylist = false;

                    return array(
                            false,
                            $text
                    );
                }
            }
            $nextstop = strpos($text, '>', $nextstart) + 1; // Note: +1 to dismiss the matched '>'.
            $entry = substr($text, $nextstart, ($nextstop - $nextstart));
            $entries[] = $entry;
        }

        $this->isplaylist = true;

        $playlistjs = <<<EOF
<script type="text/javascript">
    var tabs = document.getElementsByClassName('filter_ubicast_playlist_tab');
    var players = document.getElementsByClassName('filter_ubicast_playlist_player');
    
    var filter_ubicast_playlisttab_settab = function(itemno) {
        for (var i = 0; i < players.length; i++) {
            players[i].classList.add('hidden');
            players[i].getElementsByTagName('iframe')[0].contentWindow.postMessage('pause', '*');
            tabs[i].classList.remove('selected');
        }
        document.getElementById('filter_ubicast_playlistitem_' + itemno).classList.remove('hidden');
        document.getElementById('filter_ubicast_playlisttab_' + itemno).classList.add('selected');
    }
</script>
EOF;

        if ($jsinserted) {
            $playlistjs = '';
        }
        else {
            $jsinserted = 1;
        }

        $players = '';
        $tabs = '';
        foreach ($entries as $entryno => $entryimg) {
            $itemno = $entryno + 1; // start at #1 instead of index 0
            $hiddenclass = $itemno   === 1 ? '' : ' hidden';
            $selectedclass = $itemno   === 1 ? ' selected' : '';

            // Find out the tab's name – only way seems to use and API call.
            // We'll cheat and use \block_ubicastlife_apicall as it's available.
            $title = '';
            $oid = preg_replace('/^.*mediaid_([^"]+)".*$/', '\1', $entryimg);
            try {
                $media = \filter_ubicast_apicall::sendRequest('medias/get', ['oid' => $oid]);
                if (isset($media->info) && isset($media->info->title)) {
                    $title = $media->info->title;
                }
            }
            catch (Exception $exception) {
                // leave it.
            }

            $tabs .= <<<EOF
<a href="#" id="filter_ubicast_playlisttab_$itemno" class="filter_ubicast_playlist_tab $selectedclass" onclick="filter_ubicast_playlisttab_settab($itemno); return false;"><ol start="$itemno"><li>$title</li></ol></a>
EOF;
            // TODO for Nudgis >= 12.3 use the player iframe API to get tab titles
            /*
             * @see https://beta.ubicast.net/static/mediaserver/docs/api/player.html
             *
             * theiframe.postMessage('getMetadata', '*');  // Send back the media identifier and title using a message event
             * window.addEventListener('message', function(event) {
             *     // Check that the message comes from the player iframe.
             *     if (event.origin !== 'https://your.mediaserver.tv') {
             *         return;
             *     }
             *     // TODO Handle event data.
             *     console.log('filter_ubicast_playlist event data:', event.data);
             *  }
             */
            $players .= '<div id="filter_ubicast_playlistitem_' . $itemno . '" class="filter_ubicast_playlist_player ' . $hiddenclass . '">' . preg_replace_callback($this->pattern, [
                    'filter_ubicast',
                    'get_iframe_html'
            ], $entryimg) . '</div>';
        }

        $playlisttext = <<<EOF
$playlistjs
<div class="filter_ubicast_playlist">
    <div class="filter_ubicast_playlist_tabs">
        $tabs
    </div>
    <div class="filter_ubicast_playlist_players">
        $players
    </div>
    <div class="clearfix"></div>
</div>
EOF;


        return array(
                true,
                substr($text, 0, $start) . $playlisttext . substr($text, $nextstop)
        );
    }
}
