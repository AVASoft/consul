<?php


namespace Avasoft\Component\Consul\Utils\Tests;


use Avasoft\Component\Consul\Exception\ConsulException;
use Avasoft\Component\Consul\Utils\Configuration;
use PHPUnit\Framework\TestCase;

class ConfigurationTest extends TestCase
{
    /**
     * @return Configuration
     */
    public function testRunConfiguration(): Configuration
    {
        $configuration = new Configuration;
        $this->assertEmpty($configuration->getDirname());
        return $configuration;
    }

    /**
     * @param Configuration $configuration
     * @return Configuration
     * @throws ConsulException
     *
     * @depends testRunConfiguration
     */
    public function testExceptionFileNotFound(Configuration $configuration):Configuration
    {
        $this->expectExceptionMessage('Configuration file not found');
        $configuration->get();
    }

    /**
     * @param Configuration $configuration
     * @return Configuration
     *
     * @depends testRunConfiguration
     */
    public function testAddDirname(Configuration $configuration):Configuration
    {
        $component_direction = dirname(__DIR__, 2);
        $configuration->setDirname($component_direction);
        $this->assertSame($component_direction, $configuration->getDirname());

        return $configuration;
    }

    /**
     * @param Configuration $configuration
     * @throws ConsulException
     *
     * @depends testAddDirname
     */
    public function testGetConfig(Configuration $configuration):void
    {
        $config = $configuration->get();
        $this->assertIsString($config);
        $this->assertSame('SERVICE/avasoft-dev_8080.json',$config);
    }
}