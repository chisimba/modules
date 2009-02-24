<?php

class zoopy extends controller
{
    /**
     * Object of the dbsysconfig class in the sysconfig module.
     *
     * @access protected
     * @var object
     */
    protected $objSysConfig;

    /**
     * Object of the zoopylib class in the zoopy module.
     *
     * @access protected
     * @var object
     */
    protected $objZoopyLib;

    /**
     * Initialises the instance variables.
     *
     * @access public
     */
    public function init()
    {
        $this->objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
        $this->objZoopyLib = $this->getObject('zoopylib', 'zoopy');
    }

    /**
     * Gets the name of the template to be used.
     *
     * @access public
     * @return string Name of the template.
     */
    public function dispatch()
    {
        $uri = $this->objSysConfig->getValue('mod_zoopy_feed_uri', 'zoopy');
        $this->objZoopyLib->loadFeed($uri);

        $this->appendArrayVar('headerParams', '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('zoopy.css', 'zoopy').'">');

        return 'main_tpl.php';
    }

    /**
     * Determines of the user needs to be logged on in order to view the page.
     *
     * @access public
     * @return boolean True if the user needs to be logged in, false otherwise.
     */
    public function requiresLogin()
    {
        return false;
    }
}
