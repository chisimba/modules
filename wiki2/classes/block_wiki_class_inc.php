<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
	die("You cannot view this page directly");
}
// end security check

/**
* The class to display a block for smiley icons
*
* @author Kevin Cyster
*/
class block_wiki extends object
{
    /*
    * @var object $objLanguage: The language class in the language module
    * @access private
    */
    private $objLanguage;

    /*
    * @var string $title: The title of the block
    * @access public
    */
    public $title;

    /**
    * Constructor for the class
    *
    * @access public
    * @return
    */
    public function init()
    {
        // load html element classes
        $this->objLink = $this->newObject('link', 'htmlelements');
        
        // system classes
        $this->objLanguage = $this->getObject('language', 'language');
        
        // language items
        $this->title = $this->objLanguage->languageText('mod_wiki2_name', 'wiki2');
    }

    /**
    * Method to output a block with smiley icons
    *
    * @access public
    * @return string $str: The output string
    */
    public function show()
	{
        // language items
        $wikiLabel = $this->objLanguage->languageText('mod_wiki2_name', 'wiki2');  
        $wikiTitleLabel = $this->objLanguage->languageText('mod_wiki2_title', 'wiki2');  
                      
        // the exit link
        $this->objLink = new link($this->uri(array(), 'wiki2'));
        $this->objLink->link = $wikiLabel;
        $this->objLinkk->title = $wikiTitleLabel;
        $wikiLink = $this->objLink->show();
         
        return $wikiLink;
    }
}
?>