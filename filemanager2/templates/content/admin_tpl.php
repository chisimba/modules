<?php
    $objSysConfig  = $this->getObject('altconfig','config');
    $objExtJS = $this->getObject('extjs','htmlelements');
    $objExtJS->show();

    $this->appendArrayVar('headerParams', '
            <script type="text/javascript">
            var baseuri = "'.$objSysConfig->getsiteRoot().'index.php";
	    var uri = "'.$objSysConfig->getsiteRoot().'";
	    var defId = "'.$folderId.'";
            </script>');

    $ext = $this->getJavaScriptFile('FileUploadField.js', 'filemanager2');
    $ext .= $this->getJavaScriptFile('fileadmin.js', 'filemanager2');
    
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
    }";
        
    $this->appendArrayVar('headerParams', $ext);

    echo '<br/><center><div id="mainpanel"></div><center>';
	/*'<div id="west" class="x-hide-display"></div>
    	<div id="center2" class="x-hide-display"><a id="hideit" href="#">Toggle the west region</a><hr></div>
    	<div id="center1" class="x-hide-display"></div>
    	<div id="props-panel" class="x-hide-display" style="width:200px;height:200px;overflow:hidden;"></div>
	<div id="south" class="x-hide-display"></div><br/><br/>';*/
?>
