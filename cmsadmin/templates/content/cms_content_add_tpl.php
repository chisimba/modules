<?php
$script ='
<script type="text/javascript">
//<![CDATA[

YAHOO.namespace("example.container");

				function init() {
					
					// Define various event handlers for Dialog
					var handleSubmit = function() {
						this.submit();
					};
					var handleCancel = function() {
						this.cancel();
					};
					var handleSuccess = function(o) {
						var response = o.responseText;
						response = response.split("<!")[0];
						document.getElementById("resp").innerHTML = response;
						eval(response);
					};
					var handleFailure = function(o) {
						alert("Submission failed: " + o.status);
					};

					// Instantiate the Dialog
					YAHOO.example.container.dialog1 = new YAHOO.widget.Dialog("dialog1", 
																				{ width : "100%",
																				  fixedcenter : true,
																				  visible : false, 
																				  constraintoviewport : true,
																				 
																				 } );
					
					// Validate the entries in the form to require that both first and last name are entered
					YAHOO.example.container.dialog1.validate = function() {
						var data = this.getData();
						
							return true;
						
					};

					// Wire up the success and failure handlers
					YAHOO.example.container.dialog1.callback = { success: handleSuccess,
																 failure: handleFailure };
					
					// Render the Dialog
					YAHOO.example.container.dialog1.render();

					YAHOO.util.Event.addListener("show", "click", YAHOO.example.container.dialog1.show, YAHOO.example.container.dialog1, true);
					YAHOO.util.Event.addListener("hide", "click", YAHOO.example.container.dialog1.hide, YAHOO.example.container.dialog1, true);
				}

				YAHOO.util.Event.addListener(window, "load", init);

//]]>
</script>
';
$this->appendArrayVar('headerParams', $this->getJavascriptFile('yahoo/yahoo.js', 'yahoolib'));
$this->appendArrayVar('headerParams', $this->getJavascriptFile('event/event.js', 'yahoolib'));
$this->appendArrayVar('headerParams', $this->getJavascriptFile('dom/dom.js', 'yahoolib'));
$this->appendArrayVar('headerParams', $this->getJavascriptFile('dragdrop/dragdrop.js', 'yahoolib'));
$this->appendArrayVar('headerParams', $this->getJavascriptFile('container/container.js', 'yahoolib'));
$this->appendArrayVar('headerParams', $this->getJavascriptFile('connection/connection.js', 'yahoolib'));
$this->appendArrayVar('headerParams', $script);
$objIcon = $this->newObject('geticon', 'htmlelements');
$tbl = $this->newObject('htmltable', 'htmlelements');
$h3 = $this->getObject('htmlheading', 'htmlelements');
//$Icon = $this->newObject('geticon', 'htmlelements');
$objLayer = $this->newObject('layer', 'htmlelements');
//$Icon->setIcon('loading_circles_big');
$objRound =$this->newObject('roundcorners','htmlelements');
$objIcon->setIcon('add_article', 'png', 'icons/cms/');
if(isset($id))
{
	$h3->str = $objIcon->show().'&nbsp;'. $this->objLanguage->languageText('mod_cmsadmin_editcontentitem', 'cmsadmin');	
}
else {
	$h3->str = $objIcon->show().'&nbsp;'. $this->objLanguage->languageText('mod_cmsadmin_contentitem', 'cmsadmin').':'.'&nbsp;'.$this->objLanguage->languageText('word_new');
}

$objLayer->str = $h3->show();
$objLayer->border = '; float:left; align: left; margin:0px; padding:0px;';
$header = $objLayer->show();

$objLayer->str = $topNav;
$objLayer->border = '; float:right; align:right; margin:0px; padding:0px;';
$header .= $objLayer->show();

$objLayer->str = '';
$objLayer->border = '; clear:both; margin:0px; padding:0px;';
$headShow = $objLayer->show();

$display = '<p>'.$header.$headShow.'</p><hr />';
//Show Header
echo $display;
// Show Form

echo $addEditForm;
$button = new button();
$button->setId("show");
$button->setValue($this->objLanguage->languageText('mod_cmsadmin_pageblocks', 'cmsadmin'));
 $showDialog = '<div>
 			'.$button->show().'
			</div>';
 echo $showDialog;
/**
 * The Dialog component is an extension of Panel that is meant to emulate the behavior of an dialog window using a floating, draggable HTML element. 
 * Dialog provides an interface for easily gathering information from the user without leaving the underlying page context. 
 * The information gathered is collected via a standard HTML form; 
 * Dialog supports the submission of form data through XMLHttpRequest, through a normal form submission, or through a manual script-based response 
 * (where the script reads and responds to the form values and the form is never actually submitted to the server).
*/
			
			
//Instantiating a Dialog
if ($id != '') {
	 $h3->str =	$this->objLanguage->languageText('mod_cmsadmin_pageblocks', 'cmsadmin');
	 $h3->type = 3;
	 $dialog1 = '<div id="dialog1">';
     $dialog1 .= '<div class="hd">'.$h3->show().'</div>';
     $dialog1 .= '<div class="bd">'.$this->_objUtils->showContentBlocksForm($id, $section).'</div></div>';
     echo  $dialog1;
} 
?>