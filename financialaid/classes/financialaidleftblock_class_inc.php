<?php
/* ----------- data class extends dbTable for tbl_guestbook------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
    {
        die("You cannot view this page directly");
    }


/**
* Class to display the left hand block for financial aid module
*/
class financialaidleftblock extends object
{
    /**
    *
    * Function to show the left hand column
    *
   * @return string: The html string containting the left block
    *
    */


	function show(){
    	$this->objLanguage = &$this->getObject('language','language');
        $this->objDBFinancialAidWS = & $this->getObject('dbfinancialaidws');

        $appid = $this->getParam('appid');

        $header = "<h1>".$this->objLanguage->languagetext('mod_financialaid_financialaid','financialaid')."</h1>";
		$list = array('search'=>$this->objLanguage->languagetext('mod_financialaid_search','financialaid'));
		$links = "";
		foreach($list as $key=>$value){
			$link = new link();
			$link->href = $this->uri(array('action'=>$key));
			$link->link = $value;
			$links .= $link->show()."<br /><br />";
		}

		$links .= "<strong>".$this->objLanguage->languagetext('mod_financialaid_applications','financialaid')."</strong><br />";
		$href = new href("index.php?module=financialaid&amp;action=searchapplications&amp;all=yes",$this->objLanguage->languagetext('mod_financialaid_showallapps','financialaid'));
		$links.=$href->show()."<br />";
		$href = new href("index.php?module=financialaid&amp;action=searchapplications",$this->objLanguage->languagetext('mod_financialaid_searchapp','financialaid'));
		$links.=$href->show()."<br />";
		$href = new href("index.php?module=financialaid&amp;action=searchmarkrange",$this->objLanguage->languagetext('mod_financialaid_searchmarkrange','financialaid'));
		$links.=$href->show()."<br />";
		$href = new href("index.php?module=financialaid&amp;action=addapplication",$this->objLanguage->languagetext('mod_financialaid_addapp','financialaid'));
		$links.=$href->show()."<br />";
        $links.="<br />";
        
        $studentid = $this->getParam('id', NULL);
        if(!is_null($studentid)){
            $appinfo = $this->objDBFinancialAidWS->getApplication($studentid, 'studentNumber');
            if (count($appinfo) > 0){
                $appid = $appinfo[0]->id;
            }
        }else{
            $appinfo = $this->objDBFinancialAidWS->getApplication($appid);
            if (count($appinfo) > 0){
                $studentid = $appinfo[0]->studentNumber;
            }
        }
        if (strlen($appid) > 0){
            $href = new href("index.php?module=financialaid&amp;action=addnextofkin&amp;appid=$appid",$this->objLanguage->languagetext('mod_financialaid_addnextofkin','financialaid'));
            $links.="<br />".$href->show()."<br />";
            $href = new href("index.php?module=financialaid&amp;action=adddependant&amp;appid=$appid",$this->objLanguage->languagetext('mod_financialaid_adddependant','financialaid'));
            $links.=$href->show()."<br />";
            $href = new href("index.php?module=financialaid&amp;action=addparttimejob&amp;appid=$appid",$this->objLanguage->languagetext('mod_financialaid_addparttimejob','financialaid'));
            $links.=$href->show()."<br />";
            $href = new href("index.php?module=financialaid&amp;action=addstudentfamily&amp;appid=$appid",$this->objLanguage->languagetext('mod_financialaid_addstudentfamily','financialaid'));
            $links.=$href->show()."<br />";
            $links.="<br />";
        }
		if ($studentid) {
			$links .= "<strong>".$this->objLanguage->languagetext('mod_financialaid_stddetails','financialaid')."</strong><br />";
   
            if(strlen($appid) > 0){
                $link = new link();
                $link->href=$this->uri(array('action'=>'applicationinfo','appid'=>$appid));
                $link->link= $this->objLanguage->languagetext('mod_financialaid_showappdetails','financialaid');
                $links.=$link->show()."<br />";
                $link = new link();
                $link->href=$this->uri(array('action'=>'shownextofkin','appid'=>$appid));
                $link->link= $this->objLanguage->languagetext('mod_financialaid_showappnextofkindetails','financialaid');
                $links.=$link->show()."<br />";
                $link = new link();
                $link->href=$this->uri(array('action'=>'showdependants','appid'=>$appid));
                $link->link= $this->objLanguage->languagetext('mod_financialaid_showappdependantsdetails','financialaid');
                $links.=$link->show()."<br />";
                $link = new link();
                $link->href=$this->uri(array('action'=>'showparttimejob','appid'=>$appid));
                $link->link= $this->objLanguage->languagetext('mod_financialaid_showappparttimejobdetails','financialaid');
                $links.=$link->show()."<br />";
                $link = new link();
                $link->href=$this->uri(array('action'=>'showstudentfamily','appid'=>$appid));
                $link->link= $this->objLanguage->languagetext('mod_financialaid_showappstudentfamilydetails','financialaid');
                $links.=$link->show()."<br /><br />";
            }
			$href = new href("index.php?module=financialaid&amp;action=info&amp;id=$studentid",$this->objLanguage->languagetext('mod_financialaid_basic','financialaid'));
			$links.=$href->show()."<br />";
			$href = new href("index.php?module=financialaid&amp;action=results&amp;id=$studentid",$this->objLanguage->languagetext('mod_financialaid_course','financialaid'));
			$links.=$href->show()."<br />";
			$href = new href("index.php?module=financialaid&amp;action=account&amp;id=$studentid",$this->objLanguage->languagetext('mod_financialaid_account','financialaid'));
			$links.=$href->show()."<br />";
			$href = new href("index.php?module=financialaid&amp;action=matric&amp;id=$studentid",$this->objLanguage->languagetext('mod_financialaid_matric','financialaid'));
			$links.=$href->show()."<br /><br />";
		}
  
		$links .= "<strong>".$this->objLanguage->languagetext('word_sponsors')."</strong><br />";
		$href = new href("index.php?module=financialaid&amp;action=searchsponsors&amp;all=yes",$this->objLanguage->languagetext('mod_financialaid_listsponsors','financialaid'));
		$links.=$href->show()."<br />";


		return $header.$links;
	}
}

?>
