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
return array(
    'router' => array(
        'routes' => array(
            'dashboard' => array(//overwrite default settings to use Dashboard
                'type' => 'Segment',
                'options' => array(
                    'route' => '/[:controller[/:action]]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Dashboard\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
                    ),
                )
            ),
            'auth' => array(//overwrite default settings to use Dashboard
                'type' => 'Segment',
                'options' => array(
                    'route' => '/auth[/:action]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Dashboard\Controller',
                        'controller' => 'Auth',
                        'action' => 'index',
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory'
        ),
        'invokables' => array(
            'Dashboard\Form\Login' => 'Dashboard\Form\Login',
            'Dashboard\BusinessRule\LoginAttempt' => 'Dashboard\BusinessRule\LoginAttempt'
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Dashboard\Controller\Index' => 'Dashboard\Controller\IndexController',
            'Dashboard\Controller\Auth' => 'Dashboard\Controller\AuthController',
            'Dashboard\Controller\Event' => 'Dashboard\Controller\EventController'
        ),
    ),
    //define the list of log events
    'event_log' => array(
        'Dashboard\Form\Login\login',
        'Dashboard\Controller\IndexController\logout'
    ),
    //define the list of business rules [Event Name => Business Rule Handler]
    'business_rules' => array(
        'Dashboard\Form\Login\login' => 'Dashboard\BusinessRule\LoginAttempt'
    ),
    'navigation' => array(
        'default' => array(
            'dashboard' => array(
                'label' => 'Dashboard',
                'route' => 'dashboard',
                'controller' => 'Index',
                'resource' => 'Dashboard',
                'privilege' => 'invoke',
                'icon' => 'icon-gauge',
                'order' => 5
            ),
            'administrator' => array(
                'label' => 'Administrator',
                'uri' => '#',
                'resource' => 'Administrator',
                'privilege' => 'invoke',
                'icon' => 'icon-leaf',
                'order' => 100,
                'pages' => array(
                    array(
                        'label' => 'Event Manager',
                        'route' => 'dashboard',
                        'controller' => 'event',
                        'action' => 'index',
                        'resource' => 'Event',
                        'privilege' => 'invoke'
                    )
                )
            )
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'base_path' => '/include/dashboard',
        'template_map' => array(
            'layout/layout' => __DIR__ . '/../view/layout/default.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    )
);
