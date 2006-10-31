<?php

$tabBox = & $this->newObject('tabpane', 'htmlelements');
$featureBox = & $this->newObject('featurebox', 'navigation');
$objLink =  & $this->newObject('link', 'htmlelements');


$str = '';
$other = '';
$lects = '';

//registered courses
//var_dump($contextList);
count($contextList);
if (count($contextList) > 0)
{	
	foreach ($contextList as $context)
	{
		
		$lecturers = $this->_objUtils->getContextLecturers($context['contextcode']);
		
		if(is_array($lecturers))
		{
			foreach($lecturers as $lecturer)
			{
				$lects .= $lecturer['fullname'].', ';
			}
		} else {
			$lects = 'No Instructor for this course';
		}
		
		$content = '<span class="caption">Instructors : '.$lects.'</span>';
		$content .= '<p>'.$context['about'].'</p>';
		$content .= '<p>'.$this->_objUtils->getPlugins($context['contextcode']).'</p>';
		
		
		
		$objLink->href = $this->uri(array('action' => 'joincontext'), 'context');
		$objLink->link = '<span class="caption">Enter</span>';
		
		$str .= $featureBox->show($context['contextcode'] .' - '.$context['title'].'   '.$objLink->show(), $content ).'<hr />';
	}
} else {
	$str .= '<div align="center" style="font-size:large;font-weight:bold;color:#CCCCCC;font-family: Helvetica, sans-serif;">No are associated with any courses</div>';
}


//public courses
if(count($otherCourses))
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