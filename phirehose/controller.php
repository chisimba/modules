<?php

class phirehose extends controller
{
    /**
     * Instance of the curlwrapper class of the utilities module.
     *
     * @access protected
     * @var    object
     */
    protected $objCurl;

    /**
     * Instance of the phirehoseops class of the phirehose module.
     *
     * @access protected
     * @var    object
     */
    protected $objPhirehoseOps;

    /**
     * Instance of the dbsysconfig class of the sysconfig module.
     *
     * @access protected
     * @var    object
     */
    protected $objSysConfig;

    /**
     * List of webhooks to push to.
     *
     * @access protected
     * @var    object
     */
    protected $webhooks;

    /**
     * Standard constructor to load the necessary resources
     * and populate the new object's instance variables.
     *
     * @access public
     */
    public function init()
    {
        $this->objCurl         = $this->getObject('curlwrapper', 'utilities');
        $this->objPhirehoseOps = $this->getObject('phirehoseops', 'phirehose');
        $this->objSysConfig    = $this->getObject('dbsysconfig', 'sysconfig');
        $this->webhooks        = explode('|', $this->objSysConfig->getValue('webhooks', 'phirehose'));
    }

    /**
     * Standard dispatch method to handle the various possible actions.
     *
     * @access public
     */
    public function dispatch()
    {
        $username = $this->objSysConfig->getValue('username', 'phirehose');
        $password = $this->objSysConfig->getValue('password', 'phirehose');
        $keywords = $this->objSysConfig->getValue('keywords', 'phirehose');
        $keywords = explode('|', $keywords);
        $callback = array($this, 'push');
        $this->objPhirehoseOps->track($username, $password, $keywords, $callback);
    }

    /**
     * Responds to a new tweet by pushing it out via HTTP and/or XMPP.
     *
     * @access public
     * @param  array The data representing the new tweet.
     */
    public function push($data)
    {
        var_dump($data);
        ob_flush();
        flush();
    }

    /**
     * Overide the login object in the parent class.
     *
     * @access public
     * @param  string $action The name of the action
     * @return bool
     */
    public function requiresLogin($action)
    {
        return FALSE;
    }
}

?>
