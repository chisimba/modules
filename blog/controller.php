<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
	die("You cannot view this page directly");
}
// end security check

/**
 * Controller class for the blog module that extends the base controller
 *
 * @author Paul Scott <pscott@uwc.ac.za>
 * @copyright AVOIR
 * @package blog
 * @category chisimba
 * @licence GPL
 */
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
	 * Blog import object
	 *
	 * @var object
	 */
	public $objBlogImport;

	/**
	 * IMAP / POP3 / NNTP comms class
	 *
	 * @var object
	 */
	public $objImap;

	/**
	 * DSN (data source name) for connecting to mail servers
	 *
	 * @var unknown_type
	 */
	public $dsn;

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
			//grab the blogimporter class, just in case we need it.
			//I think that the import stuff should all be done in a seperate module...
			$this->objBlogImport = &$this->getObject('blogimporter');

			//get the imap class to grab email to blog...
			//maybe a config here to check if we wanna use this?
			$this->objImap = $this->getObject('imap', 'webmail');

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

			case 'mail2blog':
				$this->dsn = "pop3://fsiu:fsiu@itsnw.uwc.ac.za:110/INBOX";
				try {
					//grab a list of all valid users to an array for verification later
					$valid = $this->objDbBlog->checkValidUser();
					$valadds = array();
					foreach($valid as $addys)
					{
						$valadds[] = array('address' => $addys['emailaddress'], 'userid' => $addys['userid']);
					}

					//connect to the server
					$this->conn = $this->objImap->factory($this->dsn);
					@$this->objImap->getHeaders();
					//check mail
					$this->thebox = @$this->objImap->checkMbox();
					//var_dump($thebox);
					$this->folders = @$this->objImap->populateFolders($this->thebox);
					$this->msgCount = @$this->objImap->numMails();
					//$this->setVarByRef('folders', $folders);

					//get the meassge headers
					$i = 1;

					while ($i <= @$this->msgCount)
					{
						//echo $i;
						$headerinfo = @$this->objImap->getHeaderInfo($i);
						//from
						$address = @$headerinfo->fromaddress;
						//subject
						$subject = @$headerinfo->subject;
						//date
						$date = @$headerinfo->Date;
						//message flag
						$read = @$headerinfo->Unseen;
						//message body
						$bod = @$this->objImap->getMessage($i);
						if(empty($bod[1]))
						{
							$attachments = NULL;
						}
						else {
							$attachments = $bod[1];
						}
						$message = @htmlentities($bod[0]);

						//check for a valid user
						if(!empty($address))
						{
							//check the address against tbl_users to see if its valid.
							//just get the email addy, we dont need the name as it can be faked
							$fadd = $address;
							$parts = explode("<", $fadd);
							$parts = explode(">", $parts[1]);
							$addy = $parts[0];

							//echo $addy;

							//check if the address we get from the msg is in the array of valid addresses
							//print_r($valadds);
							//if(in_array($addy,$valadds))
							//{
							//	$validated = "TRUE";
							//}
							foreach ($valadds as $user)
							{
								if($user['address'] != $addy)
								{
									echo $user['address'] . "  " . $addy;
									$validated = "FALSE";

								}
								else {
									//echo "userid match - Proceed!";
									$validated = "TRUE";
									$userid = $user['userid'];

								}
							}
						}
print_r($validated);
						if($validated == "TRUE")
						{
							$data[] = array('userid' => $userid,'address' => $address, 'subject' => $subject, 'date' => $date, 'messageid' => $i, 'read' => $read,
											'body' => $message, 'attachments' => $attachments);
							print_r($data);
						}

						//delete the message as we don't need it anymore
						echo "sorting " . $this->msgCount . "messages";
						//$this->objImap->delMsg($i);

						$i++;
					}

					//print_r($data);
					if(!isset($data))
					{
						$data = array();
					}
					foreach ($data as $datum)
					{
						//add the [img][/img] tags to the body so that the images show up
						//we discard any other mimetypes for now...
						if(!empty($datum['attachments']))
						{
							//print_r($datum);
							$filename = $datum['attachments'][0]['filename'];
							$filedata = base64_decode($datum['attachments'][0]['filedata']);
							$path = $this->objConfig->getContentBasePath() . 'blog/';
							if(!file_exists($path))
							{
								mkdir($path, 0777);
							}
							chdir($path);
							$handle = fopen($filename, 'wb');
							fwrite($handle, $filedata);
							fclose($handle);

							//add the img stuff to the body at the end of the "post"
							$newbod = $datum['body'] . "[img]" . $this->objConfig->getSiteRoot() . 'usrfiles/blog/' . $filename . "[/img]";
							//echo $newbod;
						}
						else {
							$newbod = $datum['body'];
						}

						$this->objblogOps->quickPostAdd($datum['userid'], array('posttitle' => $datum['subject'], 'postcontent' => $newbod,
												    'postcat' => 0, 'postexcerpt' => '', 'poststatus' => '0',
												    'commentstatus' => 'Y',
												    'postmodified' => date('r'), 'commentcount' => 0, 'postdate' => $datum['date']));
					}




				}
				catch(customException $e) {
					customException::cleanUp();
				}
				break;





			case 'importblog':
				$username = $this->getParam('username');
				$server = $this->getParam('server');
				//set up to connect to the server
				$this->objBlogImport->setup($server);
				//connect to the remote db
				$this->objBlogImport->_dbObject();
				$blog = $this->objBlogImport->importBlog($username);
				//dump it to screen as a debug
				print_r($blog);
				//sort through the returned array and see where to put the stuff
				//use the insertPost methods to populate...

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
					if(isset($catid))
					{
						$posts = $this->objDbBlog->getAllPosts($userid, $catid);
					}
					else {
						$posts = $this->objDbBlog->getAbsAllPostsNoDrafts($userid);
					}
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

			case 'showarchives':
				$date = $this->getParam('year');
				$userid = $this->getParam('userid'); //$this->objUser->userId();
				$posts = $this->objDbBlog->getPostsMonthly($date, $userid);
				$this->setVarByRef('userid', $userid);
				$this->setVarByRef('posts', $posts);
				return 'archive_tpl.php';
				break;

			case 'catadd':
				$mode = $this->getParam('mode');
				$list = $this->getParam('catname');
				$userid = $this->objUser->userId();
				$catname = $this->getParam('catname');
				$catparent = $this->getParam('catparent');
				$catdesc = $this->getParam('catdesc');
				$id = $this->getParam('id');
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
				if($mode == 'edit')
				{
					//update the records in the db
					//build the array again
					$entry = $this->objDbBlog->getCatForEdit($userid, $id);
					$catarr = array('userid' => $userid,
									'cat_name' => $entry['cat_name'],
									'cat_nicename' => $entry['cat_nicename'],
									'cat_desc' => $entry['cat_desc'],
									'cat_parent' => $entry['cat_parent'],
									'id' => $id);

					//display the cat editor with the values in the array, set that form to editcommit
					$this->setVarByRef('catarr', $catarr);
					$this->setVarByRef('userid', $userid);
					$this->setVarByRef('catid', $id);
					return 'cedit_tpl.php';
					break;
				}
				if($mode == 'editcommit')
				{
					$catarr = array('userid' => $userid, 'cat_name' => $catname, 'cat_nicename' => $catname, 'cat_desc' => $catdesc, 'cat_parent' => $catparent, 'id' => $id);
					$this->objDbBlog->setCats($userid, $catarr, $mode);
					$this->nextAction('blogadmin', array('mode' => 'editcats'));
				}

				if($mode == NULL)
				{
					$catarr = array('userid' => $userid, 'cat_name' => $catname, 'cat_nicename' => $catname, 'cat_desc' => $catdesc, 'cat_parent' => $catparent);
					//insert the category into the db
					$this->objDbBlog->setCats($userid, $catarr);

					$this->nextAction('blogadmin');
					break;
				}
				break;



			case 'postadd':
				$mode = $this->getParam('mode');
				$userid = $this->objUser->userId();
				$id = $this->getParam('id');
				$posttitle = $this->getParam('posttitle');
				$postcontent = $this->getParam('postcontent');
				$postdate = $this->getParam('postdate');
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
												    'postmodified' => date('r'), 'commentcount' => 0, 'postdate' => $postdate));
					$this->nextAction('viewblog');
					break;
				}
				elseif($mode == 'editpost') {
					$insarredit = array('id' => $id,'posttitle' => $posttitle, 'postcontent' => $postcontent,
												    'postcat' => $cat, 'postexcerpt' => $excerpt, 'poststatus' => $status,
												    'commentstatus' => 'Y',
												    'postmodified' => date('r'), 'commentcount' => 0, 'postdate' => $postdate);

					$this->objblogOps->quickPostAdd($userid, $insarredit, $mode);
					$this->nextAction('viewblog');
					break;
				}
				else {
					$this->objblogOps->quickPostAdd($userid, array('id' => $id, 'posttitle' => $posttitle, 'postcontent' => $postcontent,
												    'postcat' => $cat, 'postexcerpt' => $excerpt, 'poststatus' => $status,
												    'commentstatus' => $commentsallowed,
												    'postmodified' => date('r'), 'commentcount' => 0, 'postdate' => $postdate));
					$this->nextAction('viewblog');
					break;
				}

				break;

			case 'deletepost':
				$id = $this->getParam('id');
				$this->objDbBlog->deletePost($id);
				$this->nextAction('blogadmin', array('mode' => 'editpost'));

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

			case 'deletecat':
				//grab the vars that we need
				$id = $this->getParam('id');
				$userid = $this->objUser->userId();
				$mode = $this->getParam('mode');
				//echo $id, $mode;
				//search through the posts table to find all the posts linked to the cat
				$posts = $this->objDbBlog->getPostsFromCat($userid, $id);
				foreach($posts as $post)
				{
					//update the found posts and set their cat to '0'
					$insarredit = array('id' => $post['id'],'posttitle' => $post['post_title'], 'postcontent' => $post['post_content'],
												    'postcat' => 0, 'postexcerpt' => $post['post_excerpt'], 'poststatus' => $post['post_status'],
												    'commentstatus' => $post['comment_status'],
												    'postmodified' => $post['post_modified'], 'commentcount' => $post['comment_count'], 'postdate' => $post['post_date']);

					$this->objblogOps->quickPostAdd($userid, $insarredit, 'editcommit');

				}
				//delete the cat from the table
				$this->objDbBlog->deleteCat($id);
				//nextaction back to the cats view thing
				$this->nextAction('blogadmin', array('mode' => 'editcats'));
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