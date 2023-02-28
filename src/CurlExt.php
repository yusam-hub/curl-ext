<?php

namespace YusamHub\CurlExt;

/**
 * Class CurlExt
 * @package YusamHub\CurlExt
 */
class CurlExt implements CurlExtInterface
{
    /**
     * @var int[]
     */
    public array $defaultCurlOptions = [
        'CURLOPT_SSL_VERIFYHOST' => 0,
        'CURLOPT_SSL_VERIFYPEER' => 0,
    ];

    /**
     * @var string
     */
    protected string $baseUrl;

    /**
     * @var int
     */
    protected int $timeOutSeconds = 0;

    /**
     * @var int
     */
    protected int $retryCount = 1;

    /**
     * @var string
     */
    protected string $encoding = self::ENCODING_UTF8;

    /**
     * @var int
     */
    protected int $jsonOptions = 0;

    /**
     * @var Request|null
     */
    protected ?Request $lastRequest = null;

    /**
     * @var Response|null
     */
    protected ?Response $lastResponse = null;

    /**
     * @var null|bool
     */
    protected ?bool $curlHttpHeaderKeyCaseLower = true;

    /**
     * Connector constructor.
     * @param string $baseUrl
     */
    public function __construct(string $baseUrl = '')
    {
        $this->baseUrl = $baseUrl;
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
     * @return void
     */
    public function setBaseUrl(string $value): void
    {
        $this->baseUrl = $value;
    }

    /**
     * @return int
     */
    public function getTimeOutSeconds(): int
    {
        return $this->timeOutSeconds;
    }

    /**
     * @param int $value
     * @return void
     */
    public function setTimeOutSeconds(int $value): void
    {
        $this->timeOutSeconds = $value;
    }

    /**
     * @return int
     */
    public function getRetryCount(): int
    {
        return $this->retryCount;
    }

    /**
     * @param int $value
     * @return void
     */
    public function setRetryCount(int $value): void
    {
        $this->retryCount = $value;
    }

    /**
     * @return string
     */
    public function getEncoding(): string
    {
        return $this->encoding;
    }

    /**
     * @param string $value
     * @return void
     */
    public function setEncoding(string $value): void
    {
        $this->encoding = $value;
    }

    /**
     * @return int
     */
    public function getJsonOptions(): int
    {
        return $this->jsonOptions;
    }

    /**
     * @return Request|null
     */
    public function getLastRequest(): ?Request
    {
        return $this->lastRequest;
    }

    /**
     * @return Response|null
     */
    public function getLastResponse(): ?Response
    {
        return $this->lastResponse;
    }

    /**
     * @param int $value
     * @return void
     */
    public function setJsonOptions(int $value): void
    {
        $this->jsonOptions = $value;
    }

    /**
     * @return bool|null
     */
    public function getCurlHttpHeaderKeyCaseLower(): ?bool
    {
        return $this->curlHttpHeaderKeyCaseLower;
    }

    /**
     * @param bool|null $value
     */
    public function setCurlHttpHeaderKeyCaseLower(?bool $value): void
    {
        $this->curlHttpHeaderKeyCaseLower = $value;
    }

    /**
     * @param string $requestUri
     * @param array $requestParams
     * @param array $requestHeaders
     * @return Response|null
     */
    public function get(string $requestUri, array $requestParams = [], array $requestHeaders = []): ?Response
    {
        return $this->request(self::METHOD_GET, $requestUri, $requestParams, $requestHeaders);
    }

    /**
     * @param string $requestUri
     * @param array $requestParams
     * @param array $requestHeaders
     * @return Response|null
     */
    public function post(string $requestUri, array $requestParams = [], array $requestHeaders = []): ?Response
    {
        return $this->request(self::METHOD_POST, $requestUri, $requestParams, $requestHeaders);
    }

    /**
     * @param string $requestUri
     * @param array $requestParams
     * @param array $requestHeaders
     * @return Response|null
     */
    public function put(string $requestUri, array $requestParams = [], array $requestHeaders = []): ?Response
    {
        return $this->request(self::METHOD_PUT, $requestUri, $requestParams, $requestHeaders);
    }

    /**
     * @param string $requestUri
     * @param array $requestParams
     * @param array $requestHeaders
     * @return Response|null
     */
    public function patch(string $requestUri, array $requestParams = [], array $requestHeaders = []): ?Response
    {
        return $this->request(self::METHOD_PATCH, $requestUri, $requestParams, $requestHeaders);
    }

    /**
     * @param string $requestUri
     * @param array $requestParams
     * @param array $requestHeaders
     * @return Response|null
     */
    public function delete(string $requestUri, array $requestParams = [], array $requestHeaders = []): ?Response
    {
        return $this->request(self::METHOD_DELETE, $requestUri, $requestParams, $requestHeaders);
    }

    /**
     * @param Request $request
     * @return void
     */
    protected function logRequestBefore(Request $request): void
    {

    }

    /**
     * @param Request $request
     * @param Response $response
     * @return void
     */
    protected function logResponse(Request $request, Response $response): void
    {

    }

    /**
     * @param Request $request
     * @return Request
     */
    protected function prepareRequest(Request $request): Request
    {
        return $request;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return void
     */
    protected function curlExecute(Request $request, Response $response): void
    {
        $curlExt = new Engine($this, $request, $response);
        $curlExt->execute();
    }

    /**
     * @param string $requestMethod
     * @param string $requestUri
     * @param array $requestParams
     * @param array $requestHeaders
     * @return Response|null
     */
    public function request(string $requestMethod, string $requestUri, array $requestParams = [], array $requestHeaders = []): ?Response
    {
        $this->lastResponse = null;

        $curlOptions = array_merge([
            'CURLOPT_HEADER' => 1,
            'CURLOPT_RETURNTRANSFER' => 1,
        ], $this->defaultCurlOptions);

        if ($this->timeOutSeconds > 0) {
            $curlOptions['CURLOPT_TIMEOUT'] = $this->timeOutSeconds;
        }

        $this->lastRequest = $this->prepareRequest(
            new Request($this->baseUrl, $requestMethod, $requestUri, $requestParams, $requestHeaders, $curlOptions)
        );

        $this->logRequestBefore($this->lastRequest);

        $this->lastResponse = new Response();

        $this->curlExecute($this->lastRequest, $this->lastResponse);

        $this->logResponse($this->lastRequest, $this->lastResponse);

        return $this->lastResponse;
    }
}