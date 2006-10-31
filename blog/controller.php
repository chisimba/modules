<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
	die("You cannot view this page directly");
}
// end security check

class blog extends controller
{
	/**
	 * User object
	 *
	 * @var user
	 */
	public $objUser;

	/**
	 * Language Object
	 *
	 * @var object
	 */
	public $objLanguage;

	/**
	 * Logger object
	 *
	 * @var object
	 */
	public $objLog;

	/**
	 * Feed object
	 *
	 * @var object
	 */
	public $objFeed;

	/**
	 * Feed creator object
	 *
	 * @var object
	 */
	public $objFeedCreator;

	/**
	 * HTTP Client object
	 *
	 * @var object
	 */
	public $objClient;

	/**
	 * Database abstraction object
	 *
	 * @var object
	 */
	public $objDbBlog;

	/**
	 * Configuration object
	 *
	 * @var object
	 */
	public $objConfig;

	/**
	 * Operations baseclass
	 *
	 * @var object
	 */
	public $objblogOps;

	/**
	 * HTML Cleaner object
	 *
	 * @var object
	 */
	public $cleaner;

	/**
	 * Icon Object
	 *
	 * @var object
	 */
	public $objIcon;

	/**
	 * Lucene indexer object
	 *
	 * @var object
	 */
	public $luceneindexer;

	/**
	 * Lucene document Object
	 *
	 * @var object
	 */
	public $lucenedoc;

	/**
     * Constructor method to instantiate objects and get variables
     *
     * @param void
     * @return string
     * @access public
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
			$this->objIcon = &$this->getObject('geticon', 'htmlelements');
			//$this->lucenedoc = &$this->getObject('doc','lucene');
			//$this->luceneindexer = &$this->getObject('indexfactory', 'lucene');
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
     * @return string template
     */
	public function dispatch($action = Null)
	{
		switch ($action) {
			default:
				if($this->objUser->isLoggedIn() == TRUE)
				{
					$act = $this->getParam('action');
					if($act != 'randblog')
					{
						$this->nextAction('viewblog');
						exit;
					}

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
					$userid = $this->getParam('userid');
					if(empty($userid))
					{
						$userid = $this->objUser->userId();
					}

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
				$mode = $this->getParam('mode');
				switch ($mode)
				{
					case 'writepost':
						return 'writepost_tpl.php';
						break;
					case 'editpost':
						return 'editpost_tpl.php';
						break;
					case 'editcats':
						return 'editcats_tpl.php';
						break;

				}
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
				$id = $this->getParam('id');
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
												    'postcat' => $cat, 'postexcerpt' => '', 'poststatus' => '0',
												    'commentstatus' => 'Y',
												    'postmodified' => date('r'), 'commentcount' => 0));
					$this->nextAction('viewblog');
					break;
				}
				elseif($mode == 'editpost') {
					$insarredit = array('id' => $id,'posttitle' => $posttitle, 'postcontent' => $postcontent,
												    'postcat' => $cat, 'postexcerpt' => $excerpt, 'poststatus' => $status,
												    'commentstatus' => 'Y',
												    'postmodified' => date('r'), 'commentcount' => 0);

					$this->objblogOps->quickPostAdd($userid, $insarredit, $mode);
					$this->nextAction('viewblog');
					break;
				}
				else {
					$this->objblogOps->quickPostAdd($userid, array('id' => $id, 'posttitle' => $posttitle, 'postcontent' => $postcontent,
												    'postcat' => $cat, 'postexcerpt' => $excerpt, 'poststatus' => $status,
												    'commentstatus' => $commentsallowed,
												    'postmodified' => date('r'), 'commentcount' => 0));
					$this->nextAction('viewblog');
					break;
				}

				break;

			case 'deletepost':
				$id = $this->getParam('id');
				$this->objDbBlog->deletePost($id);
				$this->nextAction('blogadmin');

				break;

			case 'postedit':
				$userid = $this->objUser->userId();
				$id = $this->getParam('id');
				$this->setVarByRef('editid', $id);
				$this->setVarByRef('userid', $userid);
				return 'postedit_tpl.php';
				break;

			case 'allblogs':
				$ret = $this->objDbBlog->getUBlogs('userid', 'tbl_blog_posts');
				$this->setVarByRef('ret',$ret);
				return 'allblogs_tpl.php';
				break;

		}

	}

	/**
	 * Ovveride the login object in the parent class
	 *
	 * @param void
	 * @return bool
	 * @access public
	 */
	public function requiresLogin()
	{
		return FALSE;
	}
}
?>