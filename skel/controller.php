<?php
/**
 * skeleton module controller class
 *
 * Class to control the skeleton module
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
 * @package   skel
 * @author    Paul Scott <pscott@uwc.ac.za>
 * @copyright 2010 Paul Scott
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
 * Skeleton controller class
 *
 * Class to control the skeleton module.
 *
 * @category  Chisimba
 * @package   skel
 * @author    Paul Scott <pscott@uwc.ac.za>
 * @copyright 2010 Paul Scott
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id$
 * @link      http://avoir.uwc.ac.za
 */

class skel extends controller
{
    /**
     * @var string $objLanguage String object property for holding the language object
     *
     * @access public
     */
    public $objLanguage;
    
    /**
     * @var string $objSysConfig String object property for holding the sysconfig object
     *
     * @access public
     */
    public $objSysConfig;
    
    /**
     * @var string $objUser String object property for holding the user object
     *
     * @access public
     */
    public $objUser;
    
    /**
     * @var string $objConfig String object property for holding the config object
     *
     * @access public
     */
    public $objConfig;

    /**
     * Initialises the instance variables.
     *
     * @access public
     */
    public function init()
    {
        try {
            $this->objLanguage   = $this->getObject ( 'language', 'language' );
            $this->objConfig     = $this->getObject('altconfig', 'config');
            $this->objSysConfig  = $this->getObject ( 'dbsysconfig', 'sysconfig' );
            $this->objUser       = $this->getObject('user', 'security');
            $this->objModuleCat  = $this->getObject('modules', 'modulecatalogue');
            $this->objSkelOps    = $this->getObject('skelops');
			
            if($this->objModuleCat->checkIfRegistered('activitystreamer'))
            {
                $this->objActStream = $this->getObject('activityops','activitystreamer');
                $this->eventDispatcher->addObserver(array($this->objActStream, 'postmade' ));
                $this->eventsEnabled = TRUE;
            } else {
                $this->eventsEnabled = FALSE;
            }
        }
        catch ( customException $e ) {
            customException::cleanUp ();
            exit ();
        }
    }

    /**
     * Standard dispatch method
     *
     * @access public
     * @param void
     * @return void
     */
    public function dispatch() {
        $action = $this->getParam ( 'action' );
        switch ($action) {
            case NULL:

            case 'donothing' :
                $string = $this->objLanguage->languageText("mod_skel_hello", "skel")." ".$this->objUser->fullName();
                $this->setVarByRef('string', $string);
                return 'skel_tpl.php';
                break;
              
            default:
                $this->nextAction('');
                break;
        }
    }
}
?>
