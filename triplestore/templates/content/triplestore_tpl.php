<?php
$objExtJS = $this->getObject('extjs','ext');
$objExtJS->show();

$fullUri = $this->uri(NULL);
$fullUri = explode("?" ,$fullUri);
$siteUri = $fullUri[0];

$halfUri = explode("index.php" ,$siteUri);
$halfUri = $halfUri[0];

$this->appendArrayVar('headerParams', '
    <script type="text/javascript">
	var baseuri = "'.$siteUri.'";
    var uri = "'.$halfUri.'";
    </script>');

    
	$ext = '<link rel="stylesheet" href="'.$this->getResourceUri('iconcss.css', 'triplestore').'" type="text/css" />';
    $ext .= $this->getJavaScriptFile('ext-3.0-rc2/ux/fileuploadfield/FileUploadField.js', 'ext');
    $ext .= '<link rel="stylesheet" href="'.$this->getResourceUri('ext-3.0-rc2/ux/fileuploadfield/css/fileuploadfield.css', 'ext').'" type="text/css" />';
	$ext .= $this->getJavaScriptFile('triplestore.js', 'triplestore');

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
