<?php
/* ----------- data class extends dbTable for tbl_calendar------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
    {
        die("You cannot view this page directly");
    }

/**
* Date Functions
*
* @author Tohir Solomons
* @copyright (c) 2005 University of the Western Cape
* @package calendarbase
* @version 1
*
* This class contains a number of functions for converting between dates and performing date calculations
*/
class datefunctions extends object
{

    /**
    * Constructor method to define the table
    *
    * @param void
    * @return void
    * @access public
    */
    public function init()
    {
        $this->objSimpleCal =& $this->getObject('datetime','utilities');
        $this->months3letter = $this->objSimpleCal->getMonthsAsArray('3letter');
    }

    /**
    * Method to convert a date to a timestamp
    *
    * @access public
    * @param date $givenDate Date to convert
    * @return string Timestamp of the date
    */
    public function convertDateToTimestamp ($givenDate = NULL)
    {
        if(is_null($givenDate))
        {
            return $givenDate;
        }
        $date = explode('-',$givenDate);
       
        return mktime('0','0','0',$date[1], $date[2], $date[0]);
    }

    /**
    * Method to convert a timestamp to a date
    *
    * @access public
    * @param string $timestamp Timestamp to convert
    * @param string $format format the date should be returned in
    * @return string formatted date
    */
    public function convertTimestampToDate ($timestamp, $format=NULL)
    {
        if (!isset($format)) {
            $format = 'Y-m-d';
        }
        return date ($format, $timestamp);
    }

    /**
    * Method to find the last date for a month
    *
    * @access public
    * @param int $month Month Number
    * @param int $year Year to find the date in.
    * @param string $format format the date should be returned in
    * @return string formatted date
    */
    public function lastDateMonth ($month, $year, $format=NULL)
    {
        // set the date to be the first date of the last month
        $newdate = $year.'-'.($month+1).'-1';

        // convert this date to a timestamp
        $timestamp = $this->convertDateToTimestamp($newdate);

        // subtract one day from the timestamp
        $lastdaytimestamp = $timestamp - $this->secondsInDay(1);

        // convert back to a readable format
        return $this->convertTimestampToDate($lastdaytimestamp, $format);
    }

    /**
    * Method to find the previous date for a given date
    *
    * @access public
    * @param string $date Given Date for the day
    * @param string $format format the date should be returned in
    * @return string formatted date containing previous date
    */
    public function previousDay($date, $format=NULL)
    {
        $timestamp = $this->convertDateToTimestamp($date); // convert this date to a timestamp
        $timestamp = $timestamp - $this->secondsInDay(1);    // subtract one day from the timestamp
        return $this->convertTimestampToDate($timestamp, $format); // convert back to a readable format
    }

    /**
    * Method to find the next date for a given date
    *
    * @access public
    * @param string $date Given Date for the day
    * @param string $format format the date should be returned in
    * @return string formatted date containing next date
    */
    public function nextDay($date, $format=NULL)
    {
        // convert this date to a timestamp
        $timestamp = $this->convertDateToTimestamp($date);

        // subtract one day from the timestamp
        $timestamp = $timestamp + $this->secondsInDay(1);

        // convert back to a readable format
        return $this->convertTimestampToDate($timestamp, $format);
    }

    /**
    * Method to find the day of a month for a given date
    *
    * @access public
    * @param string $date Given Date for the day
    * @return int Day of the Month
    */
    public function dayofMonth($date)
    {
        // convert this date to a timestamp
        $timestamp = $this->convertDateToTimestamp($date);

        return $this->convertTimestampToDate($timestamp, 'j');
    }

    /**
    * Method to find the month number for a given date
    *
    * @access public
    * @param string $date Given Date for the day
    * @return int Month number
    */
    public function getMonthNumber($date)
    {
        // convert this date to a timestamp
        $timestamp = $this->convertDateToTimestamp($date);

        return $this->convertTimestampToDate($timestamp, 'n');
    }

    /**
    * Method to find the year for a given date
    *
    * @access public
    * @param string $date Given Date for the day
    * @return int Year number
    */
    public function getYearNumber($date)
    {
        // convert this date to a timestamp
        $timestamp = $this->convertDateToTimestamp($date);

        return $this->convertTimestampToDate($timestamp, 'Y');
    }

    /**
    * This function calculate the amount of seconds in a day for the given number of days.
    * Used to add / subtract in timestamps
    *
    * @access public
    * @param int $numDays Number of Days
    * @return int Number of seconds
    */
    public function secondsInDay ($numDays = 1)
    {
        // multiply by hours, minutes, seconds
        return $numDays * 24 * 60 * 60;
    }

    /**
    * Method to find the previous month and year
    *
    * @access public
    * @param string $month Current Month
    * @param string $year Current Year
    * @return array Array with previous month and year
    */
    public function previousMonthYear($month, $year)
    {
        $month = $month-1;
        if ($month == 0) {
            $month = 12;
            $year = $year-1;
        }

        return array('month'=>$month, 'year'=>$year);
    }

    /**
    * Method to find the next month and year
    *
    * @access public
    * @param string $month Current Month
    * @param string $year Current Year
    * @return array Array with next month and year
    */
    public function nextMonthYear($month, $year)
    {
        $month = $month+1;
        if ($month == 13) {
            $month = 1;
            $year = $year+1;
        }

        return array('month'=>$month, 'year'=>$year);
    }

    /**
    * Method to find the month and year for a given date
    *
    * @access public
    * @param string $date Current Date
    * @return array Array with month and year
    */
    public function getMonthYear($date)
    {
        $timestamp = $this->convertDateToTimestamp($date);

        $month = $this->convertTimestampToDate($timestamp, 'n');

        $year = $this->convertTimestampToDate($timestamp, 'Y');

        return array('month'=>$month, 'year'=>$year);
    }

    /**
     * Method to reformat date string
     *
     * @access public
     * @param string $date
     * @return string date
     */
    public function reformatDateSmallMonth ($date)
    {
        $timestamp = $this->convertDateToTimestamp($date);

        $month = $this->convertTimestampToDate($timestamp, 'n');
        $year = $this->convertTimestampToDate($timestamp, 'Y');
        $day = $this->convertTimestampToDate($timestamp, 'j');

        $return = $day.' '.$this->months3letter[($month-1)];

        if ($year != date('Y')) {
            $return .= ' '.$year;
        }
        return $return;
    }

    /**
    * Method to find the difference between two dates
    *
    * @access public
    * @param string $date1 First Date
    * @param string $date1 Second Date
    * @return array Array containing difference in terms of days, hours, minutes and seconds
    */
    public function dateDifference($date1, $date2) {
        $s = strtotime($date2)-strtotime($date1);
        $d = intval($s/86400);
        $s -= $d*86400;
        $h = intval($s/3600);
        $s -= $h*3600;
        $m = intval($s/60);
        $s -= $m*60;
        return array("d"=>$d,"h"=>$h,"m"=>$m,"s"=>$s);
    }

    /**
    * This functions takes two dates, and checks if the latter is greater than the former.
    * If not, swop them around to make it so.
    *
    * @access public
    * @param string $date1 First Date
    * @param string $date1 Second Date
    * @return void
    */
    public function smallDateBigDate(&$date1, &$date2)
    {
        $date1timestamp = $this->convertDateToTimestamp($date1);
        $date2timestamp = $this->convertDateToTimestamp($date2);

        if ($date1timestamp > $date2timestamp) {
            $temp = $date1;
            $date1 = $date2;
            $date2 = $temp;
        }

        return;
    }

}
?>