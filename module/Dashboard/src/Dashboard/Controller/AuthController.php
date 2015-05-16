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

namespace Dashboard\Controller;

use \Zend\Mvc\MvcEvent,
    \Zend\View\Model\ViewModel,
    \Zend\Mvc\Controller\AbstractActionController;

/**
 * Short description for class
 *
 * Long description for class (if any)...
 *
 * @package    Auth
 * @author     Vasyl Martyniuk <vasyl@vasyltech.com>
 * @version    Release: @package_version@
 */
class AuthController extends AbstractActionController {

    /**
     *
     * @param MvcEvent $e
     */
    public function onDispatch(MvcEvent $e) {
        parent::onDispatch($e);

        if (($e->getRouteMatch()->getParam('action') != 'logout')
                    && $this->getServiceLocator()->get('auth')->hasIdentity()) {
            //if user authenticated, no need to show the login form again
            $response = $this->redirect()->toRoute('dashboard');
            $response->sendHeaders();
            exit;
        } else {
            //set login layout
            $this->layout()->setTemplate('layout/login.phtml');
        }
    }

    /**
     * 
     * @return ViewModel
     */
    public function indexAction() {
        return new ViewModel(array('code' => $this->params()->fromQuery('code')));
    }

    /**
     *
     * @return type
     */
    public function loginAction() {
        $this->getServiceLocator()->get('Dashboard\Form\Login')->run();
    }

    /**
     * 
     */
    public function logoutAction() {
        $auth = $this->getServiceLocator()->get('auth');

        //report logout
        $this->getEvent()->getApplication()->getEventManager()->trigger(
                'logout',
                $this,
                array('username' => $auth->getStorage()->read()->username)
        );
        
        //logout
        $auth->clearIdentity();
        $response = $this->redirect()->toRoute('auth');
        $response->sendHeaders();
        exit;
    }

}
