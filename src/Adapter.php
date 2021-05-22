<?php


namespace Avasoft\Component\Consul;


use Avasoft\Component\Consul\Annotation\Pure;
use Avasoft\Component\Consul\Controller\KV;
use Avasoft\Component\Consul\Controller\Service;
use Avasoft\Component\Consul\Exception\ConsulException;
use Avasoft\Component\Consul\Service\Client;
use Avasoft\Component\Consul\Utils\Configuration;
use Avasoft\Component\Consul\Utils\DefaultCheckService;

class Adapter
{
    /**
     * @var string
     */
    private string $dirname;

    /**
     * @var Client|null
     */
    private Client|null $client;

    /**
     * @return string
     */
    public function getDirname(): string
    {
        return $this->dirname;
    }

    /**
     * @param string $dirname
     */
    public function setDirname(string $dirname): void
    {
        $this->dirname = $dirname;
    }

    /**
     * @return Client|null
     */
    public function getClient(): Client|null
    {
        return $this->client;
    }

    /**
     * @param Client $client
     */
    public function setClient(Client $client): void
    {
        $this->client = $client;
    }

    /**
     * @param bool $offCache
     * @return KV
     * @throws Exception\ConsulException
     */
    #[Pure] public function KVStoreEndpoints(bool $offCache = true):KV
    {
        return new KV($offCache);
    }
    /**
     * @param bool $offCache
     * @return Service
     * @throws Exception\ConsulException
     */
    #[Pure] public function Service(bool $offCache = true):Service
    {
        return new Service($offCache);
    }

    public function getConfig(): Configuration
    {
        $config = new Configuration;
        if(!empty($this->getDirname())) {
            $config->setDirname($this->getDirname());
        }

        return $config;
    }

    /**
     * @return array
     * @throws ConsulException
     */
    public function getLocalConfig():array
    {
        $keyword = $this->getConfig()->get();


        return $this->KVStoreEndpoints()->find($keyword);
    }
    /**
     * @return array
     * @throws ConsulException
     */
    public function addService(): array
    {
        $sm = new DefaultCheckService;
        $config = self::getLocalConfig()['value'];
        $config = json_decode($config, true);
        if(json_last_error() != 0) {
            throw new ConsulException('Error JSON parse Consul config', 500);
        }
        $sm->setEnv($config);


        $service = $this->Service()->addService($sm->getServiceCheck());


        return [$service];
    }
}