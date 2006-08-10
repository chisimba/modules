<?php

$this->loadClass('link', 'htmlelements');
$this->loadClass('form', 'htmlelements');
$this->loadClass('radio', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');

$objIcon = $this->getObject('geticon', 'htmlelements');


$objHighlightLabels = $this->getObject('highlightlabels', 'htmlelements');
echo $objHighlightLabels->show();

echo '<h1>'.$this->objLanguage->languageText('mod_forum_moderatepost').': <em>'.$post['post_title'].'</em></h1>';

echo $postDisplay;

if ($post['postright'] - $post['postleft'] > 1) {
    $replies = $this->objPost->buildChildTree($post['post_id']);
}


if ($post['postleft'] == 1) {
    
    // $objIcon->setIcon('warning');
    // $message = '<p class="warning">'.$objIcon->show().' Warning </p> <p>This is the first/message post in this topic, and is the topic itself. If you wish to delete this topic, please go to the moderation options for this topic.</p>';
    
    // $link = new link ($this->uri(array('action'=>'moderatetopic', 'id'=>$post['topic_id'])));
    // $link->link = 'Moderate Topic';
    
    // $message .= '<p>'.$link->show().'</p>';
    
    // echo $message;
} else {

    $form = new form ('deletepost', $this->uri(array('action'=>'moderatepostdeleteconfirm')));
    $hiddenId = new hiddeninput('id', $post['post_id']);
    $form->addToForm($hiddenId->show());

    $form->addToForm('<h3><strong>'.$this->objLanguage->languageText('mod_forum_confirmdeletepost').'</strong></h3>');

    $radio = new radio('confirmdelete');
    $radio->addOption('N', '<strong>'.$this->objLanguage->languageText('word_no').'</strong> - '.$this->objLanguage->languageText('mod_forum_donotdeletepost'));
    $radio->addOption('Y', '<strong>'.$this->objLanguage->languageText('word_yes').'</strong> - '.$this->objLanguage->languageText('mod_forum_deletepost'));
    $radio->setSelected('N');
    $radio->setBreakspace(' <br /><br />  ');

    $form->addToForm($radio->show());

    if ($post['postright'] - $post['postleft'] > 1) {
        $form->addToForm('<div class="forumTangentIndent"><span class="warning">'.$this->objLanguage->languageText('word_warning').'</span>: '.$this->objLanguage->languageText('mod_forum_confirmdeletepost').'<br /><br />'.$replies.'</div>');
    }

    $button = new button('save', $this->objLanguage->languageText('mod_forum_confirmdelete'));
    $button->setToSubmit();
    $form->addToForm('<p>'.$button->show().'</p>');
    
    echo $form->show();
}


?>