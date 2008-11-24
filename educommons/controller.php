<?php

/**
 * eduCommons controller class
 * 
 * Class to control the eduCommons module
 * 
 * PHP version 5
 * 
 * This program is free software; you can redistribute it and/or modify 
 * it under the terms of the GNU General Public License as published by 
 * the Free Software Foundation; either version 2 of the License, or 
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful, 
 * but WITHOUT ANY WARRANTY; without even the implied warranty of 
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the 
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License 
 * along with this program; if not, write to the 
 * Free Software Foundation, Inc., 
 * 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 * 
 * @category  chisimba
 * @package   educommons
 * @author    Charl van Niekerk <charlvn@charlvn.za.net>
 * @copyright 2008 Charl van Niekerk
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id$
 * @link      http://avoir.uwc.ac.za
 */

// security check - must be included in all scripts
if (!
/**
 * Description for $GLOBALS
 * @global unknown $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 */
$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

/**
 * eduCommons controller class
 *
 * Class to control the eduCommons module
 *
 * @category  Chisimba
 * @package   educommons
 * @author    Charl van Niekerk <charlvn@charlvn.za.net>
 * @copyright 2008 Charl van Niekerk
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   Release: @package_version@
 * @link      http://avoir.uwc.ac.za
 */

class educommons extends controller
{
    protected $objImport;
    protected $objUpload;

    /**
     * Standard constructor to load the necessary resources
     * and populate the new object's instance variables.
     *
     * @access public
     */
    public function init()
    {
        $this->objImport = $this->getObject('educommonsimport', 'educommons');
        $this->objUpload = $this->getObject('uploadinput', 'filemanager');
    }

    /**
     * Standard dispatch method to handle the various possible actions.
     *
     * @access public
     */
    public function dispatch()
    {
        $action = $this->getParam('action');
        $context = $this->getParam('context');
        switch ($action) {
            case 'rss':
                $uri = 'http://free.uwc.ac.za/freecourseware/biodiversity-conservation-biology/conservation-biology/RSS';
                $this->objImport->doRssChapters($uri);
                break;
            case 'import':
                // Temporary handling for development purposes
                set_time_limit(900);
                $data = $this->objImport->parseIms();
                $this->objImport->addCourses($data, $context);
                $this->objImport->addPages($data, $context);
                header('Content-Type: text/plain; charset=UTF-8');
                print_r($data);
                break;
            case 'upload':
                $details = $this->objUpload->handleUpload();
                print_r($details);
                break;
            default:
               return 'upload_tpl.php';
        }
    }
}

?>
