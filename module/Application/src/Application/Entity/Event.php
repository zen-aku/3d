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

namespace Application\Entity;

use Zend\Stdlib\AbstractOptions;

class Event extends AbstractOptions{

    /**
     *
     * @var type
     */
    private $_datetime;

    /**
     *
     * @var type
     */
    private $_username;

    /**
     *
     * @var type
     */
    private $_event;

    /**
     *
     * @var type
     */
    private $_attributes;

    /**
     *
     * @return type
     */
    public function getDatetime() {
        return $this->_datetime;
    }

    /**
     *
     * @param type $datetime
     */
    public function setDatetime($datetime) {
        $this->_datetime = $datetime;
    }

    /**
     *
     * @return type
     */
    public function getUsername() {
        return $this->_username;
    }

    /**
     *
     * @param type $username
     */
    public function setUsername($username) {
        $this->_username = $username;
    }

    /**
     *
     * @return type
     */
    public function getEvent() {
        return $this->_event;
    }

    /**
     *
     * @param type $event
     */
    public function setEvent($event) {
        $this->_event = $event;
    }

    /**
     *
     * @return type
     */
    public function getAttributes() {
        return $this->_attributes;
    }

    /**
     *
     * @param type $attributes
     */
    public function setAttributes($attributes) {
        $this->_attributes = $attributes;
    }

}
