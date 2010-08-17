<?php
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
    public function init()
    {
    	//Create an instance of the textblock DBtable object
        $this->objDb = $this->getObject("dbtextblock", "textblock");
        //Create an instance of the language object
        $this->objLanguage = $this->getObject("language", "language");
    }
    
    /**
    * Method to render the block with the text item
    * @param string $textItem The content to render
    * @access public
    *
    */
    public function setData($id)
    {
        $ar = $this->objDb->getRow("id", $id);
        if (count($ar) > 0 ) {
            $this->showTitle = $ar['show_title'];
            if ($this->showTitle=="1") {
                $this->title = $ar['title'];
            } else {
                $this->title = FALSE;
            }
			
            $cssId = ($ar['css_id'])? "id='{$ar['css_id']}'" : "";
            $cssClass = ($ar['css_class'])? "class='{$ar['css_id']}'" : "class='featurebox'";
           
            $objWashout = $this->getObject("washout", "utilities");
            $ret = $objWashout->parseText($ar['blocktext']);
            
			$this->blockContents = "<div $cssId $cssClass>$ret</div>";
        } else {
            $this->title = $id;
            $this->blockContents = $this->objLanguage->languageText("mod_textblock_nocontent", "textblock");
        }
        return TRUE;
    }
	
	/**
    * Method to output a block with text content
    */
    function show()
	{
        return $this->blockContents;
    }
}
?>