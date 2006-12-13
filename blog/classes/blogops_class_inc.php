<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
	die("You cannot view this page directly");
}
// end security check

/**
 * Class to handle blog elements
 * This object can be used elsewhere in the system to render certain aspects of the interface
 *
 * @author Paul Scott
 * @copyright GNU/GPL, AVOIR
 * @package blog
 * @access public
 */

class blogops extends object
{

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
			$this->objLanguage = $this->getObject('language', 'language');
			$this->objDbBlog = $this->getObject('dbblog');
			$this->loadClass('href', 'htmlelements');
			$tt = $this->newObject('domtt', 'htmlelements');
		}
		catch(customException $e) {
			echo customException::cleanUp();
			die();
		}

	}

	public function commentAddForm()
	{

	}

	public function commentDelForm()
	{

	}

	public function passProtectPost()
	{

	}

	public function liveSearchForm()
	{

	}

	/**
	 * Method to display the login box for prelogin blog operations
	 *
	 * @param bool $featurebox
	 * @return string
	 */
	public function loginBox($featurebox = FALSE)
	{
		$objLogin = & $this->getObject('logininterface', 'security');
		if($featurebox == FALSE)
		{
			return $objLogin->renderLoginBox();
		}
		else {
			$objFeatureBox = $this->getObject('featurebox', 'navigation');
			return $objFeatureBox->show($this->objLanguage->languageText("word_login", "system"), $objLogin->renderLoginBox());
		}
	}

	/**
	 * Method to display a link to all the blogs on the system
	 * Setting $featurebox = TRUE will display the link in a block style featurebox
	 *
	 * @param bool $featurebox
	 * @return string
	 */
	public function showBlogsLink($featurebox = FALSE)
	{
		//set up a link to the other users blogs...
		$oblogs = new href($this->uri(array('action' => 'allblogs')),$this->objLanguage->languageText("mod_blog_viewallblogs", "blog"), NULL);
		if($featurebox == FALSE)
		{
			$ret = $oblogs->show();
		}
		else {
			$objFeatureBox = $this->getObject('featurebox', 'navigation');
			$ret = $objFeatureBox->show($this->objLanguage->languageText("mod_blog_otherblogs","blog"), $oblogs->show());
		}
		return $ret;
	}

	/**
	 * Method to build and display the categories menu
	 * Setting the optional featurebox parameter to true will display the categories in a featurebox block
	 *
	 * @param array $cats
	 * @param bool $featurebox
	 * @return string
	 */
	public function showCatsMenu($cats, $featurebox = FALSE)
	{
		$objSideBar = $this->newObject('sidebar', 'navigation');
		$nodes = array();
		$childnodes = array();

		if(!empty($cats))
		{
			$ret = "<em>" . $this->objLanguage->languageText("mod_blog_categories", "blog") . "</em>";
			$ret .= "<br />";
			foreach($cats as $categories)
			{
				//build the sub list as well
				if(isset($categories['child']))
				{
					foreach($categories['child'] as $kid)
					{
						$childnodes[] = array('text' => $kid['cat_nicename'], 'uri' => $this->uri(array('action' => 'viewblog', 'catid' => $kid['id']), 'blog'));
					}
				}
				$nodestoadd[] = array('text'=> $categories['cat_nicename'], 'uri' => $this->uri(array('action' => 'viewblog',
				'catid' => $categories['id']), 'blog'),'haschildren' => $childnodes);
				$childnodes = NULL;
				$nodes = NULL;
			}
			$ret .= $objSideBar->show($nodestoadd, NULL, NULL, 'blog', $this->objLanguage->languageText("mod_blog_word_default", "blog"));
		}
		else {
			//no cats defined
			$ret = NULL;
		}
		if($featurebox == FALSE)
		{
			return $ret;
		}
		else {
			if(is_null($ret))
			{
				return NULL;
			}
			$objFeatureBox = $this->getObject('featurebox', 'navigation');
			$ret = $objFeatureBox->show($this->objLanguage->languageText("mod_blog_categories","blog"), $ret);
			return $ret;
		}
	}

	/**
	 * Method to display a link categories box
	 *
	 * @param array $linkcats
	 * @param bool $featurebox
	 * @return string
	 */
	public function showLinkCats($linkcats, $featurebox = FALSE)
	{
		$this->objUser = &$this->getObject('user', 'security');
		//cycle through the link categories and display them
		foreach($linkcats as $lc)
		{
			$ret = "<em>" . $lc['catname'] . "</em><br />";
			$linkers = $this->objDbBlog->getLinksCats($this->objUser->userid(), $lc['id']);
			if(!empty($linkers))
			{
				$ret .= "<ul>";
				foreach($linkers as $lk)
				{
					$ret .= "<li>";

					$alt = htmlentities($lk['link_description']);
					$link = new href(htmlentities($lk['link_url']), htmlentities($lk['link_name']), "alt='{$alt}'");
					$ret .= $link->show();
					$ret .= "</li>";
				}
				$ret .= "</ul>";
			}
		}
		if($featurebox == FALSE)
		{
			return $ret;
		}
		else {
			$objFeatureBox = $this->getObject('featurebox', 'navigation');
			if(!isset($ret))
			{
				$ret = NULL;
			}
			$ret = $objFeatureBox->show($this->objLanguage->languageText("mod_blog_linkcategories","blog"), $ret);
			return $ret;
		}
	}

	/**
	 * Method to create a form to import the blog data from a remote
	 *
	 * @param bool $featurebox
	 * @return string
	 */
	public function showImportForm($featurebox = TRUE)
	{
		$this->objUser = $this->getObject('user', 'security');
		$imform = new form('importblog', $this->uri(array('action' => 'importblog')));
		//add rules
		$imform->addRule('server',$this->objLanguage->languageText("mod_blog_phrase_imserverreq", "blog"),'required');
		$imform->addRule('username',$this->objLanguage->languageText("mod_blog_phrase_imuserreq", "blog"),'required');
		//start a fieldset
		$imfieldset = $this->getObject('fieldset', 'htmlelements');
		//$imfieldset->setLegend($this->objLanguage->languageText('mod_blog_importblog', 'blog'));
		$imadd = $this->newObject('htmltable', 'htmlelements');
		$imadd->cellpadding = 5;

		//server dropdown
		$servdrop = new dropdown('server');
		$servdrop->addOption("fsiu", $this->objLanguage->languageText("mod_blog_fsiu","blog"));
		$servdrop->addOption("elearn", $this->objLanguage->languageText("mod_blog_elearn","blog"));
		$imadd->startRow();
		$servlabel = new label($this->objLanguage->languageText('mod_blog_impserv', 'blog') .':', 'input_importfrom');
		$imadd->addCell($servlabel->show());
		$imadd->addCell($servdrop->show());
		$imadd->endRow();

		//username textfield
		$imadd->startRow();
		$imulabel = new label($this->objLanguage->languageText('mod_blog_impuser', 'blog') .':', 'input_impuser');
		$imuser = new textinput('username');
		$usernameval = $this->objUser->username();
		if(isset($usernameval))
		{
			$imuser->setValue($this->objUser->username());
		}
		$imadd->addCell($imulabel->show());
		$imadd->addCell($imuser->show());
		$imadd->endRow();

		//end off the form and add the buttons
		$this->objIMButton = &new button($this->objLanguage->languageText('word_import', 'system'));
		$this->objIMButton->setValue($this->objLanguage->languageText('word_import', 'system'));
		$this->objIMButton->setToSubmit();

		$imfieldset->addContent($imadd->show());
		$imform->addToForm($imfieldset->show());
		$imform->addToForm($this->objIMButton->show());

		$imform = $imform->show();

		if($featurebox == TRUE)
		{
			$objFeatureBox = $this->getObject('featurebox', 'navigation');
			$ret = $objFeatureBox->show($this->objLanguage->languageText("mod_blog_importblog","blog"), $imform);
			return $ret;
		}
		else {
			return $imform;
		}
	}

	/**
	 * Method to show a mail setup form to set the DSN for mail2blog
	 *
	 * @param bool $featurebox
	 * @param array $dsnarr
	 * @return string
	 */
	public function showMailSetup($featurebox = TRUE, $dsnarr = NULL)
	{
		//start a form to go back to the setupmail action with the vars
		//make sure that all form vars are required!
		$mform = new form('setupmail', $this->uri(array('action' => 'setupmail')));

		//add all the rules
		$mform->addRule('mprot',$this->objLanguage->languageText("mod_blog_phrase_mprotreq", "blog"),'required');
		$mform->addRule('mserver',$this->objLanguage->languageText("mod_blog_phrase_mserverreq", "blog"),'required');
		$mform->addRule('muser',$this->objLanguage->languageText("mod_blog_phrase_muserreq", "blog"),'required');
		$mform->addRule('mpass',$this->objLanguage->languageText("mod_blog_phrase_mpassreq", "blog"),'required');
		$mform->addRule('mport',$this->objLanguage->languageText("mod_blog_phrase_mportreq", "blog"),'required');
		$mform->addRule('mbox',$this->objLanguage->languageText("mod_blog_phrase_mboxreq", "blog"),'required');

		$mfieldset = $this->getObject('fieldset', 'htmlelements');
		$mfieldset->setLegend($this->objLanguage->languageText('mod_blog_setupmail', 'blog'));
		$madd = $this->newObject('htmltable', 'htmlelements');
		$madd->cellpadding = 5;

		//mail protocol field
		//dropdown for the POP/IMAP Chooser
		$protdrop = new dropdown('mprot');
		$protdrop->addOption("pop3", $this->objLanguage->languageText("mod_blog_pop3","blog"));
		$protdrop->addOption("imap", $this->objLanguage->languageText("mod_blog_imap","blog"));
		$madd->startRow();
		$protlabel = new label($this->objLanguage->languageText('mod_blog_mailprot', 'blog') .':', 'input_mprot');
		$madd->addCell($protlabel->show());
		$madd->addCell($protdrop->show());
		$madd->endRow();

		//Mail server field
		$madd->startRow();
		$mslabel = new label($this->objLanguage->languageText('mod_blog_mailserver', 'blog') .':', 'input_mserver');
		$mserver = new textinput('mserver');
		if(isset($dsnarr['server']))
		{
			$mserver->setValue($dsnarr['server']);
		}
		$madd->addCell($mslabel->show());
		$madd->addCell($mserver->show());
		$madd->endRow();

		//Mail user field
		$madd->startRow();
		$mulabel = new label($this->objLanguage->languageText('mod_blog_mailuser', 'blog') .':', 'input_muser');
		$muser = new textinput('muser');
		if(isset($dsnarr['user']))
		{
			$muser->setValue($dsnarr['user']);
		}
		$madd->addCell($mulabel->show());
		$madd->addCell($muser->show());
		$madd->endRow();

		//Mail password field
		$madd->startRow();
		$mplabel = new label($this->objLanguage->languageText('mod_blog_mailpass', 'blog') .':', 'input_mpass');
		$mpass = new textinput('mpass');
		if(isset($dsnarr['pass']))
		{
			$mpass->setValue($dsnarr['pass']);
		}
		$madd->addCell($mplabel->show());
		$madd->addCell($mpass->show());
		$madd->endRow();

		//mail port field
		//dropdown for the POP/IMAP port
		$portdrop = new dropdown('mport');
		$portdrop->addOption(110, $this->objLanguage->languageText("mod_blog_110","blog"));
		$portdrop->addOption(447, $this->objLanguage->languageText("mod_blog_447","blog"));
		$madd->startRow();
		$portlabel = new label($this->objLanguage->languageText('mod_blog_mailport', 'blog') .':', 'input_mport');
		$madd->addCell($portlabel->show());
		$madd->addCell($portdrop->show());
		$madd->endRow();

		//Mailbox field
		$madd->startRow();
		$mblabel = new label($this->objLanguage->languageText('mod_blog_mailbox', 'blog') .':', 'input_mbox');
		$mbox = new textinput('mbox');
		if(isset($dsnarr['mailbox']))
		{
			$mserver->setValue($dsnarr['mailbox']);
		}
		$mbox->setValue("INBOX");
		$madd->addCell($mblabel->show());
		$madd->addCell($mbox->show());
		$madd->endRow();

		$this->objMButton = &new button($this->objLanguage->languageText('word_save', 'system'));
		$this->objMButton->setValue($this->objLanguage->languageText('word_save', 'system'));
		$this->objMButton->setToSubmit();

		$mfieldset->addContent($madd->show());
		$mform->addToForm($mfieldset->show());
		$mform->addToForm($this->objMButton->show());

		$mform = $mform->show();


		if($featurebox == TRUE)
		{
			$objFeatureBox = $this->getObject('featurebox', 'navigation');
			$ret = $objFeatureBox->show($this->objLanguage->languageText("mod_blog_setupmail","blog"), $mform);
			return $ret;
		}


	}

	/**
	 * Method to display the link to blog admin on post login
	 *
	 * @param bool $featurebox
	 * @return string
	 */
	public function showAdminSection($featurebox = FALSE, $blogadmin = FALSE)
	{
		//admin section
		if($featurebox == FALSE)
		{
			$ret = "<em>" . $this->objLanguage->languageText("mod_blog_admin", "blog") . "</em><br />";
		}
		else {
			$ret = NULL;
		}


			//blog admin page
			$admin = new href($this->uri(array('action' => 'blogadmin')), $this->objLanguage->languageText("mod_blog_blogadmin", "blog"));
			//blog importer
			$import = new href($this->uri(array('action' => 'importblog')), $this->objLanguage->languageText("mod_blog_blogimport", "blog"));
			//mail setup
			$mailsetup = new href($this->uri(array('action' => 'setupmail')), $this->objLanguage->languageText("mod_blog_setupmail", "blog"));
			//write new post link
			$newpost = new href($this->uri(array('action' => 'blogadmin', 'mode' => 'writepost')), $this->objLanguage->languageText("mod_blog_writepost", "blog"));
			//edit existing posts
			$editpost = new href($this->uri(array('action' => 'blogadmin', 'mode' => 'editpost')), $this->objLanguage->languageText("mod_blog_word_editposts", "blog"));
			//edit/create cats
			$editcats = new href($this->uri(array('action' => 'blogadmin', 'mode' => 'editcats')), $this->objLanguage->languageText("mod_blog_word_categories", "blog"));
			//view all other blogs
			$viewblogs = new href($this->uri(array('action' => 'allblogs')), $this->objLanguage->languageText("mod_blog_viewallblogs", "blog"));
			//go back to your blog
			$viewmyblog = new href($this->uri(array('action' => 'viewblog')), $this->objLanguage->languageText("mod_blog_viewmyblog", "blog"));

		if($blogadmin == TRUE)
		{
			//build up a bunch of featureboxen and send em out
			//this will only happen with the front page (blogadmin template)
			$this->objUser = $this->getObject('user', 'security');
			if($this->objUser->inAdminGroup($this->objUser->userId()))
			{
				$linksarr = array($admin, $import, $mailsetup, $newpost, $editpost, $editcats, $viewblogs, $viewmyblog);
			}
			else {
				$linksarr = array($admin, $import, $newpost, $editpost, $editcats, $viewblogs, $viewmyblog);
			}
			foreach($linksarr as $links)
			{
				$objFeatureBox = $this->newObject('featurebox', 'navigation');
				$ret .= $objFeatureBox->show($this->objLanguage->languageText("mod_blog_admin","blog"), $links->show());
			}
			return $ret;
		}
		else{
		//build the links
		$this->objUser = $this->getObject('user', 'security');
		if($this->objUser->inAdminGroup($this->objUser->userId()))
		{
			$ret .= $admin->show() . "<br />" . $import->show() . "<br />" . $mailsetup->show() . "<br />" . $newpost->show()  . "<br />" . $editpost->show()  . "<br />" .
				$editcats->show()  . "<br />" . $viewblogs->show() . "<br />" . $viewmyblog->show();
		}
		else {
			$ret .= $admin->show() . "<br />" . $import->show() . "<br />" . $newpost->show()  . "<br />" . $editpost->show()  . "<br />" .
				$editcats->show()  . "<br />" . $viewblogs->show() . "<br />" . $viewmyblog->show();
		}

		}

		if($featurebox == FALSE)
		{
			return $ret;
		}
		else {
			$objFeatureBox = $this->getObject('featurebox', 'navigation');
			$ret = $objFeatureBox->show($this->objLanguage->languageText("mod_blog_admin","blog"), $ret);
			return $ret;
		}
	}

	/**
	 * Method to display the posts per user
	 *
	 * @param array $posts
	 * @return string
	 */
	public function showPosts($posts)
	{
		$this->objComments = &$this->getObject('commentinterface', 'comment');
		$this->objTB = $this->getObject("trackback");
		//set the trackback options
		$tboptions = array(
			        // Options for trackback class
        			'strictness'        => 1,
        			'timeout'           => 30,          // seconds
        			'fetchlines'        => 30,
        			'fetchextra'        => true,
        			// Options for HTTP_Request class
        			'httprequest'       => array(
            			'allowRedirects'    => true,
            			'maxRedirects'      => 2,
            			'method'            => 'GET',
            			'useragent'         => 'Chisimba',
        			),
    			);


		$ret = NULL;
		//Middle column (posts)!
		//break out the ol featurebox...
		if(!empty($posts))
		{
			foreach($posts as $post)
			{
				$objFeatureBox = $this->getObject('featurebox', 'navigation');
				//build the top level stuff
				$dt = date('r', $post['post_ts']);
				$this->objUser = $this->getObject('user', 'security');
				$userid = $this->objUser->userId();

				$head = $post['post_title'] . "<br />" . $dt;
				//dump in the post content and voila! you have it...
				//build the post content plus comment count and stats???
				//do the BBCode Parsing
				try {
					$this->bbcode = $this->getObject('bbcodeparser', 'utilities');
				}
				catch (customException $e)
				{
					customException::cleanUp();
				}
				$post['post_content'] = $this->bbcode->parse4bbcode($post['post_content']);
				$this->cleaner = $this->newObject('htmlcleaner', 'utilities');

				//set up the trackback link
				$blog_name = $this->objUser->fullname($userid);
				$url = $this->uri(array('action' => 'randblog', 'userid' => $userid, 'module' => 'blog'));
				$trackback_url = $this->uri(array('action' => 'tbreceive', 'userid' => $post['userid'], 'module' => 'blog', 'postid' => $post['id']));
				$extra = NULL;

				$tbdata = array('id' => $post['id'], 'title' => $post['post_title'], 'excerpt' => $post['post_excerpt'], 'blog_name' => $blog_name, 'url' => $url, 'trackback_url' => $trackback_url, 'extra' => $extra);
				$this->objTB->setup($tbdata, $tboptions);

				$linktxt = $this->objLanguage->languageText("mod_blog_word_trackback", "blog");
				$tburl = new href($trackback_url, $linktxt, NULL);
				$tburl = $tburl->show();

				$bmurl = $this->uri(array('action' => 'viewsingle', 'userid' => $post['userid'], 'module' => 'blog', 'postid' => $post['id']));
				$bmurl = urlencode($bmurl);
				$bmlink = "http://www.addthis.com/bookmark.php?pub=&amp;url=".$bmurl."&amp;title=".urlencode(addslashes(htmlentities($post['post_title'])));
				$bmtext = '<img src="http://www.addme.com/images/button1-bm.gif" width="125" height="16" border="0" alt="'.$this->objLanguage->languageText("mod_blog_bookmarkpost", "blog").'"/>'; //$this->objLanguage->languageText("mod_blog_bookmarkpost", "blog");
				$bookmark = new href($bmlink,$bmtext, NULL);

				//grab the number of trackbacks per post
				$pid = $post['id'];
				$numtb = $this->objDbBlog->getTrackbacksPerPost($pid);
				if($numtb != 0)
				{
					$numtblnk = new href($this->uri(array('module' => 'blog', 'action' => 'viewsingle', 'mode' => 'viewtb', 'postid' => $pid, 'userid' => $post['userid'])), $numtb, NULL);
					$numtb = $numtblnk->show();
				}

				//do the cc licence part
				$cclic = $post['post_lic'];
				$this->objCC = $this->getObject('dbcreativecommons', 'creativecommons');
				$lics = $this->objCC->getAll();
				//get the lic that matches from the db
				$objIcon = $this->newObject('geticon', 'htmlelements');
				$iconList = '';
				foreach($lics as $lic)
				{
					if($cclic == $lic['code'])
					{
						$icons = explode(',', $lic['images']);
						foreach ($icons as $icon)
		    			{
							$objIcon->setIcon ($icon, NULL, 'icons/creativecommons');
							$iconList .= $objIcon->show();
		    			}
						//continue;
					}
					elseif($cclic == '') {
						$cclic = 'copyright';
						$icons = explode(',', $lic['images']);
						foreach ($icons as $icon)
		    			{
							$objIcon->setIcon ($icon, NULL, 'icons/creativecommons');
							$iconList .= $objIcon->show();
		    			}
						//continue;
					}
				}

				//edit icon in a table 1 row x however number of things to do
				if($post['userid'] == $userid)
				{

					$this->objIcon = &$this->getObject('geticon', 'htmlelements');
					$edIcon = $this->objIcon->getEditIcon($this->uri(array('action' => 'postedit', 'id' => $post['id'], 'module' => 'blog')));

					$commentLink = $this->objComments->addCommentLink($type = NULL);

					//Set the table name
					$tbl = $this->newObject('htmltable', 'htmlelements');
					$tbl->cellpadding = 3;
					$tbl->width = "100%";
					$tbl->align = "center";

					//set up the header row
					$tbl->startHeaderRow();
					$tbl->addHeaderCell($this->objLanguage->languageText("mod_blog_editpost", "blog")); //edit
					$tbl->addHeaderCell($this->objLanguage->languageText("mod_blog_bookmarkpost", "blog")); //bookmark
					$tbl->addHeaderCell($this->objLanguage->languageText("mod_blog_leavecomment", "blog")); //comments
					$tbl->addHeaderCell($this->objLanguage->languageText("mod_blog_trackbackurl", "blog")); //trackback
					$tbl->addHeaderCell($this->objLanguage->languageText("mod_blog_cclic", "blog")); //Licence
					$tbl->endHeaderRow();
					$tbl->startRow();
					$tbl->addCell($edIcon); //edit icon
					$tbl->addCell($bookmark->show()); //bookmark link(s)
					$tbl->addCell($commentLink); //comment link(s)
					$tbl->addCell($tburl . " (" . $numtb . ")"); //trackback URL
					$tbl->addCell($iconList); //cc licence
					$tbl->addCell('');
					$tbl->endRow();
					echo $this->objTB->autodiscCode();

					$ret .= $objFeatureBox->show($head, $this->cleaner->cleanHtml($post['post_content'] . "<hr />" . "<center>" . $tbl->show() . "</center>"));
				}
				else {
					//table of non logged in options
					//Set the table name
					$tblnl = $this->newObject('htmltable', 'htmlelements');
					$tblnl->cellpadding = 3;
					$tblnl->width = "100%";
					$tblnl->align = "center";

					//set up the header row
					$tblnl->startHeaderRow();
					$tblnl->addHeaderCell($this->objLanguage->languageText("mod_blog_bookmarkpost", "blog")); //bookmark
					$tblnl->addHeaderCell($this->objLanguage->languageText("mod_blog_trackbackurl", "blog")); //trackback
					$tblnl->addHeaderCell($this->objLanguage->languageText("mod_blog_cclic", "blog")); //Licence
					$tblnl->endHeaderRow();
					$tblnl->startRow();
					$tblnl->addCell($bookmark->show()); //bookmark link(s)
					$tblnl->addCell($tburl . " (" . $numtb . ")"); //trackback URL
					$tblnl->addCell($iconList); //cc licence
					$tblnl->endRow();
					echo $this->objTB->autodiscCode();

					$ret .= $objFeatureBox->show($head, $this->cleaner->cleanHtml($post['post_content']) . "<center>" . $tblnl->show() . "</center>");
				}


			}
		}
		else {
			$ret = "<h1><em><center>" . $this->objLanguage->languageText("mod_blog_noposts", "blog") . "</center></em></h1>";
		}
		return $ret;
	}

	/**
	 * Method to build and create the feeds options box
	 *
	 * @param integer $userid
	 * @param bool $featurebox
	 * @return string
	 */
	public function showFeeds($userid, $featurebox = FALSE)
	{
		$this->loadClass('dropdown','htmlelements');
		$dropdown =& new dropdown('feedselector');

		$rss2 = $this->objLanguage->languageText("mod_blog_word_rss2", "blog");
		$dropdown->addOption('rss2',$rss2);

		$rss091 = $this->objLanguage->languageText("mod_blog_word_rss091", "blog");
		$dropdown->addOption('rss091',$rss091);

		$rss1 = $this->objLanguage->languageText("mod_blog_word_rss1", "blog");
		$dropdown->addOption('rss1',$rss1);

		$pie = $this->objLanguage->languageText("mod_blog_word_pie", "blog");
		$dropdown->addOption('pie',$pie);

		$mbox = $this->objLanguage->languageText("mod_blog_word_mbox", "blog");
		$dropdown->addOption('mbox',$mbox);

		$opml = $this->objLanguage->languageText("mod_blog_word_opml", "blog");
		$dropdown->addOption('opml',$opml);

		$atom = $this->objLanguage->languageText("mod_blog_word_atom", "blog");
		$dropdown->addOption('atom',$atom);

		$html = $this->objLanguage->languageText("mod_blog_word_html", "blog");
		$dropdown->addOption('html',$html);

		$this->loadClass('button','htmlelements');

		$this->objButton = &new button($this->objLanguage->languageText('word_show', 'blog'));
		$this->objButton->setValue($this->objLanguage->languageText('word_show', 'blog'));
		$this->objButton->setToSubmit();

		$this->objUser = $this->getObject('user', 'security');
		$leftCol = NULL;
		if($featurebox == FALSE)
		{
			$leftCol .= "<em>" . $this->objLanguage->languageText("mod_blog_feedheader", "blog") . "</em><br />";
		}

		$leftCol .= $dropdown->show() . "<br />";
		$leftCol .= $this->objButton->show() . "<br />";

		if($featurebox == FALSE)
		{
			return $leftCol;
		}
		else {

			$objFeatureBox = $this->getObject('featurebox', 'navigation');

			$ret = $objFeatureBox->show($this->objLanguage->languageText("mod_blog_feedheader",'blog' ), $leftCol);
			//creating form
			$form = new form('formname', $this->uri(array('action' => 'feed', 'userid'=>$userid)));
			$form->addToForm($ret);

			return $form->show();
		}
	}

	/**
	 * Method to quickly add a category to the default category (parent = 0)
	 * Can take a comma delimited list as an input arg
	 *
	 * @param bool $featurebox
	 * @return string
	 */
	public function quickCats($featurebox = FALSE)
	{
		$qcatform = new form('qcatadd', $this->uri(array('action' => 'catadd', 'mode' => 'quickadd')));

		$qcatform->addRule('catname',$this->objLanguage->languageText("mod_blog_phrase_titlereq", "blog"),'required');
		$qcatname = new textinput('catname');
		$qcatname->size = 15;
		$qcatform->addToForm($qcatname->show());
		$this->objqCButton = &new button($this->objLanguage->languageText('word_update', 'system'));
		$this->objqCButton->setValue($this->objLanguage->languageText('word_update', 'system'));
		$this->objqCButton->setToSubmit();
		$qcatform->addToForm($this->objqCButton->show());
		$qcatform = $qcatform->show();
		if($featurebox == FALSE)
		{
			return $qcatform;
		}
		else {
			$objFeatureBox = $this->getObject('featurebox', 'navigation');
			$ret = $objFeatureBox->show($this->objLanguage->languageText("mod_blog_qcatdetails","blog"),
			$this->objLanguage->languageText("mod_blog_quickaddcat", "blog") . "<br />" . $qcatform);
			return $ret;
		}
	}

	/**
	 * Method to insert the quick add categories to the db
	 *
	 * @param string $list
	 * @param integer $userid
	 * @return void
	 */
	public function quickCatAdd($list = NULL, $userid)
	{
		$list = explode(",", $list);
		foreach($list as $items)
		{
			//echo $items;
			$insarr = array('userid' => $userid, 'cat_name' => $items, 'cat_nicename' => $items, 'cat_desc' => '', 'cat_parent' => 0);
			$this->objDbBlog->setCats($userid, $insarr);
		}

	}

	/**
	 * Method to quick add a post to the posts table
	 *
	 * @param integer $userid
	 * @param array $postarr
	 * @param string $mode
	 */
	public function quickPostAdd($userid, $postarr, $mode = NULL)
	{
		if(!empty($postarr))
		{
			if($mode == NULL)
			{
				$this->objDbBlog->insertPost($userid, $postarr);

			}
			else {
				$this->objDbBlog->insertPost($userid, $postarr, $mode);
			}
		}

	}

	/**
	 * Method to scrub grubby html
	 *
	 * @param string $document
	 * @return string
	 */
	function html2txt($document, $scrub = TRUE)
	{
		if($scrub == TRUE)
		{
			$search = array('@<script[^>]*?>.*?</script>@si',  // Strip out javascript
            	   '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
               	   /*'@<style[^>]*?>.*?</style>@siU',*/    // Strip style tags properly
               	   '@<![\s\S]*?--[ \t\n\r]*>@'        // Strip multi-line comments including CDATA
				   );
		}
		else {
			$search = array('@<script[^>]*?>.*?</script>@si',  // Strip out javascript
            	   '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
               	   /*'@<style[^>]*?>.*?</style>@siU',*/    // Strip style tags properly
               	   '@<![\s\S]*?--[ \t\n\r]*>@'        // Strip multi-line comments including CDATA
				   );
		}
		$text = preg_replace($search, '', $document);
		//$text = str_replace("<br /><br />","",$text);
		$text = str_replace("<br />","\n",$text);
		$text = str_replace("<br\">","\n",$text);
		return $text;
	}

	/**
	 * Method to build and display the full scale category editor
	 *
	 * @param integer $userid
	 * @return string
	 */
	public function categoryEditor($userid)
	{
		//get the categories layout sorted
		$this->loadClass('href', 'htmlelements');
		$this->loadClass('label', 'htmlelements');
		$this->loadClass('textinput', 'htmlelements');
		$cats = $this->objDbBlog->getAllCats($userid);
		$headstr = $this->objLanguage->languageText("mod_blog_catedit_instructions", "blog");
		$totcount = $this->objDbBlog->catCount(NULL);

		//create a table to view the categories
		$cattable = $this->newObject('htmltable', 'htmlelements');
		$cattable->cellpadding = 3;
		//set up the header row
		$cattable->startHeaderRow();
		$cattable->addHeaderCell($this->objLanguage->languageText("mod_blog_cathead_parent", "blog"));
		//$cattable->addHeaderCell($this->objLanguage->languageText("mod_blog_cathead_name", "blog"));
		$cattable->addHeaderCell($this->objLanguage->languageText("mod_blog_cathead_nicename", "blog"));
		$cattable->addHeaderCell($this->objLanguage->languageText("mod_blog_cathead_descrip", "blog"));
		$cattable->addHeaderCell($this->objLanguage->languageText("mod_blog_cathead_count", "blog"));
		$cattable->addHeaderCell($this->objLanguage->languageText("mod_blog_editdeletecat", "blog"));
		$cattable->endHeaderRow();
		if(!empty($cats))
		{
			foreach($cats as $rows)
			{
				//print_r($rows);
				//start the cats rows
				$cattable->startRow();
				if($rows['cat_parent'] != '0')
				{
					$maparr = $this->objDbBlog->mapKid2Parent($rows['cat_parent']);
					if(!empty($maparr))
					{
						$rows['cat_parent'] = "<em><b>" . $maparr[0]['cat_name'] . "</b></em>";
					}
				}
				if($rows['cat_parent'] == '0')
				{
					$rows['cat_parent'] = "<em>" . $this->objLanguage->languageText("mod_blog_word_default", "blog") . "</em>";
				}
				$cattable->addCell($rows['cat_parent']);
				//$cattable->addCell($rows['cat_name']);
				$cattable->addCell($rows['cat_nicename']);
				$cattable->addCell($rows['cat_desc']);
				$cattable->addCell($this->objDbBlog->catCount($rows['id'])); //$rows['cat_count']);
				$this->objIcon = &$this->getObject('geticon', 'htmlelements');
				$edIcon = $this->objIcon->getEditIcon($this->uri(array('action' => 'catadd', 'mode' => 'edit', 'id' => $rows['id'], 'module' => 'blog')));
				$delIcon = $this->objIcon->getDeleteIconWithConfirm($rows['id'], array('module' => 'blog', 'action' => 'deletecat', 'id' => $rows['id']), 'blog');
				$cattable->addCell($edIcon . $delIcon);
				$cattable->endRow();
			}
			$ctable = $headstr . $cattable->show();
		}
		else {
			$ctable = $this->objLanguage->languageText("mod_blog_nocats", "blog");
		}

		//add a new category form:
		$catform = new form('catadd', $this->uri(array(
		'action' => 'catadd'
		)));

		$catform->addRule('catname',$this->objLanguage->languageText("mod_blog_phrase_titlereq", "blog"),'required');
		$cfieldset = $this->getObject('fieldset', 'htmlelements');
		$cfieldset->setLegend($this->objLanguage->languageText('mod_blog_catdetails', 'blog'));
		$catadd = $this->newObject('htmltable', 'htmlelements');
		$catadd->cellpadding = 5;
		//category name field
		$catadd->startRow();
		$clabel = new label($this->objLanguage->languageText('mod_blog_catname', 'blog') .':', 'input_catname');
		$catname = new textinput('catname');
		$catadd->addCell($clabel->show());
		$catadd->addCell($catname->show());
		$catadd->endRow();

		$catadd->startRow();
		$dlabel = new label($this->objLanguage->languageText('mod_blog_catparent', 'blog') .':', 'input_catparent');
		//category parent field (dropdown)
		//get a list of the parent cats
		$pcats = $this->objDbBlog->getAllCats($userid);
		$addDrop = new dropdown('catparent');
		$addDrop->addOption(0, $this->objLanguage->languageText("mod_blog_defcat","blog"));

		//loop through the existing cats and make sure not to add a child to the dd
		foreach($pcats as $adds)
		{
			$parent = $adds['cat_parent'];
			if($adds['cat_parent'] == '0')
			{
				$addDrop->addOption($adds['id'], $adds['cat_name']);
			}
		}
		$catadd->addCell($dlabel->show());
		$catadd->addCell($addDrop->show());
		$catadd->endRow();

		//start a htmlarea for the category description (optional)
		$catadd->startRow();
		$desclabel = new label($this->objLanguage->languageText('mod_blog_catdesc', 'blog') .':', 'input_catdesc');
		$this->loadClass('textarea', 'htmlelements');
		$cdesc = new textarea; //$this->newObject('textarea','htmlelements');
		$cdesc->setName('catdesc');
		//$cdesc->setBasicToolBar();

		$catadd->addCell($desclabel->show());
		$catadd->addCell($cdesc->show()); //showFCKEditor());
		$catadd->endRow();

		$catform->addRule('catname', $this->objLanguage->languageText("mod_blog_phrase_titlereq", "blog"),'required');


		$cfieldset->addContent($catadd->show());
		$catform->addToForm($cfieldset->show());

		$this->objCButton = &new button($this->objLanguage->languageText('word_update', 'system'));
		$this->objCButton->setValue($this->objLanguage->languageText('word_update', 'system'));
		$this->objCButton->setToSubmit();
		$catform->addToForm($this->objCButton->show());
		$catform = $catform->show();

		return $ctable . "<br />" . $catform;
	}

	public function catedit($catarr, $userid, $catid)
	{
		//add a new category form:
		$catform = new form('catadd', $this->uri(array(
		'action' => 'catadd', 'mode' => 'editcommit', 'id' => $catid
		)));

		$cfieldset = $this->getObject('fieldset', 'htmlelements');
		$cfieldset->setLegend($this->objLanguage->languageText('mod_blog_cateditor', 'blog'));
		$catadd = $this->newObject('htmltable', 'htmlelements');
		$catadd->cellpadding = 5;
		//category name field
		$catadd->startRow();
		$clabel = new label($this->objLanguage->languageText('mod_blog_catname', 'blog') .':', 'input_catname');
		$catname = new textinput('catname');
		$catname->setValue($catarr['cat_name']);
		$catadd->addCell($clabel->show());
		$catadd->addCell($catname->show());
		$catadd->endRow();

		$catadd->startRow();
		$dlabel = new label($this->objLanguage->languageText('mod_blog_catparent', 'blog') .':', 'input_catparent');
		//category parent field (dropdown)
		//get a list of the parent cats
		$pcats = $this->objDbBlog->getAllCats($userid);
		$addDrop = new dropdown('catparent');
		$addDrop->addOption(0, $this->objLanguage->languageText("mod_blog_defcat","blog"));

		//loop through the existing cats and make sure not to add a child to the dd
		foreach($pcats as $adds)
		{
			$parent = $adds['cat_parent'];
			if($adds['cat_parent'] == '0')
			{
				$addDrop->addOption($adds['id'], $adds['cat_name']);
			}
		}
		$catadd->addCell($dlabel->show());
		$catadd->addCell($addDrop->show());
		$catadd->endRow();

		//start a htmlarea for the category description (optional)
		$catadd->startRow();
		$desclabel = new label($this->objLanguage->languageText('mod_blog_catdesc', 'blog') .':', 'input_catdesc');
		$this->loadClass('textarea', 'htmlelements');
		$cdesc = new textarea; //$this->newObject('textarea','htmlelements');
		$cdesc->setName('catdesc');
		$cdesc->setContent($catarr['cat_desc']);
		//$cdesc->setBasicToolBar();

		$catadd->addCell($desclabel->show());
		$catadd->addCell($cdesc->show()); //showFCKEditor());
		$catadd->endRow();

		$cfieldset->addContent($catadd->show());
		$catform->addToForm($cfieldset->show());
		$this->objCButton = &new button($this->objLanguage->languageText('word_update', 'system'));
		$this->objCButton->setValue($this->objLanguage->languageText('word_update', 'system'));
		$this->objCButton->setToSubmit();
		$catform->addToForm($this->objCButton->show());
		$catform = $catform->show();
		return $catform;

	}

	/**
	 * Method to display the posts editor in its entirety
	 *
	 * @param integer $userid
	 * @param integer $editid
	 * @return boolean
	 */
	public function postEditor($userid, $editid = NULL)
	{
		if(isset($editid))
		{
			$mode = 'editpost';
			//get the relevant post from the editid
			$editparams = $this->objDbBlog->getPostById($editid);
			$editparams = $editparams[0];
		}
		if(!isset($mode))
		{
			$mode = NULL;
		}
		if(!isset($editparams))
		{
			$editparams = NULL;
		}

		$postform = new form('postadd', $this->uri(array(
		'action' => 'postadd', 'mode' => $mode, 'id' => $editparams['id'], 'postexcerpt' => $editparams['post_excerpt'], 'postdate' => $editparams['post_date']
		)));

		$pfieldset = $this->newObject('fieldset', 'htmlelements');
		$pfieldset->setLegend($this->objLanguage->languageText('mod_blog_posthead', 'blog'));
		$ptable = $this->newObject('htmltable', 'htmlelements');
		$ptable->cellpadding = 5;

		//post title field
		$ptable->startRow();
		$plabel = new label($this->objLanguage->languageText('mod_blog_posttitle', 'blog') .':', 'input_posttitle');
		$title = new textinput('posttitle');
		$title->size = 150;

		$postform->addRule('posttitle', $this->objLanguage->languageText("mod_blog_phrase_ptitlereq", "blog"),'required');
		if(isset($editparams['post_title']))
		{

			$title->setValue($editparams['post_title']);
		}
		$ptable->addCell($plabel->show());
		$ptable->addCell($title->show());
		$ptable->endRow();

		//post category field
		//dropdown of cats
		$ptable->startRow();
		$pdlabel = new label($this->objLanguage->languageText('mod_blog_postcat', 'blog') .':', 'input_postcatfull');
		$pDrop = new dropdown('cat');
		if(isset($editparams['post_category']))
		{
			$pDrop->addOption($editparams['post_category'], $editparams['post_category']);
		}
		$pDrop->addOption(0, $this->objLanguage->languageText("mod_blog_defcat","blog"));
		$pcats = $this->objDbBlog->getAllCats($userid);
		foreach($pcats as $adds)
		{
			$pDrop->addOption($adds['id'], $adds['cat_name']);
		}
		$ptable->addCell($pdlabel->show());
		$ptable->addCell($pDrop->show());
		$ptable->endRow();

		//post status dropdown
		$ptable->startRow();
		$pslabel = new label($this->objLanguage->languageText('mod_blog_poststatus', 'blog') .':', 'input_poststatfull');
		$psDrop = new dropdown('status');
		$psDrop->addOption(0, $this->objLanguage->languageText("mod_blog_published","blog"));
		$psDrop->addOption(1, $this->objLanguage->languageText("mod_blog_draft","blog"));
		//$psDrop->addOption(2, $this->objLanguage->languageText("mod_blog_hidden","blog"));
		$ptable->addCell($pslabel->show());
		$ptable->addCell($psDrop->show());
		$ptable->endRow();

		//allow comments?
		$this->loadClass("checkbox", "htmlelements");
		$commentsallowed = new checkbox('commentsallowed',$this->objLanguage->languageText("mod_blog_word_yes", "blog"),true);
		$ptable->startRow();
		$pcomlabel = new label($this->objLanguage->languageText('mod_blog_commentsallowed', 'blog') .':', 'input_commentsallowedfull');
		$ptable->addCell($pcomlabel->show());
		$ptable->addCell($commentsallowed->show());
		$ptable->endRow();

		//post excerpt
		$this->loadClass('textarea', 'htmlelements');
		$pexcerpt = new textarea;
		$pexcerpt->setName('postexcerpt');
		$ptable->startRow();
		$pexcerptlabel = new label($this->objLanguage->languageText('mod_blog_postexcerpt', 'blog') .':', 'input_postexcerpt');
		if(isset($editparams['post_excerpt']))
		{
			$pexcerpt->setcontent(nl2br($editparams['post_excerpt']));
		}
		$ptable->addCell($pexcerptlabel->show());
		$ptable->addCell($pexcerpt->show());
		$ptable->endRow();

		//post content
		$pclabel = new label($this->objLanguage->languageText('mod_blog_pcontent', 'blog') .':', 'input_pcont');
		$pcon = $this->newObject('htmlarea','htmlelements');
		$pcon->setName('postcontent');

		if(isset($editparams['post_content']))
		{
			$pcon->setcontent(nl2br($editparams['post_content']));
		}
		$ptable->startRow();
		$ptable->addCell($pclabel->show());
		$ptable->addCell($pcon->showFCKEditor());
		$ptable->endRow();

		//CC licence
		$lic = $this->getObject('licensechooser', 'creativecommons');
		$ptable->startRow();
		$pcclabel = new label($this->objLanguage->languageText('mod_blog_cclic', 'blog') .':', 'input_cclic');
		$ptable->addCell($pcclabel->show());
		if(isset($editparams['post_lic']))
		{
			$lic->defaultValue = $editparams['post_lic'];
		}
		$ptable->addCell($lic->show());
		$ptable->endRow();

		$postform->addRule('posttitle', $this->objLanguage->languageText("mod_blog_phrase_ptitlereq", "blog"),'required');
		//$postform->addRule('postcontent', $this->objLanguage->languageText("mod_blog_phrase_pcontreq", "blog"),'required');


		$pfieldset->addContent($ptable->show());
		$postform->addToForm($pfieldset->show());
		$this->objPButton = &new button($this->objLanguage->languageText('mod_blog_word_post', 'blog'));
		$this->objPButton->setValue($this->objLanguage->languageText('mod_blog_word_post', 'blog'));
		$this->objPButton->setToSubmit();
		$postform->addToForm($this->objPButton->show());
		$postform = $postform->show();

		return $postform;
	}

	private function _archiveArr($userid)
	{
		//add in a foreach for each year
		$allposts = $this->objDbBlog->getAbsAllPosts($userid);
		$revposts = array_reverse($allposts);
		$recs = count($revposts);
		if($recs > 0)
		{
			$recs = $recs - 1;
		}
		if(!empty($revposts))
		{

			$lastrec = $revposts[$recs]['post_ts'];
			$firstrec = $revposts[0]['post_ts'];

			$startdate = date("m", $firstrec);
			$enddate  = date("m", $lastrec);//. " " .date("Y", $lastrec);

			//create a while loop to get all the posts between start and end dates
			$postarr = array();
			while ($startdate <= $enddate) {
				$posts = $this->objDbBlog->getPostsMonthly(mktime(0,0,0,$startdate, 1, date("y", $firstrec)), $userid);
				$postarr[$startdate] = $posts;
				$startdate++;
			}
			return $postarr;
		}
		else {
			return NULL;
		}

	}
	public function archiveBox($userid, $featurebox = FALSE)
	{
		//get the posts for each month
		$posts = $this->_archiveArr($userid);
		//print_r($posts);die();
		if(!empty($posts))
		{
			$months = array_keys($posts);
			//print_r($posts);die();
			$arks = NULL;
			foreach ($months as $month)
			{
				$thedate = mktime(0,0,0,$month,1,date("Y", $posts[$month][0]['post_ts']));
				$arks[] = array('formatted' => date("F", $thedate) . " " . date("Y", $thedate), 'raw' => $month, 'rfc' => $thedate);
			}

		//print_r($arks);die();

			$thismonth = mktime(0,0,0,date("m", time()), 1, date("y", time()));
			if($featurebox == FALSE)
			{
				return $thismonth;
			}
			else {
				$objFeatureBox = $this->getObject('featurebox', 'navigation');
				$lnks = NULL;
				foreach ($arks as $ark)
				{
					$lnk = new href($this->uri(array('module' => 'blog', 'action' => 'showarchives', 'month' => $ark['raw'], 'year' => $ark['rfc'], 'userid' => $posts[$month][0]['userid'])), $ark['formatted']);
					$lnks .= $lnk->show() . "<br />";
				}

				$ret = $objFeatureBox->show($this->objLanguage->languageText("mod_blog_archives","blog"),$lnks);
				return $ret;
			}
		}
		else {
			return NULL;
		}
	}

	/**
	 * Method to edit and manage posts
	 *
	 * @param integer $userid
	 * @return string
	 */
	public function managePosts($userid, $month = NULL, $year = NULL)
	{
		//create a table with the months posts, plus a dropdown of all months to edit
		//put the edit icon at the end of each row, with text linked to the postEditor() method
		//create an array with keys: cat, excerpt, title, content, catid for edit
		//start the edit table
		$editform = new form('postedit', $this->uri(array(
		'action' => 'postedit'
		)));

		//$edfieldset = $this->newObject('fieldset', 'htmlelements');
		//$edfieldset->setLegend($this->objLanguage->languageText('mod_blog_posthead', 'blog'));
		$edtable = $this->newObject('htmltable', 'htmlelements');
		$edtable->cellpadding = 5;

		//grab the posts for this month
		//$posts = $this->objDbBlog->getPostsMonthly(mktime(0,0,0,date("m", time()), 1, date("y", time())), $userid); //change this to get from the form input rather
		if($month == NULL && $year == NULL)
		{
			$posts = $this->objDbBlog->getAbsAllPosts($userid);
		}
		//print_r($posts);
		//add in a table header...
		$edtable->startHeaderRow();
        $edtable->addHeaderCell($this->objLanguage->languageText("mod_blog_posttitle", "blog"));
        $edtable->addHeaderCell($this->objLanguage->languageText("mod_blog_postdate", "blog"));
        $edtable->addHeaderCell($this->objLanguage->languageText("mod_blog_poststatus", "blog"));
        $edtable->addHeaderCell($this->objLanguage->languageText("mod_blog_postcat", "blog"));
        $edtable->addHeaderCell($this->objLanguage->languageText("mod_blog_editdelete", "blog"));
        $edtable->endHeaderRow();
		foreach($posts as $post)
		{
			$edtable->startRow();
			$edtable->addCell($post['post_title']);
			$edtable->addCell(date('r',$post['post_ts']));
			//do some voodoo on the post status, so that it looks better
			switch ($post['post_status'])
			{
				case '0':
					$post['post_status'] = $this->objLanguage->languageText("mod_blog_published", "blog");
					break;
				case '1':
					$post['post_status'] = $this->objLanguage->languageText("mod_blog_draft", "blog");
					break;
				case '2':
					$post['post_status'] = $this->objLanguage->languageText("mod_blog_hidden", "blog");
					break;
			}
			$edtable->addCell($post['post_status']);
			//category voodoo
			if($post['post_category'] == '0')
			{
				$post['post_category'] = $this->objLanguage->languageText("mod_blog_word_default", "blog");
			}
			else {
				$mapcats = $this->objDbBlog->mapKid2Parent($post['post_category']);
				$post['post_category'] = $mapcats[0]['cat_name'];
			}
			$edtable->addCell($post['post_category']);
			//do the edit and delete icon
			$this->objIcon = &$this->getObject('geticon', 'htmlelements');
			$edIcon = $this->objIcon->getEditIcon($this->uri(array('action' => 'postedit', 'id' => $post['id'], 'module' => 'blog')));
			$delIcon = $this->objIcon->getDeleteIconWithConfirm($post['id'], array('module' => 'blog', 'action' => 'deletepost', 'id' => $post['id']), 'blog');
			$edtable->addCell($edIcon . $delIcon);
			$edtable->endRow();
		}

		return $edtable->show();
	}

	/**
	 * Method to add a quick post as a blocklet
	 *
	 * @param integer $userid
	 * @param bool $featurebox
	 * @return mixed
	 */
	public function quickPost($userid, $featurebox = FALSE)
	{
		//form for the quick poster blocklet
		$this->loadClass('textarea', 'htmlelements');
		$this->loadClass('textinput', 'htmlelements');
		$qpform = new form('qpadd', $this->uri(array('action' => 'postadd', 'mode' => 'quickadd')));


		$qpform->addRule('postcontent',$this->objLanguage->languageText("mod_blog_phrase_pcontreq", "blog"),'required');

		$qptitletxt = $this->objLanguage->languageText("mod_blog_posttitle", "blog") . "<br />";
		$qptitle = new textinput('posttitle');
		//post content textarea
		$qpcontenttxt = $this->objLanguage->languageText("mod_blog_pcontent", "blog");
		$qpcontent = new textarea;
		$qpcontent->setName('postcontent');
		//$qpcontent->setBasicToolBar();
		//dropdown of cats
		$qpcattxt = $this->objLanguage->languageText("mod_blog_postcat", "blog");
		$qpDrop = new dropdown('cat');
		$qpDrop->addOption(0, $this->objLanguage->languageText("mod_blog_defcat","blog"));
		//loop through the existing cats and make sure not to add a child to the dd
		$pcats = $this->objDbBlog->getAllCats($userid);
		foreach($pcats as $adds)
		{
			$qpDrop->addOption($adds['id'], $adds['cat_name']);
		}

		//set up the form elements so they fit nicely in a box
		$qptitle->size = 15;
		$qpcontent->cols = 15;
		$qpcontent->rows = 5;

		$qpform->addToForm($qptitletxt . $qptitle->show());
		$qpform->addToForm("<br />");
		$qpform->addToForm($qpcontenttxt . $qpcontent->show());
		$qpform->addToForm("<br />");
		$qpform->addToForm($qpcattxt . $qpDrop->show());

		$this->objqpCButton = &new button($this->objLanguage->languageText('mod_blog_word_blogit', 'blog'));
		$this->objqpCButton->setValue($this->objLanguage->languageText('mod_blog_word_blogit', 'blog'));
		$this->objqpCButton->setToSubmit();
		$qpform->addToForm($this->objqpCButton->show());
		$qpform = $qpform->show();
		if($featurebox == FALSE)
		{
			return $qpform;
		}
		else {
			$objFeatureBox = $this->getObject('featurebox', 'navigation');
			$ret = $objFeatureBox->show($this->objLanguage->languageText("mod_blog_qpdetails","blog"),
			$this->objLanguage->languageText("mod_blog_quickaddpost", "blog") . "<br />" . $qpform);
			return $ret;
		}

	}

	public function editPost()
	{

	}

	/**
	 * Methid to build a table of all available bloggers on the system
	 *
	 * @param array $rec
	 * @return string
	 */
	public function buildBloggertable($rec)
	{
		$lastentry = $this->objDbBlog->getLatestPost($rec['id']);
		$link = new href($this->uri(array('action' => 'randblog', 'userid' => $rec['id'])),$lastentry['post_title']);
		$this->cleaner = $this->newObject('htmlcleaner', 'utilities');
		$txt = $lastentry['post_excerpt'];
		$txtlen = 100;
		$str_to_count = $txt; // html_entity_decode($txt);
  		if (strlen($str_to_count) <= $txtlen) {
   			$txt = $this->cleaner->cleanHtml($txt);
  		}
  		else {
  			$txt = substr($str_to_count, 0, $txtlen - 3);
  			$txt .= $txt."...";
  			$txt = $this->cleaner->cleanHtml($txt);
  		}

		$lastpost = $link->show() . "<br />" . $txt;

		$stable = $this->newObject('htmltable', 'htmlelements');
		$stable->cellpadding = 2;
		//set up the header row
		$stable->startHeaderRow();
		$stable->addHeaderCell(''); //$this->objLanguage->languageText("mod_blog_blogger", "blog") . ":");
		$stable->addHeaderCell(''); //"<em>" . $rec['name'] . "</em>");
		$stable->endHeaderRow();

		$stable->startRow();
		$stable->addCell($rec['img']);
		$stable->addCell($this->objLanguage->languageText("mod_blog_lastseen", "blog") . " : " . $rec['laston'] . "<br />" . $lastpost);
		$stable->endRow();

		$objFeatureBox = $this->newObject('featurebox', 'navigation');
		$ret = $objFeatureBox->show($this->objLanguage->languageText("mod_blog_blogger", "blog") . " : " . "<em>" . $rec['name'] . "</em>", $stable->show());

		return $ret;
	}

	/**
	 * Date manipulation method for getting posts by month/date
	 *
	 * @param mixed selected date $sel_date
	 * @return array
	 */
	public function retDates($sel_date = NULL)
	{
		if($sel_date == NULL)
		{
			$sel_date = mktime(0,0,0,date("m", time()), 1, date("y", time()));
		}
		$t = getdate($sel_date);
		$start_date = mktime($t['hours'], $t['minutes'], $t['seconds'], $t['mon'], 1, $t['year']);
		$start_date -= 86400 * date('w', $start_date);

		$prev_year = mktime($t['hours'], $t['minutes'], $t['seconds'], $t['mon'], $t['mday'], $t['year'] - 1);
		$prev_month = mktime($t['hours'], $t['minutes'], $t['seconds'], $t['mon'] - 1, $t['mday'], $t['year']);
		$next_year = mktime($t['hours'], $t['minutes'], $t['seconds'], $t['mon'], $t['mday'], $t['year'] + 1);
		$next_month = mktime($t['hours'], $t['minutes'], $t['seconds'], $t['mon'] + 1, $t['mday'], $t['year']);

		return array('mbegin' => $sel_date, 'prevyear' => $prev_year, 'prevmonth' => $prev_month, 'nextyear' => $next_year, 'nextmonth' => $next_month);
	}

	/**
	 * Method to append config settings to the config.xml file
	 *
	 * @param array $newsettings
	 */
	public function setupConfig($newsettings)
	{
		$this->objConfig = $this->getObject('altconfig', 'config');
		$this->objConfig->appendToConfig($newsettings);
	}

	/**
	 * Method to parse the DSN
	 *
	 * @access public
	 * @param string $dsn
	 * @return void
	 */
	public function parseDSN($dsn)
	{
		$parsed = NULL; //$this->imapdsn;
		$arr = NULL;
		if (is_array($dsn)) {
			$dsn = array_merge($parsed, $dsn);
			return $dsn;
		}
		//find the protocol
		if (($pos = strpos($dsn, '://')) !== false) {
			$str = substr($dsn, 0, $pos);
			$dsn = substr($dsn, $pos + 3);
		} else {
			$str = $dsn;
			$dsn = null;
		}
		if (preg_match('|^(.+?)\((.*?)\)$|', $str, $arr)) {
			$parsed['protocol']  = $arr[1];
			$parsed['protocol'] = !$arr[2] ? $arr[1] : $arr[2];
		} else {
			$parsed['protocol']  = $str;
			$parsed['protocol'] = $str;
		}

		if (!count($dsn)) {
			return $parsed;
		}
		// Get (if found): username and password
		if (($at = strrpos($dsn,'@')) !== false) {
			$str = substr($dsn, 0, $at);
			$dsn = substr($dsn, $at + 1);
			if (($pos = strpos($str, ':')) !== false) {
				$parsed['user'] = rawurldecode(substr($str, 0, $pos));
				$parsed['pass'] = rawurldecode(substr($str, $pos + 1));
			} else {
				$parsed['user'] = rawurldecode($str);
			}
		}

		//server
		if (($col = strrpos($dsn,':')) !== false) {
			$strcol = substr($dsn, 0, $col);
			$dsn = substr($dsn, $col + 1);
			if (($pos = strpos($strcol, '/')) !== false) {
				$parsed['server'] = rawurldecode(substr($strcol, 0, $pos));
			} else {
				$parsed['server'] = rawurldecode($strcol);
			}
		}

		//now we are left with the port and mailbox so we can just explode the string and clobber the arrays together
		$pm = explode("/",$dsn);
		$parsed['port'] = $pm[0];
		$parsed['mailbox'] = $pm[1];
		$dsn = NULL;

		return $parsed;
	}

	public function getMailDSN()
	{
		//check that the variables are set, if not return the template, otherwise return a thank you and carry on
		$this->objConfig = $this->getObject('altconfig', 'config');
		$vals = $this->objConfig->getItem('BLOG_MAIL_DSN');
		if($vals != FALSE)
		{
			$dsnparse = $this->parseDSN($vals);
			return $dsnparse;

		}
		else {
			return FALSE;
		}
	}

	public function blogTagCloud($userid)
	{
		$this->objTC = $this->getObject('tagcloud', 'utilities');
		//get all the categories to convert to tags
		$catarr = $this->objDbBlog->getAllCats($userid);
		if(empty($catarr))
		{
			return NULL;
		}
		//print_r($catarr); die();
		foreach ($catarr as $cat)
		{
			//get the last post from the cat and do a count on the posts table
			$post = $this->objDbBlog->getLatestPost($userid);
			//print_r($post);
			$url = $this->uri(array('action' => 'viewblog', 'catid' => $cat['id'], 'userid' => $userid));
			//weight is the count of posts in the cat
			//echo $post['id'];
			$count = $this->objDbBlog->catCount($cat['id']);
			$tag = array('name' => $cat['cat_nicename'], 'url' => $url, 'weight' => $count, 'time' => $post['post_ts']);
			$ret[] = $tag;
		}
		//print_r($ret); die();
		return $this->objTC->buildCloud($ret);
	}

	public function showTrackbacks($pid)
	{
		$objFeatureBox = $this->newObject('featurebox', 'navigation');
		$tbs = $this->objDbBlog->grabTrackbacks($pid);
		//loop through the trackbacks and build a featurebox to show em
		if(empty($tbs))
		{
			//shouldn't happen except on permalinks....?
			return $objFeatureBox->show($this->objLanguage->languageText("mod_blog_trackback4post", "blog"), "<em>".$this->objLanguage->languageText("mod_blog_trackbacknotrackback", "blog")."</em>");
		}

		$tbtext = NULL;
		foreach($tbs as $tracks)
		{
			//build up the display
			$tbtable = $this->newObject('htmltable', 'htmlelements');
			$tbtable->cellpadding = 2;
			//$tbtable->width = '80%';
			//set up the header row
			$tbtable->startHeaderRow();
			$tbtable->addHeaderCell('');
			$tbtable->addHeaderCell('');
			$tbtable->endHeaderRow();

			//where did it come from?
			$whofromhost = $tracks['tburl'];
			$link = new href(htmlentities($whofromhost), htmlentities($whofromhost), NULL);
			$whofromhost = $link->show();
			$blogname = $tracks['blog_name'];
			//title and excerpt
			$title = $tracks['title'];
			$excerpt = $tracks['excerpt'];

			$tbtable->startRow();
			$tbtable->addCell($this->objLanguage->languageText("mod_blog_tbremhost", "blog"));
			$tbtable->addCell($whofromhost);
			$tbtable->endRow();

			$tbtable->startRow();
			$tbtable->addCell($this->objLanguage->languageText("mod_blog_tbblogname", "blog"));
			$tbtable->addCell($blogname);
			$tbtable->endRow();

			$tbtable->startRow();
			$tbtable->addCell($this->objLanguage->languageText("mod_blog_tbblogtitle", "blog"));
			$tbtable->addCell($title);
			$tbtable->endRow();

			$tbtable->startRow();
			$tbtable->addCell($this->objLanguage->languageText("mod_blog_tbblogexcerpt", "blog"));
			$tbtable->addCell($excerpt);
			$tbtable->endRow();

			$tbtext .= $tbtable->show();
			$tbtable = NULL;

		}

		$tbtext = $this->bbcode->parse4bbcode($tbtext);
		$this->bbcode = $this->getObject('bbcodeparser', 'utilities');
		$ret = $objFeatureBox->show($this->objLanguage->languageText("mod_blog_trackback4post", "blog"), $tbtext);
		return $ret;


	}


}
?>