<?php


namespace Avasoft\Component\Consul\Utils;

use Avasoft\Component\Consul\Annotation\Pure;
use \CurlHandle;

class RequestParse
{
    /**
     * @var mixed
     */
    private $params;

    #[Pure]
    public function __construct(CurlHandle $handle)
    {
        $this->params = \curl_getinfo($handle, null);
    }

    /**
     * @param string $key
     * @return string
     */
    private function getParameters(string $key): string
    {
        return (array_key_exists($key, $this->params)) ? $this->params[$key] : '';
    }

    /**
     * @return string
     */
    public function getHttpCode(): string
    {
        return $this->getParameters('http_code');
    }
}