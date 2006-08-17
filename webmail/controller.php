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
                $dsn = "imap://fsiu:fsiu@itsnw.uwc.ac.za:143/INBOX";
                try {
                    $this->objImap->factory($dsn);
                    $thebox = $this->objImap->checkMbox();
                    //var_dump($thebox);
                    $numUnread = 0;
                    $inbox = array();
                    $trash = array();
                    $sentitems = array();
                    foreach ($thebox as $messages)
                    {
                    	if($messages->seen == 0)
                    	{
                    		$numUnread++;
                    	}
                    	//populate the inbox
                    	if ($messages->deleted != 1)
                    	{
                    		$inbox[] .= $messages->msgno;
                    	}
                    	//populate the trash (deleted items)
                    	if ($messages->deleted == 1)
                    	{
                    		$trash[] .= $messages->msgno;
                    	}
                    	//populate the sent items
                    	if ($messages->answered == 1)
                    	{
                    		$sentitems[] .= $messages->msgno;
                    	}
                    }
                    echo "You have " . $numUnread . " unread message(s)<br><br>";


                    print_r($inbox);
                    print_r($trash);
                    print_r($sentitems);


                    //return 'success_tpl.php';
                }
                catch(customException $e) {
                    customException::cleanUp();
                }
        }
    }
}
?>