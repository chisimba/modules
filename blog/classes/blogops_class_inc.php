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

	public function catAddForm()
	{

	}

	public function catDelForm()
	{

	}

	public function postDelForm()
	{

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
						$childnodes[] = array('text' => $kid['cat_nicename'], 'uri' => $this->uri(array('action' => 'showsection', 'id' => $kid['id']), 'blog'));
					}
				}
				$nodestoadd[] = array('text'=> $categories['cat_nicename'], 'uri' => $this->uri(array('action' => 'showsection',
				'id' => $categories['id']), 'blog'),
				'haschildren' => $childnodes);
				$childnodes = NULL;
				$nodes = NULL;
			}
			$ret .= $objSideBar->show($nodestoadd, NULL, NULL, 'blog');
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
					//$tips = $tt->show($lk['link_name'], $lk['link_description'], $lk['link_name'], $lk['link_url']);
					$alt = htmlentities($lk['link_description']);
					$link = new href(htmlentities($lk['link_url']), htmlentities($lk['link_name']), "alt='{$alt}'"); // . $tips;
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

	public function showAdminSection($featurebox = FALSE)
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
		$ret .= $admin->show();
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

	public function showPosts($posts)
	{
		$ret = NULL;
		//Middle column (posts)!
		//break out the ol featurebox...
		if(!empty($posts))
		{
			foreach($posts as $post)
			{
				$objFeatureBox = $this->getObject('featurebox', 'navigation');
				//build the top level stuff
				$dt = strtotime($post['post_date']);
				$dt = date('r', $dt);
				$head = $post['post_title'] . "<br />" . $dt;
				//dump in the post content and voila! you have it...
				//build the post content plus comment count and stats???
				$ret .= $objFeatureBox->show($head, $post['post_content']);
			}
		}
		else {
			$ret = "<h1><em><center>" . $this->objLanguage->languageText("mod_blog_noposts", "blog") . "</center></em></h1>";
		}
		return $ret;
	}

	public function showFeeds($userid, $featurebox = FALSE)
	{
		$this->objUser = $this->getObject('user', 'security');
		$leftCol = NULL;
		if($featurebox == FALSE)
		{
			$leftCol .= "<em>" . $this->objLanguage->languageText("mod_blog_feedheader", "blog") . "</em><br />";
		}
		//RSS2.0
		$rss2 = $this->getObject('geticon', 'htmlelements');
		$rss2->setIcon('rss', 'gif', 'icons/filetypes');
		$link = new href($this->uri(array('action' => 'feed', 'format' => 'rss2', 'userid' => $userid)),$this->objLanguage->languageText("mod_blog_word_rss2", "blog"));
		$leftCol .= $rss2->show() . $link->show() . "<br />";

		//RSS0.91
		$rss091 = $this->getObject('geticon', 'htmlelements');
		$rss091->setIcon('rss', 'gif', 'icons/filetypes');
		$link = new href($this->uri(array('action' => 'feed', 'format' => 'rss091', 'userid' => $userid)),$this->objLanguage->languageText("mod_blog_word_rss091", "blog"));
		$leftCol .= $rss091->show() . $link->show() . "<br />";

		//RSS1.0
		$rss1 = $this->getObject('geticon', 'htmlelements');
		$rss1->setIcon('rss', 'gif', 'icons/filetypes');
		$link = new href($this->uri(array('action' => 'feed', 'format' => 'rss1', 'userid' => $userid)),$this->objLanguage->languageText("mod_blog_word_rss1", "blog"));
		$leftCol .= $rss1->show() . $link->show() . "<br />";

		//PIE
		$pie = $this->getObject('geticon', 'htmlelements');
		$pie->setIcon('rss', 'gif', 'icons/filetypes');
		$link = new href($this->uri(array('action' => 'feed', 'format' => 'pie', 'userid' => $userid)),$this->objLanguage->languageText("mod_blog_word_pie", "blog"));
		$leftCol .= $pie->show() . $link->show() . "<br />";

		//MBOX
		$mbox = $this->getObject('geticon', 'htmlelements');
		$mbox->setIcon('rss', 'gif', 'icons/filetypes');
		$link = new href($this->uri(array('action' => 'feed', 'format' => 'mbox', 'userid' => $userid)),$this->objLanguage->languageText("mod_blog_word_mbox", "blog"));
		$leftCol .= $mbox->show() . $link->show() . "<br />";

		//OPML
		$opml = $this->getObject('geticon', 'htmlelements');
		$opml->setIcon('rss', 'gif', 'icons/filetypes');
		$link = new href($this->uri(array('action' => 'feed', 'format' => 'opml', 'userid' => $userid)),$this->objLanguage->languageText("mod_blog_word_opml", "blog"));
		$leftCol .= $opml->show() . $link->show() . "<br />";

		//ATOM
		$atom = $this->getObject('geticon', 'htmlelements');
		$atom->setIcon('rss', 'gif', 'icons/filetypes');
		$link = new href($this->uri(array('action' => 'feed', 'format' => 'atom', 'userid' => $userid)),$this->objLanguage->languageText("mod_blog_word_atom", "blog"));
		$leftCol .= $atom->show() . $link->show() . "<br />";

		//Plain HTML
		$html = $this->getObject('geticon', 'htmlelements');
		$html->setIcon('rss', 'gif', 'icons/filetypes');
		$link = new href($this->uri(array('action' => 'feed', 'format' => 'html', 'userid' => $userid)),$this->objLanguage->languageText("mod_blog_word_html", "blog"));
		$leftCol .= $html->show() . $link->show() . "<br />";

		if($featurebox == FALSE)
		{
			return $leftCol;
		}
		else {
			$objFeatureBox = $this->getObject('featurebox', 'navigation');
			$ret = $objFeatureBox->show($this->objLanguage->languageText("mod_blog_feedheader","blog"), $leftCol);
			return $ret;
		}


	}

	public function quickCats($featurebox = FALSE)
	{
		$qcatform = new form('qcatadd', $this->uri(array('action' => 'catadd', 'mode' => 'quickadd')));
		$qcatname = new textinput('catname');
		$qcatname->size = 15;
		$qcatform->addToForm($qcatname->show());
		$this->objqCButton = &new button($this->objLanguage->languageText('word_update', 'blog'));
		$this->objqCButton->setValue($this->objLanguage->languageText('word_update', 'blog'));
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

}
?>