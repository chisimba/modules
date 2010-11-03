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

class bookthesis extends dbTable {

    public $objLanguage;


    public function init() {
        $this->objLanguage = $this->getObject('language', 'language');
        parent::init('tbl_booksthesis');

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
         // load the icon
	$objIcon =  $this->newObject('geticon', 'htmlelements');
        //Load the button object
        $this->loadClass('button', 'htmlelements');
        //load the checkbox object
        $this->loadClass('checkbox', 'htmlelements');
        // load the fieldset
	$this->loadClass('fieldset', 'htmlelements');

    $strjs = '<script type="text/javascript">

     
		//<![CDATA[
        /***********************************************
        *                                              *
        *            BOOKTHESIS CLASS                  *
        *                                              *
        ************************************************/
		function init () {
			$(\'input_bookthesisredraw\').onclick = function () {
				bookthesisredraw();
			}
		}
		function bookthesisredraw () {
			var url = \'index.php\';
			var pars = \'module=security&action=generatenewcaptcha\';
		var myAjax = new Ajax.Request( url, {method: \'get\', parameters: pars, onComplete: bookthesisShowResponse} );
		}
		function bookthesisShowLoad () {
			$(\'load\').style.display = \'block\';
		}
		function bookthesisShowResponse (originalRequest) {
			var newData = originalRequest.responseText;
			$(\'bookthesiscaptchaDiv\').innerHTML = newData;
		}
		//]]>
		</script>';


        $this->appendArrayVar('headerParams', $strjs);

    }
    private function buildForm() {
    //Load the required form elements that we need
        $this->loadElements();
        $table = $this->newObject('htmltable', 'htmlelements');
        
        // get icon
	$objIcon = $this->newObject('geticon', 'htmlelements');
        //Create the form
        $objForm = new form('bookthesis', $this->getFormAction());

        //----------TEXT INPUT and Labels--------------
        
       //*****//	
	$this->loadClass('htmlheading', 'htmlelements');
	$Heading = new htmlheading();
	$Heading->type = 2;
	$Heading->str = $this->objLanguage->languageText("mod_libraryforms_commentcomment","libraryforms","title2");
	$objForm->addToForm($Heading->show()."<br/>");
  	
  	$printLabel = new label($this->objLanguage->languageText("mod_libraryforms_commenttype","libraryforms"),"titleL");
   	$objForm->addToForm($printLabel->show()."<br/>"."<br/>");

 
        $label1 = new label($this->objLanguage->languageText
            ("mod_libraryforms_commentlabell","libraryforms"),"label1");
        $objForm->addToForm($label1->show()."<br/>"."<br/>");
        

         
        $label2 = new label($this->objLanguage->languageText
            ("mod_libraryforms_commentlabell2","libraryforms"),"label2");
       $objForm->addToForm($label2->show()."<br/>"."<br/>");

  

        // create label and text box for author
        $table->startRow();
	$objaut = new textinput('aut');
        $autLabel = new label($this->objLanguage->languageText("mod_libraryforms_commentauthor2","libraryforms"),"aut");
        $table->addCell($autLabel ->show(), '', 'center', 'left', '');
        $table->addCell($objaut ->show(), '', 'center', 'left', '');
        $objForm->addRule('aut',$this->objLanguage->languageText("mod_author_required", "libraryforms"),'required');
        $table->endRow();



        //Create a new textinput for the title
        $table->startRow();
	$objtit = new textinput('thesis_titles');
        $titLabel = new label($this->objLanguage->languageText("mod_libraryforms_commenttitle2","libraryforms"),"tit");
        $table->addCell($titLabel->show(), '', 'center', 'left', '');
        $table->addCell($objtit ->show(), '', 'center', 'left', '');
    $objForm->addRule('thesis_titles',$this->objLanguage->languageText("mod_title_unrequired", "libraryforms"),'required');
        $table->endRow();


        //Create a new textinput and Label for Place        
        $table->startRow();
	$objplace = new textinput('thesis_place');
        $placeLabel = new label($this->objLanguage->languageText
            ("mod_libraryforms_commentplace","libraryforms"),"thesis_place");
        $table->addCell($placeLabel->show(), '', 'center', 'left', '');
        $table->addCell($objplace ->show(), '', 'center', 'left', '');
       $objForm->addRule('thesis_place',$this->objLanguage->languageText("mod_place_required", "libraryforms"),'required');



        //Create a new textinput for publisher
        $objpub = new textinput('thesis_publisher');
        $pubLabel = new label($this->objLanguage->languageText("mod_libraryforms_commentpublisher","libraryforms"),"thesis_publisher");
        // $table->startRow();
        $table->addCell($pubLabel->show(), '', 'center', 'left', '');
        $table->addCell($objpub->show(), '', 'center', 'left', '');
        $objForm->addRule('thesis_publisher',$this->objLanguage->languageText("mod_publisher_required", "libraryforms", 'Please enter  publisher. Publisher missing.'),'required');



        //Create a new textinput for year
        $objdate = new textinput('year');
        $dateLabel = new label($this->objLanguage->languageText("mod_libraryforms_commentdateleperiod","libraryforms"),"date");
        //$table->startRow();
        $table->addCell($dateLabel->show(), '', 'center', 'left', '');
        $table->addCell($objdate->show(), '', 'center', 'left', '');
        $objForm->addRule('year',$this->objLanguage->languageText("mod_year_required", "libraryforms"),'required');
        $table->endRow();

        //Create a new textinput for edition
        $objedition = new textinput('edition');
        $editionLabel = new label($this->objLanguage->languageText("mod_libraryforms_commentedition","libraryforms"),"edit");
        $table->startRow();
        $table->addCell($editionLabel->show(), '', 'center', 'left', '');
        $table->addCell($objedition ->show(), '', 'center', 'left', '');
        $objForm->addRule('edition',$this->objLanguage->languageText("mod_edition_required", 'libraryforms', 'Please enter a edition. Edition is missing.'),'required');



        //Create a new textinput for ISBN
        $objisbn = new textinput('ISBN');
        $isbnLabel = new label($this->objLanguage->languageText("mod_libraryforms_commentISBN","libraryforms"),"ISBN");
        $table->addCell($isbnLabel->show(), '', 'center', 'left', '');
        $table->addCell($objisbn->show(), '', 'center', 'left', '');
        $objForm->addRule('ISBN',$this->objLanguage->languageText("mod_ISBN_required", "libraryforms"),'required');


        //create text box and Label for series
        $objseries = new textinput('series');
        $serieslLabel = new label($this->objLanguage->languageText
            ("mod_libraryforms_commentseries","libraryforms"),"series");
        $table->addCell($serieslLabel->show(), '', 'center', 'left', '');
        $table->addCell($objseries->show(), '', 'center', 'left', '');
                 
        //create an istance for the photocopy
       
        $table->startRow();
        $objphoto = new textinput('photocopy');
        $photolLabel = new label($this->objLanguage->languageText
            ("mod_libraryforms_commentphotocopy","libraryforms"),"photocopy");
        $table->addCell($photolLabel->show(), '', 'center', 'left', '');
        $table->addCell($objphoto->show(), '', 'center', 'left', '');
        $table->endRow();

      

      // create a textbox and label for title 
        $table->startRow();
        $objtit2 = new textinput('titles');
        $tit2lLabel = new label($this->objLanguage->languageText
            ("mod_libraryforms_commenttitle3","libraryforms"),"titles");
        $table->addCell($tit2lLabel->show(), '', 'center', 'left', '');
        $table->addCell($objtit2 ->show(), '', 'center', 'left', '');
        $table->endRow();
        $objForm->addRule('titles',$this->objLanguage->languageText("mod_titlebook_required", "libraryforms", 'Please enter book title. Title is missing.'),'required');

         //create textbox and label for pages
        $objpag = new textinput('pages');
        $pagLabel = new label($this->objLanguage->languageText
            ("mod_libraryforms_commenttitlepages2","libraryforms"),"Pages");
        $table->startRow();
        $table->addCell($pagLabel->show(), '', 'center', 'left', '');
        $table->addCell($objpag->show(), '', 'center', 'left', '');
        $table->endRow();


        //create textbox and label for thesis    
        $table->startRow();
 	$objthes = new textinput('thesis');
        $thesLabel = new label($this->objLanguage->languageText
            ("mod_libraryforms_commenttheses","libraryforms"),"thesis");
        $table->addCell($thesLabel->show(), '', 'center', 'left', '');
        $table->addCell($objthes->show(), '', 'center', 'left', '');
        $objForm->addRule('thesis',$this->objLanguage->languageText("mod_thesis_required", "libraryforms"),'required');
        $table->endRow();

          

        $objprof = new textinput('thesis_prof');
        $profLabel = new label($this->objLanguage->languageText("mod_libraryforms_commentprof","libraryforms"),"thesis_prof");
        $table->startRow();
        $table->addCell($profLabel->show(), '', 'center', 'left', '');
        $table->addCell($objprof->show(), '', 'center', 'left', '');
        $table->endRow();

        $objadd = new textinput('thesis_address');
        $addLabel = new label($this->objLanguage->languageText("mod_libraryforms_commentaddress","libraryforms"),"thesis_address");
        $table->startRow();
        $table->addCell($addLabel->show(), '', 'center', 'left', '');
        $table->addCell($objadd->show(), '', 'center', 'left', '');
        $table->endRow();

        $objcell = new textinput('thesis_cell');
        $cellLabel = new label($this->objLanguage->languageText("mod_libraryforms_commentstudentcell","libraryforms"),"thesis_cell");
        $table->startRow();
        $table->addCell($cellLabel->show(), '', 'center', 'left', '');
        $table->addCell($objcell->show(), '', 'center', 'left', '');
	$objForm->addRule('thesis_cell','cell Must contain valid numbers','numeric');

        $objtel = new textinput('thesis_tel');
        $telLabel = new label($this->objLanguage->languageText("mod_libraryforms_commenttele","libraryforms"),"thesis_tel");
        $table->addCell($telLabel->show(), '', 'center', 'left', '');
        $table->addCell($objtel->show(), '', 'center', 'left', '');


        $objw = new textinput('thesis_w');
        $wLabel = new label($this->objLanguage->languageText("mod_libraryforms_commentW","libraryforms"),"thesis_w");
        $table->addCell($wLabel->show(), '', 'center', 'left', '');
        $table->addCell($objw->show(), '', 'center', 'left', '');



        //Create a new textinput for email
        $objemail = new textinput('thesis_email');
        $emailLabel = new label($this->objLanguage->languageText("mod_libraryforms_commentstudentemail","libraryforms"),"thesisemail");
        $table->addCell($emailLabel->show(), '', 'center', 'left', '');
        $table->addCell($objemail->show(), '', 'center', 'left', '');
        $objForm->addRule('thesis_email', 'Not a valid Email', 'email');
        $table->endRow();


        //Create a new textinput for entity
        $objentity = new textinput('entity');
        $entityLabel = new label($this->objLanguage->languageText("mod_libraryforms_commentcharge","libraryforms"),"entity");
        $table->startRow();
        $table->addCell($entityLabel->show(), '', 'center', 'left', '');
        $table->addCell($objentity->show(), '', 'center', 'left', '');


        //Create a new textinput for student no
        $objstud = new textinput('thesis_studentno');
        $studLabel = new label($this->objLanguage->languageText("mod_libraryforms_commentstudentno2","libraryforms"),"studentno");
        $table->addCell($studLabel->show(), '', 'center', 'left', '');
        $table->addCell($objstud->show(), '', 'center', 'left', '');
        $objForm->addRule(array('name'=>'thesis_studentno','length'=>15), 'Your surname is too long',
	'maxlength');


        //Create a new textinput for course
        $objcourse2 = new textinput('thesis_course');
        $course2Label = new label($this->objLanguage->languageText("mod_libraryforms_commentstudentcourse2","libraryforms"),"course");
        $table->addCell($course2Label->show(), '', 'center', 'left', '');
        $table->addCell($objcourse2->show(), '', 'center', 'left', '');
        $objForm->addRule('thesis_course',$this->objLanguage->languageText("mod_thesiscourse_required", "libraryforms"),'required');
       
        // create label for the box heading
	$bookbLabel = new label($this->objLanguage->languageText
            ("mod_libraryforms_commenttitlebox","libraryforms"),"box");
        $table->startRow();
        $table->addCell($bookbLabel->show(), '', 'center', 'left','');
     
              
         
        $table->startRow();
        $objCheck = new checkbox('arrayList[]');
        $objCheck->setValue($userPerm['id']);
        $objCheck->extra = "onclick=\"javascript: ToggleMainBox('select', 'toggle', this.checked);\"";
      
        $uwcbLabel = new label($this->objLanguage->languageText("mod_libraryforms_commentuwc","libraryforms"),"uwc");
        $table->addCell($objCheck->show(), '', 'center', 'left', '');
        $table->addCell($uwcbLabel->show(), '', 'center', 'left', '');
        $objForm->addRule('arrayList[]',$this->objLanguage->languageText("mod_surname_required", "libraryforms", 'Please enter a title. please tick one of the box.'),'required');

        //
        // create checkbox local only
        $objCheck2 = new checkbox('arrayList[]');
        $objCheck2->setValue($userPerm['id']);
        $objCheck2->extra = "onclick=\"javascript: ToggleMainBox('select', 'toggle', this.checked);\"";
        $bLabel = new label($this->objLanguage->languageText("mod_libraryforms_commentlocalonly","libraryforms"),"local");
        $table->addCell($objCheck2->show(), '', 'center', 'left', '');
        $table->addCell($bLabel->show(), '', 'center', 'left', '');


        $objCheck3 = new checkbox('arrayList[]');
        $objCheck3->setValue($userPerm['id']);
        $objCheck3->extra = "onclick=\"javascript: ToggleMainBox('select', 'toggle', this.checked);\"";
        //Create a new label for oversears
        $overLabel = new label($this->objLanguage->languageText("mod_libraryforms_commentoverseas","libraryforms"),"overseas");
        $table->addCell($objCheck3->show(), '', 'center', 'left', '');
        $table->addCell($overLabel->show(), '', 'center', 'left', '');
        $table->endRow();

        //Create a new textinput for fax
       
        $table->startRow();
	$objCheck4 = new checkbox('arrayList[]');
        $objCheck4->setValue($userPerm['id']);
       // $objCheck4->extra = "onclick=\"javascript: ToggleMainBox('select', 'toggle', this.checked);\"";
        $faxbLabel = new label($this->objLanguage->languageText("mod_libraryforms_commentfax","libraryforms"),"fax");
        $table->addCell($objCheck4->show(), '', 'center', 'left', '');
        $table->addCell($faxbLabel->show(), '', 'center', 'left', '');

        //Create a new label for ug

        $objCheck5 = new checkbox('arrayList[]');
        $objCheck5->setValue($userPerm['id']);
       //$objCheck5->extra = "onclick=\"javascript: ToggleMainBox('select', 'toggle', this.checked);\"";
        $pgbbLabel = new label($this->objLanguage->languageText("mod_libraryforms_commentpg","libraryforms"),"pg");
        //$objForm->addToForm($pgbbLabel->show());
        $table->addCell($objCheck5->show(), '', 'center', 'left', '');
        $table->addCell($pgbbLabel->show(), '', 'center', 'left', '');



        //Create a new label for staff

        $objCheck6 = new checkbox('arrayList[]');
        $objCheck6->setValue($userPerm['id']);
       // $objCheck6->extra = "onclick=\"javascript: ToggleMainBox('select', 'toggle', this.checked);\"";
        $ugbLabel = new label($this->objLanguage->languageText("mod_libraryforms_commentug","libraryforms"),"ug");
        $table->addCell($objCheck6->show(), '', 'center', 'left', '');
        $table->addCell($ugbLabel->show(), '', 'center', 'left', '');
         $table->endRow();
          
        $objCheck7= new checkbox('arrayList[]');
        $objCheck7->setValue($userPerm['id']);
        //$objCheck7->extra = "onclick=\"javascript: ToggleMainBox('select', 'toggle', this.checked);\"";
        $staffbLabel = new label($this->objLanguage->languageText("mod_libraryforms_commentstaff","libraryforms"),"staff");
        $table->addCell($objCheck7->show(), '', 'center', 'left', '');
        $table->addCell($staffbLabel->show(), '', 'center', 'left', '');
        $table->endRow();

        $objForm->addToForm($table->show());
        $objCaptcha = $this->getObject('captcha', 'utilities');
        $captcha = new textinput('thesis_captcha');
        $captchaLabel = new label($this->objLanguage->languageText('phrase_verifyrequest', 'security', 'Verify Request'), 'input_thesis_captcha');

        $strutil = stripslashes($this->objLanguage->languageText('mod_security_explaincaptcha', 'security', 'To prevent abuse, please enter the code as shown below. If you are unable to view the code, click on "Redraw" for a new one.')).'<br /><div id="bookthesiscaptchaDiv">'.$objCaptcha->show().'</div>'.$captcha->show().$required.'  <a href="javascript:bookthesisredraw();">'.$this->objLanguage->languageText('word_redraw', 'security', 'Redraw').'</a>';

        $objForm->addToForm('<br/><br/>'.$strutil.'<br/><br/>');
	$objForm->addRule('thesis_captcha',$this->objLanguage->languageText("mod_request_captcha_unrequired", 'libraryforms', 'Captcha cant be empty.Captcha is missing.'),'required');

 //----------SUBMIT BUTTON--------------
        //Create a button for submitting the form
        $objButton = new button('save');
        // Set the button type to submit
        $objButton->setToSubmit();
        // Use the language object to label button
        // with the word save
      $objButton->setValue(' '.$this->objLanguage->languageText("mod_libraryforms_savecomment", "libraryforms").'bookthesis ');
        $objForm->addToForm($objButton->show());

        return $objForm->show();
    }

    

    function insertBookthesisRecord($print, $author, $title, $place, $publisher, $date, $edition, $isbn, $series, $copy, $titlepages, $thesis, $name,$address, $cell,$fax,$tel,$telw,$emailaddress,$entitynum, $studentno,$course) {
        $id = $this->insert(array(
            //'userid' => $userid,
            'bprint' => $print,
            'bauthor' => $author,
            'btitle' => $title,
            'bplace' => $place,
            'bpublisher' => $publisher,
            'bdate' =>$date,
            'bedition' => $edition,
            'bisbn' => $isbn,
            'bseries' => $series,
            'bcopy' => $copy,
            'btitlepages' => $titlepages,
            'bpages' => $pages,
            'bthesis' => $thesis,
            'bname' => $name,
            'baddress' => $address,
            'bcell' => $cell,
            'bfax' => $fax,
            'btel' => $tel,
            'btelw' => $telw,
            'bemailaddress' => $bemailaddress,
            'bentitynum' => $bentitynum,
            'bstudentno' => $bstudentno,
            'bcourse' => $bcourse,
        ));
        return $id;
    }


    private function getFormAction() 
        {
           	 $formAction = $this->uri(array("action" => "save_book"), "libraryforms");
       
        	return $formAction;
    }

    public function show() {
        return $this->buildForm();

    }
}

