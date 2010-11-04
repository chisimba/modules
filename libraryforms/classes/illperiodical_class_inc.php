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

        //---------text inputs and Labels--------------\\

	$this->loadClass('htmlheading', 'htmlelements');
	$periodHeading = new htmlheading();
	$periodHeading->type = 2;
	$periodHeading->str = $this->objLanguage->languageText("mod_libraryforms_commentperiodicalrequest","libraryforms","periodical");
	$objForm->addToForm($periodHeading->show()."<br/>");

        $title2Label = new label($this->objLanguage->languageText("mod_libraryforms_commentcommtnt2","libraryforms"),"title2");
        $objForm->addToForm($title2Label->show()."<br/>"."<br/>");

        $printLabel = new label($this->objLanguage->languageText("mod_libraryforms_commentprint","libraryforms"),"print");
        $objForm->addToForm($printLabel->show()."<br/>"."<br/>");
 
        $label2Label = new label($this->objLanguage->languageText("mod_libraryforms_commentlabel2","libraryforms"),"label2");
        $objForm->addToForm($label2Label->show()."<br/>"."<br/>");


        //Create a new textinput for the title
        $objperiodical = new textinput('title_periodical');
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
        $objForm->addRule('period_volume',$this->objLanguage->languageText("mod_volume2_required", "libraryforms"),'required');

        //Create a new textinput for part
        $objpart = new textinput('period_part');
        $partLabel = new label($this->objLanguage->languageText("mod_libraryforms_commentpart","libraryforms"),"part");
        $table->addCell($partLabel->show(), '', 'center', 'left', '');
        $table->addCell($objpart->show(), '', 'center', 'left', '');
        $objForm->addRule('period_part',$this->objLanguage->languageText("mod_part2_required", "libraryforms"),'required');


        //Create a new textinput for year
        $objyear = new textinput('period_year');
        $yearlLabel = new label($this->objLanguage->languageText("mod_libraryforms_commentyear","libraryforms"),"year");
        $table->addCell($yearlLabel->show(), '', 'center', 'left', '');
        $table->addCell($objyear->show(), '', 'center', 'left', '');
        $objForm->addRule('period_year',$this->objLanguage->languageText("mod_year2_required", "libraryforms"),'required');


        //Create a new textinput for pages
        $objpages = new textinput('period_pages');
        $pageslLabel = new label($this->objLanguage->languageText("mod_libraryforms_commentpages","libraryforms"),"pages");
        $table->addCell($pageslLabel->show(), '', 'center', 'left', '');
        $table->addCell($objpages->show(), '', 'center', 'left', '');
       $objForm->addRule('period_pages','pages Must contain valid numbers','numeric');
        $table->endRow();


        //Create a new textinput for author
        $objauthor = new textinput('period_author');
        $authorLabel = new label($this->objLanguage->languageText("mod_libraryforms_commentauthor","libraryforms"),"author");
        $table->startRow();
        $table->addCell($authorLabel->show(), '', 'center', 'left', '');
        $table->addCell($objauthor->show(), '', 'center', 'left', '');
        $table->endRow();
        $objForm->addRule('period_author',$this->objLanguage->languageText("mod_author2_required", "libraryforms"),'required');

        $titarticle =new textinput('periodical_titlearticle');
        $reqLabel = new label($this->objLanguage->languageText("mod_libraryforms_commentarticle","libraryforms"),"request");
        $table->startRow();
        $table->addCell($reqLabel->show(), '', 'center', 'left', '');
 	$table->addCell($titarticle->show(), '', 'center', 'left', '');
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
        $objemail = new textinput('periodicalemail');
        $emailLabel = new label($this->objLanguage->languageText("mod_libraryforms_commentstudentemail","libraryforms"),"email");

        $table->addCell($emailLabel->show(), '', 'center', 'left', '');
        $table->addCell($objemail->show(), '', 'center', 'left', '');
        $objForm->addRule('periodicalemaill', 'Not a valid Email', 'email');
        $table->endRow();



        //Create a new textinput for entity
        $objentity = new textinput('periodical_entity');
        $entityLabel = new label($this->objLanguage->languageText("mod_libraryforms_commentcharge","libraryforms"),"periodical_entity");
        $table->addCell($entityLabel->show(), '', 'center', 'left', '');
        $table->addCell($objentity->show(), '', 'center', 'left', '');

        //Create a new textinput for student no
        $objstud = new textinput('periodical_student');
        $studLabel = new label($this->objLanguage->languageText("mod_libraryforms_commentstudentno","libraryforms"),"periodical_student");
        $table->addCell($studLabel->show(), '', 'center', 'left', '');
        $table->addCell($objstud->show(), '', 'center', 'left', '');
        $objForm->addRule(array('periodical_student'=>'periodical_student','length'=>10), 'Your Studentno is too long','maxlength');
        $table->endRow();


        //Create a new textinput for course
        $objcourse = new textinput('periodical_course');
        $courseLabel = new label($this->objLanguage->languageText("mod_libraryforms_commentstudentcourse2","libraryforms"),"periodical_course");
        $table->addCell($courseLabel->show(), '', 'center', 'left', '');
        $table->addCell($objcourse->show(), '', 'center', 'left', '');
        $objForm->addRule('periodical_course',$this->objLanguage->languageText("mod_course_required", "libraryforms"),'required');
        $table->endRow();


         //create an istance for the label
        $labellLabel = new label($this->objLanguage->languageText("mod_libraryforms_commentlabel","libraryforms"),"label");
        $table->startRow();
        $table->addCell($labellLabel->show(), '', 'center', 'left', '');
        $table->endRow();

  	$table->startRow();
        $objCheck = new checkbox('arrayList[]');
        $objCheck->setValue($userPerm['id']);
        
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
        $faxLabel = new label($this->objLanguage->languageText("mod_libraryforms_commentfax","libraryforms"),"fax");
        $table->addCell($objCheck3->show(), '', 'center', 'left', '');
        $table->addCell($faxLabel->show(), '', 'center', 'left', '');

        //Create a new textinput for fax
        $objCheck4 = new checkbox('arrayList[]');
        $objCheck4->setValue($userPerm['id']);
       
        //Create a new label for pg
        $uwcLabel = new label($this->objLanguage->languageText("mod_libraryforms_commentuwc","libraryforms"),"uwc");
        $table->addCell($objCheck4->show(), '', 'center', 'left', '');
        $table->addCell($uwcLabel->show(), '', 'center', 'left', '');
        $table->endRow();

        $objCheck5 = new checkbox('arrayList[]');
        $objCheck5->setValue($userPerm['id']);
      

        //Create a new label for ug
        $pgLabel = new label($this->objLanguage->languageText("mod_libraryforms_commentpg","libraryforms"),"pg");
        $table->startRow();
        $table->addCell($objCheck5->show(), '', 'center', 'left', '');
        $table->addCell($pgLabel->show(), '', 'center', 'left', '');

        $objCheck6 = new checkbox('arrayList[]');
        $objCheck6->setValue($userPerm['id']);
        $ugLabel = new label($this->objLanguage->languageText("mod_libraryforms_commentug","libraryforms"),"ug");
        $table->addCell($objCheck6->show(), '', 'center', 'left', '');
        $table->addCell($ugLabel->show(), '', 'center', 'left', '');

        $objCheck7 = new checkbox('arrayList[]');
        $objCheck7->setValue($userPerm['id']);
        $staffLabel = new label($this->objLanguage->languageText("mod_libraryforms_commentstaff","libraryforms"),"staff");
        $table->addCell($objCheck7->show(), '', 'center', 'left', '');
        $table->addCell($staffLabel->show(), '', 'center', 'left', '');
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
	$captchaLabel = new label($this->objLanguage->languageText('phrase_verifyrequest', 'security', 'Verify Request'), 'input_periodical_captcha');
 	        
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

    function insertperiodicalRecord($titleperiodical, $volume, $part, $year, $pages, $author, $titlearticle, $prof, $address, $cell, $tell,$tellw, $emailaddress,$entitynum,$studentno,$course) {
        $id = $this->insert(array(
       
            'ptitleperiodical' => $titleperiodical,
            'pvolume' => $volume,
            'ppart' => $part,
            'pyear' => $year,
            'ppages' => $pages,
            'pauthor' =>$author,
            'ptitlearticle' => $titlearticle,
            'pprof' => $prof,
            'paddress' => $address,
            'pcell' => $cell,
            'ptell' => $tell,
            'ptellw' => $tellw,
            'pemailaddress' => $emailaddress,
            'pentitynum' => $entitynum,
            'pstudentno' => $studentno,
            'pcourse' => $course,
            
        ));
        return $id;
    }

private function getFormAction() {
        $formAction = $this->uri(array("action" => "save_periodical"), "libraryforms");
         return $formAction;


    }
    public function show() {
        return $this->buildForm();

    }
}

