<?php
/**
 * Class to handle statusbar elements.
 *
 * This object can be used elsewhere in the system to render certain aspects of the interface.
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
 * @version    0.001
 * @package    schools
 * @author     Kevin Cyster kcyster@gmail.com
 * @copyright  2011 AVOIR
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @link       http://www.chisimba.com
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
 * Class to handle statusbar elements
 *
 * This object can be used elsewhere in the system to render certain aspects of the interface
 *
 * @version    0.001
 * @package    schools
 * @author     Kevin Cyster kcyster@gmail.com
 * @copyright  2011 AVOIR
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @link       http://www.chisimba.com
 */
class statusbarops extends object
{
    /**
     * 
     * Variable to hold the orientation of the status bar
     * 
     * @access proteced
     * @var string
     */
    protected $orientaion;

    /**
     * 
     * Variable to hold the position of the status bar
     * 
     * @access proteced
     * @var string
     */
    protected $position;
    
    /**
     * 
     * Variable to hold the arrearance of the status bar
     * 
     * @access proteced
     * @var string
     */
    protected $appearance;

    /**
     * Standard init function called by the constructor call of Object
     *
     * @access public
     * @return NULL
     */
    public function init()
    {
        try {
            // Load core system objects.
            $this->objLanguage = $this->getObject('language', 'language');
            $this->objUser = $this->getObject('user', 'security');
            $this->userId = $this->objUser->PKId();
            $this->objUserAdmin = $this->getObject('useradmin_model2', 'security');
            $this->objSvars = $this->getObject('serializevars', 'utilities');
            $this->objGroups = $this->getObject('groupadminmodel', 'groupadmin');
            $this->objConfirm = $this->newObject('confirm', 'utilities');

            // Load html elements.
            $this->objIcon = $this->newObject('geticon', 'htmlelements');
            $this->objTable = $this->loadClass('htmltable', 'htmlelements');
            $this->objLink = $this->loadClass('link', 'htmlelements');
            $this->objInput = $this->loadClass('textinput', 'htmlelements');
            $this->objFieldset = $this->loadClass('fieldset', 'htmlelements');
            $this->objDropdown = $this->loadClass('dropdown', 'htmlelements');
            $this->objForm = $this->loadClass('form', 'htmlelements');
            $this->objLayer = $this->loadClass('layer', 'htmlelements');
            $this->objRadio = $this->loadClass('radio', 'htmlelements');
            $this->objTab = $this->newObject('tabber', 'htmlelements');
            
            // Load db classes,
            $this->objDBconfigs = $this->getObject('dbstatusbar_configs', 'statusbar');
            $this->objDBsettings = $this->getObject('dbstatusbar_settings', 'statusbar');
            $this->getParams();
        }
        catch(customException $e) {
            echo customException::cleanUp();
            die();
        }
    }

    /**
     *
     * Method to generate an error string for display
     * 
     * @access private
     * @param string $errorText The error string
     * @return string $string The formated error string
     */
    private function error($errorText)
    {
        $error = $this->objLanguage->languageText('word_error', 'system', 'WORD: word_error, not found');
        
        $this->objIcon->title = $error;
        $this->objIcon->alt = $error;
        $this->objIcon->setIcon('exclamation', 'png');
        $errorIcon = $this->objIcon->show();
        
        $string = '<span style="color: red">' . $errorIcon . '&nbsp;<b>' . $errorText . '</b></span>';
        return $string;
    }
    
    /**
     *
     * Method to get the parameters
     * 
     * @access public
     * @return array $the array of parameters
     */
    public function getParams()
    {
        $settings = $this->objDBsettings->getSettings($this->userId);
        $configs = $this->objDBconfigs->getConfigs();
        if (!empty($settings))
        {
            foreach ($settings as $setting)
            {
                switch ($setting['param'])
                {
                    case 'Orientation':
                        $this->orientation = $setting['value'];
                        break;
                    case 'Position':
                        $this->position = $setting['value'];
                        break;
                    case 'Appearance':
                        $this->appearance = $setting['value'];
                        break;
                }
            }
        }
        else
        {
            foreach ($configs as $config)
            {
                switch ($config['param'])
                {
                    case 'Orientation':
                        $this->orientation = $config['default_value'];
                        break;
                    case 'Position':
                        $this->position = $config['default_value'];
                        break;
                    case 'Appearance':
                        $this->appearance = $config['default_value'];
                        break;
                }
            }
        }                
    }
    
