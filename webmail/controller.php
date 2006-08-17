<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
	die("You cannot view this page directly");
}
// end security check
class webmail extends controller
{
	public $objImap;
	public $objLog;
	public $objLanguage;


	/**
     * Constructor method to instantiate objects and get variables
     */
	public function init()
	{
		try {
			$this->objLanguage = $this->getObject('language', 'language');
			$this->objImap = $this->getObject('imap');
			//Get the activity logger class
			$this->objLog = $this->newObject('logactivity', 'logger');
			//Log this module call
			$this->objLog->log();
		}
		catch(customException $e) {
			echo customException::cleanUp();
			die();
		}
	}
	/**
     * Method to process actions to be taken
     *
     * @param string $action String indicating action to be taken
     */
	public function dispatch($action = Null)
	{
		switch ($action) {
			default:
				//Mailbox DSN
				$dsn = "imap://fsiu:fsiu@itsnw.uwc.ac.za:143/Inbox";
				try {
					//connect to the server
					$this->objImap->factory($dsn);
					$this->objImap->getHeaders();
					//check mail
					$thebox = $this->objImap->checkMbox();
					//var_dump($thebox);
					$folders = $this->objImap->populateFolders($thebox);
					$msgCount = $this->objImap->numMails();
					$this->setVarByRef('folders', $folders);

					//get the meassge headers
					$i = 1;
					while ($i <= $msgCount)
					{
						//echo $i;
						$headerinfo = @$this->objImap->getHeaderInfo($i);
						//from
						$address[] .= @$headerinfo->fromaddress;
						//subject
						$subject[] .= @$headerinfo->subject;
						//date
						$date[] .= @$headerinfo->Date;

						$i++;
					}

					$infoArr = array($address, $subject, $date);

					$this->setVarByRef('infoArr', $infoArr);

					return "inbox_tpl.php";

				}
				catch(customException $e) {
					customException::cleanUp();
				}
			case 'getcalendar':
				//Calendar DSN
				$caldsn = "imap://fsiu:fsiu@itsnw.uwc.ac.za:143/Calendar";
				try {
					//connect to the server
					$this->objImap->factory($caldsn);
					//check mail
					$calbox = $this->objImap->checkMbox();
					//var_dump($calbox);
					$calfolders = $this->objImap->populateFolders($calbox);
					print_r($calfolders);
					$this->setVarByRef('calfolders', $calfolders);
				}
				catch(customException $e) {
					customException::cleanUp();
				}
				break;

			case 'msglist':
				//$this->setLayoutTemplate('null_tpl.php');
				$this->setVar('pageSuppressIM', TRUE);
            	$this->setVar('pageSuppressBanner', TRUE);
            	$this->setVar('pageSuppressToolbar', TRUE);
            	$this->setVar('pageSuppressContainer', TRUE);
            	$this->setVar('suppressFooter', TRUE);
            	$this->footerStr = NULL;
				//$this->setVar('pageSuppressTrailingDiv', TRUE);
				$dsn = "imap://fsiu:fsiu@itsnw.uwc.ac.za:143/Inbox";
				try {
					//connect to the server
					$this->objImap->factory($dsn);
					$this->objImap->getHeaders();
					//check mail
					$thebox = $this->objImap->checkMbox();
					$folders = $this->objImap->populateFolders($thebox);
					$msgCount = $this->objImap->numMails();

					//get the meassge headers
					$i = 1;
					while ($i <= $msgCount)
					{
						//echo $i;
						$headerinfo = @$this->objImap->getHeaderInfo($i);
						//from
						$address = @$headerinfo->fromaddress;
						//subject
						$subject = @$headerinfo->subject;
						//date
						$date = @$headerinfo->Date;

						$data[] = array('address' => $address, 'subject' => $subject, 'date' => $date);

						$i++;
					}
					$this->setVarByRef('data', $data);

					return "msglist_tpl.php";
					break;

					//$line = $infoArr['addy'][0] . $infoArr['subject'][0] . $infoArr['date'][0];
					//echo $line;

				}
				catch (customException $e)
				{
					exit;
				}



		}
	}
}
?>