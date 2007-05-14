<?php

$tabBox = $this->newObject('tabpane', 'htmlelements');
$featureBox = $this->newObject('featurebox', 'navigation');
$objLink = $this->newObject('link', 'htmlelements');
$icon = $this->newObject('geticon', 'htmlelements');
$table = $this->newObject('htmltable', 'htmlelements');
$domtt = $this->newObject('domtt', 'htmlelements');
$objContextGroups = $this->newObject('onlinecount', 'contextgroups');

$str = '';
$other = '';
$lects = '';
$config = '';
//registered courses
//var_dump($contextList);

if (count($contextList) > 0)
{
	foreach ($contextList as $context)
	{

		$lecturers = $this->_objUtils->getContextLecturers($context['contextcode']);
		$lects = '';
		if(is_array($lecturers) && count($lecturers) > 0)
		{
			$c = 0;
			foreach($lecturers as $lecturer)
			{
			    $c++;
				$lects .= $this->_objUser->fullname($lecturer['userid']);
				$lects .= ($c < count($lecturers)) ? ', ' : '';


			}
		} else {
			$lects = $this->_objLanguage->code2Txt('mod_contextpostlogin_nolectforcourse', 'contextpostlogin'); //'No Instructor for this course';
		}

		$content = '<span class="caption">'.$this->_objLanguage->code2Txt('word_lecturers').' : '.$lects.'</span>';// Instructors
		$content .= '<p>'.stripslashes($context['about']).'</p>';
		$content .= '<p>'.$this->_objUtils->getPlugins($context['contextcode']).'</p>';

		$contextCode = $context['contextcode'];


		if($this->_objDBContext->getContextCode() == $context['contextcode'])
		{
		    $objLink->href = $this->uri(array('action' => 'leavecontext','contextCode'=>$contextCode), 'context');
		    $icon->setIcon('leavecourse');
		    $icon->alt = $this->_objLanguage->code2Txt('phrase_leavecourse'); //'Leave Course';
    		$objLink->link = $icon->show();
		} else {
		    $objLink->href = $this->uri(array('action' => 'joincontext','contextCode'=>$contextCode), 'context');
    		$icon->setIcon('entercourse');
    		$icon->alt = $this->_objLanguage->code2Txt('phrase_entercourse'); //'Enter Course';
    		$objLink->link =$icon->show();
		}
		$title = $objLink->show();
		$objLink->href = $this->uri(array('action' => 'joincontext','contextCode'=>$contextCode), 'context');
        $objLink->link = $context['contextcode'] .' - '.$context['title'].'   ';
        $title = $objLink->show().$title;
		$str .= $featureBox->show($title, $content ).'<hr />';
	}
} else {
	$str .= '<div align="center" style="font-size:large;font-weight:bold;color:#CCCCCC;font-family: Helvetica, sans-serif;">'.
	$this->_objLanguage->code2Txt('mod_contextpostlogin_notassocanycourses', 'contextpostlogin').'</div>';//You are not associated with any courses
}


//public courses
$other = $featureBox->show($this->_objLanguage->code2Txt('phrase_browsecourses'), $filter); //'Browse Courses'

if(count($otherCourses) > 0)
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
	foreach($otherCourses as $context)
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
			$c = 0;
			foreach($lecturers as $lecturer)
			{
			    $c++;
				$lects .= $this->_objUser->fullname($lecturer['userid']);
				$lects .= ($c < count($lecturers)) ? ', ' : '';


			}
		} else {
			$lects = $this->_objLanguage->code2Txt('mod_contextpostlogin_nolectforcourse', 'contextpostlogin'); //'No Instructor for this course';
		}

		$content = '<span class="caption">'.$this->_objLanguage->code2Txt('word_lecturers').' : '.$lects.'</span>';
		$content .= '<p>'.$context['about'].'</p>';
		$content .= '<p>'.$this->_objUtils->getPlugins($context['contextcode']).'</p>';


		//link to join the context
		$objLink->href = $this->uri(array('action' => 'joincontext','contextCode'=>$context['contextcode']), 'context');
		$icon->setIcon('leavecourse');
		$icon->alt = $this->_objLanguage->code2Txt('phrase_entercourse').' '.$context['title'];
		$objLink->link = $icon->show();


        //check if this user can join this context before showing the link
		if($this->_objDBContextUtils->canJoin($context['contextcode']))
		{
			$config = $objLink->show();
		} else {
			$icon->setIcon('failed','png');
			$config = $icon->show();
		}

        //setup the information icon
		$icon->setIcon('info');
		$icon->alt = '';

        //formulate the message for the mouseover
		$mes = '';
		$mes .= ($context['access'] != '') ?  $this->_objLanguage->code2Txt('word_access').' : <span class="highlight">'.$context['access'].'</span>' : '' ;
		$mes .= ($context['startdate'] != '') ? '<br/>'.$this->_objLanguage->code2Txt('phrase_startdate').' : <span class="highlight">'.$context['startdate'].'</span>'  : '';
		$mes .= ($context['finishdate'] != '') ? '<br/>'.$this->_objLanguage->code2Txt('phrase_finishdate').' : <span class="highlight">'.$context['finishdate'].'</span>'  : '';
		$mes .= ($lects != '') ? '<br/>'.$this->_objLanguage->code2Txt('word_lecturers').' : <span class="highlight">'.$lects.'</span>'  : '';
		$scnt = $objContextGroups->getUserCount($context['contextcode']);
		$mes .= ($scnt > 0) ? '<br />'.$this->_objLanguage->code2Txt('mod_contextpostlogin_numregstudents', 'contextpostlogin').' : <span class="highlight">'.$scnt.'</span>' : '';
		$mes = htmlentities($mes);

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

	$other .= '<div align="center" style="font-size:large;font-weight:bold;color:#CCCCCC;font-family: Helvetica, sans-serif;">'.
	$this->_objLanguage->code2Txt('mod_contextpostlogin_nopublicopencourses', 'contextpostlogin').'</div>';
}

$tabBox->addTab(array('name'=> $this->_objLanguage->code2Txt('phrase_mycourses'),'content' => $str));

$tabBox->addTab(array('name'=> $this->_objLanguage->code2Txt('phrase_othercourses'),'content' => $other));
echo $tabBox->show();

?>