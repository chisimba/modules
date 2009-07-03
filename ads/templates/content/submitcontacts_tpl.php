<?php
	$objDbClass = $this->getObject("dbcontacttable");
	$info=$objDbClass->getSingleRow($coursenum);
	if (count($info)==0)
	{
		$str = "Data not submitted";
	} else
	{
			$str= $info['academicname'].'    '.$info['schoolname'].'    '.$info['headsign'].'    ';
			$str.= $info['telnum'].'    '.$info['emailadd'].'    '.$info['coursename'].'   '.'<br/>';
		
	} // -- If
	echo "<h1>".$title."</h1>";
        echo $str;
	//---- Link for menu
	echo "<br/> <br/> <br/>";
	$this->loadClass("link","htmlelements");
	$link2=new link($this->uri(array('action'=>'courses','menutitle'=>'Course List'),"contactdetails"));
        $link2->link="Back to Course List";
	echo $link2->show();
?>
