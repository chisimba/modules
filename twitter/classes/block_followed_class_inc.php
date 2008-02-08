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
class block_followed extends object
{
    var $title;

    /**
    * Constructor for the class
    */
    function init()
    {
        $this->objLanguage = $this->getObject('language', 'language');
        $this->title=$this->objLanguage->languageText("mod_twitter_followed", "twitter");
    }

    /**
    * Method to output a Tweet block
    */
    function show()
	{
        $objTwitterRemote = $this->getObject('twitterremote', 'twitter');
        return $objTwitterRemote->showFollowed();
    }
}