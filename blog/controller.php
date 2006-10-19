<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

class blog extends controller
{
	public $objUser;
	public $objLanguage;
	public $objLog;
	public $objFeed;
	public $objFeedCreator;
	public $objClient;
	public $objDbBlog;

    /**
     * Constructor method to instantiate objects and get variables
     */
    public function init()
    {
        try {
        	$this->objFeed = $this->getObject('feeds', 'feed');
        	$this->objUser = $this->getObject('user', 'security');
        	$this->objFeedCreator = $this->getObject('feeder', 'feed');
        	$this->objClient = $this->getObject('client','httpclient');
            $this->objLanguage = $this->getObject('language', 'language');
            $this->objDbBlog = $this->getObject('dbblog');
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
                try {
                		$userid = $this->objUser->userId();
            			$catarr = $this->objDbBlog->getCatsTree($userid);
            			$this->setVarByRef('cats', $catarr);
                		return 'myblog_tpl.php';
                    }
                    catch(customException $e) {
                        customException::cleanUp();
                    }
                break;

            case 'buildcats':
            	try {
            		$userid = $this->objUser->userId();
            		$catarr = $this->objDbBlog->getCatsTree($userid);
            		$this->setVarByRef('cats', $catarr);
            		break;
            	}
            	catch(customException $e) {
                        customException::cleanUp();
                }

            case 'testcats':
            	try {
            		$userid = $this->objUser->userId();
            		$cat2add = array('userid' => $userid, 'catid' => 5, 'cat_name' => 'stuff kid', 'cat_nicename' => 'stuff kid', 'cat_desc' => 'stuff kid', 'cat_parent' => 'init_5607_1161173109', 'cat_count' => 2);
            		//print_r($this->objDbBlog->setCats($userid, $cat2add));
            		print_r($this->objDbBlog->getCatsTree($userid));
            	}
            	catch(customException $e) {
                        customException::cleanUp();
                }
        }

    }
}
?>