<?php
/**
 *
 * Generate outputs for the business cards
 *
 * Create a digital business card that uses microformats and web standards,
 * and display it in a nice, CSS based layout. This class generates output
 * in microformat.
 *
 * @todo Add privacy settings
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
 * @package   oembed
 * @author    Derek Keats _EMAIL
 * @copyright 2007 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id: imageprovider_class_inc.php 1 2010-01-01 16:48:15Z dkeats $
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
* Generate outputs for the business cards
*
* Create a digital business card that uses microformats and web standards,
* and display it in a nice, CSS based layout. This class generates output
* in microformat.
*
* @author Derek Keats
* @package oembed
*
*/
class buscard extends object
{

     /**
     *
     * This is a hardcoded array of the known social network providers
     * that will be supported by having Icons stored in this module
     *
     * @var array
     * @access public
     *
     */
    public $networks = array ('africator', 'delicious', 'digg', 'facebook',
        'flickr', 'friendfeed', 'google', 'identica', 'linkedin', 'muti',
        'picasa', 'qik', 'slideshare', 'technorati', 'twitter', 'youtube' );


    /**
    *
    * Constructor for the provider class
    *
    * @access public
    * @return VOID
    *
    */
    public function init()
    {
        // Get an instance of the user object to look up the file owner.
        $this->objUser = $this->getObject('user', 'security');
        // Get an instance of the userparams object to look up additional info.
        $this->objUserParams = $this->getObject("dbuserparamsadmin","userparamsadmin");
        // Get an instance of the language object.
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objUserParams->readConfig();
        // Load the style sheet
        $this->loadCss();
    }

    /**
     *
     * Default show method to show the Digital Business card
     *
     * @param <type> $userId
     * @return <type>
     * @access public
     *
     */
    public function show($userId)
    {
        if ($this->objUser->isActive($userId)) {
            $this->objUserParams->setUserId($userId);
            $this->objUserParams->readConfig();
            $ret = $this->getUserImage($userId);
            $ret .= $this->getFn($userId);
            $ret .= $this->addToTextInfo($this->getInfo('tagline'));
            $ret .= $this->getEmail($userId);
            $ret .= $this->getHomePage($userId);
            foreach ($this->networks as $network) {
                $ret .= $this->getSocialNetwork($network, $userId);
            }
            $ret .= "<br />" . $this->getLinkIcon('mf_hcard');
            $ret = $this->addToLeftCol($ret);
            $ret .= $this->addToRightCol($this->getLatLong($userId));
            // Start rendering.
            $ret = $this->addToVcard($ret);
            unset($this->objUserParams);
            return $this->addToOuterContainer($ret);
        } else {
            unset($this->objUserParams);
            return $this->objLanguage->languageText(
              'mod_digitalbusinesscard_usernotfound',
              'digitalbusinesscard'
            );
        }
    }

    /**
    *
    * Method to show the Digital Business cars in
    * a block
    *
    * @param string $userId The userid of the user to lookup
    * @return string The rendered business card
    * @access public
    *
    */
    public function showBlock($userId)
    {
        $ret = $this->getUserImage($userId);
        $ret .= $this->getFn($userId);
        $ret .= $this->getEmail($userId);
        $ret = "\n\n<div class='vcard'>\n"
          . $ret . "</div>\n\n"
          . '</div>';
        $ret .= $this->getHomePage($userId, TRUE);
        foreach ($this->networks as $network) {
            $ret .= $this->getSocialNetwork($network, $userId, TRUE);
        }
        $ret .= $this->getLatLong($userId, FALSE);
        return $this->addToOuterContainer($ret);
    }

    /**
    *
    * Method to show the Digital Business cars in
    * JSON format
    *
    * @param string $userId The userid of the user to lookup
    * @return string The rendered business card
    * @access public
    *
    */
    public function showJson($userId, $includeHtml=FALSE)
    {
        $networkAr = array();
        foreach ($this->networks as $network) {
            $identifier = $network . 'url';
            if ($item = $this->objUserParams->getValue($identifier)) {
                $networkAr[$network] = $item;
            }
        }
        $ar = array(
            'given_name' => $this->objUser->getFirstname($userId),
            'family_name' => $this->objUser->getSurName($userId),
            'email' => $this->objUser->email($userId),
            'latitude' => $this->objUserParams->getValue("latitude"),
            'longitude' => $this->objUserParams->getValue("longitude"),
            'urls' => $networkAr );
        return json_encode($ar);
    }

    /**
    *
    * Add the content to an outer DIV layer
    *
    * @param string $ret The content to add to the layer
    * @return string The content inside the layer tags
    * @access private
    *
    */
    private function addToOuterContainer($ret)
    {
        return "<div class='hcardOuter'>$ret</div>";
    }

    /**
    *
    * Add the content to an vcard layer
    *
    * @param string $ret The content to add to the layer
    * @return string The content inside the layer tags
    * @access private
    *
    */
    private function addToVcard($ret)
    {
        return "<div class='vcard'>$ret</div>";
    }

    /**
    *
    * Add the content to an floated left layer
    *
    * @param string $ret The content to add to the layer
    * @return string The content inside the layer tags
    * @access private
    *
    */
    private function addToLeftCol($ret)
    {
        return "<div class='vcard_left'>$ret</div>";
    }

    /**
    *
    * Add the content to an floated right layer
    *
    * @param string $ret The content to add to the layer
    * @return string The content inside the layer tags
    * @access private
    *
    */
    private function addToRightCol($ret)
    {
        return "<div class='vcard_right'>$ret</div>";
    }

    /**
    *
    * Add the content to an limited width textinfo span
    *
    * @param string $ret The content to add to the span
    * @return string The content inside the layer tags
    * @access private
    *
    */
    private function addToTextInfo($ret)
    {
        return "<div class='vcard_textinfo'>$ret</div>";
    }

