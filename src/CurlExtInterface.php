<?php

namespace YusamHub\CurlExt;

/**
 * interface CurlExtInterface
 * @package YusamHub\CurlExt
 */
interface CurlExtInterface
{
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';
    const METHOD_PATCH = 'PATCH';

    const ENCODING_UTF8 = 'UTF-8';

    const HEADER_ACCEPT = 'accept';
    const HEADER_CONTENT_TYPE = 'content-type';
    const CONTENT_TYPE_APPLICATION_JSON = 'application/json';
    const CONTENT_TYPE_APPLICATION_FORM_URLENCODED = 'application/x-www-form-urlencoded';
    const CONTENT_TYPE_MULTIPART_FORM_DATA = 'multipart/form-data';

    public function getBaseUrl(): string;
    public function setBaseUrl(string $value): void;
    public function getTimeOutSeconds(): int;
    public function setTimeOutSeconds(int $value): void;
    public function getRetryCount(): int;
    public function setRetryCount(int $value): void;
    public function getEncoding(): string;
    public function setEncoding(string $value): void;
    public function getJsonOptions(): int;
    public function setJsonOptions(int $value): void;

    public function get(string $requestUri, array $requestParams = [], array $requestHeaders = []);
    public function post(string $requestUri, array $requestParams = [], array $requestHeaders = []);
    public function put(string $requestUri, array $requestParams = [], array $requestHeaders = []);
    public function delete(string $requestUri, array $requestParams = [], array $requestHeaders = []);
    public function patch(string $requestUri, array $requestParams = [], array $requestHeaders = []);
}