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
		$test = "test";
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

    /**
     * Add link clicked from home page, calls this method
     * @return string
     */
    function add() {
        return "addedit_tpl.php";
    }

    /**
     * Submits the addition of a new gift to the database
     * and returns to the home page
     * @return string
     */
    function submitAdd() {
        $donor = $this->getParam('donorfield');             // Donor name
        $recipient = $this->objUser->fullName();         // Recipient name
        $name = $this->getParam('giftnamefield');                // Gift's name
        $description = $this->getParam('descfield');  // Description
        $value = $this->getParam('valuefield');              // Gift's value
        $listed = $this->getParam('gstatevalue');        // Archived status
		
        $result = $this->objDbGift->addInfo($donor,$recipient,$name,$description,$value,$listed);

        if($result) {
            $this->msg = $this->objLanguage->languageText('mod_addInfoSuccess','gift');
        }
        else {
            $this->msg = $this->objLanguage->languageText('mod_infoFailure','gift');
        }

        $recipient = $this->objUser->fullName();     // Recipient name

        $qry = "SELECT * FROM tbl_gifttable WHERE recipient = '$recipient'";
        $this->data = $this->objDbGift->getInfo($qry);

        return $this->nextAction('home');
    }

    /**
     * Depending on the archived parameter, finds all gifts donated
     * to the specific owner based on whether the gift is archived
     * or not archived
     * @return string
     */
    function result() {
        $recipient = $this->objUser->fullName();     // Recipient name
	
        $qry = "SELECT * FROM tbl_gifttable";// WHERE recipient = '$recipient'";
        $this->data = $this->objDbGift->getInfo($qry);
		
        return "edit_tpl.php";
    }

    /**
     * Edit link from edit template, calls this method
     * @return string
     */
    function edit() {
        return "addedit_tpl.php";
    }

    /**
     * Updates a record in the database dependent on the gift that
     * was edited and returns to the home page.
     * @return string
     */
    function submitEdit() {
        $donor = $this->getParam('dnvalue');
        $recipient = $this->objUser->fullName();
        $name = $this->getParam('gname');
        $description = $this->getParam('descripvalue');
        $value = $this->getParam('gvalue');
        $listed = $this->getParam('gstatevalue');
        $id =$this->getParam('id');
        
        $result = $this->objDbGift->updateInfo($donor,$recipient,$name,$description,$value,$listed,$id);
		
        if($result) {
            $this->msg = $this->objLanguage->languageText('mod_updateInfoSuccess','gift');
        }
        else {
            $this->msg = $this->objLanguage->languageText('mod_infoFailure','gift');
        }
		
        $qry = "SELECT * FROM tbl_gifttable WHERE recipient = '$recipient'";
        $this->data = $this->objDbGift->getInfo($qry);
        $this->recentdata['id'] = $this->getParam('id');

        return $this->nextAction('result');
        
    }
}
?>
