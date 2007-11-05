<?php
/**
 * Blog UI elements file.
 * 
 * This file controls the blog UI elements. It wil allow users to configure the look of their blog.
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
 * @version    CVS: $Id$
 * @package    blog
 * @subpackage blogui
 * @author     Paul Scott <pscott@uwc.ac.za>
 * @copyright  2006-2007 AVOIR
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @link       http://avoir.uwc.ac.za
 * @see        References to other sections (if any)...
 */

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

/**
 * class to control blog ui elements
 * 
 * This class controls the blog UI elements. It wil allow users to configure the look of their blog
 * 
 * @category  Chisimba
 * @package   blog
 * @author    Paul Scott <pscott@uwc.ac.za>
 * @copyright 2006-2007 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   CVS: $Id$
 * @link      http://avoir.uwc.ac.za
 * @see       References to other sections (if any)...
 */
class blogui extends object
{

    /**
     * Blog operations object
     * 
     * @var    object
     * @access public
     */
    public $objblogOps;

    /**
     * left Column layout
     * 
     * @var    object
     * @access public 
     */
    public $leftCol;

    /**
     * Right column layout
     * 
     * @var    object
     * @access public 
     */
    public $rightCol;

    /**
     * middle column layout
     * 
     * @var    object
     * @access public 
     */
    public $middleCol;

    /**
     * Template header
     * 
     * @var    object
     * @access public 
     */
    public $tplHeader;

    /**
     * CSS Layout
     * 
     * @var    object
     * @access public
     */
    public $cssLayout;

    /**
     * Left user menu
     * 
     * @var    object
     * @access public 
     */
    public $leftMenu;

    /**
     * user object
     * 
     * @var    object
     * @access public
     */
    public $objUser;
    
    /**
     * YAML object
     * 
     * @var    object YAML
     */
    public $objYaml;

    /**
     * Standard init function
     * 
     * Initialises and constructs the object via the framework
     * 
     * @return void  
     * @access public
     */
    public function init() 
    {
        // load up the blogops class
        $this->objblogOps = $this->getObject('blogops');
        // user class
        $this->objUser = $this->getObject('user', 'security');
        // load up the htmlelements
        $this->loadClass('href', 'htmlelements');
        // get the css layout object
        $this->cssLayout = $this->newObject('csslayout', 'htmlelements');
        // get the sidebar object
        $this->leftMenu = $this->newObject('usermenu', 'toolbar');
        // initialise the columns
        // left column
        $this->leftCol = NULL;
        // right column
        $this->rightCol = NULL;
        // middle column
        $this->middleCol = NULL;
        // YAML configs
        $this->objYaml = $this->getObject('yaml', 'utilities');
        $this->objConfig = $this->getObject('altconfig', 'config');
        $this->objUser = $this->getObject('user', 'security');
    }

    /**
     * reads config
     * 
     * Loads up and reads the config for a particular user to get the layout sequence
     * 
     * @return void  
     * @access public
     */
    public function myblog() 
    {
    	
    }

    /**
     * three col layout
     * 
     * Creates a 3 column css layout
     * 
     * @return object CSS layout template header
     * @access public 
     */
    public function threeCols() 
    {
        // Set columns to 3
        $this->cssLayout->setNumColumns(3);
        $this->tplHeader = $this->cssLayout;
        return $this->tplHeader;
    }

    /**
     * Left blocks
     * 
     * Blocks that will show up in the left hand column
     * 
     * @param integer $userid The User id
     * @param string  $cats   The categories menu
     * 
     * @return string  Return string
     * @access public 
     */
    public function leftBlocks($userid, $cats = NULL) 
    {
    	$this->config = $this->doConfig($userid);
    	$leftMenu = $this->newObject('usermenu', 'toolbar');
    	// init the variable
        $leftCol = NULL;
    	$config = $this->config;
    	if(isset($config[0]['leftblocks']))
    	{
    		$config = $config[0]['leftblocks'];
    	}
    	else {
    		$config = array();
    		return NULL;
    	}
    	foreach($config as $plugins)
    	{
    		// echo $plugins."<br />";
    		switch ($plugins) {
    			case 'usermenu':
    				if ($this->objUser->isLoggedIn()) {
        			    	$leftCol.= $leftMenu->show();
            		}
    				break;
    			case 'profiles':
    				if ($this->objUser->isLoggedIn()) {
    					$guestid = $this->objUser->userId();
            			if ($guestid == $userid) {
            					$leftCol.= $this->objblogOps->showProfile($userid);
            				}
    				} else {
            					$leftCol.= $this->objblogOps->showFullProfile($userid);
            				}
    				break;
    			case 'adminsection':
    				if ($this->objUser->isLoggedIn()) {
    					$leftCol.= $this->objblogOps->showAdminSection(TRUE);
    				}
    				break;
    			case 'quickcats':
    				if ($this->objUser->isLoggedIn()) {
    					$leftCol.= $this->objblogOps->quickCats(TRUE);
    				}
    				break;
    			case 'quickpost':
    				if ($this->objUser->isLoggedIn()) {
    					$leftCol.= $this->objblogOps->quickPost($userid, TRUE);
    				}
    				break;
    			case 'loginbox':
    				if (!$this->objUser->isLoggedIn()) {
    					$leftCol.= $this->objblogOps->loginBox(TRUE);
    				}
    				break;
    			case 'feeds':
    				$leftCol.= $this->objblogOps->showFeeds($userid, TRUE);
    				break;
    			case 'bloglinks':
    				$leftCol.= $this->objblogOps->showBlinks($userid, TRUE);
    				break;
    			case 'blogroll':
    				$leftCol.= $this->objblogOps->showBroll($userid, TRUE);
    				break;
    			case 'blogpages':
    				$leftCol.= $this->objblogOps->showPages($userid, TRUE);
    				break;
    			case 'blogslink':
    				$leftCol.= $this->objblogOps->showBlogsLink(TRUE);
    				break;
    			case 'archivebox':
    				$leftCol.= $this->objblogOps->archiveBox($userid, TRUE);
    				break;
    			case 'blogtagcloud':
    				$leftCol.= $this->objblogOps->blogTagCloud($userid);
    				break;
    			case 'catsmenu':
    				$leftCol.= $this->objblogOps->showCatsMenu($cats, TRUE, $userid);
    				break;
    			case 'searchbox':
    				$leftCol.= $this->objblogOps->searchBox();
    				break;
    		}
    	}    
        return $leftCol;
    }

