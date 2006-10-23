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
	public $objConfig;
	public $objblogOps;

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
			$this->objblogOps = &$this->getObject('blogops');
			//config object
			$this->objConfig = $this->getObject('altconfig', 'config');
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
				if($this->objUser->isLoggedIn() == TRUE)
				{
					$this->nextAction('viewblog');
					exit;
				}
				$this->requiresLogin(FALSE);
				$userid = $this->getParam('userid');
				if(!isset($userid))
				{
					$this->setVarByRef('message', $this->objLanguage->languageText("mod_blog_word_randomblog"));
					//get a random blog from the blog table
					$r = $this->objDbBlog->getRandBlog();
					$userid = $r['userid'];
					$this->setVarByRef('userid', $userid);
				}
				else {
					$this->setVarByRef('userid', $userid);
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

			case 'feed':
				$format = $this->getParam('format');
				$userid = $this->getParam('userid');

				//grab the feed items
				$posts = $this->objDbBlog->getAllPosts($userid);
				foreach($posts as $feeditems)
				{
					$itemTitle = $feeditems['post_title'];
					$itemLink = ''; //todo - add this to the posts table!
					$itemDescription = $feeditems['post_excerpt'];
					$itemSource = $this->objConfig->getSiteRoot() . "index.php?module=blog&userid=" . $userid;
					$itemAuthor = htmlentities($this->objUser->fullname($userid));

					$this->objFeedCreator->addItem($itemTitle, $itemLink, $itemDescription, $itemSource, $itemAuthor);
				}

				//set up the feed...
				$fullname = htmlentities($this->objUser->fullname($userid));
				$feedtitle = htmlentities($fullname);
				$feedDescription = htmlentities($this->objLanguage->languageText("mod_blog_blogof", "blog")) . " " . $fullname;
				$feedLink = $this->objConfig->getSiteRoot() . "index.php?module=blog&userid=" . $userid;
				$feedLink = htmlentities($feedLink);
				$feedURL = $this->objConfig->getSiteRoot() . "index.php?module=blog&userid=" . $userid . "action=feed&format=" . $format;
				$feedURL = htmlentities($feedURL);
				$this->objFeedCreator->setupFeed(TRUE,$feedtitle, $feedDescription, $feedLink, $feedURL);

				switch ($format) {
					case 'rss2':
						$feed = $this->objFeedCreator->output(); //defaults to RSS2.0
						break;
					case 'rss091':
						$feed = $this->objFeedCreator->output('RSS0.91');
						break;
					case 'rss1':
						$feed = $this->objFeedCreator->output('RSS1.0');
						break;
					case 'pie':
						$feed = $this->objFeedCreator->output('PIE0.1');
						break;
					case 'mbox':
						$feed = $this->objFeedCreator->output('MBOX');
						break;
					case 'opml':
						$feed = $this->objFeedCreator->output('OPML');
						break;
					case 'atom':
						$feed = $this->objFeedCreator->output('ATOM0.3');
						break;
					case 'html':
						$feed = $this->objFeedCreator->output('HTML');
						break;
					case 'js':
						$feed = $this->objFeedCreator->output('JS');
						break;

					default:
						$feed = $this->objFeedCreator->output(); //defaults to RSS2.0
						break;
				}

				//output the feed
				echo htmlentities($feed);

				break;

			case 'showsection':
				//sections should only return the bits in the section for the month...



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