<?php

class aibot extends object
{
    protected $objClient;
    protected $objSysConfig;

    public function init()
    {
        require_once $this->getPearResource('XML/RPC.php');

        $this->objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
        $url = parse_url($this->objSysConfig->getValue('url', 'aibot'));
        $objClient = new XML_RPC_Client($url['path'], $url['host'], $url['port']);
    }

    public function chat($text)
    {
        $params = array(new XML_RPC_Value($text, 'string'));
        $message = new XML_RPC_Message('chat', $params);
        $response = $client->send($message);
        var_dump($response);
    }
}
