<?php
/**
* configure class extends object
* @package etd
* @filesource
*/

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}

/**
* Class to set up the ETD repository.
* The class provides an interface for and institution to enter their information and
* set up the submission / approval process for an etd before it can be archived. It also
* provides an interface to set up permissions, giving the appropriate people access to different
* parts of the process.
*
* @author Megan Watson
* @copyright (c) 2004 UWC
* @version 0.2
* @modified by Megan Watson on 2006 11 05 Ported to 5ive / chisimba
*/

class configure extends object
{
    /**
    * Constructor method
    */
    public function init()
    {
        try{
            $this->dbCopyright = $this->getObject('dbcopyright', 'etd');
            $this->dbDegrees = $this->getObject('dbdegrees', 'etd');
            
            $this->objUser = $this->getObject('user', 'security');
            $this->objLanguage = $this->getObject('language', 'language');
            $this->objConfig = $this->getObject('altconfig', 'config');
            $this->objDbConfig = $this->getObject('dbsysconfig', 'sysconfig');
            
            $this->objFeatureBox = $this->newObject('featurebox', 'navigation');
            $this->objHead = $this->newObject('htmlheading', 'htmlelements');
            $this->objEditor = $this->newObject('htmlarea', 'htmlelements');
            
            $this->loadClass('htmltable', 'htmlelements');
            $this->loadClass('tabbedbox', 'htmlelements');
            $this->loadClass('form', 'htmlelements');
            $this->loadClass('button', 'htmlelements');
            $this->loadClass('textinput', 'htmlelements');
            $this->loadClass('link', 'htmlelements');
        }catch(Exception $e){
            throw customException($e->message());
            exit();
        }
        
        /*
        $this->dbEtdConfig = $this->getObject('dbetdconfig', 'etd');
        $this->dbInstitute = $this->getObject('dbinstituteinfo', 'etd');

        $this->objConfig = $this->getObject('dbsysconfig', 'sysconfig');
        $this->objLangCode = $this->getObject('languagecode', 'language');
        $this->objUsersDb = $this->getObject('usersdb', 'groupadmin');
        $this->objGroups = $this->getObject('groupadminmodel', 'groupadmin');

        $this->objLink = $this->newObject('link', 'htmlelements');
        $this->objLayer = $this->newObject('layer', 'htmlelements');

        $this->objForm = $this->newObject('form', 'htmlelements');
        $this->objLabel = $this->newObject('label', 'htmlelements');
        $this->objInput = $this->newObject('textinput', 'htmlelements');
        $this->objText = $this->newObject('textarea', 'htmlelements');
        $this->objDrop = $this->newObject('dropdown', 'htmlelements');
        $this->objIcon = $this->newObject('geticon', 'htmlelements');
        $this->objRadio = $this->newObject('radio', 'htmlelements');
        $this->objButton = $this->newObject('button', 'htmlelements');
        $this->objSelect = $this->newObject('selectbox','htmlelements');

        $this->btSave = $this->objLanguage->languageText('mod_etd_savecontinue');
        $this->btSave2 = $this->objLanguage->languageText('word_save');
        $this->btExit = $this->objLanguage->languageText('mod_etd_exittoindex');
        */
    }
    
