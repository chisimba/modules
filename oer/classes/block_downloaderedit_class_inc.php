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
     
        $productId = Null;
        $id = Null;
        $producttype = "adaptation";
        $step = '1';
        if (count($data) == 3) {
            $id = $data[0];
            $productId = $data[1];
            $producttype = $data[2];
        } else if (count($data) == 1){
            $productId = $data[0];
        }
        return $this->objDownloaderEdit->show($productId, $id, $producttype);
    }
}
?>