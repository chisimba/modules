<?php
/**
 *
 * Tooltip class for jquery
 *
 * This module loads the jquery and also performs checks on versions and duplications
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
 * @package   jquerycore
 * @author    Kevin Cyster kcyster@gmail.com
 * @copyright 2007 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   0.001
 * @link      http://www.chisimba.com
 *
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
* Main class for the jquerycore module
*
* This module loads the jquery and also performs checks on versions and duplications
*
* @package   jquerycore
* @author    Kevin Cyster kcyster@gmail.com
*
*/
class tooltip extends object
{
    /**
     * 
     * Variable to hold the id of the element
     * 
     * @access proteced
     * @var string
     */
    protected $cssId;

    /**
     * 
     * Variable to hold the delay option
     * 
     * @access proteced
     * @var inetger
     */
    protected $delay = 0;

    /**
     * 
     * Variable to hold the track option
     * 
     * @access proteced
     * @var boolean
     */
    protected $track = TRUE;

    /**
     * 
     * Variable to hold the show url option
     * 
     * @access boolean
     * @var inetger
     */
    protected $showUrl = TRUE;

    /**
     * 
     * Variable to hold a content string
     * 
     * @access proteced
     * @var string
     */
    protected $contentString;

    /**
     * 
     * Variable to hold a content jquery function
     * 
     * @access proteced
     * @var string
     */
    protected $contentFunction;

    /**
     * 
     * Variable to hold the showBody
     * 
     * @access proteced
     * @var string
     */
    protected $showBody = ' - ';

    /**
     * 
     * Variable to hold the pngFix
     * 
     * @access proteced
     * @var boolean
     */
    protected $pngFix = TRUE;

    /**
     * 
     * Variable to hold the opacity
     * 
     * @access proteced
     * @var float
     */
    protected $opacity;

    /**
     * 
     * Variable to hold the top position
     * 
     * @access proteced
     * @var string
     */
    protected $top;

    /**
     * 
     * Variable to hold the left position
     * 
     * @access proteced
     * @var string
     */
    protected $left;

    /**
     * 
     * Variable to hold the extra css class
     * 
     * @access proteced
     * @var string
     */
    protected $extraClass;

    /**
     *
     * Intialiser for the tooltip class
     * @access public
     * @return VOID
     *
     */
    public function init()
    {
    }
    
    /**
     *
     * Method to set the tooltip element id.
     * 
     * @param string $cssId The id of the element to have a tooltip
     */
    public function setCssId($cssId)
    {
        if (!empty($cssId) && is_string($cssId))
        {
            $this->cssId = $cssId;
        }
    }
    
    /**
     *
     * Method to set the tooltip display delay.
     * 
     * @param inetger $delay The delay in 
     */
    public function setDelay($delay)
    {
        if (!empty($delay) && is_integer($delay))
        {
            $this->delay = $delay;
        }
    }
    
    /**
     *
     * Method to set the tooltip tracking.
     * 
     * @param boolean $track TRUE to track | FALSE if not
     */
    public function setTrack($track)
    {
        if (!empty($track) && is_bool($track))
        {
            $this->track = $track;
        }
    }
    
    /**
     *
     * Method to set the tooltip show url.
     * 
     * @param boolean $showUrl TRUE to show the href/src | FALSE if not
     */
    public function setShowUrl($showUrl)
    {
        if (!empty($showUrl) && is_bool($showUrl))
        {
            $this->showUrl = $showUrl;
        }
    }

    /**
     *
     * Method to set the tooltip content if not element title.
     * 
     * @param string $contentString The tooltip content string
     */
    public function setContentString($contentString)
    {
        if (!empty($contentString) && (is_string($contentString) || is_integer($contentString)))
        {
            $this->contentString = $contentString;
        }
    }
    
    /**
     *
     * Method to set the tooltip content to a jquery function.
     * 
     * @param boolean $contentFunction The tooltip content
     */
    public function setContentFunction($contentFunction)
    {
        if (!empty($contentFunction) && (is_string($contentFunction) || is_integer($contentFunction)))
        {
            $this->contentFunction = $contentFunction;
        }
    }
    
    /**
     *
     * Method to set the tooltip title string break.
     * 
     * @param boolean $showBody The tooltip title / content break
     */
    public function setShowBody($showBody)
    {
        if (!empty($showBody) && is_string($showBody))
        {
            $this->showBody = $showBody;
        }
    }
    
    /**
     *
     * Method to set the tooltip pngFix.
     * 
     * @param boolean $pngFix TRUE if the pngFix must be applied | FALSE if not
     */
    public function setPngFix($pngFix)
    {
        if (!empty($pngFix) && is_bool($pngFix))
        {
            $this->pngFix = $pngFix;
        }
    }
    
    /**
     *
     * Method to set the tooltip image opacity.
     * 
     * @param float $opacity The opacity of the image
     */
    public function setOpacity($opacity)
    {
        if (!empty($opacity) && is_float($opacity))
        {
            $this->opacity = $opacity;
        }
    }
    
    /**
     *
     * Method to set the tooltip top position.
     * 
     * @param string $top The top position
     */
    public function setTop($top)
    {
        if (!empty($top) && is_string($top))
        {
            $this->top = $top;
        }
    }
    
    /**
     *
     * Method to set the tooltip left position.
     * 
     * @param string $left The left position
     */
    public function setLeft($left)
    {
        if (!empty($left) && is_string($left))
        {
            $this->left = $left;
        }
    }
    
    /**
     *
     * Method to set the tooltip extra css.
     * 
     * @param string $extraClass The extra css class
     */
    public function setExtraClass($extraClass)
    {
        if (!empty($extraClass) && is_string($extraClass))
        {
            $this->extraClass = $extraClass;
        }
    }
    
    /**
     *
     * Method to generate the tooltip javascript and add it to the page
     * 
     * @access public
     * @return VOID 
     */
    public function show()
    {
        $script = "<script type=\"text/javascript\">";
        $script .= "jQuery(function() {";
        $script .= "jQuery('#$this->cssId').tooltip({";
        $script .= "delay: $this->delay";        
        $script .= $this->track ? ",track: true" : ",track: false";
        $script .= $this->showUrl ? ",showUrl: true" : ",showUrl: false";
        $script .= ",showBody: \"$this->showBody\"";        
        $script .= $this->pngFix ? ",pngFix: true" : ",pngFix: false";
        if (isset($this->extraClass))
        {
            $script .= ",extraClass: \"$this->extraClass\"";
        }
        if (isset($this->top))
        {
            $script .= ",top: $this->top";
        }
        if (isset($this->left))
        {
            $script .= ",left: $this->left";
        }
        if (isset($this->opacity))
        {
            $script .= ",opacity: $this->opacity";
        }
        if (isset($this->contentString))
        {
            $script .= ",bodyHandler: function() { return \"$this->contentString\";}";
        }
        if (isset($this->contentFunction))
        {
            $contentFunction = addslashes($ths->contentFunction);
            $script .= ",bodyHandler: function() { return $contentFunction;}";
        }
        $script .= "});});</script>";
        
        $this->appendArrayVar('headerParams', $script);
    }
}
?>