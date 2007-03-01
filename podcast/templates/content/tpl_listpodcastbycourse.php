<?php

$this->loadClass('link', 'htmlelements');
$this->loadClass('windowpop', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('formatfilesize', 'files');

$objIcon = $this->newObject('geticon', 'htmlelements');

$filesize = new formatfilesize();


$objFeatureBox = $this->getObject('featurebox', 'navigation');

$heading = new htmlheading();


$heading->str = $this->objLanguage->languageText('mod_podcast_latespodcasts', 'podcast')." ".
				$this->objLanguage->languageText('mod_podcast_of','podcast')." ".$this->objPodcast->getCourseName($this->getParam('contextcode'));    


$heading->type = 1;

echo $heading->show();


if (count($podcasts) == 0) {
    
    if (isset($id)) {
        echo '<div class="noRecordsMessage">'.$this->objLanguage->languageText('mod_podcast_userhasnotaddedanypodcasts', 'podcast').'</div>';
    } else {
        echo '<div class="noRecordsMessage">'.$this->objLanguage->languageText('mod_podcast_nopodcastsavailable', 'podcast').'</div>'; 
    }
} else {
    foreach ($podcasts as $pod => $value)
    {
		foreach ($value as $podcast)
		{
        	$content = '<p>'.htmlentities($podcast['description']).'</p>';
         
         
		/* Following code added by Mohamed Yusuf */
		$context = array();
		$context = $this->objPodcast->getContextCode($podcast['id']);
		$courses = array();
		if(!empty($context)){
			foreach ($context as $key => $value)
			{
				$courses[] = $this->objPodcast->getPodcastContext($value['contextcode']);
			}
		}else{

		}
		/* end of mohamed's code */
         
         
        	$table = $this->newObject('htmltable', 'htmlelements');
        	$table->startRow();
            	if (isset($id)) {  
                	$table->addCell('<strong>'.$this->objLanguage->languageText('word_by', 'system').':</strong> '.$this->objUser->fullname($podcast['creatorid']), '50%');
            	} else {
                	$authorLink = new link ($this->uri(array('action'=>'byuser', 'id'=>$podcast['creatorid'])));
                	$authorLink->link = $this->objUser->fullname($podcast['creatorid']);
                	$table->addCell('<strong>'.$this->objLanguage->languageText('word_by', 'system').':</strong> '.$authorLink->show(), '50%');
            	}
            $table->addCell('<strong>'.$this->objLanguage->languageText('word_date', 'system').':</strong> '.$this->objDateTime->formatDate($podcast['datecreated']), '50%');
        	$table->endRow();
        	$table->startRow();
            $table->addCell('<strong>'.$this->objLanguage->languageText('phrase_filesize', 'system').':</strong> '.$filesize->formatsize($podcast['filesize']), '50%');
            $playtime = $this->objDateTime->secondsToTime($podcast['playtime']);
            $playtime = ($playtime == '0:0') ? '<em>Unknown</em>' : $playtime;
            $table->addCell('<strong>'.$this->objLanguage->languageText('word_playtime', 'system').':</strong> '.$playtime, '50%');
        	$table->endRow();
        
        	$content .= $table->show();
        
        	$downloadLink = new link ($podcast['path']);
        	$downloadLink->link = htmlentities($podcast['filename']);
        
        	$this->objPop=&new windowpop;
        	$this->objPop->set('location',$this->uri(array('action'=>'playpodcast', 'id'=>$podcast['id']), 'podcast'));
        	$this->objPop->set('linktext', $this->objLanguage->languageText('mod_podcast_listenonline', 'podcast'));
        	$this->objPop->set('width','280');
        	$this->objPop->set('height','120');
        	//leave the rest at default values
        	$this->objPop->putJs(); // you only need to do this once per page
        
        	$content .= '<br /><p>'.$this->objPop->show().' / <strong>'.$this->objLanguage->languageText('mod_podcast_downloadpodcast', 'podcast').':</strong> '.$downloadLink->show().'</p>';
         
         
		/* Following code added by Mohamed Yusuf */
		
		if(!empty($courses)){
			$content .= "<strong>".$this->objLanguage->languageText('mod_podcast_listcourse','podcast')."</strong>";
        	$content .="<ul>";
			foreach ($courses as $key => $value)
			{
				foreach ($value as $course)
				{
					$content .="<li>".$course['title']."</li>";
				}
			}
			$content .="</ul>";
		}
		/* end of Mohamed's code*/
         
        	if ($podcast['creatorid'] == $this->objUser->userId()) {
            	$objIcon->setIcon('edit');
            
            	$editLink = new link ($this->uri(array('action'=>'editpodcast', 'id'=>$podcast['id'])));
            	$editLink->link = $objIcon->show();
            
            	$deleteIcon = $objIcon->getDeleteIconWithConfirm($podcast['id'], array('action'=>'deletepodcast', 'id'=>$podcast['id']),
                'podcast', $this->objLanguage->languageText('mod_podcast_confirmdeletepodcast', 'podcast'));
            	$icons = ' '.$editLink->show().' '.$deleteIcon;
        	} else {
            	$icons = '';
        	}
        
        	echo $objFeatureBox->show(htmlentities($podcast['title']).$icons, $content);
		}
    }
    
}


echo '<p>';

    if (isset($id)) {
        $HomeLink = new link($this->uri(NULL));
        $HomeLink->link = $this->objLanguage->languageText('mod_podcast_podcasthome', 'podcast');
        
        echo $HomeLink->show().' / ';
    }
    
    $link = new link($this->uri(array('action'=>'addpodcast')));
    $link->link = $this->objLanguage->languageText('mod_podcast_addpodcast', 'podcast');
    
    echo $link->show();

echo '</p>&nbsp;';

?>