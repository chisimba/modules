<?php

$this->loadClass('form', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');
$objConfig = $this->getObject('altconfig', 'config');

$scriptFile = $this->getJavascriptFile('admin.js','htmlelements');
$this->appendArrayVar('headerParams',$this->getJavascriptFile('admin.js','htmlelements'));

//add step 1 template
$objH = new htmlheading();
$objForm = new form();

$inpContextCode =  new textinput();
$inpContextCode3 =  new textinput();
$inpContextCode2 = new textinput();
$inpMenuText = new textinput();
$inpTitle = new textinput();
$inpButton =  new button();
$dropAccess = new dropdown();

//CHANGE THIS
$objH->str = $this->_objLanguage->languageText('word_step').' 1: '.$this->_objLanguage->code2Txt("mod_contextadmin_addcontext",'contextadmin');

$objH->type = 3;

//$inpTitle->fldType="hidden";

//setup the form
$objForm->name = 'addfrm';
$objForm->action = $this->uri(array('action' => 'savestep1'));
$objForm->extra = 'class="f-wrap-1"';
$objForm->displayType = 3;

$inpContextCode->name = 'contextcode';
$inpContextCode->cssId = 'input_contextcode';
$inpContextCode->value = '';
$inpContextCode->cssClass = 'f-name';
$inpContextCode->fldType = 'hidden';

$inpContextCode2->name = 'contextcode2';
$inpContextCode2->cssId = 'input_contextcode2';
$inpContextCode2->value = '';
$inpContextCode2->cssClass = 'f-name';
$inpContextCode2->extra = 'onkeyup="updateFolder(this, \'input_contextcode3\', \'autogen\');"';

$inpContextCode3->name = 'contextcode3';
$inpContextCode3->cssId = 'input_contextcode3';
$inpContextCode3->value = '';
$inpContextCode3->cssClass = 'f-name';
$inpContextCode3->extra = '  disabled="true"  onkeyup="validateFolder(this);"';

$inpTitle->name = 'title';
$inpTitle->cssId = 'input_title';
$inpTitle->value = '';
$inpTitle->cssClass = 'f-name';

$inpMenuText->value = '';
$inpMenuText->name = 'menutext';
$inpMenuText->cssId = 'input_menutext';
$inpMenuText->cssClass = 'f-name';

//status
$dropAccess->name = 'status';
			$dropAccess->addOption('Published',$this->_objLanguage->languageText("mod_context_published",'context'));
			$dropAccess->addOption('Unpublished',$this->_objLanguage->languageText("mod_context_unpublished",'context'));

$dropAccess->setSelected('Published');

$drop = '<fieldset class="f-radio-wrap">
		
						<b>'.$this->_objLanguage->languageText("mod_context_access",'context').':</b>

			
				<fieldset>
				
				<label for="Public">
				<input id="Public" type="radio" checked="checked" name="access" value="Public" class="f-radio" tabindex="8" />
				
							'.$this->_objLanguage->languageText("mod_context_public",'context').' <span class="caption">  -  '.$this->_objLanguage->code2Txt("mod_context_publichelp",'context',array('context'=>'Course')).'</span></label>
				
				<label for="Open">
				<input id="Open" type="radio" name="access" value="Open" class="f-radio" tabindex="9" />
				'.$this->_objLanguage->languageText("mod_context_open",'context').' <span class="caption">  -  '.$this->_objLanguage->code2Txt("mod_context_openhelp",'context',array('context'=>'Course')).'</span></label>

				
				<label for="Private">

				<input id="Private" type="radio" name="access" value="Private" class="f-radio" tabindex="10" />
				'.$this->_objLanguage->languageText("mod_context_private",'context').' <span class="caption">  -  '.$this->_objLanguage->code2Txt("mod_context_privatehelp",'context',array('context'=>'course')).'</span></label>
				
	
				</fieldset>
			
			</fieldset>';

$inpButton->setToSubmit();
$inpButton->extra = 'onclick=" this.form.contextcode.value = this.form.contextcode3.value;"';
$inpButton->cssClass = 'f-submit';
$inpButton->value = $this->_objLanguage->languageText("word_next");


//validation
$objForm->addRule('contextcode2',$this->_objLanguage->code2Txt("mod_contextadmin_err_requrecontextcode",'contexadmin',array('context'=>'Course')), 'required');
$objForm->addRule('menutext',$this->_objLanguage->languageText("mod_contextadmin_err_requiremenutext",'contextadmin'), 'required!');
$objForm->addRule('title',$this->_objLanguage->languageText("mod_contextadmin_err_requiretitle",'contextadmin'), 'required!');

$objForm->addToForm('<div class="req"><b>*</b> Indicates required field</div>');
$objForm->addToForm('<fieldset>');
if($error)
{
    $objForm->addToForm('<br/><p class="error">'.$error.'</p>');
}
$objForm->addToForm($objH->show());

$objForm->addToForm('<label for="contextcode"><b><span class="req">*</span>'.ucwords($this->_objLanguage->code2Txt("mod_context_contextcode",'context',array('context'=>'Course'))).':</b>');
$objForm->addToForm($inpContextCode->show().$inpContextCode2->show(). $inpContextCode3->show().'<br /></label>');

$objForm->addToForm('<label for="title"><b><span class="req">*</span>'.$this->_objLanguage->languageText("word_title").':</b>');
$objForm->addToForm($inpTitle->show().'<br /></label>');

$objForm->addToForm('<label for="menutext"><b><span class="req">*</span>'.$this->_objLanguage->languageText("mod_context_menutext",'context').':</b>');
$objForm->addToForm($inpMenuText->show().'<br /></label>');

//$objForm->addToForm('&nbsp;<br/>');


$objForm->addToForm('<label for="access"><b><span class="req">*</span>'.$this->_objLanguage->languageText("mod_context_status",'context').':</b>');
$objForm->addToForm($dropAccess->show().'<br /></label>');

$objForm->addToForm($drop);
$objForm->addToForm('</fieldset>');
$objForm->addToForm($inpButton->show());


print $objForm->show().'<br/>';

if(!isset($list))
{
	$list = NULL;
}

$contextArr = $this->_objDBContext->getAll();

$cnt = count($contextArr);
$i = 0;

foreach ($contextArr as $context)
{
 	$i++;
	$list .="'".$context['contextcode']."'";
	$list = ($i < $cnt && $cnt!=1) ? $list.', ': $list;
}
$str = "<script type=\"text/javascript\">
        window.totalinputs = 5;
        // Array of album names for javascript functions.
        var albumArray = new Array ( ".$list." );
      
      </script>";
      
echo  $str;

?>
<input type="checkbox" name="autogenfolder" id="autogen" checked="true" onClick="toggleAutogen('folderdisplay', 'albumtitle', this);" style="display:none">