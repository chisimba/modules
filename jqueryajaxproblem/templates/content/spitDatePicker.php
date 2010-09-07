<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$testString = $this->getParam('testString', NULL);
if (isSet($testString)) {
    echo $testString;
}
$this->loadClass('datepicker','htmlelements');
$datePicker = $this->newObject('datepicker', 'htmlelements');
 $datePicker->name = 'testdatepicker';
 $datePicker->setDateFormat("Aug-06-1996");
  $datePicker->setDefaultDate("2010/02/02");
 echo $datePicker->show();
?>