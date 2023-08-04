<?php
/**
 * API request
 *
 * @param $api_endpoint string Request URL
 * @param $type string get / post / put
 * @param $data array GET/POST data (optional)
 * @param $headers array Request headers (1 array item — header key & value) (optional)
 */
/*function api_request($api_endpoint, $type, $data = false, $headers = []) {
    $server_url = 'https://...';

    // Prepare data
    if(is_array($data)) {
        $query_data = http_build_query($data);
    } else {
        $query_data = $data;
    }

    // URL
    $url = $server_url . $api_endpoint;

    if(($type == 'get') && !empty($data)) {
        $url = $url .'?'. $query_data;
    }

    $ch = curl_init($url);

    // Data type
    if($type == 'post') {
        curl_setopt($ch, CURLOPT_POST, true);
    }

    if($type == 'put') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    }

    if(
        !empty($data) &&
        (
            ($type == 'post') ||
            ($type == 'put')
        )
    ) {
        curl_setopt($ch,CURLOPT_POSTFIELDS, $query_data);
    }

    // Headers
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    // Request return instead of echo
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    // Result
    $result = curl_exec($ch);

    // Content type
    $content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);

    if(strpos($content_type, 'json') !== false) {
        $result = json_decode($result);
    }

    curl_close($ch);

    return $result;
}*/

function ram_api_request($url, $type, $data = false) {
    // Prepare data
    if(is_array($data)) {
        $query_data = http_build_query($data);
    } else {
        $query_data = $data;
    }

    // URL
    if(($type == 'get') && !empty($data)) {
        $url = $url .'?'. $query_data;
    }

    $ch = curl_init($url);

    // Data type
    if($type == 'post') {
        curl_setopt($ch, CURLOPT_POST, true);
    }

    if($type == 'put') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    }

    if(
        !empty($data) &&
        (
            ($type == 'post') ||
            ($type == 'put')
        )
    ) {
        curl_setopt($ch,CURLOPT_POSTFIELDS, $query_data);
    }

    // Request return instead of echo
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    // Result
    $result = curl_exec($ch);

    // Content type
    $content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);

    if(strpos($content_type, 'json') !== false) {
        $result = json_decode($result, true);
    }

    curl_close($ch);

    return $result;
}