<?
    /***
    * Template Page for Uploaded of Email Attachments
    * @author James Scoble
    */
    
    // First we load the class to handle the attachments
    $objTempFile=&$this->getObject('fileupload');
    
    // The emailId variable tells us which email message the attachments will be for.
    $emailId=$this->getParam('emailId');

    // Next, we check for uploads
    $upload=$this->getParam('upload');
    if ($upload==1){
        $userFile=$this->getArrayParam('userFile');
        $userFile=$_FILES['userFile']; // The getArrayParam method seems not to work properly.
        $objTempFile->uploadFile($userFile,$emailId);
    }
    
    // Check for deletions
    $delete=$this->getParam('delete');
    if ($delete==1){
        $fileId=$this->getParam('fileId');
        $objTempFile->eraseFile($fileId,$emailId);
    }
    
  
    // Display files already uploaded
    
    $list=$objTempFile->listFiles($emailId);
    if (count($list)>0){
        $objTableClass=$this->newObject('htmltable','htmlelements');
        $objTableClass->width='200';
        $objTableClass->attributes=" align='center' border=0";
        $objTableClass->cellspacing='2';
        $objTableClass->cellpadding='2';
        foreach ($list as $line)
        {
            $name=$line['filename'];
            $fileId=$line['fileId'];
            $link="<a href='".$this->uri(array('action'=>'fileupload','emailId'=>$emailId,'delete'=>'1','fileId'=>$fileId))."' class='pseudobutton'>";
            $link.=$this->objLanguage->languageText('word_delete')."</a>";
            $objTableClass->addRow(array($name,$link));
        }
        print $objTableClass->show();
    }
    
    // Now, finally, the form for uploading
    $this->objInput=$this->getObject('textinput','htmlelements');
    
    $upload="<form name='fileupload' enctype='multipart/form-data' method='POST' action='".$this->uri(array('action'=>'fileupload'))."'>\n";
    $upload.=hidden('upload','1');
    $upload.=hidden('module','email');
    $upload.=hidden('action','fileupload');
    $upload.=hidden('time',time());
    $upload.=hidden('emailId',$emailId);
    
    $objTableClass=$this->newObject('htmltable','htmlelements');
    $objTableClass->width='200';
    $objTableClass->attributes=" align='center' border=0";
    $objTableClass->cellspacing='2';
    $objTableClass->cellpadding='2';

    $input1="<input type='file' name='userFile'>\n";
    $input2="<input type='submit' class='button' value='".$this->objLanguage->languageText('word_upload')."'>\n";


    $objTableClass->addRow(array($input1));
    $objTableClass->addRow(array($input2));
    
    $upload.=$objTableClass->show();

    $upload.="</form>\n";

    print $upload;

    /**
    * method to produce a hidden form field
    */
    function hidden($name,$value)
    {
        $textobj=new textinput($name);
        $textobj->setValue($value);
        $textobj->name=$name;
        $textobj->fldType='hidden';
        return $textobj->show()."\n";
    }

?>
