<?php
/**
* Aspects of this Script were originaly written by
* William J Sanders and reprogramed by Jose-Manuel
* Contardo. It was adapted to the KEWL.NextGen framework
* and substantially added to by Derek Keats by:
*   - Making it use the multilingual interface
*   - Making it extend the framework object
*   - Changing color tags to use the CSS
*   - Implementing use of htmlTable to generate the table
*   - Added method to return an array of months, and an array of days
*
* Not that because it extends object, it must be instantiated using
* getObject or newObject, and NOT with loadClass.
*
* To display the calendar, type:
*   $this->objCal = $this->getObject("simplecal", "dateTime");
*   $this->objCal->show($mnth);
*
*/

class simplecal extends object
{
    /**
    * @var string $calLinkto The URI to link to in the linked date in the
    * calendar
    */
    var $calLinkto;
    /**
    * @var string $border The width of the calendar table border
    * defaults to 0
    */
    var $border="0";
    /**
    * @var string $cellpadding The cellpaddign of the calendar table
    * defaults to 2
    */
    var $cellpadding="2";
    /**
    * @var string $cellspacing The cellspacing of the calendar table
    * defaults to 2
    */
    var $cellspacing="2";
    /**
    * @var string $calWidth The width of the calendar table
    */
    var $calWidth = "140";
    /**
    * @var string $callingModule The modulecode of the module using
    * the calendar. Used to build the links for each day
    */
    var $callingModule;
    /**
    * @var string $startweek The day on which the week starts mon | sun
    * @todo -csimplecal Implement ability to change day to sunday. It
    * doesn't work and I need some help with it.
    */
    var $startweek="mon";
    /**
    * @var array $queryItems An array containing any additional query
    * items to pass in the query string from the linked date in the
    * calendar
    */
    var $queryItems=array();
    /**
    *
    * An array to check if there are data for a particular day.
    * It contains 'day', 'month', and 'year'
    */
    var $dayDataChkArray=array();

    /**
    *
    * @Var array $entryCheckArray An array to check entry points
    *
    */
    var $entryCheckArray=array();


    /**
    * Standard init method, just gives access to the language object
    * which is needed to translate the Month and Day names
    */
    function init()
    {
        $this->objLanguage=& $this->getObject('language', 'language');
    }


    function monthShort($month)
    {
        switch ($month) {
            case "Jan":
                return $this->objLanguage->languageText("mod_datetime_jan");
                break;
            case "Feb":
                return $this->objLanguage->languageText("mod_datetime_feb");
                break;
            case "Mar":
                return $this->objLanguage->languageText("mod_datetime_mar");
                break;
            case "Apr":
                return $this->objLanguage->languageText("mod_datetime_apr");
                break;
            case "May":
                return $this->objLanguage->languageText("mod_datetime_may");
                break;
            case "Jun":
                return $this->objLanguage->languageText("mod_datetime_jun");
                break;
            case "Jul":
                return $this->objLanguage->languageText("mod_datetime_jul");
                break;
            case "Aug":
                return $this->objLanguage->languageText("mod_datetime_aug");
                break;
            case "Sep":
                return $this->objLanguage->languageText("mod_datetime_sep");
                break;
            case "Oct":
                return $this->objLanguage->languageText("mod_datetime_oct");
                break;
            case "Nov":
                return $this->objLanguage->languageText("mod_datetime_nov");
                break;
            case "Dec":
                return $this->objLanguage->languageText("mod_datetime_dec");
                break;
            default:
                return $month;
        }
    }

    /**
    * Method to return the full text of the month when passed the
    * numeric two digit representation of month
    *
    * @param string $numMonth the numeric two digit representation of a month
    *
    * @see show()
    */
    function monthFull($numMonth)
    {
        $calMes["01"] = $this->objLanguage->languageText("mod_datetime_january");
        $calMes["1"] = $this->objLanguage->languageText("mod_datetime_january");
        $calMes["02"] = $this->objLanguage->languageText("mod_datetime_february");
        $calMes["2"] = $this->objLanguage->languageText("mod_datetime_february");
        $calMes["03"] = $this->objLanguage->languageText("mod_datetime_march");
        $calMes["3"] = $this->objLanguage->languageText("mod_datetime_march");
        $calMes["04"] = $this->objLanguage->languageText("mod_datetime_april");
        $calMes["4"] = $this->objLanguage->languageText("mod_datetime_april");
        $calMes["05"] = $this->objLanguage->languageText("mod_datetime_may");
        $calMes["5"] = $this->objLanguage->languageText("mod_datetime_may");
        $calMes["06"] = $this->objLanguage->languageText("mod_datetime_june");
        $calMes["6"] = $this->objLanguage->languageText("mod_datetime_june");
        $calMes["07"] = $this->objLanguage->languageText("mod_datetime_july");
        $calMes["7"] = $this->objLanguage->languageText("mod_datetime_july");
        $calMes["08"] = $this->objLanguage->languageText("mod_datetime_august");
        $calMes["8"] = $this->objLanguage->languageText("mod_datetime_august");
        $calMes["09"] = $this->objLanguage->languageText("mod_datetime_september");
        $calMes["9"] = $this->objLanguage->languageText("mod_datetime_september");
        $calMes["10"] = $this->objLanguage->languageText("mod_datetime_october");
        $calMes["11"] = $this->objLanguage->languageText("mod_datetime_november");
        $calMes["12"] = $this->objLanguage->languageText("mod_datetime_december");
        return $calMes[$numMonth];
    }

