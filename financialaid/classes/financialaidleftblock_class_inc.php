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


	public function show(){

//        $style = '<link rel="stylesheet" type="text/css" href="modules/financialaid/resources/finaid.css" />';
        $this->appendArrayVar('headerParams','<link rel="stylesheet" type="text/css" href="modules/financialaid/resources/finaid.css" />');

    	$this->objLanguage = &$this->getObject('language','language');
        $this->objDBFinancialAidWS = & $this->getObject('dbfinancialaidws');

        $appid = $this->getParam('appid');

        $header = "<h1>".$this->objLanguage->languagetext('mod_financialaid_financialaid','financialaid')."</h1>";
        $links = '';
        
		$link = new link();
    	$link->href=$this->uri(array('action'=>'ok'));
    	$link->link = $this->objLanguage->languagetext('word_introduction');

		$links.="<span class='menulink'>".$link->show()."</span><br /><br />";

		$links .= "<strong>".$this->objLanguage->languagetext('mod_financialaid_applications','financialaid')."</strong><br />";

		$link = new link();
    	$link->href=$this->uri(array('action'=>'searchapplications', 'all'=>'yes','dispcount'=>'25'));
    	$link->link = $this->objLanguage->languagetext('mod_financialaid_showallapps','financialaid');
		$links.="<span class='menulink'>".$link->show()."</span><br />";


		$link = new link();
    	$link->href=$this->uri(array('action'=>'searchmarkrange'));
    	$link->link = $this->objLanguage->languagetext('mod_financialaid_searchmarkrange','financialaid');
		$links.="<span class='menulink'>".$link->show()."</span><br />";
        $links.="<br />";
        
        $links .="<b>".$this->objLanguage->languagetext('mod_financialaid_addapp','financialaid')."</b><br />";

		$link = new link();
    	$link->href=$this->uri(array('action'=>'addapplication'));
    	$link->link = $this->objLanguage->languagetext('mod_financialaid_addstudent','financialaid');
  		$links.="<span class='menulink'>".$link->show()."</span><br />";

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

		$link = new link();
    	$link->link = $this->objLanguage->languagetext('mod_financialaid_addappinfo','financialaid');

        if (strlen($appid) > 0){
        	$link->href=$this->uri(array('action'=>'addappinfo','appid'=>$addid));
        }else{
        	$link->href=$this->uri(array('action'=>'addappinfo'));
        }
        $links.="<span class='menulink'>".$link->show()."</span><br /><br />";
        
		$links .= "<strong>".$this->objLanguage->languagetext('word_sponsors')."</strong><br />";


		$link = new link();
    	$link->href=$this->uri(array('action'=>'searchsponsors','all'=>'yes'));
    	$link->link = $this->objLanguage->languagetext('mod_financialaid_listsponsors','financialaid');
		$links.="<span class='menulink'>".$link->show()."</span><br />";

		return $header.$links;
	}
}

?>
