<?php

$this->setVar('pageSuppressXML', TRUE);

$this->loadClass('iframe', 'htmlelements');

$objAjaxUpload = $this->newObject('ajaxuploader');

echo $objAjaxUpload->show();

$script = '
<script type="text/javascript">
//<![CDATA[

function processConversions() {

id = \'gen8Srv42Nme28_8505_1188284968\';
jsId = \'1\';
	var url = \'index.php\';
	//var pars = \'module=filemanager&action=sendpreview\';
    var pars = \'module=filemanager&action=sendpreview&id=\'+id+\'&jsId=\'+jsId;
	var myAjax = new Ajax.Request( url, {method: \'get\', parameters: pars, onComplete: showResponse} );
}

function showResponse (originalRequest) {
	var newData = originalRequest.responseText;
	$(\'previewwindow\').innerHTML = newData;
}
//]]>
</script>';
//$this->appendArrayVar('headerParams', $script);

?>
<div id="previewwindow" style="border: 1px dashed red; padding: 5px;"></div>

<input type="button" value="asfa" onclick="processConversions();" />

<script type="text/javascript">
//<![CDATA[

function processConversions() {
    window.setTimeout('doConversion();', 2000);
}

function doConversion() {

    var pars = "module=webpresent&action=ajaxprocessconversions";
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