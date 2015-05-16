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
            'application' => array(//overwrite default settings to use Dashboard
                'type' => 'Segment',
                'options' => array(
                    'route' => '/application[/:controller[/:action]]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
                    ),
                )
            )
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController'
        ),
    ),
    'service_manager' => array(
        'invokables' => array(
            //environment checker
            'Env' => 'Application\Service\Environment',
            //register UserManager plugin and its data source
            'Plugin\UserManager' => 'Application\Plugin\UserManager',
            'Plugin\UserManager\Repository' => 'Application\Repository\UserList',
            //register EventManager plugin and its data source
            'Plugin\EventManager' => 'Application\Plugin\EventManager',
            'Plugin\EventManager\Repository' => 'Application\Repository\EventList',
            //register application event listener with logger
            'ApplicationEventListener' => 'Application\Service\EventListener',
            'ApplicationEventLogger' => 'Application\Service\EventLogger',
            //register application cache listener
            'ApplicationCacheListener' => 'Application\Service\CacheListener'
        ),
        'factories' => array(
            'cache' => 'Application\Service\CacheFactory',
            'security' => 'Application\Service\SecurityFactory',
            'auth' => 'Application\Service\AuthFactory',
            //Default Database adapter
            'FileDataAdapter' => 'Application\Adapter\FileAdapterFactory',
        )
    ),
    'plugin_adapter' => array(
        'Plugin\UserManager' => 'FileDataAdapter',
        'Plugin\EventManager' => 'FileDataAdapter'
    ),
    'acl' => array(
        'resources' => array()
    ),
    'file_adapter' => array(
        'options' => array(
            'base_dir' => __DIR__ . '/../../../data/storage',
            'file_ext' => 'txt'
        )
    ),
    'cache' => array(
        'patterns' => array(
            'default' => array(
                'storage' => array(
                    'adapter' => 'Filesystem',
                    'options' => array(
                        'cache_dir' => __DIR__ . '/../../../data/cache',
                        'key_pattern' => '/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\\\x7f-\xff]*$/',
                        'ttl' => 86400, //default Ttl - one day
                        //TURN ON/OFF caching mechanism
                        'writable' => true,
                    ),
                    'plugins' => array(
                        'serializer' => array(),
                        'Application\Plugin\CacheTtl' => array(
                            'serializerOptions' => array(
                                'key_ttls' => array() //custom key ttls
                            )
                        )
                    ),
                )
            )
        ),
    )
);
