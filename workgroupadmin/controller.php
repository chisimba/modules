<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
	die("You cannot view this page directly");
}
// end security check

/**
* Workgroup Admin module
* @author Jeremy O'Connor
* @copyright 2004 University of the Western Cape
* $Id$
*/
class workgroupadmin extends controller
{
    var $objUser;
	var $objLanguage;
	var $objDbWorkgroup;
	var $objDbWorkgroupUsers;

    /**
    * The Init function
    */
    function init()
    {
        $this->objUser =& $this->getObject('user', 'security');
        $this->objLanguage =& $this->getObject('language','language');
		$this->objDbWorkgroup =& $this->getObject('dbworkgroup','workgroup'); 
		$this->objDbWorkgroupUsers =& $this->getObject('dbworkgroupusers','workgroup'); 
        //$this->objHelp=& $this->getObject('helplink','help');
        //$this->objHelp->rootModule="helloworld";
        //Get the activity logger class
        $this->objLog=$this->newObject('logactivity', 'logger');
        //Set it to log once per session
        //$this->objLog->logOncePerSession = TRUE;
        //Log this module call
        $this->objLog->log();
    }
    
    /**
    * The dispatch funtion
    * @param string $action The action
    * @return string The content template file
    */
    function dispatch($action=Null)
    {
        $this->objConfig = &$this->getObject('altconfig','config');
        $systemType = $this->objConfig->getValue("SYSTEM_TYPE", "contextabstract");
        $isAlumni = true; //($systemType == "alumni");
        $this->setVar('isAlumni',$isAlumni);
        // Set the layout template.
        $this->setLayoutTemplate("layout_tpl.php");
        // 1. ignore action at moment as we only do one thing - say hello
        // 2. load the data object (calls the magical getObject which finds the
        //    appropriate file, includes it, and either instantiates the object,
        //    or returns the existing instance if there is one. In this case we
        //    are not actually getting a data object, just a helper to the 
        //    controller.
        // 3. Pass variables to the template
        $this->setVarByRef('objUser', $this->objUser);
		$this->setVarByRef('objLanguage', $this->objLanguage);
        //$this->setVarByRef('objHelp', $this->objHelp);
        // return the name of the template to use  because it is a page content template
        // the file must live in the templates/content subdir of the module directory
		// Get context code.
		//$objDbContext = &$this->getObject('dbcontext','context');
		$contextCode = '0'; //$objDbContext->getContextCode();
		$this->setVarByRef('contextCode', $contextCode);
        // Check if we are not in a context...
		if ($contextCode == null) {
            if ($isAlumni) {
    			$contextTitle = "Lobby";
    			$this->setVarByRef('contextTitle', $contextTitle);
            }
            else {
                return "error_tpl.php";
            }
		}
		else {
            // ... else 
			$contextTitle = 'context0'; //$objDbContext->getTitle();
			$this->setVarByRef('contextTitle', $contextTitle);
		}
        
		switch($action){
            case 'create':
                return "create_tpl.php";
			case "createconfirm": 
                // Create a new workgroup.
				$id = $this->objDbWorkgroup->insertSingle(
					$contextCode, 
					$_POST['newworkgroup']
				);
                //$id = $this->objDbWorkgroup->getLastInsertId();
                return $this->nextAction('manage', array('workgroupId'=>$id));
				break;
            case 'rename':
				$id = $this->getParam('workgroupId',NULL);
				$this->setVarByRef("id", $id);
				$list = $this->objDbWorkgroup->listSingle($id);
				$workgroup = $list[0]['description'];
				$this->setVarByRef("workgroup", $workgroup);
                return "rename_tpl.php";
			case "renameconfirm": 
				$id = $this->getParam('workgroupId',NULL);
				$this->objDbWorkgroup->updateSingle(
					$id, 
					$_POST['newworkgroup']
				);
				return $this->nextAction('confirm', null);
			case "delete":
                // Delete a workgroup.
				$id = $this->getParam('workgroupId',NULL);
				$this->objDbWorkgroup->deleteSingle($id);
				$this->objDbWorkgroupUsers->deleteAll($id);
				break;
			case "manage":
                // Edit the workgroup.
				$id = $this->getParam('workgroupId',NULL);
				$workgroups = $this->objDbWorkgroup->listSingle($id);
				$workgroup = $workgroups[0];
				$this->setVarByRef("workgroup", $workgroup);
				$members = $this->objDbWorkgroupUsers->listAll($workgroup['id']);
				$this->setVarByRef("members", $members);
                if ($isAlumni) {
                    $objUsers = $this->getObject('dbusers','workgroup');
                    $users = $objUsers->listAll();
    				$this->setVarByRef("users", $users);
                } else {
                    // Get the members of all workgroups in the context.
    				//$membersOfAll = $this->objDbWorkgroupUsers->getAllInContext($contextCode);
    				$members = $this->objDbWorkgroupUsers->listAll($workgroup['id']);
    				
                    // Get the groupAdminModel object.
    				$groups =& $this->getObject("groupAdminModel", "groupadmin");
                    
                    // Get list of lecturers
    				$gid=$groups->getLeafId(array($contextCode,'Lecturers'));
    				$lecturers = $groups->getGroupUsers($gid, array('userId',"CONCAT(firstname, ' ', surName) AS fullName"), "ORDER BY fullName");

                    // Get list of students
    				$gid=$groups->getLeafId(array($contextCode,'Students'));
    				$students = $groups->getGroupUsers($gid, array('userId',"CONCAT(firstname, ' ', surName) AS fullName"), "ORDER BY fullName");

    				$_users = array_merge($lecturers, $students);

                    $users = array();
                    foreach ($_users as $_user) {
                        $inWorkgroup = false;
                        foreach ($members as $member) {
                            if ($_user['userId'] == $member['userId']) {
                                $inWorkgroup = true;
                                break;
                            }
                        }
                        if (!$inWorkgroup) {                          
                            $users[] = $_user;
                        }
                    }

                    $this->setVarByRef("users", $users);    
                }
				$this->setVar('pageSuppressXML',true);
				return "managenew_tpl.php";
            case "processform":
                if( $this->getParam( 'button' ) == 'save' ) {
                    //.. do the save action ..
    				$workgroupId = $this->getParam('workgroupId',NULL);
                    $rightData = $this->getParam( 'rightList' );
                    // First delete all users in the workgroup.
        		    $this->objDbWorkgroupUsers->deleteAll($workgroupId);
                    if (!empty($rightData)) {
                        foreach ($rightData as $userId) {
                		    $this->objDbWorkgroupUsers->insertSingle($workgroupId,$userId);
                        }
                    }
					$this->setVar('confirm',$this->objLanguage->languageText('mod_workgroupadmin_changessaved'));
                    return $this->nextaction('confirm',NULL);
                } else if ( $this->getParam( 'button' ) == 'cancel' ) {
                    //.. do the cancel action ..
                    return $this->nextaction(NULL,NULL);
                }
            // Add selected users to workgroup.
			case 'confirm':
				$this->setVar('confirm',$this->objLanguage->languageText('mod_workgroupadmin_changessaved'));
				break;
			default:
				;
		} // switch
        // List all the workgroups.
		$workgroups = $this->objDbWorkgroup->listAll($contextCode);
		$this->setVarByRef('workgroups', $workgroups);
        return "main_tpl.php";
    }
}    
?>
