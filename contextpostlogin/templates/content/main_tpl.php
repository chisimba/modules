<?php

$tabBox = & $this->newObject('tabpane', 'htmlelements');
$featureBox = & $this->newObject('featurebox', 'navigation');


$str = '';
$other = '';

//registered courses
if (isset($contextList))
{	
	foreach ($contextList as $context)
	{
		
		$str .= $featurebox->show($context['title'], $context['about'] );
	}
} else {
	$str .= '<div align="center" style="font-size:large;font-weight:bold;color:#CCCCCC;font-family: Helvetica, sans-serif;">No are associated with any courses</div>';
}


//public courses
if(isset($otherCourses))
{
	foreach($otherCourses as $otherCourses)
	{
		$other .=  	$otherCourse['title'].'<br/>'.$otherCourse['about'];
	}
	
}else {
	
	$other = '<div align="center" style="font-size:large;font-weight:bold;color:#CCCCCC;font-family: Helvetica, sans-serif;">No Public Courses found</div>';
}

$tabBox->addTab(array('name'=>'My Courses','content' => $str));
$tabBox->addTab(array('name'=>'Other Courses','content' => $other));
echo $tabBox->show();
?>