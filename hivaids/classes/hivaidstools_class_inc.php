<?php
/**
* hivaidstools class extends object
* @package hivaidsforum
* @filesource
*/

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}

/**
* hivaidstools class
* @author Megan Watson
* @copyright (c) 2007 UWC
* @version 0.1
*/

class hivaidstools extends object
{
    /**
    * Constructor method
    */
    public function init()
    {        
        $this->objUser = $this->getObject('user', 'security');
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objCountries = $this->getObject('languagecode','language');
        
        $this->objFeatureBox = $this->newObject('featurebox', 'navigation');
        $this->objIcon = $this->newObject('geticon', 'htmlelements');
        $this->loadClass('htmltable', 'htmlelements');
        $this->loadClass('form', 'htmlelements');
        $this->loadClass('textinput', 'htmlelements');
        $this->loadClass('button', 'htmlelements');
        $this->loadClass('label', 'htmlelements');
        $this->loadClass('radio', 'htmlelements');
        $this->loadClass('dropdown', 'htmlelements');
        $this->loadClass('layer', 'htmlelements');
    }
    
    /**
    * Method to display the management page - links into the forum admin, logger, etc.
    *
    * @access public
    * @return string html
    */
    public function showManagement()
    {
        $head = ucwords($this->objLanguage->languageText('phrase_sitemanagement'));
        $lnForum = $this->objLanguage->languageText('mod_forum_name', 'forum');
        $lnCMS = $this->objLanguage->languageText('mod_cmsadmin_name', 'cmsadmin');
        $lnUsStats = $this->objLanguage->languageText('mod_userstats_name', 'userstats');
        $lnSiStats = $this->objLanguage->languageText('mod_sitestats_name', 'sitestats');
        $lnLogger = $this->objLanguage->languageText('mod_logger_name', 'logger');
        $lnSurvey = $this->objLanguage->languageText('mod_survey_name', 'survey');
        $lnPodcast = $this->objLanguage->languageText('mod_podcast_name', 'podcast');
        $lnRepository = $this->objLanguage->languageText('mod_hivaids_videorepository', 'hivaids');
        
        // Forum
        $url = $this->uri('', 'forum');
        $name = 'forum';
        $blForum = $this->objIcon->getBlockIcon($url, $name, $lnForum, 'gif', $iconfolder='icons/modules/');
        
        // CMS Admin
        $url = $this->uri('', 'cmsadmin');
        $name = 'cmsadmin';
        $blCms = $this->objIcon->getBlockIcon($url, $name, $lnCMS, 'gif', $iconfolder='icons/modules/');
        
        // User stats
        $url = $this->uri('', 'userstats');
        $name = 'userstats';
        $blUsSt = $this->objIcon->getBlockIcon($url, $name, $lnUsStats, 'gif', $iconfolder='icons/modules/');
        
        // Site stats
        $url = $this->uri('', 'sitestats');
        $name = 'sitestats';
        $blSiStat = '';//$this->objIcon->getBlockIcon($url, $name, $lnSiStats, 'gif', $iconfolder='icons/modules/');
        
        // Logger
        $url = $this->uri('', 'logger');
        $name = 'logger';
        $blLog = $this->objIcon->getBlockIcon($url, $name, $lnLogger, 'gif', $iconfolder='icons/modules/');
        
        // Survey
        $url = $this->uri('', 'survey');
        $name = 'survey';
        $blSurvey = $this->objIcon->getBlockIcon($url, $name, $lnSurvey, 'gif', $iconfolder='icons/modules/');
        
        // Poll
        
        // Video repository
        $url = $this->uri(array('action' => 'repository'));
        $name = 'resourcekit';
        $blRepository = $this->objIcon->getBlockIcon($url, $name, $lnRepository, 'gif', $iconfolder='icons/modules/');
        
        // Podcasting
        $url = $this->uri('', 'podcast');
        $name = 'podcast';
        $blPodcast = $this->objIcon->getBlockIcon($url, $name, $lnPodcast, 'gif', $iconfolder='icons/modules/');
        
        // User profiles
        
        $objTable = new htmltable();
        $objTable->cellpadding = '5';
        
        $objTable->addRow(array($blForum, $blCms, $blSurvey));
        $objTable->addRow(array('&nbsp;'));
        $objTable->addRow(array($blLog, $blUsSt, $blPodcast));
        $objTable->addRow(array('&nbsp;'));
        $objTable->addRow(array($blRepository));
        $str = $objTable->show();
        
        $box = $this->objFeatureBox->showContent($head, $str);
        
        $objLayer = new layer();
        $objLayer->str = $box;
        $objLayer->id = 'cpanel';
        
        return $objLayer->show();
    }
    
