<?php

$cssLayout = $this->getObject('csslayout', 'htmlelements');


$leftContent = '<h3>News Options</h3>';

$leftContent .= '<ul>';
$newsCategoriesLink = new link ($this->uri(array('action'=>'managecategories')));
$newsCategoriesLink->link = 'Manage News Categories';
$leftContent .= '<li>'.$newsCategoriesLink->show().'</li>';

$newsLocationsLink = new link ($this->uri(array('action'=>'managelocations')));
$newsLocationsLink->link = 'Manage Locations';
$leftContent .= '<li>'.$newsLocationsLink->show().'</li>';

$addNewsStoryLink = new link ($this->uri(array('action'=>'addstory')));
$addNewsStoryLink->link = 'Add News Story';
$leftContent .= '<li>'.$addNewsStoryLink->show().'</li>';
$leftContent .= '</ul>';

$cssLayout->setLeftColumnContent($leftContent);
$cssLayout->setMiddleColumnContent($this->getContent());

echo $cssLayout->show();

?>