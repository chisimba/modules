<?php

class gift extends controller
{
    public $id;
    public $msg;

    function init() {
        // Importing classes for use in controller
        $this->objLanguage = $this->getObject("language", "language");
        $this->objDbGift   = $this->getObject("dbgift");
        $this->objGift     = $this->getObject("giftops");
        $this->objHome     = $this->getObject("home");
        $this->objUser     = $this->getObject("user","security");
        $this->objEdit     = $this->getObject("edit");
		
        // Initialising $data (holds the data from database when edit link is called)
        $this->data = array();
    }
  
    function dispatch($action) {
        switch ($action) {
            case 'add': return $this->add();
            case 'submitadd': return $this->submitAdd();
            case 'result': return $this->result();
            case 'edit': return $this->edit();
            case 'archive': return $this->archive();
            case 'submitedit': return $this->submitEdit();
            default: return "home_tpl.php";
        }
    }
	
    function add() {
        return "addedit_tpl.php";
    }
	
    function submitAdd() {
        $donor = $this->getParam('dnvalue');
        $recipient = $this->objUser->fullName();
        $name = $this->getParam('gname');
        $description = $this->getParam('descripvalue');
        $value = $this->getParam('gvalue');
        $listed = $this->getParam('gstatevalue');
		
        $result = $this->objDbGift->addInfo($donor,$recipient,$name,$description,$value,$listed);

        if($result) {
            $this->msg = $this->objLanguage->languageText('mod_addInfoSuccess','gift');
        }
        else {
            $this->msg = $this->objLanguage->languageText('mod_infoFailure','gift');
        }

        return "home_tpl.php";
    }
	
    function result() {
        $donor = $this->getParam('donor');
        $recipient = $this->objUser->fullName();
        $giftname = $this->getParam('giftname');
        $this->status = $this->getParam('archived');
	
        $qry = "SELECT * FROM tbl_gifttable WHERE (donor LIKE '%$donor' OR recipient LIKE '%$recipient' OR giftname LIKE '%$giftname') AND listed='".!$this->status."'";
        $this->data = $this->objDbGift->getInfo($qry);

        return "edit_tpl.php";
    }
	
    function edit() {
        return "addedit_tpl.php";
    }

    function archive() {
        $data = array();
        $id = $this->getParam('id');
        $result = $this->objDbGift->archive($id);

        if($result) {
            $this->msg = $this->objLanguage->languageText('mod_archiveSuccess','gift');
        }
        else {
            $this->msg = $this->objLanguage->languageText('mod_infoFailure','gift');
        }
        return 'home_tpl.php';
    }
	
    function submitEdit() {
        $donor = $this->getParam('dnvalue');
        $recipient = $this->objUser->fullName();
        $name = $this->getParam('gname');
        $description = $this->getParam('descripvalue');
        $value = $this->getParam('gvalue');
        $listed = $this->getParam('gstatevalue');
		
        $result = $this->objDbGift->updateInfo($donor,$recipient,$name,$description,$value,$listed,$this->getParam('id'));

        if($result) {
            $this->msg = $this->objLanguage->languageText('mod_updateInfoSuccess','gift');
        }
        else {
            $this->msg = $this->objLanguage->languageText('mod_infoFailure','gift');
        }
		
        return "home_tpl.php";
    }
}
?>
