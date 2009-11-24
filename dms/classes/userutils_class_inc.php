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

    public function showPageHeading() {
        return $this->heading;
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

    public function saveFile() {
        //declare user object
        $objUser = $this->getObject('user', 'security');
        /* create directory on which to save files */
        $objMkDir = $this->getObject('mkdir', 'files');

        $destinationDir = $this->objConfig->getcontentBasePath().'/dmsUploadFiles/'.$id;
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
            $data = array('filename'=>$filename, 'filetype'=>$ext, 'date_uploaded'=>strftime('%Y-%m-%d %H:%M:%S',mktime()),'userid'=>$objUser->userId());
            $result = $this->objUploadTable->saveFileInfo($data);

            return "success";
        }
    }

    public function createJSONFileData() {
        // get text documents
        $txtDocs = $this->objUploadTable->textDocs();

        $numRows = count($txtDocs);
        $JSONstr = "[{";
        if($numRows > 0) {
            $count = 1;

            $JSONstr .= "
    filename:'Plain Text Documents',
    duration:'',
    uiProvider:'col',
    cls:'master-task',
    iconCls:'task-folder',
    children:[";
            foreach($txtDocs as $data) {
                $JSONstr .= "
        {
        filename:'".$data['filename']."',
        duration:'".$data['filetype']."',
        uiProvider:'col',
        leaf:true,
        iconCls:'task'
    }";
                if($count < $numRows) {
                    $JSONstr .= ",";
                }

                $count++;
            }
            $JSONstr .= "]";
        }
         $JSONstr .= "
}]";
        echo $JSONstr;
    }
}
?>