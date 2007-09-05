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
        $this->objConfig = $this->getObject('altconfig', 'config');
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
    
    
    private function getFilesForConversion()
    {
        return $this->getAll(' WHERE processstage=\'readyforconversion\' AND inprocess=\'N\'');
    }
    
    private function isInProcess($id)
    {
        $row = $this->getRow('id', $id);
        
        return ($row['inprocess'] == 'Y' ? TRUE : FALSE);
    }
    
    public function convertFiles()
    {
        //echo '<pre>';
        $files = $this->getFilesForConversion();
        
        //print_r($files);
        
        foreach ($files as $file)
        {
            if (!$this->isInProcess($file['id'])) {
                $this->convertFile($file);
            }
        }
    }
    
    private function convertFile($file)
    {
        //print_r($file);
        
        $path_parts = pathinfo($file['filename']);
        
        $ext = $path_parts['extension'];
        
        //echo $this->objConfig->getcontentBasePath().'webpresent/'.$file['id'].'/'.$file['id'].'.'.$ext;
        
        $result = $this->convertAlternative($file['id'], $ext);
        
        if ($result) {
            $result = $this->convertFileFromFormat($file['id'], $ext, 'swf');
        }
        
        if ($result) {
            $result = $this->convertFileFromFormat($file['id'], $ext, 'pdf');
        }
        
        if ($result) {
            $result = $this->convertFileFromFormat($file['id'], $ext, 'html');
        }
        
        return $result;
    }
    
    private function convertAlternative($id, $ext)
    {
        $other = ($ext == 'odp' ? 'ppt' : 'odp');
        
        return $this->convertFileFromFormat($id, $ext, $other);
    }
    
    private function convertFileFromFormat($id, $inputExt, $outputExt)
    {
        $source = $this->objConfig->getcontentBasePath().'webpresent/'.$id.'/'.$id.'.'.$inputExt;
        $conv = $this->objConfig->getcontentBasePath().'webpresent/'.$id.'/'.$id.'.'.$outputExt;
        
        
        if (!file_exists($conv)) {
            $objConvertDoc = $this->getObject('convertdoc', 'documentconverter');
            
            $objConvertDoc->convert($source, $conv);
            
            if (file_exists($conv)) {
                system ('chmod 777 '.$conv);
                @chmod ($conv, 0777);
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            return TRUE;
        }
        
    }
}
?>