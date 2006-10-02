<?
	$objBar=&$this->newObject('klorclients','klorclient');
 	$this->objLanguage=& $this->getObject('language', 'language');
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

/**
*Displays the metadata in a nice view
*/

$id=$this->getParam('id');

$params = $this->objDC->getMetaData($id);
//print_r($params);				
/**
*	Metadata
*/

                $title 			= $params[0]['dc_title'];
                $subject 		= $params[0]['dc_subject'];
                $description 	= $params[0]['dc_description'];
                $source 		= $params[0]['dc_source'];
                $sourceurl 		= $params[0]['dc_sourceurl'];
                $type 			= $params[0]['dc_type'];
                $relationship 	= $params[0]['dc_relationship'];
                $coverage 		= $params[0]['dc_coverage'];
                $creator 		= $params[0]['dc_creator'];
                $publisher 		= $params[0]['dc_publisher'];
                $rights 		= $params[0]['dc_rights'];
                $date 			= $params[0]['dc_date'];
                $format 		= $params[0]['dc_format'];
                $identifier 	= $params[0]['dc_identifier'];
                $language 		= $params[0]['dc_language'];
                $audience 		= $params[0]['dc_audience'];
				$creator 		= $params[0]['dc_creator']; 

/**
* A nice read worthy html format 
*/
	//Title Heading-------------------------------------
	$pgTitle = $objHeading;
	$pgTitle->type = 1;
	$pgTitle->str =$title;
	$heading = $objTable;
	$heading->width = NULL;
	$heading->startRow();
	$heading->addCell($pgTitle->show());
	$heading->addCell("&nbsp;", null, 'middle');
	$heading->endRow();
	echo $heading->show();
	//Heading-------------------------------------

/**
*txt
*/
	$title_txt          = $this->objLanguage->languageText("mod_klorclient_title");
	$subject_txt 	 	= $this->objLanguage->languageText("mod_klorclient_subject");	
	$description_txt 	= $this->objLanguage->languageText("mod_klorclient_description");
	$source_txt 		= $this->objLanguage->languageText("mod_klorclient_source");
	$sourceurl_txt 		= $this->objLanguage->languageText("mod_klorclient_sourceurl");
	$type_txt 			= $this->objLanguage->languageText("mod_klorclient_type");
	$relationship_txt 	= $this->objLanguage->languageText("mod_klorclient_relationship");
	$coverage_txt 		= $this->objLanguage->languageText("mod_klorclient_coverage");
	$creator_txt 		= $this->objLanguage->languageText("mod_klorclient_creator");
	$publisher_txt 		= $this->objLanguage->languageText("mod_klorclient_publisher");
	$rights_txt 		= $this->objLanguage->languageText("mod_klorclient_rights");
	$date_txt 			= $this->objLanguage->languageText("mod_klorclient_date");
	$format_txt 		= $this->objLanguage->languageText("mod_klorclient_format");
	$identifier_txt 	= $this->objLanguage->languageText("mod_klorclient_identifier");
	$language_txt 		= $this->objLanguage->languageText("mod_klorclient_language");
	$audience_txt 		= $this->objLanguage->languageText("mod_klorclient_audience");
	$creator_txt		= $this->objLanguage->languageText("mod_klorclient_creator");


		//format: adding rows
		$tblCourseWare->startRow($rowClass);
				$tblCourseWare->addCell($title_txt);
				$tblCourseWare->addCell($title);
		$tblCourseWare->endRow($rowClass);

		$tblCourseWare->startRow($rowClass);
	 			$tblCourseWare->addCell($subject_txt); 		
	 			$tblCourseWare->addCell($subject); 		
		$tblCourseWare->endRow($rowClass);


		$tblCourseWare->startRow($rowClass);
                $tblCourseWare->addCell($description_txt); 	
                $tblCourseWare->addCell($description); 	
		$tblCourseWare->endRow($rowClass);

		$tblCourseWare->startRow($rowClass);
                $tblCourseWare->addCell($source_txt); 		
                $tblCourseWare->addCell($source); 		
		$tblCourseWare->endRow($rowClass);

		$tblCourseWare->startRow($rowClass);
                $tblCourseWare->addCell($sourceurl_txt); 		
                $tblCourseWare->addCell($sourceurl); 		
		$tblCourseWare->endRow($rowClass);

		$tblCourseWare->startRow($rowClass);
                $tblCourseWare->addCell($type_txt );			
                $tblCourseWare->addCell($type );			
		$tblCourseWare->endRow($rowClass);

		$tblCourseWare->startRow($rowClass);
                $tblCourseWare->addCell($relationship_txt );	
                $tblCourseWare->addCell($relationship );	
		$tblCourseWare->endRow($rowClass);

		$tblCourseWare->startRow($rowClass);
                $tblCourseWare->addCell($coverage_txt );		
                $tblCourseWare->addCell($coverage );		
		$tblCourseWare->endRow($rowClass);

		$tblCourseWare->startRow($rowClass);
                $tblCourseWare->addCell($creator_txt );		
                $tblCourseWare->addCell($creator );		
		$tblCourseWare->endRow($rowClass);

		$tblCourseWare->startRow($rowClass);
                $tblCourseWare->addCell($publisher_txt); 		
                $tblCourseWare->addCell($publisher); 		
		$tblCourseWare->endRow($rowClass);

		$tblCourseWare->startRow($rowClass);
                $tblCourseWare->addCell($rights_txt );		
                $tblCourseWare->addCell($rights );		
		$tblCourseWare->endRow($rowClass);

		$tblCourseWare->startRow($rowClass);
                $tblCourseWare->addCell($date_txt 	);		
                $tblCourseWare->addCell($date 	);		
		$tblCourseWare->endRow($rowClass);

		$tblCourseWare->startRow($rowClass);
                $tblCourseWare->addCell($format_txt );		
                $tblCourseWare->addCell($format );		
		$tblCourseWare->endRow($rowClass);

		$tblCourseWare->startRow($rowClass);
                $tblCourseWare->addCell($identifier_txt );	
                $tblCourseWare->addCell($identifier );	
		$tblCourseWare->endRow($rowClass);

		$tblCourseWare->startRow($rowClass);
                $tblCourseWare->addCell($language_txt );		
                $tblCourseWare->addCell($language );		
		$tblCourseWare->endRow($rowClass);

		$tblCourseWare->startRow($rowClass);
                $tblCourseWare->addCell($audience_txt );		
                $tblCourseWare->addCell($audience );		
		$tblCourseWare->endRow($rowClass);

		$tblCourseWare->startRow($rowClass);
				$tblCourseWare->addCell($creator_txt );		
				$tblCourseWare->addCell($creator );		
		$tblCourseWare->endRow($rowClass);
	
	/**Method to draw rating graphs
	*
	*/
print '<p>';
        //$per=99;
	$percent = $per;	
	$bargraph = $objBar->bargraph($percent);
	$title= $this->getParam('title');
	
	echo '<h3>'.$title.' course rating '.$this->objHelp->show('overview','klorclient').'</h3>'.'<br>';
	echo $bargraph.'&nbsp;'.$percent.'%';
print '<p/>';

echo $tblCourseWare->show();
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

	$BackLink   = "<a href=\"{$uriBack}\">"."Back"."</a>";
	echo $BackLink;
?>
