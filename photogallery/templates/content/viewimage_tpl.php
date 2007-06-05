<?php

$link = $this->getObject('link','htmlelements');
$objThumbnail = & $this->getObject('thumbnails','filemanager');
$this->loadClass('htmltable','htmlelements');
$this->loadClass('textinput','htmlelements');
$this->loadClass('textarea','htmlelements');
$this->loadClass('dropdown','htmlelements');
$this->loadClass('button','htmlelements');
$h = $this->getObject('htmlheading','htmlelements');
$form = $this->getObject('form', 'htmlelements');



$scripts = '<script type="text/javascript" src="/chisimba_modules/photogallery/resources/lightbox/js/prototype.js"></script>
<script type="text/javascript" src="/chisimba_modules/photogallery/resources/lightbox/js/scriptaculous.js?load=effects"></script>
<script type="text/javascript" src="/chisimba_modules/photogallery/resources/lightbox/js/lightbox.js"></script>
<link rel="stylesheet" href="/chisimba_modules/photogallery/resources/lightbox/css/lightbox.css" type="text/css" media="screen" />';
$this->appendArrayVar('headerParams',$scripts);
$str = '<div id="image">';
//$link->href = $this->uri(array('action' => 'viewimage', 'imageid' => $image['id']));

$filename = $this->_objFileMan->getFileName($image['file_id']); 
$path = $objThumbnail->getThumbnail($image['file_id'],$filename);
$bigPath = $this->_objFileMan->getFilePath($image['file_id']);
 		
$link->href = $bigPath;
$link->link = '<img title="'.$image['title'].'" src="'.$bigPath.'" alt="'.$image['title'].'" width="595" height="446" />';
$link->extra = ' rel="lightbox" ';
$str.=$link->show().'</div>';
	



$form->action = $this->uri(array('action' => 'addcomment', 'imageid' => $this->getParam('imageid'), 'albumid' => $this->getParam('albumid')));

$name = new textinput('name');
$form->addRule('name','Please suppy a name!', 'required');

$table = new htmltable();
$table->width = '200';
$table->startRow();
$table->addCell('<label for="name">Name:</label>');
$table->addCell($name->show());
$table->endRow();

$email = new textinput('email');
$table->startRow();
$table->addCell('<label for="email">E-Mail:</label>');
$table->addCell($email->show());
$table->endRow();

$website = new textinput('website');
$table->startRow();
$table->addCell('<label for="website">Site:</label>');
$table->addCell($website->show());
$table->endRow();

$commentField = new textarea('comment');
$button = new button();
$button->value = 'Add Comment';
$button->setToSubmit();


$this->setVar('pageTitle', 'Photo Gallery - '.$this->_objDBAlbum->getAlbumTitle($this->getParam('albumid')).' - '.$image['title']);

$form->addToForm('<h3>Add a comment</h3>'.$table->show());
$form->addToForm($commentField->show().'<br/>'.$button->show());

if(count($comments) > 0)
{
 	$strComment = '<h3>Comments ('.count($comments).')</h3>';
	foreach($comments as $comment)
	{
		$strComment .= '<div class="comment"><div class="commentmeta"><span class="commentauthor">'.$comment['name'].'</span> says:'; 
		$strComment .=	'</div>	<div class="commentbody">'.$comment['comment'].'</div><div class="commentdate">';
		$strComment .= $comment['commentdate'].'</div>	</div>';
		
	}
}
$link->extra = '';
$link->href = $this->uri(null,'photogallery');
$link->link = 'Photo Gallery';
$galLink = $link->show();

$link->href = $this->uri(array('action' => 'viewalbum', 'albumid' =>$this->getParam('albumid') ),'photogallery');
$link->link = $this->_objDBAlbum->getAlbumTitle($this->getParam('albumid'));
$albumLink = $link->show();

$nav = $this->_objUtils->getImageNav($image['id']);
			
$head = '<div id="main2">'.$nav.'<div id="gallerytitle">
		<h2><span>'.$galLink.' | </span> <span>'.$albumLink.'
		| </span>'.$image['title'].'
		</h2></div>

	';

$desc  = '<div id="narrow"><div id="iamgeDesc" style="display: block;">'.$image['description'].'</div>';
	
print $head;

echo $str;
echo $desc;
echo $strComment;
echo $form->show().'</div></div>';

?>