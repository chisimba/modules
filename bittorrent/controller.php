<?php

/**
 * Bittorrent Controller
 * 
 * controller class for bittorrent package
 * 
 * PHP version 5
 * 
 * The license text...
 * 
 * @category  Chisimba
 * @package   bittorrent
 * @author    Paul Scott <pscott@uwc.ac.za>
 * @copyright 2007 Paul Scott
 * @license   gpl
 * @version   CVS: $Id$
 * @link      http://avoir.uwc.ac.za
 * @see       References to other sections (if any)...
 */
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
 * Short description for class
 * 
 * Long description (if any) ...
 * 
 * @category  Chisimba
 * @package   adm
 * @author    Paul Scott <pscott@uwc.ac.za>
 * @copyright 2007 Paul Scott
 * @license   gpl
 * @version   Release: @package_version@
 * @link      http://avoir.uwc.ac.za
 * @see       References to other sections (if any)...
 */
class bittorrent extends controller
{

    /**
     * Description for public
     * @var    unknown
     * @access public 
     */
	public $objLanguage;

    /**
     * Description for public
     * @var    unknown
     * @access public 
     */
	public $objConfig;
	
	public $MakeTorrent;
	
	public $encoder;
	
	public $decoder;
	
	public $torrentops;
	
	/**
     * Constructor method to instantiate objects and get variables
     */
    public function init()
    {
        try {
            $this->objLanguage = $this->getObject('language', 'language');
            $this->objConfig = $this->getObject('altconfig', 'config');
            $this->torrentops = $this->getObject('torrentops');
        }
        catch(customException $e) {
            echo customException::cleanUp();
            die();
        }
    }
    /**
     * Method to process actions to be taken
     *
     * @param string $action String indicating action to be taken
     */
    public function dispatch($action = Null)
    {
        switch ($action) {
            default:
            	// I suppose I should do some kind of tracker code in here, so that it is a self contained system
            	// sigh.
            	echo '<img src="'.$this->getResourceUri('torrents.gif').'">';
            	break;
            	
            case 'createtorrent':
            	echo $this->torrentops->makeTorrent('/var/www/chisimba_framework/app/packages/blog/register.conf', $this->uri(array()), 'Test comment');
				die();
            	break;
            	
            case 'gettorrentinfo':
            	print_r($this->torrentops->torrentInfo($this->getResourcePath('Tryad-Public_Domain.torrent')));
            	break;
        }
    }
}
?>