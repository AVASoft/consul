<?php


namespace Avasoft\Component\Consul;


use Avasoft\Component\Consul\Annotation\Pure;
use Avasoft\Component\Consul\Controller\KV;
use Avasoft\Component\Consul\Service\Client;
use Avasoft\Component\Consul\Utils\Configuration;

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

    public function getConfig(): Configuration
    {
        $config = new Configuration;
        if(!empty($this->getDirname())) {
            $config->setDirname($this->getDirname());
        }

        return $config;
    }
}