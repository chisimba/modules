<?php

class wurfl_installscripts extends object
{
    public function postinstall()
    {
        $zip = new ZipArchive();

        if ($zip->open($this->getResourcePath('wurfl-2.0.18.xml.zip')) === TRUE) {
            $zip->extractTo($this->getResourcePath(''));
            $zip->close();
        }
    }
}
?>
