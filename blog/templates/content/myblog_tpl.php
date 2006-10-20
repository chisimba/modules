<?php
$this->loadClass('href', 'htmlelements');
$tt = $this->newObject('domtt', 'htmlelements');
$cssLayout = &$this->newObject('csslayout', 'htmlelements');
$objSideBar = $this->newObject('sidebar', 'navigation');
// Set columns to 3
$cssLayout->setNumColumns(3);
$leftMenu = &$this->newObject('usermenu', 'toolbar');
$rightSideColumn = NULL; //$this->objLanguage->languageText('mod_blog_instructions', 'blog');
//$this->setLayoutTemplate('blayout_tpl.php');
//print_r($cats); die();

//print_r($cats);
$nodes = array();
$childnodes = array();

if(!empty($cats))
{
	$rightSideColumn .= "<em>" . $this->objLanguage->languageText("mod_blog_categories", "blog") . "</em>";
	$rightSideColumn .= "<br />";
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
			$nodestoadd[] = array('text'=> $categories['cat_nicename'], 'uri' => $this->uri(array('action' => 'showsection', 'id' => $categories['id']), 'blog'), 'haschildren' => $childnodes);
			$childnodes = NULL;
			$nodes = NULL;
	}
	$rightSideColumn .= $objSideBar->show($nodestoadd, NULL, NULL, 'blog');
}

//$rightSideColumn .= "<hr />";
$rightSideColumn .= "<br />";
//cycle through the link categories and display them
foreach($linkcats as $lc)
{
	$rightSideColumn .= "<em>" . $lc['catname'] . "</em><br />";
	$linkers = $this->objDbBlog->getLinksCats($this->objUser->userid(), $lc['id']);
	if(!empty($linkers))
	{
		$rightSideColumn .= "<ul>";
		foreach($linkers as $lk)
		{
			$rightSideColumn .= "<li>";

			//$tips = $tt->show($lk['link_name'], $lk['link_description'], $lk['link_name'], $lk['link_url']);
			$alt = htmlentities($lk['link_description']);
			$link = new href(htmlentities($lk['link_url']), htmlentities($lk['link_name']), "alt='{$alt}'"); // . $tips;
			$rightSideColumn .= $link->show();
			$rightSideColumn .= "</li>";
		}
		$rightSideColumn .= "</ul>";
	}
}

//add a break to the righsidecol and carry on with the meta data and admin sections
$rightSideColumn .= "<br />";

//admin section
$rightSideColumn .= "<em>" . $this->objLanguage->languageText("mod_blog_admin", "blog") . "</em><br />";

//blog admin page
$admin = new href($this->uri(array('action' => 'blogadmin')), $this->objLanguage->languageText("mod_blog_blogadmin", "blog"));
$rightSideColumn .= $admin->show();


//Middle column (posts)!
//break out the ol featurebox...
//fake it
$middleColumn = NULL;
//print_r($posts); die();
//$posts = array(array('tagline' => 'some shit', 'content' => 'some more content'),array('tagline' => 'some more shit', 'content' => 'some other content'));
if(!empty($posts))
{
	foreach($posts as $post)
	{
		$objFeatureBox = $this->getObject('featurebox', 'navigation');
		//build the top level stuff
		$dt = strtotime($post['post_date']);
		$dt = date('r', $dt);
		$head = $post['post_title'] . "<br />" . $dt;
		$middleColumn .= $objFeatureBox->show($head, $post['post_content']);
	}
}
else {
	$middleColumn .= "<h1><em><center>" . $this->objLanguage->languageText("mod_blog_noposts", "blog") . "</center></em></h1>";
}


//left menu section
$leftCol = $leftMenu->show();
$leftCol .= "<br />";
$leftCol .= "<em>" . $this->objLanguage->languageText("mod_blog_feedheader", "blog") . "</em><br />";

//RSS2.0
$rss2 = $this->getObject('geticon', 'htmlelements');
$rss2->setIcon('rss', 'gif', 'icons/filetypes');
$link = new href($this->uri(array('action' => 'getrss2')),$this->objLanguage->languageText("mod_blog_word_rss2", "blog"));
$leftCol .= $rss2->show() . $link->show();


$cssLayout->setMiddleColumnContent($middleColumn);
$cssLayout->setLeftColumnContent($leftCol); //$leftMenu->show());
$cssLayout->setRightColumnContent($rightSideColumn);
echo $cssLayout->show();
?>