    /**
    * Method to display a form for entering the institution specific information.
    *
    * @access private
    * @return string html
    */
    function getInstitute()
    {
        $lbName = $this->objLanguage->languageText('mod_etd_nameinstitution', 'etd');
        $lbYear = $this->objLanguage->languageText('mod_etd_earliestarchivalyear', 'etd');
        $lbCopyright = $this->objLanguage->languageText('mod_etd_copyrightpublicationcond', 'etd');
        $lbAccept = $this->objLanguage->code2Txt('mod_etd_tobeaccepted', 'etd');
        $lbCodes = $this->objLanguage->languageText('mod_etd_usecodes', 'etd');
        $btnUpdate = $this->objLanguage->languageText('word_update');
        $btnChange = $this->objLanguage->languageText('word_change');
        

        // institute name
        $str = '<p><b>'.$lbName.':</b>&nbsp;&nbsp;'.$this->objConfig->getInstitutionName().'</p>';
        
        // archival year
        $str .= '<p><b>'.$lbYear.':</b>&nbsp;&nbsp;';
        
        $objButton = new button('save', $btnChange);
        $objButton->setToSubmit();
        
        $objDrop = new dropdown('year');
        $curYr = date('Y');
        for($i = 1900; $i <= $curYr; $i = $i + 5){
            $objDrop->addOption($i, $i);
        }
        $objDrop->setSelected($this->objDbConfig->getValue('ARCHIVE_START_YEAR', 'etd'));
        $dropStr = $objDrop->show().'&nbsp;&nbsp;'.$objButton->show();
        
        $objForm = new form('changeyear', $this->uri(array('action' => 'saveconfig', 'mode' => 'saveyear')));
        $objForm->addToForm($dropStr);
        $str .= $objForm->show().'</p>';

        // copyright
        $str .= '<p><b>'.$lbCopyright.'</b><br />('.$lbAccept.')</p>';
        $editorStr = '<p>'.$lbCodes.'</p>';
        
        $lang = '';
        $copy = $this->dbCopyright->getCopyright($lang);
        $copyright = '';
        if(isset($copy['copyright'])){
            $copyright = $copy['copyright'];
        }
        $this->objEditor->init('copyright', $copyright, '10', '50');
        $this->objEditor->width = '400px';
        $this->objEditor->height = '400px';
        $this->objEditor->setBasicToolBar(); 
        $editorStr .= '<p>'.$this->objEditor->showFCKEditor().'</p>';
        
        $objButton = new button('save', $btnUpdate);
        $objButton->setToSubmit();
        $editorStr .= '<p>'.$objButton->show().'</p>';
        
        if(isset($copy['id'])){
            $objInput = new textinput('copyId', $copy['id'], 'hidden');
            $editorStr .= $objInput->show();
        }
        $objInput = new textinput('language', 'en', 'hidden');
        $editorStr .= $objInput->show();
            
        $objForm = new form('updatecopy', $this->uri(array('action' => 'saveconfig', 'mode' => 'savecopyright')));
        $objForm->addToForm($editorStr);
        
        $objTable = new htmltable();
        $objTable->cellspacing = '2';
        $objTable->startRow();
        $objTable->addCell($objForm->show());
        $objTable->addCell('', '3%');
        $objTable->addCell($copyright, '45%');
        $objTable->endRow();
        
        $str .= '<p>'.$objTable->show().'</p>';

/*

        $lang = strtolower($this->objConfig->defaultLanguageAbbrev());
        $this->objLabel = new label($lbLanguage, 'input_language');
        $this->objDrop = new dropdown('language');
        foreach($this->objLangCode->iso_639_2_tags as $key => $item){
            $this->objDrop->addOption($key, $item);
        }
        $this->objDrop->setSelected($lang);
*/
        return $str;
    }
    
