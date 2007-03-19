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
class block_formatting extends object
{
    /*
    * @var object $objIcon: The geticon class in the htmlelements module
    * @access private
    */
    private $objIcon;

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

    /*
    * @var string $heading: The heading of the block
    * @access private
    */
    private $heading;

    /**
    * Constructor for the class
    *
    * @access public
    * @return
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
        $title = $this->objLanguage->languageText('mod_messaging_wordformatting', 'messaging');
        $label = $this->objLanguage->languageText('mod_messaging_formatting', 'messaging');  
        $help = $this->objLanguage->languageText('mod_messaging_help', 'messaging');
        
        // help icon
        $this->objIcon->setIcon('help_small');
        $this->objIcon->align = 'top';
        $this->objIcon->title = $help;
        $this->objIcon->extra = 'style="cursor: help;" onclick="javascript:
            var el_Div = document.getElementById(\'formatHelpDiv\');
            jsShowHelp(el_Div);"';
        $helpIcon = $this->objIcon->show();
        
        // help layer
        $objLayer = new layer();
        $objLayer->id = 'formatHelpDiv';
        $objLayer->display = 'none';
        $objLayer->addToStr('<font size="1">'.$label.'</font>');
        $helpLayer = $objLayer->show();
        
        // title
        $this->title = $title.'&nbsp;'.$helpIcon.$helpLayer;                
    }

    /**
    * Method to output a block with format codes
    * 
    * @access public
    * @return string $str: The output string
    */
    public function show()
	{
        // language items
        $bold = $this->objLanguage->languageText('mod_messaging_bold', 'messaging');
        $underline = $this->objLanguage->languageText('mod_messaging_underline', 'messaging');
        $italics = $this->objLanguage->languageText('mod_messaging_italics', 'messaging');
        $colour = $this->objLanguage->languageText('mod_messaging_colour', 'messaging');
        $size = $this->objLanguage->languageText('mod_messaging_size', 'messaging');
        
        // main table
        $objTable = new htmltable();
        $objTable->cellspacing = '2';
        $objTable->cellpadding = '2';
        
        $objTable->startRow();
        $objTable->addCell('[B]&nbsp;<b>'.$bold.'</b>&nbsp;[/B]', '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell('[U]&nbsp;<u>'.$underline.'</u>&nbsp;[/U]', '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell('[I]&nbsp;<i>'.$italics.'</i>&nbsp;[/I]', '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell('[RED]&nbsp;<font style="color: red;">'.$colour.'</font>&nbsp;[/RED]', '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell('[BLUE]&nbsp;<font style="color: blue;">'.$colour.'</font>&nbsp;[/BLUE]', '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell('[S1]&nbsp;<font size="2">'.$size.'</font>&nbsp;[/S1]', '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell('[S2]&nbsp;<font size="3">'.$size.'</font>&nbsp;[/S2]', '', '', '', '', '');
        $objTable->endRow();
        $formatTable = $objTable->show();
        $str = $formatTable;  
              
        return $str;
    }
}
?>