<?php


namespace Avasoft\Component\Consul\Utils;


class DoctrineConnection
{
    /**
     * @param string $name
     * @param array $configure
     *
     * @return array
     */
    public function add(string $name, array $configure):array
    {
        $connection['dbname'] = $configure['name'];
        $connection['host'] = $configure['host'];
        $connection['port'] = $configure['port'];
        $connection['user'] = $configure['user'];
        $connection['password'] = $configure['password'];
        $connection['driver'] = $configure['driver'];
        $connection['charset'] = 'UTF8';
        $connection['server_version'] = $configure['version'];

        return $connection;
    }

    /**
     * @return array
     */
    public function runner():array
    {
        $configure = [
            'dbal' => [
                'default_connection' => 'default',
                'connections' => []
            ],
            'orm'  => []
        ];
        $connections = $_SERVER['DATABASE'];
        foreach($connections as $i=>$connection) {
            $name = $connection['connection'];
            $configure['dbal']['connections'][$name] = $this->add($name, $connection);
        }

        return $configure;
    }
}