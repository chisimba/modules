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
        $appnum = $this->getParam('appnum');

		$list = array('ok'=>$this->objLanguage->languagetext('mod_financialaid_search','financialaid'));
//		$list = array('ok'=>'Search', 'markrange'=>'Search by marks range');
		$links = "";
		foreach($list as $key=>$value){
			$link = new link();
			$link->href = $this->uri(array('action'=>$key));
			$link->link = $value;
			$links .= $link->show()."<br /><br />";
		}

		$links .= "<strong>".$this->objLanguage->languagetext('mod_financialaid_applications','financialaid')."</strong><br />";
		$href = new href("index.php?module=financialaid&amp;action=searchapplications&amp;id=$studentid",$this->objLanguage->languagetext('mod_financialaid_searchapp','financialaid'));
		$links.=$href->show()."<br />";
		$href = new href("index.php?module=financialaid&amp;action=addapplication",$this->objLanguage->languagetext('mod_financialaid_addapp','financialaid'));
		$links.=$href->show()."<br />";
  
        if (isset($appnum)){
  //      $href = new href("index.php?module=financialaid&amp;action=editapplication&amp;id=$applicationid",$this->objLanguage->languagetext('mod_financialaid_editapp','financialaid'));
  //      $links.=$href->show()."<br />";
            $href = new href("index.php?module=financialaid&amp;action=addnextofkin&amp;appnum=$appnum",$this->objLanguage->languagetext('mod_financialaid_addnextofkin','financialaid'));
            $links.="<br />".$href->show()."<br />";
            $href = new href("index.php?module=financialaid&amp;action=adddependant&amp;appnum=$appnum",$this->objLanguage->languagetext('mod_financialaid_adddependant','financialaid'));
            $links.=$href->show()."<br />";
            $href = new href("index.php?module=financialaid&amp;action=addparttimejob&amp;appnum=$appnum",$this->objLanguage->languagetext('mod_financialaid_addparttimejob','financialaid'));
            $links.=$href->show()."<br />";
            $href = new href("index.php?module=financialaid&amp;action=addstudentfamily&amp;appnum=$appnum",$this->objLanguage->languagetext('mod_financialaid_addstudentfamily','financialaid'));
            $links.=$href->show()."<br />";
        }
		
		$studentid = $this->getParam('id');
		if ($studentid) {
			$links .= "<strong>".$this->objLanguage->languagetext('mod_financialaid_stddetails','financialaid')."</strong><br />";
			$href = new href("index.php?module=financialaid&amp;action=info&amp;id=$studentid",$this->objLanguage->languagetext('mod_financialaid_basic','financialaid'));
			$links.=$href->show()."<br />";
			$href = new href("index.php?module=financialaid&amp;action=results&amp;id=$studentid",$this->objLanguage->languagetext('mod_financialaid_course','financialaid'));
			$links.=$href->show()."<br />";
			$href = new href("index.php?module=financialaid&amp;action=account&amp;id=$studentid",$this->objLanguage->languagetext('mod_financialaid_account','financialaid'));
			$links.=$href->show()."<br />";
			$href = new href("index.php?module=financialaid&amp;action=matric&amp;id=$studentid",$this->objLanguage->languagetext('mod_financialaid_matric','financialaid'));
			$links.=$href->show()."<br />";
   	//		$href = new href("index.php?module=financialaid&amp;action=addsponsor&amp;id=$studentid","Assign Sponsor");
	//		$links.="<p>&nbsp;&nbsp;&nbsp;".$href->show()."</p>";

		}
  
   //     $links .= "<p><strong>Sponsors</strong></p>";
   //     $href = new href("index.php?module=financialaid&action=newsponsor","New Sponsor");
   //     $links .="<p>&nbsp;&nbsp;&nbsp;".$href->show()."</p>";
   //     $href = new href("index.php?module=financialaid&action=sponsorlist","Sponsor List");
   //     $links .="<p>&nbsp;&nbsp;&nbsp;".$href->show()."</p>";


		return $links;
	}
	
	

}

?>
