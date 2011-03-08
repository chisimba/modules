<?php
$this->setVar('pageSuppressXML', TRUE);

$this->loadClass('iframe', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');
$header = new htmlheading();
$header->type = 2;
$header->str = $this->objLanguage->languageText('mod_podcaster_uploadsteptwo', 'podcaster', 'Step 1: Select/Create upload folder');
$upPath = $this->objLanguage->languageText('mod_podcaster_uploadpath', 'podcaster', 'Upload path');

echo $header->show();
$path = $folderdata['folderpath'];
$folderid = $folderdata['id'];
echo "<p>".$upPath.": ".$path."</p>";

$objAjaxUpload = $this->newObject('ajaxuploader');

echo $objAjaxUpload->showForm($path, $folderid);


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