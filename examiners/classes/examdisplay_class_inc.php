<?php
/* ----------- examdisplay class extends object ----------*/

// security check - must be included in all scripts
if(!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}

/**
* Display class for examiners module
* @author Kevin Cyster
*/

class examdisplay extends object
{
    /**
    * @var object $objLanguage: The language class of the language module
    * @access private
    */
    private $objLanguage;
     
    /**
    * @var object $objExamDb: The dbexams class in the examiners module
    * @access public
    */
    public $objExamDb;

    /**
    * @var object $objIcon: The geticon class in the htmlelements module
    * @access public
    */
    public $objIcon;

    /**
    * @var object $objDate: The dateandtime class in the utilities module
    * @access public
    */
    public $objDate;

    /**
    * @var object $objEditor: The htmlarea class in the htmlelements module
    * @access public
    */
    public $objEditor;

    /**
    * @var string $isLoggedIn: The login status of the user
    * @access public
    */
    public $isLoggedIn;

    /**
    * @var bool $isExamAdmin: TRUE if the user is in the Examiners Admin group FALSE if not
    * @access public
    */
    public $isExamAdmin;

    /**
    * Method to construct the class
    *
    * @access public
    * @return
    */
    public function init()
    {   
        // load html element classes
        $this->loadClass('htmlheading', 'htmlelements');
        $this->loadClass('htmltable', 'htmlelements');
        $this->loadClass('link', 'htmlelements');
        $this->loadClass('textinput', 'htmlelements');
        $this->loadClass('textarea', 'htmlelements');
        $this->loadClass('dropdown', 'htmlelements');
        $this->loadClass('button', 'htmlelements');
        $this->loadClass('form', 'htmlelements');
        $this->loadClass('layer', 'htmlelements');
		
        // system classes
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objExamDb = $this->getObject('dbexams', 'examiners');
        $this->objIcon = $this->newObject('geticon', 'htmlelements');
        $this->objEditor = $this->newObject('htmlarea', 'htmlelements');
        $this->objDate = $this->getObject('dateandtime', 'utilities');
        $this->objUser = $this->getObject('user', 'security');

        $objUser = $this->getObject('user', 'security');
        $userId = $objUser->userId();
        $pkId = $objUser->PKId($userId);
        $this->isLoggedIn = $objUser->isLoggedIn();

        $objGroup = $this->getObject('groupadminmodel', 'groupadmin');
        $groupId = $objGroup->getId('Exam Admin');
        $this->isBookAdmin = $objGroup->isGroupMember($pkId, $groupId);

        $objConfig = $this->getObject('altconfig', 'config');     
        $css = '<link id="calender_css" type="text/css" rel="stylesheet" href="'.$objConfig->getModuleURI().$this->getParam('module').'/resources/examiners.css" />';
        $this->appendArrayVar('headerParams', $css);
    }
    
	/**
	* Method to display the home page for the examiners module
	*
	* @access public
	* @return string $str: The output string
	*/
	public function showHome()
	{            
    }

