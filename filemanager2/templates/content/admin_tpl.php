<?php
    $objSysConfig  = $this->getObject('altconfig','config');
    $objExtJS = $this->getObject('extjs','ext');
    $objExtJS->show();

    $this->appendArrayVar('headerParams', '
            <script type="text/javascript">
            var baseuri = "'.$objSysConfig->getsiteRoot().'index.php";
	    var uri = "'.$objSysConfig->getsiteRoot().'";
	    var defId = "'.$folderId.'";
            </script>');

    $ext = $this->getJavaScriptFile('FileUploadField.js', 'filemanager2');
    $ext .= $this->getJavaScriptFile('fileadmin.js', 'filemanager2');
    $ext .= '<link rel="stylesheet" href="'.$this->getResourceUri('fileuploadfield.css', 'filemanager2').'" type="text/css" />';
    
    $ext .= "
	<style>
	#mainpanel{
	float:center;
	margin-top: 70px;
	margin-right: 70px;
	margin-bottom: 70px;
	margin-left: 170px;
	border:1px solid #c3daf9;
	overflow:auto;
	}
	
	html, body {
	font:normal 12px verdana;
	margin:0;
	padding:0;
	border:0 none;
	overflow:hidden;
	height:100%;
	}
	</style>";
        
    $this->appendArrayVar('headerParams', $ext);

    echo '<br/><div id="mainpanel"></div>';
?>
