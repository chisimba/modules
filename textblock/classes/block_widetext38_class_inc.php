<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
	die("You cannot view this page directly");
}
// end security check

/**
* 
* The class provides a wide text block widetext38
*
* @author Derek Keats
*
*/

$this->loadClass("textblockbase", "textblock");

class block_widetext38 extends textblockbase
{
    
    /**
    * Constructor for the class
    */
    function init()
    {
    	parent::init();
        $this->setData("widetext38");
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