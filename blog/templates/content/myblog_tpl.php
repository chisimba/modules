<?php
$cssLayout = &$this->newObject('csslayout', 'htmlelements');
// Set columns to 3
$cssLayout->setNumColumns(3);
$leftMenu = "RSS FEEDS"; //&$this->newObject('usermenu', 'toolbar');
$rightSideColumn = NULL; //$this->objLanguage->languageText('mod_blog_instructions', 'blog');
//$this->setLayoutTemplate('blayout_tpl.php');
//print_r($cats); die();
$rightSideColumn .= "<em>" . $this->objLanguage->languageText("mod_blog_categories", "blog") . "</em>";
$rightSideColumn .= "<br />";
$rightSideColumn .= "<ul>";
//print_r($cats); die();
foreach($cats as $categories)
{
	if(isset($categories['cat_name']))
	{
		$rightSideColumn .= "<li>" . $categories['cat_name'] . "</li>";
	}

}
$rightSideColumn .= "</ul>";


$cssLayout->setMiddleColumnContent($this->getContent());
$cssLayout->setLeftColumnContent($leftMenu); //$leftMenu->show());
$cssLayout->setRightColumnContent($rightSideColumn);
echo $cssLayout->show();
?>