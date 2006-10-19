<?php
$cssLayout = &$this->newObject('csslayout', 'htmlelements');
$objSideBar = $this->newObject('sidebar', 'navigation');
// Set columns to 3
$cssLayout->setNumColumns(3);
$leftMenu = "RSS FEEDS"; //&$this->newObject('usermenu', 'toolbar');
$rightSideColumn = NULL; //$this->objLanguage->languageText('mod_blog_instructions', 'blog');
//$this->setLayoutTemplate('blayout_tpl.php');
//print_r($cats); die();
$rightSideColumn .= "<em>" . $this->objLanguage->languageText("mod_blog_categories", "blog") . "</em>";
$rightSideColumn .= "<br />";

//print_r($cats);
$nodes = array();
$childnodes = array();

if(!empty($cats))
{
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





$cssLayout->setMiddleColumnContent($this->getContent());
$cssLayout->setLeftColumnContent($leftMenu); //$leftMenu->show());
$cssLayout->setRightColumnContent($rightSideColumn);
echo $cssLayout->show();
?>