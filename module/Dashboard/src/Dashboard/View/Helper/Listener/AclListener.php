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

namespace Dashboard\View\Helper\Listener;

use Zend\EventManager\Event;

/**
 * Default Access Control Listener
 */
class AclListener {

    /**
     * Determines whether a page should be accepted by ACL when iterating
     *
     * - If helper has no ACL, page is accepted
     * - If page has a resource or privilege defined, page is accepted if the
     *   ACL allows access to it using the helper's role
     * - If page has no resource or privilege, page is accepted
     * - If helper has ACL and role:
     *      - Page is accepted if it has no resource or privilege.
     *      - Page is accepted if ACL allows page's resource or privilege.
     *
     * @param  Event    $event
     * @return bool
     */
    public static function accept(Event $event) {
        $accepted = true;
        $params = $event->getParams();
        $acl = $params['acl'];
        $page = $params['page'];
        $role = $params['role'];

        if (!$acl) {
            return $accepted;
        }

        $resource = $page->getResource();
        $privilege = $page->getPrivilege();

        if ($acl->hasResource($resource) && ($resource || $privilege)) {
            $accepted = $acl->isAllowed($role, $resource, $privilege);
        }

        return $accepted;
    }

}
