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
    public $objLanugage;
    
    /**
    * Constructor for the class
    */
    function init()
    {
    	//Instantiate the language object
    	$this->objLanguage = &$this->getObject('language', 'language');
        //Set the title - 
        $this->title=$this->objLanguage->languageText("mod_blockalicious_commlinks_title", "blockalicious");
    }
    
    /**
    * Method to output a block with information on how help works
    */
    function show()
	{
		//Initialize return string
		$ret = "";
		//Instant the module object to check if modules are registered
		$objModule=& $this->getObject('modules','modulecatalogue');
		$this->loadClass('href', 'htmlelements');
		//Add blog link
		if ($objModule->checkIfRegistered('blog','blog')) {
			$ln = new href;
			$ln->link="index.php?module=blog";
			$ln->text="Blog";
			$ret .= $ln->show() . "<br />";
		}
		if ($objModule->checkIfRegistered('podcast','podcast')) {
			$ln = new href;
			$ln->link="index.php?module=podcast";
			$ln->text="Podcast";
			$ret .= $ln->show() . "<br />";
		}
		if ($objModule->checkIfRegistered('wiki','wiki')) {
			$ln = new href;
			$ln->link="index.php?module=wiki";
			$ln->text="Wiki";
			$ret .= $ln->show() . "<br />";
		}
       return $ret;
    }
}