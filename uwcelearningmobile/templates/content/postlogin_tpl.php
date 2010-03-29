<?php
//Mobile Prelogin template
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
	    die("You cannot view this page directly");
}
	$str = '<fieldset><legend><b>'.$this->objLanguage->languageText('mod_uwcelearningmobile_wordmycourse', 'uwcelearningmobile').'</b></legend>';
	foreach($usercontexts as $context)
	{
		$con = $this->dbContext->getContext($context);
		$str .= '<a href="'.$this->URI(array('action' => 'context', 'contextcode'=> $con['contextcode'])).'">'.$con['title'].'</a><br/>';
	}
	$str .= '</fieldset><br/>';
	echo $str;
?>
