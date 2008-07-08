<?php

$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('form', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('textarea','htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('radio', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');

//Load jquery
$objJQuery = $this->getObject('jquery','htmlelements');
$objJQuery->loadFormPlugin();

$objIcon = $this->newObject('geticon', 'htmlelements');
$objIcon->setIcon('loader');

    $formAction = 'NONE';
    $headerTitle = $this->objLanguage->code2Txt('phrase_uploadfile', 'system', NULL, NULL);
    $fixup = $this->getSession('fixup', NULL);
    
    $this->appendArrayVar('headerParams', '
    <script type="text/javascript">
        
        // Flag Variable - Update message or not
        var doUpdateMessage = false;
        
        // Var Current Entered Code
        var currentCode;
        
        // Action to be taken once page has loaded
        jQuery(document).ready(function(){
            jQuery("#input_fileupload").bind(\'keyup\', function() {
            	uploadfile(jQuery("#input_fileupload").attr(\'value\'));
            });
        });
	function uploadfile(code)
	{
	        
	        // If code is null
	        if (code == null) {
		        // Remove existing stuff
		        jQuery("#fileuploadmessage").html("");
		        jQuery("#fileuploadmessage").removeClass("error");
		        jQuery("#input_fileupload").removeClass("inputerror");
		        jQuery("#fileuploadmessage").removeClass("success");
		        doUpdateMessage = false;
		}else{	  
            
	                
	                
	                // Check that existing code is not in use
	                if (currentCode != code) {
	  			//document.write(code);                  
	                    // Set message to checking
	                    jQuery("#fileuploadmessage").removeClass("success");
	                    jQuery("#fileuploadmessage").html("<span id=\"contextcodecheck\">'.addslashes($objIcon->show()).' Uploading...</span>");  
	                // Set current Code
	                    currentCode = code;
		        //data: "module=codetesting&action=uploadfileajax&fileupload="+code,	      
		                    
		        // DO Ajax
			//jQuery.ajax
			//jQuery.ajax({
		        jQuery("#form_uploadform").ajaxForm({
		        type: "GET", 
		        url: "index.php", 
			data: "module=filemanager&action=uploadreadajax&fileupload",
		        success: function(msg){  

//Start Remove
                                // IF code exists
                                if (msg == "failed") {
                                    jQuery("#fileuploadmessage").html("Ooops, file upload failed. Please try again.");
                                    jQuery("#fileuploadmessage").addClass("error");
                                    jQuery("#input_fileupload").addClass("inputerror");
                                    jQuery("#fileuploadmessage").removeClass("success");
                                    jQuery("#savebutton").attr("disabled", "disabled");
                                    
                                // Else
                                } else {
                                    jQuery("#fileuploadmessage").html("File uploaded succesfully");
                                    jQuery("#fileuploadmessage").addClass("success");
                                    jQuery("#fileuploadmessage").removeClass("error");
                                    jQuery("#input_fileupload").removeClass("inputerror");
                                    jQuery("#savebutton").removeAttr("disabled");
                                }

//end Remove
/*                      
		                        jQuery("#fileuploadmessage").html("It ought to have uploaded???");
		                        jQuery("#fileuploadmessage").addClass("success");
		                        jQuery("#fileuploadmessage").removeClass("error");
		                        jQuery("#input_fileupload").removeClass("inputerror");
		                        jQuery("#savebutton").removeAttr("disabled");

*/
		                }
		        });
			}
		}

        }
    </script>');

$header = new htmlheading();
$header->type = 1;
$header->str = ucwords($headerTitle);

echo '<br />'.$header->show();


// CREATE FORM
$form = new form ('uploadform', $this->uri(array('action'=>$formAction)));


//$code = new textinput('contextcode',null,'file',null);
//textinput($name = null, $value = null, $type=null, $size=null)
$code = new textinput('fileupload',null,'file',60);
//$code->setId('myfileupload');
$codeLabel = new label (ucwords($this->objLanguage->code2Txt('mod_filemanager_selectfile', 'filemanager', NULL, Null)), 'input_fileupload');


$title = new textarea('title', null, 10, 100);
$title ->setId('markItUp');
$titleLabel = new label ($this->objLanguage->languageText('phrase_uploadresults', 'system', 'Title'), 'input_title');


$button = new button ('savecontext', $this->objLanguage->languageText('word_save', 'system', NULL));
$button->cssId = 'savebutton';
$button->setToSubmit();

$table = $this->newObject('htmltable', 'htmlelements');


    $table->startRow();
    $table->addCell($codeLabel->show(), 100);
    $table->addCell($code->show().' <span id="fileuploadmessage">'.$contextCodeMessage.'</span>');
    $table->endRow();

$table->startRow();
$table->addCell($titleLabel->show());
$table->addCell($title->show());
$table->endRow();

$table->startRow();
$table->addCell('&nbsp;');
$table->addCell('&nbsp;');
$table->endRow();
$form->addToForm($table->show().'<p><br />'.$button->show().'</p>');

$hiddenInput = new hiddeninput('mode', $mode);
$form->addToForm($hiddenInput->show());

echo $form->show();

?>
