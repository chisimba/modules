<?php

$this->loadClass('link', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');

$header = new htmlheading();
$header->type = 1;
$header->str = 'News Admin Options';
echo $header->show();

// Options
echo '<ul>';
$newsCategoriesLink = new link ($this->uri(array('action'=>'managecategories')));
$newsCategoriesLink->link = 'Manage News Categories';
echo '<li>'.$newsCategoriesLink->show().'</li>';

$newsLocationsLink = new link ($this->uri(array('action'=>'managelocations')));
$newsLocationsLink->link = 'Manage Locations';
echo '<li>'.$newsLocationsLink->show().'</li>';

$addNewsStoryLink = new link ($this->uri(array('action'=>'addstory')));
$addNewsStoryLink->link = 'Add News Story';
echo '<li>'.$addNewsStoryLink->show().'</li>';
echo '</ul>';
?>