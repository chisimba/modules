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
        $data = explode("|", $this->configData);        
        $productId = NULL;
        $step = '1';
        if (count($data) == 2) {
            $productId = $data[0];
            $step = $data[1];
        } else if (count($data) == 1){
            $productId = $data[0];
        }
        return $this->objDownloaderEdit->show($productId, $step);
    }
}
?>