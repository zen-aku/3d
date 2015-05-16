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

namespace Application;

use Zend\Mvc\MvcEvent,
    Zend\Mvc\ModuleRouteListener;

class Module {

    /**
     * 
     * @param MvcEvent $e
     */
    public function onBootstrap(MvcEvent $e) {
        $application = $e->getApplication();

        //attache router listener
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($application->getEventManager());

        //check if user is authenticated to dispatch anything withing the system
        $application->getEventManager()->attach(
                MvcEvent::EVENT_DISPATCH, array($this, 'authenticated'), 999
        );

        //initialize the ACL
        $auth = $application->getServiceManager()->get('auth');

        //set default ACL for Navigation
        \Zend\View\Helper\Navigation\AbstractHelper::setDefaultAcl(
                $application->getServiceManager()->get('security')->getAcl()
        );
        if ($auth->hasIdentity()) {
            \Zend\View\Helper\Navigation\AbstractHelper::setDefaultRole(
                    $auth->getIdentity()->role
            );
        }

        //Application event listener
        $listener = $application->getServiceManager()->get('ApplicationEventListener');
        $application->getEventManager()->attach('*', array($listener, 'listen'));
    }

    /**
     *
     * @param MvcEvent $e
     */
    public function authenticated(MvcEvent $e) {
        $auth = $e->getApplication()->getServiceManager()->get('auth');
        $routerName = $e->getRouteMatch()->getMatchedRouteName();

        if (!$auth->hasIdentity() && $routerName != 'auth') {
            header('Location: /auth');
            exit;
        }
    }

    /**
     * 
     * @return type
     */
    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * 
     * @return type
     */
    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

}
