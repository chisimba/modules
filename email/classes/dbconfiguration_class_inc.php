<?
/* ----------- data class extends dbTable for tbl_email_config ----------*/

// security check - must be included in all scripts
if(!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}

/**
* Model class for the table tbl_email_new
* @author Kevin Cyster
*/

class dbconfiguration extends dbTable
{
    function init()
    {
        parent::init('tbl_email_config');
        $this->table='tbl_email_config';

        $this->objUser=&$this->getObject('user','security');
        $this->userId=$this->objUser->userId();

    }

    /**
    * Method to retrieve configs from the data base
    *
    * @return array $data The config data
    */
    function getConfigs()
    {
        $sql="SELECT * FROM ".$this->table;
        $sql.=" WHERE user_id='".$this->userId."'";
        $data=$this->getArray($sql);
        if(!empty($data)){
            $data=$data[0];
            return $data;
        }
        return FALSE;
    }

    /**
    * Method to set configs on the data base
    *
    * @param string $field The config value to change
    * @param string $value The config value
    * @return NULL
    */
    function setConfig($field,$value)
    {
        $fields=array();
        $fields['user_id']=$this->userId;
        $fields[$field]=$value;
        $fields['updated']=date("Y-m-d H:i:s");
        $config=$this->getConfigs();
        if($config!=FALSE){
            $this->update('id',$config['id'],$fields);
        }else{
            $this->insert($fields);
        }
    }
}
?>