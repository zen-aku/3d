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

namespace Dashboard\View;

use Dashboard\Controller\EventController;

class Index {

    /**
     *
     * @var type 
     */
    protected $controller;

    /**
     *
     * @param EventController $controller
     */
    public function __construct(EventController $controller) {
        $this->controller = $controller;
    }

    /**
     *
     * @return type
     */
    public function getEventList() {
        $cache = $this->controller->getServiceLocator()->get('cache');
        $key = __NAMESPACE__ . '\EventList';

        if ($cache->hasItem($key)) {
            $list = $cache->getItem($key);
        } else {
            $list = $this->prepareList($key);
        }

        return $list;
    }

    /**
     *
     * @param type $key
     * @return string
     */
    public function getCachedTime($key) {
        $cache = $this->controller->getServiceLocator()->get('cache');
        $meta = $cache->getMetadata($key);

        if ($meta) {
            $time = date('Y-m-d H:i:s', $meta['mtime']);
        } else {
            $time = null;
        }

        return $time;
    }

    /**
     *
     * @return array
     */
    protected function prepareList($key) {
        $manager = $this->controller->getServiceLocator()->get('Plugin\EventManager');

        $list = $manager->fetchAll();
        $this->controller->getServiceLocator()->get('cache')->setItem($key, $list);

        return $list;
    }

}
