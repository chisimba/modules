<?php
	echo "<h3>Calender</h3>";
	//$year = $this->getParam('year');
	//$month = $this->getParam('month');
	//if(empty($year) || empty($month))
	//{
	        // get current year, month and day
	        //$day = "01";
	//}
	$this->objWebcal = $this->getObject('webcalendar','webcal');
    $year = $this->objWebcal->dateNow("%Y");
    $month = $this->objWebcal->dateNow("%m");
	$day = "01";
	$month = $this->objWebcal->showSimpleMonth($month,$year);
	echo $month;
?>