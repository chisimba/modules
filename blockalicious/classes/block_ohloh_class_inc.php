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
class block_ohloh extends object
{
    var $title;

    /**
    * Constructor for the class
    */
    function init()
    {
        //Set the title -
        $this->title='';
        $this->blockType = "none";

    }

    /**
    * Method to output a block with information on how help works
    */
    function show()
	{
       return "<span style=\"text-align: center;\">"
         . "<p style=\"text-align: center;\">"
         . "<script type=\"text/javascript\" "
         . "src=\"http://www.ohloh.net/projects/5263/widgets/project_thin_badge\">"
         . "</p></span></script>";
    }
}