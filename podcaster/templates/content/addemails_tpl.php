<?php
$this->loadClass('form', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('link', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass("htmltable", 'htmlelements');
$this->loadClass("textinput", "htmlelements");
$this->loadClass("hiddeninput", "htmlelements");

$this->appendArrayVar('headerParams', '<script type="text/javascript">
// <![CDATA[

function sendMail(id)
{
    if (document.forms[\'sendmailconfirm_\'+id].useremail.value == \'\') {
        alert(\'Please select a file\');
    } else {
        document.getElementById(\'form_sendemailconfirm_\'+id).style.display=\'none\';
        document.getElementById(\'div_email_\'+id).style.display=\'block\';
        document.getElementById(\'sendmailresults\').style.display=\'none\';
        document.forms[\'sendmailconfirm_\'+id].submit();
    }
}

function changeEmailAddress(id)
{
    document.forms[\'sendmailconfirm\'].filename.value = document.forms[\'sendmailconfirm\'].useremail.value;

    var tr = document.forms[\'sendmailconfirm_\'+id].useremail.value;
    len = tr.length;
    rs = 0;
    for (i = len; i > 0; i--) {
        vb = tr.substring(i,i+1)
        if (vb == "/" && rs == 0) {
            document.forms[\'sendmailconfirm_\'+id].filename.value = tr.substring(i+1,len);
            rs = 1;
        }
    }
}

// ]]>
</script>');

$header = new htmlHeading();
$header->str = $this->objLanguage->languageText('mod_podcaster_emailrssnote', 'podcaster', 'Email RSS notification');
$header->type = 1;

echo $header->show();

// Generate an ID - In case multiple uploads occur on one page
$id = mktime() . rand();

// Generate Iframe
$objIframe = $this->newObject('iframe', 'htmlelements');

$objIframe->src = $this->uri(array('action' => 'tempiframe', 'id' => $id));
$objIframe->id = 'ifra_upload_' . $id;
$objIframe->name = 'iframe_upload_' . $id;
$objIframe->frameborder = 1;
$objIframe->width = 600;
$objIframe->height = 400;
$objIframe->extra = ' style="display:none" ';

// Create Loading Icon - Hidden by Default
$objIcon = $this->newObject('geticon', 'htmlelements');
$objIcon->setIcon('loading_bar');

$form = new form('sendmailconfirm_' . $id, $this->uri(array('action' => 'doAjaxSendMail')));
$form->extra = 'enctype="multipart/form-data"';
$form->id = 'form_sendemailconfirm_' . $id;
$objTable = new htmltable();
$objTable->width = '100%';
$objTable->attributes = " align='left' border='1'";
$objTable->cellspacing = '5';

//Title
$usremail = new textinput("useremail", $useremail);
$usremail->size = 60;
$usremail->extra = 'onchange="changeEmailAddress(\'' . $id . '\');"';

$ffolderid = new hiddeninput("folderid", $folderid);
$ccreatecheck = new hiddeninput("createcheck", $createcheck);
$genId = new hiddeninput('id', $id);
$ppath = new hiddeninput("path", $path);

$buttonLabel = $this->objLanguage->languageText('word_next', 'system', 'System') . " " . $this->objLanguage->languageText('mod_podcaster_wordstep', 'podcaster', 'Step');
$buttonNote = $this->objLanguage->languageText('mod_podcaster_clickthreefromemail', 'podcaster', 'Click on the "Next step" button to send the emails and proceed to upload podcast');
$emailDesc = $this->objLanguage->languageText('mod_podcaster_emailtip', 'podcaster', 'You can type in multiple emails by seperating them with a comma i.e. john@gmail.com,mark@facebook.com');

//Save button
$button = new button("submit", $buttonLabel);
$button->setOnClick('sendMail(\'' . $id . '\');');
//$button->setToSubmit();

$objTable->startRow();
$objTable->addCell("* " . $this->objLanguage->languageText('mod_podcaster_emailadd', 'podcaster', 'Email address') . " :", 100, 'top', 'right');
$objTable->addCell($usremail->show() . $ffolderid->show() . $ccreatecheck->show() . $ppath->show() . $genId->show(), Null, 'top', 'left');
$objTable->addCell("**" . $button->show(), Null, 'top', 'right');
$objTable->endRow();

$errormsg = $this->objLanguage->languageText('mod_podcaster_validemailadd', 'podcaster', 'You need to type in a valid email address to proceed');
//Validate email
//$form->addRule('useremail', $errormsg, 'email');

$objTable->startRow();
$objTable->addCell("* " . $emailDesc, Null, 'top', 'left', '', 'colspan="3"');
$objTable->endRow();
$objTable->startRow();
$objTable->addCell("** " . $buttonNote, Null, 'top', 'left', '', 'colspan="3"');
$objTable->endRow();

$form->addToForm($objTable->show());

echo $form->show() . '<div id="div_email_' . $id . '" style="display:none;"> Sending mail ' . $objIcon->show() . '</div><div id="sendmailresults"></div><div id="updateform"></div>' . $objIframe->show();
?>

<script type="text/javascript">
    //<![CDATA[

    function loadAjaxForm(fileid) {
        window.setTimeout('loadForm("'+fileid+'");', 1000);
    }

    function loadForm(fileid) {

        var pars = "module=podcaster&action=ajaxprocess&id="+fileid;
        new Ajax.Request('index.php',{
            method:'get',
            parameters: pars,
            onSuccess: function(transport){
                var response = transport.responseText || "no response text";
                $('updateform').innerHTML = response;
            },
            onFailure: function(transport){
                var response = transport.responseText || "no response text";
                //alert('Could not download module: '+response);
            }
        });
    }

    function processConversions() {
        window.setTimeout('doConversion();', 2000);
    }

    function doConversion() {

        var pars = "module=podcaster&action=ajaxprocessconversions";
        new Ajax.Request('index.php',{
            method:'get',
            parameters: pars,
            onSuccess: function(transport){
                var response = transport.responseText || "no response text";
                //alert(response);
            },
            onFailure: function(transport){
                var response = transport.responseText || "no response text";
                //alert('Could not download module: '+response);
            }
        });
    }
    //]]>
</script>