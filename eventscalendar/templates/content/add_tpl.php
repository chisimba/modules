<?php
//For this recipe I need a form
$objForm = & $this->newObject('form', 'htmlelements');
//and a editor
$objEditor = & $this->newObject('htmlarea', 'htmlelements');
//maybe a h1 heading
$objH1 = & $this->newObject('htmlheading', 'htmlelements');
//a  text field
$objTextField = & $this->newObject('textinput', 'htmlelements');
//the categories dropdown
$objCatDropdown = & $this->newObject('dropdown', 'htmlelements');
//i need a date 
$objDatePicker =  & $this->newObject('datepicker', 'htmlelements');
//a start time 
$startTime =  & $this->newObject('textinput', 'htmlelements');
// an end time
$endTime =  & $this->newObject('textinput', 'htmlelements');
//location
$location =  & $this->newObject('textinput', 'htmlelements');
//a button
$button =  & $this->newObject('button', 'htmlelements');

$mode = $this->getParam('mode');
//check the mode
if($mode == 'edit')
{
    $eventLine = $this->_objDBEventsCalendar->getRow('id', $this->getParam('id'));
    $objTextField->value = $eventLine['title'];
    $objEditor->value = stripslashes($eventLine['description']);
    $objDatePicker->value = $eventLine['event_date'];
    $startTime->value = $eventLine['start_time'];
    $endTime->value = $eventLine['end_time'];
    $location->value =$eventLine['location'];
    $heading = 'Edit Event';
    
} else {
    $heading = 'Add Event';
    $objTextField->value = '';
    $objEditor->value = '';
    $objDatePicker->value = mktime ();
    $startTime->value =  mktime();
    $endTime->value = mktime();
    $location->value = '';
}
$objForm->name = "addevent";
$objForm->extra = ' class = "f-wrap-1" ';
$objForm->displayType = 2 ;
$objForm->action = $this->uri(array('action' => 'saveevent'));

//the title field
$objTextField->name = 'title';
$objTextField->label = 'Title';
$objTextField->size = 70;


//location
$location->name = 'location';
$location->label = 'Location';


//the date picker
$objDatePicker->name = 'start_date';
$objDatePicker->label = 'Date';

//the editor
$objEditor->name = 'description';
$objEditor->label = 'Details';

//the categories
$objCatDropdown->label = 'Category';
$objCatDropdown->addFromDB($categories,'title','id');

//start time
$startTime->name = 'start_time';
$startTime->label = "Start Time";


//end time
$endTime->name = 'end_time';
$endTime->label = "end Time";



//the button
$button->setToSubmit();
$button->value = "Save";
$button->label = "&nbsp;";
$button->setCSS("f-submit");



$str = '<select name="catid">';
foreach ($categories as $cat)
{
   $str .= '<option value="'.$cat['id'].'" style="background:'.$cat['colour'].'">&nbsp;'.$cat['title'].'&nbsp;&nbsp;</option>';
  
}
$str .= '</select>';
//$objCatDropdown->addFromDB($categories, 'title','title','Categories');


//add them all to the form
$objForm->addToForm($objTextField);
//$objForm->addToForm($objCatDropdown);
//$objForm->addToForm('Category</td><td>'.$str.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Location '.$location->show());
$objForm->addToForm($location);

//$objForm->addToForm($objLocation);
$objForm->addToForm($objDatePicker);
//$objForm->addToForm($startTime);
//$objForm->addToForm($endTime);
$objForm->addToForm('Start Time</td><td>'.$this->_objUtils->getTimeDropDown('startTime'));
$objForm->addToForm('End Time</td><td>'.$this->_objUtils->getTimeDropDown('endTime'));
$objForm->addToForm($objEditor);
$objForm->addToForm($button);

print "<h1> ".$heading."</h1>";
print $objForm->show();

?>
