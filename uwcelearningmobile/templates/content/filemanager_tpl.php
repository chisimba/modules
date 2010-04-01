<?php
//File manager tamplates
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
	    die("You cannot view this page directly");
}
	$this->loadClass('fieldset','htmlelements');
	$this->loadClass('link','htmlelements');
	$objTable = $this->newObject('htmltable', 'htmlelements');
	
	echo '<br><b>'.$this->objLanguage->languageText('mod_uwcelearningmobile_wordfilemanager', 'uwcelearningmobile').'</b>';
	$fileSize = new formatfilesize();
	$objFields = new fieldset();
	$oicon = '<img src="skins/_common/icons/folder.gif" border="0" alt="folder" title="folder">';
	$uicon = '<img src="skins/_common2/css/images/sexybuttons/icons/silk/arrow_up.png" border="0" alt="up" title="up">';
	$ficon = '<img src="skins/_common/icons/filetypes/folder.gif" border="0" alt="folder" title="folder">';
	$objFields->setLegend('<b>'.$this->objLanguage->languageText('mod_uwcelearningmobile_wordmyfiles', 'uwcelearningmobile').'</b>');
	
	$objFields->addContent($currname.$oicon);
	if($currfolder != null)
	{
		$link = new link($this->URI(array('action' => 'filemanager', 'folderid' => $currfolder)));
		$link->link = $uicon;
		$objFields->addContent('Up'.$link->show());
	}
	$objTable->startHeaderRow();	
	$objTable->addHeaderCell('<strong>'.$this->objLanguage->languageText('mod_uwcelearningmobile_wordname', 'uwcelearningmobile').'</strong>', '40%');
	$objTable->addHeaderCell('<strong>'.$this->objLanguage->languageText('mod_uwcelearningmobile_wordsize', 'uwcelearningmobile').'</strong>', '40%');
	$objTable->endHeaderRow();
	
	if(empty($files) && empty($folders)){
			$norec = $this->objLanguage->languageText('mod_uwcelearningmobile_wordnofiles', 'uwcelearningmobile');
			$objTable->startRow();
			$objTable->addCell($norec, '', 'center', 'center', '', '');
			$objTable->endRow();
	}
	else{
	 	foreach($folders as $folder)
		{	
			$link = new link($this->URI(array('action' => 'filemanager', 'folderid' => $folder['id'])));
			$link->link = basename($folder['folderpath']);
			$objTable->startRow();
			$objTable->addCell($ficon.' '.$link->show(), '', '', '', '', '');
			$objTable->addCell($this->objLanguage->languageText('mod_uwcelearningmobile_wordfolder', 'uwcelearningmobile'), '', '', '', '', '');
			$objTable->endRow();
		}
		foreach($files as $file)
		{	$fileDownloadPath = $this->objSysConfig->getcontentPath().$file['path'];
			$fileDownloadPath = $this->objCleanUrl->cleanUpUrl($fileDownloadPath);
			$link = new link($fileDownloadPath);
			$link->link = substr($file['filename'], 0, 60) . '...';
			$icon = $this->objFileIcons->getFileIcon($file['filename']);
			$objTable->startRow();
			$objTable->addCell($icon.' '.$link->show(), '', '', '', '', '');
			$objTable->addCell($fileSize->formatsize($file['filesize']), '', '', '', '', '');
			$objTable->endRow();
		}
	}
	$objFields->addContent($objTable->show().'<br/>');
	echo $objFields->show();






/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$objTable = $this->newObject('htmltable', 'htmlelements');	
	if ($this->contextCode != NULL)
		{
		$objFields = new fieldset();
		$objFields->setLegend('<b>'.$this->objLanguage->languageText('mod_uwcelearningmobile_wordcoursefiles', 'uwcelearningmobile').'</b>');
	
		$objFields->addContent($coursecurrname.$oicon);
		if($coursecurrfolder != null)
		{
			$link = new link($this->URI(array('action' => 'filemanager', 'coursefolderid' => $coursecurrfolder)));
			$link->link = $uicon;
			$objFields->addContent('Up'.$link->show());
		}
		$objTable->startHeaderRow();	
		$objTable->addHeaderCell('<strong>'.$this->objLanguage->languageText('mod_uwcelearningmobile_wordname', 'uwcelearningmobile').'</strong>', '40%');
		$objTable->addHeaderCell('<strong>'.$this->objLanguage->languageText('mod_uwcelearningmobile_wordsize', 'uwcelearningmobile').'</strong>', '40%');
		$objTable->endHeaderRow();
	
		if(empty($coursefiles) && empty($coursefolders)){
				$norec = $this->objLanguage->languageText('mod_uwcelearningmobile_wordnofiles', 'uwcelearningmobile');
				$objTable->startRow();
				$objTable->addCell($norec, '', 'center', 'center', '', '');
				$objTable->endRow();
		}
		else{
		 	foreach($coursefolders as $folder)
			{	
				$link = new link($this->URI(array('action' => 'filemanager', 'coursefolderid' => $folder['id'])));
				$link->link = basename($folder['folderpath']);
				$objTable->startRow();
				$objTable->addCell($ficon.' '.$link->show(), '', '', '', '', '');
				$objTable->addCell($this->objLanguage->languageText('mod_uwcelearningmobile_wordfolder', 'uwcelearningmobile'), '', '', '', '', '');
				$objTable->endRow();
			}
			foreach($coursefiles as $file)
			{	
				$fileDownloadPath = $this->objSysConfig->getcontentPath().$file['path'];
				$fileDownloadPath = $this->objCleanUrl->cleanUpUrl($fileDownloadPath);
				$link = new link($fileDownloadPath);
				$link->link = substr($file['filename'], 0, 9) . '...';
				$icon = $this->objFileIcons->getFileIcon($file['filename']);
				$objTable->startRow();
				$objTable->addCell($icon.' '.$link->show(), '', '', '', '', '');
				$objTable->addCell($fileSize->formatsize($file['filesize']), '', '', '', '', '');
				$objTable->endRow();
			}
		}
		$objFields->addContent($objTable->show().'<br/>');
		echo $objFields->show();
	}
	
	$homeLink = new link($this->URI(array()));
	$homeLink->link = 'Home';
	echo $homeLink->show().'</br>';
?>
