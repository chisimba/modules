<?php
/**
* 
* A class to work with dates and return a pair of values, $startDate and 
* $endDate based on some criteria. The functions set the values of these
* properties, and return true or false. This class is specialized to that
* function and does nothing else. 
* 
* Typical uses are to return date pairs to allow some SQL to return data 
* values for the pair, such as show all blogs for this week, show new 
* content this month, show discussion forum entries today, etc.
* 
* @author Derek Keats 
*/

class datepair extends object 
{
    /**
    * 
    * @var date $startDate The beginning date for the specified period 
    * as set by one of the methods.
    */
    var $startDate;
    /**
    * 
    * @var date $endDate The ending date for the specified period 
    * as set by one of the methods
    */
    var $endDate;

    /**
    * Method to set the startdate and enddate for this week
    * 
    * @return True 
    */
    function thisWeek()
    {
        $this->startDate = $this->_getAsCompDate($this->_getStartOfWeek());
        $this->endDate = $this->_getAsCompDate($this->_getDateTomorrow());
        return true;
    } 

    /**
    * Method to set the startdate and enddate for this month
    * 
    * @return True 
    */
    function thisMonth()
    {
        $this->startDate = $this->_getStartOfMonth();
        $this->endDate = $this->_getAsCompDate($this->_getDateTomorrow());
        return true;
    } 

    /**
    * Standard show method, in this case it returns the SQL WHERE
    * clause
    * 
    * @param string $dateFieldName The name of the date field, defaults
    * to dateAdded
    */
    function show($dateFieldName = "dateAdded")
    {
        return " " . $dateFieldName . " >= '" . $this->startDate
         . "' AND " . $dateFieldName . " <= '" . $this->endDate . "'";
    } 

    /**
    * Method to set the start date and end date to the beginning and
    * end of the month when passed the shift from the current month
    * 
    * @param int $shift The number of months to shift from the current month
    * @return True 
    */
    function setMonthPair($shift)
    {
        $myDateLower = mktime(0, 0, 0, date("n") + $shift, 1, date("Y")); //first day of month
        $myDateUpper = mktime(0, 0, 0, date("n") + $shift + 1, 0, date("Y")); // last day of the month 
        $this->startDate = $this->_getAsCompDate($myDateLower);
        $this->endDate = $this->_getAsCompDate($myDateUpper);
        return true;
    } 

    /**
    * Method to evaluate if a year is a leap year or not
    * 
    * @param int $year The year to check
    * @return True | False
    */
    function isLeapYear($year)
    {
        if (($year % 4 == 0) && (($year % 100 != 0) || ($year % 400 == 0))) {
            return "true";
        } else {
            return "false";
        } 
    } 

    /*------------------------- PRIVATE METHODS BELOW LINE ---------------------*/
    /**
    * Return todays date as a unix timestamp
    */
    function _getDateToday()
    {
        return mktime(0, 0, 0, date("n"),
            date("d"), date("Y"));
    } 
    /**
    * Return tomorrow's date as a unix timestamp
    */
    function _getDateTomorrow()
    {
        return $this->_getDateToday() + 86400;
    } 
    /**
    * Return the first day of the week as a unix timestamp
    */
    function _getStartOfWeek()
    {
        return mktime(0, 0, 0, date("n"),
            (date("j") - date("w")), date("Y"));
    } 
    /**
    * Return last day of the week as a unix timestamp
    * NOte: Not tested, I added it just in case
    */
    function _getEndOfWeek()
    {
        return mktime(23, 59, 59, date("n"), (date("j") + (6 - date("w"))), date("Y"));
    } 
    /**
    * Return the first day of the month as a date
    */
    function _getStartOfMonth()
    { 
        // Get today
        $d = $this->_getDateToday(); 
        // Get today's year
        $y = date("Y", $d);
        $m = date("m", $d);
        return $y . "-" . $m . "-01";
        /*return date("Y", mktime(0, 0, 0, date("n"), 1, date("Y"))) . "/"
         . date("m", mktime(0, 0, 0, date("n"), 1, date("Y"))) . "/"
         . date("d", mktime(0, 0, 0, date("n"), 1, date("Y")));*/
    } 

    function _getEndOfMonth()
    { 
        // ..... TO DO
    } 

    /**
    * Method to convert the dates to the form that can be used
    * in a SQL query (YYYY-MM-DD)
    */
    function _getAsCompDate($str)
    {
        return date("Y", $str) . "-" . date("m", $str) . "-" . date("d", $str);
    } 

    /**
    * Return a date in YYYY MM DD format as a unix timestamp
    */
    function _getCompDateAsUnix($str)
    {
        return strtotime($str);
    } 
} // end class

?>