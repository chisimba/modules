<?php 
/*
* A template to display a list of available pastpapers for a given context
or lobby
*/

$this->pastpapers = $this->getObject('pastpaper');
$this->_objDBContext->getContextCode();
$heading = $this->getObject('htmlheading','htmlelements');
$heading->align = "center";
$content = "";
$this->objLanguage= $this->getObject('language','language');
$this->loadClass('htmltable','htmlelements');
$addicon =$this->getObject('geticon','htmlelements');
$table = $this->getObject('htmltable','htmlelements');

//include he link class
$this->loadClass('link','htmlelements');
$table->width = "90%";
$table->align = "center";
$table->cellspacing =2;
if($this->_objDBContext->isInContext()){

$contextCode  = $this->_objDBContext->getContextCode();
//get the name of the context
$contextName = $this->_objDBContext->getTitle($contextCode);

 }

else {
// the person is in lobby if not in course
$contextName = $this->objLanguage->languageText('word_lobby'); 

 }
  
//check if there are some past papers in the database and if there are some, display a list 
$past_papers = $this->pastpapers->getpapersforcontext($contextCode);  

if(!empty($past_papers)){  
$heading->str = $this->objLanguage->languageText('mod_pastpapers_listofpapers','pastpapers')."&nbsp;".$contextName."&nbsp;".$addicon->getAddIcon($this->uri(array('action'=>'add')));

//begin collecting one by one row 
    $table->startRow();
	$table->addHeaderCell("<b>".$this->objLanguage->languageText('mod_fileshare_filename','fileshare')."</b>",'','','left');	
	$table->addHeaderCell("<b>".$this->objLanguage->languageText('mod_pastpapers_topic','pastpapers')."</b>",'','','left');		
	$table->addHeaderCell("<b>".$this->objLanguage->languageText('mod_pastpapers_examtime','pastpapers')."</b>",'','','left');		

    $table->addHeaderCell("<b>".$this->objLanguage->languageText('mod_pastpapers_hasanswers','pastpapers')."</b>",'','','left');	
	$table->addHeaderCell("<b>".$this->objLanguage->languageText('mod_pastpapers_addedby','pastpapers')."</b>",'','','left');	
	$table->addHeaderCell("<b>".$this->objLanguage->languageText('mod_pastpapers_dateadded','pastpapers')."</b>",'','','left');
    $table->addHeaderCell("<b>".$this->objLanguage->languageText('mod_pastpapers_addanswers','pastpapers')."</b>",'','','left');

	$table->endRow();
	
	
	$class = 'even';
	foreach($past_papers as $p){
	 $class = ($class == 'odd') ? 'even':'odd';
	 
	 if($p['hasanswers']==0){$hasanswers = "No";} else {$hasanswers = "Yes";}
	 $addedby = $this->objUser->fullname($p['userid']);
	
	$downloadlink = new link();
	$downloadlink->link = $p['filename'];
	$downloadlink->href = "modules/pastpapers/papers/".$p['filename'];
	
	
	$answeraddlink = new link($this->uri(array('action'=>'addanswers','paperid'=>$p['id'])));
	$answeraddlink->link = $this->objLanguage->languageText('mod_pastpapers_addanswers','pastpapers');
	
	$table->startRow();	
	$table->addCell($downloadlink->show(),'','','left',$class);
	$table->addCell($p['topic'],'','','left',$class);
	$table->addCell($p['examyear'],'','','left',$class);	
	$table->addCell($hasanswers,'','','left',$class);
	$table->addCell($addedby,'','','left',$class);
	$table->addCell($p['dateuploaded'],'','','left',$class);
	$table->addCell($answeraddlink->show(),'','','left',$class);
	$table->endRow();
	
	}


}
else {
 $heading->str = $this->objLanguage->languageText('mod_pastpapers_nopapers','pastpapers')."&nbsp;".$contextName."&nbsp;".$addicon->getAddIcon($this->uri(array('action'=>'add')));
 
    $table->startRow();
	$table->addCell("",'','','left');	
	$table->endRow();

}

$content .= $heading->show();

$content .= $table->show();

//add a link for viewing the other pastpapers outside the context
$viewothers = new link($this->uri(array('action'=>'otherpapers')));
$viewothers->link = $this->objLanguage->languageText('mod_pastpapers_otherpapers','pastpapers');


$content .= "<br/>".$viewothers->show();
echo $content;
?>