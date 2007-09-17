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
        $this->dbUsers = $this->getObject('dbusers', 'hivaids');
        $this->dbSuggestions = $this->getObject('dbsuggestions', 'hivaids');
        $this->dbLinks = $this->getObject('dblinks', 'hivaids');
        $this->objUser = $this->getObject('user', 'security');
        $this->objConfig = $this->getObject('altconfig', 'config');
        $this->objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
        $this->objDate = $this->getObject('dateandtime', 'utilities');
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objCountries = $this->getObject('languagecode','language');
        $this->objLoginHist = $this->getObject('dbloginhistory','userstats');
        
        $this->objFeatureBox = $this->newObject('featurebox', 'navigation');
        $this->objIcon = $this->newObject('geticon', 'htmlelements');
        $this->loadClass('htmltable', 'htmlelements');
        $this->loadClass('htmlheading', 'htmlelements');
        $this->loadClass('form', 'htmlelements');
        $this->loadClass('textinput', 'htmlelements');
        $this->loadClass('button', 'htmlelements');
        $this->loadClass('label', 'htmlelements');
        $this->loadClass('radio', 'htmlelements');
        $this->loadClass('dropdown', 'htmlelements');
        $this->loadClass('layer', 'htmlelements');
        $this->loadClass('link', 'htmlelements');
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
        $lnUsStats = $this->objLanguage->languageText('mod_hivaids_userstats', 'hivaids');
        $lnSiStats = $this->objLanguage->languageText('mod_sitestats_name', 'sitestats');
        $lnLogger = $this->objLanguage->languageText('mod_logger_name', 'logger');
        $lnSurvey = $this->objLanguage->languageText('mod_survey_name', 'survey');
        $lnPodcast = $this->objLanguage->languageText('mod_podcast_name', 'podcast');
        $lnRepository = $this->objLanguage->languageText('mod_hivaids_videorepository', 'hivaids');
        $lnLinks = $this->objLanguage->languageText('mod_hivaids_linkspage', 'hivaids');
        $lnSuggestions = $this->objLanguage->languageText('phrase_suggestionbox');
        
        // Forum
        $url = $this->uri('', 'forum');
        $name = 'forum';
        $blForum = $this->objIcon->getBlockIcon($url, $name, $lnForum, 'gif', $iconfolder='icons/modules/');
        
        // CMS Admin
        $url = $this->uri('', 'cmsadmin');
        $name = 'cmsadmin';
        $blCms = $this->objIcon->getBlockIcon($url, $name, $lnCMS, 'gif', $iconfolder='icons/modules/');
        
        // User stats
        $url = $this->uri(array('action' => 'userstats'));
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
        
        // Links page
        $url = $this->uri(array('action' => 'managelinks'), 'hivaids');
        $name = 'linksadmin';
        $blLinks = $this->objIcon->getBlockIcon($url, $name, $lnLinks, 'gif', $iconfolder='icons/modules/');

        // Suggestion box page
        $url = $this->uri(array('action' => 'viewsuggestions'), 'hivaids');
        $name = 'suggestionbox';
        $blSuggestions = $this->objIcon->getBlockIcon($url, $name, $lnSuggestions, 'gif', $iconfolder='icons/modules/');
        
        // User profiles
        
        $objTable = new htmltable();
        $objTable->cellpadding = '5';
        
        $objTable->addRow(array($blForum, $blCms, $blSurvey));
        $objTable->addRow(array('&nbsp;'));
        $objTable->addRow(array($blLog, $blUsSt, $blPodcast));
        $objTable->addRow(array('&nbsp;'));
        $objTable->addRow(array($blRepository, $blLinks, $blSuggestions));
        $str = $objTable->show();
        
        $box = $this->objFeatureBox->showContent($head, $str);
        
        $objLayer = new layer();
        $objLayer->str = $box;
        $objLayer->id = 'cpanel';
        
        return $objLayer->show();
    }
    
    /**
    * Method to display the user statistics for the site
    *
    * @access public
    * @return string html
    */
    public function showUserStats()
    {
        $data = $this->dbUsers->getLoginHistory();
        $numStaff = $this->dbUsers->getStaffInfo();
        $numStudent = $this->dbUsers->getStudentInfo();
        $total = $this->objLoginHist->getTotalLogins();
        $unique = $this->objLoginHist->getUniqueLogins();
        
        $headerParams = $this->getJavascriptFile('new_sorttable.js','htmlelements');
        $this->appendArrayVar('headerParams',$headerParams);
        
        $institution = $this->objConfig->getinstitutionShortName();
        $array = array('institution' => $institution);
        
        $head = $this->objLanguage->languageText('mod_hivaids_userstats', 'hivaids');
        $hdTitle = $this->objLanguage->languageText('word_title');
        $hdName = $this->objLanguage->languageText('word_name');
        $hdCountry = $this->objLanguage->languageText('word_country');
        $hdLastOn = $this->objLanguage->languageText('phrase_laston');
        $hdLogins = $this->objLanguage->languageText('word_logins');
        $hdStaffStud = $this->objLanguage->code2Txt('mod_hivaids_atinstitution', 'hivaids', $array);
        $hdCourse = $this->objLanguage->languageText('word_course');
        $hdYear = $this->objLanguage->languageText('phrase_yearofstudy');
        $staff = $this->objLanguage->languageText('word_staff');
        $student = $this->objLanguage->languageText('word_student');
        $no = $this->objLanguage->languageText('word_no');
        $lbTotal = $this->objLanguage->languageText('mod_hivaids_totallogins', 'hivaids');
        $lbUnique = $this->objLanguage->languageText('mod_hivaids_uniqueusers', 'hivaids');
        $lbStaff = $this->objLanguage->code2Txt('mod_hivaids_staffatinstitution', 'hivaids', $array);
        $lbStudents = $this->objLanguage->code2Txt('mod_hivaids_studentsatinstitution', 'hivaids', $array);
        
        $objHead = new htmlheading();
        $objHead->type = 1;
        $objHead->str = $head;
        $str = $objHead->show();
        
        $str .= '<p style="padding-top:10px;"><b>'.$lbTotal.':</b>&nbsp;&nbsp;'.$total;
        $str .= '<br /><b>'.$lbUnique.':</b>&nbsp;&nbsp;'.$unique;
        $str .= '<br /><b>'.$lbStaff.':</b>&nbsp;&nbsp;'.$numStaff;
        $str .= '<br /><b>'.$lbStudents.':</b>&nbsp;&nbsp;'.$numStudent.'</p>';
        
        if(!empty($data)){
    
            $objTable = new htmltable();
            $objTable->cellpadding = '5';
            $objTable->id = 'statstable';
            $objTable->css_class = 'sorttable';
            
            $hdArr = array();
            $hdArr[] = $hdTitle;
            $hdArr[] = $hdName;
            $hdArr[] = $hdCountry;
            $hdArr[] = $hdStaffStud;
            $hdArr[] = $hdCourse;
            $hdArr[] = $hdYear;
            $hdArr[] = $hdLastOn;
            $hdArr[] = $hdLogins;
            
            $objTable->row_attributes = 'name = "row_'.$objTable->id.'"';
            $objTable->addRow($hdArr, 'heading');
            
            foreach($data as $item){
                if(empty($item['userid'])){
                    continue;
                }
                $objTable->row_attributes = 'name = "row_'.$objTable->id.'" onmouseover="this.className=\'tbl_ruler\';" onmouseout="this.className=\'\';"';
                
                $row = array();
                $row[] = $item['title'];
                $row[] = $item['surname'].', '.$item['firstname'];
                $row[] = $this->objCountries->getName($item['country']);
                $row[] = isset($item['staff_student']) ? $$item['staff_student'] : '';
                $row[] = isset($item['course']) ? $item['course'] : '';
                $row[] = isset($item['study_year']) ? $item['study_year'] : '';
                $row[] = $this->objDate->formatDate($item['laston']);
                $row[] = $item['logins'];
                
                $objTable->addRow($row);
            }
            $str .= $objTable->show();
        }       
        
        return $str;
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
        $boldArr = array('bold' => '<b>', 'closebold' => '</b>');
        $lbRegister = $this->objLanguage->code2Txt('mod_hivaids_anonymousregister', 'hivaids', $boldArr);
        $lbAccount = $this->objLanguage->languageText('phrase_accountdetails');
        $lbUser = $this->objLanguage->languageText('phrase_userdetails');
        $lbAdditional = $this->objLanguage->languageText('phrase_additionaldetails');
        $lbUsername = $this->objLanguage->languageText('word_username');
        $lbPassword = $this->objLanguage->languageText('word_password');
        $lbConfirm = $this->objLanguage->languageText('phrase_confirmpassword');
        //$lbFirstname = $this->objLanguage->languageText('phrase_firstname');
        //$lbSurname = $this->objLanguage->languageText('word_surname');
        //$lbTitle = $this->objLanguage->languageText('word_title');
        $lbGender = $this->objLanguage->languageText('word_gender');
        $lbCountry = $this->objLanguage->languageText('word_country');
        $lbLanguage = $this->objLanguage->languageText('phrase_homelanguage');
        $lbSports = $this->objLanguage->languageText('word_sports');
        $lbHobbies = $this->objLanguage->languageText('word_hobbies');
        $lbMale = $this->objLanguage->languageText('word_male');
        $lbFemale = $this->objLanguage->languageText('word_female');
        $lbStaff = $this->objLanguage->languageText('word_staff');
        $lbStudent = $this->objLanguage->languageText('word_student');
        //$lbNeither = $this->objLanguage->languageText('word_neither');
        $lbCourse = $this->objLanguage->languageText('mod_hivaids_whatyoustudying', 'hivaids');
        $lbYearStudy = $this->objLanguage->languageText('mod_hivaids_yearofstudy', 'hivaids');
        $btnComplete = $this->objLanguage->languageText('phrase_completeregistration');
        $lbStudNum = $this->objLanguage->languageText('mod_hivaids_studentstaffnum', 'hivaids');
        
        $errUsername = $this->objLanguage->languageText('mod_hivaids_errornousername', 'hivaids');
        $errPassword = $this->objLanguage->languageText('mod_hivaids_errornopassword', 'hivaids');
        $errConfirm = $this->objLanguage->languageText('mod_hivaids_errornoconfirmpw', 'hivaids');
        //$errFirstname = $this->objLanguage->languageText('mod_hivaids_errornofirstname', 'hivaids');
        //$errSurname = $this->objLanguage->languageText('mod_hivaids_errornosurname', 'hivaids');
        $errNoMatch = $this->objLanguage->languageText('mod_hivaids_errornomatchpw', 'hivaids');
        $errCourse = $this->objLanguage->languageText('mod_hivaids_errornocourse', 'hivaids');
        $errStud = $this->objLanguage->languageText('mod_hivaids_errornostudnum', 'hivaids');
        
        $lbFirst = $this->objLanguage->languageText('phrase_firstyear');
        $lbSecond = $this->objLanguage->languageText('phrase_secondyear');
        $lbThird = $this->objLanguage->languageText('phrase_thirdyear');
        $lbFourth = $this->objLanguage->languageText('phrase_fourthyear');
        $lbHonours = $this->objLanguage->languageText('word_honours');
        $lbMasters = $this->objLanguage->languageText('word_masters');
        $lbPhd = $this->objLanguage->languageText('word_phd');
        
        $useNum = $this->objSysConfig->getValue('USE_STUDENT_NUMBER', 'hivaids');
        
        $institution = $this->objConfig->getinstitutionShortName();
        $array = array('institution' => $institution);
        $lbStaffStud = $this->objLanguage->code2Txt('mod_hivaids_stafforstudentatinst', 'hivaids', $array);
        $lbNeither = $this->objLanguage->code2Txt('mod_hivaids_notatinst', 'hivaids', $array);
        
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
        /*
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
        */
        $objLabel = new label($lbGender.': ', 'input_gender');
        $objRadio = new radio('gender');
        $objRadio->setId('input_gender');
        $objRadio->addOption('M', '&nbsp;'.$lbMale);
        $objRadio->addOption('F', '&nbsp;'.$lbFemale);
        $objRadio->setSelected('M');
        $objRadio->setBreakSpace('&nbsp;&nbsp;&nbsp;');
        
        $objTable->addRow(array('', $objLabel->show(), $objRadio->show()));
        
        $objLabel = new label($lbLanguage.': ', 'input_language');
        
        $objDrop = new dropdown('language');
        foreach($this->objCountries->iso_639_2_tags->codes as $key => $item){
            $objDrop->addOption($item, $item);
        }
        $objDrop->setSelected('English');
        
        $objTable->addRow(array('', $objLabel->show(), $objDrop->show()));
        
        $objLabel = new label($lbCountry.': ', 'input_country');
        $list = $this->objCountries->country();
        
        $objTable->addRow(array('', $objLabel->show(), $list));
        
        // Additional details - staff / student, course, year of study
        $objTable->addRow(array('<b>'.$lbAdditional.'</b>'));
       
        $objLabel = new label($lbStaffStud.': ', 'input_staff_student');
        //$objInput = new textinput('staff_student');
        //$objInput->setId('input_staff_student');
        
        $objRadio = new radio('staff_student');
        $objRadio->addOption('staff', '&nbsp;'.$lbStaff);
        $objRadio->addOption('student', '&nbsp;'.$lbStudent);
        $objRadio->addOption('no', '&nbsp;'.$lbNeither);
        $objRadio->setSelected('student');
        $objRadio->setBreakSpace('&nbsp;&nbsp;&nbsp;&nbsp;');
        
        $objTable->addRow(array('', $objLabel->show(), $objRadio->show()));
        
        if($useNum == 'TRUE'){
            $objLabel = new label($lbStudNum.': ', 'input_number');
            $objInput = new textinput('number');
            $objInput->setId('input_number');
            
            $objTable->addRow(array('', $objLabel->show(), $objInput->show()));
        }
        
        $objLabel = new label($lbCourse.': ', 'input_course');
        $objInput = new textinput('course');
        $objInput->setId('input_course');
        
        $objTable->addRow(array('', $objLabel->show(), $objInput->show()));

        $objLabel = new label($lbYearStudy.': ', 'input_yearstudy');
        //$objInput = new textinput('yearstudy');
        //$objInput->setId('input_yearstudy');
        
        $objDrop = new dropdown('yearstudy');
        $objDrop->addOption('NULL', ' ---- ');
        $objDrop->addOption($lbFirst, $lbFirst);
        $objDrop->addOption($lbSecond, $lbSecond);
        $objDrop->addOption($lbThird, $lbThird);
        $objDrop->addOption($lbFourth, $lbFourth);
        $objDrop->addOption($lbHonours, $lbHonours);
        $objDrop->addOption($lbMasters, $lbMasters);
        $objDrop->addOption($lbPhd, $lbPhd);
        
        $objTable->addRow(array('', $objLabel->show(), $objDrop->show()));

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
        if($useNum == 'TRUE'){
            $objForm->addRule('number', $errStud, 'required');
        }
        $objForm->addRule('course', $errCourse, 'required');
        $str .= $objForm->show();
        
        return $this->objFeatureBox->showContent($hdRegister, $str);
    }
    
    /**
    * Method to display the suggestions
    *
    * @access public
    * @return string html
    */
    public function viewSuggestions()
    {
        $data = $this->dbSuggestions->getSuggestions();
        
        $head = $this->objLanguage->languageText('phrase_suggestionbox');
        $lbNoSuggestions = $this->objLanguage->languageText('mod_hivaids_nosuggestionsinbox', 'hivaids');
        $hdSuggestion = $this->objLanguage->languageText('word_suggestion');
        $hdDate = $this->objLanguage->languageText('phrase_dateposted');
        
        $objHead = new htmlheading();
        $objHead->str = $head;
        $objHead->type = 1;
        $str = $objHead->show();
        
        if(!empty($data)){
            $objTable = new htmltable();
            $objTable->cellspacing = '2';
            $objTable->cellpadding = '5';
            
            $hdArr = array();
            $hdArr[] = $hdSuggestion;
            $hdArr[] = $hdDate;
            
            $objTable->addHeader($hdArr);
            
            $class = 'even';
            foreach($data as $item){
                $class = ($class == 'odd') ? 'even' : 'odd';
                
                $row = array();
                $row[] = $item['suggestion'];
                $row[] = $this->objDate->formatDate($item['updated']);
                
                $objTable->addRow($row, $class);
            }
            $str .= $objTable->show();
        }else{
            $str .= '<p class="noRecordsMessage">'.$lbNoSuggestions.'</p>';
        }
                
        return $str;
    }
    
    /**
    * Method to display a form for adding suggestions
    *
    * @access public
    * @return string html
    */
    public function showSuggestionBox()
    {
        $hdBox = $this->objLanguage->languageText('phrase_suggestionbox');
        $lbSuggestion = $this->objLanguage->languageText('mod_hivaids_submitsuggestions', 'hivaids');
        $btnSubmit = $this->objLanguage->languageText('word_submit');
        
        $str = '<p>'.$lbSuggestion.'</p>';
        
        $objInput = new textarea('suggestion');
        $str .= $objInput->show();
        
        $objButton = new button('save', $btnSubmit);
        $objButton->setToSubmit();
        $str .= '<p>'.$objButton->show().'</p>';
        
        $objForm = new form('box', $this->uri(array('action' => 'savesuggestion')));
        $objForm->addToForm($str);
        
        return $this->objFeatureBox->showContent($hdBox, $objForm->show());
    }
    
    /**
    * Method to manage the links page
    *
    * @access public
    * @return string html
    */
    public function manageLinks()
    {
        $head = $this->objLanguage->languageText('phrase_managelinkspage');
        $lnAdd = $this->objLanguage->languageText('phrase_addlinkspage');
        
        $objHead = new htmlheading();
        $objHead->str = $head;
        $objHead->type = 1;
        $str = $objHead->show();
        
        $str .= $this->showLinks();
        
        $objLink = new link($this->uri(array('action' => 'addlinks')));
        $objLink->link = $lnAdd;
        $str .= $objLink->show();
        return $str;
    }
    
    /**
    * Method to update the links page
    *
    * @access public
    * @return string html
    */
    public function addLinks()
    {
        $data = $this->dbLinks->getPage();
        
        $head = $this->objLanguage->languageText('phrase_addlinkspage');
        $btnSave = $this->objLanguage->languageText('word_save');
        
        $objHead = new htmlheading();
        $objHead->str = $head;
        $objHead->type = 1;
        $str = $objHead->show();
        
        $page = !empty($data) ? $data[0]['linkspage'] : '';
        $objEditor = $this->newObject('htmlarea', 'htmlelements');
        $objEditor->init('linkspage', $page);
        $formStr = $objEditor->show();
        
        $objButton = new button('save', $btnSave);
        $objButton->setToSubmit();
        $formStr .= '<p>'.$objButton->show().'</p>';
        
        $id = !empty($data) ? $data[0]['id'] : '';
        $objInput = new textinput('id', $id, 'hidden');
        $formStr .= $objInput->show();
        
        $objForm = new form('newpage', $this->uri(array('action' => 'savelinks')));
        $objForm->addToForm($formStr);
        $str .= $objForm->show();
        
        return $str;
    }
    
    /**
    * Method to display the links page
    *
    * @access public
    * @return string html
    */
    public function showLinks()
    {
        $data = $this->dbLinks->getPage();
        
        $head = $this->objLanguage->languageText('word_links');
        
        $str = '';
        if(!empty($data)){
            $str = $data[0]['linkspage'];
        }
        
        return $this->objFeatureBox->showContent($hdLinks, $str);
    }
}
?>