    /**
    * Method to display the faculties, degrees and departments for adding / editing / deleting
    *
    * @access private
    * @return string html
    */
    private function getFaculty($mode = NULL, $data = NULL)
    {
        $lbFaculty = $this->objLanguage->languageText('phrase_updatefaculties');
        $lbDepartment = $this->objLanguage->languageText('phrase_updatedepartments');
        $lbDegree = $this->objLanguage->languageText('phrase_updatedegrees');
        $lbSave = $this->objLanguage->languageText('word_save');
        
        // Index - faculty, degree, department
        $indexStr = '<ul>';
        
        $objLink = new link($this->uri(array('action' => 'showconfig', 'mode' => 'updatefaculty')));
        $objLink->link = $lbFaculty;
        $indexStr .= '<li>'.$objLink->show().'</li>';
        
        $objLink = new link($this->uri(array('action' => 'showconfig', 'mode' => 'updatedepartment')));
        $objLink->link = $lbDepartment;
        $indexStr .= '<li>'.$objLink->show().'</li>';
        
        $objLink = new link($this->uri(array('action' => 'showconfig', 'mode' => 'updatedegree')));
        $objLink->link = $lbDegree;
        $indexStr .= '<li>'.$objLink->show().'</li>';
        
        $indexStr .= '</ul>';
        
        // Form - submit box
        $formStr = '';
        if(isset($mode) && !empty($mode)){
            switch($mode){
                case 'faculty':
                    $formHead = $lbFaculty;
                    break;
                case 'department':
                    $formHead = $lbDepartment;
                    break;
                case 'degree':
                    $formHead = $lbDegree;
                    break;
            }
            
            $formStr = '<p><b>'.$formHead.'</b></p>';
            
            $objInput = new textinput('name', '', '', 60);
            $form = '<p>'.$objInput->show().'</p>';
            
            $objButton = new button('save', $lbSave);
            $objButton->setToSubmit();
            $form .= '<p>'.$objButton->show().'</p>';
            
            $objInput = new textinput('type', $mode, 'hidden', 60);
            $hidden = $objInput->show();
            
            $objForm = new form('update', $this->uri(array('action' => 'saveconfig', 'mode' => 'saveupdate', 'nextmode' => 'update'.$mode)));
            $objForm->addToForm($form);
            $objForm->addToForm($hidden);
            $formStr .= $objForm->show();
        }
        
        // List - updated
        $listStr = '';
        if(!empty($data)){
            $listStr = '<ul>';
            
            foreach($data as $item){
                $listStr .= '<li>'.$item['name'].'</li>';
            }
            
            $listStr .= '</ul>';
        }
        
        // Display
        $objTable = new htmltable();
        $objTable->cellspacing = '2';
        $objTable->startRow();
        $objTable->addCell($indexStr);
        $objTable->addCell('', '3%');
        $objTable->addCell($listStr, '45%', '','','', 'rowspan="2"');
        $objTable->endRow();
        
        $objTable->addRow(array($formStr, ''));
        
        return $objTable->show();
    }
    
    /**
    * Method to display the configurable parameters - institution information, copyright, embargoes, approval settings
    *
    * @access private
    * @return string html
    */
    private function showConfig($facultyMode = '', $data = NULL)
    {
        $head = $this->objLanguage->languageText('phrase_configuresystem');
        $lbInstitution = $this->objLanguage->languageText('phrase_institutioninformation');
        $lbFaculty = $this->objLanguage->languageText('phrase_facultyinformation');
        
        $this->objHead->str = $head;
        $this->objHead->type = 1;
        $str = $this->objHead->show();
        
        // Degrees / faculties / departments
        $str .= $this->objFeatureBox->show($lbFaculty, $this->getFaculty($facultyMode, $data));

        // Institution information
        $str .= $this->objFeatureBox->show($lbInstitution, $this->getInstitute());
        
        return $str;
    }

    /**
    * Entry portal into the class
    *
    * @access public
    * @param string $mode The action to be taken
    * @return string html
    */
    public function show($mode)
    {
        switch($mode){
            case 'permissions':
                break;
                
            case 'saveyear':
                $pvalue = $this->getParam('year');
                $this->objDbConfig->changeParam('ARCHIVE_START_YEAR', 'etd', $pvalue);
                return TRUE;
                break;
                
            case 'savecopyright':
                $copyId = $this->getParam('copyId');
                $this->dbCopyright->addCopyright($this->objUser->userId(), $copyId);
                return TRUE;
                break;
                
            case 'updatefaculty':
                $data = $this->dbDegrees->getList('faculty');
                return $this->showConfig('faculty', $data);
                
            case 'updatedepartment':
                $data = $this->dbDegrees->getList('department');
                return $this->showConfig('department', $data);
                
            case 'updatedegree':
                $data = $this->dbDegrees->getList('degree');
                return $this->showConfig('degree', $data);
                
            case 'saveupdate':
                $name = $this->getParam('name');
                $type = $this->getParam('type');
                $id = $this->getParam('id');
                $this->dbDegrees->addItem($name, $type, $id);
                return TRUE;
                break;
                
            default:
                return $this->showConfig('');
        }
    }
    
    
    
/* *** Methods below have not been ported *** */

