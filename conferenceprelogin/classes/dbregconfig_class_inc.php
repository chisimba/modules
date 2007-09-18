<?php
/**
* dbregconfig class extends dbtable
* @package conferenceprelogin
* @filesource
*/

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}

/**
* Configure the elements on the registration form for a conference
*
* @author Megan Watson
* @copyright (c) 2004-2006 UWC
* @version 0.1
*/

class dbregconfig extends dbtable
{
    /**
    * Constructor method
    */
    function init()
    {
        parent::init('tbl_conference_regconfig');
        $this->table = 'tbl_conference_regconfig';
        
        $this->objUser =& $this->getObject('user', 'security');
  
    }
    
    /**
    * Get the configuration for a conference
    *
    * @access public
    * @return array $data
    */
    function getConfig()
    {
        $sql = 'SELECT * FROM '.$this->table; 
        
        $data = $this->getArray($sql);
        
        if(!empty($data)){
            return $data[0];
        }
        return array();
    }
    
    /**
    * Insert a new registration configuration / update an existing configuration
    *
    * @access public
    * @param string $id The configuration id if exists
    * @return string $id The configuration id
    */
    function addConfig($id = NULL)
    {
        $fields = array();
        $fields['useOrganisation'] = $this->getParam('organisation');
        $fields['useNameBadge'] = $this->getParam('badge');
        $fields['useFlights'] = $this->getParam('flights');
        $fields['useTransfers'] = $this->getParam('transfers');
        $fields['useCarhire'] = $this->getParam('carhire');
        $fields['startReg'] = $this->getParam('start');
        $fields['endEarlyBird'] = $this->getParam('endearly');
        $fields['endReg'] = $this->getParam('end');
        $fields['earlyBirdFee'] = $this->getParam('earlybird');
        $fields['earlyBirdForeign'] = $this->getParam('earlybirdforeign');
        $fields['regFee'] = $this->getParam('regfee');
        $fields['regFeeForeign'] = $this->getParam('regfeeforeign');
        $fields['currency1'] = $this->getParam('currency1');
        $fields['currency2'] = $this->getParam('currency2');
        $fields['accountName'] = $this->getParam('holder');
        $fields['accountNum'] = $this->getParam('number');
        $fields['bank'] = $this->getParam('bank');
        $fields['branch'] = $this->getParam('branch');
        $fields['branchCode'] = $this->getParam('code');
        $fields['swiftCode'] = $this->getParam('swift');
        
        if(!empty($id)){
            $fields['modifierId'] = $this->objUser->userId();
            $fields['dateModified'] = date('Y-m-d H:i');
            
            $this->update('id', $id, $fields);
        }else{
            $fields['creatorId'] = $this->objUser->userId();
            $fields['dateCreated'] = date('Y-m-d H:i');
            $fields['contextCode'] = $this->contextCode;
            
            $id = $this->insert($fields);
        }
        return $id;
    }
}
?>