<?php

/**
 * Nodechat library class.
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
 * @category  chisimba
 * @package   nodechat
 * @author    Charl van Niekerk <charlvn@charlvn.com>
 * @copyright 2011 Charl van Niekerk
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id$
 * @link      http://avoir.uwc.ac.za/
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
 * Nodechat library class.
 *
 * @category  chisimba
 * @package   nodechat
 * @author    Charl van Niekerk <charlvn@charlvn.com>
 * @copyright 2011 Charl van Niekerk
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id$
 * @link      http://avoir.uwc.ac.za/
 */
class nodechatlib extends object
{
    /**
     * Instance of the dbsysconfig class of the sysconfig module.
     *
     * @access private
     * @var    object
     */
    private $objSysConfig;

    /**
     * Initialises object properties.
     *
     * @access public
     */
    public function init()
    {
        $this->objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
    }

    /**
     * Returns the HTML to display an iframe containing the chat.
     *
     * @access public
     * @param  string $width  The width of the iframe.
     * @param  string $height The height of the iframe.
     * @param  string $port   The port running Node.js. Defaults to the module configuration setting.
     * @return string The iframe HTML.
     */
    public function iframe($width, $height, $port=NULL)
    {
        if ($port === NULL) {
            $port = $this->objSysConfig->getValue('port', 'nodechat');
        }

        $document = new DOMDocument();
        $iframe = $document->createElement('iframe');
        $iframe->setAttribute('src', 'http://'.$_SERVER['SERVER_NAME'].':'.$port);
        $iframe->setAttribute('style', 'border:none;width:'.$width.';height:'.$height);
        $document->appendChild($iframe);
        return $document->saveHTML();
    }
}

?>
