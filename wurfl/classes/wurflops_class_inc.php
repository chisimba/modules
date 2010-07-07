<?php

/**
 * WURFL Operations Class
 * 
 * Facade class to the WURFL mobile device detection library.
 * 
 * PHP version 5
 * 
 * This program is free software; you can redistribute it and/or modify 
 * it under the terms of the GNU General Public License as published by 
 * the Free Software Foundation; either version 2 of the License, or 
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful, 
 * but WITHOUT ANY WARRANTY; without even the implied warranty of 
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the 
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License 
 * along with this program; if not, write to the 
 * Free Software Foundation, Inc., 
 * 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 * 
 * @category  Chisimba
 * @package   wurfl
 * @author    Charl van Niekerk <charlvn@charlvn.com>
 * @copyright 2010 Charl van Niekerk
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id$
 * @link      http://avoir.uwc.ac.za/
 * @see       http://wurfl.sourceforge.net/
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
 * WURFL Operations Class
 * 
 * Facade class to the WURFL mobile device detection library.
 * 
 * @category  Chisimba
 * @package   wurfl
 * @author    Charl van Niekerk <charlvn@charlvn.com>
 * @copyright 2010 Charl van Niekerk
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id$
 * @link      http://avoir.uwc.ac.za/
 * @see       http://wurfl.sourceforge.net/
 */
class wurflops extends object
{
    /**
     * The system configuration.
     *
     * @access protected
     * @var    object
     */
    protected $objAltConfig;

    /**
     * The WURFL device object.
     *
     * @access protected
     * @var    object
     */
    protected $objDevice;

    /**
     * Initialises object properties.
     *
     * @access public
     */
    public function init()
    {
        $this->objAltConfig = $this->getObject('altconfig', 'config');

        include_once $this->getResourcePath('WURFL/Application.php', 'wurfl');

        $config = new WURFL_Configuration_InMemoryConfig();
        $config->wurflFile($this->getResourcePath('wurfl-2.0.18.xml'));
        $config->wurflPatch($this->getResourcePath('web_browsers_patch.xml'));

        if (extension_loaded('memcache') && $this->objAltConfig->getenable_memcache() == 'TRUE') {
            $servers = chisimbacache::getServers();
            $config->persistence('memcache', array('host'=>$servers[0]['ip'], 'port'=>$servers[0]['port']));
        } elseif (extension_loaded('apc') && $this->objAltConfig->getenable_apc() == 'TRUE') {
            $config->persistence('apc');
        }

        $factory = new WURFL_WURFLManagerFactory($config);
        $manager = $factory->create();

        $this->objDevice = $manager->getDeviceForHttpRequest($_SERVER);
    }

    /**
     * Returns the value of a single capability according to the name provided.
     *
     * @access public
     * @param  string $name The name of the capability.
     * @return string The value of the capability.
     */
    public function __get($name)
    {
        return $this->objDevice->getCapability($name);
    }

    /**
     * Returns an object of all the capabilities of the device.
     *
     * @access public
     * @return object Instance of stdClass with all the capabilities set as properties.
     */
    public function getAllCapabilities()
    {
        return (object) $this->objDevice->getAllCapabilities();
    }
}

?>
