<?php

if (! function_exists('curl_ext_parse_url')) {

    function curl_ext_parse_url(string $url, &$baseUrl, &$requestUri): void
    {
        $parsed_url = parse_url($url);

        $scheme = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '';
        $host = $parsed_url['host'] ?? '';
        $port = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : '';
        $user = $parsed_url['user'] ?? '';
        $pass = isset($parsed_url['pass']) ? ':' . $parsed_url['pass']  : '';
        $pass = ($user || $pass) ? "$pass@" : '';
        $path = $parsed_url['path'] ?? '';
        $query = isset($parsed_url['query']) ? '?' . $parsed_url['query'] : '';
        $fragment = isset($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : '';

        $baseUrl =  $scheme . $user . $pass . $host . $port;
        $requestUri = $path . $query . $fragment;
    }
}

if (! function_exists('curl_ext_parse_url_get_base')) {

    function curl_ext_parse_url_get_base(string $url): string
    {
        curl_ext_parse_url($url, $baseUrl, $requestUri);
        return $baseUrl;
    }
}

if (! function_exists('curl_ext_parse_url_get_request_uri')) {

    function curl_ext_parse_url_get_request_uri(string $url): string
    {
        curl_ext_parse_url($url, $baseUrl, $requestUri);
        return $requestUri;
    }
}

if (! function_exists('curl_ext_time_utc')) {

    /**
     * @param int|null $timestamp
     * @return int
     */
    function curl_ext_time_utc(?int $timestamp = null): int
    {
        $utc = gmdate("Y-m-d H:i:s", $timestamp);
        return (int) strtotime($utc);
    }
}