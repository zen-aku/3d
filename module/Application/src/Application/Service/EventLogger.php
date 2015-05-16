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

use Zend\Log\Writer\AbstractWriter,
    Zend\ServiceManager\ServiceLocatorInterface,
    Zend\ServiceManager\ServiceLocatorAwareInterface;

class EventLogger extends AbstractWriter implements ServiceLocatorAwareInterface {

    /**
     *
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     *
     * @param array $event
     * @return type
     */
    public function doWrite(array $event) {
        //compile the log line with timestamp first
        $line = array($event['timestamp']->format('Y-m-d H:i:s'));
        $auth = $this->serviceLocator->get('auth');
        //get current username
        if ($auth->hasIdentity()) {
            $line[] = $auth->getIdentity()->username;
        } elseif (!empty($event['extra']['username'])) {
            $line[] = $event['extra']['username'];
        } else {
            $line[] = 'incognito';
        }

        //event name
        $line[] = $event['message'];
        //serialize and encode the list of attributes
        $line[] = http_build_query($event['extra']);

        return $this->serviceLocator->get('Plugin\EventManager')->insert($line);
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