	/**
	* Method to display the add/edit examiner page
	*
	* @access public
	* @param string $depId: The id of the department
	* @param string $userId: The user id of the examiner
	* @return string $str: The output string
	*/
	public function showAddEditExaminer($depId, $userId)
	{
        // get data
        $department = $this->objExamDb->getDepartmentById($depId);
        $user = $this->objExamDb->getUserById($userId);
        if($user == FALSE){
            $id = '';
            $title = '';
            $name = '';
            $surname = '';
            $organisation = '';
            $email = '';
            $tel = '';
            $ext = '';
            $cell = '';
            $address = '';
        }else{
            $id = $user['id'];
            $title = $user['title'];
            $name = $user['first_name'];
            $surname = $user['surname'];
            $organisation = $user['organisation'];
            $email = $user['email_address'];
            $tel = $user['tel_no'];
            $ext = $user['extension'];
            $cell = $user['cell_no'];
            $address = $user['address'];
        }
        
        // set up text elements
        $lblSelect = $this->objLanguage->languageText('word_select');
        $lblSave = $this->objLanguage->languageText('word_save');
        $lblCancel = $this->objLanguage->languageText('word_cancel');        
        $lblMr = $this->objLanguage->languageText('word_mr');        
        $lblMiss = $this->objLanguage->languageText('word_miss');        
        $lblMrs = $this->objLanguage->languageText('word_mrs');        
        $lblMs = $this->objLanguage->languageText('word_ms');        
        $lblDr = $this->objLanguage->languageText('word_dr');        
        $lblProf = $this->objLanguage->languageText('word_prof');        
        $lblRev = $this->objLanguage->languageText('word_rev');        
        $lblHon = $this->objLanguage->languageText('word_hon');        
        $lblTitle = $this->objLanguage->languageText('word_title');        
        $lblName = $this->objLanguage->languageText('word_name');        
        $lblSurname = $this->objLanguage->languageText('word_surname');        
        $lblOrg = $this->objLanguage->languageText('word_organisation');        
        $lblAddress = $this->objLanguage->languageText('word_address');        
        $lblEmail = $this->objLanguage->languageText('phrase_emailaddress');        
        $lblTel = $this->objLanguage->languageText('phrase_telephonenumber');        
        $lblCell = $this->objLanguage->languageText('phrase_mobilenumber');        
        $lblExt = $this->objLanguage->languageText('word_extension');
        $lblAdd = $this->objLanguage->languageText('mod_examiners_addexaminer', 'examiners');        
        $lblEdit = $this->objLanguage->languageText('mod_examiners_editexaminer', 'examiners');        
        $lblNameRequired = $this->objLanguage->languageText('mod_examiners_requiredexaminername', 'examiners');        
        $lblSurnameRequired = $this->objLanguage->languageText('mod_examiners_requiredsurname', 'examiners');        
        $lblEmailRequired = $this->objLanguage->languageText('mod_examiners_requiredemail', 'examiners');
        $lblReturn = $this->objLanguage->languageText('mod_examiners_returnexaminers', 'examiners');
        
        // set up page heading
        $lblHeading = $user ? $lblEdit : $lblAdd;
        $this->objHeading = new htmlHeading();
        $this->objHeading->str = $lblHeading;
        $this->objHeading->type = 1;
        $heading = $this->objHeading->show();
                
        $this->objHeading = new htmlHeading();
        $this->objHeading->str = $department['department_name'];
        $this->objHeading->type = 3;
        $heading .= $this->objHeading->show();

        // set up htmlelements
        $this->objDrop = new dropdown('title');
        $this->objDrop->addOption('', $lblSelect.'&#160;');
        $this->objDrop->addOption($lblMr, $lblMr.'&#160;');
        $this->objDrop->addOption($lblMrs, $lblMrs.'&#160;');
        $this->objDrop->addOption($lblMiss, $lblMiss.'&#160;');
        $this->objDrop->addOption($lblMs, $lblMs.'&#160;');
        $this->objDrop->addOption($lblDr, $lblDr.'&#160;');
        $this->objDrop->addOption($lblProf, $lblProf.'&#160;');
        $this->objDrop->addOption($lblRev, $lblRev.'&#160;');
        $this->objDrop->addOption($lblHon, $lblHon.'&#160;');
        $this->objDrop->setSelected($title);
        $drpTitle = $this->objDrop->show();
        
        $this->objInput = new textinput('name', $name, 'text', '66');
        $inpName = $this->objInput->show();

        $this->objInput = new textinput('surname', $surname, 'text', '66');
        $inpSurname = $this->objInput->show();

        $this->objInput = new textinput('org', $organisation, 'text', '66');
        $inpOrg = $this->objInput->show();

        $this->objInput = new textinput('email', $email, 'text', '40');
        $inpEmail = $this->objInput->show();

        $this->objInput = new textinput('tel', $tel);
        $inpTel = $this->objInput->show();

        $this->objInput = new textinput('ext', $ext);
        $inpExt = $this->objInput->show();

        $this->objInput = new textinput('cell', $cell);
        $inpCell = $this->objInput->show();

        $this->objText = new textarea('address', $address, '5', '50');
        $txtAddress = $this->objText->show();
        
        $this->objButton=new button('submit',$lblSave);
        $this->objButton->setToSubmit();
        $btnSubmit = $this->objButton->show();

        $this->objButton=new button('cancel',$lblCancel);
        $this->objButton->extra = 'onclick="$(\'form_frmCancel\').submit();"';
        $btnCancel = $this->objButton->show();
        
        // set up display table
        $this->objTable = new htmltable();
        $this->objTable->cellspacing = '2';
        $this->objTable->cellpading = '5';        
        $this->objTable->startRow();
        $this->objTable->addCell('<b>'.$lblTitle.'&#160;&#58;</b>', '', '', '', '', '');
        $this->objTable->addCell($drpTitle, '', '', '', '', '');
        $this->objTable->endRow();
        $this->objTable->startRow();
        $this->objTable->addCell('<b>'.$lblName.'&#160;&#58;</b>', '', '', '', '', '');
        $this->objTable->addCell($inpName, '', '', '', '', '');
        $this->objTable->endRow();
        $this->objTable->startRow();
        $this->objTable->addCell('<b>'.$lblSurname.'&#160;&#58;</b>', '', '', '', '', '');
        $this->objTable->addCell($inpSurname, '', '', '', '', '');
        $this->objTable->endRow();
        $this->objTable->startRow();
        $this->objTable->addCell('<b>'.$lblOrg.'&#160;&#58;</b>', '', '', '', '', '');
        $this->objTable->addCell($inpOrg, '', '', '', '', '');
        $this->objTable->endRow();
        $this->objTable->startRow();
        $this->objTable->addCell('<b>'.$lblEmail.'&#160;&#58;</b>', '', '', '', '', '');
        $this->objTable->addCell($inpEmail, '', '', '', '', '');
        $this->objTable->endRow();
        $this->objTable->startRow();
        $this->objTable->addCell('<b>'.$lblTel.'&#160;&#58;</b>', '', '', '', '', '');
        $this->objTable->addCell($inpTel, '', '', '', '', '');
        $this->objTable->endRow();
        $this->objTable->startRow();
        $this->objTable->addCell('<b>'.$lblExt.'&#160;&#58;</b>', '', '', '', '', '');
        $this->objTable->addCell($inpExt, '', '', '', '', '');
        $this->objTable->endRow();
        $this->objTable->startRow();
        $this->objTable->addCell('<b>'.$lblCell.'&#160;&#58;</b>', '', '', '', '', '');
        $this->objTable->addCell($inpCell, '', '', '', '', '');
        $this->objTable->endRow();
        $this->objTable->startRow();
        $this->objTable->addCell('<b>'.$lblAddress.'&#160;&#58;</b>', '', '', '', '', '');
        $this->objTable->addCell($txtAddress, '', '', '', '', '');
        $this->objTable->endRow();        
        $tblDisplay = $this->objTable->show();
        
        // set up forms
        $this->objForm = new form('frmExaminers',$this->uri(array(
            'action' => 'save_examiner',
            'd' => $depId,
            'u' => $id,
        ), 'examiners'));
        $this->objForm->addToForm($tblDisplay);
        $this->objForm->addToForm('<br />'.$btnSubmit.'&#160;'.$btnCancel);
        $this->objForm->addRule('name', $lblNameRequired, 'required');
        $this->objForm->addRule('surname', $lblSurnameRequired, 'required');
        $this->objForm->addRule('email', $lblEmailRequired, 'required');        
        $frmSubmit = $this->objForm->show();
    
        $this->objForm=new form('frmCancel',$this->uri(array(
            'action' => 'examiners',
            'd' => $depId,
        ), 'examiners'));
        $frmCancel = $this->objForm->show();
        
        // set up return link
        $this->objLink = new link($this->uri(array(
            'action' => 'examiners',
            'd' => $depId,
        ),'examiners'));
        $this->objLink->link = $lblReturn;
        $lnkReturn = $this->objLink->show();

        // set up page
        $str = $heading;
        $str .= $frmSubmit;
        $str .= $frmCancel;
        $str .= '<br />'.$lnkReturn;
               
        return $str;        
    }

