<?php

namespace YusamHub\CurlExt;

/**
 * Class CurlExt
 * @package YusamHub\CurlExt
 */
class Engine
{
    /**
     * @var CurlExt
     */
    private CurlExt $curlExt;

    /**
     * @var Request
     */
    private Request $request;

    /**
     * @var Response
     */
    private Response $response;

    /**
     * CurlExt constructor.
     * @param CurlExt $curlExt
     * @param Request $request
     * @param Response $response
     */
    public function __construct(CurlExt $curlExt, Request $request, Response $response)
    {
        $this->curlExt = $curlExt;
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * @param string $value
     * @param string $key
     */
    private function iconv(string &$value, string $key): void
    {
        $value = iconv($this->curlExt->getEncoding(), CurlExtInterface::ENCODING_UTF8 . '//IGNORE', $value);
    }

    /**
     * @param string $value
     * @param string $key
     */
    private function mb_convert_encoding(string &$value, string $key): void
    {
        $value = mb_convert_encoding($value, CurlExtInterface::ENCODING_UTF8, $this->curlExt->getEncoding());
    }

    /**
     * @param Request $request
     * @return array|string
     */
    private function makeCurlPostFields(Request $request): array|string
    {
        $requestParams = $request->getRequestParams();

        if ($request->isMultipart()) {
            $multipartStream = new \GuzzleHttp\Psr7\MultipartStream($requestParams['multipart']);
            $request->setContentTypeMultipartFormDataBoundary($multipartStream->getBoundary());
            return $multipartStream->getContents();
        }

        if (strtoupper($this->curlExt->getEncoding()) !== CurlExtInterface::ENCODING_UTF8) {
            if (function_exists('iconv')) {
                array_walk_recursive($requestParams, [$this, 'iconv']);
            } elseif (function_exists('mb_convert_encoding')) {
                array_walk_recursive($requestParams, [$this, 'mb_convert_encoding']);
            }
        }

        if ($request->isFileUploading()) {

            $request->setContentTypeMultipartFormData();
            $curlOptions = $request->getCurlOptions();
            $curlOptions['CURLOPT_SAFE_UPLOAD'] = 1;
            $request->setCurlOptions($curlOptions);

            foreach($requestParams as $key => $value) {
                if ($key[0] == '@') {
                    if (file_exists($value)) {
                        $name = substr($key, 1);
                        $requestParams[$name] = curl_file_create($value, mime_content_type($value), basename($value));
                    }
                    unset($requestParams[$key]);
                }
            }

            $request->setRequestParams($requestParams);

            return $requestParams;
        }

        if ($request->isContentTypeApplicationJson()) {
            return (string) json_encode($requestParams, $this->curlExt->getJsonOptions());
        }

        $request->setContentTypeApplicationFromUrlEncoded();
        return http_build_query($requestParams);
    }

    /**
     * @return void
     */
    private function curlInitGet(): void
    {
        $curlOptions = $this->request->getCurlOptions();
        $curlOptions['CURLOPT_HTTPHEADER'] = $this->request->getCurlHttpHeaders($this->curlExt->getCurlHttpHeaderKeyCaseLower());
        $query = http_build_query($this->request->getRequestParams());

        if ($query != "") {
            $query = "?" . $query;
        }

        $curlOptions['CURLOPT_URL'] = $this->request->getBaseUrl() . $this->request->getRequestUri() . $query;

        $this->request->setCurlOptions($curlOptions);
    }

    /**
     * @return void
     */
    private function curlInitAsPost(): void
    {
        $curlOptions = $this->request->getCurlOptions();

        if ($this->request->getRequestMethod() !== CurlExtInterface::METHOD_POST) {
            $curlOptions['CURLOPT_CUSTOMREQUEST'] = $this->request->getRequestMethod();
        }

        $curlOptions['CURLOPT_URL'] = $this->request->getBaseUrl() . $this->request->getRequestUri();
        $curlOptions['CURLOPT_POST'] = 1;
        $curlOptions['CURLOPT_POSTFIELDS'] = $this->makeCurlPostFields($this->request);
        $curlOptions['CURLOPT_HTTPHEADER'] = $this->request->getCurlHttpHeaders($this->curlExt->getCurlHttpHeaderKeyCaseLower());
        $this->request->setCurlOptions($curlOptions);
    }

    /**
     * @return Response
     */
    public function execute(): Response
    {
        $curl = curl_init();

        switch ($this->request->getRequestMethod()) {
            case CurlExtInterface::METHOD_GET:
                $this->curlInitGet();
                break;
            case CurlExtInterface::METHOD_POST:
            case CurlExtInterface::METHOD_PATCH:
            case CurlExtInterface::METHOD_PUT:
            case CurlExtInterface::METHOD_DELETE:
                $this->curlInitAsPost();
                break;
            default:
                throw new \RuntimeException(sprintf("Request method [%s] not supported.", $this->request->getRequestMethod()));
        }

        foreach($this->request->getCurlOptions() as $key => $value) {
            if (defined($key)) {
                curl_setopt($curl, CONSTANT($key), $value);
            }
        }

        $curlContent = false;

        $retryCount = 0;
        do {
            try {
                $curlContent = curl_exec($curl);
            } catch (\Exception $e){
            }
            $retryCount++;
        } while (($curlContent === false) && ($retryCount < $this->curlExt->getRetryCount()));

        $curlHeaders = explode("\n", substr($curlContent, 0, curl_getinfo($curl, CURLINFO_HEADER_SIZE)));
        $curlHeaders = array_diff($curlHeaders, ["","\r"]);

        $this->response->setCurlHeaders($curlHeaders);
        $this->response->setCurlContent(substr($curlContent, curl_getinfo($curl, CURLINFO_HEADER_SIZE)));
        $this->response->setCurlMeta((array) curl_getinfo($curl));
        $this->response->setCurlError(curl_error($curl));

        curl_close($curl);

        return $this->response;
    }
}