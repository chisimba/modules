<?php

class geoops extends object 
{
	
	public function init()
	{
		$this->objLanguage = $this->getObject('language', 'language');
		$this->objConfig = $this->getObject('altconfig', 'config');
		$this->objMailer = $this->getObject('email', 'mail');
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
        $filelabel = new label($this->objLanguage->languageText('mod_geonames_file', 'geonames') .':', 'input_file');
        
        $objSelectFile->name = 'zipfile';
        $objSelectFile->restrictFileList = array('zip');
                
        $fileadd->addCell($filelabel->show());
        $fileadd->addCell($objSelectFile->show());
        $fileadd->endRow();
        
        //end off the form and add the buttons
        $this->objIMButton = &new button($this->objLanguage->languageText('word_upload', 'system'));
        $this->objIMButton->setValue($this->objLanguage->languageText('word_upload', 'system'));
        $this->objIMButton->setToSubmit();
        $filefieldset->addContent($fileadd->show());
        $fileform->addToForm($filefieldset->show());
        $fileform->addToForm($this->objIMButton->show());
        $fileform = $fileform->show();
        
        if ($featurebox == TRUE) {
            $objFeatureBox = $this->getObject('featurebox', 'navigation');
            $ret = $objFeatureBox->showContent($this->objLanguage->languageText("mod_geonames_uploadzipfile", "geonames") , $fileform);
            return $ret;
        } else {
            return $fileform;
        }

    }
    
    public function unpackPdfs($fileid)
    {
    	//ok, now unpack the files to the dir
    	$objFile = $this->getObject('dbfile', 'filemanager');
		$geozip = $objFile->getFullFilePath($fileid);
		$zipname = $objFile->getFileName($fileid);
		//echo $geozip, $zipname; die();
		
		exec('unzip -o '.$geozip);
		//cleanup
		//unlink($geozip);
		$file = explode(".",$zipname);
		$file = $file[0].".txt";
		return $file;
    }
    
    public function parseCSV($csvfile)
	{
		$row = 1;
		$handle = fopen($csvfile, "r");
		while (($data = fgetcsv($handle, 1000, "\t")) !== FALSE) {
    		$num = count($data);
    		$row++;
    		$arr[] = $data;
		}
		fclose($handle);
		return $arr;
		
	}	
}
