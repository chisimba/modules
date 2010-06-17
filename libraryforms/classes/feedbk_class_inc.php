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


class feedbk extends object {
    public $objLanguage;

    public function init() {
        $this->objLanguage = $this->getObject('language', 'language');
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
        $objForm = new form('comments', $this->getFormAction());

        //----------TEXT INPUT and Labels--------------
        //Create a new textinput for the title

        $titlefeedbkLabel = new label($this->objLanguage->languageText("mod_libraryforms_commenttitlefeedback","libraryforms"),"Channel your feedback to inform our future planning");
        $objForm->addToForm($titlefeedbkLabel->show()."<br />");
        // $objForm->addToForm($objTitlefeedbk->show() . "<br />");

        //Create a new textinput for the name
        $objname = new textinput('name');
        $nameLabel = new label($this->objLanguage->languageText("mod_libraryforms_commentnamefeedbk","libraryforms"),"name");
        $objForm->addToForm($nameLabel->show()."<br />");
        $objForm->addToForm($objname->show() . "<br />");
        $objForm->addRule('name',$this->objLanguage->languageText("mod_name_unrequired", 'libraryforms', 'Please enter a name .Name is Missing .'),'required');


        //Create a new textinput for the email
        $objemail = new textinput('emaill');
        $emailLabel = new label($this->objLanguage->languageText("mod_libraryforms_commentemail","libraryforms"),"email");
        $objForm->addToForm($emailLabel->show()."<br />");
        $objForm->addToForm($objemail->show() . "<br />");
        $objForm->addRule('emaill',$this->objLanguage->languageText("mod_email_unrequired", 'libraryforms', 'Please enter a name .Email-Address is Missing .'),'required');

        //----------TEXTAREA--------------
        //Create a new textarea for the comment message
        $objmsg = new textarea('msgbox');
        $msgLabel = new label($this->objLanguage->languageText
            ("mod_libraryforms_commentmsgbox","libraryforms"),"message");
        $objForm->addToForm($msgLabel->show()."<br/>");
        $objForm->addToForm($objmsg->show() . "<br />");

        //----------SUBMIT BUTTON--------------
        //Create a button for submitting the form
        $objButton = new button('save');
        // Set the button type to submit
        $objButton->setToSubmit();
        // Use the language object to label button
        // with the word save

  $objButton->setValue(' '.$this->objLanguage->languageText("mod_libraryforms_savecomment", "libraryforms").' ');
 //$objForm->addToForm($objButton->show());

        $objButton->setValue(' '.$this->objLanguage->languageText("mod_libraryforms_savecomment", "libraryforms").' ');
        //$objForm->addToForm($objButton->show());



$objCaptcha = $this->getObject('captcha', 'utilities');
		$captcha = new textinput('request_captcha');
		$captchaLabel = new label($this->objLanguage->languageText('phrase_verifyrequest', 'security', 'Verify Request'), 'input_request_captcha');
		
		$strutil = stripslashes($this->objLanguage->languageText('mod_security_explaincaptcha', 'security', 'To prevent abuse, please enter the code as shown below. If you are unable to view the code, click on "Redraw" for a new one.')).'<br /><div id="feedbackcaptchaDiv">'.$objCaptcha->show().'</div>'.$captcha->show().$required.'  <a href="javascript:feedbackredraw();">'.$this->objLanguage->languageText('word_redraw', 'security', 'Redraw').'</a>';
               
		$objForm->addToForm('<br/><br/>'.$strutil.'<br/><br/>');
		$objForm->addRule('feedbackrequest_captcha',$this->objLanguage->languageText("mod_request_captcha_unrequired", 'libraryforms', 'Captcha cant be empty.Captcha is missing.'),'required');
		$objForm->addToForm($objButton->show());

        return $objForm->show();

    }
    function insertRecord($name,$emaill,$msg) {
        $id = $this->insert(array(
            'name' => $name,
            'emailaddress' => $emaill,
            'msg' => $msg, ));
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


