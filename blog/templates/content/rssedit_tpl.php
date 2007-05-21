<?php
//rss add/edit template
$cssLayout = &$this->newObject('csslayout', 'htmlelements');
// Set columns to 3
$cssLayout->setNumColumns(3);
$middleColumn = NULL;
$objUi = $this->getObject('blogui');
// left hand blocks
$leftCol = $objUi->leftBlocks($userid);
// right side blocks
$rightSideColumn = $objUi->rightBlocks($userid, NULL);
if(!empty($rss))
{
	foreach($rss as $feeds)
	{
		$timenow = time();
		if(($timenow - $feeds['rsstime']) > 43200)
		{
			$url = htmlentities($feeds['url']);
		}
		else {
			$url = htmlentities($feeds['rsscache']);
		}
		$leftCol .= $this->objblogOps->rssBox($url, $feeds['name']);
	}
}
//dump the cssLayout to screen
$cssLayout->setMiddleColumnContent($middleColumn);
$cssLayout->setLeftColumnContent($leftCol);
$cssLayout->setRightColumnContent($rightSideColumn);
echo $cssLayout->show();
?>