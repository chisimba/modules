<?php

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
$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

/**
 *
 *libraryforms
 *
 * libraryforms allows students or distant user to request books online
 *
 * @category  Chisimba
 * @package   libraryforms
 * @author    Brenda Mayinga brendamayinga@ymail.com
 */

class ILLperiodical extends dbTable {

    public $objLanguage;


    public function init() {
        $this->objLanguage = $this->getObject('language', 'language');
        parent::init('tbl_illperiodical');

    }
    private function loadElements() {
    //Load the form class
        $this->loadClass('form','htmlelements');
        //Load the textinput class
        $this->loadClass('textinput','htmlelements');
        //Load the textarea class
        //$this->loadClass('textarea','htmlelements');
        //Load the label class
        $this->loadClass('label', 'htmlelements');
        //Load the button object
        $this->loadClass('button', 'htmlelements');
        //load the checkbox object
        $this->loadClass('checkbox', 'htmlelements');

        $strjs = '<script type="text/javascript">
        /***********************************************
        *                                              *
        *           ILLPERIODICAL CLASS                *
        *                                              *
        ***********************************************/
		//<![CDATA[
		function init () {
			$(\'input_illperiodicalredraw\').onclick = function () {
				illperiodicalredraw();
			}
		}
		function illperiodicalredraw () {
			var url = \'index.php\';
			var pars = \'module=security&action=generatenewcaptcha\';
	var myAjax = new Ajax.Request( url, {method: \'get\', parameters: pars, onComplete: illperiodicalShowResponse} );
		}
		function illperiodicalShowLoad () {
			$(\'load\').style.display = \'block\';
		}
		function illperiodicalShowResponse (originalRequest) {
			var newData = originalRequest.responseText;
			$(\'illperiodicalcaptchaDiv\').innerHTML = newData;
		}
		//]]>
		</script>';
        $this->appendArrayVar('headerParams', $strjs);
    }
    private function buildForm() {
    //Load the required form elements that we need
        $this->loadElements();
        $table = $this->newObject('htmltable', 'htmlelements');
        //Create the form
        $objForm = new form('periodical', $this->getFormAction());

        //----------TEXT INPUT and Labels--------------

        $titleLabel = new label($this->objLanguage->languageText("mod_libraryforms_commentperiodicalrequest","libraryforms"),"title2");
        $objForm->addToForm($titleLabel->show()."<br/>");

        $title2Label = new label($this->objLanguage->languageText("mod_libraryforms_commentcommtnt2","libraryforms"),"title2");
        $objForm->addToForm($title2Label->show()."<br/>");



        $printLabel = new label($this->objLanguage->languageText("mod_libraryforms_commentprint","libraryforms"),"print");
        $objForm->addToForm($printLabel->show()."<br/>");


        //Create a new textinput for the title
        $objperiodical = new textinput('titleperiodical');
        $periodicalLabel = new label($this->objLanguage->languageText("mod_libraryforms_commenttitleperiod","libraryforms"),"titleperiodical");
        $table->startRow();
        $table->addCell($periodicalLabel->show(), '', 'center', 'left', '');
        $table->addCell($objperiodical->show(), '', 'center', 'left', '');
        $table->endRow();



        //Create a new textinput for postal
        $objvolume = new textinput('period_volume');
        $volumeLabel = new label($this->objLanguage->languageText
            ("mod_libraryforms_commentvolume","libraryforms"),"volume");
        $table->startRow();
        $table->addCell($volumeLabel->show(), '', 'center', 'left', '');
        $table->addCell($objvolume->show(), '', 'center', 'left', '');
        $objForm->addRule('period_volume',$this->objLanguage->languageText("mod_volume2_required", "libraryforms", 'Please enter a volume.Volume is missing.'),'required');

        //Create a new textinput for part
        $objpart = new textinput('period_part');
        $partLabel = new label($this->objLanguage->languageText("mod_libraryforms_commentpart","libraryforms"),"part");
        $table->addCell($partLabel->show(), '', 'center', 'left', '');
        $table->addCell($objpart->show(), '', 'center', 'left', '');
        $objForm->addRule('period_part',$this->objLanguage->languageText("mod_part2_required", "libraryforms", 'Please enter a part.part is missing.'),'required');


        //Create a new textinput for year
        $objyear = new textinput('period_year');
        $yearlLabel = new label($this->objLanguage->languageText("mod_libraryforms_commentyear","libraryforms"),"year");
        $table->addCell($yearlLabel->show(), '', 'center', 'left', '');
        $table->addCell($objyear->show(), '', 'center', 'left', '');
        $objForm->addRule('period_year',$this->objLanguage->languageText("mod_year2_required", "libraryforms", 'Please enter a year.year is missing.'),'required');


        //Create a new textinput for pages
        $objpages = new textinput('period_pages');
        $pageslLabel = new label($this->objLanguage->languageText("mod_libraryforms_commentpages","libraryforms"),"pages");
        $table->addCell($pageslLabel->show(), '', 'center', 'left', '');
        $table->addCell($objpages->show(), '', 'center', 'left', '');
        $objForm->addRule('period_pages',$this->objLanguage->languageText("mod_pages_required", "libraryforms", 'Please enter a page.page is missing.'),'required');
        $table->endRow();



        //Create a new textinput for author
        $objauthor = new textinput('period_author');
        $authorLabel = new label($this->objLanguage->languageText("mod_libraryforms_commentauthor","libraryforms"),"author");
        $table->startRow();
        $table->addCell($authorLabel->show(), '', 'center', 'left', '');
        $table->addCell($objauthor->show(), '', 'center', 'left', '');
        $table->endRow();
        $objForm->addRule('period_author',$this->objLanguage->languageText("mod_author_required", "libraryforms", 'Please enter an author. author is missing.'),'required');


        //create an istance for the Label
        $labellLabel = new label($this->objLanguage->languageText("mod_libraryforms_commentlabel","libraryforms"),"label");
        $table->startRow();
        $table->addCell($labellLabel->show(), '', 'center', 'left', '');



        $objCheck = new checkbox('arrayList[]');
        $objCheck->setValue($userPerm['id']);
        $objCheck->extra = "onclick=\"javascript: ToggleMainBox('select', 'toggle', this.checked);\"";
        //Create a new label for oversears
        $localLabel = new label($this->objLanguage->languageText("mod_libraryforms_commentlocalonly","libraryforms"),"local");
        $table->addCell($objCheck->show(), '', 'center', 'left', '');
        $table->addCell($localLabel->show(), '', 'center', 'left', '');
        $objForm->addRule('id',$this->objLanguage->languageText("mod_part_required", "libraryforms", 'Please select the check box. checkbox not ticked.'),'required');



        $objCheck2 = new checkbox('arrayList[]');
        $objCheck2->setValue($userPerm['id']);
        $objCheck2->extra = "onclick=\"javascript: ToggleMainBox('select', 'toggle', this.checked);\"";

        //Create a new label for oversears
        $overseaLabel = new label($this->objLanguage->languageText("mod_libraryforms_commentoverseas","libraryforms"),"overseas");
        $table->addCell($objCheck2->show(), '', 'center', 'left', '');
        $table->addCell($overseaLabel->show(), '', 'center', 'left', '');



        $objCheck3 = new checkbox('arrayList[]');
        $objCheck3->setValue($userPerm['id']);
        $objCheck3->extra = "onclick=\"javascript: ToggleMainBox('select', 'toggle', this.checked);\"";

        $faxLabel = new label($this->objLanguage->languageText("mod_libraryforms_commentfax","libraryforms"),"fax");
        $table->addCell($objCheck3->show(), '', 'center', 'left', '');
        $table->addCell($faxLabel->show(), '', 'center', 'left', '');

        //Create a new textinput for fax
        $objCheck4 = new checkbox('arrayList[]');
        $objCheck4->setValue($userPerm['id']);
        $objCheck4->extra = "onclick=\"javascript: ToggleMainBox('select', 'toggle', this.checked);\"";
        //Create a new label for pg
        $uwcLabel = new label($this->objLanguage->languageText("mod_libraryforms_commentuwc","libraryforms"),"uwc");
        $table->addCell($objCheck4->show(), '', 'center', 'left', '');
        $table->addCell($uwcLabel->show(), '', 'center', 'left', '');
        $table->endRow();

        $objCheck5 = new checkbox('arrayList[]');
        $objCheck5->setValue($userPerm['id']);
        $objCheck5->extra = "onclick=\"javascript: ToggleMainBox('select', 'toggle', this.checked);\"";

        //Create a new label for ug
        $pgLabel = new label($this->objLanguage->languageText("mod_libraryforms_commentpg","libraryforms"),"pg");
        $table->startRow();
        $table->addCell($objCheck5->show(), '', 'center', 'left', '');
        $table->addCell($pgLabel->show(), '', 'center', 'left', '');




        $objCheck6 = new checkbox('arrayList[]');
        $objCheck6->setValue($userPerm['id']);
        $objCheck6->extra = "onclick=\"javascript: ToggleMainBox('select', 'toggle', this.checked);\"";
        $ugLabel = new label($this->objLanguage->languageText("mod_libraryforms_commentug","libraryforms"),"ug");
        $table->addCell($objCheck6->show(), '', 'center', 'left', '');
        $table->addCell($ugLabel->show(), '', 'center', 'left', '');

        $objCheck7 = new checkbox('arrayList[]');
        $objCheck7->setValue($userPerm['id']);
        $objCheck7->extra = "onclick=\"javascript: ToggleMainBox('select', 'toggle', this.checked);\"";
        $staffLabel = new label($this->objLanguage->languageText("mod_libraryforms_commentstaff","libraryforms"),"staff");
        $table->addCell($objCheck7->show(), '', 'center', 'left', '');
        $table->addCell($staffLabel->show(), '', 'center', 'left', '');
        $table->endRow();

        $label2Label = new label($this->objLanguage->languageText("mod_libraryforms_commentlabel2","libraryforms"),"label2");
        $table->startRow();
        $table->addCell($label2Label->show(), '', 'center', 'left', '');
        $table->endRow();

        $reqLabel = new label($this->objLanguage->languageText("mod_libraryforms_commentrequest","libraryforms"),"request");
        $table->startRow();
        $table->addCell($reqLabel->show(), '', 'center', 'left', '');
        $table->endRow();


        $objprof = new textinput('periodical_prof');
        $profLabel = new label($this->objLanguage->languageText("mod_libraryforms_commentprof","libraryforms"),"periodical_pro");
        $table->startRow();
        $table->addCell($profLabel->show(), '', 'center', 'left', '');
        $table->addCell($objprof->show(), '', 'center', 'left', '');
        $table->endRow();


        $objadd = new textinput('periodical_address');
        $addLabel = new label($this->objLanguage->languageText("mod_libraryforms_commentaddress","libraryforms"),"periodical_address");
        $table->startRow();
        $table->addCell($addLabel->show(), '', 'center', 'left', '');
        $table->addCell($objadd->show(), '', 'center', 'left', '');
        $table->endRow();


        $objcell = new textinput('period_cell');
        $cellLabel = new label($this->objLanguage->languageText("mod_libraryforms_commentstudentcell","libraryforms"),"periodical_cell");
        $table->startRow();
        $table->addCell($cellLabel->show(), '', 'center', 'left', '');
        $table->addCell($objcell->show(), '', 'center', 'left', '');
        $objForm->addRule('period_cell','cell Must contain valid numbers','numeric');

        $objtel = new textinput('periodical_tell');
        $telLabel = new label($this->objLanguage->languageText("mod_libraryforms_commenttele","libraryforms"),"periodical_tell");
        $table->addCell($telLabel->show(), '', 'center', 'left', '');
        $table->addCell($objtel->show(), '', 'center', 'left', '');


        $objw = new textinput('periodical_w');
        $wLabel = new label($this->objLanguage->languageText("mod_libraryforms_commentW","libraryforms"),"periodical_w");
        $table->addCell($wLabel->show(), '', 'center', 'left', '');
        $table->addCell($objw->show(), '', 'center', 'left', '');

        //Create a new textinput for email
        $objemail = new textinput('email_periodical');
        $emailLabel = new label($this->objLanguage->languageText("mod_libraryforms_commentstudentemail","libraryforms"),"email_periodical");

        $table->addCell($emailLabel->show(), '', 'center', 'left', '');
        $table->addCell($objemail->show(), '', 'center', 'left', '');
        $objForm->addRule('email_periodical', 'Not a valid Email', 'email_periodical');

        $table->endRow();



        //Create a new textinput for email
        $objentity = new textinput('periodical_entity');
        $entityLabel = new label($this->objLanguage->languageText("mod_libraryforms_commentcharge","libraryforms"),"periodical_entity");
        $table->addCell($entityLabel->show(), '', 'center', 'left', '');
        $table->addCell($objentity->show(), '', 'center', 'left', '');

        //Create a new textinput for email
        $objstud = new textinput('periodical_student');
        $studLabel = new label($this->objLanguage->languageText("mod_libraryforms_commentstudentno","libraryforms"),"periodical_student");
        $table->addCell($studLabel->show(), '', 'center', 'left', '');
        $table->addCell($objstud->show(), '', 'center', 'left', '');
        $objForm->addRule(array('name'=>'periodical_student','length'=>10), 'Your Studentno is too long','maxlength');
        $table->endRow();


        //Create a new textinput for email
        $objcourse = new textinput('periodical_course');
        $courseLabel = new label($this->objLanguage->languageText("mod_libraryforms_commentstudentcourse2","libraryforms"),"periodical_course");
        $table->addCell($courseLabel->show(), '', 'center', 'left', '');
        $table->addCell($objcourse->show(), '', 'center', 'left', '');
        $objForm->addRule('periodical_course',$this->objLanguage->languageText("mod_course_required", "libraryforms", 'Please enter an course. course is missing.'),'required');
        $table->endRow();

        //----------SUBMIT BUTTON--------------
        //Create a button for submitting the form
        $objButton = new button('save');
        // Set the button type to submit
        $objButton->setToSubmit();
        // Use the language object to label button
        // with the word save
        $objButton->setValue(' '.$this->objLanguage->languageText("mod_libraryforms_savecomment", "libraryforms").' ');

	$objForm->addToForm($table->show());
	        // $objForm->addToForm($objButton->show());

		$objCaptcha = $this->getObject('captcha', 'utilities');
 	        $captcha = new textinput('periodical_captcha');
	        $captchaLabel = new label($this->objLanguage->languageText('phrase_verifyrequest', 'security', 'Verify 			Request'), 'input_periodical_captcha');
 	        
          $strutil = stripslashes($this->objLanguage->languageText('mod_security_explaincaptcha', 'security', 'To prevent abuse, please enter the code as shown below. If you are unable to view the code, click on "Redraw" for a new one.')).'<br /><div id="illperiodicalcaptchaDiv">'.$objCaptcha->show().'</div>'.$captcha->show().$required.'  <a href="javascript:illperiodicalredraw();">'.$this->objLanguage->languageText('word_redraw', 'security', 'Redraw').'</a>';

        $objForm->addToForm('<br/><br/>'.$strutil.'<br/><br/>');
		$objForm->addRule('periodical_captcha',$this->objLanguage->languageText("mod_request_captcha_unrequired", 'security', 'Captcha cant be empty.Captcha is missing.'),'required');
        $objForm->addToForm($objButton->show());


        return $objForm->show();

    }

    public function listAll($userId) {
        $userrec = $this->getAll("WHERE userid = '$userId'");
        return $userrec;
    }
   
    public function listSingle($id) {
        $onerec = $this->getRow('id', $id);
        return $onerec;
    }

    function insertRecord($titleperiodical, $volume, $part, $year, $pages, $author, $titlearticle, $prof, $address, $cell,$tell,$tellw, $emailaddress,$entitynum,$studentno,$course) {
        $id = $this->insert(array(
            //'userid' => $userid,
            'titleperiodical' => $titleperiodical,
            'volume' => $volume,
            'part' => $part,
            'year' => $year,
            'pages' => $pages,
            'author' =>$author,
            'titlearticle' => $titlearticle,
            'prof' => $prof,
            'address' => $address,
            'fax' => $fax,
            'tell' => $tell,
            'tellw' => $tellw,
            'emailaddress' => $emailaddress,
            //'address' => $address,
            'entitynum' => $entitynum,
            'studentno' => $studentno,
            'course' => $course,
            // 'department'=>$department,
            /*'btelw' => $btelw,
            'bemailaddress' => $bemailaddress,
            'bentitynum' => $bentitynum,
            'bstudentno' => $bstudentno,
            'bcourse' => $bcourse,*/
        ));
        return $id;
    }

    function updateRecord($titleperiodical, $volume, $part, $year, $pages, $author, $titlearticle, $prof, $address, $cell,$tell,$tellw, $emailaddress,$entitynum,$studentno,$course) {
        $id = $this->update(array(
            //'userid' => $userid,
            'titleperiodical' => $titleperiodical,
            'volume' => $volume,
            'part' => $part,
            'year' => $year,
            'pages' => $pages,
            'author' =>$author,
            'titlearticle' => $titlearticle,
            //'createdormodified' => date('Y-m-d, h:i:s') ,
            'prof' => $prof,
            'address' => $address,
            'fax' => $fax,
            'tell' => $tell,
            'tellw' => $tellw,
            'emailaddress' => $emailaddress,
            //'address' => $address,
            'entitynum' => $entitynum,
            'studentno' => $studentno,
            'course' => $course,
            //'department'=>$department,
        ));
        return $id;
    }

    private function getFormAction() {

        $action = $this->getParam("action", "add");
        if ($action == "edit") {
            $formAction = $this->uri(array("action" => "update"), "libraryforms");
        } else {
            $formAction = $this->uri(array("action" => "add"), "libraryforms");
        }
        return $formAction;


    }
    public function show() {
        return $this->buildForm();

    }
}

