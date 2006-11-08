<?php
/**
* @package etd
* @filesource
*/

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}

/**
* Class for displaying a block for searching the etd repository using a simple search or link to the advanced search
* @author Megan Watson
* @copyright (c) 2006 UWC
* @version 0.2
*/

class block_etdlinks extends object
{
    /**
    * @var the block title
    */
    public $title;

    /**
    * Constructor
    */
    public function init()
    {
        $this->objLanguage =& $this->getObject('language', 'language');
        $this->objIcon =& $this->newObject('geticon', 'htmlelements');

        $this->loadClass('link','htmlelements');

        $this->title = $this->objLanguage->languageText('word_links');
    }

    /**
    * Method to display a search.
    *
    * @access public
    * @param string $break The break to use between radio buttons.
    */
    public function show()
    {   
        $stats = $this->objLanguage->languageText('phrase_viewstatistics');
        $submit = $this->objLanguage->languageText('phrase_newsubmission');
        $rss = $this->objLanguage->languageText('word_rss2');
        
        // Statistics page link
		$objLink = new link($this->uri(array('action' => 'viewstats')));
		$objLink->link = $stats;
		$list = '<p>'.$objLink->show().'</p>';
		
		// Submission link
		$objLink = new link($this->uri(array('action' => 'submit', 'mode' => 'addsubmission')));
		$objLink->link = $submit;
		$list .= '<p>'.$objLink->show().'</p>';
		
        // RSS link
		$this->objIcon->setIcon('rss', 'gif', 'icons/filetypes');
		$objLink = new link($this->uri(array('action' => 'rss')));
		$objLink->link = $rss;
		$list .= '<p>'.$this->objIcon->show().' '.$objLink->show().'</p>';

        return $list;
    }
}
?>