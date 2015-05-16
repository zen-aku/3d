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

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController {

    /**
     *
     * @return type
     */
    public function indexAction() {
        $this->getResponse()->setContent(null);

        return $this->getResponse();
    }

    /**
     *
     * @return type
     */
    public function clearCacheAction() {
        $key = $this->params()->fromPost('key');

        if ($key == '*') {
            $res = $this->getServiceLocator()->get('cache')->flush();
        } else {
            $res = $this->getServiceLocator()->get('cache')->removeItem($key);
        }

        $response = $this->getResponse();
        $response->setContent(
                json_encode(array('status' => ($res ? 'success' : 'failure')))
        );

        return $this->getResponse();
    }

}
