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
	var cnxtQuotas = "'.$this->getFreeSpace('context/'.$contextfolderid).'";
        var baseuri = "'.$siteUri.'";
        var uri = "'.$objSysConfig->getsiteRoot().'";
        var defId = "'.$folderId.'";
	var myFilesId = "'.$folderId.'";
        var contextId = "'.$contextfolderid.'";
	</script>');

$ext = '<link rel="stylesheet" href="'.$this->getResourceUri('iconcss.css', 'filemanager2').'" type="text/css" />';
$ext .= $this->getJavaScriptFile('StatusBar.js', 'filemanager2');
$ext .= $this->getJavaScriptFile('BasicDialog.js', 'filemanager2');
$ext .= $this->getJavaScriptFile('FileUploadField.js', 'filemanager2');
$ext .= $this->getJavaScriptFile('myfiles.js', 'filemanager2');
$ext .= $this->getJavaScriptFile('context.js', 'filemanager2');
$ext .= $this->getJavaScriptFile('fileupload.js', 'filemanager2');
$ext .= $this->getJavaScriptFile('filebrowser.js', 'filemanager2');
$ext .= $this->getJavaScriptFile('main.js', 'filemanager2');
$ext .= '<link rel="stylesheet" href="'.$this->getResourceUri('fileuploadfield.css', 'filemanager2').'" type="text/css" />';

$this->appendArrayVar('headerParams', $ext);

echo '<br/><div id="filemanager2-mainpanel"></div>';
?>
