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

$cssLayout = $this->getObject('csslayout', 'htmlelements');
$cssLayout->setNumColumns(2);

$leftContent = $this->objNewsCategories->getCategoriesMenu();

$leftContent .= '<h3>News Options</h3>';

$leftContent .= '<ul>';
$newHomeLink = new link ($this->uri(array('action'=>'home')));
$newHomeLink->link = 'News Home';
$leftContent .= '<li>'.$newHomeLink->show().'</li>';

$newsCategoriesLink = new link ($this->uri(array('action'=>'managecategories')));
$newsCategoriesLink->link = 'Manage News Categories';
$leftContent .= '<li>'.$newsCategoriesLink->show().'</li>';

$newsLocationsLink = new link ($this->uri(array('action'=>'managelocations')));
$newsLocationsLink->link = 'Manage Locations';
$leftContent .= '<li>'.$newsLocationsLink->show().'</li>';

$viewStoriesLink = new link ($this->uri(array('action'=>'viewstories')));
$viewStoriesLink->link = 'View Stories';
$leftContent .= '<li>'.$viewStoriesLink->show().'</li>';

$addNewsStoryLink = new link ($this->uri(array('action'=>'addstory')));
$addNewsStoryLink->link = 'Add News Story';
$leftContent .= '<li>'.$addNewsStoryLink->show().'</li>';

$keywordTagCloudLink = new link ($this->uri(array('action'=>'themecloud')));
$keywordTagCloudLink->link = 'Keyword Tag Clouds';
$leftContent .= '<li>'.$keywordTagCloudLink->show().'</li>';

$leftContent .= '</ul>';

$cssLayout->setLeftColumnContent($leftContent);
$cssLayout->setMiddleColumnContent($this->getContent());

echo $cssLayout->show();

?>