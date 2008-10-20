<?php
// security check - must be included in all scripts
if (!
/**
 * Description for $GLOBALS
 * @global unknown $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 */
$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

/**
 * Library class to provide easy access to location related functions 
 *
 * @author    Charl van Niekerk <charlvn@charlvn.za.net>
 * @copyright GNU/GPL, AVOIR
 * @package   location
 * @access    public
 * @version   $Id$
 */
class locationops extends object
{
    protected $objJson;
    protected $objSysConfig;
    protected $feKey;
    protected $feSecret;
    protected $objUserParams;
    protected $feToken;
    protected $feTokenSecret;

    /**
     * Standard constructor to load the necessary resources
     * and populate the new object's instance variables
     */
    public function init()
    {
        // Load resources
        include $this->getResourcePath('oauth/OAuth.php', 'utilities');
        include $this->getResourcePath('fireeagle/fireeagle.php');

        // Create the JSON object for later use in the Fire Eagle library
        $this->json = $this->getObject('json', 'utilities');

        // Read system configuration
        $this->objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
        $this->feKey = $this->objSysConfig->getValue('fireeaglekey', 'location');
        $this->feSecret = $this->objSysConfig->getValue('fireeaglesecret', 'location');

        // Read user configuration
        $this->objUserParams = $this->getObject('dbuserparamsadmin', 'userparamsadmin');
        $this->feToken = $this->objUserParams->getValue('Fire Eagle Token');
        $this->feTokenSecret = $this->objUserParams->getValue('Fire Eagle Token Secret');
    }

    /**
     * Returns the Fire Eagle user location array
     * @return array User location information
     */
    public function getFireEagleUser()
    {
        if ($this->feKey && $this->feSecret && $this->feToken && $this->feTokenSecret) {
            $fireeagle = new FireEagle($this->feKey, $this->feSecret, $this->feToken, $this->feTokenSecret, $this->json);
            return $fireeagle->user();
        } else {
            return false;
        }
    }
}
