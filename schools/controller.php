<?php
/**
 * 
 * schools
 * 
 * Simple facility to store school basic data
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
 * @package   schools
 * @author    Kevin Cyster kcyster@gmail.com
 * @copyright 2011 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   0.001
 * @link      http://www.chisimba.com
 *
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
* Controller class for Chisimba for the module schools
*
* @author Kevin Cyster
* @package schools
*
*/
class schools extends controller
{
    
    /**
    *
    * @var string $objUser String object property for holding the 
    * user object
    * @access public
    * 
    */
    public $objUser;

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
    * @var string $objConfig String object property for holding the 
    * configuration object
    * @access public;
    * 
    */
    public $objConfig;
    
    /**
    *
    * @var string $objDBprovinces String object property for holding the 
    * schools provinces database object
    * @access public
    * 
    */
    public $objDBprovinces;

    /**
    *
    * @var string $objDBdistricts String object property for holding the 
    * schools districts database object
    * @access public
    * 
    */
    public $objDBdistricts;

    /**
    *
    * @var string $objDBcontacts String object property for holding the 
    * schools contacts database object
    * @access public
    * 
    */
    public $objDBcontacts;

    /**
    *
    * @var string $objDBdetails String object property for holding the 
    * schools details database object
    * @access public
    * 
    */
    public $objDBdetails;

    /**
    *
    * @var string $objOps String object property for holding the 
    * schools operations object
    * @access public
    * 
    */
    public $objDBops;

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
    * Intialiser for the schools controller
    * @access public
    * 
    */
    public function init()
    {
        // Load core classes.
        $this->objUser = $this->getObject('user', 'security');
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objConfig = $this->getObject('config', 'config');
        
        // Load module classes.
        $this->objDBprovinces = & $this->getObject('dbschools_provinces', 'schools');
        $this->objDBdistricts = & $this->getObject('dbschools_districts', 'schools');
        $this->objDBcontacts = & $this->getObject('dbschools_contacts', 'schools');
        $this->objDBdetail = & $this->getObject('dbschools_detail', 'schools');
        $this->objOps = $this->getObject('schoolsops', 'schools');
        
        $this->appendArrayVar('headerParams',
          $this->getJavaScriptFile('schools.js',
          'schools'));
        //Get the activity logger class
        $this->objLog=$this->newObject('logactivity', 'logger');
        //Log this module call
        $this->objLog->log();
    }
    
    
    /**
     * 
     * The standard dispatch method for the schools module.
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
        $this->setLayoutTemplate('layout_tpl.php');
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
        // All the action is in the blocks
        $this->setSession('schools', array());
        return "main_tpl.php";
    }
    
    /**
    * 
    * Method corresponding to the edit action. It sets the mode to 
    * edit and returns the edit template.
    * @access private
    * 
    */
    private function __edit()
    {
        return 'edit_tpl.php';
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
        return 'add_tpl.php';
    }
    
   
    /**
    * 
    * Method corresponding to the save action. It gets the mode from 
    * the querystring to and saves the data then sets nextAction to be 
    * null, which returns the {yourmodulename} module in view mode. 
    * 
    * @access private
    * 
    */
    private function __save()
    {
        $mode = $this->getParam("mode", NULL);
        $this->objDbschools->save($mode);
        return $this->nextAction(NULL);
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
        $id = $this->getParam('id');
        $this->objDBdetail->deleteDetail($id);
        $this->objDBcontacts->deleteContacts($id);

        return $this->nextAction('view');
    }
    
    /**
     * Method to return the ajax response to get the districts for a province
     * 
     * @access private 
     */
    private function __ajaxDistricts()
    {
        return $this->objOps->ajaxDistricts();
    }   
    
    /**
     * Method to return the ajax response to check for unique username
     * 
     * @access private 
     */
    private function __ajaxUsername()
    {
        return $this->objOps->ajaxUsername();
    }  
    
    /**
     * Method to validate the add details form
     * 
     * @access private 
     */
    private function __validateAddDetails()
    {
        $cancel = $this->getParam('cancel');
        if ($cancel == 'Cancel')
        {
            return $this->nextAction('view');
        }
        
        $data = array();
        $data['province_id'] = $this->getParam('province_id');
        $data['district_id'] = $this->getParam('district_id');
        $data['school_name'] = $this->getParam('school_name');
        $data['address_one'] = $this->getParam('address_one');
        $data['address_two'] = $this->getParam('address_two');
        $data['address_three'] = $this->getParam('address_three');
        $data['address_four'] = $this->getParam('address_four');
        $data['email_address'] = $this->getParam('email_address');
        $data['telephone_number'] = $this->getParam('telephone_number');
        $data['fax_number'] = $this->getParam('fax_number');
        $data['title'] = $this->getParam('title');
        $data['first_name'] = $this->getParam('first_name');
        $data['last_name'] = $this->getParam('last_name');
        $data['gender'] = $this->getParam('gender');
        $data['principal_email_address'] = $this->getParam('principal_email_address');
        $data['mobile_number'] = $this->getParam('mobile_number');
        $data['username'] = $this->getParam('username');
        $data['password'] = $this->getParam('password');
        $data['confirm_password'] = $this->getParam('confirm_password');
        $data['contact_position'] = $this->getParam('contact_position');
        $data['contact_name'] = $this->getParam('contact_name');
        $data['contact_address_one'] = $this->getParam('contact_address_one');
        $data['contact_address_two'] = $this->getParam('contact_address_two');
        $data['contact_address_three'] = $this->getParam('contact_address_three');
        $data['contact_address_four'] = $this->getParam('contact_address_four');
        $data['contact_email_address'] = $this->getParam('contact_email_address');
        $data['contact_telephone_number'] = $this->getParam('contact_telephone_number');
        $data['contact_mobile_number'] = $this->getParam('contact_mobile_number');
        $data['contact_fax_number'] = $this->getParam('contact_fax_number');
        $data['contact_fax_number'] = $this->getParam('contact_fax_number');
        $data['contact_fax_number'] = $this->getParam('contact_fax_number');
        $data['contact_fax_number'] = $this->getParam('contact_fax_number');
        
        $errorsFound = $this->objOps->validateAddDetails($data);
        
        if ($errorsFound == FALSE)
        {
            $this->objOps->saveEditDetails($data);
            return $this->nextAction('view');
        }
        else
        {
            return $this->nextAction('add');
        }
    }
    
    /**
     * Method to validate the edit details form
     * 
     * @access private 
     */
    private function __validateEditDetails()
    {
        $cancel = $this->getParam('cancel');
        if ($cancel == 'Cancel')
        {
            return $this->nextAction('view');
        }
        
        $data = array();
        $data['id'] = $this->getParam('id');
        $data['province_id'] = $this->getParam('province_id');
        $data['district_id'] = $this->getParam('district_id');
        $data['school_name'] = $this->getParam('school_name');
        $data['address_one'] = $this->getParam('address_one');
        $data['address_two'] = $this->getParam('address_two');
        $data['address_three'] = $this->getParam('address_three');
        $data['address_four'] = $this->getParam('address_four');
        $data['email_address'] = $this->getParam('email_address');
        $data['telephone_number'] = $this->getParam('telephone_number');
        $data['fax_number'] = $this->getParam('fax_number');
        
        $errorsFound = $this->objOps->validateEditDetails($data);
        
        if ($errorsFound == FALSE)
        {
            $this->objOps->saveEditDetails($data);
            return $this->nextAction('view');
        }
        else
        {
            return $this->nextAction('edit');
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
