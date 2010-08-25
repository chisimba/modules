<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

//$this->appendArrayVar('headerParams', $this->getJavascriptFile('1.4.2/jquery-1.4.2.min.js', 'jquery'));
 //$this->appendArrayVar('headerParams', '<script type="text/javascript">jQuery.noConflict();</script>');
echo $datePickerName = $_REQUEST['datePickerName'];
echo $datePickerValue = $_REQUEST['datePickerValue'];
echo $dateFormat = $_REQUEST['dateFormat'];
echo $defaultCustomDate = $_REQUEST['defaultCustomDate'];

//if ($defaultSelected == "on")
//{
//    $defaultSelected =true;
//}
//else
//{
//    $defaultSelected =false;
//}
$objDPEntity = $this->getObject('form_entity_datepicker','formbuilder');
 $objDPEntity->createFormElement($datePickerName,$datePickerValue);

if ($objDPEntity->insertDatePickerParameters($datePickerName,$datePickerValue,$defaultCustomDate,$dateFormat) == TRUE)
{
    $postSuccessBoolean = 1;
}
 else {
    $postSuccessBoolean = 0;
}


?>

<div id="WYSIWYGDatepicker">
    <?php
    if ($postSuccessBoolean == 1)
    {
 $datePicker = $this->newObject('datepicker', 'htmlelements');
 $datePicker->name = 'storydate';
 //$datePicker->setName("storydate");
 $datePicker->setDateFormat("Aug-06-1996");
  $datePicker->setDefaultDate("2010/02/02");
 echo $datePicker->show();
       // echo $postSuccessBoolean;

    }
 else {
        echo $postSuccessBoolean;
    }
    ?>
</div>
