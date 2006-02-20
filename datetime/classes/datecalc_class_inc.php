<?php
/**
* A class to do various calculations on dates
* 
* @author Derek Keats 
* @package datetime
* @version 1.0
* 
* These functions are based tutorials and examples posted on 
* various websites
* @see http://www.rockhounding.net/projects/time-n-date/
* 
* 
*/

class datecalc extends object 
{

    /**
    * @var object $objLanguage Property for hte language object
    */
    var $objLanguage;

    /** 
    * Standard init function
    */
    function init()
    {
    
    }
    /**
    * 
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
    } // isLeapYear

    
    /**
    * 
    * Method to calculate the day of the week when given a date, 
    * month and year
    * 
    */
    function dayOfWeek($day, $month, $year)
    {
        $a = floor((14 - $month) / 12);
        $y = $year - $a;
        $m = $month + 12 * $a-2;
        $day = ($day + $y + floor($y / 4) - floor($y / 100) + floor($y / 400) + floor(31 * $m / 12)) % 7;
        return $day;
    } #function dayOfWeek
    
    /**
    * 
    * Method to return the numeric value of month when
    * supplied as either three letter abbreviation or full
    * month. Only works for english.
    *
    * @param string $mo The month as text
    * 
    */
    function numericMonth($mo)
    {
        $mo=strtolower($mo);
        switch($mo){
        	case "jan":
            case "january": 
        		return "01";
        		break;
        	case "feb": 
            case "february":
        		return "02";
        		break;
        	case "mar":
            case "march": 
        		return "03";
        		break;
        	case "apr": 
            case "april": 
        		return "04";
        		break;
        	case "may": 
        		return "05";
        		break;
        	case "jun": 
            case "june":
        		return "06";
        		break;
        	case "jul": 
            case "july":
        		return "07";
        		break;
        	case "aug": 
            case "august":
        		return "08";
        		break;
        	case "sep": 
            case "september":
        		return "09";
        		break;
        	case "oct": 
            case "october": 
        		return "10";
        		break;
        	case "nov": 
            case "november": 
        		return "11";
        		break;
        	case "dec": 
            case "december":
        		return "12";
        		break;
        	default:
                $this->objLanguage = & $this->getObject("language", "language");
        		die($this->objLanguage->languageText("mod_datetime_unrecogmont").": ".$mo."!");
                break;
        } // switch
    } #function numericMonth
    
    
} // class