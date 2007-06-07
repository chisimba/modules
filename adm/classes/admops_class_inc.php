<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

/**
 * Class to handle adm elements
 *
 * @author Paul Scott
 * @copyright GNU/GPL, AVOIR
 * @package blog
 * @access public
 */
class admops extends object
{
	public $objConfig;

	public $admmail = TRUE;
	
    /**
     * Standard init function called by the constructor call of Object
     *
     * @param void
     * @return void
     * @access public
     */
    public function init()
    {
        try {
            $this->objConfig = $this->getObject('altconfig', 'config');
            $this->objLanguage = $this->getObject('language', 'language');
            $this->sysConfig = $this->getObject('dbsysconfig', 'sysconfig');
            $this->objUser = $this->getObject('user', 'security');
        }
        catch(customException $e) {
            echo customException::cleanUp();
            die();
        }
        if(!extension_loaded("imap"))
		{
			throw new customException($this->objLanguage->languageText("mod_adm_imaprequired", "adm"));
			$this->admmail = FALSE;
		}
		else {
			$this->admmail = TRUE;
		}
    }

    public function parsemail()
    {
    	//grab the DSN from the config file
    	$this->objConfig = $this->getObject('altconfig', 'config');
    	$this->objImap = $this->getObject('imap', 'mail');
    	$admdsn = $this->sysConfig->getValue('ADM_MAIL_DSN', 'adm');
    	if($admdsn == "not set")
    	{
    		return FALSE;
    	}
    	try {
    		//grab a list of all valid users to an array for verification later
    		//$valid = $this->objDbAdm->checkValidUser();
    		$valid = array();
    		$valadds = array();
    		//cycle through the valid email addresses and check that the mail is from a real user
    		foreach($valid as $addys)
    		{
    			$valadds[] = array('address' => $addys['emailaddress'], 'userid' => $addys['userid']);
    		}
    		//connect to the IMAP/POP3 server
    		$this->conn = $this->objImap->factory($admdsn);
    		//grab the mail headers
    		$this->objImap->getHeaders();
    		//var_dump($this->objImap->getHeaders());
    		//check mail
    		$this->thebox = $this->objImap->checkMbox();
    		//get the mail folders
    		$this->folders = $this->objImap->populateFolders($this->thebox);
    		//count the messages
    		$this->msgCount = $this->objImap->numMails();
    		//echo $this->msgCount;
    		//get the meassge headers
    		$i = 1;
    		//parse the messages
    		while ($i <= $this->msgCount)
    		{
    			//get the header info
    			$headerinfo = $this->objImap->getHeaderInfo($i);
    			//from
    			$address = $headerinfo->fromaddress;
    			//subject
    			$subject = $headerinfo->subject;
    			//date
    			$date = $headerinfo->Date;
    			//message flag
    			$read = $headerinfo->Unseen;
    			//message body
    			$bod = $this->objImap->getMessage($i);

    			//check if there is an attachment
    			if(empty($bod[1]))
    			{
    				// nope no attachments
    				$attachments = NULL;
    				// we can return because the sql data is sent as an attachment (sqldata.log)
    				return FALSE;
    			}
    			else {
    				//set the attachment
    				$attachments = $bod[1];
    				//loop through the attachments and write them down
    			}
    			//make sure the body doesn't have any nasty chars
    			$message = @htmlentities($bod[0]);
    			//check for a valid user
    			if(!empty($address))
    			{
    				//check the address against tbl_users to see if its valid.
    				//just get the email addy, we dont need the name as it can be faked
    				$fadd = $address;
    				//get rid of the RFC formatted email bits
    				$parts = explode("<", $fadd);
    				$parts = explode(">", $parts[1]);
    				//raw address string that we can use to check against
    				$addy = $parts[0];
    				//check if the address we get from the msg is in the array of valid addresses
    				foreach ($valadds as $user)
    				{
    					//check if there is a match to the user list
    					if($user['address'] != $addy)
    					{
    						//Nope, no match, not validated!
    						$validated = NULL;
    					}
    					else {
    						//echo "Valid user!";
    						//match found, you are a valid user dude!
    						$validated = TRUE;
    						//set the userid
    						//$userid = $user['userid'];
    						//all is cool, so lets break out of this loop and carry on
    						break;

    					}
    				}
    			}
    			$validated = TRUE;
    			if($validated == TRUE)
    			{
    				//insert the mail data into an array for manipulation
    				$data[] = array('address' => $address, 'subject' => $subject, 'date' => $date, 'messageid' => $i, 'read' => $read,
    				'body' => $message, 'attachments' => $attachments);
    			}

    			//delete the message as we don't need it anymore
    			//echo "sorting " . $this->msgCount . "messages";
    			//$this->objImap->delMsg($i);
    			$i++;
    		}
    		//print_r($data); die();
    		//is the data var set?
    		if(!isset($data))
    		{
    			$data = array();
    		}
    		//lets look at the data now
    		foreach ($data as $datum)
    		{
    			$newbod = $datum['body'];
    			if(!empty($datum['attachments']))
    			{
    				if(is_array($datum['attachments']))
    				{
    					foreach($datum['attachments'] as $files)
    					{
    						//do check for multiple attachments
    						//set the filename of the attachment
    						$fname = $files['filename'];
    						$filenamearr = explode(".", $fname);
    						$ext = pathinfo($fname);
    						$filename = $filenamearr[0] . "_" . time() . "." . $ext['extension'];
    						//decode the attachment data
    						$filedata = base64_decode($files['filedata']);
    						//set the path to write down the file to
    						$path = $this->objConfig->getContentBasePath() . 'adm/';
    						$fullpath = $this->objConfig->getsiteRootPath()."/usrfiles/adm/";
    						if(!file_exists($path))
    						{
    							//dir doesn't exist so create it quickly
    							mkdir($path, 0777);
    						}
    						//fix up the filename a little
    						$filename = str_replace(" ","_", $filename);
    						$filename = str_replace("%20","_", $filename);
    						//change directory to the data dir
    						chdir($path);
    						//write the file
    						$handle = fopen($filename, 'wb');
    						fwrite($handle, $filedata);
    						fclose($handle);
    						$type = mime_content_type($filename);
    						$tparts = explode("/", $type);
    						//print_r($tparts); die();
    						if($tparts[0] == "text" && $tparts[1] == "plain")
    						{
    							return $fullpath.$filename;
    							
    						}
    						else {
    							return FALSE;
    						}
    					}
    				}
    				else {
    					
    			}
    			
    		}

    	}
    	}
    	//any issues?
    	catch(customException $e) {
    		//clean up and die!
    		customException::cleanUp();
    	}
    }
    
