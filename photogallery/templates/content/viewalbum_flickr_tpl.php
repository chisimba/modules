<?php

$link = $this->getObject('link','htmlelements');
$objThumbnail = & $this->getObject('thumbnails','filemanager');

$link->href = $this->uri(null,'photogallery');
$link->link = 'Photo Gallery';

$albumInfo = $this->_objFlickr->photosets_getInfo($this->getParam('albumid'));
$this->setVar('pageTitle', 'Photo Gallery - '. $albumInfo['title']);

$head = '<div id="main2"><div id="gallerytitle">
		<h2><span>'.$link->show().' | </span> '.$albumInfo['title'].'
</h2></div>
<div id="albumDesc" style="display: block;">'.$albumInfo['description'].'</div>
	';
print $head;

if($images)
{
	
	foreach($images['photo'] as $image)
	{
		$str.='<div class="image"><div class="imagethumb">';
	
	 	$link->href = $this->uri(array('action' => 'viewimage', 'albumid' => $this->getParam('albumid'),'imageid' => $image["id"], 'mode' => 'flickr'));
	  	$link->link = '<img  src="'.$this->_objFlickr->buildPhotoURL($image, "Square").'">';
	 	//$link->extra = ' rel="lightbox" ';
		$str.=$link->show().'</div></div>';
	}
	
	print $str.'
	
	<div class="pagelist">
<ul class="pagelist">
  
</ul>
</div></div>';
}else {
	
	print 'No Photos for '.$albumInfo['title'];
} 
?>