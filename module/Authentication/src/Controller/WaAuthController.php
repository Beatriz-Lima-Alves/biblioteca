<?php

namespace Authentication\Controller;

use APIs\Controller\UserController;
use Application\Controller\AbstractController;
use Facebook\PersistentData\FacebookSessionPersistentDataHandler;
use Laminas\Json\Json;
use Useful\Controller\{CryptController, TimezoneController, UsefulController, ValidadorController};

/**
 * Class WaAuthController
 * @package Authentication\Controller
 */
class WaAuthController extends AbstractController
{
    /**
     * @return \Laminas\View\Model\ViewModel
     */
    public function indexAction(): \Laminas\View\Model\ViewModel
    {
        return $this->viewModel;
    }

    /**
     * Login
     * @return \Laminas\View\Model\JsonModel
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function loginAction(): \Laminas\View\Model\JsonModel
    {
        // Default
        $message = $this->translate("Incorrect username or password. Please try again.");
        $status = false;
        $data = [];
        $outcome = null;
        $render = null;
        if ($this->getRequest()->isPost()) {
            try {
                $post = $this->postJsonp();
                // Data
                $username = UsefulController::getValueInArray($post, 'username', null);
                $password = UsefulController::getValueInArray($post, 'password', null);
                $accepted = boolval(UsefulController::getValueInArray($post, 'accepted', false));
//                $lang_account = 'pt_BR';
                // Validate
                if (!ValidadorController::isValidUsername($username)) {
                    throw new \Exception($this->translate('Username is invalid'));
                } elseif (!ValidadorController::isValidSenha($password)) {
                    throw new \Exception($this->translate('Password is invalid'));
                } else {
                    // Clazz
                    $User = new \CalculoIdeal\Controller\UserController($this->getServiceManager());
//                    $CompanyAccount = new \CalculoIdeal\Controller\CompanyController($this->getServiceManager());
                    //Validate
                    if ($User->fetch(['username' => $username])) {
                        // Auth
                        $WaAuthentication = new \Authentication\Service\WaAuthenticationAdapter($this->getServiceManager());

                        $WaAuthentication->setUsername($username);
                        $WaAuthentication->setPassword($password);
                        $WaAuthentication->setAccepted($accepted);
                        $WaAuthentication->setPasswordColumn("password");
                        $row = $WaAuthentication->authenticate();



                        if ($row) {
                            // Uuid
                            $uuid = uniqid();
                            $data['token'] = CryptController::grantCryptToken($row->id, $uuid, 1111, 'NONE');
                            // Update
                            $User->updated($row->id, [
                                'last_access' => date('Y-m-d h:i:s')
                            ]);
//
                            // Res
                            $status = true;
                            $message = null;
                            $outcome = $row->id;
                            $render = $this->url()->fromRoute('login', ['action' => 'connect'], ['force_canonical' => true], true);
                        } else {
                            $message = $this->translate('Incorrect username or password. Please try again.');
                        }
                    } else {
                        $message = $this->translate('User not found.');
                    }
                }
            } catch (\Error $err) {
                $message = $err->getMessage();
            } catch (\Exception $e) {
                $message = $e->getMessage();
            }
        } else {
            $message = $this->translate('Authentication method is Invalid.');
        }
        return UsefulController::createResponse($status, $message, $data, $outcome, null, null, $render);
    }

    /**
     * @return \Laminas\View\Model\ViewModel
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function signupAction(): \Laminas\View\Model\ViewModel
    {

//        die();
        // Default
        $message = $this->translate("Incorrect username or password. Please try again.");
        $status = false;
        $data = [];
        $outcome = null;
        $render = null;
        if ($this->getRequest()->isPost()) {
            try {
                $post = $this->postJsonp();
                //Vars
                $id = 0;
                $country = UsefulController::getValueInArray($post, 'country', 'BR');
                $name = UsefulController::getValueInArray($post, 'name', null);
                $surname = UsefulController::getValueInArray($post, 'surname', null);
                $email = UsefulController::getValueInArray($post, 'email', null);
                $username = UsefulController::getValueInArray($post, 'username', null);
                $password = UsefulController::getValueInArray($post, 'password', null);
                $passwordConf = UsefulController::getValueInArray($post, 'passwordConf', null);
                $tnc = UsefulController::getValueInArray($post, 'tnc', false);
                // Validacao basica
                if (!ValidadorController::isValidNotEmpty($name) || strlen($name) < 2) {
                    throw new \Exception($this->translate('Please enter your name'));
                } elseif (!ValidadorController::isValidNotEmpty($surname) || strlen($surname) < 2) {
                    throw new \Exception($this->translate('Surname: Please use only letters (a-z), numbers and full stops.'));
                }  elseif (!ValidadorController::isValidUsername($username)) {
                    throw new \Exception($this->translate('Username: Please use only letters (a-z), numbers and full stops.'));
                } elseif (!ValidadorController::isValidEmail($email)) {
                    throw new \Exception($this->translate('E-mail: Your email address is invalid.'));
                } elseif (!ValidadorController::isValidNotEmpty($password) || !ValidadorController::isValidStringLength($password, 8, 30) || !ValidadorController::isValidSenha($password)) {
                    throw new \Exception($this->translate('Password is not valid!.<br/>') . $this->translate('Password: Short passwords are easy to guess. Try one with at least 8 characters. <br/> Use at least 8 characters. Don\'t use a password from another site or something too obvious like your pet\'s name.'));
                    //} elseif (!ValidadorController::ifSafeStringComparison($password, $rpassword)) {
                    //  throw new \Exception ($this->translate('Confirm your password: These passwords don\'t match. Try again?.'));
                }elseif ($password != $passwordConf){
                    throw new \Exception ($this->translate('Passwords are not the same'));
                }elseif (!$tnc) {
                    throw new \Exception ($this->translate('I Agree: In order to use our services, you must agree to Terms of Service.'));
                } else {
                    // Clazz
                    $FixIt = new \CalculoIdeal\Controller\FixItController($this->getServiceManager());
                    $User = new \CalculoIdeal\Controller\UserController($this->getServiceManager());
                    $rs = $User->fetch(['username' => $username]);
                    if ($rs) {
                        $rs = $rs['id'];
                    }

                    if ($rs != false && $id != $rs) {
                        throw new \Exception($this->translate('Someone already has that username. Try another or forgot password ?'));
                    } else {

                        $uid = $User->save(null, [
                            'name' => $name.' '.$surname,
                            'username' => $username,
                            'password' => $password,
                            'email' => $email,
                        ]);
                        //Next
                        $status = boolval($uid);
                        if ($status) {
                            //Verify Em

                            // Auth
                            $WaAuthentication = new \Authentication\Service\WaAuthenticationAdapter($this->getServiceManager());
                            $WaAuthentication->setUsername($username);
                            $WaAuthentication->setPassword($password);
                            $WaAuthentication->setPasswordColumn('password');
                            $row = $WaAuthentication->authenticate();
                            if ($row) {
                                // Uuid
                                $uuid = uniqid();
                                $data['token'] = CryptController::grantCryptToken($uid, $uuid, 1111, 'NONE');
                                // Update
                                $User->updated($row->id, [
                                    'last_access' => date('Y-m-d h:i:s')
                                ]);
                                // Res
                                $message = $this->translate('OK');
                            } else {
                                throw new \Exception($this->translate('Unable to register the social network, please sign-up with mail or try again.'));
                            }
                            // Res
                            $outcome = $uid;
                            $render = $this->url()->fromRoute('login', [], ['force_canonical' => true], false);
                        }


                    }
                }
            } catch (\Error $err) {
                $message = $err->getMessage();
            } catch (\Exception $e) {
                $message = $e->getMessage();
            }
            //Return
            return UsefulController::createResponse($status, $message, $data, $outcome, null, null, $render);
        } else {
            return $this->viewModel;
        }
    }

    /**
     * Redirecionamento
     * @return \Laminas\Http\Response
     */
    public function connectAction(): \Laminas\Http\Response
    {
        $WaAuthentication = new \Authentication\Service\WaAuthenticationAdapter($this->getServiceManager());
        if ($WaAuthentication->hasIdentity()) {
                    return $this->redirect()->toRoute('pro-order-central');
        }
        return $this->redirect()->toRoute('login');
    }

