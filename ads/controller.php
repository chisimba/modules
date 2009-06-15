<?php

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}
// end security check

class ads extends controller
{


    function init()
    {
        //Get language class
        $this->objLanguage = $this->getObject('language', 'language');

        $this->objLog = $this->getObject('logactivity', 'logger');

        //Log this module call
        $this->objLog->log();

    }

        /**
        * Method to process actions to be taken
        *
        * @param string $action String indicating action to be taken
        */
    function dispatch($action = Null) {
        switch ($action)   {
            case 'overview':
                {
                    return "overview_tpl.php";
                }
                default :{
                        return "home_tpl.php";
                    }
                }
            }


        }
        ?>