    /**
     *
     * Method to show the main status bar page
     * 
     * @access public
     * @return string $string The htlm string to display 
     */
    public function showMain()
    {
        $onlineUsersLabel = $this->objLanguage->languageText('mod_statusbar_onlineusers', 'statusbar', 'ERROR: mod_statusbar_onlineusers');
        $messageLabel = $this->objLanguage->languageText('mod_statusbar_messaging', 'statusbar', 'ERROR: mod_statusbar_messaging');
        $bookmarkLabel = $this->objLanguage->languageText('mod_statusbar_bookmarkpage', 'statusbar', 'ERROR: mod_statusbar_bookmarkpage');
        $gotoLabel = $this->objLanguage->languageText('mod_statusbar_gotobookmarks', 'statusbar', 'ERROR: mod_statusbar_gotobookmarks');
        $settingsLabel = $this->objLanguage->languageText('mod_statusbar_settings', 'statusbar', 'ERROR: mod_statusbar_settings');
        
        $tooltip = $this->newObject('tooltip', 'jquerycore');
        $tooltip->setShowUrl(FALSE);
        $tooltip->setCssId('statusbar_online_users');
        $tooltip->load();

        $this->objIcon->setIcon('users', 'png');
        $this->objIcon->title = ' ';
        $this->objIcon->alt = ' ';
        $onlineIcon = $this->objIcon->show();
        $onlineLink = '<a href="#" id="statusbar_online_users" title="' . $onlineUsersLabel . '"' . '>' . $onlineIcon . '</a>';

        $tooltip = $this->newObject('tooltip', 'jquerycore');
        $tooltip->setShowUrl(FALSE);
        $tooltip->setCssId('statusbar_messaging');
        $tooltip->load();

        $this->objIcon->setIcon('user_comment', 'png');
        $messageIcon = $this->objIcon->show();
        $messageLink = '<a href="#" id="statusbar_messaging" title="' . $messageLabel . '"' . '>' . $messageIcon . '</a>';

        $tooltip = $this->newObject('tooltip', 'jquerycore');
        $tooltip->setShowUrl(FALSE);
        $tooltip->setCssId('statusbar_add_bookmark');
        $tooltip->load();

        $this->objIcon->setIcon('bookmark', 'png');
        $bookmarkIcon = $this->objIcon->show();
        $bookmarkLink = '<a href="#" id="statusbar_add_bookmark" title="' . $bookmarkLabel . '"' . '>' . $bookmarkIcon . '</a>';

        $tooltip = $this->newObject('tooltip', 'jquerycore');
        $tooltip->setShowUrl(FALSE);
        $tooltip->setCssId('statusbar_goto_bookmark');
        $tooltip->load();

        $this->objIcon->setIcon('bookmark_go', 'png');
        $gotoIcon = $this->objIcon->show();
        $gotoLink = '<a href="#" id="statusbar_goto_bookmark" title="' . $gotoLabel . '"' . '>' . $gotoIcon . '</a>';

        $tooltip = $this->newObject('tooltip', 'jquerycore');
        $tooltip->setShowUrl(FALSE);
        $tooltip->setCssId('statusbar_settings');
        $tooltip->load();

        $this->objIcon->setIcon('cog', 'png');
        $settingsIcon = $this->objIcon->show();
        $settingsLink = '<a href="#" id="statusbar_settings" title="' . $settingsLabel . '"' . '>' . $settingsIcon . '</a>';
                
        $objTable = new htmltable();
        $objTable->cellpadding = '4';
        if ($this->orientation == 'Horizontal')
        {
            $objTable->width = '250px';
            $objTable->startRow();
            $objTable->addCell($onlineLink, '', '', 'center', '', '', '');
            $objTable->addCell($messageLink, '', '', 'center', '', '', '');
            $objTable->addCell($bookmarkLink, '', '', 'center', '', '', '');
            $objTable->addCell($gotoLink, '', '', 'center', '', '', '');
            $objTable->addCell($settingsLink, '', '', 'center', '', '', '');
            $objTable->endRow();        
        }
        else
        {
            $objTable->width = '25px';
            $objTable->startRow();
            $objTable->addCell($onlineLink, '', '', 'center', '', '', '');
            $objTable->endRow();        
            $objTable->startRow();
            $objTable->addCell($messageLink, '', '', 'center', '', '', '');
            $objTable->endRow();        
            $objTable->startRow();
            $objTable->addCell($bookmarkLink, '', '', 'center', '', '', '');
            $objTable->endRow();        
            $objTable->startRow();
            $objTable->addCell($gotoLink, '', '', 'center', '', '', '');
            $objTable->endRow();        
            $objTable->startRow();
            $objTable->addCell($settingsLink, '', '', 'center', '', '', '');
            $objTable->endRow();        
        }
        $string = $objTable->show();
        return $string;
    }
    
    /**
     *
     * Method to create the status bar floating dialog
     * 
     * @access public
     * @return string $string The display string for the dialog 
     */
    public function showStatusbar()
    {
        $position = str_replace('/', ' ', strtolower($this->position));
        $dialog = $this->newObject('dialog', 'jquerycore');
        $dialog->setCssId('statusbar');
        $dialog->setContent($this->showMain());
        $dialog->setOpen("jQuery('.ui-dialog-titlebar').hide();jQuery('#statusbar').removeAttr('class');jQuery('#statusbar').addClass('ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix');");
        if ($this->orientation == 'Horizontal')
        {
            $dialog->setMinHeight('35');
        }
        $dialog->setPosition($position);
        $dialog->setResizable(FALSE);
        $dialog->setAutoOpen(TRUE);
        $dialog->setModal(FALSE);
        $dialog->unsetButtons();
        $string = $dialog->show();
        
        return $string;
    }
    
    /**
     * 
     * Method to show the left block
     * 
     * @access public
     * @return string $string The string for the left block
     */
    public function showLeft()
    {
        return $this->showStatusbar();
    }
}
?>