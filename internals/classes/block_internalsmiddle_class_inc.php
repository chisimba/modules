<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of block_internalsmiddle_class_inc
 *
 * @author monwabisi
 */
class block_internalsmiddle extends Object {

    var $objLanguage;
    var $dbInternals;
    var $objUser;
    var $objCallendar;
    var $objAltConfig;

    //put your code here
    public function init() {
        $this->loadClass('textinput', 'htmlelements');
        $this->loadClass('link', 'htmlelements');
        $this->loadClass('form', 'htmlelements');
        $this->loadClass('radio', 'htmlelements');
        $this->objLanguage = $this->getObject('language', 'language');
        $this->title = $this->objLanguage->languageText('word_internals_title', 'system');
        $this->dbInternals = $this->getObject('dbinternals', 'internals');
        $this->objUser = $this->getObject('user', 'security');
        $this->objAltConfig = $this->getObject('altconfig', 'config');
    }

    /**
     * The function to return the form for leave application
     * private
     * @access public
     * @return object
     */
    public function biuldAppForm() {
        //leave application form
//        $this->_tableName = "tbl_requests";
        $frmLeave = new form('frmLeave');
        $frmLeave->addToForm("<h2>{$this->objLanguage->languageText('phrase_apply', 'system')}</h2>");
        $btnSave = $this->getObject('button', 'htmlelements');
        $tblLeave = $this->getObject('htmltable', 'htmlelements');
        //get all vailable leaves
        $leaves = $this->dbInternals->getLeaveList();
        $labels = $this->getObject('label', 'htmlelements');
        $labels->labelValue = $this->objLanguage->languageText('phrase_selectleave', 'system');
        $tblLeave->startHeaderRow();
        $tblLeave->addHeaderCell($labels->show());
        $labels->labelValue = $this->objLanguage->languageText('phrase_daysdue', 'system');
        $tblLeave->addHeaderCell($labels->show(), NULL, NULL, 'center', NULL, NULL);
        $labels->labelValue = $this->objLanguage->languageText('phrase_daysremaining', 'system');
        $tblLeave->addHeaderCell($labels->show(), NULL, NULL, 'center', NULL, NULL);
        $tblLeave->endHeaderRow();
        $tblLeave->startRow();
        $tblLeave->addCell('&nbsp;');
        $tblLeave->endRow();
        $index = 0;
        if (count($leaves) > 0) {
            //populate the dropdown list with the values from the database
            foreach ($leaves as $item) {
                $tblLeave->startRow();
                //radio button for leavve types
                $rdLeaveTypes = new radio('');
                $daysLeft = $this->dbInternals->getDaysLeft($item['id'], $this->objUser->getUserId($this->objUser->userName()));
                $rdLeaveTypes->addOption($item['id'], $item['name']);
                $tblLeave->addCell($rdLeaveTypes->show());
                $tblLeave->addCell($item['numberofdays'], NULL, NULL, 'center', NULL, NULL);
                if (count($daysLeft) <= 0) {
//                    $daysLeft = $item['numberofdays'];
                    $tblLeave->addCell($item['numberofdays'], NULL, NULL, 'center', NULL, NULL);
                } else {
                    $tblLeave->addCell($daysLeft[0]['daysleft'], NULL, NULL, 'center', NULL, NULL);
                }
                $tblLeave->startRow();
                $tblLeave->addCell('&nbsp;');
                $tblLeave->endRow();
                $index++;
            }
        }
        //start second row
        $tblLeave->startRow();
        $tblLeave->addCell('&nbsp;');
        $tblLeave->endRow();
        //End row
        //Start row
        $tblLeave->startRow();
        $tblLeave->addCell('&nbsp;');
        $tblLeave->endRow();
        //EndRow
        //Start row
        $tblLeave->startRow();
        //Start date
        $labels->labelValue = $this->objLanguage->languageText('phrase_startdate', 'system');
        $tblLeave->addCell($labels->show());
//                $txtStartDate->name = "startdate";
        /**
         * Date-picker
         * @TODO: Calculate the number of days
         */
        $startDate = $this->getObject('datepickajax', 'popupcalendar');
        $startDate->buildCal();
        $startDate->showCal();
        /**
         * @todo calculate the number of days
         */
        $tblLeave->addCell($startDate->show('startdate', 'no', 'yes') . "<span id='start_date_error' class='error' >{$this->objLanguage->languageText('phrase_enterstartdate', 'system')}</span>");
        $tblLeave->endRow();
        //End row
        //Start row
        $tblLeave->startRow();
        $tblLeave->addCell('&nbsp;');
        $tblLeave->endRow();
        //End row
        //Start row
        $tblLeave->startRow();
        //end date
        $labels->labelValue = $this->objLanguage->languageText('phrase_enddate', 'system');
        $tblLeave->addCell($labels->show());
        $endDate = $this->getObject('datepickajax', 'popupcalendar');
        $endDate->buildCal();
        $endDate->showCal();
        $tblLeave->addCell($endDate->show('enddate', 'no', 'yes') . "<span id='end_date_error' class='error' >{$this->objLanguage->languageText('phrase_enterenddate', 'system')}</span>");
        $tblLeave->endRow();
        //end row
        //Start row
        $tblLeave->startRow();
        $tblLeave->addCell('&nbsp;');
        $tblLeave->endRow();
        //End row
        //Start ro
        $tblLeave->startRow();
        $btnSave->name = "btnSave";
        $btnSave->cssId = "btnSave";
        $btnSave->value = $this->objLanguage->languageText('word_apply', 'system');
        $tblLeave->addCell($btnSave->show());
        $tblLeave->endRow();

        $frmLeave->addToForm($tblLeave->show());
        if (count($leaves) <= 0) {
            return $this->objLanguage->languageText('phrase_noappleave', 'system');
        }
        return $frmLeave->show();
    }

