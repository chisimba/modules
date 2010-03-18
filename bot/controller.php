<?php

class bot extends controller
{
    protected $objBotops;
    protected $objLanguage;
    protected $objMultisearch;

    public function init()
    {
        $this->objBotops      = $this->getObject('botops', 'bot');
        $this->objLanguage    = $this->getObject('language', 'language');
        $this->objMultisearch = $this->getObject('multisearchops', 'multisearch');
    }

    public function dispatch()
    {
        $sender = $this->getParam('sender');
        $to     = $this->getParam('to');
        $body   = $this->getParam('body');

        header('Content-Type: text/plain; charset=UTF-8');
        echo $this->objBotops->process($sender, $to, $message);
    }

    /**
     * Login is not required for this module.
     *
     * @access public
     * @param  string  $action The name of the action.
     * @return boolean Always returns FALSE.
     */
    public function requiresLogin($action)
    {
        return FALSE;
    }
}
