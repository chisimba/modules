<?php

$this->loadClass('form', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');
$this->loadClass('radio', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('button', 'htmlelements');

$objIcon = $this->newObject('geticon', 'htmlelements');
$objIcon->setIcon('loader');

//AJAX to check if selected folder contains scorm
    
    $this->appendArrayVar('headerParams', '
    <script type="text/javascript">
        
        // Flag Variable - Update message or not
        var doUpdateMessage = false;
        
        // Var Current Entered Code
        var currentCode;
        
        // Action to be taken once page has loaded
        jQuery(document).ready(function(){
            jQuery("#input_parentfolder").bind(\'change\', function() {
                checkCode(jQuery("#input_parentfolder").attr(\'value\'));
            });
        });
        
        // Function to check whether context code is taken
        function checkCode(code)
        {
            // Messages can be updated
            doUpdateMessage = true;
            
            // If code is null
            if (code == null) {
                // Remove existing stuff
                jQuery("#contextcodemessage").html("");
                jQuery("#contextcodemessage").removeClass("error");
                jQuery("#input_contextcode").removeClass("inputerror");
                jQuery("#contextcodemessage").removeClass("success");
                doUpdateMessage = false;
                
            // If code is root - Reserved. Saves Ajax Call
            } else if (code.toLowerCase() == "root") {
                
                currentCode = code;
                
                jQuery("#contextcodemessage").html("This folder is reserved. You cannot extract scorm in the ROOT folder.");
                jQuery("#contextcodemessage").addClass("error");
                jQuery("#input_contextcode").addClass("inputerror");
                jQuery("#contextcodemessage").removeClass("success");
                doUpdateMessage = false;
                
            // Else Need to do Ajax Call
            } else {
            
                
                
                // Check that existing code is not in use
                if (currentCode != code) {
                    
                    // Set message to checking
                    jQuery("#contextcodemessage").removeClass("success");
                    jQuery("#contextcodemessage").html("<span id=\"contextcodecheck\">'.addslashes($objIcon->show()).' Checking ...</span>");
                    
                    
                    // Set current Code
                    currentCode = code;
                    
                    // DO Ajax
                    jQuery.ajax({
                        type: "GET", 
                        url: "index.php", 
                        data: "module=scorm&action=checkfolder&code="+code, 
                        success: function(msg){                        
                            // Check if messages can be updated and code remains the same
                            if (doUpdateMessage == true && currentCode == code) {
                                
                                // IF code exists
                                if (msg == "ok") {
                                    jQuery("#contextcodemessage").html("Good! It contains scorm");
                                    jQuery("#contextcodemessage").addClass("success");
                                    jQuery("#contextcodemessage").removeClass("error");
                                    jQuery("#input_parentfolder").removeClass("inputerror");
                                    jQuery("#submitbutton").removeAttr("disabled");

                                // Else
                                } else {
                                    jQuery("#contextcodemessage").html("That folder does not contain a scorm course");
                                    jQuery("#contextcodemessage").addClass("error");
                                    jQuery("#input_parentfolder").addClass("inputerror");
                                    jQuery("#contextcodemessage").removeClass("success");
                                    jQuery("#submitbutton").attr("disabled", "disabled");                                    
                                }
                                
                            }
                        }
                    });
                }
            }
        }
    </script>');

if ($mode == 'edit') {
    $formaction = 'updatescormchapter';
    echo '<h1>'.$this->objLanguage->languageText('mod_contextcontent_editchapter','contextcontent').': '.$chapter['chaptertitle'].'</h1>';
} else {
    echo '<h1>'.$this->objLanguage->languageText('mod_contextcontent_addnewchapterin','contextcontent').' '.$this->objContext->getTitle().'</h1>';
    $formaction = 'savescormchapter';
}
    //echo '<p>Todo: Allow User to place order of chapter</p>';
    
$form = new form ('addscorm', $this->uri(array('action'=>$formaction)));
$table = $this->newObject('htmltable', 'htmlelements');

$title = new textinput('chapter');
$form->addRule('chapter', 'Title is required','required');
$title->size = 60;

if ($mode == 'edit') {
    $title->value = $chapter['chaptertitle'];
}

$label = new label ($this->objLanguage->languageText('mod_scorm_scormtitle','scorm'), 'input_chapter');
$table->startRow();
$table->addCell($label->show(), 150);
$table->addCell($title->show());
$table->endRow();
//spacer
$table->startRow();
$table->addCell("&nbsp;");
$table->addCell("&nbsp;");
$table->endRow();

$label = new label ($this->objLanguage->languageText('mod_scorm_selectscormfolder','scorm'), 'input_scormfolder');
//$htmlArea = $this->newObject('htmlarea', 'htmlelements');
//$htmlArea->name = 'intro';
//$htmlArea->context = TRUE;

if ($mode == 'edit') {
	// name of dropdown = 'parentfolder'
	$usrFolders = $this->objFolders->getTreedropdown($chapter['introduction']);
//    $htmlArea->value = $chapter['introduction'];
} else {
	$usrFolders = $this->objFolders->getTreedropdown(Null);
}

$table->startRow();
$table->addCell($label->show());
//$table->addCell($htmlArea->show());
$table->addCell($usrFolders.' <span id="contextcodemessage">'.$contextCodeMessage.'</span>');
$table->endRow();
//spacer
$table->startRow();
$table->addCell("&nbsp;");
$table->addCell("&nbsp;");
$table->endRow();


$radio = new radio ('visibility');
$radio->addOption('Y', ' '.$this->objLanguage->languageText('word_yes','system', 'Yes'));
$radio->addOption('N', ' '.$this->objLanguage->languageText('word_no','system', 'No'));
$radio->addOption('I', ' '.$this->objLanguage->languageText('mod_contextcontent_onlyshowintroduction','contextcontent'));

if ($mode == 'edit') {
    $radio->setSelected($chapter['visibility']);
} else {
    $radio->setSelected('Y');
}
$radio->setBreakSpace(' &nbsp; ');

$table->startRow();
$table->addCell($this->objLanguage->code2Txt('mod_contextcontent_visibletostudents','contextcontent'));
$table->addCell($radio->show());
$table->endRow();
//spacer
$table->startRow();
$table->addCell("&nbsp;");
$table->addCell("&nbsp;");
$table->endRow();

$form->addToForm($table->show());

    
$hiddeninput = new hiddeninput('scorm', 'Y');
$form->addToForm($hiddeninput->show());


$button = new button('submitbutton', $this->objLanguage->languageText('mod_contextcontent_chapter','contextcontent'));
$button->setToSubmit();
$form->addToForm($button->show());

if ($mode == 'edit') {
    $hiddeninput = new hiddeninput('id', $id);
    $form->addToForm($hiddeninput->show());

    $hiddeninput = new hiddeninput('chaptercontentid', $chapter['id']);
    $form->addToForm($hiddeninput->show());
    
    $hiddeninput = new hiddeninput('contextchapterid', $chapter['contextchapterid']);
    $form->addToForm($hiddeninput->show());
    
}

echo $form->show();

?>
