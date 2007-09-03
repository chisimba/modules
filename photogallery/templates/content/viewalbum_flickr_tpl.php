<?php
$str = '';
$link = $this->getObject('link','htmlelements');
$objThumbnail = & $this->getObject('thumbnails','filemanager');
$this->loadClass('windowpop', 'htmlelements');

$link->href = $this->uri(null,'photogallery');
$link->link = 'Photo Gallery';

$albumInfo = $this->_objFlickr->photosets_getInfo($this->getParam('albumid'));
$this->setVar('pageTitle', 'Photo Gallery - '. $albumInfo['title']);

$url = 'http://www.flickr.com/photos/'.$albumInfo['owner'].'/sets/'.$albumInfo['id'].'/show/';	
$this->objPop=new windowpop();
         $this->objPop->set('location',$url);
            $this->objPop->set('linktext','View Slide Show');
            $this->objPop->set('width','850');
            $this->objPop->set('height','650');
            $this->objPop->set('left','300');
            $this->objPop->set('top','400');
            //leave the rest at default values
            $this->objPop->putJs(); // you only need to do this once per page
             

$slideshow = '<div id="nextimage" >'.$this->objPop->show().'</div></div>';

$head = '<div id="main2"><div class="imgnav">'.$slideshow.'<div id="gallerytitle">
		<h2><span>'.$link->show().' | </span><img src="http://static.netvibes.com/img/flickr.png"> '.$albumInfo['title'].'
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