<?php

namespace YusamHub\CurlExt;

/**
 * Class Request
 * @package YusamHub\CurlExt
 */
class Request
{
    /**
     * @var string
     */
    protected string $baseUrl;

    /**
     * @var string
     */
    protected string $requestMethod;

    /**
     * @var string
     */
    protected string $requestUri;

    /**
     * @var array
     */
    protected array $requestParams;

    /**
     * @var array
     */
    protected array $requestHeaders;

    /**
     * @var array
     */
    protected array $curlOptions;

    /**
     * Request constructor.
     * @param string $baseUrl
     * @param string $requestMethod
     * @param string $requestUri
     * @param array $requestParams
     * @param array $requestHeaders
     * @param array $curlOptions
     */
    public function __construct(
        string $baseUrl = '',
        string $requestMethod = '',
        string $requestUri = '',
        array  $requestParams = [],
        array  $requestHeaders = [],
        array  $curlOptions = []
    )
    {
        $this->baseUrl = $baseUrl;
        $this->requestMethod = strtoupper($requestMethod);
        $this->requestUri = $requestUri;
        $this->requestParams = $requestParams;
        $this->requestHeaders = $requestHeaders;
        $this->curlOptions = $curlOptions;
    }

    /**
     * @return string
     */
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * @param string $value
     */
    public function setBaseUrl(string $value): void
    {
        $this->baseUrl = $value;
    }

    /**
     * @return string
     */
    public function getRequestMethod(): string
    {
        return $this->requestMethod;
    }

    /**
     * @param string $value
     */
    public function setRequestMethod(string $value): void
    {
        $this->requestMethod = $value;
    }

    /**
     * @return string
     */
    public function getRequestUri(): string
    {
        return $this->requestUri;
    }

    /**
     * @param string $value
     */
    public function setRequestUri(string $value): void
    {
        $this->requestUri = $value;
    }

    /**
     * @return array
     */
    public function getRequestParams(): array
    {
        return $this->requestParams;
    }

    /**
     * @param array $value
     */
    public function setRequestParams(array $value): void
    {
        $this->requestParams = $value;
    }

    /**
     * @return array
     */
    public function getRequestHeaders(): array
    {
        return $this->requestHeaders;
    }

    /**
     * @param array $requestHeaders
     */
    public function setRequestHeaders(array $requestHeaders): void
    {
        $this->requestHeaders = $requestHeaders;
    }

    /**
     * @return array
     */
    public function getCurlOptions(): array
    {
        return $this->curlOptions;
    }

    /**
     * @param array $curlOptions
     */
    public function setCurlOptions(array $curlOptions): void
    {
        $this->curlOptions = $curlOptions;
    }

    /**
     * @param bool $keyCaseLower
     * @return array
     */
    public function getCurlHttpHeaders($keyCaseLower = true): array
    {
        $out = [];
        foreach($this->requestHeaders as $key => $value) {
            if ($keyCaseLower === true) {
                $string = strtolower($key) . ": " . $value;
            } elseif ($keyCaseLower === false) {
                $string = strtoupper($key) . ": " . $value;
            } else {
                $string = $key . ": " . $value;
            }
            $out[] = $string;
        }
        return $out;
    }

    /**
     * @return void
     */
    public function setContentTypeApplicationJson(): void
    {
        $this->requestHeaders[CurlExtInterface::HEADER_CONTENT_TYPE] = CurlExtInterface::CONTENT_TYPE_APPLICATION_JSON;
    }

    /**
     * @return void
     */
    public function setContentTypeMultipartFormData(): void
    {
        $this->requestHeaders[CurlExtInterface::HEADER_CONTENT_TYPE] = CurlExtInterface::CONTENT_TYPE_MULTIPART_FORM_DATA;
    }

    /**
     * @param $boundary
     * @return void
     */
    public function setContentTypeMultipartFormDataBoundary($boundary): void
    {
        $this->requestHeaders[CurlExtInterface::HEADER_CONTENT_TYPE] = CurlExtInterface::CONTENT_TYPE_MULTIPART_FORM_DATA . '; boundary=' . $boundary;
    }

    /**
     * @return void
     */
    public function setContentTypeApplicationFromUrlEncoded(): void
    {
        $this->requestHeaders[CurlExtInterface::HEADER_CONTENT_TYPE] = CurlExtInterface::CONTENT_TYPE_APPLICATION_FORM_URLENCODED;
    }

    /**
     * @return bool
     */
    public function isContentTypeApplicationJson(): bool
    {
        return count(
                array_filter(
                    $this->getRequestHeaders(),
                    function($v, $k){
                        return (strtolower($k) === CurlExtInterface::HEADER_CONTENT_TYPE) && (strtolower($v) === CurlExtInterface::CONTENT_TYPE_APPLICATION_JSON);
                    },
                    ARRAY_FILTER_USE_BOTH)
            ) === 1;
    }

    /**
     * @return bool
     */
    public function isContentTypeMultipartFormData(): bool
    {
        return count(
                array_filter(
                    $this->getRequestHeaders(),
                    function($v, $k){
                        return (strtolower($k) === CurlExtInterface::HEADER_CONTENT_TYPE) && (strtolower($v) === CurlExtInterface::CONTENT_TYPE_MULTIPART_FORM_DATA);
                    },
                    ARRAY_FILTER_USE_BOTH)
            ) === 1;
    }

    /**
     * @return bool
     */
    public function isFileUploading(): bool
    {
        return count(array_filter($this->getRequestParams(), function($v, $k){
            return is_string($k) && isset($k[0]) && ($k[0] == '@');
        }, ARRAY_FILTER_USE_BOTH)) > 0;
    }

    /**
     * @return bool
     */
    public function isMultipart(): bool
    {
        return count(array_filter($this->getRequestParams(), function($v, $k){
                return ($k === 'multipart') && is_array($v);
            }, ARRAY_FILTER_USE_BOTH)) > 0;
    }
}