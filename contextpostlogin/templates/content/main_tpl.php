<?php

$tabBox = & $this->newObject('tabpane', 'htmlelements');
$featureBox = & $this->newObject('featurebox', 'navigation');
$objLink =  & $this->newObject('link', 'htmlelements');
$icon =  & $this->newObject('geticon', 'htmlelements');

$str = '';
$other = '';
$lects = '';

//registered courses
//var_dump($contextList);

if (count($contextList) > 0)
{	
	foreach ($contextList as $context)
	{
		
		$lecturers = $this->_objUtils->getContextLecturers($context['contextcode']);
		$lects = '';
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
		
		$contextCode = $context['contextcode'];
		
		$objLink->href = $this->uri(array('action' => 'joincontext','contextCode'=>$contextCode), 'context');
		$icon->setIcon('leavecourse');
		$icon->alt = 'Enter Course';
		$objLink->link = $icon->show();
		
		
		$str .= $featureBox->show($context['contextcode'] .' - '.$context['title'].'   '.$objLink->show(), $content ).'<hr />';
	}
} else {
	$str .= '<div align="center" style="font-size:large;font-weight:bold;color:#CCCCCC;font-family: Helvetica, sans-serif;">No are associated with any courses</div>';
}


//public courses
if(count($otherCourses) > 0)
{
	$other = $featureBox->show('Browse Courses', $filter);
	
	foreach($otherCourses as $context)
	{
		$lecturers = $this->_objUtils->getContextLecturers($context['contextcode']);
		$lects = '';
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
		$icon->setIcon('leavecourse');
		$icon->alt = 'Enter Course';
		$objLink->link = $icon->show();
		
		
		$other .= $featureBox->show($context['contextcode'] .' - '.$context['title'].'   '.$objLink->show(), $content ).'<hr />';
	}
	
}else {
	
	$other = '<div align="center" style="font-size:large;font-weight:bold;color:#CCCCCC;font-family: Helvetica, sans-serif;">No Public Courses found</div>';
}

$tabBox->addTab(array('name'=>'My Courses','content' => $str));
$tabBox->addTab(array('name'=>'Other Courses','content' => $other));
echo $tabBox->show();

?>