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
     * Function that is called by Java applet when user wants to select a presentation.
     */
    function openPresentationFileChooser() {
      openWindow('<?php echo str_replace('amp;', '', $this->uri(array('action'=>'selectpresentationwindow', 'name'=>'selectpresentation'), 'filemanager')); ?>','new','toolbar=no, menubar=no, width=600, height=400, resizable=yes, scrollbars=auto, toolbar=no top=200 screenY=200 left=300 screenY=300');
    }

    /*
     *Function that is called by file manager image chooser popup when a file has been selected.
     */    
    function passImageUrlToApplet(imageURL)
    {
       // alert(imageURL);
    // var imageURL = document.getElementById('hidden_selectimage').value;
    document.RealtimeClassroomApplet.selectImage(imageURL);
    }

    /*
     *Function that is called by file manager presentation chooser popup when a file has been selected.
     */    
    function passPresentationUrlToApplet(presentationURL,presentationPath,presentationId)
    {
     //var presentationURL = document.getElementById('hidden_selectpresentation').value;
    document.RealtimeClassroomApplet.selectPresentation(presentationURL,presentationPath,presentationId);
    }
    </script>
   <div style="display:none;">
   <?php

    $objSelectFile = $this->getObject('selectfile', 'filemanager');
    $objSelectFile->name = 'selectimage';
    $this->setVar('pageSuppressXML', TRUE);
    echo $objSelectFile->show();
?>
</div>

<!-- END FILE MANAGER FILE CHOOSER CODE-->
<?
    $modPath=$this->objAltConfig->getModulePath();
    $replacewith="";
    $docRoot=$_SERVER['DOCUMENT_ROOT'];
    $appletPath=str_replace($docRoot,$replacewith,$modPath);
    $appletCodeBase="http://" . $_SERVER['HTTP_HOST']."/".$appletPath.'/realtime/resources/';
    $objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
    $port=$objSysConfig->getValue('WHITEBOARDPORT', 'realtime');
    $linuxJMFPathLib=$modPath.'/realtime/resources/jmf-linux-i586/lib/';
    $linuxJMFPathBin=$modPath.'/realtime/resources/jmf-linux-i586/bin/';
    //echo $appletCodeBase;
    echo '<applet codebase="'.$appletCodeBase.'"';
    echo 'code="avoir.realtime.classroom.RealtimeClassroomApplet.class" name ="RealtimeClassroomApplet"';
    echo 'archive="avoir-realtime-classroom-0.1.jar,avoir-realtime-common-0.1.jar,avoir-whiteboard-client-0.1.jar" width="100%" height="600">';
    echo '<param name=userName value="'.$this->objUser->userName().'">';
    echo '<param name=fullname value="'.$this->objUser->fullname().'">';
    echo '<param name=userLevel value="'.$this->userLevel.'">';
    echo '<param name=linuxJMFPathLib value="'.$linuxJMFPathLib.'">';    
    echo '<param name=linuxJMFPathBin value="'.$linuxJMFPathBin.'">';
    echo '<param name=port value="'.$port.'">';
    echo '</applet>';
         

}
?>
