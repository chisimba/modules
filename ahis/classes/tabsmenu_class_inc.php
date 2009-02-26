<?php
/**
 * ahis tabsmenu Class
 *
 * File containing tabsmenu layout helper class
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
 * @package   ahis
 * @author    Nic Appleby <nappleby@uwc.ac.za>
 * @copyright 2008 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id$
 * @link      http://avoir.uwc.ac.za
 */
// security check - must be included in all scripts
if (!
/**
 * Description for $GLOBALS
 * @global entry point $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 */
$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check


/**
 * Tabs Menu
 *
 * Class to quickly generate a glossy tabs menu.
 * This is a product of the class of the same name in
 * the toolbar module, modified specifically for AHIS
 *
 * @category  Chisimba
 * @package   ahis
 * @author    Tohir Solomons, Nic Appleby
 * @copyright 2008 AVOIR
 * 
 */
class tabsmenu extends object
{
    
    /**
     * @var array $menuItems List of actions to be include in menu
     */
    private $menuItems;
    
    /**
     * @var string $default The default selected item
     */
    private $default = '';
    
    /**
    * Method to construct the class
    */
    function init()
    {
        // Load Classes Required
        $this->loadClass('link','htmlelements');
        $this->objUser = $this->getObject('user', 'security');
        
        $this->objLanguage = $this->getObject('language', 'language');
        
        $this->menuItems['entry'] = array('text'=>$this->objLanguage->languageText('mod_ahis_dataentry', 'ahis'), 'link'=>$this->uri(array('action'=>'select_officer'), 'ahis'), 'class'=>'homelink');
        $this->menuItems['report'] = array('text'=>$this->objLanguage->languageText('mod_ahis_viewreports', 'ahis'), 'link'=>$this->uri(array('action'=>'view_reports'), 'ahis'));
    }
    
    /**
     * Method to display the menu / toolbar
     */
    public function show()
    {
        $objBreadcrumbs = $this->getObject('breadcrumbs', 'toolbar');
        
        return $this->generateMenu().'<div id="breadcrumbs">'.$objBreadcrumbs->show().'</div>';
        
    }
    
    /**
     * Method to generate the elearn toolbar menu items
     */
    private function generateMenu()
    {        
        // Add Admin Module
        if ($this->objUser->isAdmin()) {
            $this->menuItems['admin'] = array('text'=>$this->objLanguage->languageText('category_admin', 'toolbar', 'Admin'), 'link'=>$this->uri(array('action'=>'admin'), 'ahis'));
        }
        
        $this->determineDefault($this->getParam('action'));
        
        return $this->generateOutput();
    }
    
    
    /**
     * Method to determine which tab should be highlighted dependent on the current module being viewed
     * @param string $module Name of the Current Module
     */
    private function determineDefault($action)
    {        
        switch ($action)
        {
            case 'select_officer':
            case 'passive_surveillance':
            case 'passive_outbreak':
            case 'passive_species':
            case 'passive_vaccine':
                $this->default = 'entry';
                break;
            case 'admin':
            case 'geography_level3_admin':
            case 'geography_level2_admin':
            case 'geography_level3_add':
            case 'geography_level2_add':
            case 'create_territory':
            case 'employee_admin':
            case 'create_employee':
            case 'employee_insert':
            case 'production_admin':
            case 'production_add':
            case 'title_admin':
            case 'title_add':
            case 'status_admin':
            case 'status_add':
            case 'sex_admin':
            case 'sex_add':
            case 'outbreak_admin':
            case 'outbreak_add':
            case 'diagnosis_admin':
            case 'diagnosis_add':
            case 'control_admin':
            case 'control_add':
            case 'quality_admin':
            case 'quality_add':
            case 'age_admin':
            case 'age_add':
            case 'role_admin':
            case 'role_add':
            case 'department_admin':
            case 'department_add':
            case 'report_admin':
            case 'report_add':
                $this->default = 'admin';
                break;
            case 'view_reports':
                $this->default = 'report';
                break;
        }
    }
    
    /**
     * Method to generate the actual toolbar
     */
    private function generateOutput()
    {
        
        // Logout is always last
        if ($this->objUser->isLoggedIn()) {
            $this->menuItems['logout'] = array('text'=>$this->objLanguage->languageText('word_logout', 'system', 'Logout'), 'link'=>$this->uri(array('action'=>'logoff'), 'security'));
        }
        
        $str = '<ul class="glossytabs">';
        
        foreach ($this->menuItems as $menuItem=>$menuInfo)
        {
            $link = new link ($menuInfo['link']);
            $link->link = '<strong>'.$menuInfo['text'].'</strong>';
            
            if (isset($menuInfo['title'])) {
                $link->title = $menuInfo['title'];
            }
            
            if (isset($menuInfo['class'])) {
                $link->cssClass = $menuInfo['class'];
            }
            
            $css = ($this->default == $menuItem) ? ' class="current"' : '';
            $str .= '<li '.$css.'>'.$link->show().'</li>';
        }
        
        $str .= '</ul>';
        
        return $str;
    }
    
}
?>