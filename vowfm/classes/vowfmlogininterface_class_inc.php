<?php

class vowfmlogininterface extends object {

    function init() {
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objHelp = $this->getObject('help', 'help');
        $this->loadClass('button', 'htmlelements');
        $this->loadClass('textinput', 'htmlelements');
        $this->loadClass('checkbox', 'htmlelements');
        $this->loadClass('link', 'htmlelements');
        $this->loadClass('label', 'htmlelements');
        $this->loadClass('fieldset', 'htmlelements');
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

        $objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
        $registerModule = $objSysConfig->getValue('REGISTRATION_MODULE', 'security');
        $registerModule = !empty($registerModule) ? $registerModule : 'userregistration';

        $registerLink = new link($this->uri(array('action' => 'showregister'), $registerModule));
        $registerLink->link = $this->objLanguage->languageText('word_register');

        $content.='&nbsp;&nbsp;' . $registerLink->show();

        $helpText = strtoupper($this->objLanguage->languageText('word_help', 'system'));
        $helpIcon = $this->objHelp->show('register', 'useradmin', $helpText);
        $resetLink = new Link($this->uri(array('action' => 'needpassword'), 'security'));
        $resetLink->link = $this->objLanguage->languageText('mod_security_forgotpassword');
        // the help link
        $p = '&nbsp;' . $resetLink->show() . '&nbsp;' . $helpIcon;

        $content.='&nbsp;' . $p;
        $objForm->addToForm($content);

        return $objForm->show();
    }

}
?>
