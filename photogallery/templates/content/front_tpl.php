<?php
$link = $this->getObject('link','htmlelements');
$objThumbnail = & $this->getObject('thumbnails','filemanager');
$objH = $this->getObject('htmlheading', 'htmlelements');

//print '<div id="gallerytitle">
//		<h2>my photos | shared photos</h2>
//	</div>';
	//var_dump($albums);

if($this->_objUser->isLoggedIn())
{
 	if($this->getParam('mode') != 'shared')
 	{
		$link->href = $this->uri(array('action' => 'front', 'mode' => 'shared'));
		$link->link = '| Shared Photos';	
		$objH->str = ' My Gallery   '.$link->show().'</span>';	
	} else {
		$link->href = $this->uri(array('action' => 'front'));
		$link->link = 'My Gallery | ';	
		$objH->str = '<span> '.$link->show().' </span>Shared Photos';	
	}
  
} else {
	$objH->str ='Photo Gallery ';
}
$objH->type = 2;

echo '<div id="gallerytitle">'.$objH->show().'</div>';


if(count($albums) > 0 && $this->_objUser->isLoggedIn() && $this->getParam('mode') != 'shared')
{
	
	foreach($albums as $album)
	{
	 	$str .= '<div class="album">';
	 	
	 	$filename = $this->_objFileMan->getFileName($album['file_id']); 
 		$path = $objThumbnail->getThumbnail($album['thumbnail'],$filename);
 	
	 	$link->href = $this->uri(array('action' => 'viewalbum', 'albumid' => $album['id']));
	 	$link->link = '<img src="'.$path.'" alt="'.$album['title'].'" />';
		
		$str .= $link->show();
		$str .= '<div class="albumdesc">';
		
		$link->href = $this->uri(array('action' => 'viewalbum', 'albumid' => $album['id']));
	 	$link->link = $album['title'];

		$str .=	'<h3>'.$link->show().'</h3><p>'.$album['description'].'</p></div>
					<p style="clear: both; "></p></div>';
		
	}
	
	print '<div id="albums">'. $str .'</div>';
} else {
 


		if(count($sharedalbums) > 0 )
		{
			
			foreach($sharedalbums as $sharedAlbum)
			{
				$str .= '<div class="album">
							';
			 	
			 	$filename = $this->_objFileMan->getFileName($sharedAlbum['file_id']); 
		 		$path = $objThumbnail->getThumbnail($sharedAlbum['thumbnail'],$filename);
		 	
			 	$link->href = $this->uri(array('action' => 'viewalbum', 'albumid' => $sharedAlbum['id']));
			 	$link->link = '<img src="'.$path.'" alt="'.$sharedAlbum['title'].'" />';
				
				$str .= $link->show();
				$str .= '
						<div class="albumdesc">
							<small></small>';
				
				$link->href = $this->uri(array('action' => 'viewalbum', 'albumid' => $sharedAlbum['id']));
			 	$link->link = $sharedAlbum['title'];
		
				$str .=	'<h3>'.$link->show().'</h3>'.$sharedAlbum['description'].'
						</div>
							<p style="clear: both; "></p>
						</div>';
				
			}
			
			echo '<div id="albums">'. $str .'</div>';	
				
		
		}

	}
?>