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

namespace Application\Service;

use Zend\Authentication\Storage\Session,
    Zend\ServiceManager\FactoryInterface,
    Zend\Authentication\Adapter\Callback,
    Zend\Authentication\AuthenticationService,
    Zend\ServiceManager\ServiceLocatorInterface;

class AuthFactory implements FactoryInterface {

    /**
     *
     * @var type
     */
    protected $auth = null;

    /**
     *
     * @var type
     */
    protected $serviceLocator;

    /**
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return \Application\Service\Authentication
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $this->setServiceLocator($serviceLocator);

        $this->auth = new AuthenticationService(
                new Session, new Callback(array($this, 'verify'))
        );

        return $this;
    }

    /**
     *
     * @param type $name
     * @param type $arguments
     * @return type
     */
    public function __call($name, $arguments) {
        return call_user_func_array(array($this->auth, $name), $arguments);
    }

    /**
     *
     * @param type $identity
     * @param type $credentials
     *
     * @todo Do not like that function at all!!
     */
    public function verify($identity, \stdClass $credentials) {
        if (is_null($identity) && $credentials) { //no identity, first time
            $list = $this->getServiceLocator()->get('Plugin\UserManager')->fetchAll();
            foreach ($list as $user) {
                if ($user->getUsername() == $credentials->username
                                && $user->getPassword() == $credentials->password) {
                    $identity = (object) array(
                                'username' => $credentials->username,
                                'role' => $user->getRole()
                    );
                }
            }
        }

        return $identity;
    }

    /**
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     *
     * @return type
     */
    public function getServiceLocator() {
        return $this->serviceLocator;
    }

}
