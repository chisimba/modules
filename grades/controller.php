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
        $this->objGroupOps = $this->getObject('groupops', 'groupadmin');
        $this->objGroups = $this->getObject('groupadminmodel', 'groupadmin');
        $this->objUser = $this->getObject('user', 'security');
        $this->objLanguage = $this->getObject('language', 'language');
        // Create the configuration object
        $this->objConfig = $this->getObject('config', 'config');
        
        // Create an instance of the database class
        $this->objDBgrades = $this->getObject('dbgrades', 'grades');
        $this->objDBsubjects = $this->getObject('dbsubjects', 'grades');
        $this->objDBclasses = $this->getObject('dbclasses', 'grades');
        $this->objDBbridging = $this->getObject('dbbridging', 'grades');
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
                $newId = $dbClass->insertData($data);
                
                if ($newId)
                {
                    $groupId = $this->objGroups->addGroup($data['name'], $data['description']);
                }
            }
            $this->setSession('errors', array());
            return $this->nextAction('list', array('type' => $type));
        }
        return $this->nextAction('form', array('type' => $type, 'id' => $id));
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
                $this->objDBbridging->deleteLinks('grade_id', $id);
                break;
            case 's':
                $this->objDBsubjects->deleteData($id);
                $this->objDBbridging->deleteLinks('subject_id', $id);
                break;
            case 'c':
                $this->objDBclasses->deleteData($id);
                $this->objDBbridging->deleteLinks('class_id', $id);
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
        
        // save actual link made.
        $data = array();
        switch ($type)
        {
            case 's':
                $id = $this->getParam('subject_id');
                $data['subject_id'] = $id;
                $data['created_by'] = $this->objUser->PKId();
                $data['date_created'] = date('Y-m-d H:i:s');
                switch ($link)
                {
                    case 'h':
                        $data['school_id'] = $this->getParam('school_id');
                        $tab = 0;
                        break;
                    case 'g':
                        $data['grade_id'] = $this->getParam('grade_id');
                        $tab = 1;
                        break;
                    case 'c':
                        $data['class_id'] = $this->getParam('class_id');
                        $tab = 2;
                        break;
                    case 'x':
                        $data['context_id'] = $this->getParam('context_id');
                        $tab = 3;
                        break;
                }
                break;
            case 'g':
                $id = $this->getParam('grade_id');
                $data['grade_id'] = $id;
                $data['created_by'] = $this->objUser->PKId();
                $data['date_created'] = date('Y-m-d H:i:s');
                switch ($link)
                {
                    case 'h':
                        $data['school_id'] = $this->getParam('school_id');
                        $tab = 0;
                        break;
                    case 's':
                        $data['subject_id'] = $this->getParam('subject_id');
                        $tab = 1;
                        break;
                    case 'c':
                        $data['class_id'] = $this->getParam('class_id');
                        $tab = 2;
                        break;
                }
                break;
            case 'c':
                $id = $this->getParam('class_id');
                $data['class_id'] = $id;
                $data['created_by'] = $this->objUser->PKId();
                $data['date_created'] = date('Y-m-d H:i:s');
                switch ($link)
                {
                    case 'h':
                        $data['school_id'] = $this->getParam('school_id');
                        $tab = 0;
                        break;
                    case 's':
                        $data['subject_id'] = $this->getParam('subject_id');
                        $tab = 1;
                        break;
                    case 'g':
                        $data['grade_id'] = $this->getParam('grade_id');
                        $tab = 2;
                        break;
                }
                break;
        }
        $this->objDBbridging->insertData($data);
        
        // save associated links.
        switch ($type)
        {
            case 'g':
                $linked = $this->objDBbridging->getLinked('grade_id', $id);
                if (!empty($linked))
                {      
                    $schoolIds = array();
                    $subjectIds = array();
                    $classIds = array();
                    switch ($link)
                    {
                        case 'h':
                            $linkedId = $this->getParam('school_id');
                            foreach ($linked as $item)
                            {
                                if (!empty($item['subject_id']))
                                {
                                    $subjectIds[] = "'" . $item['subject_id'] . "'";
                                }
                                if (!empty($item['class_id']))
                                {
                                    $classIds[] = "'" . $item['class_id'] . "'";
                                }
                            }
                            $data = array();
                            if (!empty($subjectIds))
                            {
                                $idString = implode(',', $subjectIds);
                                $unlinked = $this->objDBbridging->getUnlinked('subject_id', $idString, 'school_id', $linkedId);
                                if (!empty($unlinked))
                                {
                                    $data['school_id'] = $linkedId;
                                    $data['created_by'] = $this->objUser->PKId();
                                    $data['date_created'] = date('Y-m-d H:i:s');
                                    foreach ($unlinked as $item)
                                    {
                                        $data['subject_id'] = $item;
                                        $this->objDBbridging->insertData($data);
                                    }                                    
                                }                                
                            }
                            $data = array();
                            if (!empty($classIds))
                            {
                                $idString = implode(',', $classIds);
                                $unlinked = $this->objDBbridging->getUnlinked('class_id', $idString, 'school_id', $linkedId);
                                if (!empty($unlinked))
                                {
                                    $data['school_id'] = $linkedId;
                                    $data['created_by'] = $this->objUser->PKId();
                                    $data['date_created'] = date('Y-m-d H:i:s');
                                    foreach ($unlinked as $item)
                                    {
                                        $data['class_id'] = $item;
                                        $this->objDBbridging->insertData($data);
                                    }                                    
                                }                                
                            }
                            break;
                        case 's':
                            $linkedId = $this->getParam('subject_id');
                            foreach ($linked as $item)
                            {
                                if (!empty($item['school_id']))
                                {
                                    $schoolIds[] = "'" . $item['school_id'] . "'";
                                }
                                if (!empty($item['class_id']))
                                {
                                    $classIds[] = "'" . $item['class_id'] . "'";
                                }
                            }
                            $data = array();
                            if (!empty($schoolIds))
                            {
                                $idString = implode(',', $schoolIds);
                                $unlinked = $this->objDBbridging->getUnlinked('school_id', $idString, 'subject_id', $linkedId);
                                if (!empty($unlinked))
                                {
                                    $data['subject_id'] = $linkedId;
                                    $data['created_by'] = $this->objUser->PKId();
                                    $data['date_created'] = date('Y-m-d H:i:s');
                                    foreach ($unlinked as $item)
                                    {
                                        $data['school_id'] = $item;
                                        $this->objDBbridging->insertData($data);
                                    }                                    
                                }                                
                            }
                            $data = array();
                            if (!empty($classIds))
                            {
                                $idString = implode(',', $classIds);
                                $unlinked = $this->objDBbridging->getUnlinked('class_id', $idString, 'subject_id', $linkedId);
                                if (!empty($unlinked))
                                {
                                    $data['subject_id'] = $linkedId;
                                    $data['created_by'] = $this->objUser->PKId();
                                    $data['date_created'] = date('Y-m-d H:i:s');
                                    foreach ($unlinked as $item)
                                    {
                                        $data['class_id'] = $item;
                                        $this->objDBbridging->insertData($data);
                                    }                                    
                                }                                
                            }
                            break;
                        case 'c':
                            $linkedId = $this->getParam('class_id');
                            foreach ($linked as $item)
                            {
                                if (!empty($item['school_id']))
                                {
                                    $schoolIds[] = "'" . $item['school_id'] . "'";
                                }
                                if (!empty($item['subject_id']))
                                {
                                    $subjectIds[] = "'" . $item['subject_id'] . "'";
                                }
                            }
                            $data = array();
                            if (!empty($schoolIds))
                            {
                                $idString = implode(',', $schoolIds);
                                $unlinked = $this->objDBbridging->getUnlinked('school_id', $idString, 'class_id', $linkedId);
                                if (!empty($unlinked))
                                {
                                    $data['class_id'] = $linkedId;
                                    $data['created_by'] = $this->objUser->PKId();
                                    $data['date_created'] = date('Y-m-d H:i:s');
                                    foreach ($unlinked as $item)
                                    {
                                        $data['school_id'] = $item;
                                        $this->objDBbridging->insertData($data);
                                    }                                    
                                }                                
                            }
                            $data = array();
                            if (!empty($subjectIds))
                            {
                                $idString = implode(',', $subjectIds);
                                $unlinked = $this->objDBbridging->getUnlinked('subject_id', $idString, 'class_id', $linkedId);
                                if (!empty($unlinked))
                                {
                                    $data['class_id'] = $linkedId;
                                    $data['created_by'] = $this->objUser->PKId();
                                    $data['date_created'] = date('Y-m-d H:i:s');
                                    foreach ($unlinked as $item)
                                    {
                                        $data['subject_id'] = $item;
                                        $this->objDBbridging->insertData($data);
                                    }                                    
                                }                                
                            }
                            break;
                    }
                }
                break;
            case 's':
                $linked = $this->objDBbridging->getLinked('subject_id', $id);
                if (!empty($linked))
                {      
                    $schoolIds = array();
                    $gradeIds = array();
                    $classIds = array();
                    switch ($link)
                    {
                        case 'g':
                            $linkedId = $this->getParam('grade_id');
                            foreach ($linked as $item)
                            {
                                if (!empty($item['school_id']))
                                {
                                    $schoolIds[] = "'" . $item['school_id'] . "'";
                                }
                                if (!empty($item['class_id']))
                                {
                                    $classIds[] = "'" . $item['class_id'] . "'";
                                }
                            }
                            $data = array();
                            if (!empty($schoolIds))
                            {
                                $idString = implode(',', $schoolIds);
                                $unlinked = $this->objDBbridging->getUnlinked('school_id', $idString, 'grade_id', $linkedId);
                                if (!empty($unlinked))
                                {
                                    $data['grade_id'] = $linkedId;
                                    $data['created_by'] = $this->objUser->PKId();
                                    $data['date_created'] = date('Y-m-d H:i:s');
                                    foreach ($unlinked as $item)
                                    {
                                        $data['school_id'] = $item;
                                        $this->objDBbridging->insertData($data);
                                    }                                    
                                }                                
                            }
                            $data = array();
                            if (!empty($classIds))
                            {
                                $idString = implode(',', $classIds);
                                $unlinked = $this->objDBbridging->getUnlinked('class_id', $idString, 'grade_id', $linkedId);
                                if (!empty($unlinked))
                                {
                                    $data['grade_id'] = $linkedId;
                                    $data['created_by'] = $this->objUser->PKId();
                                    $data['date_created'] = date('Y-m-d H:i:s');
                                    foreach ($unlinked as $item)
                                    {
                                        $data['class_id'] = $item;
                                        $this->objDBbridging->insertData($data);
                                    }                                    
                                }                                
                            }
                            break;
                        case 'c':
                            $linkedId = $this->getParam('class_id');
                            foreach ($linked as $item)
                            {
                                if (!empty($item['school_id']))
                                {
                                    $schoolIds[] = "'" . $item['school_id'] . "'";
                                }
                                if (!empty($item['grade_id']))
                                {
                                    $gradeIds[] = "'" . $item['grade_id'] . "'";
                                }
                            }
                            $data = array();
                            if (!empty($schoolIds))
                            {
                                $idString = implode(',', $schoolIds);
                                $unlinked = $this->objDBbridging->getUnlinked('school_id', $idString, 'class_id', $linkedId);
                                if (!empty($unlinked))
                                {
                                    $data['class_id'] = $linkedId;
                                    $data['created_by'] = $this->objUser->PKId();
                                    $data['date_created'] = date('Y-m-d H:i:s');
                                    foreach ($unlinked as $item)
                                    {
                                        $data['school_id'] = $item;
                                        $this->objDBbridging->insertData($data);
                                    }                                    
                                }                                
                            }
                            $data = array();
                            if (!empty($gradeIds))
                            {
                                $idString = implode(',', $gradeIds);
                                $unlinked = $this->objDBbridging->getUnlinked('grade_id', $idString, 'class_id', $linkedId);
                                if (!empty($unlinked))
                                {
                                    $data['class_id'] = $linkedId;
                                    $data['created_by'] = $this->objUser->PKId();
                                    $data['date_created'] = date('Y-m-d H:i:s');
                                    foreach ($unlinked as $item)
                                    {
                                        $data['grade_id'] = $item;
                                        $this->objDBbridging->insertData($data);
                                    }                                    
                                }                                
                            }
                            break;
                        case 'h':
                            $linkedId = $this->getParam('school_id');
                            foreach ($linked as $item)
                            {
                                if (!empty($item['class_id']))
                                {
                                    $classIds[] = "'" . $item['class_id'] . "'";
                                }
                                if (!empty($item['grade_id']))
                                {
                                    $gradeIds[] = "'" . $item['grade_id'] . "'";
                                }
                            }
                            $data = array();
                            if (!empty($classIds))
                            {
                                $idString = implode(',', $classIds);
                                $unlinked = $this->objDBbridging->getUnlinked('class_id', $idString, 'school_id', $linkedId);
                                if (!empty($unlinked))
                                {
                                    $data['school_id'] = $linkedId;
                                    $data['created_by'] = $this->objUser->PKId();
                                    $data['date_created'] = date('Y-m-d H:i:s');
                                    foreach ($unlinked as $item)
                                    {
                                        $data['class_id'] = $item;
                                        $this->objDBbridging->insertData($data);
                                    }                                    
                                }                                
                            }
                            $data = array();
                            if (!empty($gradeIds))
                            {
                                $idString = implode(',', $gradeIds);
                                $unlinked = $this->objDBbridging->getUnlinked('grade_id', $idString, 'school_id', $linkedId);
                                if (!empty($unlinked))
                                {
                                    $data['grade_id'] = $linkedId;
                                    $data['created_by'] = $this->objUser->PKId();
                                    $data['date_created'] = date('Y-m-d H:i:s');
                                    foreach ($unlinked as $item)
                                    {
                                        $data['school_id'] = $item;
                                        $this->objDBbridging->insertData($data);
                                    }                                    
                                }                                
                            }
                            break;
                    }
                }
                break;
            case 'c':
                $linked = $this->objDBbridging->getLinked('class_id', $id);
                if (!empty($linked))
                {      
                    $schoolIds = array();
                    $gradeIds = array();
                    $subjectIds = array();
                    switch ($link)
                    {
                        case 'g':
                            $linkedId = $this->getParam('grade_id');
                            foreach ($linked as $item)
                            {
                                if (!empty($item['school_id']))
                                {
                                    $schoolIds[] = "'" . $item['school_id'] . "'";
                                }
                                if (!empty($item['subject_id']))
                                {
                                    $subjectIds[] = "'" . $item['subject_id'] . "'";
                                }
                            }
                            $data = array();
                            if (!empty($schoolIds))
                            {
                                $idString = implode(',', $schoolIds);
                                $unlinked = $this->objDBbridging->getUnlinked('school_id', $idString, 'grade_id', $linkedId);
                                if (!empty($unlinked))
                                {
                                    $data['grade_id'] = $linkedId;
                                    $data['created_by'] = $this->objUser->PKId();
                                    $data['date_created'] = date('Y-m-d H:i:s');
                                    foreach ($unlinked as $item)
                                    {
                                        $data['school_id'] = $item;
                                        $this->objDBbridging->insertData($data);
                                    }                                    
                                }                                
                            }
                            $data = array();
                            if (!empty($subjectIds))
                            {
                                $idString = implode(',', $subjectIds);
                                $unlinked = $this->objDBbridging->getUnlinked('subject_id', $idString, 'grade_id', $linkedId);
                                if (!empty($unlinked))
                                {
                                    $data['grade_id'] = $linkedId;
                                    $data['created_by'] = $this->objUser->PKId();
                                    $data['date_created'] = date('Y-m-d H:i:s');
                                    foreach ($unlinked as $item)
                                    {
                                        $data['grade_id'] = $item;
                                        $this->objDBbridging->insertData($data);
                                    }                                    
                                }                                
                            }
                            break;
                        case 'h':
                            $linkedId = $this->getParam('school_id');
                            foreach ($linked as $item)
                            {
                                if (!empty($item['subject_id']))
                                {
                                    $subjectIds[] = "'" . $item['subject_id'] . "'";
                                }
                                if (!empty($item['grade_id']))
                                {
                                    $gradeIds[] = "'" . $item['grade_id'] . "'";
                                }
                            }
                            $data = array();
                            if (!empty($subjectIds))
                            {
                                $idString = implode(',', $subjectIds);
                                $unlinked = $this->objDBbridging->getUnlinked('subject_id', $idString, 'school_id', $linkedId);
                                if (!empty($unlinked))
                                {
                                    $data['school_id'] = $linkedId;
                                    $data['created_by'] = $this->objUser->PKId();
                                    $data['date_created'] = date('Y-m-d H:i:s');
                                    foreach ($unlinked as $item)
                                    {
                                        $data['subject_id'] = $item;
                                        $this->objDBbridging->insertData($data);
                                    }                                    
                                }                                
                            }
                            $data = array();
                            if (!empty($gradeIds))
                            {
                                $idString = implode(',', $gradeIds);
                                $unlinked = $this->objDBbridging->getUnlinked('grade_id', $idString, 'school_id', $linkedId);
                                if (!empty($unlinked))
                                {
                                    $data['school_id'] = $linkedId;
                                    $data['created_by'] = $this->objUser->PKId();
                                    $data['date_created'] = date('Y-m-d H:i:s');
                                    foreach ($unlinked as $item)
                                    {
                                        $data['grade_id'] = $item;
                                        $this->objDBbridging->insertData($data);
                                    }                                    
                                }                                
                            }
                            break;
                        case 's':
                            $linkedId = $this->getParam('subject_id');
                            foreach ($linked as $item)
                            {
                                if (!empty($item['school_id']))
                                {
                                    $schoolIds[] = "'" . $item['school_id'] . "'";
                                }
                                if (!empty($item['grade_id']))
                                {
                                    $gradeIds[] = "'" . $item['grade_id'] . "'";
                                }
                            }
                            $data = array();
                            if (!empty($schoolIds))
                            {
                                $idString = implode(',', $schoolIds);
                                $unlinked = $this->objDBbridging->getUnlinked('school_id', $idString, 'subject_id', $linkedId);
                                if (!empty($unlinked))
                                {
                                    $data['subject_id'] = $linkedId;
                                    $data['created_by'] = $this->objUser->PKId();
                                    $data['date_created'] = date('Y-m-d H:i:s');
                                    foreach ($unlinked as $item)
                                    {
                                        $data['school_id'] = $item;
                                        $this->objDBbridging->insertData($data);
                                    }                                    
                                }                                
                            }
                            $data = array();
                            if (!empty($gradeIds))
                            {
                                $idString = implode(',', $gradeIds);
                                $unlinked = $this->objDBbridging->getUnlinked('grade_id', $idString, 'subject_id', $linkedId);
                                if (!empty($unlinked))
                                {
                                    $data['grade_id'] = $linkedId;
                                    $data['created_by'] = $this->objUser->PKId();
                                    $data['date_created'] = date('Y-m-d H:i:s');
                                    foreach ($unlinked as $item)
                                    {
                                        $data['subject_id'] = $item;
                                        $this->objDBbridging->insertData($data);
                                    }                                    
                                }                                
                            }
                            break;
                    }
                }
                break;
        }        
        
        return $this->nextAction('link', array('type' => $type, 'id' => $id, 'tab' => $tab));        
    }
    
    /**
     *
     * Method that corresponds to the ajaxShowSubject action.
     * It returns the html for the ajax request
     * 
     * @access private 
     */
    private function __ajaxShowSubject()
    {
        return $this->objOps->ajaxShowSubject();
    }
    
    /**
     *
     * Method that corresponds to the ajaxShowContext action.
     * It returns the html for the ajax request
     * 
     * @access private 
     */
    private function __ajaxShowContext()
    {
        return $this->objOps->ajaxShowContext();
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
                    case 'h':
                        $tab = 0;
                        break;
                    case 'g':
                        $tab = 1;
                        break;
                    case 'c':
                        $tab = 2;
                        break;
                    case 'x':
                        $tab = 3;
                        break;
                }
                break;
            case 'g':
                switch ($link)
                {
                    case 'h':
                        $tab = 0;
                        break;
                    case 's':
                        $tab = 1;
                        break;
                    case 'c':
                        $tab = 2;
                        break;
                }
                break;
            case 'c':
                switch ($link)
                {
                    case 'h':
                        $tab = 0;
                        break;
                    case 's':
                        $tab = 1;
                        break;
                    case 'g':
                        $tab = 2;
                        break;
                }
                break;
        }
        $this->objDBbridging->deleteLink($del);

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
        if ($this->objUser->isLoggedIn())
        {
            $groupId = $this->objGroups->getId("School Managers");
            $userId = $this->objUser->userId();
            if ($this->objUser->isAdmin() || 
                $this->objGroupOps->isGroupMember($groupId, $userId ))
            {
                return TRUE;
            }
            else
            {
                return FALSE;
            }
        }

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
            default:
                return TRUE;
                break;
        }
     }
}
?>
