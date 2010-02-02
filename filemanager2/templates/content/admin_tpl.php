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

        #itree{
    	float:left;
    	margin:20px;
    	border:1px solid #c3daf9;
    	overflow:auto;
    }
    </style>";



    $ext .= "<style>
	
	html, body {
        font:normal 12px verdana;
        margin:0;
        padding:0;
        border:0 none;
        overflow:hidden;
        height:100%;
    }
    p {
        margin:5px;
    }
    .settings {
        background-image:url(../shared/icons/fam/folder_wrench.png);
    }
    .nav {
        background-image:url(../shared/icons/fam/folder_go.png);
    }</style>";
        
    $this->appendArrayVar('headerParams', $ext);

    echo '<br/><center><div id="mainpanel"></div><center>';
?>
