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

class User extends AbstractOptions {

    /**
     *
     * @var type
     */
    private $_username;

    /**
     *
     * @var type
     */
    private $_password;

    /**
     *
     * @var type
     */
    private $_role;

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
    public function getPassword() {
        return $this->_password;
    }

    /**
     *
     * @param type $password
     */
    public function setPassword($password) {
        $this->_password = $password;
    }

    /**
     *
     * @return type
     */
    public function getRole() {
        return $this->_role;
    }

    /**
     *
     * @param type $role
     */
    public function setRole($role) {
        $this->_role = $role;
    }

}
