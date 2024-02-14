<?php

namespace Useful\Controller;

use Composer\Console\Application;
use Interop\Container\ContainerInterface;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Hydrator\ArraySerializable as ReflectionHydrator;
use Laminas\Stdlib\ArrayObject;

/**
 * Class ContainerController
 * @package Useful\Controller
 * Author Claudio
 * Date 07/12/2020
 * Time 15:37
 */
class ContainerController
{
    /**
     * @var \Laminas\Log\Logger
     */
    protected $logger;

    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        // $this->logger = $this->container->get('MyLogger');
        $writer = new \Laminas\Log\Writer\Stream('./data/logs/' . date('Y-m-d'));
        $logger = new \Laminas\Log\Logger();
        $logger->addWriter($writer);
        $this->logger = $logger;
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    ################# Logger

    /**
     * @param string $name
     * @param bool $includeDate
     */
    public function initLogger(string $name = '', bool $includeDate = false)
    {
        $date = date('Y-m-d');
        $filename = $includeDate ? "{$name}-{$date}" : $name;
        $writer = new \Laminas\Log\Writer\Stream('./data/logs/' . $filename);
        $logger = new \Laminas\Log\Logger();
        $logger->addWriter($writer);
        $this->logger = $logger;
    }

    /**
     * Escreve o logger
     * @param string $message
     */
    public function writerLogger($message = null)
    {
        $this->logger->info("{$message}");
    }

    /**
     * Salva no arquivo de log
     * @param $message
     */
    public function debug($message)
    {
        $this->logger->info($message . PHP_EOL);
    }

    /**
     * @return \Laminas\Log\Logger
     */
    public function getMyLogger()
    {
        return $this->logger;
    }

    /**
     * Retorna o Repositorio
     * @param $repository
     * @return mixed
     */
    public function getRepository($repository)
    {
        return new $repository($this->getContainer()->get(AdapterInterface::class), new ReflectionHydrator(), new ArrayObject());
    }

    /**
     * Somente leitura
     * @param $repository
     * @return mixed
     */
    public function getRepositoryReader($repository)
    {
        $adapter = new \Laminas\Db\Adapter\Adapter(UsefulController::getConfigGlobal('ro'));
        return new $repository($adapter, new ReflectionHydrator(), new ArrayObject());
    }

    /**
     * Retorna a class reponsavel pela traducao
     * @return mixed
     */
    public function getMvcTranslate()
    {
        return $this->getContainer()->get('MvcTranslator');
    }

    /**
     * Traducao direta
     * @param string $message
     * @return mixed
     */
    public function translate(string $message)
    {
        return $this->getContainer()->get('MvcTranslator')->translate($message);
    }
}