    /**
    * Method to display an index to the configuration.
    */
    function index()
    {
        $hdIndex = $this->objLanguage->languageText('mod_etd_index');
        $lbInstitution = $this->objLanguage->languageText('mod_etd_enterinstitutioninfo');
        $lbProcess = $this->objLanguage->languageText('mod_etd_confsubmissionprocess');
        $lbPerms = $this->objLanguage->languageText('mod_etd_assignuserperms');
        $lbExit = $this->objLanguage->languageText('mod_etd_exitconfig');

        $this->objHead->str = $hdIndex;
        $this->objHead->type = 3;
        $indexStr = $this->objHead->show();

        // list
        $style = 'style="padding:5px;"';
        $indexStr .= "<ul><li $style>";
        $indexStr .= $this->getLink($lbInstitution, 'configinfo', array('step'=>1))."</li><li $style>";
        $indexStr .= $this->getLink($lbProcess, 'configprocess')."</li><li $style>";
        $indexStr .= $this->getLink($lbPerms, 'configperms')."</li><li $style>";
        $indexStr .= $this->getLink($lbExit, '').'</li>';
        $indexStr .= '</ul>';

        $this->output = $indexStr;
    }

    /* *** User Permissions *** */

    /**
    * Method to assign user permissions for the etd
    */
    function configurePerms($group = NULL)
    {
        $head = $this->objLanguage->languageText('mod_etd_assignuserperms');
        $lbAdmin = $this->objLanguage->languageText('mod_etd_etdadministrators');
        $lbManager = $this->objLanguage->languageText('mod_etd_etdmanagers');
        $lbInitial = $this->objLanguage->languageText('mod_etd_etdleveloneapprovers');
        $lbApprovers = $this->objLanguage->languageText('mod_etd_etdapprovers');
        $lbEditors = $this->objLanguage->languageText('mod_etd_etdmetaeditors');

        $this->objHead->str = $head;
        $this->objHead->type = 2;
        $selStr = $this->objHead->show();
        $align = 'center';

        switch($group){
            case 'etdadmin':
                $selStr .= $this->addUsers('etdAdmin', $lbAdmin);
                break;
            case 'etdmanager':
                $selStr .= $this->addUsers('etdManager', $lbManager);
                break;
            case 'etdapprover':
                $selStr .= $this->addUsers('etdApprover', $lbApprovers);
                break;
            case 'etdinitialapprover':
                $selStr .= $this->addUsers('etdInitialApprover', $lbInitial);
                break;
            case 'etdcataloger':
                $selStr .= $this->addUsers('etdCataloger', $lbEditors);
                break;
            default:
                $style = 'style="padding:5px;"';
                $indexStr = "<ul><li $style>";
                $indexStr .= $this->getLink($lbAdmin, 'configperms', array('group'=>'etdadmin'));
                $indexStr .= "</li><li $style>";
                $indexStr .= $this->getLink($lbManager, 'configperms', array('group'=>'etdmanager'));
                $indexStr .= "</li><li $style>";
                $indexStr .= $this->getLink($lbInitial, 'configperms', array('group'=>'etdinitialapprover'));
                $indexStr .= "</li><li $style>";
                $indexStr .= $this->getLink($lbApprovers, 'configperms', array('group'=>'etdapprover'));
                $indexStr .= "</li><li $style>";
                $indexStr .= $this->getLink($lbEditors, 'configperms', array('group'=>'etdcataloger'));
                $indexStr .= "</li><li $style>";
                $indexStr .= $this->getLink($this->btExit, 'configureetd').'</li>';
                $indexStr .= '</ul>';
                $selStr .= $indexStr;
                $align = 'left';
        }

        $this->objLayer->str = $selStr;
        $this->objLayer->cssClass = 'etdbox';
        $this->objLayer->align = $align;

        $this->output = $this->objLayer->show();
    }