	/**
	* Method to display a list of examiners
	*
	* @access public
	* @param string $depId: The id of the department
	* @return string $str: The output string
	*/
	public function showExaminers($depId)
	{
        // append javascript
        $headerParams = $this->getJavascriptFile('new_sorttable.js', 'htmlelements');
        $this->appendArrayVar('headerParams', $headerParams);
        
        // get data
        $department = $this->objExamDb->getDepartmentById($depId);
        $users = $this->objExamDb->getUsersByDepartment($depId);

        // set up text elements
        $lblList = $this->objLanguage->languageText('mod_examiners_examinerlist', 'examiners');        
        $lblAdd = $this->objLanguage->languageText('mod_examiners_addexaminertitle', 'examiners');        
        $lblEdit = $this->objLanguage->languageText('mod_examiners_editexaminertitle', 'examiners');        
        $lblDelete = $this->objLanguage->languageText('mod_examiners_deleteexaminertitle', 'examiners');        
        $lblConfirm = $this->objLanguage->languageText('mod_examiners_examinerconfirm', 'examiners');        
        $lblTitle = $this->objLanguage->languageText('word_title');        
        $lblName = $this->objLanguage->languageText('word_name');        
        $lblSurname = $this->objLanguage->languageText('word_surname');        
        $lblOrg = $this->objLanguage->languageText('word_organisation');        
        $lblAddress = $this->objLanguage->languageText('word_address');        
        $lblEmail = $this->objLanguage->languageText('phrase_emailaddress');        
        $lblTel = $this->objLanguage->languageText('phrase_telephonenumber');        
        $lblCell = $this->objLanguage->languageText('phrase_mobilenumber');        
        $lblExt = $this->objLanguage->languageText('word_extension');
        $lblReturn = $this->objLanguage->languageText('mod_examiners_returnexaminers', 'examiners');
        $lblNoRecords = $this->objLanguage->languageText('mod_examiners_noexaminers', 'examiners');
        $lblReturn = $this->objLanguage->languageText('mod_examiners_returndepartment', 'examiners');
                
        // set up add examiner icon
        $this->objIcon->title = $lblAdd;
        $icoAdd = $this->objIcon->getAddIcon($this->uri(array(
            'action' => 'examiner',
            'd' => $depId,
        ), 'examiners'));

        // set up page heading
        $this->objHeading = new htmlHeading();
        $this->objHeading->str = $lblList.'&#160;'.$icoAdd;
        $this->objHeading->type = 1;
        $heading = $this->objHeading->show();
        
        $this->objHeading = new htmlHeading();
        $this->objHeading->str = $department['department_name'];
        $this->objHeading->type = 3;
        $heading .= $this->objHeading->show();
        
        // set up display table
        $this->objTable = new htmltable();
        $this->objTable->id = "examinerList";
        $this->objTable->css_class = "sorttable";
        $this->objTable->cellpadding = '5';        
        $this->objTable->row_attributes = 'onmouseover="this.className=\'ruler\';" onmouseout="this.className=\'\';" name="row_'.$this->objTable->id.'"';
        $this->objTable->startRow();
        $this->objTable->addCell($lblTitle, '', '', '', 'header', '');
        $this->objTable->addCell($lblName, '', '', '', 'header', '');
        $this->objTable->addCell($lblSurname, '', '', '', 'header', '');
        $this->objTable->addCell($lblOrg, '', '', '', 'header', '');
        $this->objTable->addCell($lblEmail, '', '', '', 'header', '');
        $this->objTable->addCell($lblTel, '', '', '', 'header', '');
        $this->objTable->addCell($lblExt, '', '', '', 'header', '');
        $this->objTable->addCell($lblCell, '', '', '', 'header', '');
        $this->objTable->addCell($lblAddress, '', '', '', 'header', '');
        $this->objTable->addCell('', '', '', '', 'header', '');
        $this->objTable->endRow();
        if($users == FALSE){
            $this->objTable->startRow();
            $this->objTable->addCell($lblNoRecords, '', '', '', 'noRecordsMessage', 'colspan="10"');
            $this->objTable->endRow();
        }else{
            foreach($users as $user){
                // set up edit icon
                $this->objIcon->title = $lblEdit;
                $icoEdit = $this->objIcon->getEditIcon($this->uri(array(
                    'action' => 'examiner',
                    'u' => $user['id'],
                ), 'examiners'));
                
                // set up delete icon
                $deleteArray = array(
                    'action' => 'delete_examiner',
                    'u' => $user['id'],
                );
                $icoDelete = $this->objIcon->getDeleteIconWithConfirm('', $deleteArray, 'examiners', $lblConfirm);
                
                $this->objTable->startRow();
                $this->objTable->addCell($user['title'], '', 'top', '', '', '');
                $this->objTable->addCell($user['first_name'], '', 'top', '', '', '');
                $this->objTable->addCell($user['surname'], '', 'top', '', '', '');
                $this->objTable->addCell($user['organisation'], '', 'top', '', '', '');
                $this->objTable->addCell($user['email_address'], '', 'top', '', '', '');
                $this->objTable->addCell($user['tel_no'], '', 'top', '', '', '');
                $this->objTable->addCell($user['extension'], '', 'top', '', '', '');
                $this->objTable->addCell($user['cell_no'], '', 'top', '', '', '');
                $this->objTable->addCell(nl2br($user['address']), '', 'top', '', '', '');
                $this->objTable->addCell($icoEdit.$icoDelete, '', '', '', '', '');
                $this->objTable->endRow();
            }
        }
        $tblDisplay = $this->objTable->show();
                
        // set up return link
        $this->objLink = new link($this->uri(array(
            'action' => 'departments',
        ),'examiners'));
        $this->objLink->link = $lblReturn;
        $lnkReturn = $this->objLink->show();

        // set up page
        $str = $heading;
        $str .= $tblDisplay;
        $str .= '<br />'.$lnkReturn;
               
        return $str;        
    }

	/**
	* Method to display the add/edit department page
	*
	* @access public
	* @param string $depId: The id of the department
	* @return string $str: The output string
	*/
	public function showAddEditDepartment($depId)
	{
        // get data
        $department = $this->objExamDb->getDepartmentById($depId);
        if($department == FALSE){
            $id = '';
            $name = '';
        }else{
            $id = $department['id'];
            $name = $department['department_name'];
        }
        
        // set up text elements
        $lblSave = $this->objLanguage->languageText('word_save');
        $lblCancel = $this->objLanguage->languageText('word_cancel');        
        $lblName = $this->objLanguage->languageText('word_name');        
        $lblAdd = $this->objLanguage->languageText('mod_examiners_adddepartment', 'examiners');        
        $lblEdit = $this->objLanguage->languageText('mod_examiners_editdepartment', 'examiners');        
        $lblNameRequired = $this->objLanguage->languageText('mod_examiners_requireddepartmentname', 'examiners');        
        $lblReturn = $this->objLanguage->languageText('mod_examiners_returndepartment', 'examiners');
        
        // set up page heading
        $lblHeading = $department ? $lblEdit : $lblAdd;
        $this->objHeading = new htmlHeading();
        $this->objHeading->str = $lblHeading;
        $this->objHeading->type = 1;
        $heading = $this->objHeading->show();
                
        // set up htmlelements
        $this->objInput = new textinput('name', $name, 'text', '66');
        $inpName = $this->objInput->show();

        $this->objButton=new button('submit',$lblSave);
        $this->objButton->setToSubmit();
        $btnSubmit = $this->objButton->show();

        $this->objButton=new button('cancel',$lblCancel);
        $this->objButton->extra = 'onclick="$(\'form_frmCancel\').submit();"';
        $btnCancel = $this->objButton->show();
        
        // set up display table
        $this->objTable = new htmltable();
        $this->objTable->cellspacing = '2';
        $this->objTable->cellpading = '5';        
        $this->objTable->startRow();
        $this->objTable->addCell('<b>'.$lblName.'&#160;&#58;</b>', '', '', '', '', '');
        $this->objTable->addCell($inpName, '', '', '', '', '');
        $this->objTable->endRow();
        $tblDisplay = $this->objTable->show();
        
        // set up forms
        $this->objForm = new form('frmDepartments',$this->uri(array(
            'action' => 'save_department',
            'd' => $id,
        ), 'examiners'));
        $this->objForm->addToForm($tblDisplay);
        $this->objForm->addToForm('<br />'.$btnSubmit.'&#160;'.$btnCancel);
        $this->objForm->addRule('name', $lblNameRequired, 'required');
        $frmSubmit = $this->objForm->show();
    
        $this->objForm=new form('frmCancel',$this->uri(array(
            'action' => 'departments',
        ), 'examiners'));
        $frmCancel = $this->objForm->show();
        
        // set up return link
        $this->objLink = new link($this->uri(array(
            'action' => 'departments',
        ),'examiners'));
        $this->objLink->link = $lblReturn;
        $lnkReturn = $this->objLink->show();

        // set up page
        $str = $heading;
        $str .= $frmSubmit;
        $str .= $frmCancel;
        $str .= '<br />'.$lnkReturn;
               
        return $str;        
    }

