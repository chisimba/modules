<?php
/* -------------------- Splashscreen CLASS ----------------*/

/**
* Class to handle the display of the splashscreen before login
*/
class splashscreenrender extends object
{

    var $objModule;
    var $objHelp;
    var $objSkin;
    var $objLanguage;
    var $objConfig;
    var $userNameBox;
    var $passwordBox;
    var $useLdapCheck;
    var $resetLink;
    var $registerLink;
    var $skin;
    var $startForm;
    var $loginButton;

    /**
    * Constructor method to define the table
    */
    function init()
    {
        try
        {
        	// Get an instance of the config object
        	$this->objConfig=& $this->getObject('altconfig','config');
        	//Get an instance of the language object
        	$this->objLanguage = &$this->getObject('language', 'language');
        	//Get an instance of the skin
        	$this->objSkin = &$this->getObject('skin', 'skin');
        	//Get an instance of the help object
        	$this->objHelp=& $this->getObject('helplink','help');
        	//Create an instance of the module object
        	$this->objModule=& $this->getObject('modules','modulecatalogue');
        	// Create an instance of the help object
        	$this->objHelp =& $this->getObject('help', 'help');
        	
        	$this->loadClass('form', 'htmlelements');
        	$this->loadClass('dropdown', 'htmlelements');
        	$this->loadClass('button', 'htmlelements');
        /*********************************************
        * Create elements needed by subsequent methods
        **********************************************/
        //the link to registration
        $registerModule=$this->objConfig->getValue('SELFREGISTER_MODULE') or $registerModule='useradmin';
        $this->registerLink="<a href='".$this->uri(array('action'=>'register'),$registerModule)."'>".
        $this->objLanguage->languageText('word_register')."</a>\n";
        // the link for resetting passwords
        $this->resetLink="<a href='".$this->uri(array('action'=>'needpassword'),'useradmin')."'>".
        $this->objLanguage->languageText('mod_security_forgotpassword')."</a>\n";
        // the help link
        // Create help object
        $helpText = $this->objLanguage->languageText('mod_useradmin_help','useradmin');
        $helpIcon = $this->objHelp->show('register', 'useradmin', $helpText);
        $this->resetLink .= '<br></br>'.$helpIcon;
        //the variable to hold the username textbox
        $this->userNameBox=$this->objLanguage->languageText("word_username")
            .':<input name="username" type="text" id="username" class="text prelogin" /><br />';
        //the variable to hold the password textbox
        $this->passwordBox=$this->objLanguage->languageText("word_password")
            .':<input name="password" type="password" id="password" class="text prelogin" /><br />';
        //the variable to hold the useLDAP checkbox
        $this->useLdapCheck='<input id="LdapCheckbox" type="checkbox" name="useLdap" value="yes" class="transparentbgnb"/>'
            .'<label for="LdapCheckbox">'
            .$this->objLanguage->languageText("phrase_networkid")
            .'</label>';
        //the variable to hold the login button
        $jsWarning = '<noscript><span class="error"><strong>'.$this->objLanguage->languageText('mod_security_javascriptwarning').'</strong></span><br /></noscript>';
        $this->loginButton= $jsWarning.'<input name="Submit" type="submit" class="button"
          onclick="KEWL_validateForm(\'username\',\'\',\'R\',\'password\',\'\',\'R\');'
          .'return document.KEWL_returnValue" value="'
          .$this->objLanguage->languageText("word_login").'"/>';
        $this->objSkin->validateSkinSession();
        $this->skin = '<input type="hidden" name="skinlocation" value="'.$this->objSkin->getSkin().'" />';
        }
        catch (customException $e)
        {
        	echo customException::cleanUp($e);
        	die();
        }
    }


