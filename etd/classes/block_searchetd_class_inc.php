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
        $this->loadClass('textinput','htmlelements');

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
        $objInput = new textinput('searchField');
        $objInput->size = 10;
        $search = '<p>'.$lbKeywords.':<br />'.$objInput->show().'</p>';

        $objInput = new textinput('searchAuthors');
        $objInput->size = 10;
        $search .= '<p>'.$lbAuthors.':<br />'.$objInput->show().'</p>';

        $objButton = new button('search', $searchLabel);
        $objButton->setToSubmit();
        $search .= '<p>'.$objButton->show().'</p>';

        $objForm = new form('search', $this->uri(array('action' => 'advsearch', 'mode'=>'simple')));
        $objForm->addToForm($search);
        $str = $objForm->show();

        $objLink = new link($this->uri(array('action' => 'search')));
        $objLink->link = $advSearchLabel;
        $str .= '<p>'.$objLink->show().'</p>';

        return $str;
    }
}
?>