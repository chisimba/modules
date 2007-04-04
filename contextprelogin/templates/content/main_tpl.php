<?php

$tabBox = & $this->newObject('tabpane', 'htmlelements');
$featureBox = & $this->newObject('featurebox', 'navigation');
$objLink =  & $this->newObject('link', 'htmlelements');
$icon =  & $this->newObject('geticon', 'htmlelements');
$table = & $this->newObject('htmltable', 'htmlelements');
$domtt = & $this->newObject('domtt', 'htmlelements');
$objContextGroups = & $this->newObject('onlinecount', 'contextgroups');
/*
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
		
		
		if($this->_objDBContext->getContextCode() == $context['contextcode'])
		{
		    $objLink->href = $this->uri(array('action' => 'leavecontext','contextCode'=>$contextCode), 'context');
		    $icon->setIcon('leavecourse');
		    $icon->alt = 'Leave Course';
    		$objLink->link = $icon->show();
		} else {
		    $objLink->href = $this->uri(array('action' => 'joincontext','contextCode'=>$contextCode), 'context');
    		$icon->setIcon('entercourse');
    		$icon->alt = 'Enter Course';
    		$objLink->link =$icon->show();
		}
		$title = $objLink->show();
		$objLink->href = $this->uri(array('action' => 'joincontext','contextCode'=>$contextCode), 'context');
        $objLink->link = $context['contextcode'] .' - '.$context['title'].'   ';
        $title = $objLink->show().$title;
		$str .= $featureBox->show($title, $content ).'<hr />';
	}
} else {
	$str .= '<div align="center" style="font-size:large;font-weight:bold;color:#CCCCCC;font-family: Helvetica, sans-serif;">You are not associated with any courses</div>';
}
*/

//public courses
$other = $featureBox->show('Browse Courses', $filter);

if(count($publicCourses) > 0)
{
	//set the headings
	$table->width = '60%';
	$table->startHeaderRow();
	$table->addHeaderCell('Code');
	$table->addHeaderCell('Title');
	$table->addHeaderCell('Details');
	$table->addHeaderCell('&nbsp;');
	$table->endHeaderRow();
	
	$rowcount = 0;
	
    //loop through the context
	foreach($publicCourses as $context)
	{
		//set the odd and even rows
		$oddOrEven = ($rowcount == 0) ? "even" : "odd";
        
        //get the lecturers 
		$lecturers = $this->_objUtils->getContextLecturers($context['contextcode']);
		
        //reset the $lects
        $lects = '';
        
        //check if there are lecturers
		if(is_array($lecturers))
		{
            //get their names
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
		
		
		//link to join the context
		$objLink->href = $this->uri(array('action' => 'joincontext','contextCode'=>$context['contextcode']), 'context');
		$icon->setIcon('leavecourse');
		$icon->alt = 'Enter Course '.$context['title'];
		$objLink->link = $icon->show();
		
$config = '';
        //check if this user can join this context before showing the link
		if($this->_objDBContextUtils->canJoin($context['contextcode']))
		{
			//$config = $objLink->show();
		} else {
			$icon->setIcon('failed','png');
			$config = $icon->show();
		}
		
        //setup the information icon
		$icon->setIcon('info');
		$icon->alt = '';
    
        //formulate the message for the mouseover
		$mes = '';
		$mes .= ($context['access'] != '') ?  'Access : <span class="highlight">'.$context['access'].'</span>' : '' ; 
		$mes .= ($context['startdate'] != '') ? '<br/>Start Date : <span class="highlight">'.$context['startdate'].'</span>'  : '';
		$mes .= ($context['finishdate'] != '') ? '<br/>Finish Date : <span class="highlight">'.$context['finishdate'].'</span>'  : '';
		$mes .= ($lects != '') ? '<br/>Lecturers : <span class="highlight">'.$lects.'</span>'  : '';
		$scnt = $objContextGroups->getUserCount($context['contextcode']);
		$mes .= ($scnt > 0) ? '<br />No. Registered Students : <span class="highlight">'.$scnt.'</span>' : '';
		$mes = htmlentities($mes);

		$info = $domtt->show(htmlentities($context['title']),$mes,$icon->show());
		$tableRow = array();
		
		$tableRow[] = $context['contextcode'];
		$tableRow[] = $context['title'];
		$tableRow[] = $info;
		$tableRow[] = $objLink->show();// $config;
		
		$table->addRow($tableRow, $oddOrEven);
		 $rowcount = ($rowcount == 0) ? 1 : 0;
		//$other .= $featureBox->show($context['contextcode'] .' - '.$context['title'].'   '.$objLink->show(), $content ).'<hr />';
	}
	
	$other .='<hr />'.$featureBox->show('Courses', $table->show() );
}else {
	
	$other .= '<div align="center" style="font-size:large;font-weight:bold;color:#CCCCCC;font-family: Helvetica, sans-serif;">No Public or Open Courses are available</div>';
}

//$tabBox->addTab(array('name'=>'My Courses','content' => $str));

$tabBox->addTab(array('name'=>'Public Courses','content' => $other));
echo $tabBox->show();

?>