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
	public $cleaner;

	/**
     * Constructor method to instantiate objects and get variables
     */
	public function init()
	{
		try {
			$this->objFeed = &$this->getObject('feeds', 'feed');
			$this->objUser = $this->getObject('user', 'security');
			$this->objFeedCreator = &$this->getObject('feeder', 'feed');
			$this->objClient = $this->getObject('client','httpclient');
			$this->objLanguage = $this->getObject('language', 'language');
			$this->objDbBlog = $this->getObject('dbblog');
			$this->objblogOps = &$this->getObject('blogops');
			$this->cleaner = &$this->getObject('htmlcleaner', 'utilities');
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
					if(!empty($r))
					{
						$userid = $r['userid'];
						$this->setVarByRef('userid', $userid);
					}
					else {
						return 'noblogs_tpl.php';
					}
				}
				else {
					$this->setVarByRef('userid', $userid);
				}
				//carry on...
				$catarr = $this->objDbBlog->getCatsTree($userid);
				$linkcats = $this->objDbBlog->getAllLinkCats($userid);
				$posts = $this->objDbBlog->getAllPosts($userid, $catid = NULL);
				$this->setVarByRef('posts', $posts);
				$this->setVarByRef('linkcats', $linkcats);
				$this->setVarByRef('cats', $catarr);
				return 'randblog_tpl.php';

				break;

			case 'feed':
				$format = $this->getParam('format');
				$userid = $this->getParam('userid');

				//grab the feed items
				$posts = $this->objDbBlog->getAllPosts($userid, $catid = NULL);

				//set up the feed...
				$fullname = htmlentities($this->objUser->fullname($userid));
				$feedtitle = htmlentities($fullname);
				$feedDescription = htmlentities($this->objLanguage->languageText("mod_blog_blogof", "blog")) . " " . $fullname;
				$feedLink = $this->objConfig->getSiteRoot() . "index.php?module=blog&userid=" . $userid;
				$feedLink = htmlentities($feedLink);
				$feedURL = $this->objConfig->getSiteRoot() . "index.php?module=blog&userid=" . $userid . "action=feed&format=" . $format;
				$feedURL = htmlentities($feedURL);
				$this->objFeedCreator->setupFeed(TRUE,$feedtitle, $feedDescription, $feedLink, $feedURL);

				foreach($posts as $feeditems)
				{
					$itemTitle = $feeditems['post_title'];
					$itemLink = ''; //todo - add this to the posts table!
					$itemDescription = $feeditems['post_content'];
					$itemSource = $this->objConfig->getSiteRoot() . "index.php?module=blog&userid=" . $userid;
					$itemAuthor = htmlentities($this->objUser->fullname($userid));

					$this->objFeedCreator->addItem($itemTitle, $itemLink, $itemDescription, $itemSource, $itemAuthor);
				}



				//$this->objFeedCreator->setupFeed(TRUE,$feedtitle, $feedDescription, $feedLink, $feedURL);

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
					$catid = $this->getParam('catid');

					$userid = $this->objUser->userId();
					$catarr = $this->objDbBlog->getCatsTree($userid);
					$linkcats = $this->objDbBlog->getAllLinkCats($userid);
					$posts = $this->objDbBlog->getAllPosts($userid, $catid);
					$this->setVarByRef('catid', $catid);
					$this->setVarByRef('posts', $posts);
					$this->setVarByRef('linkcats', $linkcats);
					$this->setVarByRef('cats', $catarr);
					$this->setVarByRef('userid', $userid);
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

			case 'catadd':
				$mode = $this->getParam('mode');
				$list = $this->getParam('catname');
				$userid = $this->objUser->userId();
				$catname = $this->getParam('catname');
				$catparent = $this->getParam('catparent');
				$catdesc = $this->getParam('catdesc');
				//category quick add
				if($mode == 'quickadd')
				{
					if(empty($list))
					{
						$this->nextAction('blogadmin');
					    break;
					}
					$this->objblogOps->quickCatAdd($list, $userid);
					$this->nextAction('blogadmin');
					break;
				}

				$catarr = array('userid' => $userid, 'cat_name' => $catname, 'cat_nicename' => $catname, 'cat_desc' => $catdesc, 'cat_parent' => $catparent);
				//insert the category into the db
				$this->objDbBlog->setCats($userid, $catarr);

				$this->nextAction('blogadmin');
				break;

			case 'postadd':
				$mode = $this->getParam('mode');
				$userid = $this->objUser->userId();
				$posttitle = $this->getParam('posttitle');
				$postcontent = $this->getParam('postcontent');
				$cat = $this->getParam('cat');
				$status = $this->getParam('status');
				$commentsallowed = $this->getParam('commentsallowed');
				$excerpt = $this->getParam('postexcerpt');

				//post quick add
				if($mode == 'quickadd')
				{
					$this->objblogOps->quickPostAdd($userid, array('posttitle' => $posttitle, 'postcontent' => $postcontent,
												    'postcat' => $cat, 'postexcerpt' => '', 'poststatus' => 'Published',
												    'commentstatus' => 'Y',
												    'postmodified' => date('r'), 'commentcount' => 0));
					$this->nextAction('viewblog');
					break;
				}
				else {
					$this->objblogOps->quickPostAdd($userid, array('posttitle' => $posttitle, 'postcontent' => $postcontent,
												    'postcat' => $cat, 'postexcerpt' => $excerpt, 'poststatus' => $status,
												    'commentstatus' => $commentsallowed,
												    'postmodified' => date('r'), 'commentcount' => 0));
					$this->nextAction('viewblog');
					break;
				}

				break;

		}

	}

	public function requiresLogin()
	{
		return FALSE;
	}
}
?>