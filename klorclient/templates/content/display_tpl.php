<?
error_reporting(E_ALL & ~E_NOTICE);
/**
* Local content download page for kLOR module of KEWL.NextGen
* @author Jameel Adam
* 
*/
	//Set layout template
	$this->objConfig=& $this->newObject('altconfig', 'config');
	//$this->setLayoutTemplate('context_layout_tpl.php');
	$this->objKlor=  &  $this->getObject('klorclients', 'klorclient');
	
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
	
	$tblCourseWare->cellspacing='1';
	
	$tblCourseWare->cellpadding='0';
	
	$tblCourseWare->width='99%';
	
        	
	// 'Add' Icon 
	$icon = $objIcon;
	$icon->setIcon('upload');
	$icon->alt = "Upload";
	$icon->align=false;
	$link = $this->getObject('link','htmlelements');
	$link->href = $this->uri(array('action'=>'upload'));
	$link->link = $icon->show();
   	$addIcon = $link->show();
	$contentBasePath = $this->objConfig->getcontentPath().'content'.'/';
	$courseHeading = $objLanguage->languageText('mod_klorserver_courseName');
	$courseDescription = $objLanguage->languageText('mod_klorserver_courseInfo');
	$courseDate = $objLanguage->languageText('mod_klorserver_postedDate');
	$courseDownload = $objLanguage->languageText('mod_klorserver_download');
	$courseBittorrent = $objLanguage->languageText('mod_klorserver_bittorrent');
	$courseCode = $objLanguage->languageText('mod_klorserver_code');
	$courseInfo = $objLanguage->languageText('mod_klorserver_overview');
	$delete = $objLanguage->languageText('mod_klorserver_delete');
	$courseLicense = $objLanguage->languageText('mod_klorserver_license');
	$MetaData_txt = $objLanguage->languageText('mod_klorserver_metadata');	
	$coursePreview = $objLanguage->languageText('mod_klorserver_preview');
	$rating = $objLanguage->languageText('mod_klorserver_rating');	
	$lblDelete=$delete;

	$tblCourseWare->startHeaderRow();
		//$tblCourseWare->addHeaderCell($courseCode);
		$tblCourseWare->addHeaderCell($courseHeading);
		$tblCourseWare->addHeaderCell($courseDescription);
		$tblCourseWare->addHeaderCell($courseDate);
		//$tblCourseWare->addHeaderCell($courseInfo);
		$tblCourseWare->addHeaderCell($coursePreview);
		$tblCourseWare->addHeaderCell($courseLicense);
		//$tblCourseWare->addHeaderCell($courseDownload);
		$tblCourseWare->addHeaderCell($courseBittorrent);
		
		$tblCourseWare->addHeaderCell($rating);
		
		$this->objConfig= &$this->newObject('config','config');
		if(!$this->objUser->isAdmin())
		{
		
		}else{
		$tblCourseWare->addHeaderCell($MetaData_txt);
		$tblCourseWare->addHeaderCell($delete);
		}
	$tblCourseWare->endHeaderRow();
	
	//extraction 
	$sql="Select * from tbl_context";
   	//$data=$this->objContext->getArray($sql);
	//$sql2="Select * from tbl_klor_coursefile";
   	//$sql2='';
	$data2=$this->objCourseUpload->getArray($sql);


	//echo  $this->objConfig->contentBasePath();

	//*******Display of Upload Files*********//	
	foreach($data2 as $item)
	{	$preview = $item['link'];
		$bittorrentlocation = $item['bittorrentlocation'];
		
		$filelocation = $item['file'];
		$id = $item['id'];
		$title = $item['title'];
		$description = $item['description'];
		$path = $item['path'];
		$date = $item['updated'];
		$name = $item['name'];
		$clean_path = $this->objConfig->contentPath().'klor/courses/course-'.$name;
		$zipname = $name;
		$linklocation = $path;
		$staticcontent = '';
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
		
		$licenseicon = $this->getObject('geticon','htmlelements');
		$licenseicon->setIcon('modules/license');
		$lbllicense = "View license";	
		$licenseicon->alt = $lbllicense;
		$licenseicon->align=false;
		
		$name=$contextCode;
		$title = $item['title'];
		$description = $item['description'];
		$path = $item['path'];
		$name = $item['name'];
		$delete_icon = $this->getObject('geticon','htmlelements');
		$delete_icon->setIcon('delete');
		$lbldelete = "delete";	
		$delete_icon->alt = "delete";
		$delete_icon->align=false;
		$meta_icon = $this->getObject('geticon','htmlelements');
		$meta_icon->setIcon('editmetadata');
		$lblmeta = "Edit metadata";	
		$meta_icon->alt = $lblmeta;
		$meta_icon->align=false;
		$viewicon = $this->getObject('geticon','htmlelements');
		$viewicon->setIcon('preview');
		$lblView = "View";	
		$viewicon->alt = $lblView;
		$viewicon->align=false;
		$rating_icon;
		$rating_icon = $this->getObject('geticon','htmlelements');
		$rating_icon->setIcon('star');
		$lblView = "Rate this course";	
		$rating_icon->alt = $lblView;
		$rating_icon->align=false;

		$ratingoff_icon;
		$ratingoff_icon = $this->getObject('geticon','htmlelements');
		$ratingoff_icon->setIcon('star-g');
		$lblView = "Rate this course";	
		$ratingoff_icon->alt = $lblView;
		$ratingoff_icon->align=false;

		// Delete an entry in the table.
		$uriDelete = $this->uri(
			array(
				'action' => 'deletefile', 
				'id' =>$id 
			)
		);
		$uriMeta = $this->uri(
			array(
				'action' => 'metadata', 
				'id' =>$id 
			)
		);
		$uriMetaview = $this->uri(
			array(
				'action' => 'overview',
				'id' =>$id ,
				'title'=>$description 
			)
		);
		$uriRating = $this->uri(
			array(
				'action' => 'rating',
				'id' =>$id,
				'name'=>$name
			)
		);
		$MetaLink   = "<a href=\"{$uriMeta}\">".$meta_icon->show()."</a>";
    	$deleteLink = "<a href=\"{$uriDelete}\">".$delete_icon->show()."</a>";
		$viewMeta   = "<a href=\"{$uriMetaview}\">".$viewicon->show()."</a>";

		//$sum = $this->objKlor->calcrating($id);
		$random = rand(0,4);
		for($i=0;$i<=$random;$i++){
		$ratetime .= $rating_icon->show();
		}
		for($j=$random;$j<4;$j++){
		$ratetime .= $ratingoff_icon->show();
		}
		//------- Random Rating System
		
		$ratingNumber = $item['rating'];
		
		if($ratingNumber==Null || $ratingNumber==0){
		$ratetime = Null;
		$ratetime .= $ratingoff_icon->show();
		$ratetime .= $ratingoff_icon->show();
		$ratetime .= $ratingoff_icon->show();
		$ratetime .= $ratingoff_icon->show();
		$ratetime .= $ratingoff_icon->show();
		}else if($ratingNumber==1){
		$ratetime = Null;
		$ratetime .= $rating_icon->show();
		$ratetime .= $ratingoff_icon->show();
		$ratetime .= $ratingoff_icon->show();
		$ratetime .= $ratingoff_icon->show();
		$ratetime .= $ratingoff_icon->show();
		}else if($ratingNumber==2){
		$ratetime = Null;
		$ratetime .= $rating_icon->show();
		$ratetime .= $rating_icon->show();
		$ratetime .= $ratingoff_icon->show();
		$ratetime .= $ratingoff_icon->show();
		$ratetime .= $ratingoff_icon->show();
		}else if($ratingNumber==3){
		$ratetime = Null;
		$ratetime .= $rating_icon->show();
		$ratetime .= $rating_icon->show();
		$ratetime .= $rating_icon->show();
		$ratetime .= $ratingoff_icon->show();
		$ratetime .= $ratingoff_icon->show();
		}else if($ratingNumber==4){
		$ratetime = Null;
		$ratetime .= $rating_icon->show();
		$ratetime .= $rating_icon->show();
		$ratetime .= $rating_icon->show();
		$ratetime .= $rating_icon->show();
		$ratetime .= $ratingoff_icon->show();
		}else if($ratingNumber==5){
		$ratetime = Null;
		$ratetime .= $rating_icon->show();
		$ratetime .= $rating_icon->show();
		$ratetime .= $rating_icon->show();
		$ratetime .= $rating_icon->show();
		$ratetime .= $rating_icon->show();
		}else{
		$ratetime = 'dunno';
		}
		
		
		//$ratetime = $ratingoff_icon->show();
		//$ratetime .= $ratingoff_icon->show();

		$uriRating  = "<a href=\"{$uriRating}\">".$ratetime."</a>";
		$ratetime = null;
		$tblCourseWare->startRow($rowClass);
			//$tblCourseWare->addCell($title);
			
			$tblCourseWare->addCell("<a href=\"{$filelocation}\">".$name."<a>");
			$tblCourseWare->addCell("<a href=\"{$uriMetaview}\">".$description."</a>");
			$tblCourseWare->addCell($date);
			//$tblCourseWare->addCell($viewMeta);
			
			//-------Pop Up Window-----
			$this->loadClass('windowpop', 'htmlelements');
			$popup = $this->newObject('windowpop', 'htmlelements');
			$clean_path = $this->objConfig->contentPath().'klor/courses/course-'.$name;
			$popup->location = $clean_path.'/';//$preview;
			$popup->linktext = $viewicon->show(); // <----link txt is a icon now
			$popup->width = 500;
			$popup->height = 400;
			$popup->left = 300;
			$popup->top = 400;
			$popup->menubar = "yes";
			$popup->scrollbars = "yes";
			$popup->toolbar = "yes";
	
			//echo $popup->show();
			//--------End Popup Window---	
			$tblCourseWare->addCell($popup->show(),'1%');
			$popuplicense = $this->newObject('windowpop', 'htmlelements');
			//$popuplicense->location = '/modules/klorclient/license/license.txt';//$preview;
			$popuplicense->location ="http://creativecommons.org/licenses/by-nc/1.0/legalcode";//$preview;
			$popuplicense->linktext = $licenseicon->show(); // <----link txt is a icon now
			$popuplicense->width = 500;
			$popuplicense->height = 400;
			$popuplicense->left = 300;
			$popuplicense->top = 400;
			$popuplicense->menubar = "yes";
			$popuplicense->scrollbars = "yes";
			$popuplicense->toolbar = "yes";
		
			$tblCourseWare->addCell($popuplicense->show(),'1%');
			//$tblCourseWare->addCell("<a href=\"{$link}\">".$icon->show()."</a>");
			
			$tblCourseWare->addCell("<a href=\"{$bittorrentlocation}\">".$icon->show()."</a>");
			$tblCourseWare->addCell($uriRating,'15%');
			if(!$this->objUser->isAdmin())
		{
		
		}else{
			$tblCourseWare->addCell($MetaLink);
			$tblCourseWare->addCell($deleteLink,'5%');
		}
		$tblCourseWare->endRow($rowClass);
	}//end for 


	//Heading-------------------------------------
	// Page Heading-------------------------------
	// Page title --------------------------------
	$pgTitle = $objHeading;
	$pgTitle->type = 1;
	$nbsp = "&nbsp;";
	$pgTitle->str ="Courseware Downloads".$nbsp.'<right>'.$this->objHelp->show('courselist','klorclient').'</right>';