    /** Atualizacao requerida**/
    public function requiredUpgradeAction(): \Laminas\View\Model\ViewModel
    {
        return $this->viewModel;
    }

    /**
     * Logout
     * @return \Laminas\Http\Response
     */
    public function logoutAction(): \Laminas\Http\Response
    {
        try {
            $WaAuthentication = new \Authentication\Service\WaAuthenticationAdapter($this->getServiceManager());
            $WaAuthentication->logout();
        } catch (\Exception $e) {
        }
        $route = boolval($this->params()->fromQuery('route', 0));
        if ($route) {
            return $this->redirect()->toRoute('home');
        } else {
            return $this->redirect()->toRoute('login');
        }
    }

    /**
     * Reset Password
     * @return \Laminas\View\Model\JsonModel|\Laminas\View\Model\ViewModel
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function forgotAction()
    {

    }

    /**
     * @return \Laminas\View\Model\ViewModel
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function resetAction(): \Laminas\View\Model\ViewModel
    {
    }

    /**
     * @return \Laminas\View\Model\ViewModel
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function confirmVerificationAction(): \Laminas\View\Model\ViewModel
    {
        $message = '';
        try {
            $hash = $this->params()->fromQuery('key', null);
            if (!ValidadorController::isValidNotEmpty($hash)) {
                throw new \Exception($this->translate('An attempt has been made to operate on an impersonation token by a thread that is not currently impersonating a client. [001]'));
            } elseif (!ValidadorController::isValidRegexp($hash, 'base64')) {
                throw new \Exception($this->translate('An attempt has been made to operate on an impersonation token by a thread that is not currently impersonating a client. [002]'));
            } else {
                //Decrypt
                $decrypt = CryptController::decrypt($hash, true);
                if (!$decrypt) {
                    throw new \Exception($this->translate('An attempt has been made to operate on an impersonation token by a thread that is not currently impersonating a client. [003]'));
                } elseif (!ValidadorController::isValidJson($decrypt)) {
                    throw new \Exception($this->translate('An attempt has been made to operate on an impersonation token by a thread that is not currently impersonating a client. [004]'));
                } else {
                    $data = \Laminas\Json\Decoder::decode($decrypt, Json::TYPE_ARRAY);
                    $uid = (int)isset($data['uid']) ? $data['uid'] : null;
                    $cid = (int)isset($data['cid']) ? $data['cid'] : null;
                    $role = isset($data['role']) ? $data['role'] : null;
                    if (ValidadorController::isValidNotEmpty($uid) && ValidadorController::isValidNotEmpty($cid) && ValidadorController::isValidNotEmpty($role)) {
                        //Clazz
                        $User = new \CalculoIdeal\Controller\UserController($this->getServiceManager());
                        $Company = new \CalculoIdeal\Controller\CompanyController($this->getServiceManager());
                        //Check
                        $User->updated($uid, ['status' => 1]);
                        $Company->updated($cid, ['check_verify' => 1]);
                        //Msg
                        $message = $this->translate('<div class="text-center"><span class="text-success">Your email was verified.  =)</span> <p>&nbsp;</p>Thanks,<br/>  Team</div>');
                    } else {
                        throw new \Exception($this->translate('An attempt has been made to operate on an impersonation token by a thread that is not currently impersonating a client. [005]'));
                    }
                }
            }
        } catch (\Error $err) {
            $message = $err->getMessage();
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }
        //Return
        return $this->viewModel->setVariable('CALCULOIDEAL_RESET_MESSAGE', $message);
    }

    /**
     * Altera o idioma
     * @param null $lang_account
     * @return \Laminas\View\Model\JsonModel
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function langChooserAction($lang_account = null): \Laminas\View\Model\JsonModel
    {
        // Default
        $message = $this->translate("Unknown Error");
        $status = false;
        $data = [];
        $outcome = 'pt';
        // Allow
        $default = 'pt_BR';
        $supported = array(
            'en_US' => 'English (United States)',
            'es_ES' => 'Español (España)',
            'es_419' => 'Español (Latinoamérica)',
            'pt_PT' => 'Português (Portugal)',
            'pt_BR' => 'Português (Brasil)',
        );
        //Session
        $Session = new \Laminas\Session\Container ('CALCULOIDEAL_LANG');
        //Check
        if ($this->getRequest()->isPost()) {
            try {
                $post = $this->postJsonp();
                //Vars
                $lang = UsefulController::getValueInArray($post, 'lang', 'pt-BR');
                if (ValidadorController::isValidNotEmpty($lang)) {
                    if (array_key_exists($lang, $supported)) {
                        $Session->locale = $lang;
//                    } elseif (!isset ($Session->locale) && ($lang_account != null)) {
//                        $Session->locale = $lang_account;
                    } elseif (!isset ($Session->locale)) {
                        $Session->locale = $default;
                    }
                    $status = \Locale::setDefault($Session->locale);
                    if ($status) {
                        $this->getServiceManager()->get('MvcTranslator')->setLocale($Session->locale);
                    }
                }
            } catch (\Error $err) {
                $message = $err->getMessage();
            } catch (\Exception $e) {
                $message = $e->getMessage();
            }
        } else {
            $status = true;
            $data = $Session->locale ?? $default;
        }
        //Return
        return UsefulController::createResponse($status, $message, $data, substr($data, 0, 2));
    }
}