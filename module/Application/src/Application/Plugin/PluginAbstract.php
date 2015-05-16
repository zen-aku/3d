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

namespace Application\Plugin;

use Zend\Db\Adapter\AdapterInterface,
    Zend\EventManager\EventManagerInterface,
    Zend\ServiceManager\ServiceLocatorInterface,
    Zend\EventManager\EventManagerAwareInterface,
    Zend\ServiceManager\ServiceLocatorAwareInterface;

abstract class PluginAbstract implements ServiceLocatorAwareInterface, EventManagerAwareInterface {

    /**
     *
     * @var type
     */
    protected $serviceLocator = null;

    /**
     *
     * @var type
     */
    protected $eventManager = null;

    /**
     *
     * @var type
     */
    protected $dataAdapter = null;

    /**
     *
     * @var type
     */
    protected $repository = null;

    /**
     *
     * @param type $name
     * @param type $arguments
     * @throws \Exception
     */
    public function __call($name, $arguments) {
        $repository = $this->getRepository();

        if ($repository) {
            $response = call_user_func_array(array($repository, $name), $arguments);
        } else {
            Throw new \Exception('Undefined function ' . $name);
        }

        return $response;
    }

    /**
     *
     * @return type
     */
    public function getRepository() {
        if (is_null($this->repository)) {
            $identifier = $this->getIdentifier();

            //create repository
            $repository = $this->getServiceLocator()->get(
                "{$identifier}\Repository"
            );
            $repository->setReference($this);

            $this->repository = $repository;
        }

        return $this->repository;
    }

    /**
     * 
     */
    abstract public function getIdentifier();

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
     * Inject an EventManager instance
     *
     * @param  EventManagerInterface $eventManager
     * @return void
     */
    public function setEventManager(EventManagerInterface $eventManager) {
        $this->eventManager = $eventManager;
    }

    /**
     *
     * @return type
     */
    public function getEventManager() {
        return $this->eventManager;
    }

    /**
     *
     * @param AdapterInterface $dataAdapter
     */
    public function setDataAdapter(AdapterInterface $dataAdapter) {
        $this->dataAdapter = $dataAdapter;
    }

    /**
     *
     * @return type
     */
    public function getDataAdapter() {
        if (is_null($this->dataAdapter)) {
            $config = $this->getServiceLocator()->get('Config');
            $identifier = $this->getIdentifier();

            if (!empty($config['plugin_adapter'][$identifier])) {
                $this->setDataAdapter(
                        $this->getServiceLocator()->get(
                                $config['plugin_adapter'][$identifier]
                        )
                );
            } else {
                Throw new \Exception("Plugin Adapter undefined for {$identifier}");
            }
        }
        
        return $this->dataAdapter;
    }

}
