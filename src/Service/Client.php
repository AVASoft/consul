<?php


namespace Avasoft\Component\Consul\Service;

use Avasoft\Component\Consul\Utils\RequestParse;
use Avasoft\Component\Consul\Exception\ConsulException;
use \CurlHandle;

/**
 * Class Client
 * @package Avasoft\Component\Consul\Service
 */
class Client
{
    private const CONSUL_PROTOCOL = 'http';
    private const CONSUL_HOST = '172.255.232.11';//'172.17.0.1';
    private const CONSUL_PORT = '8500';

    /**
     * @var string
     */
    private string $address;

    /**
     * @var CurlHandle
     */
    private CurlHandle $http;

    /**
     * @var RequestParse
     */
    private RequestParse $parse;

    public function __construct()
    {
        /**
         * Initialize a HTTP-Client session
         * @link https://www.php.net/manual/en/function.curl-init.php
         */
        $this->http = \curl_init();
    }

    /**
     * @param string $address
     */
    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        if (empty($this->address)) {
            $this->address = sprintf(
                '%s://%s:%s',
                self::CONSUL_PROTOCOL, self::CONSUL_HOST, self::CONSUL_PORT
            );
        }

        return $this->address;
    }

    /**
     * @return RequestParse
     */
    public function getParse(): RequestParse
    {
        return $this->parse;
    }

    /**
     * @param RequestParse $parse
     */
    public function setParse(RequestParse $parse): void
    {
        $this->parse = $parse;
    }

    /**
     * @param string $path
     * @return array
     * @throws ConsulException
     */
    public function get(string $path):array
    {
        return $this->request('GET', $path);
    }

    /**
     * @param string $path
     * @param array $parameters
     * @return array
     * @throws ConsulException
     */
    public function put(string $path, array $parameters = []):array
    {
        return $this->request('PUT', $path, $parameters);
    }

    /**
     * @param string $path
     * @param array $parameters
     * @return array
     * @throws ConsulException
     */
    public function post(string $path, array $parameters = []):array
    {
        return $this->request('POST', $path, $parameters);
    }

    /**
     * @param string $method
     * @param string $path
     * @param array $parameters
     * @throws ConsulException
     *
     * @return array
     */
    public function request(string $method, string $path = '/', array $parameters = []):array
    {
        $uri = $this->getAddress() . $path;

        if(filter_var($uri, FILTER_VALIDATE_URL) == false) {
            throw new ConsulException(sprintf('URI Address "%s" is not valid', $uri), 500);
        }

        /**
         * Add cURL options
         * @link https://www.php.net/manual/en/function.curl-setopt.php
         */
        \curl_setopt($this->http, CURLOPT_URL, $uri);
        \curl_setopt($this->http, CURLOPT_RETURNTRANSFER, true);
        \curl_setopt($this->http, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

        /**
         * Switch HTTP-methods
         */
        switch($method) {
            case 'PUT':
                if(is_array($parameters)) {
                    $parameters = json_encode($parameters, JSON_PRETTY_PRINT);
                }
                \curl_setopt($this->http, CURLOPT_CUSTOMREQUEST, "PUT");
                \curl_setopt($this->http, CURLOPT_POSTFIELDS, $parameters);
                break;
            case 'POST':
                if(is_array($parameters)) {
                    $parameters = json_encode($parameters);
                }
                \curl_setopt( $this->http, CURLOPT_POSTFIELDS, $parameters);
                break;
            case 'GET':
            default:
                \curl_setopt($this->http, CURLOPT_HTTPGET, true);
                break;
        }

        /**
         * Request
         *
         * @see https://www.php.net/manual/en/function.curl-exec.php
         */
        $result = \curl_exec($this->http);

        /**
         * Get request info
         */
        $parse = new RequestParse($this->http);
        $this->setParse($parse);

        /**
         * @link @link https://php.net/manual/en/function.curl-error.php
         */
        if(\curl_errno($this->http) == 7) {
            throw new ConsulException(\curl_error($this->http), 500);
        }

        /**
         * Close a HTTP-Client session
         *
         * @see https://www.php.net/manual/en/function.curl-close.php
         */
        \curl_close($this->http);

        if(empty($result)) {
            $result = [];
        } else {
            $result = json_decode($result, true);
            if (json_last_error() != JSON_ERROR_NONE) {
                $result = [$result];
//                throw new ConsulException('Error response json decode', 500);
            }
        }

        return $result;
    }
}