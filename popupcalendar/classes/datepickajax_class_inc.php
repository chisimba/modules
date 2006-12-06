<?php
/**
* @package popupcalendar
*/

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}
// end security check

/**
* Class datepickajax displays a pop up calendar for selecting the date and time.
* The pop up displays a calendar for selecting the date and two dropdowns for selecting the time.
* The time can be hidden be setting showtime = 'no' in the url for the pop up.
*
* @copyright University of the Western Cape 2005
* @author Megan Watson
*
* The datepicker will be called from within a module as shown in the example.
* The example sets 'showtime' = no, so the time element is not displayed.
* Example:
* $url = $this->uri(array('action'=>'ajaxcal', 'field'=>'document.form1.start', 'fieldvalue'=>$start, 'showtime'=>'no'), 'popupcalendar');
* $onclick = "javascript:window.open('" .$url."', 'popupcal', 'width=310, height=370')";
*
* $objIcon->setIcon('select_date');
* $objLink = new link('javascript:void(0)');
* $objLink->extra = "onclick=\"$onclick\"";
* $objLink->link = $objIcon->show();
* $dateIcon = $objLink->show();
*
* $objInput = new textinput('start', $start);
* $objInput->extra = 'READONLY';
* echo $objInput->show().$dateIcon
*
*/

class datepickajax extends object
{
    /**
    * @var string $str Property to display the calendar on first opening.
    */
    var $str = '';

    /**
    * @var string $formStr Property to display the form containing the calling form information.
    */
    var $formStr = '';

    /**
    * @var string $timeStr Property to display the time on first opening.
    */
    var $timeStr = '';

    /**
    * @var string $showTime Property to determine whether to display the time for editing.
    */
    var $showTime = TRUE;

    /**
    * @var string $showMonths Property to determine whether to display the months as single letter links.
    */
    var $showMonths = TRUE;

    /**
    * Constructor
    *
    * @access public
    * @return
    */
    public function init()
    {
        $this->loadClass('xajax', 'ajaxwrapper');
        $this->loadClass('xajaxresponse', 'ajaxwrapper');

        $this->objLanguage =& $this->getObject('language', 'language');
        $this->objDate =& $this->getObject('datetime', 'utilities');

        $this->objLink =& $this->getObject('link', 'htmlelements');
        $this->objIcon =& $this->getObject('geticon', 'htmlelements');
        $this->objTable =& $this->getObject('htmltable', 'htmlelements');
        $this->objForm =& $this->getObject('form', 'htmlelements');
        $this->objInput =& $this->getObject('textinput', 'htmlelements');
        $this->objDrop =& $this->getObject('dropdown', 'htmlelements');
        $this->objButton =& $this->getObject('button', 'htmlelements');
    }

    /**
    * Method to get the icons for moving the next month/year
    *
    * @access private
    * @param string $day The currently selected day
    * @param string $month The currently selected month
    * @param string $year The currently selected year
    * @return The icons
    */
    private function getNext($day, $month, $year)
    {
        $nextMonth = $this->objLanguage->languageText('mod_popupcalendar_nextmonth','popupcalendar');
        $nextYear = $this->objLanguage->languageText('mod_popupcalendar_nextyear','popupcalendar');

        $nxMonth = $month+1;
        $nxYear = $year+1;
        if($month == 12){
            $nxMonth = 1;
            $year = $year+1;
        }

        $this->objIcon->setIcon('next');
        $this->objIcon->title = $nextMonth;
        $this->objIcon->extra = ' height="20" width="20"';

        $this->objLink = new link('#');
        $this->objLink->link = $this->objIcon->show();
        $this->objLink->extra = "onclick=\"xajax_buildCal($day, $nxMonth, $year); xajax_insertDate($day, $nxMonth, $year);\"";
        $icon =$this->objLink->show();

        $this->objIcon->setIcon('last');
        $this->objIcon->title = $nextYear;
        $this->objIcon->extra = ' height="20" width="20"';

        $this->objLink = new link('#');
        $this->objLink->link = $this->objIcon->show();
        $this->objLink->extra = "onclick=\"xajax_buildCal($day, $month, $nxYear); xajax_insertDate($day, $month, $nxYear);\"";
        $icon .=$this->objLink->show();
        return $icon;
    }

