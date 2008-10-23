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
    protected $objDbLocation;
    protected $feToken;
    protected $feTokenSecret;
    protected $feUser;

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
        $this->objDbLocation = $this->getObject('dblocation', 'location');
        $this->feToken = $this->objDbLocation->getFireEagleToken();
        $this->feTokenSecret = $this->objDbLocation->getFireEagleSecret();
    }

    /**
     * Returns the Fire Eagle user location array
     * @return array User location information
     */
    public function getFireEagleUser()
    {
        if ($this->feKey && $this->feSecret && $this->feToken && $this->feTokenSecret && !$this->feUser) {
            $fireeagle = new FireEagle($this->feKey, $this->feSecret, $this->feToken, $this->feTokenSecret, $this->json);
            $this->feUser = $fireeagle->user();
        }
        return $this->feUser;
    }

    /**
     * Initialises Fire Eagle OAuth authentication handshake
     */
    public function initFireEagleHandshake()
    {
        $fireeagle = new FireEagle($this->feKey, $this->feSecret, null, null, $this->json);
        $token = $fireeagle->getRequestToken();
        $_SESSION['request_token'] = $token['oauth_token'];
        $_SESSION['request_secret'] = $token['oauth_token_secret'];
        header('Location: ' . $fireeagle->getAuthorizeURL($token['oauth_token']));
        exit;
    }

    /**
     * Handles the Fire Eagle authentication handshake callback
     */
    public function handleFireEagleCallback()
    {
        if ($_GET['oauth_token'] != $_SESSION['request_token']) {
            die('Token mismatch');
        }
        $fireeagle = new FireEagle($this->feKey, $this->feSecret, $_SESSION['request_token'], $_SESSION['request_secret'], $this->json);
        $token = $fireeagle->getAccessToken();
        $this->feToken = $token['oauth_token'];
        $this->feTokenSecret = $token['oauth_token_secret'];
        $this->objDbLocation->setFireEagleToken($token['oauth_token']);
        $this->objDbLocation->setFireEagleSecret($token['oauth_token_secret']);
        $this->objDbLocation->put();
   }

    /**
     * Updates the local database with the latest data from Fire Eagle
     */
    function update()
    {
        $oldLongitude = $this->objDbLocation->getLongitude();
        $oldLatitude = $this->objDbLocation->getLatitude();
        $oldName = $this->objDbLocation->getName();

        $location = $this->getFireEagleUser();
        $name = $location['user']['location_hierarchy'][0]['name'];
        $longitude = $location['user']['location_hierarchy'][0]['geometry']['coordinates'][0][0][0];
        $latitude = $location['user']['location_hierarchy'][0]['geometry']['coordinates'][0][0][1];

        if ($oldLongitude != $longitude || $oldLatitude != $latitude || $oldName != $name) {
            $this->objDbLocation->setLongitude($longitude);
            $this->objDbLocation->setLatitude($latitude);
            $this->objDbLocation->setName($name);
            $this->objDbLocation->put();
            /*if ($this->twitterUpdates && $this->objTwitterLib) {
                $this->objTwitterLib->setUid($this->userName);
                $this->objTwitterLib->updateStatus('Current Location: ' . $name);
            }*/
        }
    }

    /**
     * Has the current user already been authenticated?
     * @return boolean
     */
    function isFireEagleAuthenticated()
    {
        return $this->feToken && $this->feTokenSecret;
    }
}
