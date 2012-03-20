<?php
/**
 * 
 * grades
 * 
 * Module to hold grades - can be used in conjunction with the schools module
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
 * @package   grades
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
* Controller class for Chisimba for the module grades
*
* @author Kevin Cyster
* @package grades
*
*/
class grades extends controller
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
    * Intialiser for the grades controller
    * @access public
    * 
    */
    public function init()
    {
        $this->objUser = $this->getObject('user', 'security');
        $this->objLanguage = $this->getObject('language', 'language');
        // Create the configuration object
        $this->objConfig = $this->getObject('config', 'config');
        
        // Create an instance of the database class
        $this->objDBgrades = $this->getObject('dbgrades', 'grades');
        $this->objDBsubjects = $this->getObject('dbsubjects', 'grades');
        $this->objDBclasses = $this->getObject('dbclasses', 'grades');
        $this->objDBgradesubject = $this->getObject('dbgradesubject', 'grades');
        $this->objDBsubjectclass = $this->getObject('dbsubjectclass', 'grades');
        $this->objDBsubjectcontext = $this->getObject('dbsubjectcontext', 'grades');
        $this->objOps = $this->getObject('gradesops', 'grades');

        $this->appendArrayVar('headerParams',
          $this->getJavaScriptFile('grades.js',
          'grades'));
        //Get the activity logger class
        $this->objLog=$this->newObject('logactivity', 'logger');
        //Log this module call
        $this->objLog->log();
    }
    
    
    /**
     * 
     * The standard dispatch method for the grades module.
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
        return "main_tpl.php";
    }/**
     *
     * Method corresponding to the manage action. It shows the 
     * template corresponding to the component selected
     * 
     * @acces private 
     */
    private function __list()
    {
        return 'list_tpl.php';
    }
    
    /**
     *
     * Method corresponding to the form action. It shows 
     * template corresponsding to the component and action
     * 
     * @access private
     */
    private function __form()
    {
        return 'form_tpl.php';
    }
    
    /**
     *
     * Method corresponding to the validate action. if validation
     * is successful it adds the data to the database else it returns
     * form action
     * 
     * @access private 
     */
    private function __validate()
    {
        $type = $this->getParam('type');
        $cancel = $this->getParam('cancel');
        $id = $this->getParam('id');
        if ($cancel == 'Cancel')
        {
            $this->setSession('errors', array());
            return $this->nextAction('list', array('type' => $type));
        }
        
        $data = array();
        $data['name'] = $this->getParam('name');
        $data['description'] = $this->getParam('description');
        
        $errorsFound = $this->objOps->validate($data);
        if (!$errorsFound)
        {
            switch ($type)
            {
                case 'g':
                    $dbClass = $this->objDBgrades;
                    break;
                case 's':
                    $dbClass = $this->objDBsubjects;
                    break;
                case 'c':
                    $dbClass = $this->objDBclasses;
                    break;
            }
            
            if (!empty($id))
            {
                $data['modified_by'] = $this->objUser->PKId();
                $data['date_modified'] = date('Y-m-d H:i:s');
                $dbClass->updateData($id, $data);             
            }
            else
            {
                $data['created_by'] = $this->objUser->PKId();
                $data['date_created'] = date('Y-m-d H:i:s');
                $dbClass->insertData($data);
            }
            $this->setSession('errors', array());
        }
        return $this->nextAction('list', array('type' => $type));
    }
       
    /**
    * 
    * Method corresponding to the delete action. It requires a 
    * confirmation, and then delets the item, and then sets 
    * nextAction to be null, which returns the {yourmodulename} module 
    * in view mode. 
    * 
    * @access private
    */
    private function __delete()
    {
        $type = $this->getParam('type');
        $id = $this->getParam('id');
        
        switch ($type)
        {
            case 'g':
                $this->objDBgrades->deleteData($id);
                break;
            case 's':
                $this->objDBsubjects->deleteData($id);
                $this->objDBgradesubject->deleteLinks('subject_id', $id);
                $this->objDBsubjectclass->deleteLinks('subject_id', $id);
                $this->objDBsubjectcontext->deleteLinks('subject_id', $id);
                break;
            case 'c':
                $this->objDBclasses->deleteData($id);
                break;
        }       
        return $this->nextAction('list', array('type' => $type));
    }
    
    /**
     *
     * Method that corresponds to the link action. It returns the 
     * page to link vaarious lerning components
     * 
     * @access private 
     */
    private function __link()
    {
        return 'link_tpl.php';
    }
    
    /**
     *
     * Method that corresponds to the savelink action. It returns the 
     * page to link various learning components after saving a link
     * 
     * @access private 
     */
    private function __savelink()
    {
        $type = $this->getParam('type');
        $link = $this->getParam('link');
        
        switch ($type)
        {
            case 's':
                $id = $this->getParam('subject_id');
                $data['subject_id'] = $id;
                $data['created_by'] = $this->objUser->PKId();
                $data['date_created'] = date('Y-m-d H:i:s');
                switch ($link)
                {
                    case 'g':
                        $data['grade_id'] = $this->getParam('grade_id');
                        $this->objDBgradesubject->insertData($data);
                        $tab = 0;
                        break;
                    case 'c':
                        $data['class_id'] = $this->getParam('class_id');
                        $this->objDBsubjectclass->insertData($data);
                        $tab = 1;
                        break;
                    case 'x':
                        $data['context_id'] = $this->getParam('context_id');
                        $this->objDBsubjectcontext->insertData($data);
                        $tab = 2;
                        break;
                }
                break;
        }
        return $this->nextAction('link', array('type' => $type, 'id' => $id, 'tab' => $tab));        
    }
    
    /**
     *
     * Method that corresponds to the savelink action. It returns the 
     * page to link various learning components after saving a link
     * 
     * @access private 
     */
    private function __deletelink()
    {
        $type = $this->getParam('type');
        $link = $this->getParam('link');
        $id = $this->getParam('id');
        $del = $this->getParam('del');

        switch ($type)
        {
            case 's':
                switch ($link)
                {
                    case 'g':
                        $this->objDBgradesubject->deleteLink($del);
                        $tab = 0;
                        break;
                    case 'c':
                        $this->objDBsubjectclass->deleteLink($del);
                        $tab = 1;
                        break;
                    case 'x':
                        $this->objDBsubjectcontext->deleteLink($del);
                        $tab = 2;
                        break;
                }
                break;
        }
        return $this->nextAction('link', array('type' => $type, 'id' => $id, 'tab' => $tab));
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
