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
class block_xxx extends object
{
    var $title;

    /**
    * Constructor for the class
    */
    public function init()
    {
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objUser = $this->getObject('user', 'security');
        $this->title="";
        $this->blockType = "none";
    }

    /**
    * Method to output a Tweet block
    */
    public function show()
	{
        return $this->getWidget();
    }

    private function getWidget()
    {
        return '';
    }
}