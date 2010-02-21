<?php

class phirehose extends controller
{
    /**
     * The character to separate list configuration parameters on.
     */
    const CONFIG_SEPARATOR;

    /**
     * Keywords to track on Twitter.
     *
     * @access protected
     * @var    array
     */
    protected $keywords;

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
     * The password of the Twitter account to connect as.
     *
     * @access protected
     * @var    string
     */
    protected $password;

    /**
     * The username of the Twitter account to connect as.
     *
     * @access protected
     * @var    string
     */
    protected $username;

    /**
     * List of webhooks to push to.
     *
     * @access protected
     * @var    object
     */
    protected $webhooks;

    /**
     * Initialises the object properties.
     *
     * @access public
     */
    public function init()
    {
        // Initialise the object properties.
        $this->objCurl         = $this->getObject('curlwrapper', 'utilities');
        $this->objPhirehoseOps = $this->getObject('phirehoseops', 'phirehose');
        $this->objSysConfig    = $this->getObject('dbsysconfig', 'sysconfig');

        // Initialise the data properties.
        $this->keywords = explode(self::CONFIG_SEPARATOR, $this->objSysConfig->getValue('keywords', 'phirehose'));
        $this->password = $this->objSysConfig->getValue('password', 'phirehose');
        $this->username = $this->objSysConfig->getValue('username', 'phirehose');
        $this->webhooks = explode(self::CONFIG_SEPARATOR, $this->objSysConfig->getValue('webhooks', 'phirehose'));
    }

    /**
     * Links into the Twitter Streaming API and listens for new tweets.
     *
     * @access public
     */
    public function dispatch()
    {
        $callback = array($this, 'push');
        $this->objPhirehoseOps->track($this->username, $this->password, $this->keywords, $callback);
    }

    /**
     * Responds to a new tweet by pushing it out via HTTP and/or XMPP.
     *
     * @access public
     * @param  array The data representing the new tweet.
     */
    public function push($data)
    {
        $json = json_encode($data);
        foreach ($this->webhooks as $webhook) {
            $this->objCurl->postCurl($webhook, $json);
        }
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