    /**
     * The function to return the appropriate forms
     * 
     * @access public
     * @return object form
     */
    public function buildUserForm() {
        //initialize all objects
        $form = $this->getObject('form', 'htmlelements');
        $label = $this->getObject('label', 'htmlelements');
        $link = $this->getObject('link', 'htmlelements');
        //get applicable leaves
        $leaveList = $this->dbInternals->getLeaveList();
        if (count($leaveList) > 0) {
            foreach ($leaveList as $Item) {
                $link->link = $Item['name'];
                $link->href = '#';
                $link->cssId = strtolower($link->link);
                //display leave
                $label->labelValue = $Item['name'] . '<br />';
                $form->addToForm($link->show());
                $link->extra = "id='" . ($Item['id']);
            }
        } else {
            $label->labelValue = "<h1>{$this->objLanguage->languageText('mod_internals_noleave', 'internals')}</h1>";
            $form->addToForm($label->show());
        }
        return $this->biuldAppForm() . $this->getjavascriptFile('internalsHelper.js', 'internals');
    }

    /**
     * Function returning the form used to add leave types
     * 
     * @access public
     * @return object form
     */
    public function addLeaveType() {
        $txtLang = new textinput();
        $lblValues = $this->getObject('label', 'htmlelements');
        $txtLang->name = "txtSaveLeave";
        $txtLang->cssId = "txtSaveLeave";
        $txtDays = new textinput();
        $txtDays->name = "txtNumDays";
        $txtDays->cssId = "txtNumDays";
        $btnSave = $this->getObject('button', 'htmlelements');
        $btnSave->name = "btnSaveLeave";
        $btnSave->cssId = "btnSaveLeave";
        $btnSave->value = $this->objLanguage->languageText('word_save', 'system');
        $frmAddLeave = new form('frmAddLeave');
        $frmAddLeave->name = "frmAddLeave";
        $frmAddLeave->cssId = "frmAddLeave";
        $frmAddLeave->addToForm('<h2>' . $this->objLanguage->languageText('mod_internals_addleave', 'internals') . '</h2>');
        $tblLayout = new htmltable();
        //Build the form
        $tblLayout->startRow();
        $lblValues->labelValue = "{$this->objLanguage->languageText('phrase_leavename', 'system')}";
        $tblLayout->addCell($lblValues->show());
        $tblLayout->addCell($txtLang->show());
        $tblLayout->endRow();
        //
        $tblLayout->startRow();
        $lblValues->labelValue = $this->objLanguage->languageText('phrase_enterdaysdue', 'system');
        $tblLayout->addCell($lblValues->show());
        $tblLayout->addCell($txtDays->show());
        $tblLayout->endRow();
        //
        $tblLayout->startRow();
        $tblLayout->addCell($btnSave->show());
        $tblLayout->endRow();
        //
        $frmAddLeave->addToForm($tblLayout->show());
        return $frmAddLeave->show();
    }

