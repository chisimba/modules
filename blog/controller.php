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
            			$linkcats = $this->objDbBlog->getAllLinkCats($userid);
            			$this->setVarByRef('linkcats', $linkcats);
            			$this->setVarByRef('cats', $catarr);
                		return 'myblog_tpl.php';
                    }
                    catch(customException $e) {
                        customException::cleanUp();
                    }
                break;


            case 'testcats':
            	try {
            		$userid = $this->objUser->userId();
            		$linkcat2add = array('userid' => $userid, 'catname' => 'work', 'autotoggle' => 'N', 'show_images' => 'Y', 'show_description' => 'N', 'show_rating' => 'Y', 'show_updated' => 'Y', 'sort_order' => 'rand', 'sort_desc' => 'N', 'list_limit' => '-1');
            		$cat2add = array('userid' => $userid, 'cat_name' => 'Uncategorized', 'cat_nicename' => 'Uncategorized', 'cat_desc' => 'Uncategorized posts', 'cat_parent' => 0, 'cat_count' => 1);
            		print_r($this->objDbBlog->setCats($userid, $cat2add));
            		//print_r($this->objDbBlog->setLinkCats($userid, $linkcat2add));
            		//print_r($this->objDbBlog->getAllLinkCats($userid));
            	}
            	catch(customException $e) {
                        customException::cleanUp();
                }
        }

    }
}
?>