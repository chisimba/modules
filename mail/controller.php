<?php
/* security check - must be included in all scripts */
if (!$GLOBALS['kewl_entry_point_run'])
{
	die("You cannot view this page directly");
}
/* end security check */

/**
* Simple demonstration module
* @author yourname here
* @package yourpackage here
*/
class mail extends controller
{
    /**
    *
    * Standard init method for KINKY controller
    *
    */
    function init()
    {

    }
    
    /**
    *
    * Dispatch method
    *
    */
    function dispatch()
    {
            return "dump_tpl.php";
    }
}    
?>
