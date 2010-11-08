<?php

class tweetstreamer extends controller
{
    private $objSysConfig;

    public function init()
    {
        $this->objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
    }

    public function dispatch()
    {
        $socketio_host = $this->objSysConfig->getValue('socketio_host', 'tweetstreamer');
        $socketio_port = $this->objSysConfig->getValue('socketio_port', 'tweetstreamer');

        $this->setVar('socketio_host', $socketio_host);
        $this->setVar('socketio_port', $socketio_port);

        return 'main_tpl.php';
    }

    public function requiresLogin()
    {
        return FALSE;
    }
}
