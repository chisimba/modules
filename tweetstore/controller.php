<?php

class tweetstore extends controller
{
    private $objJson;
    private $objMongo;
    private $objSysConfig;
    private $whitelist;

    public function init()
    {
        $this->objJson = $this->getObject('json', 'utilities');
        $this->objMongo = $this->getObject('mongoops', 'mongo');
        $this->objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');

        $collection = $this->objSysConfig->getValue('collection', 'tweetstore');
        $this->objMongo->setCollection($collection);

        $whitelist = $this->objSysConfig->getValue('whitelist', 'tweetstore');
        $this->whitelist = explode('|', $whitelist);
        if (!in_array($_SERVER['SERVER_ADDR'], $this->whitelist)) {
            $this->whitelist[] = $_SERVER['SERVER_ADDR'];
        }
    }

    public function dispatch()
    {
        switch ($this->getParam('action')) {
            case 'add':
                if (in_array($_SERVER['REMOTE_ADDR'], $this->whitelist)) {
                    $json = file_get_contents('php://input');
                    $data = $this->objJson->decode($json);
                    if (is_array($data)) {
                        $this->objMongo->insert($data);
                    }
                } else {
                    header('HTTP/1.1 403 Forbidden');
                }
                break;
            default:
                $cursor = $this->objMongo->find();
                $data = iterator_to_array($cursor);
                $json = $this->objJson->encode($data);
                header('Content-Type: application/json; charset=UTF-8');
                echo $json;
        }
    }

    public function requiresLogin($action)
    {
        return FALSE;
    }
}
