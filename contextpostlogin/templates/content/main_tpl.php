<?php

$tabBox = & $this->newObject('tabpane', 'htmlelements');
$featureBox = & $this->newObject('featurebox', 'navigation');
$objLink =  & $this->newObject('link', 'htmlelements');
$icon =  & $this->newObject('geticon', 'htmlelements');
$table = & $this->newObject('htmltable', 'htmlelements');
$domtt = & $this->newObject('domtt', 'htmlelements');


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
	$str .= '<div align="center" style="font-size:large;font-weight:bold;color:#CCCCCC;font-family: Helvetica, sans-serif;">You are associated with any courses</div>';
}


//public courses
$other = $featureBox->show('Browse Courses', $filter);

if(count($otherCourses) > 0)
{
	
	$table->width = '60%';
	$table->startHeaderRow();
	$table->addHeaderCell('Code');
	$table->addHeaderCell('Title');
	$table->addHeaderCell('Details');
	$table->addHeaderCell('&nbsp;');
	$table->endHeaderRow();
	
	$rowcount = 0;
	
	foreach($otherCourses as $context)
	{
		
		$oddOrEven = ($rowcount == 0) ? "even" : "odd";
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
		
		
		
		$objLink->href = $this->uri(array('action' => 'joincontext','contextCode'=>$context['contextcode']), 'context');
		$icon->setIcon('leavecourse');
		$icon->alt = 'Enter Course '.$context['title'];
		$objLink->link = $icon->show();
		
		if($this->_objDBContextUtils->canJoin($context['contextcode']))
		{
			$config = $objLink->show();
		} else {
			$icon->setIcon('failed','png');
			$config = $icon->show();
		}
		
		$icon->setIcon('info');
		$icon->alt = '';
		$mes = '';
		$mes .= ($context['access'] != '') ?  'Access : <span class="highlight">'.$context['access'].'</span>' : '' ; 
		$mes .= ($context['startdate'] != '') ? '<br/>Start Date : <span class="highlight">'.$context['startdate'].'</span>'  : '';
		$mes .= ($context['finishdate'] != '') ? '<br/>Finish Date : <span class="highlight">'.$context['finishdate'].'</span>'  : '';
		$mes .= ($lects != '') ? '<br/>Lecturers : <span class="highlight">'.$lects.'</span>'  : '';
		$noStuds = 0;
		$mes .= '<br />No. Registered Students : <span class="highlight">'.$noStuds.'</span>';
		
		$info = $domtt->show(htmlentities($context['title']),$mes,$icon->show());
		$tableRow = array();
		
		$tableRow[] = $context['contextcode'];
		$tableRow[] = $context['title'];
		$tableRow[] = $info;
		$tableRow[] = $config;
		
		$table->addRow($tableRow, $oddOrEven);
		 $rowcount = ($rowcount == 0) ? 1 : 0;
		//$other .= $featureBox->show($context['contextcode'] .' - '.$context['title'].'   '.$objLink->show(), $content ).'<hr />';
	}
	
	$other .='<hr />'.$featureBox->show('Courses', $table->show() );
}else {
	
	$other .= '<div align="center" style="font-size:large;font-weight:bold;color:#CCCCCC;font-family: Helvetica, sans-serif;">No Public or Open Courses is available</div>';
}

$tabBox->addTab(array('name'=>'My Courses','content' => $str));
$tabBox->addTab(array('name'=>'Other Courses','content' => $other));
echo $tabBox->show();

?>