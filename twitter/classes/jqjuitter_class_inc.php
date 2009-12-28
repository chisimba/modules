<?php
/**
 *
 * jQuery Juitter interface elements
 *
 * Twitter is a module that creates an integration between your Chisimba
 * site using your Twitter account. This class uses jQuery to access the
 * juitter plugin for jQuery
 *
 * You can get Juitter! at
 *    http://juitter.com/
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
 * @package   twitter
 * @author    Derek Keats _EMAIL
 * @copyright 2007 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id: twitterremote_class_inc.php 16033 2009-12-23 16:48:15Z charlvn $
 * @link      http://avoir.uwc.ac.za
 */

// security check - must be included in all scripts
if (!
/**
 * The $GLOBALS is an array used to control access to certain constants.
 * Here it is used to check if the file is opening in engine, if not it
 * stops the file from running.
 *
 * @global entry point $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 *
 */
$GLOBALS['kewl_entry_point_run'])
{
        die("You cannot view this page directly");
}
// end security check

/**
*
* jQuery Juitter interface elements
*
* @author Derek Keats
* @package twitter
*
*/
class jqjuitter extends object
{
    /**
    *
    * @var string $userName The twitter username of the authenticating user
    * @access public
    *
    */
    public $userName='';
    
    /**
    *
    * @var string $objLanguage String object property for holding the
    * language object
    * @access public
    *
    */
    public $objLanguage;

    /**
    *
    * Constructor for the twitterremote class
    * @access public
    * @return VOID
    *
    */
    public function init()
    {
        $this->objLanguage = $this->getObject('language', 'language');
    }

    /**
    *
    * Method to load the jQuery plugin
    *
    * @access public
    * @return VOID
    *
    */
    public function loadJuitterPlugin()
    {
        $script = '<script language="javascript" src="'
          . $this->getResourceUri("jquery.juitter.js", "twitter")
          . '" type="text/javascript"></script>';
        $this->appendArrayVar('headerParams', $script);
        return TRUE;

    }

    /**
    *
    * Method to load the juitter default system.js
    *
    * @access public
    * @return VOID
    *
    */
    public function loadJuitterSystem()
    {
        $script = '<script language="javascript" src="'
          . $this->getResourceUri("system.js", "twitter")
          . '" type="text/javascript"></script>';
        $this->appendArrayVar('headerParams', $script);
        return TRUE;

    }

    /**
    *
    * Method to load the CSS 
    * @access public
    * @return VOID
    *
    */
    public function loadJuitterCss()
    {
        $script = '<link href="'
          . $this->getResourceUri("juitter.css", "twitter")
          . '" media="all" rel="stylesheet" type="text/css"/>
          ';
        $this->appendArrayVar('headerParams', $script);
        return TRUE;
    }

    /**
    *
    * Method to load the <DIV> tags
    * @param string $userName The username of the authenticating user
    * @access public
    * @return VOID
    *
    */
    public function loadJuitterDiv()
    {
        return "<div id=\"juitterContainer\"></div>";
    }

    /**
    *
    * Method to load the <DIV> tags
    * @param string $userName The username of the authenticating user
    * @access public
    * @return VOID
    *
    */
    public function loadJuitterSearchForm()
    {
        return "<form method=\"post\" id=\"juitterSearc\" action=\"\">\n"
          . "<p>Search Twitter: <input type=\"text\" class=\"juitterSearch\" "
          . "value=\"Type a word and press enter\" /></p>\n</form>";
}
    }
?>