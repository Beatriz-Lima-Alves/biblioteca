<?php
namespace Authentication\Factory;

use Authentication\Service\AuthorizationService;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class AuthorizationServiceFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new AuthorizationService($container);
    }

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new AuthorizationService($serviceLocator);
    }
}