    /**
    * Method to display the users and group users for assigning permissions.
    */
    function addUsers($group, $hdGroup)
    {
        $arrGroup = array('groupname'=>$group);
        $hdList = $this->objLanguage->code2Txt('mod_etd_groupusers', $arrGroup);

        $this->objHead->str = $hdGroup;
        $this->objHead->type = 4;
        $selStr = $this->objHead->show();

        $this->objForm = new form('select', $this->uri(array('action'=>'saveconfperms')));
        $this->getSelectBox($hdList, $group);

        return $selStr.$this->objForm->show();
    }

    /**
    * Method to save the user permissions
    */
    function savePerms()
    {
        $groupId = $this->getParam('groupId');
        $list = $this->getParam('rightList', array());

        // Get the original member ids
        $fields = array ( 'tbl_users.id' );
        $memberList = $this->objGroups->getGroupUsers($groupId, $fields);
        $oldList = $this->objGroups->getField($memberList, 'id');

        // Get the added member ids
        $addList = array_diff( $list, $oldList );

        // Get the deleted member ids
        $delList = array_diff( $oldList, $list );

        // Add these members
        foreach( $addList as $userId ) {
            $this->objGroups->addGroupUser( $groupId, $userId );
        }
        // Delete these members
        foreach( $delList as $userId ) {
            $this->objGroups->deleteGroupUser( $groupId, $userId );
        }
    }

    /* *** Process Configuration *** */

