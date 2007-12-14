<?php

class renderstory extends object
{

    public function init()
    {
		$this->objUser = $this->getObject('user', 'security');
        $this->loadClass('link', 'htmlelements');
		$this->loadClass('htmlheading', 'htmlelements');
        $this->objLanguage = $this->getObject('language', 'language');
		
		$this->objStories = $this->getObject('dbnewsstories');
        // Load Menu Tools Class
        $this->objMenuTools = $this->getObject('tools', 'toolbar');
		
		// Permissions Module
        $this->objDT = $this->getObject( 'decisiontable','decisiontable' );
        // Create the decision table for the current module
        $this->objDT->create('news');
        // Collect information from the database.
        $this->objDT->retrieve('news');
		
		$this->objIcon = $this->newObject('geticon', 'htmlelements');
    }
    
    public function render($story, $category)
	{
		$objDateTime = $this->getObject('dateandtime', 'utilities');
        
        $categoryLink = new link ($this->uri(array('action'=>'viewcategory', 'id'=>$category['id'])));
        $categoryLink->link = $category['categoryname'];
        
        $this->objMenuTools->addToBreadCrumbs(array($categoryLink->show(), $story['storytitle']));
        
        $this->setVar('pageTitle', $story['storytitle']);
        
        $header = new htmlheading();
        $header->type = 1;
        $header->str = $story['storytitle'];
        
        if ($this->objDT->isValid('editstory')) {
            $this->objIcon->setIcon('edit');
            $this->objIcon->alt = $this->objLanguage->languageText('mod_news_editstory', 'news', 'Edit Story');
            $this->objIcon->title = $this->objLanguage->languageText('mod_news_editstory', 'news', 'Edit Story');
            $editLink = new link ($this->uri(array('action'=>'editstory', 'id'=>$story['id'])));
            $editLink->link = $this->objIcon->show();
            
            $header->str .= ' '.$editLink->show();
        }
        
        if ($this->objDT->isValid('deletestory')) {
            $this->objIcon->setIcon('delete');
            $this->objIcon->alt = $this->objLanguage->languageText('mod_news_deletestory', 'news', 'Delete Story');
            $this->objIcon->title = $this->objLanguage->languageText('mod_news_deletestory', 'news', 'Delete Story');
            $editLink = new link ($this->uri(array('action'=>'deletestory', 'id'=>$story['id'])));
            $editLink->link = $this->objIcon->show();
            
            $header->str .= ' '.$editLink->show();
        }
        
        
        
        $str = $header->show();
        
        $str .= '<p>'.$objDateTime->formatDate($story['storydate']).'</p>';
        
        /*
        if ($story['storyimage'] != '') {
            $objThumbnails = $this->getObject('thumbnails', 'filemanager');
            $str .= '<img class="storyimage" src="'.$objThumbnails->getThumbnail($story['storyimage'], $story['filename']).'" alt="'.$story['storytitle'].'" title="'.$story['storytitle'].'" />';
        }
        */

        $objWashOut = $this->getObject('washout', 'utilities');

        $str .= $objWashOut->parseText($story['storytext']);

        if ($story['storysource'] != '') {
            $objUrl = &$this->getObject('url', 'strings'); 
            
            $source = $story['storysource'];
            
            $source = $objUrl->makeClickableLinks(htmlentities($source));
            
            $str .= '<p><strong>'.$this->objLanguage->languageText('word_source', 'word', 'Source').':</strong><br />'.$source.'</p>';
        }
        
        $serverPage = urlencode('http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
        
        $stumbleUpon = new link ('http://www.stumbleupon.com/submit?url='.$serverPage);
        $stumbleUpon->link = '<img src="'.$this->getResourceURI('stumbleupon.png').'" /> Stumble Upon';
        
        $delicious = new link ('http://del.icio.us/post?url='.$serverPage);
        $delicious->link = '<img src="'.$this->getResourceURI('delicious.png').'" /> del.icio.us';
        
        $newsvine = new link ('http://www.newsvine.com/_tools/seed&amp;save?u='.$serverPage);
        $newsvine->link = '<img src="'.$this->getResourceURI('newsvine.png').'" /> Newsvine';
        
        $reddit = new link ('ttp://reddit.com/submit?url='.$serverPage);
        $reddit->link = '<img src="'.$this->getResourceURI('reddit.png').'" /> Reddit';
        
        $muti = new link ("javascript:location.href='http://muti.co.za/submit?url='+encodeURIComponent(location.href)+'&amp;title='+encodeURIComponent(document.title)");
        $muti->link = '<img src="'.$this->getResourceURI('muti.png').'" /> muti';
        
        $facebook = new link ('http://www.facebook.com/share.php?u='.$serverPage);
        $facebook->link = '<img src="'.$this->getResourceURI('facebook_share_icon.gif').'" /> Facebook';
        
        $addThis = new link ('http://www.addthis.com/bookmark.php?pub=&amp;url='.$serverPage);
        $addThis->link = '<img src="http://www.addme.com/images/button1-bm.gif" width="125" height="16" border="0" alt="Bookmark this post" />';
        
        
        $table = $this->newObject('htmltable', 'htmlelements');
        $table->startRow();
        $table->addCell('<script type="text/javascript">
				           daigg_skin = \'compact\';
				       </script><script src="http://digg.com/tools/diggthis.js" type="text/javascript"></script>');
        $table->addCell($stumbleUpon->show().' '.$delicious->show().' '.$reddit->show().'<br />'.$newsvine->show().' '.$facebook->show().' '.$muti->show().'<p>'.$addThis->show().'</p>', NULL, NULL, 'center');
        $table->endRow();
        
        //$str .= $table->show();
        
        $objComments = $this->getObject('dbnewscomments');
        $str .= $objComments->getStoryComments($story['storyid']);
        $str .= $objComments->commentsForm($story['storyid']);
        
        return $str;
    }


}
?>