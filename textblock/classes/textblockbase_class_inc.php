<?
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
	die("You cannot view this page directly");
}
// end security check

/**
* 
* The class provides a hello world block to demonstrate
* how to use blockalicious
*
* @author Derek Keats
*
*/
class textblockbase extends object
{
    public $title;
    private $objDb;
    private $objLanguage;
    public $blockContents;
    
    /**
    * Constructor for the class
    */
    function init()
    {
    	//Create an instance of the textblock DBtable object
        $this->objDb = $this->getObject("dbtextblock", "textblock");
        //Create an instance of the language object
        $this->objLanguage = &$this->getObject("language", "language");
    }
    
    /**
    * Method to output a block with information on how help works
    */
    function setData($textItem)
	{
		$ar = $this->objDb->getRow("blockid", $textItem);
		if (count($ar) > 0 ) {
		    $this->title = $ar['title'];
		    $this->blockContents = $ar['blocktext'];
		} else {
		    $this->title = $textItem;
		    $this->blockContents = $this->objLanguage->languageText("mod_textblock_nocontent", "textblock");
		}
       	return TRUE;
    }
}