    /**
    * Method to get the icons for moving the previous month/year
    *
    * @access private
    * @param string $day The currently selected day
    * @param string $month The currently selected month
    * @param string $year The currently selected year
    * @return The icons
    */
    private function getPrevious($day, $month, $year)
    {
        $prevMonth = $this->objLanguage->languageText('mod_popupcalendar_prevmonth','popupcalendar');
        $prevYear = $this->objLanguage->languageText('mod_popupcalendar_prevyear','popupcalendar');

        $prMonth = $month-1;
        $prYear = $year-1;
        if($month == 1){
            $prMonth = 12;
            $year = $year-1;
        }

        $this->objIcon->setIcon('first');
        $this->objIcon->title = $prevYear;
        $this->objIcon->extra = ' height="20" width="20"';

        $this->objLink = new link('#');
        $this->objLink->link = $this->objIcon->show();
        $this->objLink->extra = "onclick=\"xajax_buildCal($day, $month, $prYear); xajax_insertDate($day, $month, $prYear);\"";
        $icon =$this->objLink->show();

        $this->objIcon->setIcon('prev');
        $this->objIcon->title = $prevMonth;
        $this->objIcon->extra = ' height="20" width="20"';

        $this->objLink = new link('#');
        $this->objLink->link = $this->objIcon->show();
        $this->objLink->extra = "onclick=\"xajax_buildCal($day, $prMonth, $year); xajax_insertDate($day, $prMonth, $year);\"";
        $icon .=$this->objLink->show();

        return $icon;
    }

    /**
    * Method to get a list of the months
    *
    * @access private
    * @param string $day The currently selected day
    * @param string $month The currently selected month
    * @param string $year The currently selected year
    * @return The month list
    */
    private function getAllMonths($day, $month, $year)
    {
        $mnths = $this->objDate->getMonthsAsArray('1letter');

        $str = '';
        foreach($mnths as $key => $item){
            if(!empty($str)){
                $str .= '&#160;&#160;';
            }
            $newmonth = $key + 1;

            $this->objLink = new link('#');
            $this->objLink->link = $item;
            $this->objLink->extra = "onclick=\"xajax_buildCal($day, $newmonth, $year); xajax_insertDate($day, $newmonth, $year);\"";
            $str .= $this->objLink->show();
        }
        return $str;
    }

    /**
    * Method to get the form containing the selected date and time.
    *
    * @access private
    * @param string $field The field in the form calling the calendar.
    * @param string $date The current date contained in the field.
    * @param bool $showTime Determines whether to display the time.
    * @return The form
    */
    private function getForm($date)
    {
        $save = $this->objLanguage->languageText('word_save');

        $field = $this->session('field');
        $arrDate = explode(' ', $date);

        $this->objForm = new form('select', $this->uri(array('action'=>'')));

        $this->objInput = new textinput('field', $field);
        $this->objInput->fldType = 'hidden';
        $this->objForm->addToForm($this->objInput->show());

        $this->objInput = new textinput('date', $arrDate[0]);
        $this->objInput->fldType = 'hidden';
        $this->objForm->addToForm($this->objInput->show());

        $timeValue = '';

        $showTime = $this->session($field.'_showTime');
        if($showTime){
            $this->objInput = new textinput('time', $arrDate[1]);
            $this->objInput->fldType = 'hidden';
            $this->objForm->addToForm($this->objInput->show());

            $timeValue = "+' '+document.getElementById('input_time').value";
        }

        $onclick = "javascript:window.opener.document.getElementById('input_".$field."').value = document.getElementById('input_date').value".$timeValue."; document.getElementById('form_select').submit(); window.close();";
/*
        $this->objLink = new link('#');
        $this->objLink->extra = "onclick=\"$onclick\"";
        $this->objLink->link = $save;
        $this->objForm->addToForm($this->objLink->show());
*/
        $this->objButton = new button('save', $save);
        $this->objButton->extra = " onclick=\"$onclick\"";
        $saveButton = $this->objButton->show();
        $this->objForm->addToForm($saveButton);

        return $this->objForm->show();
    }

