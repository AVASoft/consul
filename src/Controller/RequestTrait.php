<?php


namespace Avasoft\Component\Consul\Controller;


use Avasoft\Component\Consul\Service\Client;

trait RequestTrait
{

    public function request()
    {
        return new Client;
    }
}