<?php
if (isset ($noContextCode))
{
	echo $noContextCode;
} else {
?>
<!-- START FILE MANAGER FILE CHOOSER CODE-->
<script type="text/javascript">

	function openWindow(theURL,winName,features) { 
	  newwindow=window.open(theURL,winName,features);
       if (window.focus) {
       	newwindow.focus()
	   }
    } 

    /*
     * Function that is called by Java applet when user wants to select an image.
     */
    function openImageFileChooser() {
		openWindow('<?php echo str_replace('amp;', '', $this->uri(array('action'=>'selectrealtimeimagewindow', 'name'=>'selectimage'), 'filemanager')); ?>','new','toolbar=no, menubar=no, width=600, height=400, resizable=yes, scrollbars=auto, toolbar=no top=200 screenY=200 left=300 screenY=300');
    }

	/*
	 * Function that is called by file manager image chooser popup when a file has been selected.
	 */    
    function callFunctionFromParent()
	{
	    var imageURL = document.getElementById('hidden_selectimage').value;
	    document.getElementById("whiteboardapplet").imageSelected(imageURL);
	}
</script>
<div style="display:none;">
<?php

	$objSelectFile = $this->getObject('selectrealtimeimage', 'filemanager');
	$objSelectFile->name = 'selectimage';
	$this->setVar('pageSuppressXML', TRUE);
	echo $objSelectFile->show();
?>
</div>

<!-- END FILE MANAGER FILE CHOOSER CODE-->
             <applet codebase="<?= "http://" . $_SERVER['HTTP_HOST'].'/'.$this->objAltConfig->getModuleUri() ?>/realtime/resources/"
              code="avoir.realtime.classroom.RealtimeClassroomApplet.class"
              archive="avoir-realtime-classroom-0.1.jar,avoir-realtime-common-0.1.jar,avoir-whiteboard-client-0.1.jar" width="700" height="500">
	          <param name=userName value="<?echo $this->objUser->userName()?>">
	          <param name=fullname value="<?echo $this->objUser->fullname()?>">
	          <param name=port value="1981">
              </applet>
         
<?php
}
?>