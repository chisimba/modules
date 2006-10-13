<?
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
	die("You cannot view this page directly");
}
// end security check

/**
* A block to return the last 10 podcasts entries
*
* @author Derek Keats

* 
* $Id$
*
*/
class block_dictionary extends object
{
    /**
    * @var string $title The title of the block
    */
    var $title;
    
    /**
    * @var object $objDicIntf String to hold the dictionary interface object
    */
    var $objDicIntf;

    /**
    * @var object $objLanguage String to hold the language object
    */    
    var $objLanguage;
    
    /**
    * Standard init function to instantiate language and user objects
    * and create title
    */
    function init()
    {
        //Create an instance of the language object
        $this->objLanguage = &$this->getObject("language", "language");
        $font = "<font size=\"-2\">";
        $cFont = "</font>";
        //add the title 
        $this->title = $font . 
          $this->objLanguage->languageText("mod_dictionary_title")
          . $cFont;
        //Create an instance of the database class for this module
        $this->objDicIntf = & $this->getObject('dicinterface');
    }
   
    /**
    * Standard block show method. It builds the output based
    * on data obtained
    */ 
    function show()
	{
        return  $this->objDicIntf->makeSearch();
    }
}