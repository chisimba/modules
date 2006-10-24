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

class block_searchetd extends object
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

        $this->loadClass('button','htmlelements');
        $this->loadClass('form','htmlelements');
        $this->loadClass('link','htmlelements');
        $this->objInput =& $this->loadClass('textinput','htmlelements');

        $this->title = $this->objLanguage->languageText('word_search');
    }

    /**
    * Method to display a search.
    * @param string $break The break to use between radio buttons.
    */
    public function show()
    {
        $searchLabel = $this->objLanguage->languageText('word_search');
        $advSearchLabel = $this->objLanguage->languageText('phrase_advancedsearch');
        $lbKeywords = $this->objLanguage->languageText('word_keywords');
        $lbAuthors = $this->objLanguage->languageText('word_author');

        // search button and input
        $this->objInput->textinput('searchField');
        $this->objInput->size = 10;
        $search = '<p>'.$lbKeywords.':<br />'.$this->objInput->show().'</p>';

        $this->objInput->textinput('searchAuthors');
        $this->objInput->size = 10;
        $search .= '<p>'.$lbAuthors.':<br />'.$this->objInput->show().'</p>';

        $this->objButton->button('search', $searchLabel);
        $this->objButton->setToSubmit();
        $search .= '<p>'.$this->objButton->show().'</p>';

        $this->objForm->form('search', $this->uri(array('action' => 'advsearch', 'mode'=>'simple')));
        $this->objForm->addToForm($search);
        $str = $this->objForm->show();

        $this->objLink = new link($this->uri(array('action' => 'search')));
        $this->objLink->link = $advSearchLabel;
        $str .= '<p>'.$this->objLink->show().'</p>';

        return $str;
    }
}
?>