    /**
    * Method to insert the selected date into the form field.
    *
    * @access public
    * @param string $mnDay The selected day
    * @param string $mnth The selected month
    * @param string $year The selected year
    * @return The XML for the function
    */
    public function insertDate($mnDay, $mnth, $year)
    {
        $objResponse = new xajaxResponse();

        $date = $year.'-'.$mnth.'-'.$mnDay;

        $objResponse->addAssign('input_date', 'value', $date);
        return $objResponse->getXML();
    }

    /**
    * Method to insert the selected time into the form field.
    *
    * @access public
    * @param string $hour The selected hour
    * @param string $min The selected minutes
    * @return The XML for the function
    */
    public function insertTime($hour, $min)
    {
        $objResponse = new xajaxResponse();

        $time = $hour.':'.$min;

        $objResponse->addAssign('input_time', 'value', $time);
        return $objResponse->getXML();
    }

    /**
    * Method to build the calendar
    *
    * @access public
    * @param string $day The default day
    * @param string $mnth The default month
    * @param string $year The default year
    * @return The XML for the function
    */
    public function buildCal($day = NULL, $mnth = NULL, $year = NULL)
    {
        $objResponse = new xajaxResponse();

        if(is_null($day)){
            $day = date('d');
        }
        if(is_null($mnth)){
            $mnth = date('m');
        }
        if(is_null($year)){
            $year = date('Y');
        }

        // get days of the week
        $week = $this->objDate->getDaysAsArray('3letter');
        $weekFull = $this->objDate->getDaysAsArray();

        // get month name
        $month = $this->objDate->monthFull($mnth);

        // set up month format/layout
        $first = '1 '.$month.' '.$year;
        $timestamp = strtotime($first);
        $numDays = date('t', $timestamp);
        $startDay = date('w', $timestamp);
        if($startDay == 0){
            $startDay = 7;
        }

        // offset = the difference between the start of the week and the first day of the month
        $offset = $startDay-1;

        // get the number of weeks to display
        $numWks = ceil(($numDays + $offset) / 7);

        $this->objTable->init();
        $this->objTable->cellpadding = 1;
        $this->objTable->row_attributes = ' height="15"';

        // Row containing month and links to next/previous month/year
        $next = $this->getNext($day, $mnth, $year);
        $prev = $this->getPrevious($day, $mnth, $year);
        $smallMonths = $this->getAllMonths($day, $mnth, $year);

        $field = $this->session('field');
        $showMonths = $this->session($field.'_showMonths');
        if($showMonths){
            $this->objTable->startRow();
            $this->objTable->addCell($smallMonths, '', '', 'center', 'even', ' colspan="3"');
            $this->objTable->endRow();
        }

        $this->objTable->startRow();
        $this->objTable->addCell($prev, '', '', 'left', 'heading');
        $this->objTable->addCell($month.' '.$year, '', '', 'center', 'heading');
        $this->objTable->addCell($next, '', '', 'right', 'heading');
        $this->objTable->endRow();

        $str = $this->objTable->show();

        $this->objTable->init();
        $this->objTable->cellpadding = 1;
        $this->objTable->border = 1;
        $this->objTable->row_attributes = ' height="15"';

        // Heading - days of the week
        $this->objTable->addRow($week, 'heading');

        // Content - days of the month: weekends = odd; selected day = HighLightText.
        $x = 0;
        for($i = 0; $i < $numWks; $i++){

            $this->objTable->startRow();
            for($j = 1; $j <= 7; $j++){
                $skip = FALSE;

                // Calculate offset from the start of the week.
                $mnDay = (($i*7)+$j)-$offset;
                if($mnDay <= 0 || $mnDay > $numDays){
                    $mnDay = '';
                    $skip = TRUE;
                }

                // Calculate class for the cell
                $class = 'even';
                if($j > 5){
                    $class = 'odd';
                }
                if($mnDay == $day){
                    $class = 'HighLightText';
                }

                if($skip){
                    $link = '';
                }else{
                    // Make the day a link
                    $this->objLink = new link('#');
                    $this->objLink->extra = "onclick=\"xajax_buildCal($mnDay, $mnth, $year); xajax_insertDate($mnDay, $mnth, $year);\"";
                    $this->objLink->link = $mnDay;
                    $link = $this->objLink->show();
                }

                $this->objTable->addCell($link, round((1/7*100),2).'%', '', 'center', $class);
            }
            $this->objTable->endRow();
        }

        // Display Table
        $str .= $this->objTable->show();

        // Display Date in text format
        $show = $day.' '.$month.' '.$year;

        $newTimestamp = strtotime($show);
        $numDay = date('w', $newTimestamp);
        if($numDay == 0){
            $numDay = 7;
        }
        $wkDay = $weekFull[$numDay-1];

        $str .= '<p align = "center"><b><i> '.$wkDay.' '.$show.'</i></b></p>';

        $this->str = $str;

        $objResponse->addAssign('calDiv', 'innerHTML', $str);
        return $objResponse->getXML();
    }

