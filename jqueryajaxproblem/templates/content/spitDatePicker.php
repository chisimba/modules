<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$testString = $this->getParam('testString', NULL);
if (isSet($testString)) {
    echo $testString;
}

$datePicker = $this->newObject('datepicker2');
 $datePicker->name = 'testdatepicker2';
 $datePicker->setDateFormat("Aug-06-1996");
  $datePicker->setDefaultDate("2010/09/02");
 echo $datePicker->show();
?>