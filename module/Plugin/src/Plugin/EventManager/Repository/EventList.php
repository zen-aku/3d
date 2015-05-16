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

namespace Plugin\EventManager\Repository;

use Zend\Db\Sql\Sql,
    Application\Entity\Event,
    Application\Plugin\RepositoryAbstract,
    Application\Repository\EventListInterface;

class EventList extends RepositoryAbstract implements EventListInterface {

    /**
     *
     * @return type
     */
    public function fetchAll() {
        $adapter = $this->getAdapter();
        $sql = new Sql($adapter);

        $query = $sql->getSqlStringForSqlObject($sql->select('event'));

        $response = array();
        foreach ($adapter->query($query, $adapter::QUERY_MODE_EXECUTE) as $row) {
            $response[] = new Event($row);
        }

        return $response;
    }

    /**
     *
     * @param type $row
     * 
     * @return type
     */
    public function insert($row = array()) {
        $adapter = $this->getAdapter();

        $sql = new Sql($adapter);
        $insert = $sql->insert('event');
        $insert->values(array(
            'datetime' => $row[0],
            'username' => $row[1],
            'event' => $row[2],
            'attributes' => $row[3]
        ));

        $query = $sql->getSqlStringForSqlObject($insert);

        return $adapter->query($query, $adapter::QUERY_MODE_EXECUTE);
    }

}