    /**
    * Method to render the time element
    *
    * @access public
    * @param string $hour The default hour
    * @param string $min The default minutes
    * @return The XML for the function
    */
    public function buildTime($hour, $min)
    {
        $objResponse = new xajaxResponse();

        $field = $this->session('field');
        $defaultDate = $this->session($field.'_defaultDate');
        if($defaultDate != NULL){
            $date = strtotime($defaultDate);
            $hour = date('H', $date);
            $min = date('i', $date);
        }

        $time = $this->objLanguage->languageText('mod_popupcalendar_time','popupcalendar');
        $timeStr = '<b>'.$time.':</b>&#160;&#160;';

        $hrSelect = "document.getElementById('input_hour').options[document.getElementById('input_hour').selectedIndex].value";
        $mnSelect = "document.getElementById('input_min').options[document.getElementById('input_min').selectedIndex].value";

        $this->objDrop = new dropdown('hour');
        for($i = 0; $i <= 23; $i++){
            if(strlen($i) == 1){
                $i = '0'.$i;
            }
            $this->objDrop->addOption($i, $i.'&#160;');
        }
        $this->objDrop->setSelected($hour);
        $this->objDrop->extra = "onchange = \"xajax_insertTime($hrSelect, $mnSelect);\"";
        $timeStr .= $this->objDrop->show();

        $this->objDrop = new dropdown('min');
        for($i = 0; $i <= 59; $i++){
            if(strlen($i) == 1){
                $i = '0'.$i;
            }
            $this->objDrop->addOption($i, $i.'&#160;');
        }
        $this->objDrop->setSelected($min);
        $this->objDrop->extra = "onchange = \"xajax_insertTime($hrSelect, $mnSelect);\"";
        $timeStr .= '<b>:</b>&#160;&#160;'.$this->objDrop->show();

        $this->objForm = new form('seltime', $this->uri(NULL));
        $this->objForm->addToForm($timeStr);

        $this->timeStr = $this->objForm->show();

        $objResponse->addAssign('timeDiv', 'innerHTML', $this->objForm->show());
        return $objResponse->getXML();
    }

    /**
    * Method to set up the calling module's info
    *
    * @access private
    * @return
    */
    private function setUpInfo()
    {
        $field = $this->getParam('field');
        $value = $this->getParam('fieldvalue', NULL);
        $showtime = $this->getParam('showtime', FALSE);
        $showmonths = $this->getParam('showmonths', FALSE);

        $this->session('field', $field);

        if(strtolower($showtime) == 'yes' || strtolower($showtime) == 'true'){
            $showtime = TRUE;
        }else{
            $showtime = FALSE;
        }
        $this->session($field.'_showTime', $showtime);

        if(strtolower($showmonths) == 'yes' || strtolower($showmonths) == 'true'){
            $showmonths = TRUE;
        }else{
            $showmonths = FALSE;
        }
        $this->session($field.'_showMonths', $showmonths);

        // initialise starting date
        $defaultDate = $this->session($field.'_defaultDate');
        if((is_null($value) || $value == 0) && $defaultDate == NULL){
            $day = date('d');
            $mnth = date('m');
            $year = date('Y');
            $hour = 12;
            $min = '00';
            $value = $year.'-'.$mnth.'-'.$day.' '.$hour.':'.$min;
        }else{
            $value = $this->session($field.'_defaultDate');
        }

        $this->formStr = $this->getForm($value);

        return $value;
    }

