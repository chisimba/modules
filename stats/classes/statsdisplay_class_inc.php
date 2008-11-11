<?php
/**
* Class statsdisplay
* 
* @author James Scoble
*/
class statsdisplay extends object
{
 
    /**
    * init function used by object-derived classes
    * instantiates other classes used
    */
    function init()
    {   

    }


    function defaultMessage()
    {
        $output=file_get_contents($this->getResourcePath('display.txt','stats'));
        return $output;
    }
    
}
?>
