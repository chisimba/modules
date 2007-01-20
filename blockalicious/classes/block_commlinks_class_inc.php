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
class block_commlinks extends object
{
    public $title;
    public $objLanguage;
    public $messageForBlock;
    
    /**
    * Constructor for the class
    */
    function init()
    {
    	//Instantiate the language object
    	$this->objLanguage = &$this->getObject('language', 'language');
    	$this->_objContent = $this->getObject('dbcontent', 'cmsadmin');
		$objModule = $this->getObject('modules','modulecatalogue');
		if ($objModule->checkIfRegistered('blog', 'blof')) {
			$this->messageForBlock = "<a href=\"index.php?module=blog\">Blog</a><br />";		    
		}
		if ($objModule->checkIfRegistered('podcast', 'podcast')) {
			$this->messageForBlock .= "<a href=\"index.php?module=Podcast\">Podcast</a><br />";
		}
		if ($objModule->checkIfRegistered('wiki', 'wiki')) {
			$this->messageForBlock .= "<a href=\"index.php?module=wiki\">Wiki</a><br />";
		}
		//Set the title - 
        $this->title=$this->objLanguage->languageText("mod_blockalicious_commlinks_title", "blockalicious");
    }
    
    /**
    * Method to output a block with all news stories
    */
    function show()
	{
       return $this->messageForBlock;
    }
}