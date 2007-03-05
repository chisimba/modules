<?php
/**
* dbEmbargo class extends dbtable
* @package etd
* @filesource
*/

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}

/**
* dbEmbargo class for managing the data in the tbl_etd_embargos table.
*
* @author Megan Watson
* @copyright (c) 2004 UWC
* @version 0.1
*/

class dbEmbargo extends dbtable
{
    /**
    * Constructor
    */
    public function init()
    {
        try{
            parent::init('tbl_etd_embargos');
            $this->table = 'tbl_etd_embargos';
            $this->objUser = $this->getObject('user', 'security');
        }catch(Exception $e){
            throw customException($e->message());
        }
    }

    /**
    * Method to insert/update an embargo request into the table.
    * @param string $submitId The id of the submission for which an embargo has been requested.
    * @param string $id The id the embargo if it is being edited.
    */
    public function saveEmbargoRequest($submitId, $id = NULL)
    {        
        $request = $this->getParam('reason');
        $period = $this->getParam('period');

        $fields = array();
        $fields['request'] = $request;
        $fields['period'] = $period;
        $fields['updated'] = date('Y-m-d H:i:s');

        if(isset($id) && !empty($id)){
            $fields['modifierid'] = $this->objUser->userId();
            $fields['datemodified'] = date('Y-m-d H:i:s');
            $this->update('id', $id, $fields);
        }else{
            $fields['submissionid'] = $submitId;
            $fields['granted'] = 'pending';
            $fields['creatorid'] = $this->objUser->userId();
            $fields['datecreated'] = date('Y-m-d H:i:s');
            $id = $this->insert($fields);
        }
        return $id;
    }

    /**
    * Method to get the embargo request for a submission.
    * @param string $submitId The id of the submission for which the embargo was been requested.
    */
    public function getEmbargoRequest($submitId)
    {
        $sql = 'SELECT * FROM '.$this->table;
        $sql .= " WHERE submissionid = '$submitId'";

        $data = $this->getArray($sql);
        if(!empty($data)){
            return $data[0];
        }
        return FALSE;
    }

    /**
    * Method to get the reason or request for an embargo.
    * @param string $id The id of the embargo.
    */
    public function getField($id, $fields)
    {
        $sql = "SELECT $fields FROM ".$this->table;
        $sql .= " WHERE id = '$id'";
        $data = $this->getArray($sql);

        if(!empty($data)){
            return $data[0];
        }
        return FALSE;
    }

    /**
    * Method to grant / refuse an embargo request.
    * @param string $id The id of the embargo to grant.
    */
    public function grantEmbargo($id = NULL)
    {
        $period = $this->getParam('period', 3);
        $grant = $this->getParam('grant', 'no');
        $reason = $this->getParam('reason');

        $fields = array();
        $fields['period'] = $period;
        $fields['granted'] = $grant;
        $fields['reason'] = $reason;
        $fields['updated'] = date('Y-m-d H:i:s');

        if($id){
            $fields['modifierid'] = $this->objUser->userId();
            $fields['datemodified'] = date('Y-m-d H:i:s');
            $this->update('id', $id, $fields);
        }else{
            $fields['creatorid'] = $this->objUser->userId();
            $fields['datecreated'] = date('Y-m-d H:i:s');
            $id = $this->insert($fields);
        }
        return $id;
    }

    /**
    * Method to remove an embargo request.
    */
    public function removeEmbargo($id)
    {
        $this->delete('id', $id);
    }
}
?>