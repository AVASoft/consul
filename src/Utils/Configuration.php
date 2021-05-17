<?php


namespace Avasoft\Component\Consul\Utils;

use Avasoft\Component\Consul\Exception\ConsulException;

class Configuration
{
    private const CONFIGURATION_FILENAME = 'config/packages/consul.yaml';

    /**
     * @var string
     */
    private string $dirname;

    /**
     * @param string $dirname
     */
    public function setDirname(string $dirname):void
    {
        $this->dirname = $dirname;
    }

    /**
     * @return string
     */
    public function getDirname():string
    {
        return $this->dirname ?? '';
    }

    /**
     * @return string
     * @throws ConsulException
     */
    public function get():string
    {
        $config = sprintf('%s/%s', $this->getDirname(), self::CONFIGURATION_FILENAME);

        if(!file_exists($config)) {
            throw new ConsulException('Configuration file not found', 500);
        }

        $parameters = \yaml_parse_file($config);

        if(is_array($parameters) === false) {
            throw new ConsulException('Type error: configuration not array', 500);
        }

        if(array_key_exists('parameters', $parameters) === false) {
            throw new ConsulException('Content error: parameters not found', 500);
        }

        $env = $parameters['parameters'];

        return sprintf('%s/%s_%s.json', $env['serviceName'], $env['hostName'], $env['hostPort']);
    }
}