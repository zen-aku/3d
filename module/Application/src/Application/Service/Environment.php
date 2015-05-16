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

use Zend\ServiceManager\ServiceLocatorInterface,
    Zend\ServiceManager\ServiceLocatorAwareInterface;

class Environment implements ServiceLocatorAwareInterface {

    /**
     * 
     */
    const ENV_DEV = 'development';

    /**
     *
     */
    const ENV_PROD = 'production';

    /**
     *
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     *
     * @return type
     */
    public function isDev() {
        return ($this->getEnv() == self::ENV_DEV ? true : false);
    }

    /**
     *
     * @return type
     */
    public function isProd() {
        return ($this->getEnv() == self::ENV_PROD ? true : false);
    }

    /**
     *
     * @return type
     */
    public function getEnv() {
        $config = $this->getServiceLocator()->get('Config');

        return (empty($config['env']) ? self::ENV_PROD : $config['env']);
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
