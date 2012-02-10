<?php

/**
 * this block is used for rendering downloader edit form
 *
 * @author pwando
 */
class block_downloaderedit extends object {
    private $objDownloaderEdit;

    function init() {
        $this->title = "";
        $this->objDownloaderEdit = $this->getObject("downloaderedit", "oer");
    }

    function show() {
        $id = $this->configData;
        
        return $this->objDownloaderEdit->show();
    }
}
?>