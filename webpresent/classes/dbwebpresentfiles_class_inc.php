<?php
// security check - must be included in all scripts
if(!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}
// end of security

class dbwebpresentfiles extends dbtable
{
    
    public function init()
    {
        parent::init('tbl_webpresent_files');
        $this->objUser = $this->getObject('user', 'security');
    }
    
    public function getFile($id)
    {
        return $this->getRow('id', $id);
    }
    
    public function autoCreateTitle()
    {
        return $this->insert(array(
                'processstage'=>'uploading'
            ));
    }
    
    public function removeAutoCreatedTitle($id)
    {
        $row = $this->getRow('id', $id);
        
        if ($row['processstage'] == 'uploading') {
            $this->delete('id', $id);
        }
    }
    
    public function updateReadyForConversion($id, $filename, $mimetype)
    {
        return $this->update('id', $id, array(
                'filename' => stripslashes($filename),
                'mimetype' => $mimetype,
                'filetype' => $this->getFileType($filename),
                'processstage' => 'readyforconversion',
                'creatorid' => $this->objUser->userId(),
                'dateuploaded' => strftime('%Y-%m-%d %H:%M:%S', mktime()),
            ));
    }
    
    public function updateFileDetails($id, $title, $description, $license)
    {
        return $this->update('id', $id, array(
                'title' => stripslashes($title),
                'description' => stripslashes($description),
                'cclicense' => $license
            ));
    }
    
    public function getLatestPresentations()
    {
        return $this->getAll(' ORDER BY dateuploaded DESC LIMIT 10');
    }
    
    private function getFileType($filename)
    {
        $pathInfo = pathinfo($filename);
        
        switch ($pathInfo['extension'])
        {
            case 'ppt':
            case 'pps':
                $type = 'powerpoint';
                break;
            case 'odp':
                $type = 'openoffice';
                break;
            default:
                $type = 'unknown';
                break;
        }
        
        return $type;
    }
}
?>