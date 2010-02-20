<?php

class phirehose extends controller
{
    protected $objPhirehoseOps;
    protected $objSysConfig;

    public function init()
    {
        $this->objPhirehoseOps = $this->getObject('phirehoseops', 'phirehose');
        $this->objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
    }

    public function dispatch()
    {
        $username = $this->objSysConfig->getValue('username', 'phirehose');
        $password = $this->objSysConfig->getValue('password', 'phirehose');
        $keywords = $this->objSysConfig->getValue('keywords', 'phirehose');
        $callback = array($this, 'push');
        $this->objPhirehoseOps->track($username, $password, $keywords, $callback);
    }

    public function push($data)
    {
        var_dump($data);
        ob_flush();
        flush();
    }

    public function requiresLogin($action)
    {
        return FALSE;
    }
}

?>
