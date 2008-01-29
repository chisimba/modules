<?php
/*
* Download page for essay.
* @package essay
*/

/**
* Page to display dialog for downloading a file
* @author James Scoble, Abdurahim Shariff
*/
/*
		$this->objFiles = $this->getObject('dbfile','filemanager');
		$this->objConfig = $this->getObject('altconfig', 'config');
		$this->objCleanUrl = $this->getObject('cleanurl','filemanager');
		
	$fileId=$this->getParam('fileid');
        $file = $this->objFiles->getFileInfo($fileId);
        if ($file == FALSE) {
            die('No Record of Such a File Exists.');
        }

        $filePath = $this->objConfig->getcontentPath().$file['path'];

        $this->objCleanUrl->cleanUpUrl($filePath);

        // To do: Build in Security on whether user can view file
        if (file_exists($filePath)) {
            header("Location:{$filePath}");
        } else {
            die ('File does not exist');
        }
*/
$this->objConfig = $this->getObject('altconfig', 'config');
$filePath = $this->objConfig->getcontentPath();
echo $filePath;
?>
