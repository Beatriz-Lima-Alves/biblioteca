<?php
namespace Authentication\Service;

use APIs\Controller\APIsController;
use Useful\Controller\CryptController;
use Laminas\Mvc\MvcEvent;
use Laminas\View\Model\JsonModel;

/**
 * HTTP Basic Authentication for API
 */
class AuthorizationService extends APIsController
{

    /**
     * Constants compartilhada entre as APIs
     */
    const AUTHORIZATION_HEADER = 'Authorization';

    const AUTHORIZATION_BASIC = 'BASIC';

    protected $_user = null;

    protected $_version = 1;

    /**
     * @param MvcEvent $event
     * @return mixed|MvcEvent
     */
    public function onDispatch(MvcEvent $event)
    {
        // Default
        $message = $this->translate("Unknown Error, try again, please.");
        $status = false;
        $data = [];
        $outcome = null;
        try {
            // Token
            $token = self::getTheTokenHeader();
            // The header needs to be base64 decoded, then match the regex in order to proceed.
            $authorizationHeader = base64_decode($token);
            // Encontrou algo, vamos verificar se eh valido
            if (! $authorizationHeader) {
                throw new \Exception($this->translate('AuthorizationHeader not acceptable'), 401);
            } else {
                $decrypted = CryptController::decrypt($authorizationHeader);
                if (! $decrypted) { // Nao aceitou
                    throw new \Exception($this->translate('Token not acceptable'), 401);
                } else { // Aceitou, mas contem os campos esperados?
                    if (! self::isValidToken($decrypted)) {
                        throw new \Exception($this->translate('Token is invalid or expired'), 401);
                    }
                }
            }
            // Verificando versao do token
            if (! self::checkVersion(self::getTokenParam($decrypted, 'version'))) {
                throw new \Exception($this->translate('Upgrade Required. You need to upgrade application or try the validation number again .'), 426);
            }
            // Verifica o UUID
            if (! self::checkSingleConnection(self::getTokenParam($decrypted, 'uid'), self::getTokenParam($decrypted, 'uniqid'))) {
                throw new \Exception($this->translate('Your code has been disabled. because it was used in another phone.'), 406);
            }
            return parent::onDispatch($event);
        } catch (\Error $err) {
            $message = $err->getMessage();
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }
        // Result
        // View
        $viewModel = $event->setViewModel(new JsonModel(array(
            'status' => $status,
            'message' => $message,
            'data' => $data,
            'outcome' => $outcome
        )));
        // ### BEGIN DEBUG
        // Logger
        $writer = new \Laminas\Log\Writer\Stream('./data/logs/' . date('Y-m-d'));
        $logger = new \Laminas\Log\Logger();
        $logger->addWriter($writer);
        $this->logger = $logger;
        
        $request = $this->getRequest();
        foreach ($request->getHeaders() as $header) {
            $logger->info($header->getFieldName() . ' with value ' . $header->getFieldValue());
        }
        $logger->info($request->getContent() . PHP_EOL);
        
        $logger->info($viewModel->serialize() . PHP_EOL);
        // ### END DEBUG
        // rETURN
        return $viewModel;
    }

    /**
     * Retira o token do header
     *
     * @return mixed
     * @throws \Exception
     */
    private function getTheTokenHeader()
    {
        return self::getRequestHeader($this->getRequest());
    }

    /**
     * Obter um param de um json
     *
     * @param $param
     * @return bool|null
     */
    private function getParamsfromJson($param)
    {
        $body = $this->getRequest()->getContent();
        if (! empty($body)) {
            $json = json_decode($body, true);
            if (! empty($json)) {
                return isset($json[$param]) ? $json[$param] : null;
            }
        }
        
        return false;
    }

    /**
     * @param $Request
     * @return mixed
     * @throws \Exception
     */
    private function getRequestHeader($Request)
    {
        $Identity = $Request->getHeader(self::AUTHORIZATION_HEADER);
        if (! empty($Identity)) {
            if ($Identity != null || $Identity != '') {
                $value = $Identity->getFieldValue();
                $authorizationParts = explode(' ', $value);
                if (strtoupper(trim($authorizationParts[0])) == self::AUTHORIZATION_BASIC) {
                    if (isset($authorizationParts[1])) {
                        return $authorizationParts[1];
                    } else {
                        throw new \Exception($this->translate('Format token is invalid.'), 401);
                    }
                }
            }
        }
        throw new \Exception('Session expired.', 401);
    }

    /**
     * Retorna um especifico valor que se encontra dentro do token
     *
     * @param $decrypted
     * @param $param
     * @return bool
     * @throws \Exception
     */
    private function getTokenParam($decrypted, $param)
    {
        if (is_null($decrypted) || strlen($decrypted) < 30) {
            throw new \Exception($this->translate('Invalid Key/Token/Session '), 401);
        } else {
            $rs = CryptController::getPrivilegesToken($decrypted);
            if (! empty($rs) && ! is_null($rs)) {
                if (isset($rs[$param])) {
                    if ($param == 'uniqid') {
                        $pieces = explode("-", $rs[$param]);
                        return $pieces[1];
                    }
                    return $rs[$param];
                }
            }
        }
        return false;
    }

    /**
     * Valida se o Token e valido
     *
     * @param String $token
     * @throws \Exception
     * @return boolean
     */
    private function isValidToken($token)
    {
        if (is_null($token) || strlen($token) < 30) {
            throw new \Exception($this->translate('Invalid Key/Token/Session '), 401);
        } else {
            $rs = CryptController::getPrivilegesToken($token);
            if (! empty($rs) && ! is_null($rs)) {
                if (isset($rs['expire'])) {
                    // if ($rs ['expire'] > time ()) { //DESABILITANDO VALIDADE DO TOKEN
                    self::setUser($rs);
                    return true;
                    // }
                }
            }
        }
        return false;
    }

    /**
     * Checa se a conexao contem o Uuid valido
     *
     * @param $uid
     * @param $uuid
     * @return mixed
     */
    private function checkSingleConnection($uid, $uuid)
    {
        $User = new \CalculoIdeal\Controller\UserController($this->getContainer());
        return $User->isValidConnection($uid, $uuid);
    }

    /**
     * Chega se a versao do token esta atualizada
     *
     * @param $version
     * @return bool
     */
    private function checkVersion($version) :  bool
    {
        if (! is_null($version)) {
            if ($version >= self::getVersion()) {
                return true;
            }
        }
        return false;
    }

    /**
     * Getter
     *
     * @return number
     */
    public function getVersion()
    {
        return $this->_version;
    }

    /**
     *
     * @setter
     *
     * @param array $user
     */
    public function setUser($user)
    {
        $this->_user = $user;
    }

    /**
     * Retorna o valor contido na sessao do usuario
     *
     * @param string $value
     * @return boolean
     */
    public function getUser($value)
    {
        if (isset($this->_user[$value])) {
            return $this->_user[$value];
        }
        return false;
    }
}