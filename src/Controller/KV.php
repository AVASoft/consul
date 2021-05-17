<?php


namespace Avasoft\Component\Consul\Controller;


use Avasoft\Component\Consul\Cache;
use Avasoft\Component\Consul\Exception\ConsulException;

class KV extends Cache
{
    use RequestTrait;


    /**
     * @param string $key
     * @param bool $fromCache
     * @return array
     * @throws ConsulException
     */
    public function find(string $key, bool $fromCache = false):array
    {
        $response = $this->request()->get(
            sprintf('/v1/kv/%s', $key)
        );

        $response = reset($response);

        $result = [];

        if( is_array($response) && array_key_exists('Value', $response)) {
            $result['key'] = $response['Key'];
            $toString = base64_decode($response['Value']);
            $result['value'] = json_decode($toString, true);
            /**
             * Add to cache
             */
            $this->add($key, $result);
        }

        return $result;
    }

    public function create()
    {

    }

    public function update()
    {

    }


}