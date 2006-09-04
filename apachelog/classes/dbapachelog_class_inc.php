<?php
// security check - must be included in all Chisimba scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

/**
* apachelog data access class
*
* Class of methods used to access and manipulate the data in tbl_apachelog
*
* @author Paul Scott
* @category Chisimba
* @package apachelog
* @copyright AVOIR
* @licence GNU/GPL
*
*/
class dbapachelog extends dbtable
{


    /**
    *
    * @param string object $objUser A property to hold an instance of the user object
    *
    */
    public $objUser;



    /**
    *
    * Constructor for the module dbtable class for
    * It sets the database table via the parent dbtable class init
    * method, and instantiates required objects.
    *
    */
    public function init()
    {
        parent::init('tbl_apachelog');
        //Instantiate the user object
        $this->objUser = $this->getObject('user', 'security');
    }

    public function dumpData($dataArr)
    {
    	$data = array(
    		'fullrecord' => $dataArr['fullrecord'],
    		'ip_addr' => $dataArr['ip'],
    		'log_date' => $dataArr['date'],
    		'request' => $dataArr['request'],
    		'servercode' => $dataArr['servercode'],
    		'requrl' => $dataArr['requrl'],
    		'useragent' => $dataArr['useragent'],
    		'userid' => $this->objUser->userId(),
    		'datecreated' => $this->now(),
    		'modifierid' => $this->objUser->userId(),
    		'datelastupdated' => $this->now(),
    		);

    	//print_r($data);
    	$this->insert($data);

    }

    /**
    *
    * Save method for
    * @param string $mode: edit if coming from edit, add if coming from add
    *
    */
    public function saveData($mode)
    {
        //Retrieve the value of the primary keyfield $id
        $id = $this->getParam('id', NULL);
        //Retrieve the value of $fullrecord
        $fullrecord = $this->getParam('fullrecord', NULL);
        //Retrieve the value of $ip_addr
        $ip_addr = $this->getParam('ip_addr', NULL);
        //Retrieve the value of $log_date
        $log_date = $this->getParam('log_date', NULL);
        //Retrieve the value of $request
        $request = $this->getParam('request', NULL);
        //Retrieve the value of $servercode
        $servercode = $this->getParam('servercode', NULL);
        //Retrieve the value of $requrl
        $requrl = $this->getParam('requrl', NULL);
        //Retrieve the value of $useragent
        $useragent = $this->getParam('useragent', NULL);
        //Retrieve the value of $userid
        $userid = $this->getParam('userid', NULL);
        //Retrieve the value of $datelastupdated
        $datelastupdated = $this->getParam('datelastupdated', NULL);

        //If coming from edit use the update code
        if ($mode=="edit") {
            $ar = array(
              'fullrecord' => $fullrecord,
              'ip_addr' => $ip_addr,
              'log_date' => $log_date,
              'request' => $request,
              'servercode' => $servercode,
              'requrl' => $requrl,
              'useragent' => $useragent,
              'userid' => $userid,
              'modifierid' => $this->objUser->fullName(),
              'datelastupdated' => $datelastupdated
            );
            $this->update('id', $id, $ar);
        } else {
            $ar = array(
              'fullrecord' => $fullrecord,
              'ip_addr' => $ip_addr,
              'log_date' => $log_date,
              'request' => $request,
              'servercode' => $servercode,
              'requrl' => $requrl,
              'useragent' => $useragent,
              'userid' => $userid,
              'datecreated' => $datecreated,
              'modifierid' => $modifierid,
              'datelastupdated' => $datelastupdated
            );
            $this->insert($ar);
        }

    }

    /**
    * Method to retrieve the data for edit and prepare the vars for
    * the edit template.
    *
    * @param string $mode The mode should be edit or add
    */
    public function getForEdit()
    {
        $order = $this->getParam("order", NULL);
        // retrieve the group ID from the querystring
        $keyvalue=$this->getParam("id", NULL);
        if (!$keyvalue) {
          die($this->objLanguage->languageText("modules_badkey").": ".$keyvalue);
        }
        // Get the data for edit
        $key="id";
        return $this->getRow($key, $keyvalue);
    }

    /**
    *
    * Delete a record from . Use cautiously as it can delete
    * all records by accident if the wrong key is used.
    *
    * @param string $key The key of the record to delete
    * @param string $keyValue The value of the key where deletion should take place
    *
    */
    public function deleteRecord($key, $keyValue)
    {
       $this->delete($key, $keyValue);
    }


}
?>