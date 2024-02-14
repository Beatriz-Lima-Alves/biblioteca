<?php
namespace Authentication\Service;

use Laminas\Authentication\Adapter\DbTable\CredentialTreatmentAdapter as AuthAdapter;
use Laminas\Authentication\Adapter\AdapterInterface;
use Interop\Container\ContainerInterface;
use Laminas\Db\Adapter\Adapter as DbAdapter;

class AuthenticationAdapter implements AdapterInterface
{

    private $username;

    private $password;

    private $password_column = 'password';

    private $container;

    public function __construct(ContainerInterface $container)
    {
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
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Sets password.
     */
    public function setPassword($password)
    {
        $this->password = (string) $password;
    }

    /**
     * Coluna de autenticacao
     *
     * @param string $column
     */
    public function setPasswordColumn($column)
    {
        $this->password_column = (string) $column;
    }

    /**
     * Performs an authentication attempt.
     */
    public function authenticate()
    {
        $dbAdapter = $this->container->get(DbAdapter::class);
        // Or configure the instance with setter methods:
        $authAdapter = new AuthAdapter($dbAdapter);
        // Coluna de acordo com o tipo de autenticacao
        if ($this->password_column == 'facebook_id') {
            $authAdapter->setTableName('user')
                ->setIdentityColumn('email')
                ->setCredentialColumn('facebook_id')
                ->setCredentialTreatment("removed='0'");
            
        } elseif ($this->password_column == 'google_id') {
            $authAdapter->setTableName('user')
                ->setIdentityColumn('email')
                ->setCredentialColumn('google_id')
                ->setCredentialTreatment("removed='0'");
            
        } else {
            $authAdapter->setTableName('user')
                ->setIdentityColumn('email')
                ->setCredentialColumn('password')
                ->setCredentialTreatment("MD5(CONCAT(?,password_salt)) AND removed='0'");
        }
        // Set the input credential values (e.g., from a login form):
        $authAdapter->setIdentity($this->username)->setCredential($this->password);
        // Perform the authentication query, saving the result
        $result = $authAdapter->authenticate();
        if ($result->isValid()) {
            // Print the result row:
            $columnsToOmit = [
                'password',
                'password_salt',
                'google_token',
                'facebook_token',
                'uuid'
            ];
            return $authAdapter->getResultRowObject(null, $columnsToOmit);
        }
        
        throw new \Exception("Login failed");
    }
}