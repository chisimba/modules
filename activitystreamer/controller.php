<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
	die("You cannot view this page directly");
}
// end security check
class activitystreamer extends controller
{
	
	public function init()
	{
		try {
			
		}
		catch(customException $e) {
			echo customException::cleanUp();
			die();
		}
	}
	
	 /**
     *
     * The standard dispatch method for
     *
     */
    public function dispatch()
    {
       
    }
	
}