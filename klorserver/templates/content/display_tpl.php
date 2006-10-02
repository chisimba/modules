<?
/**
* Local content download page for kLOR module of KEWL.NextGen
* @author Jameel Adam
* 
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

	//---------Icons
// 'Add' Icon 
	$icon = $objIcon;
	$icon->setIcon('add');
	$icon->alt = "Upload";
	$icon->align=false;
	$link = $this->getObject('link','htmlelements');
	$link->href = $this->uri(array('action'=>'upload'));
	$link->link = $icon->show();
   	$addIcon = $link->show();
	//echo  $addIcon;
	//--------------




	/*DIRECTORY--------PATHS*/	
	//base path & content folder , next we need the contextCode , contextCode.zip
	$contentBasePath = $this->objConfig->contentPath().'content'.'/';
	//print $contentBasePath;
	

	
	//Display In Table:
	// contextCode , menutext , location , author(s) , timestamp , downloadLinks , 
	//displaying all the coarses availble , contextCode is the coarse code,menutext is the "title"   
	$courseHeading = $objLanguage->languageText('mod_klorserver_courseName');
	$courseDescription = $objLanguage->languageText('mod_klorserver_courseInfo');
	$courseDate = $objLanguage->languageText('mod_klorserver_postedDate');
	$courseDownload = $objLanguage->languageText('mod_klorserver_download');
	$courseCode = $objLanguage->languageText('mod_klorserver_code');
	$courseInfo = $objLanguage->languageText('mod_klorserver_overview');
	$delete = $objLanguage->languageText('mod_klorserver_delete');
	$lblDelete=$delete;

	$tblCourseWare->startHeaderRow();
		$tblCourseWare->addHeaderCell($courseCode);
		$tblCourseWare->addHeaderCell($courseHeading);
		$tblCourseWare->addHeaderCell($courseDescription);
		$tblCourseWare->addHeaderCell($courseDate);
		$tblCourseWare->addHeaderCell($courseInfo);
		$tblCourseWare->addHeaderCell($courseDownload);
		$tblCourseWare->addHeaderCell($delete);
	$tblCourseWare->endHeaderRow();
	
	//extraction 
	$sql="Select * from tbl_context";
   	$data=$this->objContext->getArray($sql);
	$sql2="Select * from tbl_coursefile";
   	$data2=$this->objCourseUpload->getArray($sql2);


   	foreach($data as $item)
	{	
		$id = $item['id'];
		$contextCode = $item['contextCode'];
		$title = $item['menutext'];
		$description = $item['about'];
		$date = $item['updated'];
	
		//link & location
		$zipname = "$contextCode".".zip";
		$linklocation = $contentBasePath."{$contextCode}"."/"."{$zipname}";
		$infolink=$this->objConfig->contentPath().'content'.'/';	
		$staticcontent = $infolink."{$contextCode}"."/"."staticcontent/"."index.html";
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


		
		$name=$contextCode;
		$tblCourseWare->startRow($rowClass);
			$tblCourseWare->addCell($contextCode);
			$tblCourseWare->addCell($title);
			$tblCourseWare->addCell($description);
			$tblCourseWare->addCell($date);
			$tblCourseWare->addCell("<a href=\"{$staticcontent}\">".$viewicon->show()."</a>");
			$tblCourseWare->addCell("<a href=\"{$link}\">".$icon->show()."</a>");
			$tblCourseWare->addCell($deleteLink);
		$tblCourseWare->endRow($rowClass);
	}//end for 


	//*******Display of Upload Files*********//	
	foreach($data2 as $item)
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

	print_r($link);die(sqlall);
		
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
	//******************//


	//Heading-------------------------------------
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
	


?>
