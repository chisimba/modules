<?php

class elsilogininterface extends object {

    function init() {
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objHelp = $this->getObject('help', 'help');
        $this->loadClass('button', 'htmlelements');
        $this->loadClass('textinput', 'htmlelements');
        $this->loadClass('checkbox', 'htmlelements');
        $this->loadClass('link', 'htmlelements');
        $this->loadClass('label', 'htmlelements');
        $this->loadClass('fieldset', 'htmlelements');
        $this->objAltConfig = $this->getObject('altconfig', 'config');
        $this->objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
        $this->siteRoot = $this->objAltConfig->getsiteRoot();
        $this->skinUri = $this->objAltConfig->getskinRoot();
    }

    function show() {
        $formAction = $this->objEngine->uri(array('action' => 'login'), 'security');
        $objBox = $this->newObject('alertbox', 'htmlelements');
        // prepare the link for the oAuth providers
        //$box = $this->oauthDisp();
        //$fb = $this->fbConnect();
        // Create a Form object
        $objForm = new form('loginform', $formAction);
        $content = "";
        //--Create an element for the username
        $usernameField = new textinput('username', '', 'text', '15');
        $usernameField->extra = "maxlength=255";
        $usernameLabel = new label($this->objLanguage->languageText('word_username') . ': ', 'input_username');
      
//Add validation for username
        $objForm->addRule('username', $this->objLanguage->languageText("mod_login_unrequired", 'security', 'Please enter a username. A username is required in order to login.'), 'required');


        //Add the username box to the form
        $content.= $usernameLabel->show() . '&nbsp;' . $usernameField->show();
        $passwordField = new textinput('password', '', 'password', '15');
        $passwordField->extra = "maxlength=255";
        $passwordLabel = new label($this->objLanguage->languageText('word_password') . ': ', 'input_password');
        $content.='&nbsp;' . $passwordLabel->show() . '&nbsp;' . $passwordField->show();

        //--- Create a submit button
        $objButton = new button('submit', $this->objLanguage->languageText("word_login"));
        // Set the button type to submit
        $objButton->setToSubmit();
        $content.='&nbsp;' . $objButton->show();

        /*      $objRElement = new checkbox("remember");
          $objRElement->setCSS("transparentbgnb noborder");
          $objRElement->label = $this->objLanguage->languageText("phrase_rememberme", "security");
          $rem = $objRElement->show() . ' ' . $objRElement->label . ' | ';
         */

        $facebookimg = '<img align="top" src="' . $this->siteRoot . '/' . $this->skinUri . '/wits_elearn2/canvases/_default/images/FaceBook_32x32.png">';
        $facebooklink = '<a href="' . $this->objSysConfig->getValue('FACEBOOK_URL', 'elsi') . '">Follow us ' . $facebookimg . '</a>';


        $helpText = strtoupper($this->objLanguage->languageText('word_help', 'system'));
        $helpIcon = $this->objHelp->show('register', 'useradmin', $helpText);
        $resetLink = new Link($this->uri(array('action' => 'needpassword'), 'security'));
        $resetLink->link = $this->objLanguage->languageText('mod_security_forgotpassword');
        // the help link
        $p = '&nbsp;' . $helpIcon;
        
        $content.='&nbsp;' . $p;
        $content.='&nbsp;&nbsp;|&nbsp;&nbsp;' . $facebooklink;
        $objForm->addToForm($content);

        return $objForm->show();
    }

}

?>