    /**
     * Function to return the Administrators form
     * 
     * @access public
     * @return object Form
     */
    public function buildAdminForm() {
        //reject link
        $rejectLink = new link('#');
        $rejectLink->cssClass = 'rejectLink';
        $rejectLink->link = $this->objLanguage->languageText('word_reject', 'system');
        //accept link
        $acceptLink = new link('#');
        $acceptLink->cssClass = 'acceptLink';
        $acceptLink->link = $this->objLanguage->languageText('word_approve', 'system');
        //form to contain all controlls
        $form = new form('frmAdminRequests');
        $form->name = "frmAdminRequests";
        $form->cssId = "frmAdminRequests";
        $valuesArray = array();
        //if user is internals amdin, get the list of leave requests
        $values = $this->dbInternals->getLeaveRequests();
        //check available requests and display them
        if (count($values) > 0) {
            foreach ($values as $value) {
                //reject link
                $rejectLink->cssId = $value['id'];
                $rejectLink->extra = "x-data={$value['userid']}";
                //send comments link, in case of rejected requests
                $sendLink = new link('#');
                $sendLink->link = "{$this->objLanguage->languageText('word_send', 'system')}";
                $sendLink->cssId = $value['id'];
                $sendLink->cssClass = "sendLink";
                $sendLink->extra = "x-data={$value['userid']}";
                //comentary paragraph
                $this->loadClass('textarea', 'htmlelements');
                $comments = new textarea();
                $comments->cssId = $value['id'];
                $comments->name = $value['id'];
                $comments->cssClass = "requestComments";
                //accept link
                $acceptLink->cssId = $value['id'];
                $acceptLink->extra = "x-data={$value['userid']}";
                $acceptLink->href = $this->uri(array('action' => 'accept','id'=>$value['id'],'x_data'=>$value['userid'],'status'=>'approved','leaveid'=>$value['leaveid']), 'internals');
                $userName = $this->objUser->fullName($value['userid']) . '<br />Requested ' . $value['days'] . ' day(s) of ' . $this->dbInternals->getLeaveName($value['leaveid']) . ' leave<br />Starting from ' . $value['startdate'] . '<br />' . $acceptLink->show() . '&nbsp;&nbsp;&nbsp;&nbsp;' . $rejectLink->show();
                array_push($valuesArray, $userName);
                $form->addToForm('<p >' . $userName . '</p>');
                $form->addToForm($comments->show());
                $form->addToForm('<br />');
                $form->addToForm($sendLink->show());
            }
            return $form->show() . $this->getjavascriptFile('internlsHelper.js', 'internals');
        } else {
            $days = 13;
            $startDate = '2013-03-14';
            $endDate = '2013-03-28';
            require $this->objAltConfig->getModulePath() . 'pdfmaker/resources/tcpdf.php';
            $pdf = new TCPDF();
            $html = "
<div><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
<h1 align='center' ><font size='30'  ><u>Leave Application Form</u></font></h1><br/><br/>
<table width='100%'>
<thead>
</thead>
<tbody>
<tr>
<td >
<b>Name:</b> <u>Monwabisi Sifumba</u>
</td>
<td>
<b>Date:</b> <u>2013-03-04</u>
</td>
</tr>
<tr>
<td>
</td>
</tr>
<tr>
<td>
&nbsp;<b>Position:</b>
<u>Web Developer</u>
</td>
</tr>
</tbody>
</table>
<br/>
<table>
<tbody>
<tr>
<td>
<div> Please approve absence from work for <b>{$days}</b> days, from <b>{$startDate}</b> to <b>{$endDate}</b> inclusive.</div>
</td>
</tr>
</tbody>
</table>
<table>
<br/><br/>
<tbody>
<tr>
<td>
            Annual leave
</td>
<td>
            Public Holiday
</td>
</tr>
<tr>
<td>
</td>
</tr>
<tr>
<td>
            Compassionate leave
</td>
<td>
            Absent without pay
</td>
</tr>
<tr>
<td>
</td>
</tr>
<tr>
<td>
            Maternity
</td>
<td>
            Others, please specify
</td>
</tr>
</tbody>
</table>
<br/><br/><br/>
<table border='1'>
<thead>
<tr>
<td border='1'>
No. of Days available
</td>
<td>
No. of Days leave taken
</td>
<td>
No. of Days leave balance
</td>
</tr>
</thead>
<tbody>
<tr>
<td align='center'>
21
</td>
<td align='center'>
13
</td>
<td align='center'>
8
</td>
</tr>
</tbody>
</table>
</div>";
            $pdf->SetAuthor("Monwai");
            $pdf->SetTitle("TCPDF Example 001");
            $pdf->SetSubject("TCPDF Tutorial");
            $pdf->SetKeywords("TCPDF, PDF, example, test, guide");
            $pdf->AddPage('');
            $pdf->setImageScale(5);
            $pdf->Image($this->objAltConfig->getModulePath() . "pdfmaker/resources/images/thumbzup-logo.jpg", 100, 5, 100, 30, '', 'http://www.tcpdf.org', '', true, 72);
//            $pdf->Image($this->objAltConfig->getModulePath() . "pdfmaker/resources/images/logo.jpg", 160, 10, 45, 60, '', 'http://www.tcpdf.org', '', true, 72);
            $pdf->writeHTML($html);
// get current vertical position
//            $current_y_position = $pdf->getY();
// write the first column
//            $pdf->writeHTMLCell($first_column_width, 0, 0, 0, $left_column, 0, 0, 0, true);
//$pdf->CheckBox('newsletter', 5, true);
// write the second column
//            $pdf->writeHTMLCell($second_column_width, 0, 0, 0, $right_column, 0, 1, 0, true);
// reset pointer to the last page
            $pdf->Image($this->objAltConfig->getModulePath() . "pdfmaker/resources/images/unchkd.png", 14, 113, 8, 7, '', 'http://www.tcpdf.org', '', true, 72);
            $pdf->Image($this->objAltConfig->getModulePath() . "pdfmaker/resources/images/Untitled.png", 15, 113, 8, 7, '', 'http://www.tcpdf.org', '', true, 72);
            $pdf->Image($this->objAltConfig->getModulePath() . "pdfmaker/resources/images/unchkd.png", 14, 124, 8, 7, '', 'http://www.tcpdf.org', '', true, 72);
            $pdf->Image($this->objAltConfig->getModulePath() . "pdfmaker/resources/images/unchkd.png", 109, 113, 8, 7, '', 'http://www.tcpdf.org', '', true, 72);
            $pdf->Image($this->objAltConfig->getModulePath() . "pdfmaker/resources/images/unchkd.png", 109, 124, 8, 7, '', 'http://www.tcpdf.org', '', true, 72);
            $pdf->Image($this->objAltConfig->getModulePath() . "pdfmaker/resources/images/unchkd.png", 14, 135, 8, 7, '', 'http://www.tcpdf.org', '', true, 72);
            $pdf->Image($this->objAltConfig->getModulePath() . "pdfmaker/resources/images/unchkd.png", 109, 135, 8, 7, '', 'http://www.tcpdf.org', '', true, 72);

//            $pdf->lastPage();
//            $pdf->writeHTMLCell(0, 0, 0, 0, $html = '<h1>Hey</h1>', 0, 0, 0, true, '');
//            $pdf->Output();
//            $pdf->Output();

            $objMail = $this->getObject('mailer', 'mail');
            $objMail->setValue('to', "wsifumba@gmail.com");
            $objMail->setValue('from', 'noreply@hermes');
            $objMail->setValue('fromName', 'Monwabisi');
            $objMail->setValue('subject', 'Leaves appliction');
//            $objMail->setValue('body', $pdf->Output("example_001.pdf", "I"));
//            $objMail->setValue('htmlbody', $pdf->Output("example_001.pdf", "I"));
//            $form->addToForm("<h2>{$this->objLanguage->languageText('phrase_norequests','system')}</h2>");
//            $objMail->send();
            return $form->show() . $this->addLeaveType();
            ;
        }
    }

    /**
     * Function to add  a user to the internals module
     * 
     * @param string $userId
     * @return object form
     */
    public function addUsers($userId) {
        $form = new form();
        $link = new link();
        $link->link = $this->objLanguage->languageText('phrase_registerlink', 'system');
        $link->href = $this->uri(array('action' => 'adduser', 'id' => $this->objUser->getUserId($this->objUser->userName())), 'internals');
        $form->addToForm($link->show());
        return $form->show();
    }

    /**
     * 
     * @return string or object||String is returned if the user has not registered yet
     */
    public function show() {
        $userId = $this->objUser->getUserId($this->objUser->userName());
        if ($this->dbInternals->valueExists('id', $userId, 'tbl_internals')) {
//                $form->addToForm($this->getjavascriptFile('internalsHelper.js','internals'));
            //show requests
            if ($this->objUser->isAdmin()) {
                return $this->buildAdminForm() . '<br />' . $this->buildUserForm();
            } else {
                //return user form if not admin
                return $this->buildUserForm();
            }
        } else {
            return "<h1>{$this->objLanguage->languageText('phrase_notregistered', 'system')}</h1>" . $this->addUsers($userId);
        }
    }

}

?>