    /**
    * Method to display a form to configure the approval process for a submitted ETD
    */
    function configureApproval()
    {
        $head = $this->objLanguage->languageText('mod_etd_etdapprovalprocess');
        $hdLevel1 = $this->objLanguage->languageText('mod_etd_levelone');
        $hdLevel2 = $this->objLanguage->languageText('mod_etd_leveltwo');
        $lbLevel1 = $this->objLanguage->languageText('mod_etd_initialappr');
        $lbLevel2 = $this->objLanguage->languageText('mod_etd_secondappr');
        $lbHead = $this->objLanguage->languageText('mod_etd_twolevels');
        $lbGrant = $this->objLanguage->languageText('mod_etd_grantrefuseembargo');
        $lbRequest = $this->objLanguage->languageText('mod_etd_requestembargo');
        $lbFinal = $this->objLanguage->languageText('mod_etd_finalapprovalbypassltwo');
        $lbChanges = $this->objLanguage->languageText('mod_etd_finalapprovalofchanges');
        $hdPeriod = $this->objLanguage->languageText('mod_etd_periodofembargo');
        $lbPeriod = $this->objLanguage->languageText('mod_etd_allowedembargoperiods');
        $lbShort = $this->objLanguage->languageText('mod_etd_shortestperiodallowed');
        $lbLong = $this->objLanguage->languageText('mod_etd_longestallowedperiod');
        $lbIncrement = $this->objLanguage->languageText('mod_etd_incrementbetweenperiods');

        $data = $this->dbEtdConfig->getConfig();
        if(isset($data) && !empty($data)){
            $configId = $data['id'];
            $grantEm = $data['grantEmbargo'];
            $reqEm = $data['requestEmbargo'];
            $finApp = $data['finalApproval'];
            $finAppBypass = $data['finalApprovalBypass'];
            $grantEm2 = $data['grantEmbargoL2'];
            $short = $data['shortPeriod'];
            $long = $data['longPeriod'];
            $inc = $data['incPeriod'];
        }else{
            $grantEm = 'no';
            $reqEm = 'yes';
            $finApp = 'yes';
            $finAppBypass = 'no';
            $grantEm2 = 'yes';
            $short = 3;
            $long = 12;
            $inc = 3;
        }

        $this->objHead->str = $head;
        $this->objHead->type = 2;
        $appStr = $this->objHead->show();
        $appStr .= '<p><b><i>'.$lbHead.'</i></b></p>';

        $this->objTable->init();
        $this->objTable->cellpadding = 4;

        $this->objHead->str = $hdLevel1;
        $this->objHead->type = 4;

        $this->objTable->startRow();
        $this->objTable->addCell('', '5%');
        $this->objTable->addCell($this->objHead->show(), '','','','', 'colspan=2');
        $this->objTable->addCell('', '10%');
        $this->objTable->endRow();

        $this->objTable->startRow();
        $this->objTable->addCell('');
        $this->objTable->addCell('<i>'.$lbLevel1.'</i>', '','','','', 'colspan=2');
        $this->objTable->endRow();

        $this->objTable->addRow(array('&nbsp;'));

        $this->objTable->addRow(array('', $lbGrant.': ', $this->getRadio('grant1', $grantEm)));
        $this->objTable->addRow(array('', $lbRequest.': ', $this->getRadio('request1', $reqEm)));
        $this->objTable->addRow(array('', $lbChanges.': ', $this->getRadio('changes1', $finApp)));
        $this->objTable->addRow(array('', $lbFinal.': ', $this->getRadio('final1', $finAppBypass)));

        $this->objHead->str = $hdLevel2;
        $this->objHead->type = 4;

        $this->objTable->startRow();
        $this->objTable->addCell('');
        $this->objTable->addCell($this->objHead->show(), '','','','', 'colspan=2');
        $this->objTable->endRow();

        $this->objTable->startRow();
        $this->objTable->addCell('');
        $this->objTable->addCell('<i>'.$lbLevel2.'</i>', '','','','', 'colspan=2');
        $this->objTable->endRow();

        $this->objTable->addRow(array('', '&nbsp;'));

        $this->objTable->addRow(array('', $lbGrant.': ', $this->getRadio('grant2', $grantEm2)));

        $this->objHead->str = $hdPeriod;
        $this->objHead->type = 4;

        $this->objTable->startRow();
        $this->objTable->addCell('');
        $this->objTable->addCell($this->objHead->show(), '','','','', 'colspan=2');
        $this->objTable->endRow();

        $this->objTable->startRow();
        $this->objTable->addCell('');
        $this->objTable->addCell('<i>'.$lbPeriod.'</i>', '','','','', 'colspan=2');
        $this->objTable->endRow();

        $this->objTable->addRow(array('&nbsp;'));

        $this->objTable->addRow(array('', $lbShort.': ', $this->getDropdown('short', $short)));
        $this->objTable->addRow(array('', $lbLong.': ', $this->getDropdown('long', $long)));
        $this->objTable->addRow(array('', $lbIncrement.': ', $this->getDropdown('increment', $inc)));

        $this->objTable->addRow(array('', '&nbsp;'));

        // buttons
        $this->objButton = new button('save', $this->btSave2);
        $this->objButton->setToSubmit();
        $btnStr = $this->objButton->show();

        $this->objButton = new button('exit', $this->btExit);
        $this->objButton->setToSubmit();
        $btnStr .= '&nbsp;&nbsp;&nbsp;'.$this->objButton->show();

        // hidden
        if(isset($configId) && !empty($configId)){
            $this->objInput = new textinput('id', $configId);
            $this->objInput->fldType = 'hidden';
            $btnStr .= $this->objInput->show();
        }

        $this->objTable->startRow();
        $this->objTable->addCell('');
        $this->objTable->addCell($btnStr, '','','center');
        $this->objTable->endRow();

        $this->objForm = new form('confapprove', $this->uri(array('action'=>'saveprocess')));
        $this->objForm->addToForm($this->objTable->show());

        $appStr .= $this->objForm->show();

        $this->objLayer->str = $appStr;
        $this->objLayer->cssClass = 'etdbox';
        $this->objLayer->align = 'left';

        $this->output = $this->objLayer->show();
    }

    /**
    * Method to save the approval process.
    */
    function saveProcess()
    {
        $id = $this->getParam('id', NULL);
        $this->dbEtdConfig->addConfig($id);
    }

    /* *** Institutional Specific Information *** */