    /**
     * Right side blocks
     * 
     * CSS layout for the right hand side blocks
     * 
     * @param integer $userid The user id
     * @param string  $cats   categories
     * 
     * @return string  string of blocks
     * @access public 
     */
    public function rightBlocks($userid, $cats = NULL) 
    {
    	$config = NULL;
    	$config = $this->config;
    	if(isset($config[0]['rightblocks']))
    	{
    		$config = $config[0]['rightblocks'];
    	}
    	else {
    		$config = array();
    		return NULL;
    	}
    	
    	$leftMenu = $this->newObject('usermenu', 'toolbar');
        // init the variable
        $rightCol = NULL;
        
        foreach($config as $plugins)
    	{
    		// echo $plugins."<br />";
    		switch ($plugins) {
    			case 'usermenu':
    				if ($this->objUser->isLoggedIn()) {
        			    	$rightCol.= $leftMenu->show();
            		}
    				break;
    			case 'profiles':
    				if ($this->objUser->isLoggedIn()) {
    					$guestid = $this->objUser->userId();
            			if ($guestid == $userid) {
            					$rightCol.= $this->objblogOps->showProfile($userid);
            				}
    				} else {
            					$rightCol.= $this->objblogOps->showFullProfile($userid);
            				}
    				break;
    			case 'adminsection':
    				if ($this->objUser->isLoggedIn()) {
    					$rightCol.= $this->objblogOps->showAdminSection(TRUE);
    				}
    				break;
    			case 'quickcats':
    				if ($this->objUser->isLoggedIn()) {
    					$rightCol.= $this->objblogOps->quickCats(TRUE);
    				}
    				break;
    			case 'quickpost':
    				if ($this->objUser->isLoggedIn()) {
    					$rightCol.= $this->objblogOps->quickPost($userid, TRUE);
    				}
    				break;
    			case 'loginbox':
    				if (!$this->objUser->isLoggedIn()) {
    					$rightCol.= $this->objblogOps->loginBox(TRUE);
    				}
    				break;
    			case 'feeds':
    				$rightCol.= $this->objblogOps->showFeeds($userid, TRUE);
    				break;
    			case 'bloglinks':
    				$rightCol.= $this->objblogOps->showBlinks($userid, TRUE);
    				break;
    			case 'blogroll':
    				$rightCol.= $this->objblogOps->showBroll($userid, TRUE);
    				break;
    			case 'blogpages':
    				$rightCol.= $this->objblogOps->showPages($userid, TRUE);
    				break;
    			case 'blogslink':
    				$rightCol.= $this->objblogOps->showBlogsLink(TRUE);
    				break;
    			case 'archivebox':
    				$rightCol.= $this->objblogOps->archiveBox($userid, TRUE);
    				break;
    			case 'blogtagcloud':
    				$rightCol.= $this->objblogOps->blogTagCloud($userid);
    				break;
    			case 'catsmenu':
    				$rightCol.= $this->objblogOps->showCatsMenu($cats, TRUE, $userid);
    				break;
    			case 'searchbox':
    				$rightCol.= $this->objblogOps->searchBox();
    				break;
    		}
    	}    
    	return $rightCol;
    }
    
    /**
     * Configuration editor
     *
     * @param integer $userid The user ID
     * 
     * @return array
     */
    public function doConfig($userid)
    {
    	$conf = NULL;
    	if(!file_exists($this->objConfig->getcontentBasePath().'users/'.$userid))
    	{
    		mkdir($this->objConfig->getcontentBasePath().'users/'.$userid, 0777);
    	}
    	// read the YAML config to see what this user wants
    	$yamlfile = $this->objConfig->getcontentBasePath().'users/'.$userid.'/blogconfig.yaml';
    	$conf = $this->objYaml->parseYaml($yamlfile);
    	if(empty($conf))
    	{
    		// load defaults
    		$defaults[] = array(
                     'leftblocks'  => array(
                                       'usermenu', 
                                       'profiles', 
                                       'adminsection', 
                                       'loginbox', 
                                       'feeds', 
                                       'bloglinks', 
                                       'blogroll', 
                                       'blogpages',
                                      ), 
                     'rightblocks' => array(
                                       'blogslink',
                                       'archivebox',
                                       'blogtagcloud',
                                       'catsmenu',
                                       'searchbox',
                                       'quickcats',
                                       'quickpost',
                                      ),
    						          );
    		
    		// write the defaults to the file
    		$yaml = $this->objYaml->saveYaml($defaults);
    		$filename = $yamlfile;
    		if (!$handle = fopen($filename, 'wb')) 
    		{
         		throw new customException($this->objLanguage->languageText("mod_blog_nowriteyamlfile", "blog"));
         		exit;
    		}
			if (fwrite($handle, $yaml) === FALSE) {
        		throw new customException($this->objLanguage->languageText("mod_blog_nowriteyamlfile", "blog"));
        		exit;
    		}
    		fclose($handle);
    		
    		return $this->objYaml->parseYaml($yaml);
    	}
    	else {
    		return $conf;
    	}
    }
}
?>