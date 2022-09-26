<?php

namespace YusamHub\CurlExt;

/**
 * Class CurlExtDebug
 * @package YusamHub\CurlExt
 */
class CurlExtDebug extends \YusamHub\CurlExt\CurlExt
{
    /**
     * @var bool
     */
    public bool $isDebugging = false;

    /**
     * @var string|null
     */
    protected ?string $storageLogFile;

    /**
     * @param string $baseUrl
     * @param string|null $storageLogFile
     */
    public function __construct(string $baseUrl = '', ?string $storageLogFile = null)
    {
        $this->storageLogFile = $storageLogFile;
        parent::__construct($baseUrl);
    }

    /**
     * @return string
     */
    protected function prepareFile(): string
    {
        $directory = pathinfo($this->storageLogFile, PATHINFO_DIRNAME);

        if (!file_exists($directory)) {
            @mkdir($directory, 0777, true);
        }

        return $this->storageLogFile;
    }

    /**
     * @param mixed ...$vars
     */
    public function logPrint(...$vars)
    {
        if (empty($this->storageLogFile)) return;

        foreach ($vars as $v) {
            @file_put_contents($this->storageLogFile, print_r($v, true) . PHP_EOL, FILE_APPEND);
        }
    }

    /**
     * @param string $message
     * @return void
     */
    public function logDebug(string $message): void
    {
        $this->logPrint($message);
    }

    /**
     * @param Request $request
     * @return void
     */
    protected function logRequestBefore(Request $request): void
    {
        $this->logDebug(sprintf("REQUEST DATE TIME: %s", date("Y-m-d H:i:s")));
        $this->logDebug(sprintf("REQUEST FULL URL: %s %s%s", $request->getRequestMethod(), $request->getBaseUrl(), $request->getRequestUri()));
        $this->logDebug(sprintf("REQUEST HEADER: %s", print_r($request->getRequestHeaders(), true)));
        $this->logDebug(sprintf("REQUEST PARAMS: %s", print_r($request->getRequestParams(), true)));
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return void
     */
    protected function logResponse(Request $request, Response $response): void
    {
        $this->logDebug(sprintf("REQUEST CURL OPTIONS: %s", print_r($request->getCurlOptions(), true)));
        $this->logDebug(sprintf("RESPONSE HTTP STATUS: %s", $response->getStatusCode()));
        $this->logDebug(sprintf("RESPONSE CURL ERROR: %s", $response->getCurlError()));
        $this->logDebug(sprintf("RESPONSE CURL META: %s", print_r($response->getCurlMeta(), true)));
        $this->logDebug(sprintf("RESPONSE CURL HEADERS: %s", print_r($response->getCurlHeaders(), true)));
        $this->logDebug(sprintf("RESPONSE CURL CONTENT: %s", $response->getCurlContent()));
        $this->logDebug('');
    }
}