<?php
$this->loadClass('htmltable','htmlelements');
$this->loadClass('textinput','htmlelements');
$this->loadClass('textarea','htmlelements');
$this->loadClass('dropdown','htmlelements');
$this->loadClass('checkbox','htmlelements');
$this->loadClass('button','htmlelements');
$objThumbnail = & $this->getObject('thumbnails','filemanager');
$link = $this->getObject('link','htmlelements');
//$button = $this->getObject('button','htmlelements');
//$dropdown = $this->getObject('dropdown','htmlelements');
$icon = $this->getObject('geticon', 'htmlelements');
$h1 = $this->getObject('htmlheading','htmlelements');
$form = $this->getObject('form', 'htmlelements');


$h1->type = 2;
$h1->str = 'Edit Album';
echo $h1->show();

$link->href = $this->uri(array('action' => 'editsection'));
$link->link = '&laquo; back to the list';
$str = $link->show().' | ';

$link->href = $this->uri(array('action' => 'sortalbumimages', 'albumid' => $this->getParam('albumid')));
$link->link = 'Sort Album';
$str .= $link->show().' | ';

$link->href = $this->uri(array('action' => 'viewalbum', 'albumid' => $this->getParam('albumid')));
$link->link = 'View Album';
$str .= $link->show().' | ';

$form->action = $this->uri(array('action' => 'savealbumedit','albumid' => $this->getParam('albumid')));

$title = new textinput('albumtitle');
$title->value = $album['title'];


$checkbox = new checkbox('isshared','isshared',$album['is_shared']);

$description = new textarea('description');
$description->value = $album['description'];

$dropdown = new dropdown('thumbselect');
//$dropdown->name = 'thumbselect';
//$dropdown->id = 'thumbselect';
$dropdown->extra = 'onchange="updateThumbPreview(this)"';

$table2 = new htmltable();
$table2->cssId = "edittable";
$table2->width = '50%';

$cnt = 0;
foreach($thumbnails as $thumbnail)
{
	$filename = $this->_objFileMan->getFileName($thumbnail['file_id']); 
 	$path = $objThumbnail->getThumbnail($thumbnail['file_id'],$filename);
 	$extra = 'class="thumboption" style="background-image: url(/chisimba_framework/app/'.$path.'); background-repeat: no-repeat;"';
	
	if($thumbnail['file_id'] == $album['thumbnail'] )
	{
		$dropdown->setSelected($thumbnail['file_id']);
	}

	$dropdown->addOption($thumbnail['file_id'],$cnt.$thumbnail['title'].' ('.$filename.')',$extra);
	
	$bigPath = $this->_objFileMan->getFilePath($thumbnail['file_id']);
	$img = '<img id="thumb-'.$cnt.'" src="'.$path.'" alt="'.$thumbnail['title'].'" 
                  onclick="toggleBigImage(\'thumb-'.$cnt.'\', \''.$bigPath.'\');" />';
    $imgTitle = new textinput($cnt.'-title',$thumbnail['title']);
    $imgId = new textinput($cnt.'-id', $thumbnail['id'],'hidden');
	
	 
	$imgDescription = new textarea($cnt.'-desc',$thumbnail['description'],'4','60');
	  
	$table2->startRow();
	$table2->addCell($img.$imgId->show(),null,'center');
	$table2->addCell('Title: <br />'.$imgTitle->show().'<br/>Description: <br />'.$imgDescription->show().'<br/><br/>');
	
	$table2->addCell($icon->getDeleteIconWithConfirm($thumbnail['id'],array('action' => 'deleteimage', 'imageid' => $thumbnail['id'], 'albumid' => $thumbnail['album_id']),'photogallery'),null,'center');
	$table2->endRow();
	
	$cnt++;
}

$totalimages = new textinput('totalimages',count($thumbnails),'hidden');


$button = new button();
$button->value = 'Save';
$button->setToSubmit();

$table = new htmltable();
$table->width = '40%';
$table->startRow();
$table->addCell('Ablum Title');
$table->addCell($title->show());
$table->endRow();

$table->startRow();
$table->addCell('Ablum Description');
$table->addCell($description->show());
$table->endRow();

$table->startRow();
$table->addCell('Thumbnail');
$table->addCell($dropdown->show());
$table->endRow();

$table->startRow();
$table->addCell('Share this album');
$table->addCell($checkbox->show());
$table->endRow();

$link->href = $this->uri(array('action' => 'editsection'));
$link->link = '&laquo; back to the list';

$form->addToForm($str.'<div class="box" style="padding: 15px;"><h2>editing <em>'.$album['title'].'</em></h2>');
$form->addToForm($table->show().'<br/>'.$button->show());
$form->addToForm($totalimages->show().'</div><hr /><div class="box" style="padding: 15px;"><p>Click the images for a larger version</p>');
$form->addToForm($table2->show().$button->show().'</div>');
$form->addToForm('<p>'.$link->show().'</p>');
?>
<script language="JavaScript" type="text/javascript"><!--
function updateThumbPreview(selectObj) {
  var thumb = selectObj.options[selectObj.selectedIndex].style.backgroundImage;

  selectObj.style.backgroundImage = thumb;
}

function toggleBigImage(id, largepath) {
  var imageobj = document.getElementById(id);
  if (!imageobj.sizedlarge) {
    imageobj.src2 = imageobj.src;
    imageobj.src = largepath;
    imageobj.style.position = 'absolute';
    imageobj.style.zIndex = '1000';
    imageobj.sizedlarge = true;
  } else {
    imageobj.style.position = 'relative';
    imageobj.style.zIndex = '0';
    imageobj.src = imageobj.src2;
    imageobj.sizedlarge = false;
  }
}
  //-->
		</script>
<?php

echo '<div id="main">'.$form->show().'</div';
?>