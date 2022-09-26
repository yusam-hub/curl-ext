<?php

namespace YusamHub\CurlExt;

/**
 * Class Response
 * @package YusamHub\CurlExt
 */
class Response
{
    /**
     * @var array|null
     */
    protected ?array $toArraySaved = null;

    /**
     * @var array
     */
    protected array $curlHeaders;

    /**
     * @var string
     */
    protected string $curlContent;

    /**
     * @var array
     */
    protected array $curlMeta;

    /**
     * @var string
     */
    protected string $curlError;

    /**
     * Response constructor.
     * @param array $curlHeaders
     * @param string $curlContent
     * @param array $curlMeta
     * @param string $curlError
     */
    public function __construct(array $curlHeaders = [], string $curlContent = '', array $curlMeta = [], string $curlError = '')
    {
        $this->curlHeaders = $curlHeaders;
        $this->curlContent = $curlContent;
        $this->curlMeta = $curlMeta;
        $this->curlError = $curlError;
    }

    /**
     * @return array
     */
    public function getCurlHeaders(): array
    {
        return $this->curlHeaders;
    }

    /**
     * @param array $value
     */
    public function setCurlHeaders(array $value): void
    {
        $this->curlHeaders = $value;
    }

    /**
     * @return string
     */
    public function getCurlContent(): string
    {
        return $this->curlContent;
    }

    /**
     * @param string $value
     */
    public function setCurlContent(string $value): void
    {
        $this->curlContent = $value;
        $this->toArraySaved = null;
    }

    /**
     * @return array
     */
    public function getCurlMeta(): array
    {
        return $this->curlMeta;
    }

    /**
     * @param array $value
     */
    public function setCurlMeta(array $value): void
    {
        $this->curlMeta = $value;
    }

    /**
     * @return string
     */
    public function getCurlError(): string
    {
        return $this->curlError;
    }

    /**
     * @param string $value
     */
    public function setCurlError(string $value): void
    {
        $this->curlError = $value;
    }

    /**
     * @return bool
     */
    public function isContentTypeJson(): bool
    {
        return isset($this->curlMeta['content_type']) && stristr($this->curlMeta['content_type'], CurlExtInterface::CONTENT_TYPE_APPLICATION_JSON);
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        if (isset($this->curlMeta['http_code'])) {
            return (int) $this->curlMeta['http_code'];
        }

        return -1;
    }

    /**
     * @param int $statusCode
     * @return bool
     */
    public function isStatusCode(int $statusCode): bool
    {
        return $this->getStatusCode() === $statusCode;
    }

    /**
     * @param int $jsonDecodeFlags
     * @return array
     */
    public function toArray(int $jsonDecodeFlags = 0): array
    {
        if ($this->isContentTypeJson()) {

            if (is_null($this->toArraySaved)) {
                $this->toArraySaved = (array) @json_decode($this->curlContent, true, $jsonDecodeFlags);
            }

            return $this->toArraySaved;
        }

        return [];
    }
}