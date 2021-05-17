<?php


namespace Avasoft\Component\Consul;


use Avasoft\Component\Consul\Exception\ConsulException;

/**
 * Class Cache
 * @package Avasoft\Component\Consul
 *
 *
 */
class Cache
{
    /**
     * Cache constructor.
     * @throws ConsulException
     * @var bool $off
     */
    public function __construct(bool $off = true)
    {
        if(\apcu_enabled() == false && $off == false) {
            throw new ConsulException('APCu not possible to use in this environment', 500);
        }
    }

    /**
     * @param string $key
     * @return bool|array|string
     */
    public function get(string $key = ''):bool|array|string
    {
        $response = false;
        if(\apcu_exists($key)) {
            $response = \apcu_fetch($key);
        }
        return $response;
    }

    /**
     * @param string $key
     * @param mixed $var
     * @param int $ttl
     * @param bool $rewrite
     *
     * @return bool
     */
    public function add(string $key, mixed $var, int $ttl = 0, bool $rewrite = false):bool
    {
        return ($rewrite == true) ? \apcu_store($key, $var, $ttl) : \apcu_add($key, $var, $ttl);
    }

    /**
     * Clear all cache from APCu system
     */
    public function clear():void
    {
        \apcu_clear_cache();
    }

    /**
     * Delete cache by key(s), return true/false(array keys)
     *
     * @param string|array $key
     * @return array|bool
     */
    public function delete(string|array $key): array|bool
    {
        return \apcu_delete($key);
    }

    /**
     * Delete cache by regex
     *
     * @param string $regex
     * @return array|bool
     */
    public function iteratorDelete(string $regex): array|bool
    {
        return \apcu_delete(new \APCuIterator($regex));
    }

}