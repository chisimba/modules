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

	/**
	 * Method to display the link to blog admin on post login
	 *
	 * @param bool $featurebox
	 * @return string
	 */
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

	/**
	 * Method to display the posts per user
	 *
	 * @param array $posts
	 * @return string
	 */
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

	/**
	 * Method to build and create the feeds options box
	 *
	 * @param integer $userid
	 * @param bool $featurebox
	 * @return string
	 */
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
	 * Method to build and display the full scale category editor
	 *
	 * @param integer $userid
	 * @return string
	 */
	public function categoryEditor($userid)
	{
		//get the categories layout sorted
		$cats = $this->objDbBlog->getAllCats($userid);
		//create a table to view the categories
		$cattable = $this->getObject('htmltable', 'htmlelements');
		$cattable->cellpadding = 3;
		//set up the header row
		$cattable->startHeaderRow();
		$cattable->addHeaderCell($this->objLanguage->languageText("mod_blog_cathead_parent", "blog"));
		//$cattable->addHeaderCell($this->objLanguage->languageText("mod_blog_cathead_name", "blog"));
		$cattable->addHeaderCell($this->objLanguage->languageText("mod_blog_cathead_nicename", "blog"));
		$cattable->addHeaderCell($this->objLanguage->languageText("mod_blog_cathead_descrip", "blog"));
		$cattable->addHeaderCell($this->objLanguage->languageText("mod_blog_cathead_count", "blog"));
		$cattable->endHeaderRow();
		if(!empty($cats))
		{
			foreach($cats as $rows)
			{
				//start the cats rows
				$cattable->startRow();
				if($rows['cat_parent'] != '0')
				{
					$maparr = $this->objDbBlog->mapKid2Parent($rows['cat_parent']);
					$rows['cat_parent'] = "<em><b>" . $maparr[0]['cat_name'] . "</b></em>";
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
				$cattable->endRow();
			}
			$ctable = $cattable->show();
		}
		else {
			$ctable = $this->objLanguage->languageText("mod_blog_nocats", "blog");
		}

		//add a new category form:
		$catform = new form('catadd', $this->uri(array(
		'action' => 'catadd'
		)));

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
		//$catadd->startRow();
		$desclabel = new label($this->objLanguage->languageText('mod_blog_catdesc', 'blog') .':', 'input_catdesc');
		$cdesc = $this->newObject('htmlarea','htmlelements');
		$cdesc->setName('catdesc');
		//$cdesc->setBasicToolBar();
		$cdesc->showFCKEditor();
		$catadd->addCell($desclabel->show());
		$catadd->addCell($cdesc->show());
		$catadd->endRow();

		$cfieldset->addContent($catadd->show());
		$catform->addToForm($cfieldset->show());
		$this->objCButton = &new button($this->objLanguage->languageText('word_update', 'blog'));
		$this->objCButton->setValue($this->objLanguage->languageText('word_update', 'blog'));
		$this->objCButton->setToSubmit();
		$catform->addToForm($this->objCButton->show());
		$catform = $catform->show();

		return $ctable . "<br />" . $catform;
	}



}
?>