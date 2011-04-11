<?PHP
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}

/**
*
* Controller class for Chisimba for the module tzschoolacedemics
* which uses the vlc library to wrap and
* create an online vlc player
*
* @author
* @package  tzschoolacedemics
*
*/


class  tzschoolacedemics extends controller {

    var $tzacademicdb;
	 /**
     * Intialiser for the stories controller
     *
     * @param byref $ string $engine the engine object
     */
    public function init()
    {        //Instantiate the language object
       $this->tzacademicdb = $this->newObject('tzschoolacedemicsdb');
    }


    public function dispatch()
    {
    	
    }



}
?>