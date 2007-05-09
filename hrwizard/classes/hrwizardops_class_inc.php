<?php

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}

class hrwizardops extends object {
	
	public function init()
	{
		$this->objLanguage = $this->getObject('language', 'language');
		$this->objConfig = $this->getObject('altconfig', 'config');
		$this->objMailer = $this->getObject('email', 'mail');
		
	}
	
	public function parseCSV($csvfile)
	{
		$path = $this->objConfig->getcontentBasePath()."hrtmp/";
    	if(!file_exists($path))
    	{
    		mkdir($path, 0777);
    		chmod($path, 0777);
    	}
    	//ok, now unpack the files to the dir
    	$objFile = $this->getObject('dbfile', 'filemanager');
		$csv = $objFile->getFullFilePath($csvfile);
		$csvname = $objFile->getFileName($csvfile);
		require_once "File/Archive.php"; 
		File_Archive::extract($csv,$path);
		chdir($path);
		
		$row = 1;
		$handle = fopen($csv, "r");
		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
    		$num = count($data);
    		$row++;
    		$arr[] = $data;
		}
		fclose($handle);
		
		return $arr;
	}
	
	public function uploadDataFile($featurebox = TRUE)
    {
    	$this->loadClass('href', 'htmlelements');
        $this->loadClass('label', 'htmlelements');
        $objSelectFile = $this->newObject('selectfile', 'filemanager');
    	$this->objUser = $this->getObject('user', 'security');
    	
    	$fileform = new form('uploaddatafile', $this->uri(array(
            	'action' => 'uploaddatafile'
        	)));
        	
        //start a fieldset
        $filefieldset = $this->getObject('fieldset', 'htmlelements');
        $fileadd = $this->newObject('htmltable', 'htmlelements');
        $fileadd->cellpadding = 3;

        //file textfield
        $fileadd->startRow();
        $filelabel = new label($this->objLanguage->languageText('mod_hrwizard_file', 'hrwizard') .':', 'input_file');
        
        $objSelectFile->name = 'zipfile';
        $objSelectFile->restrictFileList = array('zip');
                
        $fileadd->addCell($filelabel->show());
        $fileadd->addCell($objSelectFile->show());
        $fileadd->endRow();
        
        //end off the form and add the buttons
        $this->objIMButton = &new button($this->objLanguage->languageText('word_next', 'hrwizard'));
        $this->objIMButton->setValue($this->objLanguage->languageText('word_next', 'hrwizard'));
        $this->objIMButton->setToSubmit();
        $filefieldset->addContent($fileadd->show());
        $fileform->addToForm($filefieldset->show());
        $fileform->addToForm($this->objIMButton->show());
        $fileform = $fileform->show();
        
        if ($featurebox == TRUE) {
            $objFeatureBox = $this->getObject('featurebox', 'navigation');
            $ret = $objFeatureBox->showContent($this->objLanguage->languageText("mod_hrwizard_uploadpdfzipfile", "hrwizard") , $fileform);
            return $ret;
        } else {
            return $fileform;
        }

    }
    
    public function unpackPdfs($fileid)
    {
    	//check if the temp directory exists or not..
    	$path = $this->objConfig->getcontentBasePath()."hrtmp/";
    	if(!file_exists($path))
    	{
    		mkdir($path, 0777);
    		chmod($path, 0777);
    	}
    	//ok, now unpack the files to the dir
    	$objFile = $this->getObject('dbfile', 'filemanager');
		$pdfzip = $objFile->getFullFilePath($fileid);
		$zipname = $objFile->getFileName($fileid);
		//echo $pdfzip;
		
		require_once "File/Archive.php"; 
		File_Archive::extract($pdfzip,$path);
		chdir($path);
		exec('unzip -o '.$pdfzip);
		//cleanup
		unlink($path.$zipname);
		return;
    }
	
	public function uploadCSVFile($featurebox = TRUE)
    {
    	$this->loadClass('href', 'htmlelements');
        $this->loadClass('label', 'htmlelements');
        $objSelectFile = $this->newObject('selectfile', 'filemanager');
    	$this->objUser = $this->getObject('user', 'security');
    	
    	$fileform = new form('uploadcsvfile', $this->uri(array(
            	'action' => 'uploadcsvfile'
        	)));
        	
        //start a fieldset
        $filefieldset = $this->getObject('fieldset', 'htmlelements');
        $fileadd = $this->newObject('htmltable', 'htmlelements');
        $fileadd->cellpadding = 3;

        //file textfield
        $fileadd->startRow();
        $filelabel = new label($this->objLanguage->languageText('mod_hrwizard_file', 'hrwizard') .':', 'input_file');
        
        $objSelectFile->name = 'csvfile';
        $objSelectFile->restrictFileList = array('csv');
                
        $fileadd->addCell($filelabel->show());
        $fileadd->addCell($objSelectFile->show());
        $fileadd->endRow();
        
        //end off the form and add the buttons
        $this->objIMButton = &new button($this->objLanguage->languageText('word_next', 'hrwizard'));
        $this->objIMButton->setValue($this->objLanguage->languageText('word_next', 'hrwizard'));
        $this->objIMButton->setToSubmit();
        $filefieldset->addContent($fileadd->show());
        $fileform->addToForm($filefieldset->show());
        $fileform->addToForm($this->objIMButton->show());
        $fileform = $fileform->show();
        
        if ($featurebox == TRUE) {
            $objFeatureBox = $this->getObject('featurebox', 'navigation');
            $ret = $objFeatureBox->showContent($this->objLanguage->languageText("mod_hrwizard_uploadcsvfile", "hrwizard") , $fileform);
            return $ret;
        } else {
            return $fileform;
        }

    }
    
    public function sendMails($recarr, $bodyText)
    {
    	foreach($recarr as $record)
    	{
    		//get the pdf associated with the employee number
    		$path = $this->objConfig->getcontentBasePath()."hrtmp/";
    		$file = $record[0] . ".pdf";
    		//print_r($record);
    		if(file_exists($file))
    		{
    			$objMailer = $this->getObject('email', 'mail');
				$objMailer->setValue('to', array($record[3]));
				$objMailer->setValue('from', 'hr@uwc.ac.za');
				$objMailer->setValue('fromName', $this->objLanguage->languageText("mod_hrwizard_emailfromname", "hrwizard"));
				$objMailer->setValue('subject', $this->objLanguage->languageText("mod_hrwizard_emailsub", "hrwizard"));
				$objMailer->setValue('body', $bodyText);
				//$objMailer->send();
    		}
    	}
    }

}
?>