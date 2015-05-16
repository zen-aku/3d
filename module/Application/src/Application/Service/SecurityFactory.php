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

use Zend\Permissions\Acl\Acl,
    Zend\ServiceManager\FactoryInterface,
    Zend\ServiceManager\ServiceLocatorInterface;

class SecurityFactory implements FactoryInterface {

    /**
     *
     * @var type
     */
    protected $serviceLocator;

    /**
     *
     * @var type
     */
    protected $acl = null;

    /**
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return \Application\Service\Authentication
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $this->setServiceLocator($serviceLocator);

        if ($this->getServiceLocator()->get('auth')->hasIdentity()) {
            $this->initializeACL();
        }

        return $this;
    }

    /**
     * 
     */
    protected function initializeAcl() {
        $config = $this->getServiceLocator()->get('Config');
        if (!empty($config['acl']['resources'])) {
            foreach ($config['acl']['resources'] as $resourceId => $roles) {
                $this->registerResource($resourceId, $roles);
            }
        }
    }

    /**
     *
     * @param type $resourceId
     * @param type $roles
     */
    protected function registerResource($resourceId, $roles) {
        $this->createResourceTree($resourceId);

        //add roles to rules to resource
        foreach ($roles as $roleId => $rules) {
            if (!$this->getAcl()->hasRole($roleId)) {
                $this->getAcl()->addRole($roleId);
            }
            foreach ($rules as $rule => $allow) {
                if ($allow) {
                    $this->getAcl()->allow($roleId, $resourceId, $rule);
                } else {
                    $this->getAcl()->deny($roleId, $resourceId, $rule);
                }
            }
        }
    }

    /**
     *
     * @param type $resourceId
     * @return type
     */
    protected function createResourceTree($resourceId) {
        $xpath = explode('\\', $resourceId);

        if (count($xpath) > 1) {
            $parent = null;
            foreach ($xpath as $node) {
                $resource = (is_null($parent) ? $node : "{$parent}\\{$node}");
                if (!$this->getAcl()->hasResource($resource)) {
                    $this->getAcl()->addResource($resource, $parent);
                }
                $parent = $resource;
            }
        } elseif (!$this->getAcl()->hasResource($resourceId)) {
            $this->getAcl()->addResource($resourceId);
        }

        return $resourceId;
    }

    /**
     *
     * @param type $resource
     * @param type $rule
     * @param type $role
     */
    public function isAllowed($resource, $rule = null, $role = null) {
        if (is_null($role)) {
            $role = $this->getCurrentUserRole();
        }

        $acl = $this->getAcl();

        if ($acl->hasRole($role) && $acl->hasResource($resource)) {
            $result = $acl->isAllowed($role, $resource, $rule);
        } else {
            $result = true;
        }

        return $result;
    }

    /**
     *
     * @return type
     */
    public function getCurrentUserRole() {
        $identity = $this->getServiceLocator()->get('auth')->getIdentity();

        return $identity->role;
    }

    /**
     *
     * @return \Zend\Permissions\Acl\Acl
     */
    public function getAcl() {
        if (is_null($this->acl)) {
            $this->acl = new Acl();
            //by default allow access to all resources
            $this->acl->allow();
        }

        return $this->acl;
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
