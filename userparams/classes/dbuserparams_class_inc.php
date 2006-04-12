<?php
/**
* 
* Class to handle data access for userparams. 
* 
* This class allows the management of user parameters
* such as ICQ, JABBER, MSN etc for a user. Only the 
* user has rights to enter or change these parameters.
* 
* @author Derek Keats, Jeremy O'Connor
* 
*/
class dbuserparams extends dbTable 
{
    /**
    * 
    * Property to hold the user object
    * 
    * @var object $objUser The user object
    * 
    */
    var $objUser;

    /**
    * 
    * Property to hold the language object
    * 
    * @var object $objLanguage The language object
    * 
    */
    var $objLanguage;

    /**
    * 
    * Standard init function to set the database table and instantiate
    * common classes.
    * 
    */
    function init()
    { 
        // Set the database table for this class
        parent::init('tbl_userparams'); 
        // Get an instance of the user object
        $this->objUser = & $this->getObject('user', 'security'); 
        // Get an instance of the language object
        $this->objLanguage = & $this->getObject('language', 'language');
    } 

    /**
    * 
    * Method to insert a configuration parameter. It first checks if an 
    * insert would produce a duplicate record using the private method
    * _checkForDuplicate.
    * 
    * @var string $userId The userId of the user owning the config item
    * @var string $pname The name of the parameter being set
    * @var string $value The value of the config parameter
    * 
    */
    function insertParam($userId, $pname, $pvalue)
    { 
        // Check if an insert would produce a duplicate
        if (!$this->_checkForDuplicate($pname, $userId)) {
            $this->insert(array('userId' => $userId,
                    'pname' => $pname,
                    'pvalue' => $pvalue,
                    'creatorId' => $this->objUser->userId(),
                    'dateCreated' => date("Y/m/d H:i:s")));
            return true;
        } else {
            return false;
        } #if 
    } #function insertParam
    

    /**
    * 
    * Method to read a user parameter. This is the preferred
    * method for routine lookups.
    * 
    * @var string $module The module code of the module owning the config item
    * @var string $name The name of the parameter being set, use UPPER_CASE
    * @return the value of the parameter, or $default if not found
    * 
    */
    function getValue($pname, $userId=NULL, $default=null)
    {
		if (is_null($userId)) {
		    $userId = $this->objUser->userId();
		}
        if (!isset($this->$pname)) {
            $this->setProperties($userId);
        } 
        if (isset($this->$pname)) {
            return $this->$pname;
        } else {
            return $default;
        } 
    } #function getValue
    
    /**
    * 
    * Method to read a configuration parameter and associated data. Use this
    * method only if you need all the extra data.
    * 
    * @var string $name The name of the parameter being set, use UPPER_CASE
    * @var string $userId The userId of the user owning the parameter
    * @return An array of userId, pname, pvalue, creatorId, dateAdded, modifierId, dateModified
    * 
    */
    function getValueFull($pname, $userId=NULL)
    {
        $where = " WHERE userId='$userId' AND pname='$pname' ";
        return $this->getAll($where);
    } #function getValueFull

    
    /**
    * 
    * Get user parameters for the user denoted by $userId
    * 
    * @param string $userId The user for which to get the parameters
    * 
    */
    function getProperties($userId)
    {
        $where = " WHERE userId='$userId' ";
        $ar = $this->getAll($where);
        return $ar;
    } #function getProperties
    
    /**
    * 
    * Method to delete by supplying an id. Use it
    * instead of delete to make sure that the user 
    * deleting the record owns it. Otherwise deny
    * access.
    * 
    * @param string $id The id of the record to delete
    * @return TRUE|FALSE TRUE on success, FALSE on failure
    *
    */
    function deleteById($id)
    {
        $ar = $this->getRow('id', $id);
        $userId = $ar['userId'];
        if ( $userId == $this->objUser->userId() ) {
            $this->delete('id', $id);
            return TRUE;
        } else {
            return FALSE;
        }
        
    } #function deleteById
    

    /**
    * Method to check if a configuration parameter is set
    * 
    * @var string $module The module code of the module owning the config item
    * @var string $name The name of the parameter being set
    */
    function checkIfSet($pname, $userId=NULL)
    {
        $where = " WHERE userId='$userId' AND pname='$pname'";
        if ($this->getRecordCount($where) >= 1) {
            return true;
        } else {
            return false;
        } #if
    } #function checkIfSet

    
    /**
    * 
    * Save method to save the results of a single addition
    * or edit
    * 
    * @param string $mode edit|add edir or add
    * @return TRUE
    * 
    */
    function saveSingle($mode = "edit")
    {
        $userId = $this->objUser->userId();
        $pname = TRIM($_POST['pname']);
        $pvalue = TRIM($_POST['pvalue']);
        if ($mode == "add") {
            $this->insertParam($userId, $pname, $pvalue);
            return TRUE;
        } else {
            $id = $this->getParam('id', NULL);
            $modifierId = $this->objUser->userId();
            $dateModified = date("Y/m/d H:i:s");
            $this->update("id", $id, array('pname' => $pname,
                    'pvalue' => $pvalue,
                    'modifierId' => $modifierId,
                    'dateModified' => $dateModified));
            return TRUE;
        } #if
    } #function saveSingle
    
    
    /**
    * Set properties of the configuration object corresponding to all the 
    * config parameters for $module.
    * 
    * @param string $module The module for which to set the properties
    */
    function setProperties($userId)
    {
        $where = " WHERE userId='$userId' ";
        $ar = $this->getAll($where);
        if (!(count($ar) >= 1)) {
            return false;
        } else {
            foreach ($ar as $line) {
                $pname = $line['pname'];
                $pvalue = $line['pvalue'];
                $this->$pname = $pvalue;
            } #foreach
        } #if 
        return true; //$ar;
    } #function setProperties
    
    /*------------------ PRIVATE METHODS BELOW LINE ------------------------*/

    /**
    * Method to get the config file as a string
    * 
    * @var string $name The name of the parameter being looked up
    * @var string $userId The userId of the user owning the parameter
    */
    function _checkForDuplicate($name, $userId)
    {
        $where = " WHERE userId='$userId' AND pname='$name' ";
        if ($this->getRecordCount($where) >= 1) {
            return true;
        } else {
            return false;
        } #if
    } #function _checkForDuplicate
    
    /**
    * Method to get the id field for a userId/name combination
    * 
    * @var string $name The name of the parameter being looked up
    * @var string $userId The module code of the module owning the config item
    */
    function _lookUpId($name, $userId)
    {
        $where = " WHERE userId='$userId' AND pname='$name' ";
        $ar = $this->getAll($where);
        if (count($ar) > 0) {
            return $ar['0']['id'];
        } else {
            die($this->objLanguage->languageText("mod_sysconfig_err_keynotexist"));
        } 
    } #function _lookUpId
    
} #dbUserParams class

?>
