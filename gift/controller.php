<?php

class gift extends controller
{
    public $id;

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
		
        $this->objDbGift->addInfo($donor,$recipient,$name,$description,$value,$listed);
		
        return "home_tpl.php";
    }
	
    function result() {
        $donor = $this->getParam('donor');
        $recipient = $this->objUser->fullName();
        $giftname = $this->getParam('giftname');
	
        $qry = "SELECT * FROM tbl_gifttable WHERE donor LIKE '%$donor' OR recipient LIKE '%$recipient' OR giftname LIKE '%$giftname'";
        $this->data = $this->objDbGift->getInfo($qry);

        return "edit_tpl.php";
    }
	
    function edit() {
        return "addedit_tpl.php";
    }
	
    function submitEdit() {
        $donor = $this->getParam('dnvalue');
        $recipient = $this->objUser->fullName();
        $name = $this->getParam('gname');
        $description = $this->getParam('descripvalue');
        $value = $this->getParam('gvalue');
        $listed = $this->getParam('gstatevalue');
		
        $this->objDbGift->updateInfo($donor,$recipient,$name,$description,$value,$listed,$this->getParam('id'));
		
        return "home_tpl.php";
    }
}
?>
