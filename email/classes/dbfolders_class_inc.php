<?
/* ----------- data class extends dbTable for tbl_email_folders ----------*/

// security check - must be included in all scripts
if(!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}

/**
* Model class for the table tbl_email_folders
* @author Kevin Cyster
*/

class dbfolders extends dbTable
{
    function init()
    {
        parent::init('tbl_email_folders');
        $this->table='tbl_email_folders';

        $this->dbRules=&$this->getObject('dbrules');
        $this->dbRouting=&$this->getObject('dbRouting');
        $this->objUser=&$this->getObject('user','security');
        $this->userId=$this->objUser->userId();
    }

    /**
    * Method for adding a row to the database.
    *
    * @access public
    * @param string $folderName The name of the folder
    * @return string $folderId The id of the folder
    */
    function addFolder($folderName)
    {
        $fields=array();
        $fields['user_id']=$this->userId;
        $fields['folder_name']=$folderName;
        $fields['updated']=date("Y-m-d H:i:s");
        $folderId=$this->insert($fields);
    }

    /**
    * Method for editing a row on the database.
    *
    * @access public
    * @param string $folderId The id of the folder to edit
    * @param string $folderName The name of the folder
    * @return
    */
    function editFolder($folderId,$folderName)
    {
        $fields=array();
        $fields['folder_name']=$folderName;
        $fields['updated']=date("Y-m-d H:i:s");
        $this->update('id',$folderId,$fields);
    }

    /**
    * Method for deleting a row from the database.
    *
    * @access public
    * @param string $folderId The id of the folder to delete
    * @return
    */
    function deleteFolder($folderId)
    {
        $this->delete('id',$folderId);
    }

    /**
    * Method for listing all rows for the current user
    *
    * @return array $data  All row information.
    */
    function listFolders()
    {
        $sql="SELECT * FROM ".$this->table;
        $sql.=" WHERE user_id='".$this->userId."' OR user_id='system'";
        $data=$this->getArray($sql);
        if(!empty($data)){
            foreach($data as $key=>$line){
                $this->dbRules->applyRules($line['id']);
                $emails=$this->dbRouting->getAllMail($line['id'],array(1=>3,2=>'DESC'),NULL);
                $data[$key]['allmail']=!empty($emails)?count($emails):0;
                $unreadMail=$this->dbRouting->getUnreadMail($line['id']);
                $data[$key]['unreadmail']=!empty($unreadMail)?count($unreadMail):0;
            }
            return $data;
        }
        return FALSE;
    }

    /**
    * Method for getting a folder for the current user
    *
    * @param string $folderId The id of the folder to retrieve
    * @return array $data  All row information.
    */
    function getFolder($folderId)
    {
        $sql="SELECT * FROM ".$this->table;
        $sql.=" WHERE id='".$folderId."'";
        $data=$this->getArray($sql);
        if(!empty($data)){
            foreach($data as $key=>$line){
                $emails=$this->dbRouting->getAllMail($line['id'],array(1=>3,2=>'DESC'),NULL);
                $data[$key]['allmail']=!empty($emails)?count($emails):0;
                $unreadMail=$this->dbRouting->getUnreadMail($line['id']);
                $data[$key]['unreadmail']=!empty($unreadMail)?count($unreadMail):0;
            }
            $data=$data[0];
            return $data;
        }
        return FALSE;
    }
}
?>