    /**
    * Method to put the splashscreen on the login page
    * @author Sean Legassick and James Scoble
    * @param string $goplace Intercept any querystring parameter
    * for go place so that the user can be transferred to the
    * URL in goplace after login. This is used when coming in
    * from static content.
    */
     function putAdminLogin()
    {
        $autoLogin =& $this->newObject('autologin','splashscreen');
        return $autoLogin->show();
    }
    
    public function generateMenu($module = 'splashscreen')
    {
    	 // Create the menu
    	 
        $menu = array();
        if ($module == 'cms') {
            $menu[]='<a href="'.$this->uri(array(),$module).'">'.'Home'.'</a>';
        }
        
        $menu[]='<a href="'.$this->uri(array('block'=>'languages'),$module).'">'.$this->objLanguage->languageText('word_languages').'</a>';
        $menu[]='<a href="'.$this->uri(array('block'=>'context'),$module).'">'.ucwords($this->objLanguage->code2Txt('mod_context_context','context')).'</a>';
        $menu[]='<a href="'.$this->uri(array('block'=>'skins'),$module).'">'.$this->objLanguage->languageText('word_skins').'</a>';
        $menu[]='<a href="'.$this->uri(array('action'=>'register'),'useradmin').'">'.'Register'.'</a>';
        // Encapsulate menu items with <li></li>
        for ($i=0;$i<count($menu);$i++) {
            $menu[$i] = '<li>'.$menu[$i].'</li>';
        }
        // Create the login button
        $login='<nobr><a href="'.$this->uri(array('block'=>'login'),$module).'" class="pseudobutton">'.$this->objLanguage->languageText('word_login').'</a>&nbsp;</nobr>';
        // Create a table for layout of menu and login button
        $objTable =& $this->newObject('htmltable','htmlelements');
        $objTable->width="100%";
        $objTable->startRow();
        $objTable->addCell('<div id="menu"><ul id="nav">'.join('',$menu).'</ul></div>', '90%', 'center','left','');
        $objTable->addCell('<ul id="nav">'.$login.'</ul>', '10%', 'center','right','');
        $objTable->endRow();
        $str = '<div id="toolbar">'.$objTable->show().'</div>';
        return $str;
    }