	/**
	* Method to display a list of departments
	*
	* @access public
	* @return string $str: The output string
	*/
	public function showDepartments()
	{
        // append javascript
        $headerParams = $this->getJavascriptFile('new_sorttable.js', 'htmlelements');
        $this->appendArrayVar('headerParams', $headerParams);
        
        // get data
        $departments = $this->objExamDb->getAllDepartments();

        // set up text elements
        $lblExaminerList = $this->objLanguage->languageText('mod_examiners_examinerlist', 'examiners');        
        $lblList = $this->objLanguage->languageText('mod_examiners_departmentlist', 'examiners');        
        $lblAdd = $this->objLanguage->languageText('mod_examiners_adddepartmenttitle', 'examiners');        
        $lblEdit = $this->objLanguage->languageText('mod_examiners_editdepartmenttitle', 'examiners');        
        $lblDelete = $this->objLanguage->languageText('mod_examiners_deletedepartmenttitle', 'examiners');        
        $lblConfirm = $this->objLanguage->languageText('mod_examiners_departmentconfirm', 'examiners');        
        $lblName = $this->objLanguage->languageText('word_name');        
        $lblReturn = $this->objLanguage->languageText('mod_examiners_returndepartment', 'examiners');
        $lblNoRecords = $this->objLanguage->languageText('mod_examiners_nodepartments', 'examiners');
        
                
        // set up add examiner icon
        $this->objIcon->title = $lblAdd;
        $icoAdd = $this->objIcon->getAddIcon($this->uri(array(
            'action' => 'department',
        ), 'examiners'));

        // set up page heading
        $this->objHeading = new htmlHeading();
        $this->objHeading->str = $lblList.'&#160;'.$icoAdd;
        $this->objHeading->type = 1;
        $heading = $this->objHeading->show();
        
        // set up display table
        $this->objTable = new htmltable();
        $this->objTable->id = "departmentList";
        $this->objTable->css_class = "sorttable";
        $this->objTable->cellpadding = '5';        
        $this->objTable->row_attributes = 'onmouseover="this.className=\'ruler\';" onmouseout="this.className=\'\';" name="row_'.$this->objTable->id.'"';
        $this->objTable->startRow();
        $this->objTable->addCell($lblName, '', '', '', 'header', '');
        $this->objTable->addCell('', '', '', '', 'header', '');
        $this->objTable->endRow();
        if($departments == FALSE){
            $this->objTable->startRow();
            $this->objTable->addCell($lblNoRecords, '', '', '', 'noRecordsMessage', 'colspan="2"');
            $this->objTable->endRow();
        }else{
            foreach($departments as $department){
                // set up edit icon
                $this->objIcon->title = $lblEdit;
                $icoEdit = $this->objIcon->getEditIcon($this->uri(array(
                    'action' => 'department',
                    'd' => $department['id'],
                ), 'examiners'));
                
                // set up delete icon
                $deleteArray = array(
                    'action' => 'delete_department',
                    'd' => $department['id'],
                );
                $icoDelete = $this->objIcon->getDeleteIconWithConfirm('', $deleteArray, 'examiners', $lblConfirm);
                
                // set up subject link
                $this->objLink = new link($this->uri(array(
                    'action' => 'subjects',
                    'd' => $department['id'],
                ),'examiners'));
                $this->objLink->link = $department['department_name'];
                $lnkName = $this->objLink->show();

                // set up examiners icon
                $this->objIcon->title = $lblExaminerList;
                $this->objIcon->extra = '';
                $icoExaminers = $this->objIcon->getLinkedIcon($this->uri(array(
                    'action' => 'examiners',
                    'd' => $department['id'],
                )), 'customerdetails');

                $this->objTable->startRow();
                $this->objTable->addCell($lnkName, '', 'top', '', '', '');
                $this->objTable->addCell($icoExaminers.'&#160;'.$icoEdit.'&#160;'.$icoDelete, '', '', '', '', '');
                $this->objTable->endRow();
            }
        }
        $tblDisplay = $this->objTable->show();
                
        // set up page
        $str = $heading;
        $str .= $tblDisplay;
        //$str .= '<br />'.$lnkReturn;
               
        return $str;        
    }

