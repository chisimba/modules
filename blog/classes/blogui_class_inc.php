<?php

/**
 * Blog UI elements file
 * 
 * This file controls the blog UI elements. It wil allow users to configure the look of their blog
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
 * @package   blog
 * @author    Paul Scott <pscott@uwc.ac.za>
 * @copyright 2007 Paul Scott
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   CVS: $Id$
 * @link      http://avoir.uwc.ac.za
 * @see       References to other sections (if any)...
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
 * @copyright 2007 Paul Scott
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   CVS: $Id$
 * @link      http://avoir.uwc.ac.za
 * @see       References to other sections (if any)...
 */
class blogui extends object
{

    /**
     * Blog operations object
     * @var    object
     * @access public
     */
    public $objblogOps;

    /**
     * left Column layout
     * @var    object
     * @access public 
     */
    public $leftCol;

    /**
     * Right column layout
     * @var    object
     * @access public 
     */
    public $rightCol;

    /**
     * middle column layout
     * @var    object
     * @access public 
     */
    public $middleCol;

    /**
     * Template header
     * @var    object
     * @access public 
     */
    public $tplHeader;

    /**
     * CSS Layout
     * @var    object
     * @access public
     */
    public $cssLayout;

    /**
     * Left user menu
     * @var    object
     * @access public 
     */
    public $leftMenu;

    /**
     * user object
     * @var    object
     * @access public
     */
    public $objUser;
    
    /**
     * YAML object
     * @var object YAML
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
        //YAML configs
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
     * @param  integer $userid The User id
     * @return string  Return string
     * @access public 
     */
    public function leftBlocks($userid, $cats = NULL) 
    {
    	$config = $this->doConfig($userid);
    	if(isset($config[0]['leftblocks']))
    	{
    		$config = $config[0]['leftblocks'];
    	}
    	else {
    		$config = array();
    		return NULL;
    	}
    	
    	$leftMenu = $this->newObject('usermenu', 'toolbar');
        // init the variable
        $leftCol = NULL;
        // get all the left column blocks
        if ($this->objUser->isLoggedIn()) {
            $guestid = $this->objUser->userId();
            if ($guestid == $userid) {
            	if(in_array('usermenu', $config))
            	{
                	$leftCol.= $leftMenu->show();
            	}
            	if(in_array('profiles', $config))
            	{
                	$leftCol.= $this->objblogOps->showProfile($userid);
            	}
            } else {
            	if(in_array('profiles', $config))
            	{
                	$leftCol.= $this->objblogOps->showFullProfile($userid);
            	}
            }
            if(in_array('adminsection', $config))
            {
            	$leftCol.= $this->objblogOps->showAdminSection(TRUE);
            }
            if(in_array('quickcats', $config))
            {
            	$leftCol.= $this->objblogOps->quickCats(TRUE);
            }
            if(in_array('quickpost', $config))
            {
            	$leftCol .= $this->objblogOps->quickPost($userid, TRUE);
            }
        } else {
        	if(in_array('loginbox', $config))
        	{
        		$leftCol.= $this->objblogOps->loginBox(TRUE);
        	}
        	if(in_array('profiles', $config))
        	{
        		$leftCol.= $this->objblogOps->showFullProfile($userid);
        	}
        }
        if(in_array('feeds', $config))
        {
        	$leftCol.= $this->objblogOps->showFeeds($userid, TRUE);
        }
        if(in_array('bloglinks', $config))
        {
        	$leftCol.= $this->objblogOps->showBlinks($userid, TRUE);
        }
        if(in_array('blogroll', $config))
        {
        	$leftCol.= $this->objblogOps->showBroll($userid, TRUE);
        }
        if(in_array('blogpages', $config))
        {
        	$leftCol.= $this->objblogOps->showPages($userid, TRUE);
        }
        if(in_array('blogslink', $config))
        {
        	$leftCol.= $this->objblogOps->showBlogsLink(TRUE);
        }
        if(in_array('archivebox', $config))
        {
        	$leftCol.= $this->objblogOps->archiveBox($userid, TRUE);
        }   
        if(in_array('blogtagcloud', $config))
        {
        	$leftCol.= $this->objblogOps->blogTagCloud($userid);
        }        
        if(in_array('catsmenu', $config))
        {
        	$leftCol.= $this->objblogOps->showCatsMenu($cats, TRUE, $userid);
        }
        if(in_array('searchbox', $config))
        {
        	$leftCol.= $this->objblogOps->searchBox();
        }
             
        return $leftCol;
    }

