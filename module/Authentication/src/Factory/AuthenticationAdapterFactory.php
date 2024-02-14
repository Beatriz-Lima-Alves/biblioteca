<?php
namespace Authentication\Factory;

use Authentication\Service\AuthenticationAdapter;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class AuthenticationAdapterFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new AuthenticationAdapter($container);
    }

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new AuthenticationAdapter($serviceLocator);
    }
}