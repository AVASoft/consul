<?php


namespace Avasoft\Component\Consul\Controller;

use Avasoft\Component\Consul\Cache;
use Avasoft\Component\Consul\Exception\ConsulException;
use Avasoft\Component\Consul\Entity\Service as Entity;

class Service extends Cache
{
    use RequestTrait;

    private const HEALTH_SERVICE_PATH = '/v1/agent/health/service/name/%s';
    private const REGISTER_SERVICE_PATH = '/v1/agent/service/register';
    private const DEREGISTER_SERVICE_PATH = '/v1/agent/service/deregister/%s';

    /**
     * @param string $serviceName
     * @param bool $updateCache
     * @return string
     */
    public function getAddress(string $serviceName, bool $updateCache = false):string
    {
        $cache = new Cache;

        /**
         * Clear key from APCu
         */
        if($updateCache == true) {
            $cache->delete($serviceName);
        }

        $result = $cache->get($serviceName);

        if($result == false) {

            $config = $this->queryAddress($serviceName);

            $item = [];

            foreach ($config as $i => $service) {
                if ($service['AggregatedStatus'] == 'passing') {
                    $item[] = (array)$service['Service'];
                }
            }
            $item = $item[array_rand($item)];

            $protocol = (in_array($item['Port'], ['443, 8443'])) ? 'https' : 'http';

            $result = sprintf('%s://%s:%s', $protocol, $item['Address'], $item['Port']);

            if(filter_var($result, FILTER_VALIDATE_URL) == true) {
                $cache->add($serviceName, $result, 0, true);
            }
        }

        return $result;
    }

    /**
     * @param string $serviceName
     * @return array
     * @throws ConsulException
     */
    public function queryAddress(string $serviceName):array
    {
        $path = sprintf(self::HEALTH_SERVICE_PATH, $serviceName);

        $result = $this->request()->get($path);
        if(empty($result)) {
            throw new ConsulException(sprintf('Service %s not found to Consul API', $serviceName), 500);
        }
        return $result;
    }


    /**
     * @param Entity $service
     * @return array
     * @throws ConsulException
     */
    public function addService(Entity $service): array
    {

        $config = [
            'Id' => $service->getId(),
            'Name' => $service->getName(),
            'Tags' => $service->getTags(),
            'Address' => $service->getAddress(),
            'Port' => $service->getPort(),
            'Checks' => $service->getChecks()
        ];

        return $this->request()->put(self::REGISTER_SERVICE_PATH, $config);
    }

}