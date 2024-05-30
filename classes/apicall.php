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
 * @package    filter_ubicast
 * @copyright  2024 Université de Laussanne {@link https://www.unil.ch}
 * @author     Nicolas Dunand <nicolas.dunand@unil.ch>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

class filter_ubicast_apicall {

    /**
     * Sends an API request
     *
     * @param       $path
     * @param array $params
     * @param int   $timeout
     *
     * @return false|mixed
     * @throws dml_exception
     * @throws moodle_exception
     */
    static function sendRequest($path, $params = [], $timeout = 0, $method = 'GET') {

        $apikey = get_config('filter_ubicast', 'apikey');
        $request_url = trim(get_config('filter_ubicast', 'apilocation'), '/') . '/' . trim($path,
                        '/') . '/' . '?api_key=' . $apikey;
        if (!$timeout) {
            $timeout = get_config('filter_ubicast', 'defaultapitimeoutsecs');
        }
        $timeoutms = 1e3 * (int)$timeout;

        if ($method === 'GET') {
            foreach ($params as $key => $value) {
                $request_url .= '&' . $key . '=' . $value;
            }
        }

        libxml_use_internal_errors(true);

        $curl_request = curl_init();
        curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_request, CURLOPT_CUSTOMREQUEST, $method);
        if ($method === 'POST') {
            curl_setopt($curl_request, CURLOPT_POSTFIELDS, $params);
        }
        curl_setopt($curl_request, CURLOPT_TIMEOUT_MS, $timeoutms);
        curl_setopt($curl_request, CURLOPT_SSL_VERIFYPEER, true);

        curl_setopt($curl_request, CURLOPT_URL, $request_url);

        $output = curl_exec($curl_request);
        $curl_errno = curl_errno($curl_request); // 0 if fine
        $response_details = curl_getinfo($curl_request);

        curl_close($curl_request);

        if ($output === false) {
            if ($curl_errno) {
                mtrace('CURL REQUEST ERROR ' . $curl_errno . ' while calling ' . $path . ' ' . json_encode($params));
            }

            return false;
        }

        try {
            $return = json_decode($output);
        }
        catch (Exception $e) {
            print_error('api_fail', 'exam', null, $e->getMessage() . $e->getCode());

            return false;
        }

        return $return;
    }

}

