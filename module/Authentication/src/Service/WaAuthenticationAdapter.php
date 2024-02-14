<?php

namespace Authentication\Service;

use Useful\Controller\UsefulController;
use Laminas\Authentication\Adapter\DbTable\CredentialTreatmentAdapter as AuthAdapter;
use Laminas\Authentication\Adapter\AdapterInterface;
use Interop\Container\ContainerInterface;
use Laminas\Db\Adapter\Adapter as DbAdapter;

class WaAuthenticationAdapter implements AdapterInterface
{

    private $username;

    private $password;

    private $accepted;

    private $container;

    private $password_column = 'account_admin';//Fake

    private $sessionManager;

    private $authStorage;

    private $authService;

    public function __construct(ContainerInterface $container)
    {
        $this->sessionManager = new \Laminas\Session\SessionManager();
        $this->authStorage = new \Laminas\Authentication\Storage\Session('Zend_Auth', 'session', $this->sessionManager);
        $this->authService = new \Laminas\Authentication\AuthenticationService();
        $this->authService->setStorage($this->authStorage);
        $this->container = $container;
    }

    /**
     *
     * @return \Interop\Container\ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Sets user username.
     * @param $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Sets password.
     * @param $password
     */
    public function setPassword($password)
    {
        $this->password = (string)$password;
    }

    /**
     * Confirmacao de atualizacao
     * @param $accepted
     */
    public function setAccepted($accepted)
    {
        $this->accepted = boolval($accepted);
    }

    /**
     * Coluna de autenticacao
     *
     * @param string $column
     */
    public function setPasswordColumn($column)
    {
        $this->password_column = (string)$column;
    }

    /**
     * Performs an WaAuthentication attempt.
     */
    public function authenticate()
    {
        $dbAdapter = $this->container->get(DbAdapter::class);
        // Or configure the instance with setter methods:
        $authAdapter = new AuthAdapter($dbAdapter);

        if ($this->password_column == 'facebook_id') {
            $authAdapter->setTableName('employee')
                ->setIdentityColumn('email')
                ->setCredentialColumn('facebook_id')
                ->setCredentialTreatment("removed='0'");

        } elseif ($this->password_column == 'google_id') {
            $authAdapter->setTableName('employee')
                ->setIdentityColumn('email')
                ->setCredentialColumn('google_id')
                ->setCredentialTreatment("removed='0'");

        } elseif ($this->password_column == 'password') {
            $authAdapter->setTableName('employee')
                ->setIdentityColumn('username')
                ->setCredentialColumn('password')
                ->setCredentialTreatment("SHA1(CONCAT(?,salt)) AND removed='0'");

        } elseif ($this->password_column == 'plaintext') {
            $authAdapter->setTableName('employee')
                ->setIdentityColumn('username')
                ->setCredentialColumn('password')
                ->setCredentialTreatment("removed='0");

        } else {
            $authAdapter->setTableName('employee')
                ->setIdentityColumn('username')
                ->setCredentialColumn('password')
                ->setCredentialTreatment("MD5(?) AND removed='0'");
        }
        // Set the input credential values (e.g., from a login form):
        $authAdapter->setIdentity($this->username)->setCredential($this->password);

        // Perform the WaAuthentication query, saving the result
        $result = $authAdapter->authenticate();


        if ($result->isValid()) {
            // Print the result row:
            $columnsToOmit = [
                'password',
                'uuid'
            ];
            $resultRow = $authAdapter->getResultRowObject(null, $columnsToOmit);
//            if ($resultRow->status == 1 && ($resultRow->check_portal_access_portal || $resultRow->privilege_type == 1)) {
                //Sessao
                $this->sessionManager->rememberMe(60 * 60 * 24 * 30);
                /** @var TYPE_NAME $resultRow */
                $this->authService->getStorage()->write([
                    // User
                    'id' => $resultRow->id,
                    'name' => strstr($resultRow->name, ' ', true),
                    'username' => $resultRow->name,
                    'email' => $resultRow->email,
                    'removed' => $resultRow->removed ?? 0
                ]);
//                Result
                return $resultRow;
//            }
        }

        return false;
    }


    /**
     * @return bool
     */
    public function hasIdentity(): bool
    {
        if ($this->authService->getIdentity() == null) {
            return false;
        }
        return true;
    }

    /**
     * Identidade logada
     * @return mixed|null
     */
    public function getIdentity()
    {
        return $this->authService->getIdentity();
    }

    /**
     * Performs user logout.
     * @throws \Exception
     */
    public function logout()
    {
        // Allow to log out only when user is logged in.
        if ($this->getIdentity() == null) {
            throw new \Exception('The user is not logged in');
        }
        $Session = new \Laminas\Session\Container ('BIBLIOTECA_LANG');
        $lang = $Session->locale;
        // Remove identity from session.
        $this->authService->clearIdentity();
        session_destroy();
        $Session = new \Laminas\Session\Container ('BIBLIOTECA_LANG');
        $Session->locale = $lang;
        $Session->offsetSet('locale', $lang);
    }
}