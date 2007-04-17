<script type="text/javascript">
//<![CDATA[
function init () {
	$('input_redraw').onclick = function () {
		redraw();
	}
}
function redraw () {
	var url = 'index.php';
	var pars = 'module=security&action=generatenewcaptcha';
	var myAjax = new Ajax.Request( url, {method: 'get', parameters: pars, onComplete: showResponse} );
}
function showLoad () {
	$('load').style.display = 'block';
}
function showResponse (originalRequest) {
	var newData = originalRequest.responseText;
	$('captchaDiv').innerHTML = newData;
}
//]]>
</script>
<?php
$objmsg = &$this->getObject('timeoutmessage', 'htmlelements');
$this->loadClass('href', 'htmlelements');
$cssLayout = &$this->newObject('csslayout', 'htmlelements');
$objSideBar = $this->newObject('usermenu', 'toolbar');
$objFeatureBox = $this->newObject('featurebox', 'navigation');

// Set columns to 3
$cssLayout->setNumColumns(3);

$leftMenu = NULL;
$rightSideColumn = NULL;
$leftCol = NULL;
$middleColumn = NULL;

//check for messages...
if ($msg == 'save') {
    $objmsg->message = $this->objLanguage->languageText('mod_feedback_recsaved', 'feedback');
    echo $objmsg->show();
} elseif($msg = 'nodata') {
	$objmsg->message = $this->objLanguage->languageText('mod_feedback_elaborate', 'feedback');
    echo $objmsg->show();
    $msg = NULL;
}
else {
	$msg = NULL;
}

if($this->objUser->isLoggedIn())
{
	$leftCol .= $objSideBar->show();
	
}
else {
	$leftCol = $this->objFb->loginBox(TRUE);
	
}
$middleColumn = $this->objFb->dfbform();

$cssLayout->setMiddleColumnContent($middleColumn);
$cssLayout->setLeftColumnContent($leftCol); //$leftMenu->show());
$cssLayout->setRightColumnContent($rightSideColumn);

echo $cssLayout->show();
?>