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
    }

    public function showPageHeading() {
        return $this->heading;
    }

    public function searchFiles($url) {
        $script = '
                var url = "'.$url.'";
                showSearchForm(url);
        ';
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

    public function getRecentFiles() {
        $data = "<h1>Latest 10 Uploads</h1><hr />";

        return $data;
    }

    public function saveFile($filename) {
        $objMkDir = $this->getObject('mkdir', 'files');

        $destinationDir = $this->objConfig->getcontentBasePath().'/dmsUploadFiles/'.$id;
        $objMkDir->mkdirs($destinationDir);

        @chmod($destinationDir, 0777);

        $objFileUpload = $this->getObject('upload','files');
        $objFileUpload->permittedTypes = array("txt", "xls", "doc","pdf", "mp3", "ppt", "odp", "pps", "css");print_r($objFileUpload->permittedTypes);
        $objFileUpload->overWrite = TRUE;
        $objFileUpload->uploadFolder = $destinationDir.'/';

        $result = $objFileUpload->doUpload(TRUE, $filename);
        $pathParts = pathinfo($fileName);
        
        echo "<br>$filename<br>";
        print_r($pathParts);
        echo "<br>";
        var_dump($result);
        echo "<br>";
        if ($result['success'] == FALSE) {
            //rmdir($this->objConfig->getcontentBasePath().'/dmsUploadFiles/');
            //$filename = isset($_FILES['fileupload']['name']) ? $_FILES['fileupload']['name'] : '';
            return $this->nextAction('uploadFile', array('message'=>'error'));
        }
        else {
            $filename = $result['filename'];
            $mimetype = $result['mimetype'];
            $path_parts = $result['storedname'];
            $ext = $path_parts['extension'];
            $file = $this->objConfig->getcontentBasePath().'/dmsUploadFiles/'.$filename.$ext;

            if (is_file($file)) {
                @chmod($file, 0777);
            }

            // save the file information into the database

            //return $this->nextAction('home');
        }
    }
}

?>
