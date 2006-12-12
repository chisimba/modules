<?php
/**
* dbfiles class extends dbtable
* @package etd
* @filesource
*/

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}

/**
* dbfiles class for managing the data in the tbl_etd_submission_files table.
* @author Megan Watson
* @copyright (c) 2004 UWC
* @version 1.0
*/

class dbfiles extends dbtable
{
    /**
    * Constructor
    */
    public function init()
    {
        parent::init('tbl_etd_submission_files');
        $this->table = 'tbl_etd_submission_files';
    }

    /**
    * Method to insert file data into the table.
    * @param string $submitId The id of the submission containing the file.
    * @param string $userId The user Id of the user creating the file.
    * @param string $description A brief description of the file.
    * @param string $fileName The name of the file.
    * @param string $fileId The id of the file in the database.
    * @param array $fileData The array containg the file data
    */
    public function addFile($fileData)
    {
        $fields = array();
        $fields['submissionid'] = $fileData['submitId'];
        $fields['filename'] = $fileData['filename'];
        $fields['mimetype'] = $fileData['mime'];
        $fields['filesize'] = $fileData['size'];
        $fields['path'] = $fileData['path'];
        $fields['absolutepath'] = $fileData['absolutepath'];
        if(isset($fileData['description'])){
            $fields['description'] = $description;
        }
        $fields['creatorid'] = $fileData['userId'];
        $fields['datecreated'] = date('Y-m-d H:i:s');
        $fields['updated'] = date('Y-m-d H:i:s');
        $id = $this->insert($fields);
        return $id;
    }

    /**
    * Method to edit file data in the table.
    * @param string $submitId The id of the submission containing the file.
    * @param string $userId The user Id of the user modifying the file.
    * @param string $description A brief description of the file.
    * @param string $fileName The name of the file.
    * @param string $fileId The id of the file in the database.
    */
    public function editFile($id, $userId, $description, $fileName=NULL)
    {
        $fields = array();
        $fields['description'] = $description;
        $fields['modifierid'] = $userId;
        $fields['updated'] = date('Y-m-d H:i:s');
/*
        if(isset($fileId) && !empty($fileId)){
            $fields['fileId'] = $fileId;
        }
*/
        $this->update('id', $id, $fields);
        return $id;
    }

    /**
    * Method to upload a new / replace an existing file
    *
    * @access public
    * @param string $id
    * @param string $fileId
    * @return
    * @deprecated - filemanager replaces filestore
    public function uploadFile($userId, $file, $id = NULL)
    {
        $fileId = $this->getParam('fileId');
        $description = $this->getParam('description');
        $submitId = $this->getParam('submitId');
        $fileName = $_FILES[$file]['name'];

        if(!empty($id)){
            $fileId = $this->objFile->uploadFile($_FILES[$file], $submitId, $fileId);
            $this->editFile($id, $userId, $description, $fileName, $fileId);
        }else{
            $fileId = $this->objFile->uploadFile($_FILES[$file], $submitId);
            $this->addFile($submitId, $userId, $description, $fileName, $fileId);
        }
    }

    /**
    * Method to get the files attached to a submission.
    * @param string $submitId The id of the submission containing the files.
    *
    * @deprecated - filemanager replaces filestore
    public function getFiles($submitId)
    {
        $sql = 'SELECT store.filetype, store.size, file.* FROM '.$this->table.' AS file ';
        $sql .= 'LEFT JOIN '.$this->storeTable.' AS store ';
        $sql .= 'ON file.fileId = store.fileId ';
        $sql .= "WHERE file.submissionId = '$submitId'";
        $data = $this->getArray($sql);

        if(!empty($data)){
            return $data;
        }
        return FALSE;
    }

    /**
    * Method to delete a file.
    */
    public function deleteFile($id)
    {
        $this->delete('id', $id);
    }

    /**
    * Method to delete all files attached to a submission.
    *
    * @access public
    * @param string $submitId The submissions
    * @return
    *
    * @deprecated - filemanager replaces filestore
    public function deleteAllFiles($submitId)
    {
        $data = $this->getFiles($submitId);
        if(!empty($data)){
            foreach($data as $item){
                $this->objFile->eraseFile($item['fileId']);
                $this->delete('id', $item['id']);
            }
        }
        return TRUE;
    }
    */
}
?>