	/**
	* Method to display the add/edit subject page
	*
	* @access public
	* @param string $depId: The id of the department
	* @param string $subjId: The id of the subject
	* @return string $str: The output string
	*/
	public function showAddEditSubject($depId, $subjId)
	{
        // get data
        $subject = $this->objExamDb->getSubjectById($subjId);
        $department = $this->objExamDb->getDepartmentById($depId);
        if($subject == FALSE){
            $id = '';
            $code = '';
            $name = '';
            $level = '1';
            $status = '1';
        }else{
            $id = $subject['id'];
            $code = $subject['course_code'];
            $name = $subject['course_name'];
            $level = $subject['course_level'];
            $status = $subject['course_status'];
        }
        
        // set up text elements
        $lblSelect = $this->objLanguage->languageText('word_select');
        $lblSave = $this->objLanguage->languageText('word_save');
        $lblCancel = $this->objLanguage->languageText('word_cancel');        
        $lblCode = $this->objLanguage->languageText('word_code');        
        $lblName = $this->objLanguage->languageText('word_name');
        $lblLevel = $this->objLanguage->languageText('word_level');
        $lblStatus = $this->objLanguage->languageText('word_status');        
        $lblInactive = $this->objLanguage->languageText('word_inactive');
        $lblActive = $this->objLanguage->languageText('word_active');
        $lblUndergraduate = $this->objLanguage->languageText('word_undergraduate');
        $lblPostgraduate = $this->objLanguage->languageText('word_postgraduate');
        $lblAdd = $this->objLanguage->languageText('mod_examiners_addsubject', 'examiners');        
        $lblEdit = $this->objLanguage->languageText('mod_examiners_editsubject', 'examiners');        
        $lblNameRequired = $this->objLanguage->languageText('mod_examiners_requiredsubjectname', 'examiners');        
        $lblCodeRequired = $this->objLanguage->languageText('mod_examiners_requiredsubjectcode', 'examiners');        
        $lblReturn = $this->objLanguage->languageText('mod_examiners_returnsubject', 'examiners');
        
        // set up page heading
        $lblHeading = $subject ? $lblEdit : $lblAdd;
        $this->objHeading = new htmlHeading();
        $this->objHeading->str = $lblHeading;
        $this->objHeading->type = 1;
        $heading = $this->objHeading->show();
                
        $this->objHeading = new htmlHeading();
        $this->objHeading->str = $department['department_name'];
        $this->objHeading->type = 3;
        $heading .= $this->objHeading->show();

        // set up htmlelements
        $this->objInput = new textinput('code', $code, 'text', '');
        $inpCode = $this->objInput->show();

        $this->objInput = new textinput('name', $name, 'text', '66');
        $inpName = $this->objInput->show();
        
        $this->objDrop = new dropdown('level');
        $this->objDrop->addOption('', $lblSelect.'&#160;');
        $this->objDrop->addOption('1', $lblUndergraduate.'&#160;');
        $this->objDrop->addOption('2', $lblPostgraduate.'&#160;');
        $this->objDrop->setSelected($level);
        $drpLevel = $this->objDrop->show();
        
        $this->objDrop = new dropdown('status');
        $this->objDrop->addOption('', $lblSelect.'&#160;');
        $this->objDrop->addOption('1', $lblActive.'&#160;');
        $this->objDrop->addOption('2', $lblInactive.'&#160;');
        $this->objDrop->setSelected($status);
        $drpStatus = $this->objDrop->show();
        
        $this->objButton=new button('submit',$lblSave);
        $this->objButton->setToSubmit();
        $btnSubmit = $this->objButton->show();

        $this->objButton=new button('cancel',$lblCancel);
        $this->objButton->extra = 'onclick="$(\'form_frmCancel\').submit();"';
        $btnCancel = $this->objButton->show();
        
        // set up display table
        $this->objTable = new htmltable();
        $this->objTable->cellspacing = '2';
        $this->objTable->cellpading = '5';        
        $this->objTable->startRow();
        $this->objTable->addCell('<b>'.$lblCode.'&#160;&#58;</b>', '', '', '', '', '');
        $this->objTable->addCell($inpCode, '', '', '', '', '');
        $this->objTable->endRow();
        $this->objTable->startRow();
        $this->objTable->addCell('<b>'.$lblName.'&#160;&#58;</b>', '', '', '', '', '');
        $this->objTable->addCell($inpName, '', '', '', '', '');
        $this->objTable->endRow();
        $this->objTable->startRow();
        $this->objTable->addCell('<b>'.$lblLevel.'&#160;&#58;</b>', '', '', '', '', '');
        $this->objTable->addCell($drpLevel, '', '', '', '', '');
        $this->objTable->endRow();
        $this->objTable->startRow();
        $this->objTable->addCell('<b>'.$lblStatus.'&#160;&#58;</b>', '', '', '', '', '');
        $this->objTable->addCell($drpStatus, '', '', '', '', '');
        $this->objTable->endRow();
        $tblDisplay = $this->objTable->show();
        
        // set up forms
        $this->objForm = new form('frmSubjects',$this->uri(array(
            'action' => 'save_subject',
            's' => $id,
            'd' => $depId,
        ), 'examiners'));
        $this->objForm->addToForm($tblDisplay);
        $this->objForm->addToForm('<br />'.$btnSubmit.'&#160;'.$btnCancel);
        $this->objForm->addRule('code', $lblCodeRequired, 'required');
        $this->objForm->addRule('name', $lblNameRequired, 'required');
        $frmSubmit = $this->objForm->show();
    
        $this->objForm=new form('frmCancel',$this->uri(array(
            'action' => 'subjects',
            'd' => $depId,
        ), 'examiners'));
        $frmCancel = $this->objForm->show();
        
        // set up return link
        $this->objLink = new link($this->uri(array(
            'action' => 'subjects',
            'd' => $depId,
        ),'examiners'));
        $this->objLink->link = $lblReturn;
        $lnkReturn = $this->objLink->show();

        // set up page
        $str = $heading;
        $str .= $frmSubmit;
        $str .= $frmCancel;
        $str .= '<br />'.$lnkReturn;
               
        return $str;        
    }

