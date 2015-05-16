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

use Zend\Cache\Storage\Event;
use Zend\Cache\Storage\Plugin\AbstractPlugin;
use Zend\EventManager\EventManagerInterface;

class CacheTtl extends AbstractPlugin {

    /**
     *
     * @var type
     */
    protected $handles = array();

    /**
     *
     * @param EventManagerInterface $events
     * @return \Application\Plugin\CacheTtl
     */
    public function attach(EventManagerInterface $events) {
        $this->handles[] = $events->attach('setItem.pre', array($this, 'setItemPre'));

        return $this;
    }

    /**
     *
     * @param EventManagerInterface $events
     * @return \Application\Plugin\CacheTtl
     */
    public function detach(EventManagerInterface $events) {
        foreach ($this->handles as $handle) {
            $events->detach($handle);
        }
        $this->handles = array();

        return $this;
    }

    /**
     *
     * @param Event $event
     */
    public function setItemPre(Event $event) {
        $event->getTarget()->getOptions()->setTtl(
                $this->getKeyTtl(
                        $event->getParams()->offsetGet('key'),
                        $event->getTarget()->getOptions()->getTtl()
                )
        );
    }

    /**
     *
     * @param type $key     Cache key
     * @param type $default Default Ttl from Cache Adapter options
     */
    protected function getKeyTtl($key, $default) {
        $response = $default;

        $options = $this->getOptions()->getSerializerOptions();
        foreach ($options['key_ttls'] as $id => $ttl) {
            if ($this->wildcardMatch($key, $id) || ($key == $id)) {
                $response = $ttl;
                break;
            }
        }

        return $response;
    }

    /**
     *
     * @param type $key
     * @param type $id
     * @return boolean
     */
    protected function wildcardMatch($key, $id) {
        $match = false;

        if ($id == '*') {
            $match = true;
        } elseif (strpos($id, '*')) {
            $wildcard = substr($id, 0, strpos($id, '*'));
            if (strpos($key, $wildcard) === 0) {
                $match = true;
            }
        }

        return $match;
    }

    /**
     *
     * @param type $cacheKeyTtl
     */
    public function setCacheKeyTtl($cacheKeyTtl) {
        $this->cacheKeyTtl = $cacheKeyTtl;
    }

    /**
     *
     * @return type
     */
    public function getCacheKeyTtl() {
        return $this->cacheKeyTtl;
    }

}