    function putKNGForgeSplashScreen($module = 'splashscreen')
    {
        $this->goplace = '';
        $str = '';
        $str .= $this->putAdminLogin();
       
        // Add the content
        switch($this->getParam('block',NULL)){
            case 'login':
                $str .= '<div style="padding: 1em;">';
                $str .= $this->addBlock(array('function'=>'Login','name'=>$this->objLanguage->languageText('word_login')));
                $str .= $this->addBlock(array('function'=>'Registration','name'=>$this->objLanguage->languageText('word_registration')));
                $str .= '</div>';
                break;
            case 'languages':
                $str .= '<div style="padding: 1em;">';
                $str .= $this->addBlock(array('function'=>'Languages','name'=>$this->objLanguage->languageText('word_languages')));
                $str .= '</div>';
                break;
            case 'context':
                $str .= '<div style="padding: 1em;">';
                $str .= $this->addBlock(array('function'=>'Context','name'=>ucwords($this->objLanguage->code2Txt('mod_context_context','context'))));
                $str .= '</div>';
                break;
            case 'skins':
                $str .= '<div style="padding: 1em;">';
                $str .= $this->addBlock(array('function'=>'Skins','name'=>$this->objLanguage->languageText('word_skins')));
                $str .= '</div>';
                break;
            case NULL:
            default:
                ;
        } // switch
        return $str;
    }
       /**
    * Function to add the HTML for a particular block to the content string
    * @author Nic Appleby
    * @param mix $rec the record containing the block to add
    * @return string the HTML code containing the block
    */
    function addBlock($rec) {
    $ts_content='';
    switch ($rec['function']) {

        case 'Login':
            $arr = array('action' => 'login');
            if ($this->goplace!="") {
                $arr['goplace'] = $this->goplace;
            }
            $ts_content.='<form class="login" name="login_form" id="form1" method="post" target="_top" action="'.$this->objEngine->uri($arr, 'security').'">';
            $ts_content.= '<fieldset class="tabbox"><legend class="tabbox">'.$rec['name'].'</legend><TABLE>';
                $ts_content.='<TR><TD ALIGN="RIGHT">'.$this->userNameBox.'</TD></TR>';
                $ts_content.='<TR><TD ALIGN="RIGHT">'.$this->passwordBox.'</TD></TR>';
            // Display the LDAP checkbox only if this site is using LDAP
                $ts_content.='<TR><TD ALIGN="RIGHT">';
            if ($this->objConfig->getuseLDAP()){
                   $ts_content.=$this->useLdapCheck.'<br />';
                } else {
                   $ts_content.='<br /> ';
                }
                $ts_content.=$this->loginButton.'<br /></TD></TR>';
            // Put the link to reset the user's password
                $ts_content.='<TR><TD>'.$this->resetLink.'</TD></TR></TABLE></fieldset>';
            $ts_content.= $this->skin."</form>";
            return $ts_content;

        case 'Registration':
            $ts_content = '<fieldset class="tabbox"><legend class="tabbox">'.$rec['name'].'</legend><TABLE><TR>';
                // Put registration link only if allowselfregister is true
                if ($this->objConfig->getallowSelfRegister()) {
                        $ts_content.='<TD>'.$this->registerLink.'</TD>';
                } else {
                        $ts_content.=' ';
                }
            $ts_content.='</TR></TABLE></fieldset>';
            return $ts_content;

        case 'Languages':
            $ts_content ='<fieldset class="tabbox"><legend class="tabbox">'. $rec['name'].'</legend><TABLE><TR>';
                $languageChooser=$this->objLanguage->putlanguageChooser();
                $ts_content.='<TD>'.$languageChooser.'</TD></TR></TABLE></fieldset>';
            return $ts_content;

        case 'Context':
            $ts_content ='<fieldset class="tabbox"><legend class="tabbox">'. $rec['name'].'</legend><TABLE><TR>';
                // Course Chooser
               // $ts_content.='<TD>''</TD></TR></TABLE></fieldset>';
            return $ts_content;

        case 'JoinContext':
            $ts_content ='<fieldset class="tabbox"><legend class="tabbox">'. ucfirst(strtolower(wordwrap($rec['name'],12, "<br />\n"))).'</legend><TABLE><TR>';
                // Join Course Chooser
               // $ts_content.='<TD>'.$this->getJoinContextDropDown().'</TD></TR></TABLE></fieldset>';
            return $ts_content;

        case 'Skins':
            $ts_content ='<fieldset class="tabbox"><legend class="tabbox">'. $rec['name'].'</legend><TABLE><TR>';
                $skinChooser=$this->objSkin->putSkinChooser();
                $ts_content.='<TD>'.$skinChooser.'</TD></TR></TABLE></fieldset>';
            return $ts_content;

        case 'module':
            $this->objModuleBlocks=$this->newObject('blocks','blocks');
            $array=explode("|",$rec['name']);
            $module=$array[0];
            $block=$array[1];
            $ts_content=$this->objModuleBlocks->showBlock($block,$module,'tabbedbox',$titleLength=22);
            return $ts_content;

        default:
            $ts_content ='<fieldset class="tabbox"><legend class="tabbox">'. $rec['name'].'</legend><TABLE><TR>';
                $ts_content.='<TD>'.stripslashes($rec['content']).'</TD></TR></TABLE></fieldset>';
            return $ts_content;
    }
    }

    /**
    * Method to display the default splashscreen if no dynamic changes
    * have been made through splashscreenadmin
    * @param string $ts_content the splashcreen content so far to be built upon
    * @return string the modified string containing the default splashscreen content
    * @author Nic Appleby
    */

