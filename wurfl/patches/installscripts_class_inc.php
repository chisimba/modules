<?php

class wurfl_installscripts extends object
{
    private $objAltConfig;

    public function init()
    {
        $this->objAltConfig = $this->getObject('altconfig', 'config');
    }

    public function postinstall()
    {
        $resources = $this->objAltConfig->getModulePath() . 'wurfl/resources/';
        $zip = new ZipArchive();

        if ($zip->open($resources . 'wurfl-2.0.18.xml.zip') === TRUE) {
            $zip->extractTo($resources);
            $zip->close();
        }
    }
}
?>