    /**
     * Right side blocks
     * 
     * CSS layout for the right hand side blocks
     * 
     * @param  unknown $userid The user id
     * @param  unknown $cats   categories
     * @return string  string of blocks
     * @access public 
     */
    public function rightBlocks($userid, $cats = NULL) 
    {
    	$config = $this->doConfig($userid);
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
        // get all the left column blocks
        if ($this->objUser->isLoggedIn()) {
            $guestid = $this->objUser->userId();
            if ($guestid == $userid) {
            	if(in_array('usermenu', $config))
            	{
                	$rightCol.= $leftMenu->show();
            	}
            	if(in_array('profiles', $config))
            	{
                	$rightCol.= $this->objblogOps->showProfile($userid);
            	}
            } else {
            	if(in_array('profiles', $config))
            	{
                	$rightCol.= $this->objblogOps->showFullProfile($userid);
            	}
            }
            if(in_array('adminsection', $config))
            {
            	$rightCol.= $this->objblogOps->showAdminSection(TRUE);
            }
            if(in_array('quickcats', $config))
            {
            	$rightCol.= $this->objblogOps->quickCats(TRUE);
            }
            if(in_array('quickpost', $config))
            {
            	$rightCol .= $this->objblogOps->quickPost($userid, TRUE);
            }
        } else {
        	if(in_array('loginbox', $config))
        	{
        		$rightCol.= $this->objblogOps->loginBox(TRUE);
        	}
        	if(in_array('profiles', $config))
        	{
        		$rightCol.= $this->objblogOps->showFullProfile($userid);
        	}
        }
        if(in_array('feeds', $config))
        {
        	$rightCol.= $this->objblogOps->showFeeds($userid, TRUE);
        }
        if(in_array('bloglinks', $config))
        {
        	$rightCol.= $this->objblogOps->showBlinks($userid, TRUE);
        }
        if(in_array('blogroll', $config))
        {
        	$rightCol.= $this->objblogOps->showBroll($userid, TRUE);
        }
        if(in_array('blogpages', $config))
        {
        	$rightCol.= $this->objblogOps->showPages($userid, TRUE);
        }
        if(in_array('blogslink', $config))
        {
        	$rightCol.= $this->objblogOps->showBlogsLink(TRUE);
        }
        if(in_array('archivebox', $config))
        {
        	$rightCol.= $this->objblogOps->archiveBox($userid, TRUE);
        }   
        if(in_array('blogtagcloud', $config))
        {
        	$rightCol.= $this->objblogOps->blogTagCloud($userid);
        }        
        if(in_array('catsmenu', $config))
        {
        	$rightCol.= $this->objblogOps->showCatsMenu($cats, TRUE, $userid);
        }
        if(in_array('searchbox', $config))
        {
        	$rightCol.= $this->objblogOps->searchBox();
        }
             
        return $rightCol;
    }
    
    public function doConfig($userid)
    {
    	// read the YAML config to see what this user wants
    	$yamlfile = $this->objConfig->getcontentBasePath().'users/'.$userid.'/blogconfig.yaml';
    	$conf = $this->objYaml->parseYaml($yamlfile);
    	if(empty($conf))
    	{
    		// load defaults
    		$defaults[] = array('leftblocks' => array(
    							'usermenu', 
    							'profiles', 
    							'adminsection', 
    							'loginbox', 
    							'feeds', 
    							'bloglinks', 
    							'blogroll', 
    							'blogpages'
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