<?php

$this->loadClass('link', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');

$header = new htmlheading();
$header->type = 1;
$header->str = 'Latest News';
echo $header->show();

echo $topStories;



if (count($categories) > 0) {
	foreach ($categories as $category)
	{
		echo '<h3>'.$category['categoryname'].'</h3>';
		
		echo $this->objNewsStories->getNonTopStoriesFormatted($category['id'], $topStoriesId);
	}	
}
//$nonTopStories = $this->objNewsStories->getNonTopStories($topStories['topstoryids']);
?>