    /**
    * Method to build the ajax code
    *
    * @access public
    * @return array $return An array of variables to be passed to the template
    */
    public function showCal()
    {
        $xajax = new xajax($this->uri(array()));

        // Register functions
        $xajax->registerFunction(array($this,"buildCal"));
        $xajax->registerFunction(array($this,"insertDate"));

        $field = $this->session('field');
        $showTime = $this->session($field.'_showTime');
        if($showTime){
            $xajax->registerFunction(array($this,"buildTime"));
            $xajax->registerFunction(array($this,"insertTime"));
        }

        // XAJAX method to be called
        $xajax->processRequests();

        // Send JS to header
        $this->appendArrayVar('headerParams', $xajax->getJavascript());

        // Set up initial page
        $initDate = $this->setUpInfo();
        $arrDateTime = explode(' ', $initDate);
        $arrDate = explode('-', $arrDateTime[0]);

        $arrTime = array('', '');
        if(isset($arrDateTime[1])){
            $arrTime = explode(':', $arrDateTime[1]);
        }

        $this->buildCal($arrDate[2], $arrDate[1], $arrDate[0]);

        $field = $this->session('field');
        $showTime = $this->session($field.'_showTime');
        if($showTime){
            $this->buildTime($arrTime[0], $arrTime[1]);
        }

        $return = array();
        $return[] = $this->str;
        $return[] = $this->formStr;
        $return[] = $this->timeStr;

        // return initial set up
        return $return;
    }

    /**
    * Method to show the input field and the select date icons
    *
    * @access public
    * @param string $field The name of the date field
    * @param bool $showTime Indicate whether the time must be shown - TRUE=yes FALSE=no
    * @param bool $showMonths Indicate whether the month letters must be shown - TRUE=yes FALSE=no
    * @param string $defaultDate The default date to use
    * @return string $str The html string
    */
    public function show($field, $showTime = 'no', $showMonths = 'no', $defaultDate = NULL)
    {
        $selectLabel = $this->objLanguage->languageText('phrase_selectdate');

        //set the height of the popup window
        if(strtolower($showTime) == 'no' || strtolower($showTime) == 'false'){
            $length = 7;
            if(strtolower($showMonths) == 'no' || strtolower($showMonths) == 'false'){
                $height = 'height=363';
            }else{
                $height = 'height=383';
            }
        }else{
            $length = 12;
            if(strtolower($showMonths) == 'no' || strtolower($showMonths) == 'false'){
                $height = 'height=406';
           }else{
                $height = 'height=426';
           }
        }

        $this->objInput = new textinput($field,'','',$length);
        $this->objInput->extra=' readonly="readonly"';
        $dateText=$this->objInput->show();
        $url=$this->uri(array(
            'field' => $field,
            'fieldvalue' => $defaultDate,
            'showtime' => $showTime,
            'showmonths' => $showMonths,
            ),'popupcalendar');
        $onclick="javascript:window.open('" .$url."','popupcal','width=300,".$height.",scrollbars=1,resize=yes')";

        $this->objIcon->title=$selectLabel;
        $this->objIcon->setIcon('select_date');
        $this->objLink=new link('#');
        $this->objLink->extra="onclick=\"$onclick\"";
        $this->objLink->link=$this->objIcon->show();
        $dateLink=$this->objLink->show();
        $this->objLink->extra = ''; //clear the 'extra' just in case

        $str = $dateText.'&#160;'.$dateLink;
        return $str;
    }

    /**
    * Method to get and set sessions
    *
    * @access public
    * @param string $session The name of the session
    * @param value $value The value to set, if no $array given the current session value is returned
    * @return array $value The value stored in session
    */
    public function session($session, $value = NULL)
    {
        if($value != NULL){
            $this->setSession($session, $value);
        }else{
            $value = $this->getSession($session);
            return $value;
        }
    }
}
?>