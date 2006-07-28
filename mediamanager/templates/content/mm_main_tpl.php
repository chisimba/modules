<?php

$fieldset = & $this->newObject('fieldset', 'htmlelements');
$fieldset2 = & $this->newObject('fieldset', 'htmlelements');
$dropdown = & $this->newObject('dropdown', 'htmlelements');
$iframe = & $this->newObject('iframe', 'htmlelements');
$form = & $this->newObject('form', 'htmlelements');
$input = & $this->newObject('textinput', 'htmlelements');
$input2 = & $this->newObject('textinput', 'htmlelements');
$smalltable = & $this->newObject('htmltable', 'htmlelements');
$createButton = & $this->newObject('button', 'htmlelements');
$uploadButton = & $this->newObject('button', 'htmlelements');
$hinput = & $this->newObject('textinput', 'htmlelements');
$form2 = & $this->newObject('form', 'htmlelements');
$inputNewFolder = & $this->newObject('textinput', 'htmlelements');

//form for creating folders
$form2->name = 'frmlist';
$form2->id = 'frmlist';
$form2->setAction($this->uri(array('action' => 'createfolder', 'folder' => $this->getParam('folder')), 'mediamanager'));
$inputNewFolder->name = 'newfolder';
$inputNewFolder->value = '';
$inputNewFolder->fldType = 'hidden';

$form2->addToForm($inputNewFolder);

//create the form
$form->name = 'frmmedia';
$form->id = 'frmmedia';
$form->setAction($this->uri(array('action' => 'upload', 'folder' => $this->getParam('folder')), 'mediamanager'));
$form->extra = 'enctype="multipart/form-data"';
//$form->displayType = 4;

//create folder button
$createButton->value = 'Create Folder';
$createButton->setOnClick('newFolder()');

//upload button
$uploadButton->value = 'Upload';
$uploadButton->setToSubmit();

//hidden input to hold the current folder
$hinput->value = $this->getParam('folder');
$hinput->name = 'folderhidden';
$hinput->fldType = 'hidden';

//upload field
$input->value = '';
$input->fldType ='file';
$input->name = 'upload';
$input->size = '59';


//create folder
$input2->value = '';
$input2->name = 'createfolder';
$input2->setId('createfolder');
$input2->size = '70';

$smalltable->cellspacing = 10;
$smalltable->startRow();
$smalltable->addCell('Upload');
$smalltable->addCell($input->show().'<span class="warning" >Max size = 16M</span>');
$smalltable->endRow();

$smalltable->startRow();
$smalltable->addCell('Create Folder');
$smalltable->addCell($input2->show());
$smalltable->endRow();



$fieldset2->setLegend('<h1>Media Manager</h1>');
$this->_objMedia->getFolders();

$dropdown->addFromDB($this->_objMedia->getFolders(),'foldername','foldername',$this->getParam('folder'));
$dropdown->extra = ' onchange="goUpDir()"';
$dropdown->name = 'dirPath';
$dropdown->id = 'dirPath';

$iframe->width = '100%';
$iframe->height = '170';
$iframe->name = 'list';
$iframe->marginwidth = '0';
$iframe->marginheight = '0';
//$iframe->align = 'top';
$iframe->scrolling ='auto';
$iframe->frameborder = '0';
$iframe->src = $this->uri(array('action' => 'showmedia', 'folder' => $this->getParam('folder')), 'mediamanager');

$frame = $iframe->show().'</p>';
//$up = '<a href="javascript:dirup()"><img src="components/com_media/images/btnFolderUp.gif" width="15" height="15" border="0" alt="Up"></a>';

$fieldset->addContent('Directory   '.$dropdown->show().'&nbsp;'.$up.'<p>'. $frame);

$fieldset2->addContent('<p>&nbsp;</p>'.$fieldset->show());
$fieldset2->addContent($createButton->show().$uploadButton->show().'<p></p>'.$smalltable->show());
//$fieldset2->addContent('<p>Create Folder   '.$input2->show());


//$form->beginFieldset('legend');

$form->addToForm($fieldset2->show());
//$form->endFieldset();
echo $form->show();
echo $form2->show();

?>

<script language="javascript" type="text/javascript">
<![CDATA[
	function dirup(){
		var urlquery=frames['<?php echo $iframe->name;?>'].location.search.substring(1);
		var curdir= urlquery.substring(urlquery.indexOf('folder=')+8);
		var listdir=curdir.substring(0,curdir.lastIndexOf('/'));
		frames['<?php echo $iframe->name;?>'].location.href='<?php  echo $this->uri(array('action' => 'showmedia'))?>&folder=' + listdir;
	}

	function newFolder()
	{
		
		var selection = document.forms['<?php echo $form->name ?>'].createfolder;
		var newSelection = document.forms['<?php echo $form2->name ?>'].newfolder;
		newSelection.value = selection.value;
		document.forms['<?php echo $form->name ?>'].submit();
		alert(input.value);
		
	}

	function goUpDir()
	{
		var selection = document.forms[1].dirPath;
		var dir = selection.options[selection.selectedIndex].value;
		document.forms[1].action = document.forms[1].action +'&folder='  + dir;
		
		frames['<?php echo $iframe->name;?>'].location.href='<?php  echo str_replace("&amp;", "&", $this->uri(array('action' => 'showmedia')))?>&folder=' + dir;
	}
]]>
	</script>
	