    function doDefault($ts_content) {

    $ts_content.='<div id="leftnav">';
    //login block
    $arr = array('action' => 'login');
    if ($this->goplace!="") {
        $arr['goplace']=$this->goplace;
    }
    $ts_content.='<form class="login" name="login_form" id="form1" target="_top" method="post" action="'.$this->objEngine->uri($arr, 'security').'">';
    $ts_content.='<fieldset class="tabbox"><legend class="tabbox">'.$this->objLanguage->languageText('word_login').'</legend><TABLE>';
        $ts_content.='<TR><TD ALIGN="RIGHT">'.$this->userNameBox.'</TD></TR>';
        $ts_content.='<TR><TD ALIGN="RIGHT">'.$this->passwordBox.'</TD></TR>';
    // Display the LDAP checkbox only if this site is using LDAP
        $ts_content.='<TR><TD ALIGN="RIGHT">';
    if ($this->objConfig->useLDAP()){
            $ts_content.=$this->useLdapCheck;
        } else {
           $ts_content.=' ';
        }
        $ts_content.='<br>'.$this->loginButton.'</br></TD></TR>';
    // Put the link to reset the user's password
        $ts_content.='<TR><TD>'.$this->resetLink.'</TD></TR></TABLE></fieldset>';
    $ts_content.= $this->skin."</form>";

    //registration block
    $ts_content.= '<fieldset class="tabbox"><legend class="tabbox">'.$this->objLanguage->languageText('word_registration').'</legend><TABLE><TR>';
        // Put registration link only if allowselfregister is true
        if ($this->objConfig->allowSelfRegister()) {
            $ts_content.='<TD>'.$this->registerLink.'</TD>';
        } else {
            $ts_content.=' ';
        }
    $ts_content.='</TR></TABLE></fieldset>';

    //languages block
    $ts_content.='<fieldset class="tabbox"><legend class="tabbox">'. $this->objLanguage->languageText('word_languages').'</legend><TABLE><TR>';
        $languageChooser=$this->objLanguage->putlanguageChooser();
        $ts_content.='<TD>'.$languageChooser.'</TD></TR></TABLE></fieldset>';

    //courses block
    $temp = $this->objLanguage->code2Txt('word_courses');
    $temp[0] = strtoupper($temp[0]);
    $ts_content.='<fieldset class="tabbox"><legend class="tabbox">'.$temp.'</legend><TABLE><TR>';
        // Course Chooser
       // $ts_content.='<TD>'.$this->getContextDropDown().'</TD></TR></TABLE></fieldset>';

    $ts_content.='</div>';
    //end of left nav panel

    $ts_content.='<div id="rightnav">';
    //Put the skin chooser if requested
    $ts_content.='<fieldset class="tabbox"><legend class="tabbox">'. $this->objLanguage->languageText('word_skins').'</legend><TABLE><TR>';
        $skinChooser=$this->objSkin->putSkinChooser();
        $ts_content.='<TD>'.$skinChooser.'</TD></TR></TABLE></fieldset>';

    $ts_content.='</div>';
    //end of right nav panel
    return $ts_content;
    }
 /**
    *  Method to get the dropdown that contains all the public courses that you can join
    * @author Nic Appleby
    */
    function getJoinContextDropDown() {
        $contexts = $this->objContext->getPublicContext();
        if ($this->objModule->checkIfRegistered('splashscreenadmin','splashscreenadmin')) {
            $this->objBlocks=&$this->getObject('defaultblockstable','splashscreenadmin');
            $content = $this->objBlocks->getRow('function','JoinContext');
            if (empty($content['content'])) {
                $contextForm = $this->objLanguage->code2Txt('phrase_joincontextasstudent').':';
            }
            else {
                $contextForm = $content['content'];
            }
        }
        else {
            $contextForm = $this->objLanguage->code2Txt('phrase_joincontextasstudent').':';
        }
        if (!empty($contexts)) {
            $this->loadClass('dropdown','htmlelements');
            $this->loadClass('button','htmlelements');
            $this->loadClass('form','htmlelements');
            $dropDown = &new dropdown('joincontext_dropdown');
            $dropDown->cssClass = 'coursechooser';
            $dropDown->addFromDB($contexts,'menutext','contextCode',$contexts[0]['contextCode']);
            $form=&new form('join_context',$this->uri(array('action'=>'joincontextasstudent'),'context'));
            $form->setDisplayType(3);
            $sub = &new button();
            $sub->setValue($this->objLanguage->languageText('word_go'));
            $sub->setToSubmit();
            $form->addToForm($dropDown->show());
            $form->addToForm($sub->show());
            $contextForm .= $form->show();
        }
        else {
            $contextForm = $this->objLanguage->languageText('phrase_nocontext');
        }
        return $contextForm;
    }

