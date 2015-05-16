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

abstract class RepositoryAbstract implements ServiceLocatorAwareInterface, EventManagerAwareInterface {

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
     * @var Application\Plugin\PluginAbstract
     */
    protected $reference = null;

    /**
     *
     * @var type 
     */
    protected $adapter;

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
     * @param Application\Plugin\PluginAbstract $reference
     */
    public function setReference(PluginAbstract $reference) {
        $this->reference = $reference;
    }

    /**
     *
     * @return Application\Plugin\PluginAbstract
     */
    public function getReference() {
        return $this->reference;
    }

    /**
     *
     * @param AdapterInterface $adapter
     */
    public function setAdapter(AdapterInterface $adapter) {
        $this->adapter = $adapter;
    }

    /**
     *
     * @return AdapterInterface
     */
    public function getAdapter() {
        if (is_null($this->adapter)) {
            $this->setAdapter($this->getReference()->getDataAdapter());
        }
        
        return $this->adapter;
    }

    /**
     *
     * @param string $cacheKey
     *
     * @return boolean
     */
    public function hasCache($cacheKey) {
        return $this->getServiceLocator()->get('cache')->hasItem($cacheKey);
    }

    /**
     *
     * @param string $cacheKey
     *
     * @return mixed
     */
    public function getCache($cacheKey) {
        if ($this->hasCache($cacheKey)) {
            $cache = $this->getServiceLocator()->get('cache')->getItem($cacheKey);
        } else {
            $cache = null;
        }

        return $cache;
    }

    /**
     *
     * @param type $cacheKey
     * @param type $data
     */
    public function addCache($cacheKey, $data) {
        return $this->getServiceLocator()->get('cache')->setItem($cacheKey, $data);
    }

}
