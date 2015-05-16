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

use Zend\Cache\PatternFactory,
    Zend\ServiceManager\FactoryInterface,
    Zend\ServiceManager\ServiceLocatorInterface;

class CacheFactory implements FactoryInterface {

    /**
     *
     * @var type
     */
    protected $serviceLocator;

    /**
     *
     * @var type 
     */
    protected $patterns = array();

    /**
     *
     * @var type 
     */
    protected static $defaultPattern = 'Application\Plugin\KeyCachePattern';

    /**
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return \Application\Service\Authentication
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $this->setServiceLocator($serviceLocator);

        return $this;
    }

    /**
     *
     * @param type $name
     */
    public function __invoke($name = null) {
        //if there is no argument specified, run default __call function that will
        //trigger default pattern, otherwise proceed with defined pattern
        return (is_null($name) ? $this : $this->getPattern($name));
    }

    /**
     *
     * @param type $name
     * @return type
     */
    protected function getPattern($name = null) {
        $pattern = (is_null($name) ? self::$defaultPattern : $name);
        
        if (!isset($this->patterns[$pattern])) {
            $this->patterns[$pattern] = PatternFactory::factory(
                            $pattern, $this->getPatternConfig($pattern)
            );
        }

        return $this->patterns[$pattern];
    }

    /**
     *
     * @param type $name
     * @param type $arguments
     * @return type
     */
    public function __call($name, $arguments) {
        return call_user_func_array(array($this->getPattern(), $name), $arguments);
    }

    /**
     *
     * @param type $patterName
     * @return type
     */
    protected function getPatternConfig($patterName = 'default') {
        $config = $this->getServiceLocator()->get('Config');

        if (!empty($config['cache']['patterns']['default'])) {
            $default = $config['cache']['patterns']['default'];
        } else {
            Throw new \Exception('Default cache pattern is not defined');
        }

        if (!empty($config['cache']['patterns'][$patterName])) {
            $response = array_merge(
                    $default, $config['cache']['patterns'][$patterName]
            );
        } else {
            $response = $default;
        }

        return $response;
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
