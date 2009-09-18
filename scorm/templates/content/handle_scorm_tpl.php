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
    
    $this->appendArrayVar('headerParams', "<style type='text/css'>
  div.small-box {
  width:250px;
  height:550px;
  border:1px solid grey;
  overflow:scroll;
  font:30px;
  }
  div.big-box {
  width:700px;
  height:550px;
  border:1px solid grey;
  //overflow:scroll;
  font:30px;
  }

</style>
    <script type='text/javascript'>
        
        // Flag Variable - Update message or not
        var doUpdateMessage = false;
	var nextPage;
	var prevPage;        
        // Var Current Entered Code
        var currentCode;

	//var fpath = jQuery('#rootFolder').attr(\"value\");

/*        
        // Action to be taken once page has loaded
        jQuery(document).ready(function(){
            jQuery('#div_navigators').bind(\"mouseover\", function() {
                getIframeID(jQuery('#IFRAME_content').attr(\"src\"));
            });
        });
        jQuery(document).ready(function(){
		jQuery('#div_navigators a').click(function() {
			getIframeID(this.href);
			return confirm('You are going to visit: ' + this.href);
		});
        });
//jQuery('#rootFolder').attr(\"value\")
*/
	// prepare the form when the DOM is ready 
        jQuery(document).ready(function(){

		jQuery('#span_next').click(function() {
			jQuery('#span_next').html('');
			getNextPage(nextPage,jQuery('#input_rootfolder').attr(\"value\"));
			getPrevPage(nextPage,jQuery('#input_rootfolder').attr(\"value\"));
			
		});
		jQuery('#span_prev').click(function() {
			jQuery('#span_next').html('');
			getNextPage(prevPage,jQuery('#input_rootfolder').attr(\"value\"));
			getPrevPage(prevPage,jQuery('#input_rootfolder').attr(\"value\"));
			
		});
		jQuery('#span_nextb').click(function() {
			jQuery('#span_nextb').html('');
			getNextPage(nextPage,jQuery('#input_rootfolder').attr(\"value\"));
			getPrevPage(nextPage,jQuery('#input_rootfolder').attr(\"value\"));
			
		});
		jQuery('#span_prevb').click(function() {
			jQuery('#span_nextb').html('');
			getNextPage(prevPage,jQuery('#input_rootfolder').attr(\"value\"));
			getPrevPage(prevPage,jQuery('#input_rootfolder').attr(\"value\"));
			
		});

		jQuery('#div_navigators a').click(function() {
			//getIframeID(this.href);
			//getIframeID(fpath);
			//getIframeID(this.href,jQuery('#input_rootfolder').attr(\"value\"));
			getNextPage(this.href,jQuery('#input_rootfolder').attr(\"value\"));
			getPrevPage(this.href,jQuery('#input_rootfolder').attr(\"value\"));
		});
		jQuery('#span_home a').click(function() {
			jQuery('#span_next').html('');
			jQuery('#span_prev').html('');
			getNextPage(this.href,jQuery('#input_rootfolder').attr(\"value\"));
			getPrevPage(this.href,jQuery('#input_rootfolder').attr(\"value\"));
		});
		jQuery('#span_homeb a').click(function() {
			jQuery('#span_next').html('');
			jQuery('#span_prev').html('');
			jQuery('#span_nextb').html('');
			jQuery('#span_prevb').html('');
			getNextPage(this.href,jQuery('#input_rootfolder').attr(\"value\"));
			getPrevPage(this.href,jQuery('#input_rootfolder').attr(\"value\"));
		});

/*
		jQuery('#divContent iframe').load(function() {
			jQuery('#span_prev').html('');
			jQuery('#span_next').html('');
			getNextPage(jQuery('#IFRAME_content').attr(\"src\"),jQuery('#input_rootfolder').attr(\"value\"));
			getPrevPage(jQuery('#IFRAME_content').attr(\"src\"),jQuery('#input_rootfolder').attr(\"value\"));
		});
*/
        });
	function getNextPage(current,fpath)
	{
		var page;
		var folderpath;
                    // DO Ajax
		// prepare the form when the DOM is ready 
                    jQuery.ajax({
                        type: 'GET', 
                        url: 'index.php?', 
                        data: 'module=scorm&action=getNext&page='+current+'&folderpath='+fpath, 
                        success: function(msg){
				nextPage = msg;
                                // IF next page exists
                                if (msg=='omega') {
                                    jQuery('#span_next').html('Last Page');
                                    jQuery('#span_next').addClass('error');
                                    jQuery('#span_next').removeClass('success');			
                                    jQuery('#span_nextb').html('Last Page');
                                    jQuery('#span_nextb').addClass('error');
                                    jQuery('#span_nextb').removeClass('success');			
                                }else{
                                    jQuery('#span_next').html('<a href=\''+nextPage+'\' target = \'content\' id = \'next\'>Next</a>');
                                    jQuery('#span_next').addClass('success');
                                    jQuery('#span_next').removeClass('error');
                                    jQuery('#span_nextb').html('<a href=\''+nextPage+'\' target = \'content\' id = \'next\'>Next</a>');
                                    jQuery('#span_nextb').addClass('success');
                                    jQuery('#span_nextb').removeClass('error');

				}
  	                              
                            }
                    });

	}
	function getPrevPage(current,fpath)
	{
		var page;
		var folderpath;
                    // DO Ajax
		// prepare the form when the DOM is ready 
                    jQuery.ajax({
                        type: 'GET', 
                        url: 'index.php?', 
                        data: 'module=scorm&action=getPrev&page='+current+'&folderpath='+fpath, 
                        success: function(msg){
				prevPage = msg;
                                // IF next page exists
                                if (msg=='alpha') {
                                    jQuery('#span_prev').html('First Page');
                                    jQuery('#span_prev').addClass('error');
                                    jQuery('#span_prev').removeClass('success');			
                                    jQuery('#span_prevb').html('First Page');
                                    jQuery('#span_prevb').addClass('error');
                                    jQuery('#span_prevb').removeClass('success');			
                                }else{
                                    jQuery('#span_prev').html('<a href=\''+prevPage+'\' target = \'content\' id = \'next\'>Previous</a>');
                                    jQuery('#span_prev').addClass('success');
                                    jQuery('#span_prev').removeClass('error');
                                    jQuery('#span_prevb').html('<a href=\''+prevPage+'\' target = \'content\' id = \'next\'>Previous</a>');
                                    jQuery('#span_prevb').addClass('success');
                                    jQuery('#span_prevb').removeClass('error');

				}
  	                              
                            }
                    });

	}

	function getIframeID(el,path)
	{
		jQuery('#contextcodemessage').html(el+' '+path);
                jQuery('#contextcodemessage').addClass('success');

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
	$firstPage = $this->objReadXml->xmlFirstPage($filePath);
	//$navigators = $this->objReadXml->treeMenuXML($filePath);
	$objTable = new htmltable();
	$objTable->width='950px';
	$objTable->height='100%';
	$objTable->attributes=" align='center' border='0'";
	$objTable->cellspacing='5';
	$row = array("<b>".$objLanguage->code2Txt("word_name").":</b>");
	//$objIframe = new iframe();
	//iframe to hold the API
	$apiIFrame = '<iframe src="'.$getApi.'" name="API" height=0 width=10 frameborder=0 scrolling=no></iframe>';
	//iframe to hold the content
	$content = '<iframe id="IFRAME_content" src="'.$firstPage.'" name="content" height=450 width=650 frameborder=0 scrolling=yes></iframe>';
$testNavs = "<div id='divNavs' align = 'center'><span id='span_home'> <a href = '".$firstPage."' target = 'content' id = 'home'> Home</a></span>"." &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <span id='span_prev'>&nbsp</span>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span id='span_next'>&nbsp</next> </div>";
$testNavsB = "<div id='divNavsb' align = 'center'><span id='span_homeb'> <a href = '".$firstPage."' target = 'content' id = 'home'> Home</a></span>"." &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <span id='span_prevb'>&nbsp</span>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span id='span_nextb'>&nbsp</next> </div>";

	// Spacer
	$objTable->startRow();
	    $objTable->addCell($apiIFrame);
	    $objTable->addCell('<div id="div_navigators" class="small-box">'.$navigators."</div>");
//	    $objTable->addCell($readXml);
	    $objTable->addCell('<div class="big-box">'.$testNavs."<br /><div id='divContent' align = 'center'>".$content."</div><br />".$testNavsB).'</div>';
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
