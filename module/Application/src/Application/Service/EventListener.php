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

use Zend\Log\Logger,
    Zend\EventManager\Event,
    Zend\ServiceManager\ServiceLocatorInterface,
    Zend\ServiceManager\ServiceLocatorAwareInterface;

class EventListener implements ServiceLocatorAwareInterface {

    /**
     *
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     *
     * @param Event $event
     */
    public function listen(Event $event) {
        $config = $this->serviceLocator->get('Config');
        $trigger = get_class($event->getTarget()) . '\\' . $event->getName();

        //iterate through the list of events that should be logged
        if (!empty($config['event_log'])) {
            $logger = new Logger;
            $logger->addWriter($this->serviceLocator->get('ApplicationEventLogger'));
            foreach ($config['event_log'] as $eventName) {
                if ($trigger == $eventName) {
                    $logger->info($trigger, $event->getParams());
                }
            }
        }

        //iterate throught the business rules list
        if (!empty($config['business_rules'])) {
            foreach ($config['business_rules'] as $eventName => $serviceName) {
                if ($trigger == $eventName) {
                    //invoke service and execute
                    $this->serviceLocator->get($serviceName)->execute($event);
                }
            }
        }
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