    /**
    *
    * Get the full name of the user and render in in hcard format
    *
    * @param string $userId The userid of the user to look up
    * @return string The rendered full name
    * @access private
    *
    */
    private function getFn($userId)
    {
        $givenName = '<span class="name-wrapper"><span class="given-name">'
          . $this->objUser->getFirstname($userId) . '</span>';
        $surName = '<span class="family-name">'
          . $this->objUser->getSurName($userId) . '</span></span>';
        return '<span class="fn n">'
          . $givenName . ' '
          . $surName . '</span><br />'
          . "\n\n";
    }

    /**
    *
    * Get the email address of the user and render in in hcard format
    *
    * @param string $userId The userid of the user to look up
    * @param boolean $noText TRUE|FALSE whether to return text, default yes
    * @return string The rendered email
    * @access private
    *
    */
    private function getEmail($userId, $noText=FALSE)
    {
        $email = $this->objUser->email($userId);
        $icon = $this->getLinkIcon("email");
        if ($noText) {
            return "<a class='email' href='mailto:$email'>$icon</a><br />\n";
        } else {
            return "<a class='url' rel='me' href='mailto:$email'>$icon $email</a><br />\n";
        }
    }

    /**
    *
    * Get the social network and return it with linked icon
    * and optionally with or without text
    *
    * @param string $network The social network from the array of networks
    * @param string $userId The userid of the person to look up
    * @param boolean $noText TRUE|FALSE whether to return text, default yes
    * @return string The rendered icon/text
    * @access public
    *
    */
    public function getSocialNetwork($network, $userId, $noText=FALSE)
    {
        $identifier = $network . "url";
        if ($url = $this->objUserParams->getValue($identifier)) {
            $icon = $this->getLinkIcon($network);
            if ($noText) {
                return "<a class='url' rel='me' href='$url' "
                 . "target='_blank'>$icon</a><br />\n";
            } else {
                return "<a class='url' rel='me' href='$url' "
                  . "target='_blank'>$icon $url</a><br />\n";
            }
        }
    }

    /**
    *
    * Get the home page of the user
    *
    * @param string $userId The userid of the person to look up
    * @param boolean $noText TRUE|FALSE whether to return text, default yes
    * @return string The rendered icon/text
    * @access public
    *
    */
    public function getHomePage($userId, $noText=FALSE)
    {
        if ($url = $this->objUserParams->getValue("homepage")) {
            $icon = $this->getLinkIcon("home");
            if ($noText) {
                return "<a class='url' rel='me' href='$url' "
                 . "target='_blank'>$icon</a><br />\n";
            } else {
                return "<a class='url' rel='me' href='$url' "
                  . "target='_blank'>$icon $url</a><br />\n";
            }
        }
    }

    /**
    *
    * Method to get an icon for a particular link from the resources/icons
    * directory in this module. The file $network.png must exist.
    *
    * @param string $network The network icon to look up
    * @return string The rendered icon
    * @access private
    *
    */
    private function getLinkIcon($network)
    {
        $img = $this->getResourceUri("icons/$network.png", "digitalbusinesscard");
        return "<img style='border: 0px none; vertical-align:middle' class='snicon' src='$img' /> ";
    }

    /**
    *
    * Load the CSS required for some of the extended
    * functionality and layout
    *
    * @return VOID
    * @access private
    *
    */
    private function loadCss()
    {
        $css = "<link rel=\"stylesheet\" type=\"text/css\" href=\""
          . $this->getResourceUri("css/vcard.css", "digitalbusinesscard")
          . "\" />";
        $this->appendArrayVar('headerParams', $css);
    }

    /**
     *
     * Get the latitude and longitude of the user and return it in hcard format
     * while optionally rendering a google map
     *
     * @param string $userId The userid of the user to lookup
     * @param boolean $showMap Whether or not to show the map, default TRUE
     * @return string The rendered output
     * @access private
     *
     */
    private function getLatLong($userId, $showMap=TRUE)
    {
        $latitude = $this->objUserParams->getValue("latitude");
        $longitude = $this->objUserParams->getValue("longitude");
        if ($latitude && $longitude) {
            $ret = '<span class="geo">'
              . '<abbr class="latitude" title="' . $latitude
              . '">' . $latitude . "</abbr>\n"
              .  '<abbr class="longitude" title="'
              . $longitude . '">' . $longitude . "</abbr>\n"
              . "</span>\n";
            $ret = $this->getLinkIcon("earth") . $ret;
            if ($showMap) {
                $ret .= $this->getMap($latitude, $longitude);
            }
            return $ret;
        }
    }

    /**
     *
     * Method to render a simple google map
     *
     * @param string $latitude Latitude of user
     * @param string $longitude Longitude of user
     * @return string The rendered map
     * @access private
     *
     */
    private function getMap($latitude, $longitude)
    {
        $ret = '<br /><div class="vcard_map">'
          . '<iframe width="425" height="350" '
          . 'frameborder="0" scrolling="no" '
          . 'marginheight="0" marginwidth="0" '
          . 'src="http://maps.google.com/maps?f=q&amp;'
          . 'source=s_q&amp;hl=en&amp;geocode=&amp;q='
          . $latitude .',' . $longitude 
          . '&amp;output=embed"></iframe></div>';
        return $ret;
    }

    /**
    *
    * Return a rendered icon-sized image of the user
    *
    * @param string $userId The userid of the user to lookup
    * @return string the rendered image
    * @access private
    * 
    */
    private function getUserImage($userId)
    {
        return $this->objUser->getSmallUserImage($userId);
    }

    private function getInfo($param)
    {
        if ($ret = $this->objUserParams->getValue($param)) {
            return $ret;
        }
    }
}
?>
