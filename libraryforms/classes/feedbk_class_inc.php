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


class feedbk extends dbTable {
    public $objLanguage;

    public function init() {
        $this->objLanguage = $this->getObject('language', 'language');
         parent::init('tbl_feedbackform');
    }
    private function loadElements() {
    //Load the form class
        $this->loadClass('form','htmlelements');
        //Load the textinput class
        $this->loadClass('textinput','htmlelements');
        //Load the textarea class
        $this->loadClass('textarea','htmlelements');
        //Load the label class
        $this->loadClass('label', 'htmlelements');
        //Load the button object
        $this->loadClass('button', 'htmlelements'); 
	




$strjs = '<script type="text/javascript">
		//<![CDATA[

 
   
	/***********************************************
        *                                              *
        *              FEEDBACK CLASS                  *
        *                                              *
        ***********************************************/
        //<![CDATA[

		function init () {
			$(\'input_feedbackredraw\').onclick = function () {
				feedbackredraw();
			}
		}
		function feedbackredraw () {
			var url = \'index.php\';
			var pars = \'module=security&action=generatenewcaptcha\';
			var myAjax = new Ajax.Request( url, {method: \'get\', parameters: pars, onComplete: feedbackShowResponse} );
		}
		function feedbackLoad () {
			$(\'load\').style.display = \'block\';
		}
		function feedbackShowResponse (originalRequest) {
			var newData = originalRequest.responseText;
			$(\'feedbackcaptchaDiv\').innerHTML = newData;
		}
		//]]>
		</script>';

        $this->appendArrayVar('headerParams', $strjs);
    }

    private function buildForm() {
    //Load the required form elements in the form
        $this->loadElements();

        //Create the form
        $objForm = new form('feedback', $this->getFormAction());

        //----------TEXT INPUT and Labels--------------
        //Create a new textinput for the title
           
	$this->loadClass('htmlheading', 'htmlelements');
	$fdbkHeading = new htmlheading();
	$fdbkHeading->type = 2;
	$fdbkHeading->str = $this->objLanguage->languageText("mod_libraryforms_commenttitlefeedback","libraryforms","fdbk");
	$objForm->addToForm($fdbkHeading->show()."<br/>");

        //Create a new textinput for the name
        $objname = new textinput('feedback_name');
        $nameLabel = new label($this->objLanguage->languageText("mod_libraryforms_commentnamefeedbk","libraryforms"),"name");
        $objForm->addToForm($nameLabel->show()."<br />");
        $objForm->addToForm($objname->show() . "<br />"."<br />");
        $objForm->addRule('feedback_name',$this->objLanguage->languageText("mod_libraryforms_commentnamerequired", "libraryforms", ''),'required');


        //Create a new textinput for the email
        $objemail = new textinput('fbkemail');
        $emailLabel = new label($this->objLanguage->languageText("mod_libraryforms_commentemail","libraryforms"),"fbkemail");
        $objForm->addToForm($emailLabel->show()."<br />");
        $objForm->addToForm($objemail->show() . "<br />"."<br />");
        $objForm->addRule('fbkemail', 'Not a valid Email', 'email');

        //----------TEXTAREA--------------
        //Create a new textarea for the comment message
        $objmsg = new textarea('msgbox');
        $msgLabel = new label($this->objLanguage->languageText
            ("mod_libraryforms_commentmsgbox","libraryforms"),"message");
        $objForm->addToForm($msgLabel->show()."<br/>");
        $objForm->addToForm($objmsg->show() . "<br />");
 $objForm->addRule('msgbox',$this->objLanguage->languageText
("mod_libraryform_commentmessage", "libraryforms", ''),'required');

	$objCaptcha = $this->getObject('captcha', 'utilities');
	$captcha = new textinput('feedback_captcha');
	$captchaLabel = new label($this->objLanguage->languageText('phrase_verifyrequest', 'security', 'Verify Request'), 'input_feedback_captcha');
		
	$strutil = stripslashes($this->objLanguage->languageText('mod_security_explaincaptcha', 'security', 'To prevent abuse, please enter the code as shown below. If you are unable to view the code, click on "Redraw" for a new one.')).'<br /><div id="feedbackcaptchaDiv">'.$objCaptcha->show().'</div>'.$captcha->show().$required.'  <a href="javascript:feedbackredraw();">'.$this->objLanguage->languageText('word_redraw', 'security', 'Redraw').'</a>';
               
	$objForm->addToForm('<br/><br/>'.$strutil.'<br/><br/>');
	$objForm->addRule('feedback_captcha',$this->objLanguage->languageText("mod_request_captcha_unrequired", 'libraryforms', 'Captcha cant be empty.Captcha is missing.'),'required');



        //----------SUBMIT BUTTON--------------
        //Create a button for submitting the form
        $objButton = new button('save');
        // Set the button type to submit
        $objButton->setToSubmit();
        // Use the language object to label button
        // with the word save

  $objButton->setValue(''.$this->objLanguage->languageText("mod_libraryforms_savecomment", "libraryforms").' ');
   $objForm->addToForm($objButton->show());
       
        return $objForm->show();
       

    }

    function insertmsgRecord($name,$emaill,$msg) {
        $id = $this->insert(array('name' => $name,'emaill' => $emaill,'msg' => $msg));
        return $id;
    }

    private function getFormAction() {
        $action = $this->getParam("action", "addfeedbk");
     
        if ($action == "addfeedbk") {
           $formAction = $this->uri(array("action" => "save_fdbk"), "libraryforms");
        } else {
        $formAction = $this->uri(array("action" => "update_fdbk"), "libraryforms");
        }
        return $formAction;
    }

    public function show() {
        return $this->buildForm();
    }
}


