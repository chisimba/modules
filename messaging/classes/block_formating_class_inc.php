<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
	die("You cannot view this page directly");
}
// end security check

/**
* The class to display a list of fomat codes
*
* @author Kevin Cyster
*/
class block_formating extends object
{
    /*
    * @var object $objIcon The geticon class in the htmlelements module
    * @access private
    */
    private $objIcon;

    /*
    * @var object $objLanguage The language class in the language module
    * @access private
    */
    private $objLanguage;

    /*
    * @var string $title The title of the block
    * @access public
    */
    public $title;

    /*
    * @var string $heading The heading of the block
    * @access public
    */
    public $heading;

    /**
    * Constructor for the class
    */
    public function init()
    {
        // load html element classes
        $this->loadClass('htmltable', 'htmlelements');
        $this->loadClass('link', 'htmlelements');
        $this->objIcon = $this->getObject('geticon', 'htmlelements');
        
        // system classes
        $this->objLanguage = $this->getObject('language', 'language');

        // language items
        $this->heading = $this->objLanguage->languageText('mod_messaging_wordformating', 'messaging');
        $label = $this->objLanguage->languageText('mod_messaging_formating', 'messaging');  
        $help = $this->objLanguage->languageText('mod_messaging_helpclick', 'messaging');
        
        $this->objIcon->setIcon('help_small');
        $this->objIcon->align = 'top';
        $this->objIcon->title = $help;
        $this->objIcon->extra = ' onclick="alert(\''.$label.'\')"';
        $helpIcon = '<a href="#">'.$this->objIcon->show().'</a>';
        
        $this->title = $this->heading.'&nbsp;'.$helpIcon;
        
    }

    /**
    * Method to output a block with information on how help works
    */
    public function show()
	{
        $objTable = new htmltable();
        $objTable->cellspacing = '2';
        $objTable->cellpadding = '2';
        
        $objTable->startRow();
        $objTable->addCell('<font class="warning"><b>[ B ]</b></font>&nbsp;<b>'.$this->heading.'</b>&nbsp;<font class="warning"><b>[ /B ]</b></font>', '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell('<font class="warning"><b>[ U ]</b></font>&nbsp;<u>'.$this->heading.'</u>&nbsp;<font class="warning"><b>[ /U ]</b></font>', '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell('<font class="warning"><b>[ I ]</b></font>&nbsp;<i>'.$this->heading.'</i>&nbsp;<font class="warning"><b>[ /I ]</b></font>', '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell('<font class="warning"><b>[ RED ]</b></font>&nbsp;<font style="color: red;">'.$this->heading.'</font>&nbsp;<font class="warning"><b>[ /RED ]</b></font>', '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell('<font class="warning"><b>[ S=1 ]</b></font>&nbsp;<font size="2">'.$this->heading.'</f>&nbsp;<font class="warning"><b>[ /S ]</b></font>', '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell('<font class="warning"><b>[ S=2 ]</b></font>&nbsp;<font size="3">'.$this->heading.'</f>&nbsp;<font class="warning"><b>[ /S ]</b></font>', '', '', '', '', '');
        $objTable->endRow();
        $formatTable = $objTable->show();
        $str = $formatTable;  
              
        return $str;
    }
}
?>