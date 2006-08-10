<?php
// Load the window popup class
$objPop = $this->newObject('windowpop', 'htmlelements');

$link = $this->uri(array('action' => 'attachmentwindow', 'elementid' => $id, 'context'=>$contextCode, 'forum' => $forum, 'type'=>$forumtype ));

$objPop->set('location', $link);
$objPop->set('linktext', $this->objLanguage->languageText('mod_forum_addeditattachments'));
$objPop->set('window_name','forum_attachments');
$objPop->set('width','600'); 
$objPop->set('height','400');
$objPop->set('left','100');
$objPop->set('top','100');
if (count($files) > 0) {

    echo '<p>'.$objPop->show().'</p>';
    
    echo '<ul>';
    
    foreach ($files AS $file)
    {
        echo ('<li>'.$file['filename'].'</li>');
    
    }
    
    echo '</ul>';
} else {
    echo '<span class="noRecordsMessage">'.$this->objLanguage->languageText('mod_forum_noattachments').'</span>';
    
    echo '<p>'.$objPop->show().'</p>';

}







?>