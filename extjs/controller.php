<?php
/**
 * Short description for file
 *
 * Long description (if any) ...
 *
 * PHP version 5
 *
 * The license text...
 *
 * @category  Chisimba
 * @package   extjs
 * @author    Qhamani Fenama<qfenama@uwc.ac.za>
 * @copyright 2009 Qhamani Fenama
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id: controller.php 11973 2009-11-04 
 * @link      http://avoir.uwc.ac.za
 * @see       References to other sections (if any)...
 */
/* -------------------- security class extends module ----------------*/

// security check - must be included in all scripts
if (!
/**
 * Description for $GLOBALS
 * @global unknown $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 */
$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}
// end security check

/**
* Module class to handle displaying the module list
*
* @author Qhamani Fenama
*
* $Id: controller.php 11973 2008-12-29 22:07:46Z charlvn $
*/
class extjs extends controller
{

     /**
     * Short description for function
     *
     * Long description (if any) ...
     *
     * @return void
     * @access public
     */
    function init()
    {
		try{
		    $this->objDBUser= $this->getObject('user','security');
			$this->objTabs = $this->getObject('tabs');
			}
       catch(customException $e) {
           echo customException::cleanUp();
           die();
        }
    }


    /**
     * Short description for function
     *
     * Long description (if any) ...
     *
     * @param  unknown $action Parameter description (if any) ...
     * @return string  Return description (if any) ...
     * @access public
     */
    function dispatch()
    {
		
       	//$this->setVar('greet', $this->objTabs->getExtjsResource());
		//return $this->objTabs->getExtjsResource();
       
/*        $action = $this->getParam("action", NULL);
		switch ($action) {
			default:		
				return "extj_tpl.php";
				break;

		}*/

	}
}
?>
