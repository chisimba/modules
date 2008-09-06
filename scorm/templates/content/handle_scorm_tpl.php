<?php
    // Load classes.
	$this->loadClass("form","htmlelements");
	$this->loadClass("htmltable", 'htmlelements');
	$this->loadClass("iframe", 'htmlelements');
	//Paul M. To do -- Correct form action
	$form = new form("default", 
		$this->uri(array(
	    		'module'=>'contextcontent'
	)));
	//Get The API
	$getApi = $this->getResourcePath('api.htm', 'scorm');
	//get scorm folder id
	//$folderId = 'gen5Srv7Nme24_7833_1217613986';
        $folder = $this->objFolders->getFolder($folderId);
	$filePath = $folder['folderpath'];
        $this->setVarByRef('filePath', $filePath);
	//Generate the TOC for navigation from imsmanifest.xml
	$navigators = $this->objReadXml->readManifest($filePath);
	//$navigators = $this->objReadXml->treeMenuXML($filePath);
	$objTable = new htmltable();
	$objTable->width='100%';
	$objTable->height='100%';
	$objTable->attributes=" align='center' border='0'";
	$objTable->cellspacing='12';
	$row = array("<b>".$objLanguage->code2Txt("word_name").":</b>");
	//$objIframe = new iframe();
	//iframe to hold the API
	$apiIFrame = '<iframe src="'.$getApi.'" name="API" height=0 width=10 frameborder=0 scrolling=no></iframe>';
	//iframe to hold the content
	$content = '<iframe src="usrfiles/'.$filePath.'/index.html" name="content" height=700 width=700 frameborder=0 scrolling=yes></iframe>';
	// Spacer
	$objTable->startRow();
	    $objTable->addCell($apiIFrame);
	    $objTable->addCell('<div>'.$navigators."</div>");
//	    $objTable->addCell($readXml);
	    $objTable->addCell($content);
	$objTable->endRow();
	$form->addToForm($objTable->show());
	echo $form->show();
?>