    /**
    * Method to configure the institution specific etd information.
    */
    function configureInfo($step, $extra = NULL)
    {
        switch($step){
            case 1:
                $str = $this->getInstitute($extra);
                break;

            case 2:
                $str = $this->getDegrees($step, 'degree');
                break;

            case 3:
                $str = $this->showDegrees($step, 'degree');
                break;

            case 4:
                $str = $this->getDegrees($step, 'department');
                break;

            case 5:
                $str = $this->showDegrees($step, 'department');
                break;

            default:
                return FALSE;
        }

        $this->objLayer->str = $str;
        $this->objLayer->cssClass = 'etdbox';
        $this->objLayer->align = 'center';

        $this->output = $this->objLayer->show();
        return TRUE;
    }

    /**
    * Method to save the institutions information.
    */
    function saveConfigInfo($saveaction)
    {
        switch($saveaction){
            case 'saveinstitute':
                $pvalue = $this->getParam('year', date('Y'));
                $this->objConfig->changeParam('ARCHIVE_START_YEAR', 'etd', $pvalue);
                $copyId = $this->getParam('copyId', NULL);
                $this->dbCopyright->addCopyright($this->objUser->userId(), $copyId);
                break;

            case 'savedegree':
                $degType = $this->getParam('degtype', array());
                $arrDegree = $this->getParam('degree', array());
                if(isset($arrDegree) && !empty($arrDegree)){
                    foreach($arrDegree as $item){
                        if(!empty($item)){
                            $this->dbInstitute->addInfo($item, $degType);
                        }
                    }
                }
                break;

            default:
                return FALSE;
        }
    }

    /**
    * Method to display the degrees with the option to add more.
    */
    function showDegrees($step, $type = 'degree')
    {
        if($type == 'degree'){
            $hdType = $this->objLanguage->languageText('mod_etd_degreesoffered');
            $lbType = $this->objLanguage->languageText('mod_etd_degree');
        }else{
            $hdType = $this->objLanguage->languageText('mod_etd_departments');
            $lbType = $this->objLanguage->languageText('mod_etd_department');
        }
        $icAdd = $this->objIcon->getAddIcon($this->uri(array('action'=>'configinfo', 'step'=>$step-1)));

        $this->objHead->str = $hdType.'&nbsp;&nbsp;'.$icAdd;
        $this->objHead->type = 3;
        $degreeStr = $this->objHead->show();

        $this->objTable->init();
        $this->objTable->width = '60%';
        $this->objTable->cellpadding = 4;
        $this->objTable->cellspacing = 2;

        $hdArr = array();
        $hdArr[] = $lbType;
        $hdArr[] = '';

        $this->objTable->addHeader($hdArr, 'heading');

        $data = $this->dbInstitute->getInfo($type);
        if(isset($data) && !empty($data)){
            $i = 0;
            foreach($data as $item){
                $class = (($i++ % 2) == 0) ? 'odd':'even';

                $icons = $this->objIcon->getDeleteIconWithConfirm('', array('action'=>'deleteconfig', 'id'=>$item['id'], 'step'=>$step), 'etd');

                $row = array();
                $row[] = $item['name'];
                $row[] = $icons;

                $this->objTable->addRow($row, $class);
            }
        }
        $degreeStr .= $this->objTable->show();

        // buttons
        $this->objButton = new button('save', $this->btSave);
        $this->objButton->setToSubmit();
        $formStr = '<br><p>'.$this->objButton->show();

        $this->objButton = new button('exit', $this->btExit);
        $this->objButton->setToSubmit();
        $formStr .= '&nbsp;&nbsp;&nbsp;'.$this->objButton->show().'</p>';

        // hidden
        $this->objInput = new textinput('step', $step+1);
        $this->objInput->fldType = 'hidden';
        $formStr .= $this->objInput->show();

        // form
        $this->objForm = new form('confinfo', $this->uri(array('action'=>'configinfo')));
        $this->objForm->addToForm($formStr);

        return $degreeStr.$this->objForm->show();
    }