//
	$heading = $objTable;
	$heading->width = NULL;
	$heading->startRow();
	$heading->addCell($pgTitle->show());
	$heading->addCell("&nbsp;", null, 'middle');
	$heading->endRow();
	//Heading------------------------------------
	echo $heading->show();
	//Heading-------------------------------------
	$objdownload->link($this->uri(array('action'=>'upload'))); 
	echo $objdownload->show();
	
	//displayTable-------------
		echo $tblCourseWare->show();
	//displayTable-------------
	
	$back_icon;
	$back_icon = $this->getObject('geticon','htmlelements');
	$back_icon->setIcon('bookopen');
	$lblView = "Back";	
	$back_icon->alt = $lblView;
	$back_icon->align=false;
	// Delete an entry in the table.
	$uriBack = $this->uri(
		array(
			'action' => ' ', 
			'id' =>$id 
		)
	);

	$BackLink   = "<a href=\"{$uriBack}\">".$back_icon->show()."</a>";
	//echo $BackLink;
	$lic_icon;
	$lic_icon = $this->getObject('geticon','htmlelements');
	$lic_icon->setIcon('view');
	$lblView = "license";	
	$lic_icon->alt = $lblView;
	$lic_icon->align=false;
	// Delete an entry in the table.
	$uriLic = $this->uri(
		array(
			'action' => 'license'
			
		)
	);
	
?>
