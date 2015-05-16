<?php

/**
 * Short description for file
 *
 * Long description for file (if any)...
 *
 * PHP version 5
 *
 * LICENSE: This source file is subject to version 3.01 of the Creative Commons
 * Attribution-NonCommercial that is available through the world-wide-web at the
 * following URI: http://creativecommons.org/licenses/by-nc/3.0/.  If you did not
 * receive a copy of the Creative Commons Attribution-NonCommercial and are unable
 * to obtain it through the web, please send a note to vasyl@vasyltech.com so we
 * can mail you a copy immediately.
 *
 * @package    Auth
 * @author     Vasyl Martyniuk <vasyl@vasyltech.com>
 * @copyright  2015 Vasyltech
 * @license    Creative Commons Attribution-NonCommercial 3.0
 * @license    http://creativecommons.org/licenses/by-nc/3.0/
 * @since      File available since Release 0.1
 */

namespace Dashboard\Form;

use \Zend\Form\Form,
    \Zend\Http\Response,
    Zend\ServiceManager\ServiceLocatorInterface,
    Zend\ServiceManager\ServiceLocatorAwareInterface;

class Login extends Form implements ServiceLocatorAwareInterface {

    /**
     *
     * @var type 
     */
    protected $serviceLocator;

    /**
     * Set service locator
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * Get service locator
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator() {
        return $this->serviceLocator;
    }

    /**
     * 
     */
    public function run() {
        $request = $this->getServiceLocator()->get('Request');
        
        if ($request->isPost()) {
            $credentials = $this->parseForm();

            $service = $this->getServiceLocator()->get('auth');
            $service->getAdapter()->setCredential($credentials);

            $response = $this->getServiceLocator()->get('Response');

            if ($service->authenticate()->isValid()) { //redirect to dashboard
                $response->getHeaders()->addHeaderLine('Location', '/');
                $response->setStatusCode(Response::STATUS_CODE_200);
            } else { //redirect back to the login form
                $response->getHeaders()->addHeaderLine(
                        'Location', '/auth?code=' . Response::STATUS_CODE_401
                );
                $response->setStatusCode(Response::STATUS_CODE_401);
            }

            //report the event
            $this->report($service, $credentials);

            //finish login
            $response->sendHeaders();
            exit;
        }
    }

    /**
     *
     * @param AuthFactory $service
     */
    protected function report($service, $credentials) {
        $this->getServiceLocator()->get('Application')->getEventManager()->trigger(
                'login', 
                $this,
                array(
                    'username' => $credentials->username,
                    'status' => ($service->hasIdentity() ? 'success' : 'failure')
                )
        );
    }

    /**
     *
     * @return Credentials
     */
    public function parseForm() {
        $request = $this->getServiceLocator()->get('Request')->getPost();

        return (object) array(
            'username' => $request->get('username'),
            'password' => $request->get('password')
        );
    }

}
