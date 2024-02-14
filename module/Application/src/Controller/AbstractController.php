<?php

namespace Application\Controller;

use Interop\Container\ContainerInterface;
use Useful\Controller\UsefulController;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\ServiceManager\ServiceManager;
use Laminas\View\Model\ViewModel;

/**
 * Class AbstractController
 * @package Application\Controller
 */
class AbstractController extends AbstractActionController
{
    /**
     * View Render
     *
     * @var ViewModel
     */
    protected $viewModel;
    /**
     * @var Object_
     */
    protected $Identity = null;
    /**
     *
     * @var ServiceManager
     */
    protected $serviceManager;

    public function __construct(ContainerInterface $container)
    {
        $this->serviceManager = $container;
        $this->viewModel = new ViewModel();
        $this->getSetHostname();
    }

    /**
     * Retorna o User Logged
     * @return object
     */
    public function getIdentity()
    {
        return $this->Identity;
    }

    /**
     * Seta array variaveis para set usado na view/partial
     *
     * @param array $variables
     */
    public function setViewVariables($variables)
    {
        $this->viewModel->setVariables($variables);
    }

    public function getSetHostname()
    {
        list($subdomain, $host) = explode('.', $_SERVER["SERVER_NAME"]);
        $this->setViewVariable('CALCULOIDEAL_SYSTEM_SUBDOMAIN', $subdomain);
        $this->setViewVariable('CALCULOIDEAL_SYSTEM_HOST', $host);
    }

    /**
     * Seta variavel, nome e valor
     *
     * @param string $name
     * @param string $value
     */
    public function setViewVariable($name, $value)
    {
        $this->viewModel->setVariable($name, $value);
        $this->layout()->setVariable($name, $value);
    }

    /**
     *
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     *
     * @param mixed $serviceManager
     * @return $this
     */
    public function setServiceManager($serviceManager)
    {
        $this->serviceManager = $serviceManager;
        return $this;
    }

    /**
     * Translate
     *
     * @param string $message
     * @return string
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function translate($message)
    {
        return $this->getServiceManager()
            ->get('MvcTranslator')
            ->translate($message);
    }

    /**
     * Retorna a ultima lang salva em sessao
     * @return string
     */
    public function getCurrentLang(): string
    {
        $Session = new \Laminas\Session\Container ('CALCULOIDEAL_LANG');
        return isset ($Session->locale) ? $Session->locale : 'pt_BR';
    }

    /**
     * Json Post
     * @return mixed|void|array|\Useful\Controller\stdClass|NULL|boolean|string|\Useful\Controller\unknown
     */
    public function postJsonp()
    {
        return UsefulController::getPost(true, $this->getRequest()->getContent());
    }
}