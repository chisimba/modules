<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
	die("You cannot view this page directly");
}
// end security check

/**
 * Class to handle blog elements
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

	public function showBlogsLink()
	{
		//set up a link to the other users blogs...
		$oblogs = new href($this->uri(array('action' => 'allblogs')),$this->objLanguage->languageText("mod_blog_viewallblogs", "blog"), NULL);
		$ret = $oblogs->show();
		return $ret;
	}

	public function showCatsMenu($cats)
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
		return $ret;
	}

	public function showLinkCats($linkcats)
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
		return $ret;
	}

	public function showAdminSection()
	{
		//admin section
		$ret = "<em>" . $this->objLanguage->languageText("mod_blog_admin", "blog") . "</em><br />";
		//blog admin page
		$admin = new href($this->uri(array('action' => 'blogadmin')), $this->objLanguage->languageText("mod_blog_blogadmin", "blog"));
		$ret .= $admin->show();

		return $ret;
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

	public function showFeeds()
	{
		$leftCol = NULL;

		$leftCol .= "<em>" . $this->objLanguage->languageText("mod_blog_feedheader", "blog") . "</em><br />";
		//RSS2.0
		$rss2 = $this->getObject('geticon', 'htmlelements');
		$rss2->setIcon('rss', 'gif', 'icons/filetypes');
		$link = new href($this->uri(array('action' => 'feed', 'format' => 'rss2', 'userid' => $this->objUser->userid())),$this->objLanguage->languageText("mod_blog_word_rss2", "blog"));
		$leftCol .= $rss2->show() . $link->show() . "<br />";

		//RSS0.91
		$rss091 = $this->getObject('geticon', 'htmlelements');
		$rss091->setIcon('rss', 'gif', 'icons/filetypes');
		$link = new href($this->uri(array('action' => 'feed', 'format' => 'rss091', 'userid' => $this->objUser->userid())),$this->objLanguage->languageText("mod_blog_word_rss091", "blog"));
		$leftCol .= $rss091->show() . $link->show() . "<br />";

		//RSS1.0
		$rss1 = $this->getObject('geticon', 'htmlelements');
		$rss1->setIcon('rss', 'gif', 'icons/filetypes');
		$link = new href($this->uri(array('action' => 'feed', 'format' => 'rss1', 'userid' => $this->objUser->userid())),$this->objLanguage->languageText("mod_blog_word_rss1", "blog"));
		$leftCol .= $rss1->show() . $link->show() . "<br />";

		//PIE
		$pie = $this->getObject('geticon', 'htmlelements');
		$pie->setIcon('rss', 'gif', 'icons/filetypes');
		$link = new href($this->uri(array('action' => 'feed', 'format' => 'pie', 'userid' => $this->objUser->userid())),$this->objLanguage->languageText("mod_blog_word_pie", "blog"));
		$leftCol .= $pie->show() . $link->show() . "<br />";

		//MBOX
		$mbox = $this->getObject('geticon', 'htmlelements');
		$mbox->setIcon('rss', 'gif', 'icons/filetypes');
		$link = new href($this->uri(array('action' => 'feed', 'format' => 'mbox', 'userid' => $this->objUser->userid())),$this->objLanguage->languageText("mod_blog_word_mbox", "blog"));
		$leftCol .= $mbox->show() . $link->show() . "<br />";

		//OPML
		$opml = $this->getObject('geticon', 'htmlelements');
		$opml->setIcon('rss', 'gif', 'icons/filetypes');
		$link = new href($this->uri(array('action' => 'feed', 'format' => 'opml', 'userid' => $this->objUser->userid())),$this->objLanguage->languageText("mod_blog_word_opml", "blog"));
		$leftCol .= $opml->show() . $link->show() . "<br />";

		//ATOM
		$atom = $this->getObject('geticon', 'htmlelements');
		$atom->setIcon('rss', 'gif', 'icons/filetypes');
		$link = new href($this->uri(array('action' => 'feed', 'format' => 'atom', 'userid' => $this->objUser->userid())),$this->objLanguage->languageText("mod_blog_word_atom", "blog"));
		$leftCol .= $atom->show() . $link->show() . "<br />";

		//Plain HTML
		$html = $this->getObject('geticon', 'htmlelements');
		$html->setIcon('rss', 'gif', 'icons/filetypes');
		$link = new href($this->uri(array('action' => 'feed', 'format' => 'html', 'userid' => $this->objUser->userid())),$this->objLanguage->languageText("mod_blog_word_html", "blog"));
		$leftCol .= $html->show() . $link->show() . "<br />";

		return $leftCol;
	}

}
?>