	/**
	* Method to display a list of departments
	*
	* @access public
	* @param string $depId: The id of the department
	* @return string $str: The output string
	*/
	public function showSubjects($depId)
	{
        // append javascript
        $headerParams = $this->getJavascriptFile('new_sorttable.js', 'htmlelements');
        $this->appendArrayVar('headerParams', $headerParams);
        
        // get data
        $subjects = $this->objExamDb->getSubjectsByDepartment($depId);
        $department = $this->objExamDb->getDepartmentById($depId);

        // set up text elements
        $lblList = $this->objLanguage->languageText('mod_examiners_subjectlist', 'examiners');        
        $lblAdd = $this->objLanguage->languageText('mod_examiners_addsubjecttitle', 'examiners');        
        $lblEdit = $this->objLanguage->languageText('mod_examiners_editsubjecttitle', 'examiners');        
        $lblDelete = $this->objLanguage->languageText('mod_examiners_deletesubjecttitle', 'examiners');        
        $lblConfirm = $this->objLanguage->languageText('mod_examiners_subjectconfirm', 'examiners');        
        $lblCode = $this->objLanguage->languageText('word_code');        
        $lblName = $this->objLanguage->languageText('word_name');
        $lblLevel = $this->objLanguage->languageText('word_level');
        $lblStatus = $this->objLanguage->languageText('word_status');
        $lblInactive = $this->objLanguage->languageText('word_inactive');
        $lblActive = $this->objLanguage->languageText('word_active');
        $lblUndergraduate = $this->objLanguage->languageText('word_undergraduate');
        $lblPostgraduate = $this->objLanguage->languageText('word_postgraduate');
        $lblReturn = $this->objLanguage->languageText('mod_examiners_returnsubject', 'examiners');
        $lblNoRecords = $this->objLanguage->languageText('mod_examiners_nosubjects', 'examiners');        
        $lblReturn = $this->objLanguage->languageText('mod_examiners_returndepartment', 'examiners');
        $lblExaminerList = $this->objLanguage->languageText('mod_examiners_examinerlist', 'examiners');        
                
        // set up add examiner icon
        $this->objIcon->title = $lblAdd;
        $icoAdd = $this->objIcon->getAddIcon($this->uri(array(
            'action' => 'subject',
            'd' => $depId,
        ), 'examiners'));

        // set up page heading
        $this->objHeading = new htmlHeading();
        $this->objHeading->str = $lblList.'&#160;'.$icoAdd;
        $this->objHeading->type = 1;
        $heading = $this->objHeading->show();
        
        // set up page heading
        $this->objHeading = new htmlHeading();
        $this->objHeading->str = $department['department_name'];
        $this->objHeading->type = 3;
        $heading .= $this->objHeading->show();
        
        // set up display table
        $this->objTable = new htmltable();
        $this->objTable->id = "subjectList";
        $this->objTable->css_class = "sorttable";
        $this->objTable->cellpadding = '5';        
        $this->objTable->row_attributes = 'onmouseover="this.className=\'ruler\';" onmouseout="this.className=\'\';" name="row_'.$this->objTable->id.'"';
        $this->objTable->startRow();
        $this->objTable->addCell($lblCode, '', '', '', 'header', '');
        $this->objTable->addCell($lblName, '', '', '', 'header', '');
        $this->objTable->addCell($lblLevel, '', '', '', 'header', '');
        $this->objTable->addCell($lblStatus, '', '', '', 'header', '');
        $this->objTable->addCell('', '', '', '', 'header', '');
        $this->objTable->endRow();
        if($subjects == FALSE){
            $this->objTable->startRow();
            $this->objTable->addCell($lblNoRecords, '', '', '', 'noRecordsMessage', 'colspan="5"');
            $this->objTable->endRow();
        }else{
            foreach($subjects as $subject){
                // set up edit icon
                $this->objIcon->title = $lblEdit;
                $icoEdit = $this->objIcon->getEditIcon($this->uri(array(
                    'action' => 'subject',
                    's' => $subject['id'],
                    'd' => $subject['dep_id'],
                ), 'examiners'));
                
                // set up delete icon
                $deleteArray = array(
                    'action' => 'delete_subject',
                    's' => $subject['id'],
                    'd' => $subject['dep_id'],
                );
                $icoDelete = $this->objIcon->getDeleteIconWithConfirm('', $deleteArray, 'examiners', $lblConfirm);
                
                // set up examiners link
                $this->objLink = new link($this->uri(array(
                    'action' => 'matrix',
                    'd' => $department['id'],
                    's' => $subject['id'],
                ),'examiners'));
                $this->objLink->link = $subject['course_name'];
                $lnkName = $this->objLink->show();


                if($subject['course_level'] == 1){
                    $level = $lblUndergraduate;
                }else{
                    $level = $lblPostgraduate;
                }
                
                if($subject['course_status'] == 1){
                    $status = $lblActive;
                }else{
                    $status = $lblInactive;
                }

                $this->objTable->startRow();
                $this->objTable->addCell($subject['course_code'], '', 'top', '', '', '');
                $this->objTable->addCell($lnkName, '', 'top', '', '', '');
                $this->objTable->addCell($level, '', 'top', '', '', '');
                $this->objTable->addCell($status, '', 'top', '', '', '');
                $this->objTable->addCell($icoEdit.'&#160;'.$icoDelete, '', '', '', '', '');
                $this->objTable->endRow();
            }
        }
        $tblDisplay = $this->objTable->show();
                
        // set up return link
        $this->objLink = new link($this->uri(array(
            'action' => 'departments',
        ),'examiners'));
        $this->objLink->link = $lblReturn;
        $lnkReturn = $this->objLink->show();

        // set up page
        $str = $heading;
        $str .= $tblDisplay;
        $str .= '<br />'.$lnkReturn;
               
        return $str;        
    }
    