    function putSplashScreen($goplace = '') {
        // Create help object
        $helpText = $this->objLanguage->languageText('mod_useradmin_help','useradmin');
        $helpIcon = $this->objHelp->show('register', 'useradmin', $helpText);


        //the variable to start the form
        $startForm='<form class="login" name="login_form"
          id="form1" method="post" action="'
          .$this->objEngine->uri(array('action' => 'login'), 'security');
        if ($goplace!="") {
            $startForm=$startForm.'&goplace='.$goplace;
        }
        $startForm=$startForm.'">';
        //the link to registration
        $registerModule='useradmin';
        $registerLink="<a href='".$this->uri(array('action'=>'register'),$registerModule)."'>".
        $this->objLanguage->languageText('word_register')."</a>\n";
        // the link for resetting passwords
        $resetLink="<a href='".$this->uri(array('action'=>'needpassword'),'useradmin')."'>".
        $this->objLanguage->languageText('mod_security_forgotpassword')."</a>\n";
        // the help link
        $resetLink .= '<br /><br />'.$helpIcon;

        //the variable to hold the username textbox
        $userNameBox=$this->objLanguage->languageText("word_username")
            .':<br /><input name="username" type="text" id="username" class="text" size="15"/>';
        //the variable to hold the password textbox
        $passwordBox=$this->objLanguage->languageText("word_password")
            .':<br /><input name="password" type="password" id="password" class="text" size="15" />';
        //the variable to hold the useLDAP checkbox
        $useLdapCheck='<input type="checkbox" name="useLdap" value="yes" class="transparentbgnb" />'
            .$this->objLanguage->languageText("phrase_networkid");
        //the variable to hold the login button

        $jsWarning = '<noscript><span class="error"><strong>'.$this->objLanguage->languageText('mod_security_javascriptwarning','security').'</strong></span><br /></noscript>';

        $loginButton= $jsWarning.'<input name="Submit" type="submit" class="button"
          onclick="KEWL_validateForm(\'username\',\'\',\'R\',\'password\',\'\',\'R\');'
          .'return document.KEWL_returnValue" value="'
          .$this->objLanguage->languageText("word_login").'" />';
        $login=$userNameBox.'&nbsp;&nbsp;&nbsp;'
            .$passwordBox.'&nbsp;&nbsp;&nbsp;'
        .$useLdapCheck.'&nbsp;&nbsp;&nbsp;'.$loginButton;
        //Open and parse the template file for the skin (splash_readfile_template.php)
        $splashFile=$this->objSkin->getSkinLocation().'splashscreen/splash_readfile_template.php';
        $ts=fopen($splashFile,"r") or die($this->objLanguage->languageText("error_splashscrmissing",'security')
            .": ".$splashFile.".");
        $ts_content=fread($ts, filesize($splashFile));

        $this->objSkin->validateSkinSession();
        $skin = '<input type="hidden" name="skinlocation" value="'.$this->objSkin->getSkin().'" />';

        $ts_content=str_replace("[-STARTFORM-]", $startForm, $ts_content);
        $ts_content=str_replace("[-SKIN-]", $skin, $ts_content);
        $ts_content=str_replace("[-USERNAMEBOX-]", $userNameBox, $ts_content);
        $ts_content=str_replace("[-PASSWORDBOX-]", $passwordBox, $ts_content);

	$ts_content=str_replace("[-SITENAME-]", $this->objConfig->getSiteName(), $ts_content);

        // Display the LDAP checkbox only if this site is using LDAP
        if ($this->objConfig->getuseLDAP()){
            $ts_content=str_replace("[-USELDAPCHECK-]", $useLdapCheck, $ts_content);
        } else {
            $ts_content=str_replace("[-USELDAPCHECK-]",NULL, $ts_content);
        }
        $ts_content=str_replace("[-LOGINBUTTON-]", $loginButton, $ts_content);
        $ts_content=str_replace("[-LOGIN-]", $login, $ts_content);

        $ts_content=str_replace("[-ENDFORM-]", $skin."</form>", $ts_content);

        // Course Chooser
        //$ts_content=str_replace("[-CONTEXTCHOOSER-]", $this->getContextDropDown(), $ts_content);
		$ts_content=str_replace("[-CONTEXT-]", ucwords($this->objLanguage->code2Txt('mod_context_context','context')), $ts_content);

        //Resource Kit Link
        $ts_content=str_replace('[-RESOURCEKIT-]', $this->uri(array(),'resourcekit'), $ts_content);

        //Put the skin chooser if requested
        $skinChooser=$this->objSkin->putSkinChooser();
        $ts_content=str_replace("[-SKINCHOOSER-]", $skinChooser, $ts_content);
        $languageChooser=$this->objLanguage->putlanguageChooser();
    $ts_content=str_replace("[-LANGUAGECHOOSER-]",$languageChooser, $ts_content);
        // Put registration link only if allowselfregister is true
        if ($this->objConfig->getallowSelfRegister()) {
            $ts_content=str_replace("[-REGISTER-]", $registerLink, $ts_content);
        } else {
            $ts_content=str_replace("[-REGISTER-]", NULL, $ts_content);
        }

        // Put the link to reset the user's password
        $ts_content=str_replace("[-NEWPASSWORD-]", $resetLink, $ts_content);


        if($this->objModule->checkIfRegistered('stories','stories')){
            $this->objStories=& $this->getObject('sitestories', 'stories');
            $ts_content=str_replace('[-PRELOGINSTORIES-]', $this->objStories->fetchCategory('prelogin'), $ts_content);
            $ts_content=str_replace('[-PRELOGINSTORIESFOOTER-]', $this->objStories->fetchCategory('preloginfooter', NULL, FALSE), $ts_content);
        } else {
            $ts_content=str_replace('[-PRELOGINSTORIES-]', ' ', $ts_content);
            $ts_content=str_replace('[-PRELOGINSTORIESFOOTER-]', ' ', $ts_content);
        }

        return $ts_content;
    }

    /**
    *  Method to get the dropdown that contains all the public courses
    * @author Tohir Solomons
    */
    function getContextDropDown()
    {
        $objContext =& $this->getObject('dbcontext', 'context');
        $courses = $objContext->getListOfPublicContext();

        if (count($courses) == 0) {
            return '';
            
        } else {
            $form = new form('joincontext', $this->uri(array('action'=>'joincontext'), 'context'));
            $dropdown = new dropdown ('contextCode');
            foreach ($courses AS $course)
            {
                $dropdown->addOption($course['contextcode'], $course['menutext']);
            }
            $dropdown->setSelected($objContext->getContextCode());
            $button = new button ('submitform', ucwords($this->objLanguage->code2Txt('mod_context_joincontext', 'context')));
            $button->setToSubmit();
            
            $form->addToForm($dropdown->show().'<br />'.$button->show());
            
            return $form->show();
        }
    }


}  #end of class
?>
