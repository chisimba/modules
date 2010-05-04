<?php
$objSysConfig  = $this->getObject('altconfig','config');
$objExtJS = $this->getObject('extjs','ext');
//$objQuotas = $this->getObject('dbquotas', 'filemanager');
$objExtJS->show();
$fullUri = $this->uri(NULL);
$fullUri = explode("?",$fullUri);
$siteUri = $fullUri[0];


$this->appendArrayVar('headerParams', '
    <script type="text/javascript">
	var usrQuotas = "'.$this->getFreeSpace('users/'.$this->objUser->userId()).'";
	var cnxtQuotas = "'.$this->getFreeSpace('context/'.$contextId).'";
    var baseuri = "'.$siteUri.'";
    var uri = "'.$objSysConfig->getsiteRoot().'";
    var defId = "'.$folderId.'";
	var contextfolderId = "'.$contextfolderId.'";
	var contextId = "'.$contextId.'";
	</script>');

    $ext = '<link rel="stylesheet" href="'.$this->getResourceUri('iconcss.css', 'filemanager2').'" type="text/css" />';
    $ext .= $this->getJavaScriptFile('StatusBar.js', 'filemanager2');
	$ext .= $this->getJavaScriptFile('BasicDialog.js', 'filemanager2');
    $ext .= $this->getJavaScriptFile('FileUploadField.js', 'filemanager2');
    $ext .= $this->getJavaScriptFile('fileadmin.js', 'filemanager2');
    $ext .= '<link rel="stylesheet" href="'.$this->getResourceUri('fileuploadfield.css', 'filemanager2').'" type="text/css" />';

    $ext .= "
	<style>
	#mainpanel{
	margin-left: auto ;
    margin-right: auto ;
	border:1px solid #c3daf9;
	overflow:auto;
	}
	</style>";

$this->appendArrayVar('headerParams', $ext);

echo '<br/><div id="mainpanel"></div>';
?>
