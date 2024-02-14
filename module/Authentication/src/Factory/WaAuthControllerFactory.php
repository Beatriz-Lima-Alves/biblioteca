<?php
namespace Authentication\Factory;

use Authentication\Controller\WaAuthController;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class WaAuthControllerFactory implements FactoryInterface
{

    /**
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new WaAuthController($container);
    }

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new WaAuthController($serviceLocator);
    }
}