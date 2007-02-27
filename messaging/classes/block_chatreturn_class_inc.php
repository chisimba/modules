<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
	die("You cannot view this page directly");
}
// end security check

/**
* The class to display an exit link in a block
*
* @author Kevin Cyster
*/
class block_chatreturn extends object
{
    /*
    * @var object $objLanguage: The language class in the language module
    * @access public
    */
    public $objLanguage;

    /*
    * @var object $title: The title of the block
    * @access public
    */
    public $title;

    /**
    * Constructor for the class
    */
    public function init()
    {
        // load html element classes
        $this->loadClass('htmltable', 'htmlelements');
        $this->loadClass('link', 'htmlelements');
        
        // system classes
        $this->objLanguage = $this->getObject('language', 'language');
    }

    /**
    * Method to output a block with information on how help works
    */
    public function show()
	{
        $returnLabel = $this->objLanguage->languageText('mod_messaginge_return', 'messaging');  
        $returnTitleLabel = $this->objLanguage->languageText('mod_messaginge_returntitle', 'messaging');  
                      
        $objLink = new link($this->uri(array(
            'action' => 'leaveroom',
        ), 'messaging'));
        $objLink->link = $returnLabel;
        $objLink->title = $returnTitleLabel;
        $backLink = $objLink->show();
         
        $objTable = new htmltable();
        $objTable->cellspaccing = '2';
        $objTable->cellpadding = '2';
        
        $objTable->startRow();
        $objTable->addCell($backLink, '', '', 'center', '', '');
        $objTable->endRow();
        $str = $objTable->show();
        
        return $str;
    }
}
?>