    /**
    * Method to return an array of months
    *
    * @author Derek Keats
    *
    * @return array $calMes an array of 12 months
    */
    function getMonthsAsArray($abbrev=Null)
    {
        switch($abbrev){
            case "3letter":
                $calMes = array(
                    $this->objLanguage->languageText("mod_datetime_jan"),
                    $this->objLanguage->languageText("mod_datetime_feb"),
                    $this->objLanguage->languageText("mod_datetime_mar"),
                    $this->objLanguage->languageText("mod_datetime_apr"),
                    $this->objLanguage->languageText("mod_datetime_may"),
                    $this->objLanguage->languageText("mod_datetime_jun"),
                    $this->objLanguage->languageText("mod_datetime_jul"),
                    $this->objLanguage->languageText("mod_datetime_aug"),
                    $this->objLanguage->languageText("mod_datetime_sep"),
                    $this->objLanguage->languageText("mod_datetime_oct"),
                    $this->objLanguage->languageText("mod_datetime_nov"),
                    $this->objLanguage->languageText("mod_datetime_dec"));
                break;
            case "1letter":
                $calMes = array(
                    $this->objLanguage->languageText("mod_datetime_jy"),
                    $this->objLanguage->languageText("mod_datetime_fy"),
                    $this->objLanguage->languageText("mod_datetime_mm"),
                    $this->objLanguage->languageText("mod_datetime_al"),
                    $this->objLanguage->languageText("mod_datetime_my"),
                    $this->objLanguage->languageText("mod_datetime_jn"),
                    $this->objLanguage->languageText("mod_datetime_jl"),
                    $this->objLanguage->languageText("mod_datetime_ag"),
                    $this->objLanguage->languageText("mod_datetime_st"),
                    $this->objLanguage->languageText("mod_datetime_ot"),
                    $this->objLanguage->languageText("mod_datetime_nv"),
                    $this->objLanguage->languageText("mod_datetime_dc"));
                break;
            default:
                $calMes = array($this->objLanguage->languageText("mod_datetime_january"),
                    $this->objLanguage->languageText("mod_datetime_february"),
                    $this->objLanguage->languageText("mod_datetime_march"),
                    $this->objLanguage->languageText("mod_datetime_april"),
                    $this->objLanguage->languageText("mod_datetime_may"),
                    $this->objLanguage->languageText("mod_datetime_june"),
                    $this->objLanguage->languageText("mod_datetime_july"),
                    $this->objLanguage->languageText("mod_datetime_august"),
                    $this->objLanguage->languageText("mod_datetime_september"),
                    $this->objLanguage->languageText("mod_datetime_october"),
                    $this->objLanguage->languageText("mod_datetime_november"),
                    $this->objLanguage->languageText("mod_datetime_december"));
                break;;
        } // switch
        return $calMes;

    }

