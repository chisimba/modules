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
    public function init() {
        //instantiate the language object
        $this->loadClass('link', 'htmlelements');
        //instantiate the file upload object
        $this->objUpload = $this->getObject('upload', 'filemanager');
    }

    public function showUploadForm() {
        return $this->objUpload->show();
    }
}

?>
