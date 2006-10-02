<?

/**
* Local content download page for kLOR module of KEWL.NextGen
* @author Jameel Adam
* 
*/
	
	/**
	*Enabling the client 
	*/

	//Set layout template
	$this->objConfig=& $this->newObject('config', 'config');
	//$this->setLayoutTemplate('context_layout_tpl.php');
	$table=&$this->newObject('htmltable','htmlelements');
	$objdownload=&$this->newObject('link','htmlelements');
	$form=&$this->newObject('form','htmlelements');
	$ta=&$this->newObject('textarea','htmlelements');
	$saveBut=&$this->newObject('button','htmlelements');
	$idInput=&$this->newObject('textinput','htmlelements');
	$modeInput=&$this->newObject('textinput','htmlelements');
	$nodeIdInput=&$this->newObject('textinput','htmlelements');
	$rowClass = 'odd';
	$this->objCourseUpload =& $this->getObject('dbcoursewarefile', 'klorserver');
	$this->objContext =& $this->getObject('dbcontext', 'context');
	$numberofcontexts = count($this->objContext->getAll());
	$tblCourseWare =& $this->newObject('htmltable','htmlelements');
	$objTable = &$this->getObject('htmltable','htmlelements');
	$objHeading = &$this->getObject('htmlheading','htmlelements');
	$objIcon = &$this->getObject('geticon','htmlelements');
	$objLink = & $this->getObject('link','htmlelements');
	$this->objLanguage=& $this->getObject('language', 'language');
	$tblCourseWare->cellspacing='2';
	$tblCourseWare->cellpadding='2';

//-------------------------THE END---------------------//

	/**Now We swich modes ,send file,get file 
	*	file list 
	* 3 modes
	*/
	//print_r($mode);
	$mode = $this->getParam('mode',$mode);
	switch($mode){
		
	case 'fileList':
		//set up a basic display for filelist		
		$courseHeading 		= $objLanguage->languageText('mod_klorserver_courseName');
		$courseDescription 	= $objLanguage->languageText('mod_klorserver_courseInfo');
		$courseDate 		= $objLanguage->languageText('mod_klorserver_postedDate');
		$courseDownload 	= $objLanguage->languageText('mod_klorserver_download');
		$courseCode 		= $objLanguage->languageText('mod_klorserver_code');
		$courseInfo 		= $objLanguage->languageText('mod_klorserver_overview');
		$delete 			= $objLanguage->languageText('mod_klorserver_delete');
		$lblDelete			= $delete;
	
		$tblCourseWare->startHeaderRow();
				$tblCourseWare->addHeaderCell($courseCode);
					$tblCourseWare->addHeaderCell($courseHeading);
						$tblCourseWare->addHeaderCell($courseDescription);
							$tblCourseWare->addHeaderCell($courseDate);
					$tblCourseWare->addHeaderCell($courseInfo);
				$tblCourseWare->addHeaderCell($courseDownload);
			$tblCourseWare->addHeaderCell($delete);
		$tblCourseWare->endHeaderRow();
	
			//*******Display of Upload Files*********//	
		foreach($fileList as $item)
		{
			$id = $item['id'];
			$title = $item['title'];
			$description = $item['description'];
			$path = $item['path'];
			
			$name = $item['name'];
			//print $path;
			//$date = $item['updated'];
		
			//link & location
			$zipname = $name;
			$linklocation = $path;
			$staticcontent = '';
			//print "$staticcontent";
			$link = $linklocation;
		
			//download icon	
			$icon = $this->getObject('geticon','htmlelements');
			$icon->setIcon('download');
			$lblDownload = "Download";	
			$icon->alt = $lblDownload;
			$icon->align=false;
			
			$viewicon = $this->getObject('geticon','htmlelements');
			$viewicon->setIcon('view');
			$lblView = "View";	
			$viewicon->alt = $lblView;
			$viewicon->align=false;
		
			$name=$contextCode;
			
			$title = $item['title'];
			$description = $item['description'];
			$path = $item['path'];
			$name = $item['name'];
			
			$delete_icon = $this->getObject('geticon','htmlelements');
			$delete_icon->setIcon('delete');
			$lbldelete = "delete";	
			$delete_icon->alt = $lblView;
			$delete_icon->align=false;
			
			// Delete an entry in the table.
			$uriDelete = $this->uri(
				array(
					'action' => 'deletefile', 
					'id' =>$id 
				)
			);
			$deleteLink = "<a href=\"{$uriDelete}\">".$delete_icon->show()."</a>";
			
			$tblCourseWare->startRow($rowClass);
				$tblCourseWare->addCell($title);
				$tblCourseWare->addCell($name);
				$tblCourseWare->addCell($description);
				$tblCourseWare->addCell($date);
				$tblCourseWare->addCell(' ');
				$tblCourseWare->addCell("<a href=\"{$link}\">".$icon->show()."</a>");
				$tblCourseWare->addCell($deleteLink);
			$tblCourseWare->endRow($rowClass);
		}//end for 
	
		// Page Heading-------------------------------
		// Page title --------------------------------
		$pgTitle = $objHeading;
		$pgTitle->type = 1;
		$pgTitle->str ="Courseware Downloads"."&nbsp;".$addIcon;
	
		$heading = $objTable;
		$heading->width = NULL;
		$heading->startRow();
		$heading->addCell($pgTitle->show());
		$heading->addCell("&nbsp;", null, 'middle');
    	$heading->endRow();
		echo $heading->show();
		//Heading-------------------------------------
		//Heading-------------------------------------
	
	
		$objdownload->link($this->uri(array('action'=>'upload'))); 
		echo $objdownload->show();
	
		//displayTable-------------
		echo $tblCourseWare->show();
		//displayTable-------------
		break;

	case 'sendfile':
		// Page Heading-------------------------------
		// Page title --------------------------------
		$pgTitle = $objHeading;
		$pgTitle->type = 1;
		$pgTitle->str ="Send File Function"."&nbsp;".$addIcon;
	
		$heading = $objTable;
		$heading->width = NULL;
		$heading->startRow();
		$heading->addCell($pgTitle->show());
		$heading->addCell("&nbsp;", null, 'middle');
    	$heading->endRow();
		echo $heading->show();
		//Heading-------------------------------------
		//Heading-------------------------------------
		$objdownload->link($this->uri(array('action'=>'upload'))); 
		echo $objdownload->show();
	//client will receive the encoded string 
	//i must now convert it into a file and download to client machine
 	
	$file = base64_decode($file);
	print $file;
	
	/**Funtion that stores the file to filesystem is to be 
	*  done within the klor class 
	*/	
	break;

	/** This case is for get file
	*	action for client to receive file
	*/

	case 'getfile':
	print_r($getfile);
	break;

	}//end switch	


	
?>