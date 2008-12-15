<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
/**
 * XML-RPC Client class for the chatterbot
 *
 * @author Paul Scott
 * @copyright GPL
 * @package packages
 * @version 0.1
 */
class chatterbot extends object
{
    /**
     * Language Object
     *
     * @var object
     */
    public $objLanguage;

    /**
     * Config object
     *
     * @var object
     */
    public $objConfig;

    /**
     * Sysconfig object
     *
     * @var object
     */
    public $sysConfig;

    /**
     * Port
     */
    public $port = 8000;

    /**
     * Proxy info
     */
    public $proxy;

    /**
     * Proxy object
     */
    public $objProxy;


    /**
     * Standard init function
     *
     * @param void
     * @return void
     */
    public function init()
    {
        //require_once($this->getPearResource('XML/RPC.php'));
        $this->objConfig = $this->getObject('altconfig', 'config');
        $this->objLanguage = $this->getObject('language', 'language');
        $this->sysConfig = $this->getObject('dbsysconfig', 'sysconfig');
        $this->objProxy = $this->getObject('proxy', 'utilities');

        // get the proxy info if set
        $proxyArr = $this->objProxy->getProxy(NULL);
        if (!empty($proxyArr)) {
            $this->proxy = array(
                'proxy_host' => $proxyArr['proxyserver'],
                'proxy_port' => $proxyArr['proxyport'],
                'proxy_user' => $proxyArr['proxyusername'],
                'proxy_pass' => $proxyArr['proxypassword']
            );
        }
        else {
            $this->proxy = array(
                'proxy_host' => '',
                'proxy_port' => '',
                'proxy_user' => '',
                'proxy_pass' => '',
            );
        }
    }

    /**
     * Chat to the robot by sending a text string and a unique user id and get back a string response
     *
     * @param string $msg
     * @param string $uid
     * @return serialized string (XML)
     */
    public function chat($msg, $uid)
    {
        $msg = new XML_RPC_Message('chat', array(new XML_RPC_Value($msg, "string"), new XML_RPC_Value($uid, "string")));
        $mirrorserv = $this->sysConfig->getValue('chatserver', 'ai');
        $mirrorurl = $this->sysConfig->getValue('chaturl', 'ai');
        $cli = new XML_RPC_Client($mirrorurl, $mirrorserv, $this->port, $this->proxy['proxy_host'], $this->proxy['proxy_port'], $this->proxy['proxy_user'], $this->proxy['proxy_pass']);
        $cli->setDebug(0);

        // send the request message
        $resp = $cli->send($msg);
        //log_debug($resp);
        if (!$resp)
        {
            throw new customException($this->objLanguage->languageText("mod_packages_commserr", "packages").": ".$cli->errstr);
            exit;
        }
        if (!$resp->faultCode())
        {
            $val = $resp->value();
            return $val->serialize($val);
        }
        else
        {
            /*
            * Display problems that have been gracefully caught and
            * reported by the xmlrpc server class.
            */
            throw new customException($this->objLanguage->languageText("mod_packages_faultcode", "packages").": ".$resp->faultCode().$this->objLanguage->languageText("mod_packages_faultreason", "packages").": ".$resp->faultString());
        }
    }
}
?>