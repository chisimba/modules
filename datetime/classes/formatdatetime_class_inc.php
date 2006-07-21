<?php
/**
* Class to format date and time
*
* This class formats standard datetime fields into a more human readable format.
* It will perform stripping, parsing for you.
*
* @author Tohir Solomons
*/
class formatdatetime 
{
    
    /**
    * Constructor
    */
    function formatdatetime()
    { }
    
    /**
    * Method to format date into an English Format
    * Given 2006-07-20 10:57:38, it will return it as 20 July 2006
    * @param string $date Date Time String
    * @return string Formatted Date
    */
    function formatDate($date)
    {
        
        if (isset($date)) {
            $date = getdate(strtotime($date)); 
            
            return ($date['mday'].' '.$date['month'].' '. $date['year']);
        }
    }
    
    /**
    * Method to format time
    * Given 2006-07-20 13:26:38, it will return it as 13:26
    * @param string $time Date Time String
    * @return string Formatted Rime
    */
    function formatTime($time)
    {
        $time = getdate(strtotime($time)); 
        
        // Check whether to add a zero prior to the minute.
        if ($time['minutes'] < 10) {
            $zeroes = '0';
        } else {
            $zeroes = '';
        }
        
        return ($time['hours'].':'.$zeroes.$time['minutes']);
    }
} 

?>