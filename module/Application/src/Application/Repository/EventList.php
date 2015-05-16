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

namespace Application\Repository;

use Application\Entity\Event,
    Application\Plugin\RepositoryAbstract;

class EventList extends RepositoryAbstract implements EventListInterface {

    /**
     *
     * @return type
     */
    public function fetchAll() {
        $response = array();
        foreach ($this->getAdapter()->read('event') as $row) {
            $parsed = explode(',', $row);
            $response[] = new Event(array(
                'Datetime' => $parsed[0],
                'Username' => $parsed[1],
                'Event' => $parsed[2],
                'Attributes' => $parsed[3]
            ));
        }

        return $response;
    }

    /**
     *
     * @param type $row
     * @param type $filename
     * @return type
     */
    public function insert($row = null) {
        return $this->getAdapter()->writeLn('event', implode(',', $row) . PHP_EOL);
    }

}
