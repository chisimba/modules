<?php
/**
 * 
 * Make module
 * 
 * Enable developers to quickly build a module that complies with Chisimba development standards.
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
 * @category  Chisimba
 * @package   helloforms
 * @author    Derek Keats derek@dkeats.com
 * @copyright 2007 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   CVS: $Id: controller.php,v 1.4 2007-11-25 09:13:27 dkeats Exp $
 * @link      http://avoir.uwc.ac.za
 */
 
// security check - must be included in all scripts
if (!
/**
 * The $GLOBALS is an array used to control access to certain constants.
 * Here it is used to check if the file is opening in engine, if not it
 * stops the file from running.
 * 
 * @global entry point $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 *         
 */
$GLOBALS['kewl_entry_point_run'])
{
        die("You cannot view this page directly");
}
// end security check

/**
* 
* Controller class for Chisimba for the module makemodule
*
* @author Derek Keats
* @package makemodule
*
*/
class makemodule extends controller
{
    
    /**
    * 
    * @var string $objConfig String object property for holding the 
    * configuration object
    * @access public;
    * 
    */
    public $objConfig;
    
    /**
    * 
    * @var string $objLanguage String object property for holding the 
    * language object
    * @access public
    * 
    */
    public $objLanguage;
    /**
    *
    * @var string $objLog String object property for holding the 
    * logger object for logging user activity
    * @access public
    * 
    */
    public $objLog;

    /**
    * 
    * Intialiser for the makemodule controller
    * @access public
    * 
    */
    public function init()
    {
        $this->objUser = $this->getObject('user', 'security');
        $this->objLanguage = $this->getObject('language', 'language');
        // Create the configuration object
        $this->objConfig = $this->getObject('config', 'config');
        // Set the jQuery version to the latest functional
        $this->setVar('JQUERY_VERSION', '1.4.2');
        // Load the helper Javascript
        $this->objOps = $this->getObject('makemoduleops', 'makemodule');
        $this->objOps->loadHelperScript();
        // Create an instance of the database class
        $this->objDbmakemodule = & $this->getObject('dbmakemodule', 'makemodule');
        //Get the activity logger class
        $this->objLog=$this->newObject('logactivity', 'logger');
        //Log this module call
        $this->objLog->log();
    }
    
    
    /**
     * 
     * The standard dispatch method for the makemodule module.
     * The dispatch method uses methods determined from the action 
     * parameter of the  querystring and executes the appropriate method, 
     * returning its appropriate template. This template contains the code 
     * which renders the module output.
     * 
     */
    public function dispatch()
    {
        //Get action from query string and set default to view
        $action=$this->getParam('action', 'view');
        /*
        * Convert the action into a method (alternative to 
        * using case selections)
        */
        $method = $this->__getMethod($action);
        // Set the layout template to compatible one
        $this->setLayoutTemplate('layout_template.php');
        /*
        * Return the template determined by the method resulting 
        * from action
        */
        return $this->$method();
    }
    
    
    /*------------- BEGIN: Set of methods to replace case selection ------------*/

    /**
    * 
    * Method corresponding to the view action. It shows the default
    * dynamic canvas template, showing you how to create block based
    * view templates
    * @access private
    * 
    */
    private function __view()
    {

        if ($this->objUser->userName() == 'admin') {
            // We don't want modules created by the anonymous 'admin' user.
            $ret = "notadmin_tpl.php";
        } else {
            if ($this->objOps->isDeveloper() || $this->objUser->isAdmin()) {
                $ret = "main_tpl.php";
            } else {
                $ret = "guest_tpl.php";
            }
        }
        return $ret;
    }

    /**
    *
    * Method corresponding to the save action. Create the module.
    *
    * @access private
    *
    */
    private function __save()
    {
        echo $this->objOps->createModule();
        die();
    }





    // No edits after this
    
    /**
    * 
    * Method corresponding to the edit action. It sets the mode to 
    * edit and returns the edit template.
    * @access private
    * 
    */
    private function __edit()
    {
        $this->setvar('mode', "edit");
        return 'editform_tpl.php';
    }

    /**
    * 
    * Method corresponding to the add action. It sets the mode to 
    * add and returns the edit content template.
    * @access private
    * 
    */
    private function __add()
    {
        $this->setvar('mode', 'add');
        return 'editform_tpl.php';
    }
    
   

    
    /**
    * 
    * Method corresponding to the delete action. It requires a 
    * confirmation, and then delets the item, and then sets 
    * nextAction to be null, which returns the {yourmodulename} module 
    * in view mode. 
    * 
    * @access private
    * 
    */
    private function __delete()
    {
        // retrieve the confirmation code from the querystring
        $confirm=$this->getParam("confirm", "no");
        if ($confirm=="yes") {
            $this->deleteItem();
            return $this->nextAction(NULL);
        }
    }
    
    
    /**
    * 
    * Method to return an error when the action is not a valid 
    * action method
    * 
    * @access private
    * @return string The dump template populated with the error message
    * 
    */
    private function __actionError()
    {
        $this->setVar('str', "<h3>"
          . $this->objLanguage->languageText("phrase_unrecognizedaction")
          .": " . $action . "</h3>");
        return 'dump_tpl.php';
    }
    
    /**
    * 
    * Method to check if a given action is a valid method
    * of this class preceded by double underscore (__). If it __action 
    * is not a valid method it returns FALSE, if it is a valid method
    * of this class it returns TRUE.
    * 
    * @access private
    * @param string $action The action parameter passed byref
    * @return boolean TRUE|FALSE
    * 
    */
    function __validAction(& $action)
    {
        if (method_exists($this, "__".$action)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    /**
    * 
    * Method to convert the action parameter into the name of 
    * a method of this class.
    * 
    * @access private
    * @param string $action The action parameter passed byref
    * @return stromg the name of the method
    * 
    */
    function __getMethod(& $action)
    {
        if ($this->__validAction($action)) {
            return "__" . $action;
        } else {
            return "__actionError";
        }
    }
    
    /*------------- END: Set of methods to replace case selection ------------*/
    


    /**
    *
    * This is a method to determine if the user has to 
    * be logged in or not. Note that this is an example, 
    * and if you use it view will be visible to non-logged in 
    * users. Delete it if you do not want to allow annonymous access.
    * It overides that in the parent class
    *
    * @return boolean TRUE|FALSE
    *
    */
    public function requiresLogin()
    {
        $action=$this->getParam('action','NULL');
        switch ($action)
        {
            case 'view':
                return FALSE;
                break;
            default:
                return TRUE;
                break;
        }
     }
}
?>
