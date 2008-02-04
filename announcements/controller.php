<?php
/**
 * Short description for file
 * 
 * Long description (if any) ...
 * 
 * PHP version unknow
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
 
// security check - must be included in all scripts
/**
 * Description for $GLOBALS
 * @global unknown $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 */
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}
// end security check

/**
 *
 * Model controller for the table tbl_phonebook
 * @authors:Godwin Du Plessis, Ewan Burns, Helio Rangeiro, Jacques Cilliers, Luyanda Mgwexa and Qoane Seitlheko.
 * @copyright 2007 University of the Western Cape
 */
class announcements extends controller
{
    /**
     * Description for public
     * @var    unknown
     * @access public 
     */
    public $objLanguage;

    /**
     * Description for public
     * @var    unknown
     * @access public 
     */
    public $objConfig;

    /**
     * Description for public
     * @var    object
     * @access public
     */
    public $objDBAnnouncements;

    /**
     * Description for public
     * @var    object
     * @access public
     */
    public $objUser;
 /**
     * Description for public
     * @var    object
     * @access public
     */
    public $objContextUsers;

/**
     * Description for public
     * @var    object
     * @access public
     */
    public $objSendMail;


/**
     * Description for public
     * @var    object
     * @access public
     */
    public $objDate;

    /**
     * Constructor method to instantiate objects and get variables
     */
    public function init() 
    {
        try {
            $this->objUser = $this->getObject('user', 'security');
	    $this->objContext = $this->getObject('dbcontext','context');
 	    $this->objContextUsers = $this->getObject('managegroups','contextgroups');
 	    $this->objSendMail = $this->getObject('email','mail');
            $this->objDbAnnouncements = $this->getObject('dbAnnouncements', 'announcements');
	    $this->objDate = $this->newObject('dateandtime', 'utilities');
            $this->objLanguage = $this->getObject('language', 'language');
            $this->objConfig = $this->getObject('altconfig', 'config');

	  
        }
        catch(customException $e) {
            echo customException::cleanUp();
            die();
        }
    } //end of init function
    
    /**
     * Method to process actions to be taken
     *
     * @param string $action String indicating action to be taken
     */
    public function dispatch($action = Null) 
    {
        
	//get context so that only messages for this context are displayed
		$isInContext=$this->objContext->isInContext();
		 if($isInContext)
  		 {
   		 $this->contextCode=$this->objContext->getContextCode();
   		 $contextPuid=$this->objContext->getField('puid',$this->contextCode);
   		 }

		$contextusers=$this->objContextUsers->contextUsers('Students', $this->contextCode);
		
		
		
	switch ($action) {
            default:
            case 'default':
		//set start to 0
		//@param integer $start to indicate start of Sql Limit
		$start=0;
                $records = $this->objDbAnnouncements->listAll($contextPuid,$start);
                $this->setVarByRef('records', $records);
                return 'view_tpl.php';
                break;
            // Case to add an entry
	    case 'archive':
	    //set start to 5
	    //@param integer $start to indicate start of Sql Limit
	    $start=5;
	    $records = $this->objDbAnnouncements->listAll($contextPuid,$start);
            $this->setVarByRef('records', $records);	
	    return 'archive_tpl.php';	
	    break;
            case 'add';
            $userId = $this->objUser->userId();
            $title = htmlentities($this->getParam('title') , ENT_QUOTES);
            $message = htmlentities($this->getParam('message') , ENT_QUOTES);
            $createdon = htmlentities($this->getParam('createdon') , ENT_QUOTES);
            $createdby = htmlentities($this->getParam('createdby') , ENT_QUOTES);
            $courseid = $contextPuid;
       	    //$this->objDbAnnouncements->insertRecord($title, $message, $createdon, $createdby, $courseid);
	    //prepare $RecipientList to send mails
	    $subject=$title;

	    //count the number of context users
	    $count=count($contextusers);
	    //array to contain recipients
	    $RecipientList=array();
	   
	    //loop thro array context users to get each users id
	    for($i=0;$i<$count;$i++)
	    {
		$contextUserId=$contextusers[$i]['userid'];
    
                //get student email address, and add them to array
                $contextUserEmail=$this->objUser->email($contextUserId);
                array_push($RecipientList,$contextUserEmail);  
               
	    }
            //set recipient list
                $this->objSendMail->setValue('to', $RecipientList);
             //get sender's email address
                $SenderEmail=$this->objUser->email($userId);
                 $this->objSendMail->setValue('from', $SenderEmail);
                //get sender's fullnames
                 $fromFullname= $this->objUser->fullname($userId);
                //set sender'sfullname 
               $this->objSendMail->setValue('fromName', $fromFullname);
                //set email subject
                $this->objSendMail->setValue('subject', $subject);
                //set email body
                $this->objSendMail->setValue('body', $message);
                //emailAltBody
                $this->objSendMail->setValue('altBody', '');
                //email mailer
                $this->objSendMail->setValue('mailer', 'mail');
                //now send emails
               //$this->objSendMail->send();
	    exit;
	    $this->nextAction('');
            break;
        // Link to the template
        case 'link':
            return 'add_tpl.php';
            break;
        // Case to get the information from the form
        case 'edit':
            $id = html_entity_decode($this->getParam('id'));
            $oldrec = $this->objDbAnnouncements->listSingle($id);
            $this->setVarByRef('oldrec', $oldrec);
            return 'edit_tpl.php';
        // Case to edit/update an entry
        case 'update':
            $id = $this->getParam('id');
            $title = htmlentities($this->getParam('title'));
            $message = htmlentities($this->getParam('message'));
            $this->objUser = $this->getObject('user', 'security');
            $arrayOfRecords = array(
                'title' => $title,
                'message' => $message

            );
            $this->objDbAnnouncements->updateRec($id, $arrayOfRecords);
            return $this->nextAction('view_tpl.php');
            break;
        // Case to delete an entry
        case 'delete':
            $this->objDbAnnouncements->deleteRec($this->getParam('id'));
            return $this->nextAction('view_tpl.php');
            break;
    } //end of switch
  }  
} //end of dispatch function
?>