    /**
    * Method to input a set of degree names
    */
    function getDegrees($step, $type, $saveaction = 'savedegree')
    {
        if($type == 'degree'){
            $hdType = $this->objLanguage->languageText('mod_etd_degreesoffered');
        }else{
            $hdType = $this->objLanguage->languageText('mod_etd_departments');
        }
        $this->objHead->str = $hdType;
        $this->objHead->type = 3;
        $degStr = $this->objHead->show();

        for($i = 0; $i < 10; $i++){
            $this->objInput = new textinput('degree[]');
            $this->objInput->size = 50;
            $degStr .= '<p>'.$this->objInput->show().'</p>';
        }

        // buttons
        $this->objButton = new button('save', $this->btSave);
        $this->objButton->setToSubmit();
        $degStr .= '<br><p>'.$this->objButton->show();

        $this->objButton = new button('exit', $this->btExit);
        $this->objButton->setToSubmit();
        $degStr .= '&nbsp;&nbsp;&nbsp;'.$this->objButton->show().'</p>';

        // hidden
        $this->objInput = new textinput('step', $step+1);
        $this->objInput->fldType = 'hidden';
        $degStr .= $this->objInput->show();

        $this->objInput = new textinput('degtype', $type);
        $this->objInput->fldType = 'hidden';
        $degStr .= $this->objInput->show();

        $this->objInput = new textinput('saveaction', $saveaction);
        $this->objInput->fldType = 'hidden';
        $degStr .= $this->objInput->show();

        // form
        $this->objForm = new form('confinfo', $this->uri(array('action'=>'saveconfig')));
        $this->objForm->addToForm($degStr);

        return $this->objForm->show();
    }

    /* *** General functions *** */

    /**
    * Method to display a pair of select boxes.
    */
    function getSelectBox($lbRight, $group)
    {
        $lbUsers = $this->objLanguage->languageText('mod_etd_allusers');

        $this->objSelect->init();
        $this->objSelect->create($this->objForm, 'leftList[]', $lbUsers, 'rightList[]', $lbRight);

        $groupId = $this->objGroups->getLeafId(array($group));
        $grUsers = $this->objGroups->getGroupUsers($groupId);

        $str = ''; $filter = NULL;
        foreach($grUsers as $item){
            $str .= "'{$item['id']}'";
        }
        if(!empty($str)){
            $filter = " WHERE id NOT IN($str) ORDER BY UPPER(firstName)";
        }

        $users = $this->objGroups->getUsers(NULL,  $filter);

        $this->objSelect->insertLeftOptions($users, 'id', 'fullname' );
        $this->objSelect->insertRightOptions($grUsers, 'id', 'fullName');

        $this->objForm->addToForm($this->objSelect->show());

        $arrFormButtons = $this->objSelect->getFormButtons();
        $this->objForm->addToForm( implode( ' / ', $arrFormButtons ) );

        $this->objInput = new textinput('groupId', $groupId);
        $this->objInput->fldType = 'hidden';
        $this->objForm->addToForm($this->objInput->show());
    }

    /**
    * Method to display radio buttons for a yes/no selection
    */
    function getRadio($name = 'select', $selected = 'no')
    {
        $lbYes = $this->objLanguage->languageText('word_yes');
        $lbNo = $this->objLanguage->languageText('word_no');

        $this->objRadio = new radio($name);
        $this->objRadio->addOption('yes', $lbYes);
        $this->objRadio->addOption('no', $lbNo);

        $this->objRadio->setSelected($selected);

        return $this->objRadio->show();
    }

    /**
    * Method to create a dropdown list of months.
    */
    function getDropdown($name = 'drop', $selected = 3)
    {
        $lbMonth = ' '.$this->objLanguage->languageText('mod_etd_months');
        $this->objDrop = new dropdown($name);
        for($i = 1; $i <= 24; $i++){
            $this->objDrop->addOption($i, $i.$lbMonth);
        }
        $this->objDrop->setSelected($selected);
        return $this->objDrop->show();
    }

    /**
    * Method to display a link.
    * @param string $link The link text.
    * @param string $action The link action to take.
    */
    function getLink($link, $action, $params=NULL)
    {
        $array = array();
        $array['action'] = $action;
        if(isset($params) && !empty($params)){
            foreach($params as $key=>$val){
                $array[$key] = $val;
            }
        }

        $this->objLink = new link($this->uri($array));
        $this->objLink->link = $link;
        return $this->objLink->show();
    }
}
?>