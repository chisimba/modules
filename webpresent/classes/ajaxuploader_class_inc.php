<?php
// security check - must be included in all scripts
if(!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}
// end of security

class ajaxuploader extends object
{
    
    public function init()
    {
        $this->loadClass('form', 'htmlelements');
        $this->loadClass('textinput', 'htmlelements');
        $this->loadClass('button', 'htmlelements');
        $this->loadClass('label', 'htmlelements');
        $this->loadClass('hiddeninput', 'htmlelements');
    }
    
    public function show()
    {
        $id = mktime().rand();
        
        $objIframe = $this->newObject('iframe', 'htmlelements');
        
        $objIframe->src = $this->uri(array('action'=>'tempiframe', 'id'=>$id));
        $objIframe->id = 'ifra_upload_'.$id;
        $objIframe->name = 'iframe_upload_'.$id;
        $objIframe->frameborder = 1;
        $objIframe->width = 0;
        $objIframe->height = 0;
        $objIframe->extra = ' style="display:none" ';
        
        $objIcon = $this->newObject('geticon', 'htmlelements');
        $objIcon->setIcon('loading_bar');
        
        //$this->appendArrayVar('headerParams', $this->addJavaScript($id));
        
        $form = new form ('uploadfile_'.$id, $this->uri(array('action'=>'doajaxupload')));
        $form->extra = 'enctype="multipart/form-data" target="iframe_upload_'.$id.'"';;
        $form->id = 'form_upload_'.$id;
        
        $fileInput = new textinput('fileupload');
        $fileInput->fldType = 'file';
        $fileInput->extra = 'onchange="changeFileName(\''.$id.'\');"';
        
        $button = new button ('upload', 'Upload');
        $button->setOnClick('doUpload(\''.$id.'\');');
        
        $filename = new hiddeninput('filename', '');
        $hiddenInput = new hiddeninput('id', $id);
        
        $form->addToForm($fileInput->show().' '.$button->show().$filename->show().$hiddenInput->show());
        $this->addJS();
        
        return $form->show().'<div id="uploadresults"></div><div id="updateform"></div>'.$objIframe->show().'<div id="div_upload_'.$id.'" style="display:none;">'.$objIcon->show().' Upload in Progress</div>';
    }
    
    /**
    *
    *
    */
    private function addJS()
    {
        $this->appendArrayVar('headerParams', '<script type="text/javascript">
// <![CDATA[

//window.history.forward(1);
//var par = window.parent.document;

function doUpload(id)
{
    if (document.forms[\'uploadfile_\'+id].fileupload.value == \'\') {
        alert(\'Please select a file\');
    } else {
        document.getElementById(\'form_upload_\'+id).style.display=\'none\';
        document.getElementById(\'div_upload_\'+id).style.display=\'block\';
        document.getElementById(\'uploadresults\').style.display=\'none\';
        document.forms[\'uploadfile_\'+id].submit();
    }
}

function changeFileName(id)
{
    //document.forms[\'uploadfile\'].filename.value = document.forms[\'uploadfile\'].fileupload.value;

    var tr = document.forms[\'uploadfile_\'+id].fileupload.value;
    len = tr.length;
    rs = 0;
    for (i = len; i > 0; i--) {
        vb = tr.substring(i,i+1)
        if (vb == "/" && rs == 0) {
            document.forms[\'uploadfile_\'+id].filename.value = tr.substring(i+1,len);
            rs = 1;
        }
    }
}

// ]]>
</script>');
    }
}
?>