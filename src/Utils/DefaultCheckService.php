<?php


namespace Avasoft\Component\Consul\Utils;

use Avasoft\Component\Consul\Entity\Check;
use Avasoft\Component\Consul\Entity\Service;
use Avasoft\Component\Consul\Exception\ConsulException;

class DefaultCheckService
{
    /**
     * @var array
     */
    private array $env;

    /**
     * @param array $env
     */
    public function setEnv(array $env): void
    {
        $this->env = $env;
    }

    /**
     * @return array
     * @throws ConsulException
     */
    public function getEnv(): array
    {
        if(empty($this->env)) {
            throw new ConsulException('Install ENV configuration for Consul service', 500);
        }

        return $this->env;
    }

    /**
     * @return Service
     * @throws ConsulException
     */
    public function getServiceCheck():Service
    {
        /**
         * Get consul configuration
         */
        $env = $this->getEnv();
        $config = $env['consul'];

        $service = new Service;
        $service->setId($config['ID']);
        $service->setName($config['Name']);
        $service->setAddress($config['Address']);
        $service->setPort($config['Port']);
        $service->setTags(['primary','v1','php']);
        $service->addCheck($this->createCheck());

        return $service;
    }

    /**
     * @return Check
     * @throws ConsulException
     */
    private function createCheck():Check
    {
        $env = $this->getEnv();

        $config =  $env['consul'];
        $check = new Check;
        $check->setName('HTTP API on port 80');

        $http = sprintf('http://%s:%s/consul/check', $config['Address'], $config['Port']);
        $check->setHttp($http);
        $check->setTLSSkipVerify(false);
        $check->setMethod("POST");
        $check->setHeader([
            "Content-type" => ["application/json"]
        ]);

        $body = json_encode(["method"=>"health"], JSON_PRETTY_PRINT);
        $check->setBody($body);
        $check->setInterval("10s");
        $check->setTimeout("1s");
        $check->setDeregisterCriticalServiceAfter("90m");

        return $check;
    }
}