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

namespace Application\Adapter;

use Zend\Db\Adapter\AdapterInterface,
    Zend\ServiceManager\FactoryInterface,
    \Zend\ServiceManager\ServiceLocatorInterface;

class FileAdapterFactory implements AdapterInterface, FactoryInterface {

    /**
     *
     * @var type
     */
    protected $serviceLocator = null;

    /**
     *
     * @var type 
     */
    protected $config;

    /**
     * Create db adapter service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return Adapter
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $this->serviceLocator = $serviceLocator;
        //init config
        $config = $this->serviceLocator->get('Config');
        if (!empty($config['file_adapter']['options'])) {
            $this->config = $config['file_adapter']['options'];
        }

        return $this;
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
     * @param type $name
     * @return array
     */
    public function read($name) {
        $path = $this->config['base_dir'] . "/{$name}.{$this->config['file_ext']}";

        if (file_exists($path)) {
            $result = file($path, FILE_IGNORE_NEW_LINES);
        } else {
            $result = array();
        }

        return $result;
    }

    /**
     *
     * @param type $name
     * @param type $line
     * @return type
     */
    public function writeLn($name, $line) {
        $path = $this->config['base_dir'] . "/{$name}.{$this->config['file_ext']}";

        return file_put_contents($path, $line, FILE_APPEND);
    }

    /**
     * @return Driver\DriverInterface
     * @todo Implement File Driver
     */
    public function getDriver() {
        return null;
    }

    /**
     * @return Platform\PlatformInterface
     * @todo Implement File Platform
     */
    public function getPlatform() {
        return null;
    }

}
