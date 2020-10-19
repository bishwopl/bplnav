<?php

namespace BplNav\Service\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class NavManagerFactory implements FactoryInterface{
    /**
     * 
     * @param ContainerInterface $container
     * @param type $requestedName
     * @param array $options
     * @return \BplNav\Service\NavManager
     */
    public function __invoke(ContainerInterface $container, $requestedName, Array $options = null) {
        $navigation =  new \BplNav\Service\NavManager();
        return $navigation->createService($container);
    }
}