    public function sendLog()
    {
    	$path = $this->objConfig->getsiteRootPath()."/error_log/sqllog.log";
    	if(file_exists($path) && filesize($path) > 0)
    	{
    		//echo filesize($path);
    		// bomb a mail off to the mirrors with the sql attached.
    		$objMailer = $this->getObject('email', 'mail');
    		$objMailer->setValue('to', array('pscott@uwc.ac.za', 'fsiu@uwc.ac.za'));
    		$objMailer->setValue('from', 'noreply@chisimba.mirr.or');
    		$objMailer->setValue('fromName', $this->objLanguage->languageText("mod_adm_emailfromname", "adm"));
    		$objMailer->setValue('subject', $this->objLanguage->languageText("mod_adm_emailsub", "adm"));
    		$objMailer->setValue('body', date('r'));
    		$objMailer->attach($path, $this->objLanguage->languageText("mod_adm_sqldata", "adm"));
    		if ($objMailer->send()) {
    			log_debug($this->objLanguage->languageText("mod_adm_sqldatasent", "adm"));
    			unlink($path);
    			touch($path);
    		} else {
    			log_debug($this->objLanguage->languageText("mod_adm_sqldatanotsent", "adm"));
    		}
    	}
    	else {
    		return $this->objLanguage->languageText("mod_adm_filezero", "adm");
    	}
    }
    
}
?>