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
            	$this->requiresLogin(FALSE);
            	$userid = $this->getParam('userid');
            	if(!isset($userid))
            	{
            		$this->setVarByRef('message', $this->objLanguage->languageText("mod_blog_word_randomblog"));
            		//get a random blog from the blog table
            		$r = $this->objDbBlog->getRandBlog();
            		$userid = $r['userid'];
            	}
            	//carry on...
            	$catarr = $this->objDbBlog->getCatsTree($userid);
            	$linkcats = $this->objDbBlog->getAllLinkCats($userid);
            	$posts = $this->objDbBlog->getAllPosts($userid);
            	$this->setVarByRef('posts', $posts);
            	$this->setVarByRef('linkcats', $linkcats);
            	$this->setVarByRef('cats', $catarr);
                return 'randblog_tpl.php';

            	break;

            case 'viewblog':
                try {

                		$userid = $this->objUser->userId();
            			$catarr = $this->objDbBlog->getCatsTree($userid);
            			$linkcats = $this->objDbBlog->getAllLinkCats($userid);
            			$posts = $this->objDbBlog->getAllPosts($userid);
            			$this->setVarByRef('posts', $posts);
            			$this->setVarByRef('linkcats', $linkcats);
            			$this->setVarByRef('cats', $catarr);
                		return 'myblog_tpl.php';
                    }
                    catch(customException $e) {
                        customException::cleanUp();
                    }
                break;

            case 'blogadmin':
            	$userid = $this->objUser->userId();
            	$this->setVarByRef('userid', $userid);
            	return 'blogadmin_tpl.php';
            	break;


            case 'testcats':
            	try {
            		$userid = $this->objUser->userId();
            		$linkcat2add = array('userid' => $userid, 'catname' => 'Blogroll', 'autotoggle' => 'N', 'show_images' => 'Y', 'show_description' => 'N', 'show_rating' => 'Y', 'show_updated' => 'Y', 'sort_order' => 'rand', 'sort_desc' => 'N', 'list_limit' => '-1');
            		$cat2add = array('userid' => $userid, 'cat_name' => 'Uncategorized', 'cat_nicename' => 'Uncategorized', 'cat_desc' => 'Uncategorized posts', 'cat_parent' => 0, 'cat_count' => 1);
            		$link2add = array('userid' => $userid, 'link_url' => 'http://fsiu.uwc.ac.za/kinky/index.php?module=blog&action=showblog&blogger=1339050927', 'link_name' => 'Pauls FSIU Blog', 'link_image' => '', 'link_target' => '', 'link_category' => 'init_5101_1161324562', 'link_description' => 'FSIU blog for Paul Scott', 'link_visible' => 'Y', 'link_owner' => $userid, 'link_rating' => '1', 'link_updated' => '', 'link_rel' => '', 'link_notes' => 'Paul Scott has a FSIU blog too', 'link_rss' => 'http://fsiu.uwc.ac.za/kinky/index.php?module=blog&action=genrss&blogger=1339050927');

            		print_r($this->objDbBlog->setLink($userid, $link2add));
            		//print_r($this->objDbBlog->setCats($userid, $cat2add));
            		//print_r($this->objDbBlog->setLinkCats($userid, $linkcat2add));
            		//print_r($this->objDbBlog->getAllLinkCats($userid));
            	}
            	catch(customException $e) {
                        customException::cleanUp();
                }
        }

    }

    public function requiresLogin()
    {
    	return FALSE;
    }
}
?>