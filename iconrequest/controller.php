<?php
/* ------------iconrequest class extends controller ----------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
        die("You cannot view this page directly");
}
// end security check

/**
* Send request for new icon
* @author Nic Appleby
* $Id: controller.php,v 1.0 2005/12/13
*/

class iconrequest extends controller
{
   public $objH;			//Main page heading
   public $objLanguage;			//Language object for language independant text
   public $dbReq;			//Database containing request data
   public $dbDev;			//Database containing developer data
   public $dbFile;			//Database containing example files
   public $config;			//Config object for content base path
   public $mailMsg;			//kngemail object to email request to developer
   public $objSys;

   /**
   * Function to initialise objects
   */
   function init()
   {
	$this->objLanguage = &$this->getObject("language", "language");
	$this->objUser =& $this->getObject('user', 'security');
	//Get the activity logger class
    $this->objLog = &$this->newObject('logactivity', 'logger');
    //Log this module call
    $this->objLog->log();
	//initialise tables
    $this->dbReq = &$this->getObject('requestTable');
	$this->dbDev = &$this->getObject('developerTable');
	$this->dbFile = &$this->getObject('filestable');
	//get config object for content basse path
	$this->config = &$this->getObject('config','config');
	//setup the kngemail object
	$this->mailMsg = &$this->getObject('kngemail','utilities');
    $this->objSys = &$this->getObject('dbsysconfig','sysconfig');
   }

