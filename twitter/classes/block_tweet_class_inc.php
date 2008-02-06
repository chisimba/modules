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
class block_tweet extends object
{
    var $title;
    
    /**
    * Constructor for the class
    */
    function init()
    {
        //Set the title - 
        $this->title='Twitter';
    }
    
    /**
    * Method to output a Tweet block
    */
    function show()
	{
        $objWidjet = $this->getObject("twitterwidgets","twitter");
        return $objWidjet->sendWidget();
    }
}