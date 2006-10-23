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

}
?>