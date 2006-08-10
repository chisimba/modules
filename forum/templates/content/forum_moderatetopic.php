<?
$style = '<style type="text/css">
.switchmenutext { text-align: left;}
</style>';

$this->appendArrayVar('headerParams', $style);

$this->loadClass('form', 'htmlelements');
$this->loadClass('link', 'htmlelements');
$this->loadClass('radio', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');

$objIcon = $this->getObject('geticon', 'htmlelements');

$hiddeninput = new hiddeninput('id', $topic['topic_id']);

//$objIcon->setIcon(



// Get a list of all the topics in forum, excluding thisone
$additionalSql = ' AND tbl_forum_topic.id != "'.$topic['topic_id'].'" ';
$otherTopicsInForum = $this->objTopic->showTopicsInForum($topic['forum_id'], $this->userId, NULL, NULL, NULL, $additionalSql);

$optionsCount = 1;



echo '<h1>'.$this->objLanguage->languageText('mod_forum_moderatetopic', 'Moderate Topic').': '.$topic['post_title'].'</h1>';

if ($this->getParam('message') == 'deletecancelled') {
    echo '<p align="center"><span class="confirm">'.$this->objLanguage->languageText('mod_forum_topicwasnotdeleted', 'This Topic was not Deleted - The Delete confirmation was set to "No".').'</span></p>';

}

echo '<p>'.$this->objLanguage->languageText('mod_forum_whattodowithtopic', 'What would you like to do with this topic?').'</p>';


$switchmenu = $this->newObject('switchmenu', 'blocks');
$switchmenu->mainId  = 'moderate';

/**************************************************************************************
////////////////////////// FIRST OPTION - DELETING A TOPIC  ///////////////////////////
**************************************************************************************/

$deleteForm = new form ('moderate_deletetopic', $this->uri(array('action'=>'moderate_deletetopic')));

if ($topicParent != '') {
    $moderateParent = new link ($this->uri(array('action'=>'moderatetopic', 'id'=>$topicParent['topic_id'])));
    $moderateParent->link = 'click here';
    
    $str = $this->objLanguage->languageText('mod_forum_warntopicistangent', 'This topic is a tangent to <strong>[-POSTTITLE-]</strong>. This option will only affect this tangent. <br />If you would like to moderate the parent topic, please [-CLICKHERE-]');
    
    $str = str_replace('[-CLICKHERE-]', $moderateParent->show(), $str);
    
    $str = str_replace('[-POSTTITLE-]', $topicParent['post_title'], $str);
    
    $deleteForm->addToForm($str);
    
    //$deleteForm->addToForm('<p>This topic is a tangent to <strong>'.$topicParent['post_title'].'</strong>. This option will only affect this tangent. <br />If you would like to moderate the parent topic, please '.$moderateParent->show().'.</p>');
    
} 
    
    if (count($tangents) > 0) {
		$str = $this->objLanguage->languageText('mod_forum_topichasfollwoingtangents', 'This topic has the following [-COUNTTANGENTS-] tangent(s). Please indicate what you would like to happen to them as well?');
		
		$str = str_replace('[-COUNTTANGENTS-]', count($tangents), $str);
		
		$deleteForm->addToForm($str);
        
        $tangentList = '<ul>';
        foreach ($tangents as $tangent)
        {
            $tangentList .= '<li>'.$tangent['post_title'].'</li>';
        }
        $tangentList .= '</ul>';
        
        $deleteForm->addToForm($tangentList);
    }
    
    $deleteForm->addToForm('<fieldset><legend>'.$this->objLanguage->languageText('mod_forum_deleteoptions', 'Delete Options').'</legend><p><strong>');
    
    if (count($tangents) > 0) {
        $deleteForm->addToForm('a) ');
    }
    
    $deleteForm->addToForm($this->objLanguage->languageText('mod_forum_confirmdeletetopic', 'Are you sure you want to delete this topic?').'</strong> ');
    
    $deleteConfirm = new radio ('delete');
    $deleteConfirm->addOption('0', '<strong><span class="error">'.$this->objLanguage->languageText('word_no', 'No').'</span></strong>');
    $deleteConfirm->addOption('1', '<strong><span class="error">'.$this->objLanguage->languageText('word_yes', 'Yes').'</span></strong>');
    $deleteConfirm->setBreakSpace(' &nbsp; ');
    $deleteConfirm->setSelected('0');
    
    $deleteForm->addToForm($deleteConfirm->show().'</p>');
    
    if (count($tangents) > 0) {
        $deleteForm->addToForm('<p>b) '.$this->objLanguage->languageText('mod_forum_whathappentotangents', 'What should happen to the tangents?').'</p>');
        
        $tangentOption = new radio ('tangentoption');
        $tangentOption->addOption('delete', $this->objLanguage->languageText('mod_forum_deletealltangents', 'Delete all Tangents related to this topic'));
        
        $dropdown = new dropdown ('topicmove');
        
        if (count($otherTopicsInForum) > 0) {
            foreach ($otherTopicsInForum as $forumtopic)
            {
                $dropdown->addOption($forumtopic['topic_id'], $forumtopic['post_title']);
            }
            $tangentOption->addOption('move', $this->objLanguage->languageText('mod_forum_movetangentstofollowingtopic', 'Move them to the following topic').' - '.$dropdown->show());
        } 
        
        $tangentOption->addOption('newtopic', $this->objLanguage->languageText('mod_forum_movetangentstonewtopic', 'Move the Tangents to Topics. Each Tangent will be a new topic.'));
        
        // Preserve Users Default Selected Option
        $defaultOption = strtolower($this->getParam('option', NULL));
        
        if ($defaultOption == 'delete' || $defaultOption == 'move' || $defaultOption == 'newtopic') {
            $tangentOption->setSelected($defaultOption);
        } else {
            $tangentOption->setSelected('delete');
        }
        // End: Preserve
        
        $tangentOption->setBreakSpace('<br />');
        
        $deleteForm->addToForm('<blockquote>'.$tangentOption->show().'</blockquote>');
    }
    
    $button = new button ('confirmdelete');
    $button->value = $this->objLanguage->languageText('mod_forum_confirmdelete', 'Confirm Delete');
    $button->setToSubmit();
    

    
    $deleteForm->addToForm('<p align="center">'.$button->show().'</p></fieldset>');


//Add Hidden Id - Common to both
$deleteForm->addToForm($hiddeninput->show());

$switchmenu->addBlock($optionsCount.') '.$this->objLanguage->languageText('mod_forum_deletethetopic', 'Delete the Topic'), $deleteForm->show(), 'switchmenutext');




/**************************************************************************************
//////////////////// SECOND OPTION - MOVING TO TOPIC TO A TANGENT  ////////////////////
**************************************************************************************/


// Only show this option if there are other topics.
// You cant move a topic as a tangent to another topic if there aren't any other topics


if (count($otherTopicsInForum) > 0) {

    // Increase Options Count for next item
    $optionsCount++;

    $moveToTangentForm = new form ('moderate_movetotangent', $this->uri(array('action'=>'moderate_movetotangent')));
    $dropdown = new dropdown ('topicmove');
    
    foreach ($otherTopicsInForum as $forumtopic)
    {
        if ($forumtopic['topic_id'] != $topic['topic_tangent_parent']) {
            $dropdown->addOption($forumtopic['topic_id'], $forumtopic['post_title']);
        }
    }
    $moveToTangentForm->addToForm($this->objLanguage->languageText('mod_forum_movetopicastangent', 'Move the Topic as a tangent to the following topic').': '.$dropdown->show());
    
    if (count($tangents) > 0) {
		$str = $this->objLanguage->languageText('mod_forum_tangentsmovedwithtopic', '<strong>Note</strong> This topic has [-COUNTTANGENTS-] tangent(s). They will automatically become tangents to the selected topic.');
		$str = str_replace('[-COUNTTANGENTS-]', count($tangents), $str);
        $moveToTangentForm->addToForm('<p>'.$str.'</p>');
    }
    
    $button = new button ('confirmmovetotangent');
    $button->value = $this->objLanguage->languageText('mod_forum_confirmmovetopic', 'Confirm Move Topic');
    $button->setToSubmit();
    
    /// CONTINUE ADD BUTTON TO FORM
    
    $moveToTangentForm->addToForm('<p>'.$button->show().'</p>');
    
    //Add Hidden Id - Common to both
    $moveToTangentForm->addToForm($hiddeninput->show());
    
    $switchmenu->addBlock($optionsCount.') '.$this->objLanguage->languageText('mod_forum_movetopicastangent', 'Move it as a Tangent to another Topic'), $moveToTangentForm->show(), 'switchmenutext');

}




/**************************************************************************************
//////////////////// THIRD OPTION - MOVING TANGENT TO A NEW TOPIC  ////////////////////
**************************************************************************************/

if ($topic['topic_tangent_parent'] != '0') {
    // Increase Options Count for next item
    $optionsCount++;
    
    $moveToNewTopicForm = new form ('moderate_movetonewtopic', $this->uri(array('action'=>'moderate_movetonewtopic')));
    
    $moveToNewTopicForm->addToForm($this->objLanguage->languageText('mod_forum_confirmovetopicastangent', 'Are you sure you want to move this tangent to a new topic?').' ');
    
    $moveConfirm = new radio ('move');
    $moveConfirm->addOption('0', '<strong><span class="error">'.$this->objLanguage->languageText('word_no', 'No').'</span></strong>');
    $moveConfirm->addOption('1', '<strong><span class="error">'.$this->objLanguage->languageText('word_yes', 'Yes').'</span></strong>');
    $moveConfirm->setBreakSpace(' &nbsp; ');
    $moveConfirm->setSelected('0');
    
    $moveToNewTopicForm->addToForm($deleteConfirm->show().'</p>');
    
    $button = new button ('confirmdelete');
    $button->value = $this->objLanguage->languageText('mod_forum_confirmmovetonewtopic', 'Confirm Move to New Topic');
    $button->setToSubmit();
    
    $moveToNewTopicForm->addToForm('<p>'.$button->show().'</p>');
    
    //Add Hidden Id - Common to both
    $moveToNewTopicForm->addToForm($hiddeninput->show());
    
    $switchmenu->addBlock($optionsCount.') '.$this->objLanguage->languageText('mod_forum_confirmmovingnewtopic', 'Move it as a New Topic'), $moveToNewTopicForm->show(), 'switchmenutext');
    
}

/**************************************************************************************
//////////////////// FOURTH OPTION - LOCKING / UNLOCKING TOPIC  ///////////////////////
**************************************************************************************/


// Increase Options Count for next item
$optionsCount++;
    
    
    


$topicStatusForm = new form('topicStatusForm', $this->uri( array('module'=>'forum', 'action'=>'changetopicstatus')));

$objElement = new radio('topic_status');
$objElement->addOption('OPEN','<strong>'.$this->objLanguage->languageText('word_open').'</strong> - '.$this->objLanguage->languageText('mod_forum_allowusersreply'));
$objElement->addOption('CLOSE','<strong>'.$this->objLanguage->languageText('word_close').'</strong> - '.$this->objLanguage->languageText('mod_forum_preventusersreply'));

if ($topic['topicstatus'] == 'OPEN') {
    $objElement->setSelected('OPEN');
    $displayStyle = 'none';
} else {
    $objElement->setSelected('CLOSE');
    $displayStyle = 'block';
}
$objElement->extra = ' onClick="showReasonForm()"';
$objElement->setBreakSpace('<br />');
$topicStatusForm->addToForm('<p>'.$objElement->show().'</p>');

$topicStatusForm->addToForm('<div id="closeReason" style="display:'.$displayStyle.'">');

$header = new htmlheading();
$header->type=3;
$header->str=$this->objLanguage->languageText('mod_forum_providereason');
$topicStatusForm->addToForm($header->show());

$editor=&$this->newObject('htmlarea','htmlelements');
$reasonContent = $topic['lockReason'];

if ($reasonContent == '') {
    $reasonContent = ' <p>Here are some typical reasons why  topics are closed - Please edit this message</p>
 <ul>
   <li>The topic has reached a conclusion, the issue has been fully addressed </li>
   <li>The topic is moving into a direction out of bounds </li>
   <li>The topic is being used for inappropriate speech.</li>
 </ul>';
}

$editor->setName('reason');
$editor->setRows(10);
$editor->setColumns('100%');
$editor->setContent($reasonContent);
$editor->setDefaultToolBarSetWithoutSave();
$editor->context = TRUE;

$topicStatusForm->addToForm( $editor);

$topicStatusForm->addToForm('</div>');

$submitButton = new button('submit', $this->objLanguage->languageText('mod_forum_updatetopicstatus', 'Update Topic Status'));
$submitButton->setToSubmit();

$topicStatusForm->addToForm('<p>'.$submitButton->show().'</p>');

$topicHiddenInput = new textinput('topic');
$topicHiddenInput->fldType = 'hidden';
$topicHiddenInput->value = $topic['topic_id'];
$topicStatusForm->addToForm($topicHiddenInput->show());


$switchmenu->addBlock($optionsCount.') '.$this->objLanguage->languageText('mod_forum_lockingunlockingtopic', 'Locking / Unlocking a Topic'), $topicStatusForm->show(), 'switchmenutext');

/**************************************************************************************
//////////////////// FIFTH OPTION - MAKING THE TOPIC STICKY  //////////////////////////
**************************************************************************************/


// Increase Options Count for next item
$optionsCount++;

if ($topic['topic_tangent_parent'] == '0') {

    $stickyTopicForm = new form('stickyTopicForm', $this->uri( array('module'=>'forum', 'action'=>'moderate_topicsticky')));

    $objElement = new radio('stickytopic');
    $objElement->addOption('1','<strong>'.$this->objLanguage->languageText('word_sticky', 'Sticky').'</strong> - '.$this->objLanguage->languageText('mod_forum_stickytopicexplained', 'A Sticky topic always appears (sticks) on the top of a forum.'));
    $objElement->addOption('0','<strong>'.$this->objLanguage->languageText('word_notsticky', 'Not Sticky').'</strong> - '.$this->objLanguage->languageText('mod_forum_notstickytopicexplained', 'The topic will appear according to standard sorting criteria.'));
    
    $objElement->setSelected($topic['sticky']);


    $objElement->setBreakSpace('<br />');
    $stickyTopicForm->addToForm('<p>'.$objElement->show().'</p>');
    
    $submitButton = new button('submit', $this->objLanguage->languageText('mod_forum_updatestickystatus', 'Update Sticky Status'));
    $submitButton->setToSubmit();

    $stickyTopicForm->addToForm('<p>'.$submitButton->show().'</p>');
    
    $stickyTopicForm->addToForm($hiddeninput->show());

    $switchmenu->addBlock($optionsCount.') '.$this->objLanguage->languageText('mod_forum_makingtopicsticky', 'Making a Topic Sticky or Not'), $stickyTopicForm->show(), 'switchmenutext');
}

// Further Options
//    - Placing a comment on the topic of the topic
//    - Making the Topic Invisible


echo $switchmenu->show();

$returnLink = new link ($this->uri(array('action'=>'viewtopic', 'id'=>$topic['topic_id'])));
$returnLink->link = $this->objLanguage->languageText('mod_forum_returntotopic', 'Return to Topic').' - '.$topic['post_title'];

$returnForumLink = new link ($this->uri(array('action'=>'forum', 'id'=>$topic['forum_id'])));
$returnForumLink->link = $this->objLanguage->languageText('mod_forum_returntoforum', 'Return to Forum').' - '.$forum['forum_name'];

echo '<p align="center">'.$returnLink->show().' / '.$returnForumLink->show().'</p>';

//print_r($topic);

?>
<script language="JavaScript">

if(!document.getElementById && document.all)
document.getElementById = function(id){ return document.all[id]} 



    function showReasonForm()
    {
        if (document.topicStatusForm.topic_status[1].checked)
            {
                    showhide('closeReason', 'block');
                    var oEditor = FCKeditorAPI.GetInstance('reason') ;
                    try 
                    {
                        oEditor.MakeEditable();
                        
                    }
                    catch (e) {}
                    oEditor.Focus();
            } else{
                    showhide('closeReason', 'none');
            }
            
    }

    function showhide (id, visible)
    {
        var itemstyle = document.getElementById(id).style
        itemstyle.display = visible;
    } 
    
    var collAll = document.frames("reason___Frame").document.all;
    //var oEditor = collAll.FCKeditorAPI.GetInstance('reason') ;
    
    
</script>
