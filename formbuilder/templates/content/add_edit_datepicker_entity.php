<?php

$this->appendArrayVar('headerParams', $this->getJavascriptFile('1.4.2/jquery-1.4.2.min.js', 'jquery'));
 //$this->appendArrayVar('headerParams', '<script type="text/javascript">jQuery.noConflict();</script>');
$datePickerName = $_REQUEST['datePickerName'];
$datePickerValue = $_REQUEST['datePickerValue'];
$dateFormat = $_REQUEST['dateFormat'];
$defaultCustomDate = $_REQUEST['defaultCustomDate'];


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
//!!!Problem Code!!!
 echo $objDPEntity->showWYSIWYGDatepickerEntity();
// $datePicker = $this->newObject('datepicker', 'htmlelements');
// $datePicker->name = 'storydate';
// //$datePicker->setName("storydate");
// $datePicker->setDateFormat("Aug-06-1996");
//  $datePicker->setDefaultDate("2010/02/02");
// echo $datePicker->show();
//       echo $postSuccessBoolean;
//
//       echo "fweljfklwejfklejflejfl;wejf";

    }
 else {
        echo $postSuccessBoolean;
    }
    ?>
</div>