    /**
    * Method to show the examiner matrix
    *
    * @access public
    * @param string $depId: The id of the department
    * @param string $subjId: The id of the subject
	* @return string $str: The output string
	*/
	public function showMatrix($depId, $subjId, $year)
	{
        // get data
        $year = (isset($year) && !empty($year)) ? $year : date('Y');   
        $department = $this->objExamDb->getDepartmentById($depId);
        $subject = $this->objExamDb->getSubjectById($subjId);
        $matrix = $this->objExamDb->getMatrixByYear($depId, $subjId, $year);

        // set up text elements
        $lblMatrix = $this->objLanguage->languageText('mod_examiners_matrix', 'examiners');        
        $lblReturn = $this->objLanguage->languageText('mod_examiners_returnsubject', 'examiners');
        $lblNoRecords = $this->objLanguage->languageText('mod_examiners_nomatrix', 'examiners');        
        $lblReturn = $this->objLanguage->languageText('mod_examiners_returnsubject', 'examiners');
        $lblFirst = $this->objLanguage->languageText('mod_examiners_first', 'examiners');
        $lblSecond = $this->objLanguage->languageText('mod_examiners_second', 'examiners');
        $lblModerate = $this->objLanguage->languageText('mod_examiners_moderator', 'examiners');
        $lblAlternate = $this->objLanguage->languageText('mod_examiners_alternate', 'examiners');
        $lblRemark = $this->objLanguage->languageText('mod_examiners_remark', 'examiners');
        $lblEdit = $this->objLanguage->languageText('mod_examiners_editmatrixtitle', 'examiners');        
        $lblDelete = $this->objLanguage->languageText('mod_examiners_deletematrixtitle', 'examiners');        
        $lblConfirm = $this->objLanguage->languageText('mod_examiners_matrixconfirm', 'examiners');        

        // set up edit icon
        $this->objIcon->title = $lblEdit;
        $icoEdit = $this->objIcon->getEditIcon($this->uri(array(
            'action' => 'edit_matrix',
            'd' => $depId,
            's' => $subjId,
            'y' => $year,
        ), 'examiners'));
                
        // set up delete icon
        $deleteArray = array(
            'action' => 'delete_matrix',
            'd' => $depId,
            's' => $subjId,
            'y' => $year,
        );
        $icoDelete = $this->objIcon->getDeleteIconWithConfirm('', $deleteArray, 'examiners', $lblConfirm);
        if($matrix == FALSE){
            $icoDelete = '';
        }
                
        // set up page heading
        $this->objHeading = new htmlHeading();
        $this->objHeading->str = $lblMatrix.'&#160;'.$icoEdit.'&#160;'.$icoDelete;
        $this->objHeading->type = 1;
        $heading = $this->objHeading->show();

        $this->objHeading = new htmlHeading();
        $this->objHeading->str = $department['department_name'];
        $this->objHeading->type = 3;
        $heading .= $this->objHeading->show();
                
        $this->objHeading = new htmlHeading();
        $this->objHeading->str = $subject['course_code'].'&#160;&#58;&#160;'.$subject['course_name'];
        $this->objHeading->type = 3;
        $heading .= $this->objHeading->show();

        // set up htmlelements
        $this->objDrop = new dropdown('y');
        for($loop = date('Y'); $loop >= 2006; $loop--){
            $this->objDrop->addOption($loop, $loop.'&#160;');
        }
        $this->objDrop->setSelected($year);
        $this->objDrop->extra = 'onchange="javascript:$(\'form_frmMatrix\').submit();"';
        $drpYear = $this->objDrop->show();

        // set up form
        $this->objForm = new form('frmMatrix',$this->uri(array(
            'action' => 'matrix',
            'd' => $depId,
            's' => $subjId,
        ), 'examiners'));
        $this->objForm->addToForm($drpYear);
        $frmYear = $this->objForm->show();
    
        // set up display table
        $this->objTable = new htmltable();
        $this->objTable->cellpadding = '5';        
        $this->objTable->cellspacing = '2';        
        $this->objTable->row_attributes = 'onmouseover="this.className=\'ruler\';" onmouseout="this.className=\'\';"';
        $this->objTable->startRow();
        $this->objTable->addCell($lblFirst, '20%', '', '', 'header', '');
        $this->objTable->addCell($lblSecond, '20%', '', '', 'header', '');
        $this->objTable->addCell($lblModerate, '20%', '', '', 'header', '');
        $this->objTable->addCell($lblAlternate, '20%', '', '', 'header', '');
        $this->objTable->addCell($lblRemark, '20%', '', '', 'header', '');
        $this->objTable->endRow();
        if($matrix == FALSE){
            $this->objTable->startRow();
            $this->objTable->addCell($lblNoRecords, '', '', '', 'noRecordsMessage', 'colspan="5"');
            $this->objTable->endRow();
        }else{
            $this->objTable->startRow();
            foreach($matrix as $record){
                $user = $record['title'];
                $user .= '&#160;'.$record['first_name'];
                $user .= '&#160;'.$record['surname'].'<br />';
                $user .= isset($record['organisation']) ? $record['organisation'].'<br />' : '';
                $user .= isset($record['email_address']) ? $record['email_address'].'<br />' : '';
                $user .= isset($record['tel_no']) ? $record['tel_no'].'<br />' : '';
                $user .= isset($record['extension']) ? 'Ext&#160;&#58;'.$record['extension'].'<br />' : '';
                $user .= isset($record['cell_no']) ? $record['cell_no'].'<br />' : '';
                $user .= isset($record['address']) ? nl2br($record['address']) : '';
                $this->objTable->addCell($user, '', 'top', '', '', '');
            }
            $this->objTable->endRow();
            $this->objTable->startRow();
            foreach($matrix as $record){
                $user = isset($record['remarks']) ? nl2br($record['remarks']) : '';
                $this->objTable->addCell($user, '', '', '', '', '');
            }
            $this->objTable->endRow();
        }
        $tblDisplay = $this->objTable->show();
        
        // set up return link
        $this->objLink = new link($this->uri(array(
            'action' => 'subjects',
            'd' => $depId,
        ),'examiners'));
        $this->objLink->link = $lblReturn;
        $lnkReturn = $this->objLink->show();

        // set up page
        $str = $heading;
        $str .= $frmYear;
        $str .= $tblDisplay;
        $str .= '<br />'.$lnkReturn;
               
        return $str;        
    }

