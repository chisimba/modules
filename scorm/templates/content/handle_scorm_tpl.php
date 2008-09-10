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

//AJAX to check if selected folder contains scorm
    
    $this->appendArrayVar('headerParams', "
    <script type='text/javascript'>
        
        // Flag Variable - Update message or not
        var doUpdateMessage = false;
        
        // Var Current Entered Code
        var currentCode;
        
        // Action to be taken once page has loaded
        jQuery(document).ready(function(){
            jQuery('#content').bind(\"change\", function() {
                getIframeID(jQuery('#content').attr(\"src\"));
            });
        });

	function getIframeID(el)
	{
	var myTop;
	if (window.frameElement) {
		myTop = window.frameElement;
	} else if (window.top) {
		myTop = window.top;
		var myURL = location.href;
		var iFs = myTop.document.getElementsByTagName('iframe');
		var x, i = iFs.length;
		while ( i-- ){
		x = iFs[i];
			if (x.src && x.src !== myURL){
				myTop = x;
				jQuery('#contextcodemessage').html(myTop);
				break;
			}
		}
	}
	if (myTop){
		return 'The iframe ' + ((myTop.id)?
		'has ID=' + myTop.id : 'is anonymous');
	} else {
		return 'Couldn\'t find the iframe';
	}
	}
    </script>");

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
$testNavs = "<div align = 'center'> <a href = 'usrfiles/".$filePath."/index.html' target = 'content' id = 'home'> Home</a>"." &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  Back  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Next </div>";

	// Spacer
	$objTable->startRow();
	    $objTable->addCell($apiIFrame);
	    $objTable->addCell('<div>'.$navigators."</div>");
//	    $objTable->addCell($readXml);
	    $objTable->addCell($testNavs."<br />".$content."<br />".$testNavs);
	$objTable->startRow();
	    $objTable->addCell("&nbsp;");
	    $objTable->addCell("&nbsp;");
	    $objTable->addCell(' <span id="contextcodemessage">'.$contextCodeMessage.'</span>');
//	    $objTable->addCell($readXml);
//	    $objTable->addCell($content);
	$objTable->endRow();

	$objTable->endRow();
	$form->addToForm($objTable->show());
	echo $form->show();
?>
