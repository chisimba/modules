<?php
/* -------------------- sysconfig class extends controller ----------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
} 
/**
* 
* Userparams module controller for KEWL.NextGen. THe userparams 
* module allows the input and storage of pairs of user information
* in the form of name and value
* 
* For example
*  icq_number, 12760932
*  msn_address, derekkeats@hotmail.com
*  favourite_colout, blue
* 
* This enables the storage of an unlimited amount of additional
* user information withou having to write a specialized module to
* do it.
* 
* @author Derek Keats 
* 
*/
class userparams extends controller {

    /**
    * var object Property to hold database access object
    */
    var $objDbUserparams;
    
    /**
    * var object Property to hold language object
    */
    var $objLanguage;
    
    /**
    * var object Property to hold user object
    */
    var $objUser;
    
    /**
    * Standard init function
    */
    function init()
    {
        //Get an instance of the configuration object
        $this->objDbUserparams = & $this->getObject('dbUserParams');
        //Get an Instance of the language object
        $this->objLanguage = &$this->getObject('language', 'language');
        //Get an instance of the user object
        $this->objUser = & $this->getObject('user', 'security');
        
        $this->setLayoutTemplate('user_layout_tpl.php');
    }
    
    /**
    * Dispatch method for sysconfig class. It selects the steps in
    */
    function dispatch()
    {
        $action = $this->getParam("action", NULL);
        $userId=$this->objUser->userId();
        $title=$this->objLanguage->languageText("mod_userparams_title");
        switch ($action) {
            case NULL:
            case 'listall':
                $ar = $this->objDbUserparams->getProperties($userId);
                $this->setVar('str', 'Tested with null');
                $this->setVarByRef('ar', $ar);
                return 'listall_tpl.php';
            case 'edit':
                $this->setVar('arUp', $this->getUserParamsList());
                $id = $this->getParam("id", NULL);
                $ar = $this->objDbUserparams->getRow('id', $id);
                $this->setVar('action', 'edit');
                $this->setVarByRef('ar', $ar);
                return "edit_add_tpl.php";
                break;
            case 'add':
                $this->setVar('arUp', $this->getUserParamsList());
                //Set the action
                $this->setVar('action', 'add');
                //Set the mode variable to add
                $this->setVar('mode', 'add');
                //Set the string to display the form
                return "edit_add_tpl.php";
            case 'save':
                $userId = $this->objUser->userId();
                $pname = $this->getParam('pname', NULL);
                $pvalue = $this->getParam('pvalue', NULL);
                $mode = $this->getParam('mode', NULL);
                //select waht to do dependingon whether we are editing or adding
                if ($mode=='edit') {
                    if ($this->objDbUserparams->saveSingle("edit")) {
                        $ar = $this->objDbUserparams->getProperties($userId);
                        $this->setVarByRef('ar', $ar);
                    } else {
                        $rep = array('PARAMNAME' => $pname);
                        $erMsg = $this->objLanguage->code2Txt('mod_userparams_err_updatefail', $rep);
                        $this->setVarByRef('errorStr', $erMsg);
                    }
                } else {
                    if ($this->objDbUserparams->insertParam($userId, $pname, $pvalue)) {
                        $ar = $this->objDbUserparams->getProperties($userId);
                        $this->setVarByRef('ar', $ar);
                    } else {
                        $rep = array('PARAMNAME' => $pname);
                        $erMsg = $this->objLanguage->code2Txt('mod_userparams_err_dupattempt', $rep);
                        $this->setVarByRef('errorStr', $erMsg);
                    }
                } #if mode=add
                $this->nextAction("listall");
                break;

            //If we want to delet a parameter
            case 'delete': 
                // retrieve the confirmation code from the querystring
                $confirm = $this->getParam("confirm", "no");
                if ($confirm == "yes") {
                    $this->objDbUserparams->deleteById($this->getParam('id', null));
                    $ar = $this->objDbUserparams->getProperties($userId);
                    $this->setVarByRef('ar', $ar);
                }
                $this->nextAction("listall");
                break;
            default:
                die($this->objLanguage->languageText("phrase_actionunknown").": ".$action);
                break;
        } #switch
            
    }
    
    
    function getUserParamsList()
    {
        $objUpa = & $this->getObject('dbuserparamsadmin', 'userparamsadmin');
        $sql = "SELECT pname, ptag FROM tbl_userparamsadmin";
        return $objUpa->getArray($sql);
    }

} # end of class

?>
