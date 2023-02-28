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
     * @var bool
     */
    private bool $logResponseContentEnable = true;

    /**
     * @param string $baseUrl
     * @param string|null $storageLogFile
     */
    public function __construct(string $baseUrl = '', ?string $storageLogFile = null)
    {
        $this->storageLogFile = $storageLogFile;

        $this->mkdirIfNotExistsRecursive();

        parent::__construct($baseUrl);
    }

    /**
     * @return bool
     */
    public function isLogResponseContentEnable(): bool
    {
        return $this->logResponseContentEnable;
    }

    /**
     * @param bool $logResponseContentEnable
     */
    public function setLogResponseContentEnable(bool $logResponseContentEnable): void
    {
        $this->logResponseContentEnable = $logResponseContentEnable;
    }

    /**
     * @return void
     */
    protected function mkdirIfNotExistsRecursive(): void
    {
        if (empty($this->storageLogFile)) return;

        $directory = pathinfo($this->storageLogFile, PATHINFO_DIRNAME);

        if (!file_exists($directory)) {
            @mkdir($directory, 0777, true);
        }
    }

    /**
     * @param ...$vars
     * @return void
     */
    protected function logPrint(...$vars): void
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
    protected function logDebug(string $message): void
    {
        $this->logPrint($message);
    }

    /**
     * @param Request $request
     * @return void
     */
    protected function logRequestBefore(Request $request): void
    {
        if (!$this->isDebugging) return;

        $this->logDebug('BEGIN-------------------------------------------------------------------------------------');
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
        if (!$this->isDebugging) return;

        $this->logDebug(sprintf("REQUEST CURL OPTIONS: %s", print_r($request->getCurlOptions(), true)));

        $this->logDebug('------------------------------------------------------------------------------------------');
        $this->logDebug(sprintf("RESPONSE HTTP STATUS: %s", $response->getStatusCode()));
        $this->logDebug(sprintf("RESPONSE CURL ERROR: %s", $response->getCurlError()));
        $this->logDebug(sprintf("RESPONSE CURL META: %s", print_r($response->getCurlMeta(), true)));
        $this->logDebug(sprintf("RESPONSE CURL HEADERS: %s", print_r($response->getCurlHeaders(), true)));

        if ($this->logResponseContentEnable) {
            $this->logDebug(sprintf("RESPONSE CURL CONTENT: %s", $response->getCurlContent()));
        } else {
            $this->logDebug(sprintf("RESPONSE CURL CONTENT LEN: %d", strlen($response->getCurlContent())));
        }
        $this->logResponseContentEnable = true;

        $this->logDebug('END---------------------------------------------------------------------------------------');
    }
}