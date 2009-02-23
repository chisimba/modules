<?php

class zoopy extends controller
{
    protected $objSysConfig;
    protected $objZoopyLib;

    public function init()
    {
        $this->objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
        $this->objZoopyLib = $this->getObject('zoopylib', 'zoopy');
    }

    public function dispatch()
    {
        $uri = $this->objSysconfig->getValue('zoopy_feed_uri');
        $this->objZoopyLib->loadFeed($uri);

        return 'main_tpl.php';
    }
}
