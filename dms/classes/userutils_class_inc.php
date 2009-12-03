<?php
if (!
/**
 * Description for $GLOBALS
 * @global string $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 */
$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
class userutils extends object {
    var $heading = "Document Management System";
    public function init() {
        //instantiate the language object
        $this->loadClass('link', 'htmlelements');
        $this->objConfig = $this->getObject('altconfig', 'config');
        $this->objUploadTable = $this->getObject('dbfileuploads');
    }

    public function showPageHeading($page=null) {
        if($page != null) {
            return $this->heading." - ".ucfirst($page);
        }
        else {
            return $this->heading;
        }
    }

    public function searchFiles($url) {
        $script = "
                var url = '".$url."';
                showSearchForm(url);
        ";
        return $script;
    }

    public function showUploadForm($url=null) {
        //instantiate the file upload object
        $this->objUpload = $this->getObject('upload', 'filemanager');
        $script = '
            var url ="'.$url.'";
            showUploadForm(url);
        ';
        return $script;
        //return $this->objUpload->show();
    }

    public function getRecentFiles($url) {
        //$data = "<h1>Latest 10 Uploads</h1><hr />";
        $data = "
                showLatestUploads('".$url."')";
        
        return $data;
    }

    public function saveFile($permissions) {
        //declare user object
        $objUser = $this->getObject('user', 'security');
        /* create directory on which to save files */
        $objMkDir = $this->getObject('mkdir', 'files');

        $userid = $objUser->userId();
        $this->objUploadTable->setUserId($userid);
        if($permissions == 1) {
            $destinationDir = $this->objConfig->getcontentBasePath().'/dmsUploadFiles/'.$userid."/shared/".$id;
        }
        else {
            $destinationDir = $this->objConfig->getcontentBasePath().'/dmsUploadFiles/'.$userid."/".$id;
        }
        $objMkDir->mkdirs($destinationDir);

        @chmod($destinationDir, 0777);

        $objFileUpload = $this->getObject('upload');
        $objFileUpload->overWrite = TRUE;
        $objFileUpload->uploadFolder = $destinationDir.'/';

        $result = $objFileUpload->doUpload(TRUE);
        
        if ($result['success'] == FALSE) {
            return "error";
        }
        else {
            $filename = $result['filename'];
            $ext = $result['extension'];
            $file = $this->objConfig->getcontentBasePath().'/dmsUploadFiles/'.$filename;

            if (is_file($file)) {
                @chmod($file, 0777);
            }

            // save the file information into the database
            $data = array('filename'=>$filename, 'filetype'=>$ext, 'date_uploaded'=>strftime('%Y-%m-%d %H:%M:%S',mktime()),'userid'=>$objUser->userId(), 'shared'=>$permissions);
            $result = $this->objUploadTable->saveFileInfo($data);

            return "success";
        }
    }

    public function createJSONFileData($userid) {
        $this->objUploadTable->setUserId($userid);
        //get distinct file types
        $distinctFT = $this->objUploadTable->getFileTypes();

        $count = 1;
        $numTypes = count($distinctFT);
        $JSONstr = "[";
        foreach($distinctFT as $data) {
            $docs = $this->objUploadTable->getDocs($data['filetype']);
            $numRows = count($txtDocs);
            $JSONstr .= "{";
            
            $numRows = count($docs);
            $JSONstr .= "
    filename:";
            if(strcmp($data['filetype'], "txt") == 0) {
                $JSONstr .= "'Plain Text Documents',";
            }
            else if(strcmp($data['filetype'], "pdf") == 0) {
                $JSONstr .= "'PDF Documents',";
            }
            else if(strcmp($data['filetype'], "doc") == 0) {
                $JSONstr .= "'Word Documents',";
            }
            else if(strcmp($data['filetype'], "mp3") == 0) {
                $JSONstr .= "'Music Files',";
            }
            else if(strcmp($data['filetype'], "odt") == 0) {
                $JSONstr .= "'OpenOffice Word',";
            }
            else {
                $JSONstr .= "'',";
            }
    $JSONstr .= "
    duration:'',
    uiProvider:'col',
    cls:'master-task',
    iconCls:'task-folder',
    children:[";

            $counter = 1;
            foreach($docs as $newdata) {
                $mylink = new link();
                $mylink->link($this->uri(array('action'=>'viewfiledetails', 'id'=>$newdata['id'])));
                $mylink->link = "Click here for details";
                $date = date_create($newdata['date_uploaded']);
                if($newdata['shared'] == '1') {
                    $status = "public";
                }
                else {
                    $status = "private";
                }

                
                $JSONstr .= "
    {
        filename:'".$newdata['filename']."',
        duration:'".$newdata['filetype']."',
        details: '".str_replace("amp;", "", $mylink->show())."',
        modified: '".date_format($date, 'm/d/Y')."',
        status: '".$status."',
        uiProvider:'col',
        leaf:true,
        iconCls:'task'
    }";
                if($counter < $numRows) {
                    $JSONstr .= ",";
                }
                $counter++;
            }
            $JSONstr .= "
]}";
            if($count < $numTypes) {
                $JSONstr .= ",";
            }
            $count++;

        }
        $JSONstr .= "]";
        echo $JSONstr;
    }
}
?>