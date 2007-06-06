<?php

$cssLayout = $this->getObject('csslayout', 'htmlelements');


$leftContent = '<h3>News Options</h3>';

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