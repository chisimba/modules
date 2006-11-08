<?php
/**
* Block for the right menu 
*
* @package etd
* @author Megan Watson
* @version 0.1
* @copyright (c) UWC 2006
*/

class block_rightmenu extends object
{
    /**
    * @var the block title
    */
    public $title;
    
    /**
    * Constructor for the class
    */
    public function init()
    {
        $this->objLanguage =& $this->getObject('language','language');
        $this->objIcon =& $this->newObject('geticon', 'htmlelements');
        $this->loadClass('link', 'htmlelements');
        
        //Set the title
        $this->title = $this->objLanguage->languageText('word_menu');
    }
    
    /**
    * The display method for the block
    */
    public function show()
	{
        $browse = $this->objLanguage->languageText('word_browse');
        $collections = $this->objLanguage->languageText('word_collections');
        $authors = $this->objLanguage->languageText('word_authors');
        $titles = $this->objLanguage->languageText('word_titles');
        $stats = $this->objLanguage->languageText('phrase_viewstatistics');
        $submit = $this->objLanguage->languageText('phrase_newsubmission');
        $rss = $this->objLanguage->languageText('word_rss2');
        
	    // Browse menu items
        $list = '<b>'.$browse.':</b><br /><ul>';
        
        $objLink = new link($this->uri(array('action'=>'browsecollection')));
        $objLink->link = $collections;
        $list .= '<li style="padding-bottom: 5px;">'.$objLink->show().'</li>';
        
        $objLink = new link($this->uri(array('action'=>'browseauthor')));
        $objLink->link = $authors;
        $list .= '<li style="padding-bottom: 5px;">'.$objLink->show().'</li>';
        
        $objLink = new link($this->uri(array('action'=>'browsetitle')));
        $objLink->link = $titles;
        $list .= '<li>'.$objLink->show().'</li>';
        
        $list .= '</ul>';
        
        // Statistics page link
		$objLink = new link($this->uri(array('action' => 'viewstats')));
		$objLink->link = $stats;
		$list .= '<p>'.$objLink->show().'</p>';

        // RSS link
		$this->objIcon->setIcon('rss', 'gif', 'icons/filetypes');
		$objLink = new link($this->uri(array('action' => 'rss')));
		$objLink->link = $rss;
		$list .= '<p>'.$this->objIcon->show().' '.$objLink->show().'</p>';
		
		// Submission link
		$objLink = new link($this->uri(array('action' => 'submit', 'mode' => 'addsubmission')));
		$objLink->link = $submit;
		$list .= '<p>'.$objLink->show().'</p>';
		
        return $list;
    }
}