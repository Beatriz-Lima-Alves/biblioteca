<?php
namespace Authentication\Factory;

use Authentication\Service\WaAuthenticationAdapter;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class WaAuthenticationAdapterFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new WaAuthenticationAdapter($container);
    }

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new WaAuthenticationAdapter($serviceLocator);
    }
}