    /**
    * Method to display the user registration form
    *
    * @access public
    * @return string html
    */
    public function showRegistration()
    {
        $hdRegister = $this->objLanguage->languageText('mod_hivaids_registerhivaids', 'hivaids');
        $lbRegister = $this->objLanguage->languageText('mod_hivaids_anonymousregister', 'hivaids');
        $lbAccount = $this->objLanguage->languageText('phrase_accountdetails');
        $lbUser = $this->objLanguage->languageText('phrase_userdetails');
        $lbAdditional = $this->objLanguage->languageText('phrase_additionaldetails');
        $lbUsername = $this->objLanguage->languageText('word_username');
        $lbPassword = $this->objLanguage->languageText('word_password');
        $lbConfirm = $this->objLanguage->languageText('phrase_confirmpassword');
        $lbFirstname = $this->objLanguage->languageText('phrase_firstname');
        $lbSurname = $this->objLanguage->languageText('word_surname');
        $lbTitle = $this->objLanguage->languageText('word_title');
        $lbGender = $this->objLanguage->languageText('word_gender');
        $lbCountry = $this->objLanguage->languageText('word_country');
        $lbSports = $this->objLanguage->languageText('word_sports');
        $lbHobbies = $this->objLanguage->languageText('word_hobbies');
        $lbMale = $this->objLanguage->languageText('word_male');
        $lbFemale = $this->objLanguage->languageText('word_female');
        $btnComplete = $this->objLanguage->languageText('phrase_completeregistration');
        
        $errUsername = $this->objLanguage->languageText('mod_hivaids_errornousername', 'hivaids');
        $errPassword = $this->objLanguage->languageText('mod_hivaids_errornopassword', 'hivaids');
        $errConfirm = $this->objLanguage->languageText('mod_hivaids_errornoconfirmpw', 'hivaids');
        $errFirstname = $this->objLanguage->languageText('mod_hivaids_errornofirstname', 'hivaids');
        $errSurname = $this->objLanguage->languageText('mod_hivaids_errornosurname', 'hivaids');
        $errNoMatch = $this->objLanguage->languageText('mod_hivaids_errornomatchpw', 'hivaids');
        
        $str = '<p>'.$lbRegister.'</p><br />';
        
        $objTable = new htmltable();
        $objTable->cellpadding = '5';
        $objTable->cellspacing = '2';
        
        // Account details - username, password, confirm password
        $objTable->addRow(array('<b>'.$lbAccount.'</b>'));
        
        $objLabel = new label($lbUsername.': ', 'input_username');
        $objInput = new textinput('username');
        $objInput->setId('input_username');
        
        $objTable->addRow(array('', $objLabel->show(), $objInput->show()));
        
        $objLabel = new label($lbPassword.': ', 'input_password');
        $objInput = new textinput('password', '', 'password');
        $objInput->setId('input_password');
        
        $objTable->addRow(array('', $objLabel->show(), $objInput->show()));
        
        $objLabel = new label($lbConfirm.': ', 'input_confirmpassword');
        $objInput = new textinput('confirmpassword', '', 'password');
        $objInput->setId('input_confirmpassword');
        
        $objTable->addRow(array('', $objLabel->show(), $objInput->show()));
        
        // User details - first name, surname, title, gender, country
        $objTable->addRow(array('<b>'.$lbUser.'</b>'));
        
        $objLabel = new label($lbTitle.': ', 'input_title');
        $objDrop = new dropdown('title');
        $objDrop->setId('input_title');
        
        $titles = array('title_mr', 'title_miss', 'title_mrs', 'title_ms', 'title_dr', 'title_prof', 'title_rev', 'title_assocprof');
        foreach ($titles as $title)
        {
            $_title = trim($this->objLanguage->languageText($title));
            $objDrop->addOption($_title,$_title);
        }

        $objTable->addRow(array('', $objLabel->show(), $objDrop->show()));

        $objLabel = new label($lbFirstname.': ', 'input_firstname');
        $objInput = new textinput('firstname');
        $objInput->setId('input_firstname');
        
        $objTable->addRow(array('', $objLabel->show(), $objInput->show()));

        $objLabel = new label($lbSurname.': ', 'input_surname');
        $objInput = new textinput('surname');
        $objInput->setId('input_surname');
        
        $objTable->addRow(array('', $objLabel->show(), $objInput->show()));
        
        $objLabel = new label($lbGender.': ', 'input_gender');
        $objRadio = new radio('gender');
        $objRadio->setId('input_gender');
        $objRadio->addOption('M', '&nbsp;'.$lbMale);
        $objRadio->addOption('F', '&nbsp;'.$lbFemale);
        $objRadio->setSelected('M');
        $objRadio->setBreakSpace('&nbsp;&nbsp;&nbsp;');
        
        $objTable->addRow(array('', $objLabel->show(), $objRadio->show()));
        
        $objLabel = new label($lbCountry.': ', 'input_country');
        $list = $this->objCountries->country();
        
        $objTable->addRow(array('', $objLabel->show(), $list));
        
        // Additional details - sports, hobbies
        $objTable->addRow(array('<b>'.$lbAdditional.'</b>'));
        
        $objLabel = new label($lbSports.': ', 'input_sports');
        $objInput = new textinput('sports');
        $objInput->setId('input_sports');
        
        $objTable->addRow(array('', $objLabel->show(), $objInput->show()));

        $objLabel = new label($lbHobbies.': ', 'input_hobbies');
        $objInput = new textinput('hobbies');
        $objInput->setId('input_hobbies');
        
        $objTable->addRow(array('', $objLabel->show(), $objInput->show()));

        $objButton = new button('save', $btnComplete);
        $objButton->setToSubmit();
        $objTable->addRow(array('&nbsp;'));
        $objTable->addRow(array('', '', $objButton->show()));

        $objForm = new form('registration', $this->uri(array('action' => 'register')));
        $objForm->addToForm($objTable->show());
        $objForm->addRule('username', $errUsername, 'required');
        $objForm->addRule('password', $errPassword, 'required');
        $objForm->addRule('confirmpassword', $errConfirm, 'required');
        $objForm->addRule(array('password', 'confirmpassword'), $errNoMatch, 'compare');
        $objForm->addRule('firstname', $errFirstname, 'required');
        $objForm->addRule('surname', $errSurname, 'required');
        $str .= $objForm->show();
        
        return $this->objFeatureBox->showContent($hdRegister, $str);
    }
}
?>