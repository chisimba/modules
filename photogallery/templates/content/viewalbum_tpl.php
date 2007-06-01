<?php

$link = $this->getObject('link','htmlelements');
$objThumbnail = & $this->getObject('thumbnails','filemanager');

$link->href = $this->uri(null,'photogallery');
$link->link = 'Photo Gallery';

$head = '<div id="gallerytitle">
		<h2><span>'.$link->show().' | </span> '.$this->_objDBAlbum->getAlbumTitle($this->getParam('albumid')).'
</h2></div>
<div id="albumDesc" style="display: block;">'.$this->_objDBAlbum->getAlbumDescription($this->getParam('albumid')).'</div>
	';
print $head;

if(count($images) > 0)
{
	
	foreach($images as $image)
	{
		$str.='<div class="image"><div class="imagethumb">';
		$filename = $this->_objFileMan->getFileName($image['file_id']); 
 		$path = $objThumbnail->getThumbnail($image['file_id'],$filename);
 		$bigPath = $this->_objFileMan->getFilePath($image['file_id']);
	 	$link->href = $this->uri(array('action' => 'viewimage', 'albumid' => $this->getParam('albumid'),'imageid' => $image['id']));
	 	$link->link = '<img title="'.$image['title'].'" src="'.$path.'" alt="'.$image['title'].'"  />';
	 	$link->extra = ' rel="lightbox" ';
		$str.=$link->show().'</div></div>';
	}
	
	print $str.'</div>
	
	<div class="pagelist">
<ul class="pagelist">
  
</ul>
</div>';
}else {
	
	print 'No Photos for '.$album['title'];
} 
?>