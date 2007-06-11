<?php
$this->loadClass('htmltable','htmlelements');
$link = $this->getObject('link','htmlelements');
$form = $this->getObject('form', 'htmlelements');
$icon = $this->getObject('geticon', 'htmlelements');
$this->loadClass('textinput','htmlelements');
$this->loadClass('button','htmlelements');


$form->action = $this->uri(array('action' => 'validateflickusername'));
$form->displayType = 2;

$cnt =0 ;
$str = '';


//$table->cssId = "edittable";
//$table->width = '50%';

$username = new textinput('username');
$username->label = 'Flickr Username:';

$password = new textinput('password');
$password->label = 'Password:';
$button = new button();
$button->value = 'Add';
$button->setToSubmit();

$form->addToForm($username);
//$form->addToForm($password);
$form->addToForm($button);


if(count($usernames) > 0)
{
 	$table = new htmltable();
	$table->cssClass = 'bordered';
	$table->startHeaderRow();
	$table->width = '50%';
	$table->addHeaderCell('Username',55);
	$table->addHeaderCell('View Albums/Sets',30);
	$table->addHeaderCell('Upload Images',30);
	$table->addHeaderCell('Add Comments',30);
	$table->addHeaderCell('Edit Images',30);
	$table->addHeaderCell('');
	$table->endHeaderRow();

	foreach($usernames as $username)
	{
	 	$table->startRow();
		$table->addCell($username['flickr_username']);		
		$table->addCell($thumb);
		$table->addCell('');
		$table->addCell('');
		$table->addCell('');
		$table->addCell('');
		$table->endRow();
	}
	
	$list =  $table->show();
} else {
	//$list = 'No Flickr usernames available';
}
echo '<div id="main"><h2><img src="http://l.yimg.com/www.flickr.com/images/flickr_logo_gamma.gif.v1.5.7"> Flickr Usernames</h2>';
echo '<div class="box" style="padding: 15px;">'.$form->show().$list.'</div>';

echo '</div>'
?>