    function getDaysAsArray($abbrev=Null)
    {
        switch($abbrev){
            case "3letter":
                $caldays = array(
                    $this->objLanguage->languageText("mod_datetime_mon"),
                    $this->objLanguage->languageText("mod_datetime_tue"),
                    $this->objLanguage->languageText("mod_datetime_wed"),
                    $this->objLanguage->languageText("mod_datetime_thu"),
                    $this->objLanguage->languageText("mod_datetime_fri"),
                    $this->objLanguage->languageText("mod_datetime_sat"),
                    $this->objLanguage->languageText("mod_datetime_sun"));
                break;
            case "2letter":
                $caldays = array(
                    $this->objLanguage->languageText("mod_datetime_mo"),
                    $this->objLanguage->languageText("mod_datetime_tu"),
                    $this->objLanguage->languageText("mod_datetime_we"),
                    $this->objLanguage->languageText("mod_datetime_th"),
                    $this->objLanguage->languageText("mod_datetime_fr"),
                    $this->objLanguage->languageText("mod_datetime_sa"),
                    $this->objLanguage->languageText("mod_datetime_su"));
                break;

            case "1letter":
                $caldays = array(
                    $this->objLanguage->languageText("mod_datetime_m"),
                    $this->objLanguage->languageText("mod_datetime_tuy"),
                    $this->objLanguage->languageText("mod_datetime_w"),
                    $this->objLanguage->languageText("mod_datetime_thy"),
                    $this->objLanguage->languageText("mod_datetime_f"),
                    $this->objLanguage->languageText("mod_datetime_say"),
                    $this->objLanguage->languageText("mod_datetime_suy"));

                break;

            default:
                $caldays = array(
                    $this->objLanguage->languageText("mod_datetime_monday"),
                    $this->objLanguage->languageText("mod_datetime_tuesday"),
                    $this->objLanguage->languageText("mod_datetime_wednesday"),
                    $this->objLanguage->languageText("mod_datetime_thursday"),
                    $this->objLanguage->languageText("mod_datetime_friday"),
                    $this->objLanguage->languageText("mod_datetime_saturday"),
                    $this->objLanguage->languageText("mod_datetime_sunday"));
                break;
        }
        if ($this->startweek=="sun") { // Take sunday from the end and put it first
            $sunday=$caldays[6];
            array_splice($caldays, 6,1);
            array_unshift($caldays, $sunday);

        }
        //echo implode($caldays, "!");
        return $caldays;
    }

