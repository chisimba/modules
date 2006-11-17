<?php
/* ----------- data class extends dbTable for tbl_guestbook------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
    {
        die("You cannot view this page directly");
    }


/**
 * Model class for the linc DB
 * @author Jeremy O'Connor
 */

class lincclient extends object
{
    var $objSoapClient;
    /**
    * Constructor Method for the class.
    * This method initialises the web service to be used.
    */
    function init(){
        parent::init();
        try{
        $this->objSoapClient = new SoapClient("http://172.16.65.134/s3m5d3v/lincgeneric.php?wsdl");
        }catch(Exception $e){
            die($e->getMessage());
        }
    }

	function getLimitQuery($tableName, $keys, $orderBy, $start=0, $offset=0, $schema='LINC')
	{
		return $this->objSoapClient->getlimitQuery($tableName, $keys, $orderBy, $start, $offset, $schema);
	}
	
	function getQueryCount($tableName, $keys, $schema='LINC')
	{
		return $this->objSoapClient->getQueryCount($tableName, $keys, $schema);
	}
}