   /**
   * Main function
   */
   function dispatch()
   {
	$this->action = $this->getParam("action", null);
        switch ($this->action) {

            	// Welcome screen displaying current requests
            	case null:
                    return "request_tpl.php";

                case "test":
                    return "test2_tpl.php";

            	// Edit an icon request
            	case "edit":
            		return "icon_form_tpl.php";

		        // Template to edit the icon developer details
		        case "developer":
            		return "dev_edit_tpl.php";

            	// Template to get all the icon request data from the user
            	case "request":
			        return "icon_form_tpl.php";

		//content of iframe for dynamically uploading icon
		case "tempframe":
			//suppress layout templates for iframe
			$this->setLayoutTemplate(NULL);
			$this->setVar('pageSuppressIM', TRUE);
        		$this->setVar('pageSuppressToolbar', TRUE);
        		$this->setVar('pageSuppressBanner', TRUE);
        		$this->setVar('pageSuppressContainer', TRUE);
        		$this->setVar('suppressFooter', TRUE);
			return "upload_icon_tpl.php";

		// Submit a new request to the DB
		case "submit":
	   		//create new request to insert into db
	   		$this->loadClass('request');
	   		$icon = &new request($this->getParam('reqId'),
				$this->getParam('module_name'),$this->getParam('priority'),
	   		$this->getParam('icon_type'),$this->getParam('rdbtphptype'),
				$this->getParam('icon_name'),$this->getParam('icon_description'),
				$this->objUser->userId(),$this->getParam('idea_uri1'),
				$this->getParam('idea_uri2'));

			switch ($icon->priority) {
				case 'y' : $iconPr = $this->objLanguage->languageText('word_yesterday');
					break;
				case 'h' : $iconPr = $this->objLanguage->languageText('word_high');
					break;
				case 'n' : $iconPr = $this->objLanguage->languageText('word_normal');
					break;

			switch ($icon-phpvers){
			}		
				case '4' : $iconPt = $this->objLanguage->languageText('word_php4');
					break;
				case '5' : $iconPt = $this->objLanguage->languageText('word_php5');
					break;
				case '1' : $iconPt = $this->objLanguage->languageText('word_phpunkn');
					break;
				default  : $iconPt = $this->objLanguage->languageText('word_phpunkn');
					break;
		
			}
			
			$iconTy = ($icon->type == 'm') ? $this->objLanguage->languageText('word_module') : $this->objLanguage->languageText('word_common');
			//try insert the record and return appropriate message
	   		if ($this->dbReq->insertRec($icon) == false) {
	   			return $this->nextAction(null,array('message'=>'fail'));
	   		} else {	//success, generate email and send
            $host = $this->objSys->getValue('KEWL_SERVERNAME');
	   		$this->mailMsg->setup($this->objUser->email(), $this->objLanguage->languageText('mod_name') .' '. $this->objUser->fullName(),$host);
            $email = $this->objUser->email($this->dbDev->getId());
				$subject = $this->objLanguage->languageText('mod_email_subject');
				$name = $this->objUser->fullname($this->dbDev->getId());
				$body = $this->objLanguage->languageText('mod_email_body');
				$body .= $this->objLanguage->languageText('form_label1').' '.$icon->modName.'<br>';
				$body .= $this->objLanguage->languageText('form_label2').' '.$iconPr.'<br>';
				$body .= $this->objLanguage->languageText('form_label3').' '.$iconTy.'<br>';
				/*
				*   Auhtor of changes Dean Van Niekerk
				*   Email address : dvanniekerk@uwc.ac.za
				*/
				$body .= $this->objLanguage->languageText('form_label11').' '.$icon->Phpversion.'<br>';
				$body .= $this->objLanguage->languageText('form_label4').' '.$icon->iconName.'<br>';
				$body .= $this->objLanguage->languageText('form_label5').' '.$icon->description.'<br>';
				$body .= $this->objLanguage->languageText('form_label8').' '.$icon->uri1.'<br>';
				$body .= $this->objLanguage->languageText('form_label9').' '.$icon->uri2.'<br>';
				$body .= $this->objLanguage->languageText('form_label10').' <a href="mailto:'.$this->objUser->email().'">'.$this->objUser->fullName($icon->uploaded).'</a><br>';
				$pic = $this->dbFile->getRow('reqId',$icon->reqId);
				if ($pic !=Null) {	//attachment exists
					$path = $this->config->contentBasePath().'assets/'.$pic['filename'];
					$attach = file_get_contents($path);
				} else {
					$attach = Null;
				}
				$this->mailMsg->sendMail($name,$subject,$email,$body,true,$attach,$pic['filename']);
	   			return $this->nextAction(null,array('message'=>'confirm'));
	   		}

	   	// Update the percentage complete of the request
	   	case "update":
			$pt = $this->getParam('percentage');
			$icon = array('modName' =>$this->getParam('module_name'),'priority' =>$this->getParam('priority'),
					'type' =>$this->getParam('icon_type'),'phptype'=>$this->getParam('rdbtphptype'), 'iconName'=>$this->getParam('icon_name'),
					'description'=>$this->getParam('icon_description'),'uri1'=>$this->getParam('idea_uri1'),
					'uri2'=>$this->getParam('idea_uri2'),'complete'=>$pt);
	   		$pk = $this->getParam('pk');
			$this->dbReq->update('id',$pk,$icon);
	   		if ($pt==100) {
                if (!$this->dbDev->isEmpty()) {
                    $id = $this->dbDev->getId();
                    $email = $this->objUser->email($id);
                    $developer = $this->objUser->fullname($id);
                	  $host = $this->objSys->getValue('KEWL_SERVERNAME');
                	  $this->mailMsg->setup($email,$developer,$host);
                	  $d = $this->dbReq->getRow('id',$pk);
					$email = $this->objUser->email($d['uploaded']);
					$subject = $this->objLanguage->languageText('icondone_email_subject');
					$name = $this->objUser->fullName($d['uploaded']);
					$body = $this->objLanguage->languageText('phrase_icon');
					$body .= $d['iconName'].$this->objLanguage->languageText('phrase_icon2');
					$body .= $email.'">'.$developer;
					$body .= $this->objLanguage->languageText('phrase_icon3');
					$this->mailMsg->sendMail($name,$subject,$email,$body,true,null,null);
					return $this->nextAction('delete',array('reqId'=>$d['reqId'],'Id'=>$pk));
                }
			} else {
				$host = $this->objSys->getValue('KEWL_SERVERNAME');
	   			$this->mailMsg->setup($this->objUser->email(), $this->objLanguage->languageText('mod_name') .' '. $this->objUser->fullName(),$host);
                $email = $this->objUser->email($this->dbDev->getId());
				$subject = $this->objLanguage->languageText('mod_email_subject');
				$name = $this->objUser->fullname($this->dbDev->getId());
				$body = $this->objLanguage->languageText('mod_email_body');
				$body .= $this->objLanguage->languageText('form_label1').' '.$icon->modName.'<br>';
				$body .= $this->objLanguage->languageText('form_label2').' '.$icon->priority.'<br>';
				$body .= $this->objLanguage->languageText('form_label3').' '.$icon->type.'<br>';
				/*
				*   Auhtor of changes Dean Van Niekerk
				*   Email address : dvanniekerk@uwc.ac.za
				*/
				$body .= $this->objLanguage->languageText('form_label11').' '.$icon->Phpversion.'<br>';				
				
				$body .= $this->objLanguage->languageText('form_label4').' '.$icon->iconName.'<br>';
				$body .= $this->objLanguage->languageText('form_label5').' '.$icon->description.'<br>';
				$body .= $this->objLanguage->languageText('form_label8').' '.$icon->uri1.'<br>';
				$body .= $this->objLanguage->languageText('form_label9').' '.$icon->uri2.'<br>';
				$body .= $this->objLanguage->languageText('form_label10').' <a href="mailto:'.$this->objUser->email().'">'.$this->objUser->fullName($icon->uploaded).'</a><br>';
				$pic = $this->dbFile->getRow('reqId',$icon->reqId);
				if ($pic !=Null) {	//attachment exists
					$path = $this->config->contentBasePath().'assets/'.$pic['filename'];
					$attach = file_get_contents($path);
				} else {
					$attach = Null;
				}
				$this->mailMsg->sendMail($name,$subject,$email,$body,true,$attach,$pic['filename']);
	   			return $this->nextAction(null);
			}
			break;

	   	// Change the developer information in the DB
	   	case "changedev":
	   		if (($this->objUser->userId() == $this->dbDev->getId()) || ($this->objUser->isAdmin())) {
				$this->dbDev->updateRec($this->getParam('dev_id'));
	   		}
	   		return $this->nextAction(null);

	   	// Delete a request from the DB
		case "delete":
			$reqId = $this->getParam('reqId');
			$example = $this->dbFile->getRow('reqId',$reqId);
			if ($example != Null) {	//if there is an assosciated entry in filestable delete this too
				$this->dbFile->deleteFile($reqId);
				$fName = $this->config->contentBasePath().'assets/'.$example['filename'];
				unlink($fName);	//delete from the server directory
			}
			$this->dbReq->deleteRec($this->getParam("Id", null));
			return $this->nextAction(null);

		// Delete the icon developer information from the DB (debugging purposes only)
		case "deldev":
			$this->dbDev->deleteRec();
			return $this->nextAction(null);

		default:
			die("Action unknown");
                	break;
		} //switch
   }
}
?>