    /**
    * The standard show method
    *
    * @param integer $shift The number of months to shift from the current month
    *
    */
    function show($shift = 0)
    {
        /*
        * This section assigns timestamps to dates, starting with today as an unshifted
        * (i.e. non relative) date
        */
        // non relative date
        $todayTs = mktime(0, 0, 0, date("n"), date("d"), date("Y"));
        // first day of the month, either this month or this month + $shift
        $firstdayMonthTs = mktime(0, 0, 0, date("n") + $shift, 1, date("Y"));
        /* last day of the month, either this month or this month + $shift
           $shift + 1 is used with a 0 day to return the 0th day of next month
           which happens to be the last day of this month */
        $lastdayMonthTs = mktime(0, 0, 0, date("n") + $shift + 1, 0, date("Y")); // last day of the month
        // Assign the numeric value of the year
        $numYear = date("Y", $firstdayMonthTs);
        // Assign the numeric value of the month
        $numMonth = date("m", $firstdayMonthTs);
        // Assign the text value of the month
        $textMonth = $this->monthFull(date("n", $firstdayMonthTs));
        // Calculate the days in the month
        $daysInMonth = date("t", $firstdayMonthTs);
        // raplace day 0 for day 7, week starts on monday
        $dayMonth_start = date("w", $firstdayMonthTs);
        if ($dayMonth_start == 0) {
            $dayMonth_start = 7;
        }
        $dayMonth_end = date("w", $lastdayMonthTs);
        if ($dayMonth_end == 0) {
            $dayMonth_end = 7;
        }
        // formating output as a table using the htmltable object of the KNG framework
        $this->objTable=& $this->newObject('htmltable', 'htmlelements');
        // Use the table with property to control the width of the calendar
        $this->objTable->width = $this->calWidth;
        // Use the cal-main CSS entry to control the look of the calendar
        $this->objTable->cssClass = "cal-main";
        //Specify the border of the calendar table defaulting to 0
        $this->objTable->border=$this->border;
        $this->objTable->cellpadding=$this->cellpadding;
        $this->objTable->cellspacing=$this->cellspacing;
        // Make the caption
        $this->objTable->caption=$textMonth . "&nbsp;&nbsp;" . $numYear;
        // load the days of the week, 2 letter version
        $this->objTable->addRow($this->getDaysAsArray("2letter"));

        // Load the days with no date into the table for output
        $this->objTable->startRow();
        // Fill with white spaces until the first day
        for ($k = 1; $k < $dayMonth_start; $k++) {
            $this->objTable->addcell("&nbsp;","20", "top");
        }
        //Today as integer
        $dayToday=date("j", time());
        //This month as month
        $monthToday=date("m", time());
        //This year as year
        $yearToday=date("Y", time());

        //Loop over the days in the month
        for ($i = 1; $i <= $daysInMonth; $i++) {
            // Assigns a timestamp to day i
            $day_i_ts = mktime(0, 0, 0, date("n", $firstdayMonthTs), $i, date("Y", $firstdayMonthTs));
            $day_i = date("w", $day_i_ts);
            // Placing Sunday as last day of the week
            //-----> I have not figured out how to make this work with Sunday as the first day.
            if ($day_i == 0) {
                $day_i = 7;
            }

            // Target link from the arrays
            $d2_i = date("d", $day_i_ts);
            $links = array(
                'month' => $numMonth,
                'year' => $numYear,
                'day' => $d2_i,
                'shift'=>$shift);
            // Add any link elements that are passed in this->queryitems
            if (is_array($this->queryItems)) {
                $links = array_merge($links, $this->queryItems);
            }
            $link_i = $this->uri($links, $this->callingModule);

            /*------------ SECTION TO PRINT DATES LINED -----
           This section parses the dates and determines:
            1. Are there any entries?
            2. Is the date in the future? */
            //If it is a year in the future then no links
            if ( $numYear > $yearToday ) {
                $link_i = $i;
            } else {
                //If it it this year
                if ( $numYear == $yearToday ) {
                    //If is a future month in this year
                    if ( $numMonth > $monthToday ) {
                        $link_i = $i;
                    } else {
                        //If it is this month
                        if ( $i > $dayToday ) {
                            $link_i = $i;
                        } else {
                             if ( $this->dayHasData($i, $numMonth, $numYear) ) {
                                $link_i = "<a href=\"" . $link_i . "\">" . $i . "</a>";
                            } else {
                                $link_i = $i;
                            }
                        }
                    }
                 } else {
                        //Now check if there are data for the day before
                        //making the link
                        if ( $this->dayHasData($i, $numMonth, $numYear, $this->entryCheckArray) ) {
                         $link_i = "<a href=\"" . $link_i . "\">" . $i . "</a>";
                     } else {
                         $link_i = $i;
                     }
                 }
            }

            // Plancing day i on calendar
            if ($shift == 0 && $todayTs == $day_i_ts) {
                $this->objTable->addcell($link_i, "20", "top", Null, "cal-today");
            } else {
                if ($day_i==6 || $day_i==7) {
                    $css="cal-weekend";
                } else {
                    $css="cal-default";
                }
                $this->objTable->addcell($link_i, "20", "top", Null, $css);
            }
            if ($day_i == 7 && $i < $daysInMonth) {
                $this->objTable->endRow();
                $this->objTable->startRow();
            } else if ($day_i == 7 && $i == $daysInMonth) {
                $this->objTable->endRow();
            } else if ($i == $daysInMonth) {
                for ($h = $dayMonth_end; $h < 7; $h++) {
                    $this->objTable->addcell("&nbsp;");
                }
                $this->objTable->endRow();
            }
        } // end for
        $nextAr=array('shift'=>$shift+1);
        $prevAr=array('shift'=>$shift-1);
        $linkNx=$this->uri(array_merge($nextAr, $this->queryItems), $this->callingModule);
        $linkPv=$this->uri(array_merge($prevAr, $this->queryItems), $this->callingModule);
        //Create the anchor link object
        $this->objAnchor=&$this->newObject('link', 'htmlelements');
        // Add the next link
        $this->objIcon=& $this->newObject('geticon', 'htmlelements');
        $this->objIcon->setIcon("next");
        $this->objAnchor->href = $linkNx;
        $this->objAnchor->link = $this->objIcon->show();
        $next=$this->objAnchor->show();
        // Add the previous link
        $this->objAnchor->href = $linkPv;
        $this->objIcon->setIcon("prev");
        $this->objAnchor->link = $this->objIcon->show();
        $prev=$this->objAnchor->show();

        // Start a table row, insert the two links, and show the table
        $this->objTable->startRow();
        $this->objTable->addcell($prev, Null, Null, "left", Null, "colspan='3'");
        $this->objTable->addcell($next, Null, Null, "right", Null, "colspan='4'");
        $this->objTable->endRow();
        return $this->objTable->show();
    } // end function


    /**
    * Method to check if a given day has data
    *
    *
    */
    function dayHasData($numDay, $numMonth, $numYear)  {
        $ch = $numYear.$numMonth.$numDay;
        foreach ($this->dayDataChkArray as $line) {
            if ( $line['date']==$ch ) {
                  return TRUE;
            }
        }
        return FALSE;
    }

    /**
    * Method to take a date/datetime string and return a formatted date.
    * @author Megan Watson
    * @param string $date The date in datetime format - yyyy-mm-dd hh:mm.
    * @return string $ret The formatted date - 30 June 2005 11:50.
    */
    function formatDate($date)
    {
        $array = explode(' ', $date);

        $date = explode('-', $array[0]);
        $format = $date[2].' ';
        $format .= $this->monthFull($date[1]).' ';
        $format .= $date[0];

        if(isset($array[1]) && $array[1] != 0){
            $format .= ' '.substr($array[1],0,5);
        }
        return $format;
    }
} // end class

?>