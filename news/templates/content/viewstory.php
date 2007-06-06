<style type="text/css">

/*Credits: Dynamic Drive CSS Library */
/*URL: http://www.dynamicdrive.com/style/ */

#ddblueblockmenu{
border: 1px solid black;
border-bottom-width: 0;
width: 165px;
}

#ddblueblockmenu ul{
margin: 0;
padding: 0;
list-style-type: none;
font: normal 90% 'Trebuchet MS', 'Lucida Grande', Arial, sans-serif;
}

#ddblueblockmenu li {
	margin: 0;
	background-image: none;
	padding-left: 0;
}
#ddblueblockmenu li a{
display: block;
padding: 3px 0;
padding-left: 9px;
width: 149px; /*165px minus all left/right paddings and margins*/
text-decoration: none;
color: white;
background-color: #2175bc;
border-bottom: 1px solid #90bade;
border-left: 7px solid #1958b7;
}

* html #ddblueblockmenu li a{ /*IE only */
width: 167px; /*IE 5*/
w\idth: 149px; /*185px minus all left/right paddings and margins*/
}

#ddblueblockmenu li a:hover {
background-color: #2586d7;
border-left-color: #1c64d1;
}

#ddblueblockmenu div.menutitle{
color: white;
border-bottom: 1px solid black;
padding: 1px 0;
padding-left: 5px;
background-color: black;
font: bold 90% 'Trebuchet MS', 'Lucida Grande', Arial, sans-serif;
}

.storyimage {
	float: left;
	margin: 5px;
}

.newsstory {
	display: block;
}

</style>
<?php

$this->loadClass('link', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');

$objIcon = $this->getObject('geticon', 'htmlelements');

$objDateTime = $this->getObject('dateandtime', 'utilities');
$objUrl = &$this->getObject('url', 'strings'); 
$objThumbnails = $this->getObject('thumbnails', 'filemanager');

$objIcon->setIcon('edit');
$editLink = new link ($this->uri(array('action'=>'editstory', 'id'=>$story['id'])));
$editLink->link = $objIcon->show();

$header = new htmlheading();
$header->type = 1;
$header->str = $story['storytitle'];

if ($this->isValid('editstory')) {
    $header->str .= ' '.$editLink->show();
}


$middleContent = $header->show();

$middleContent .= '<p>'.$objDateTime->formatDate($story['storydate']).'</p>';

if ($story['storyimage'] != '') {
    $middleContent .= '<img class="storyimage" src="'.$objThumbnails->getThumbnail($story['storyimage'], $story['filename']).'" alt="'.$story['storytitle'].'" title="'.$story['storytitle'].'" />';
}

$objWashOut = $this->getObject('washout', 'utilities');

$middleContent .= $objWashOut->parseText($story['storytext']);

if ($story['storysource'] != '') {
	$source = $story['storysource'];
	
	$source = $objUrl->makeClickableLinks($source);
	
	$middleContent .= '<p><strong>Source:</strong><br />'.$source.'</p>';
}

$cssLayout = $this->getObject('csslayout', 'htmlelements');
$cssLayout->setNumColumns(3);

$leftContent = $this->objNewsCategories->getCategoriesMenu();

$rightContent = '';


$rightContent .= $this->objNewsStories->getRelatedStoriesFormatted($story['id'], $story['storydate'], $story['datecreated']);
$rightContent .= $this->objKeywords->getStoryKeywordsBlock($story['id']);

$cssLayout->setLeftColumnContent($leftContent);
$cssLayout->setMiddleColumnContent($middleContent);
$cssLayout->setRightColumnContent($rightContent);

echo $cssLayout->show();


?>