    /**
    * Method to show the examiner matrix
    *
    * @access public
    * @param string $depId: The id of the department
    * @param string $subjId: The id of the subject
	* @return string $str: The output string
	*/
	public function showEditMatrix($depId, $subjId, $year)
	{
        // get data
        $year = (isset($year) && !empty($year)) ? $year : date('Y');   
        $department = $this->objExamDb->getDepartmentById($depId);
        $subject = $this->objExamDb->getSubjectById($subjId);
        $matrix = $this->objExamDb->getMatrixByYear($depId, $subjId, $year);
        $first = '';
        $firstText = '';
        $second = '';
        $secondText = '';
        $moderate = '';
        $moderateText = '';
        $alternate = '';
        $alternateText = '';
        $remarking = '';
        $remarkingText = '';
        if($matrix != FALSE){
            if($matrix[0] != FALSE){
                $first = $matrix[0]['exam_id'];
                $firstText = $matrix[0]['remarks'];
            }
            if($matrix[1] != FALSE){
                $second = $matrix[1]['exam_id'];
                $secondText = $matrix[1]['remarks'];
            }
            if($matrix[2] != FALSE){
                $moderate = $matrix[2]['exam_id'];
                $moderateText = $matrix[2]['remarks'];
            }
            if($matrix[3] != FALSE){
                $alternate = $matrix[3]['exam_id'];
                $alternateText = $matrix[3]['remarks'];
            }
            if($matrix[4] != FALSE){
                $remarking = $matrix[4]['exam_id'];
                $remarkingText = $matrix[4]['remarks'];
            }
        }
        $users = $this->objExamDb->getUsersByDepartment($depId);

        // set up text elements
        $lblEdit = $this->objLanguage->languageText('word_edit');
        $lblMatrix = $this->objLanguage->languageText('mod_examiners_matrix', 'examiners');        
        $lblReturn = $this->objLanguage->languageText('mod_examiners_returnsubject', 'examiners');
        $lblNoRecords = $this->objLanguage->languageText('mod_examiners_nomatrix', 'examiners');        
        $lblReturn = $this->objLanguage->languageText('mod_examiners_returnmatrix', 'examiners');
        $lblFirst = $this->objLanguage->languageText('mod_examiners_first', 'examiners');
        $lblSecond = $this->objLanguage->languageText('mod_examiners_second', 'examiners');
        $lblModerate = $this->objLanguage->languageText('mod_examiners_moderator', 'examiners');
        $lblAlternate = $this->objLanguage->languageText('mod_examiners_alternate', 'examiners');
        $lblRemarking = $this->objLanguage->languageText('mod_examiners_remark', 'examiners');
        $lblSelect = $this->objLanguage->languageText('word_select');
        $lblSave = $this->objLanguage->languageText('word_save');
        $lblCancel = $this->objLanguage->languageText('word_cancel');        

        // set up page heading
        $this->objHeading = new htmlHeading();
        $this->objHeading->str = $lblEdit.'&#160;'.strtolower($lblMatrix);
        $this->objHeading->type = 1;
        $heading = $this->objHeading->show();

        $this->objHeading = new htmlHeading();
        $this->objHeading->str = $department['department_name'];
        $this->objHeading->type = 3;
        $heading .= $this->objHeading->show();
                
        $this->objHeading = new htmlHeading();
        $this->objHeading->str = $subject['course_code'].'&#160;&#58;&#160;'.$subject['course_name'];
        $this->objHeading->type = 3;
        $heading .= $this->objHeading->show();

        $this->objHeading = new htmlHeading();
        $this->objHeading->str = $year;
        $this->objHeading->type = 3;
        $heading .= $this->objHeading->show();
        
        // set up htmlelements
        $this->objDrop = new dropdown('first');
        $this->objDrop->addOption('', $lblSelect.'&#160;');
        if($users != FALSE){
            foreach($users as $user){
                $name = $user['title'].'&#160;'.$user['first_name'].'&#160;'.$user['surname'].'&#160;';
                $this->objDrop->addOption($user['id'], $name);
            }
        }
        $this->objDrop->setSelected($first);
        $drpFirst = $this->objDrop->show();

        $this->objText = new textarea('text_first', $firstText, '5', '');
        $txtFirst = $this->objText->show();

        $this->objDrop = new dropdown('second');
        $this->objDrop->addOption('', $lblSelect.'&#160;');
        if($users != FALSE){
            foreach($users as $user){
                $name = $user['title'].'&#160;'.$user['first_name'].'&#160;'.$user['surname'].'&#160;';
                $this->objDrop->addOption($user['id'], $name);
            }
        }
        $this->objDrop->setSelected($second);
        $drpSecond = $this->objDrop->show();

        $this->objText = new textarea('text_second', $secondText, '5', '');
        $txtSecond = $this->objText->show();

        $this->objDrop = new dropdown('moderate');
        $this->objDrop->addOption('', $lblSelect.'&#160;');
        if($users != FALSE){
            foreach($users as $user){
                $name = $user['title'].'&#160;'.$user['first_name'].'&#160;'.$user['surname'].'&#160;';
                $this->objDrop->addOption($user['id'], $name);
            }
        }
        $this->objDrop->setSelected($moderate);
        $drpModerate = $this->objDrop->show();

        $this->objText = new textarea('text_moderate', $moderateText, '5', '');
        $txtModerate = $this->objText->show();

        $this->objDrop = new dropdown('alternate');
        $this->objDrop->addOption('', $lblSelect.'&#160;');
        if($users != FALSE){
            foreach($users as $user){
                $name = $user['title'].'&#160;'.$user['first_name'].'&#160;'.$user['surname'].'&#160;';
                $this->objDrop->addOption($user['id'], $name);
            }
        }
        $this->objDrop->setSelected($alternate);
        $drpAlternate = $this->objDrop->show();

        $this->objText = new textarea('text_alternate', $alternateText, '5', '');
        $txtAlternate = $this->objText->show();

        $this->objDrop = new dropdown('remarking');
        $this->objDrop->addOption('', $lblSelect.'&#160;');
        if($users != FALSE){
            foreach($users as $user){
                $name = $user['title'].'&#160;'.$user['first_name'].'&#160;'.$user['surname'].'&#160;';
                $this->objDrop->addOption($user['id'], $name);
            }
        }
        $this->objDrop->setSelected($remarking);
        $drpRemarking = $this->objDrop->show();

        $this->objText = new textarea('text_remarking', $remarkingText, '5', '');
        $txtRemarking = $this->objText->show();

        $this->objButton=new button('submit',$lblSave);
        $this->objButton->setToSubmit();
        $btnSubmit = $this->objButton->show();

        $this->objButton=new button('cancel',$lblCancel);
        $this->objButton->extra = 'onclick="$(\'form_frmCancel\').submit();"';
        $btnCancel = $this->objButton->show();
        
        // set up display table
        $this->objTable = new htmltable();
        $this->objTable->cellpadding = '5';        
        $this->objTable->cellspacing = '2';        
        $this->objTable->row_attributes = 'onmouseover="this.className=\'ruler\';" onmouseout="this.className=\'\';"';
        $this->objTable->startRow();
        $this->objTable->addCell($lblFirst, '20%', '', '', 'header', '');
        $this->objTable->addCell($lblSecond, '20%', '', '', 'header', '');
        $this->objTable->addCell($lblModerate, '20%', '', '', 'header', '');
        $this->objTable->addCell($lblAlternate, '20%', '', '', 'header', '');
        $this->objTable->addCell($lblRemarking, '20%', '', '', 'header', '');
        $this->objTable->endRow();
        $this->objTable->startRow();
        $this->objTable->addCell($drpFirst, '', '', '', '', '');
        $this->objTable->addCell($drpSecond, '', '', '', '', '');
        $this->objTable->addCell($drpModerate, '', '', '', '', '');
        $this->objTable->addCell($drpAlternate, '', '', '', '', '');
        $this->objTable->addCell($drpRemarking, '', '', '', '', '');
        $this->objTable->endRow();
        $this->objTable->startRow();
        $this->objTable->addCell($txtFirst, '', '', '', '', '');
        $this->objTable->addCell($txtSecond, '', '', '', '', '');
        $this->objTable->addCell($txtModerate, '', '', '', '', '');
        $this->objTable->addCell($txtAlternate, '', '', '', '', '');
        $this->objTable->addCell($txtRemarking, '', '', '', '', '');
        $this->objTable->endRow();
        $tblDisplay = $this->objTable->show();
        
        // set up forms
        $this->objForm = new form('frmSubjects',$this->uri(array(
            'action' => 'save_matrix',
            's' => $subjId,
            'd' => $depId,
            'y' => $year,
        ), 'examiners'));
        $this->objForm->addToForm($tblDisplay);
        $this->objForm->addToForm('<br />'.$btnSubmit.'&#160;'.$btnCancel);
        $frmSubmit = $this->objForm->show();
    
        $this->objForm=new form('frmCancel',$this->uri(array(
            'action' => 'matrix',
            's' => $subjId,
            'd' => $depId,
            'y' => $year,
        ), 'examiners'));
        $frmCancel = $this->objForm->show();
        
        // set up return link
        $this->objLink = new link($this->uri(array(
            'action' => 'matrix',
            's' => $subjId,
            'd' => $depId,
            'y' => $year,
        ),'examiners'));
        $this->objLink->link = $lblReturn;
        $lnkReturn = $this->objLink->show();

        // set up page
        $str = $heading;
        $str .= $frmSubmit;
        $str .= $frmCancel;
        $str .= '<br />'.$lnkReturn;
               
        return $str;        
    }
}
?>