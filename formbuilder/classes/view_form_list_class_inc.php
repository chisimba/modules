<?php
class view_form_list extends object
{
 public $objLanguage;

 private $objDBFormList;
 private $objDBFormPublishingOptions;
  private $objUser;
  private $publishOptionsArray;
public function init()
{
 //Instantiate the language object
 $this->objLanguage = $this->getObject('language','language');
 //Instantiate the language object
 $this->objDBFormList = $this->getObject('dbformbuilder_form_list','formbuilder');
 $this->objDBFormPublishingOptions = $this->getObject('dbformbuilder_publish_options','formbuilder');
         $this->objUser = &$this->getObject('user', 'security');
         $this->publishOptionsArray=NULL;
         $this->setNumberOfEntriesInPaginationBatch();
}
private function loadElements()
{
 //Load the form class
 $this->loadClass('form','htmlelements');
 //Load the form class
 $this->loadClass('link','htmlelements');
 //Load the textinput class
 $this->loadClass('textinput','htmlelements');
 //Load the label class
 $this->loadClass('label','htmlelements');
 //Load the textarea class
 $this->loadClass('textarea','htmlelements');
 //Load the button object
 $this->loadClass('button','htmlelements');
  $this->loadClass('radio','htmlelements');
  $this->loadClass('hiddeninput','htmlelements');
    $this->loadClass('checkbox','htmlelements');

}
private function buildViewPublishingIndicator($formNumber)
{
    if ($this->publishOptionsArray["0"]['publishoption'] == NULL)
    {
        return 0;
    }
 else {
        return 1;
    }

}
private function buildViewPublishingGeneralDetails($formNumber)
{
if ($this->publishOptionsArray["0"]['publishoption'] == 'simple')
{
    if ($this->publishOptionsArray["0"]['siteurl'] == NULL)
    {
$generalDetails = "This form is published with simple parameters.
    On successful submission of this form, a link will be provided to
    return to the form builder module.";
    }
    else
    {
      $generalDetails = "This form is published with simple parameters.
    On successful submission of this form, the submitter will be diverted
    in ".$this->publishOptionsArray["0"]['chisimbadiverterdelay']."
        seconds to another site with \"".$this->publishOptionsArray["0"]['siteurl']."\" as
        a url.";
    }
}
 else {
    if ($this->publishOptionsArray["0"]['chisimbaparameters'] == "yes")
    {
$generalDetails = "This form is published with advanced parameters.
    On successful submission of this form, this method will call an action
    named \"".$this->publishOptionsArray["0"]['chisimbaaction'] ."\" belonging to
        the \"".$this->publishOptionsArray["0"]['chisimbamodule']."\" module within
         the chisimba framework. The contents of the form that will be submitted
         will be created into a text string in standard URL-encoded notation that
         can be used by you ultilizing a \"REQUEST\" or \"GET\" action.";
    }
    else
    {
     $generalDetails = "This form is published with advanced parameters.
    On successful submission of this form, this method will call an action
    named \"".$this->publishOptionsArray["0"]['chisimbaaction'] ."\" belonging to
        the \"".$this->publishOptionsArray["0"]['chisimbamodule']."\" module within
         the chisimba framework. No form contents will be submitted.";
    }
}
return $generalDetails;
}

private function buildViewPublishingSimpleDetails($formNumber)
{
$simpleDetails= "Use the URL below to construct your form for submission:<br>";
$this->loadClass('textinput','htmlelements');
$this->loadClass('textarea','htmlelements');
$simpleUrlTextInput = new textarea("simpleUrl",html_entity_decode($this->uri(array('action'=>'buildCurrentForm','formNumber'=>$formNumber),$this->moduleName)),3,80);
$simpleDetails .=$simpleUrlTextInput->show()."<br>";
$simpleDetails.= "Copy this code into HTML to create a link to your form:<br>";
$simpleLink ="'<a href='".html_entity_decode($this->uri(array('action'=>'buildCurrentForm','formNumber'=>$formNumber),$this->moduleName))."'>Construct Form</a>'";
$simpleLinkTextInput = new textarea("simpleUrl",$simpleLink,3,80);
$simpleDetails .=$simpleLinkTextInput->show();
return $simpleDetails;
}

private function buildViewPublishingAdvancedDetails($formNumber)
{
  $this->loadClass('textarea','htmlelements');
    $advancedDetails= "<h3>For Chisimba PHP Developers</h3>";
//      $advancedDetails.= "Use the code below to construct your form: <br>";
//      $advancedFormConstructionTextInput = new textarea("simpleUrl",'$this->nextAction("form_entity_handler", "formbuilder");
//echo $objYourForm->buildForm('.$formNumber.');',1,70);
  $advancedDetails.="Use the code below if you want to construct your form anywhere you desire:<br>";

$advancedFormConstructionTextInput = new textarea("simpleUrl",'$objYourForm = $this->getObject("form_entity_handler", "'.$this->moduleName.'");
echo $objYourForm->buildForm('.$formNumber.');',3,80);
  $advancedDetails .=$advancedFormConstructionTextInput->show()."<br>";
  $advancedDetails.= "The Chisimba URI is provided below: <br>";
  $advancedURITextInput = new textarea("simpleUrl",'$this->uri(array(
      "module"=>"'.$this->moduleName.'",
    "action"=>"buildCurrentForm",
    "formNumber" =>'. $formNumber.'
   ));',5,80);
    $advancedDetails.=   $advancedURITextInput->show();

   return $advancedDetails;
}
public function getPublishingFormParameters($formNumber)
{
    $this->publishOptionsArray = $this->objDBFormPublishingOptions->getFormPublishingData($formNumber);
}
private function buildFormPublishingIndicator()
{
  $this->loadClass('radio','htmlelements');
    $this->loadClass('hiddeninput','htmlelements');
    $publishingRadio = new radio('publishingRadio');
    $publishingRadio->addOption('publish','Publish This Form');
    $publishingRadio->addOption('unpublish','Unpublish This Form');
    if ($this->publishOptionsArray["0"]['publishoption'] == NULL)
    {
    $publishingRadio->setSelected('unpublish');
    }
 else {
    $publishingRadio->setSelected('publish');
    }

  $publishingFormIndicator = $publishingRadio->show().'<br>';
  $simpleOrAdvancedIndicator= new  hiddeninput("simpleOrAdvancedHiddenInput", $this->publishOptionsArray["0"]['publishoption']);


    $publishingFormIndicator .=$simpleOrAdvancedIndicator->show();
   return     $publishingFormIndicator;
}

private function buildSimplePublishingForm()
{
    $this->loadElements();
    $simplePublishingFormUnderConstruction = "Select an action to preform after a form submission:<br>";

 $postActionRadio = new radio('simplePostActionRadio');
 $postActionRadio->addOption('internal','Provide a link to go the form builder module.');
 $postActionRadio->addOption('external','Divert to a url of your choice.
     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     &nbsp;');
 if ($this->publishOptionsArray["0"]['siteurl'] != NULL)
    {
 $postActionRadio->setSelected('external');
    }
    else
    {
 $postActionRadio->setSelected('internal');
    }

 $postActionRadio->setBreakSpace("<br>");
    $postActionRadio->show().'<br>';

$simplePublishingFormUnderConstruction .= $postActionRadio->show().'<br>';

$simplePublishingFormUnderConstruction .= "<div id='urlInserter'>";
$simplePublishingFormUnderConstruction .= "Insert a url of your choice:<br>";
if ($this->publishOptionsArray["0"]['siteurl'] == NULL)
    {
$urlTextInput = new textinput("urlChoice",'','text','100');
    }
 else {
        $siteURL = $this->publishOptionsArray["0"]['siteurl'];
        $urlTextInput = new textinput("urlChoice",$siteURL,'text','100');
    }
$simplePublishingFormUnderConstruction .= $urlTextInput->show().'<br>';
$simplePublishingFormUnderConstruction .= "Select the time delay before the divert intiates:<br>";
 $divertDelayRadio = new radio('simpleDivertDelayRadio');
 $divertDelayRadio->addOption('5','Divert after 5 seconds.
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
 $divertDelayRadio->addOption('10','Divert after 10 seconds.
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
 $divertDelayRadio->addOption('0','Divert immediately without any delay.');
   if ($this->publishOptionsArray["0"]['chisimbadiverterdelay'] == NULL)
    {
 $divertDelayRadio->setSelected('5');
    }
    else
    {
 $divertDelayRadio->setSelected($this->publishOptionsArray["0"]['chisimbadiverterdelay']);
    }
 $divertDelayRadio->setBreakSpace("<br>");
 $simplePublishingFormUnderConstruction .= $divertDelayRadio->show().'<br>';
$simplePublishingFormUnderConstruction .="</div>";
return $simplePublishingFormUnderConstruction;
}

private function buildAdvancedPublishingForm()
{
      $this->loadElements();
    $advancedPublishingFormUnderConstruction = "<b>For Chisimba PHP Developers</b><br><br>";
     $advancedPublishingFormUnderConstruction .= "Insert the next action after the form has been submitted:<br>";
    if ($this->publishOptionsArray["0"]['chisimbaaction'] == NULL)
    {
     $nextActionTextInput = new textinput("nextAction",'','text','100');
    }
 else {
     $nextAction = $this->publishOptionsArray["0"]['chisimbaaction'];
  $nextActionTextInput = new textinput("nextAction",$nextAction,'text','100');
    }

 $advancedPublishingFormUnderConstruction .= $nextActionTextInput->show().'<br>';
  $advancedPublishingFormUnderConstruction .= "Enter the module this action belongs to:<br>";
         if ($this->publishOptionsArray["0"]['chisimbamodule'] == NULL)
    {
  $nextActionModuleTextInput = new textinput("nextActionModule",'','text','100');
    }  
    else {

    $chisimbaModule = $this->publishOptionsArray["0"]['chisimbamodule'];
      $nextActionModuleTextInput = new textinput("nextActionModule",$chisimbaModule,'text','100');
    }
 $advancedPublishingFormUnderConstruction .= $nextActionModuleTextInput->show().'<br>';

if ($this->publishOptionsArray["0"]['chisimbaparameters'] == NULL)
{
   $parametersCheckbox = new checkbox("chisimbaParameters",  '', 0);  // this will checked
}
else
{
   if  ($this->publishOptionsArray["0"]['chisimbaparameters']== "yes")
   {
     $parametersCheckbox = new checkbox("chisimbaParameters",  '', 1);  // this will checked
   }
 else {
         $parametersCheckbox = new checkbox("chisimbaParameters",  '', 0);  // this will checked
   }
}
   $parametersCheckboxLabel = new label("Pass Form Element Parameters with this Action",  "chismbaParameters");
 $advancedPublishingFormUnderConstruction .=$parametersCheckbox->show().   $parametersCheckboxLabel->show()."<br>";
  $advancedPublishingFormUnderConstruction .="Select the time delay before the divert intiates:<br>";
 $divertDelayRadio = new radio('advancedDivertDelayRadio');
 $divertDelayRadio->addOption('5','Divert after 5 seconds.
           &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
 $divertDelayRadio->addOption('10','Divert after 10 seconds
           &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
 $divertDelayRadio->addOption('0','Divert immediately without any delay');
   if ($this->publishOptionsArray["0"]['chisimbadiverterdelay'] == NULL)
    {
 $divertDelayRadio->setSelected('5');
    }
    else
    {
 $divertDelayRadio->setSelected($this->publishOptionsArray["0"]['chisimbadiverterdelay']);
    }
 $divertDelayRadio->setBreakSpace("<br>");
  $advancedPublishingFormUnderConstruction .= $divertDelayRadio->show();
 return $advancedPublishingFormUnderConstruction;
             
}
private function buildFormOptionsMenu($formNumber)
{
    $this->loadClass('button','htmlelements');
$formMetaDataList = $this->objDBFormList->getFormMetaData($formNumber) ;
$formMetaName = $formMetaDataList["0"]['name'];

    // $createNewFormButton=new button('createNewFormButton');
    // $createNewFormButton->setValue('Create A New Form');
     //$createNewFormButton->setCSS("createNewFormButton");

$mngCreateNewFormlink = html_entity_decode($this->uri(array(
    'module'=>'formbuilder',
    'action'=>'addFormParameters'
   )));

$mngEditFormMetaDatalink = html_entity_decode($this->uri(array(
    'module'=>'formbuilder',
    'action'=>'editFormParameters',
    'formNumber'=> $formNumber
   )));

$mngEditWYSIWYGFormslink = html_entity_decode($this->uri(array(
    'module'=>'formbuilder',
    'action'=>'editWYSIWYGForm',
    'formNumber'=> $formNumber,
    'formTitle'=> $formMetaName
   )));
$mngBuildFormlink = html_entity_decode($this->uri(array(
    'module'=>'formbuilder',
    'action'=>'buildCurrentForm',
    'formNumber' => $formNumber
   )));
$mngViewSubmitResultslink = html_entity_decode($this->uri(array(
    'module'=>'formbuilder',
    'action'=>'viewSubmittedResults',
    'formNumber' => $formNumber
   )));
 //    $createNewFormButton->setOnClick("parent.location='$mngCreateNewFormlink'");
 // return $mngBuildFormlink = $createNewFormButton->showDefault();
  $formOptionsMenuUnderConstruction = "<div >New Commands</div>";
  $formOptionsMenuUnderConstruction .= "<button class='createNewFormButton' onclick=parent.location='$mngCreateNewFormlink'>Create A New Form</button><br>";
  $formOptionsMenuUnderConstruction .= "<div >Edit Commands</div>";
  $formOptionsMenuUnderConstruction .= "<button class='editFormMetaData' onclick=parent.location='$mngEditFormMetaDatalink'>Edit Form Metadata</button>";
$formOptionsMenuUnderConstruction .= "<button class='editWYSIWYGForm' onclick=parent.location='$mngEditWYSIWYGFormslink'>Edit Form in WYSIWYG Interface</button>";
  $formOptionsMenuUnderConstruction .= "<div >Build Commands</div>";
  $formOptionsMenuUnderConstruction .= "<button class='previewFormButton' name='$formNumber'>Preview Form Contents</button>";
$formOptionsMenuUnderConstruction .= "<button class='constructFormButton' name='constructFormButton' onclick=parent.location='$mngBuildFormlink'>Construct Form For Submission</button>";
  $formOptionsMenuUnderConstruction .= "<div >Delete Commands</div>";
 $formOptionsMenuUnderConstruction .= "<button class='deleteFormSubmissions' name='$formNumber'>Delete Form Submissions</button>";
 $formOptionsMenuUnderConstruction .= "<button class='deleteForm' name='$formNumber'>Delete All Form Contents</button>";
   $formOptionsMenuUnderConstruction .= "<div >Form Submission Commands</div>";
   $formOptionsMenuUnderConstruction .= "<button class='constructFormButtonForSubmission' name='constructFormButton' onclick=parent.location='$mngBuildFormlink'>Construct Form For Submission</button>";
    $formOptionsMenuUnderConstruction .= "<button class='viewSubmitResultsButton' name='viewSubmitResultsButton' onclick=parent.location='$mngViewSubmitResultslink'>View Form Submissions</button>";
   //$formOptionsMenuUnderConstruction .= "<div >Form Publishing Commands</div>";
     //$formOptionsMenuUnderConstruction .= "<button class='viewPublishingDataButton' name='$formNumber'>View Publishing Data</button>";
       // $formOptionsMenuUnderConstruction .= "<button class='editPublishingDataButton' name='$formNumber'>Edit Publishing Parameters</button>";
     return $formOptionsMenuUnderConstruction;

}
private function buildSearchMenu()
{



            $this->loadClass('form','htmlelements');
    $this->loadClass('textinput','htmlelements');
          $objForm = new form('searchFormListForm',$this->uri(array("action" => "searchAllForms"), "formbuilder"));
   $searchTextinput = new textinput("searchFormList",'','text',"40");
$objForm->addToForm($searchTextinput->show()."&nbsp;");

    $objResetFormListButton=new button('resetFormListButton');
    $objResetFormListButton->setValue('Reset');
    $objResetFormListButton->setToReset();
    $objResetFormListButton->setCSS("searchFormListButton");
//$objForm->addToForm($objResetFormListButton->show());

$objsearchFormListButton=new button('searchFormListButton');
$objsearchFormListButton->setValue('Search');
$objsearchFormListButton->setToSubmit();
$objsearchFormListButton->setCSS("searchFormListButton");
$objForm->addToForm($objsearchFormListButton->showDefault().'<br>');

return "<div id='searchFormListMenu' style='float:right;clear:right;'>".$objForm->show()."</div>";

}

private function buildPaginationMenu($paginationRequestNumber)
{
  $formNumberStart = $this->determinePaginationEntryOffset($paginationRequestNumber);
  $formNumberStop =   $formNumberStart + $this->getNumberOfEntriesInPaginationBatch();
  $totalNumberOfEntries = $this->objDBFormList->getNumberOfForms();
$numberOfPaginationRequests = ceil($totalNumberOfEntries/$this->getNumberOfEntriesInPaginationBatch());
    $paginationMenuUnderConstruction = "<div id='paginationMenu' style='float:right;clear:right;'>";
   $paginationMenuUnderConstruction .= "<button class='previousButton'>Previous</button>";
 // $paginationMenuUnderConstruction .= "<button>".($formNumberStart+1)." - ".$formNumberStop." of ";
 // $paginationMenuUnderConstruction .=$totalNumberOfEntries;
 //$paginationMenuUnderConstruction .=" forms</button>";

   $paginationMenuUnderConstruction .= "<button class='nextButton'>Next</button>";
 $paginationMenuUnderConstruction .="</div>";

  return $paginationMenuUnderConstruction;
}

    public function getNumberofPaginationRequests($searchValue=NULL)
    {
  if ($searchValue != NULL)
      {

         $totalNumberOfEntries = $this->objDBFormList->getNumberofSearchedEntries($searchValue);
         }
 else {
        $totalNumberOfEntries = $this->objDBFormList->getNumberOfForms();
}

return $numberOfPaginationRequests = ceil($totalNumberOfEntries/$this->getNumberOfEntriesInPaginationBatch());
    }


    
private function buildPaginationIndicator($paginationRequestNumber,$searchValue=NULL)
{
   
        $formNumberStart = $this->determinePaginationEntryOffset($paginationRequestNumber);
  $formNumberStop =   $formNumberStart + $this->getNumberOfEntriesInPaginationBatch();
   //     $action = $this->getParam("action", "searchAllForms");
  if ($searchValue != NULL)
      {

         $totalNumberOfEntries = $this->objDBFormList->getNumberofSearchedEntries($searchValue);
         }
         else
         {

  $totalNumberOfEntries = $this->objDBFormList->getNumberOfForms();
         }
  $numberOfPaginationRequests = ceil($totalNumberOfEntries/$this->getNumberOfEntriesInPaginationBatch());

if ($numberOfPaginationRequests == $paginationRequestNumber+1)
    {
        $paginationIndicatorUnderConstruction = "<button id='paginationIndicator'>".($formNumberStart+1)." - ".$totalNumberOfEntries." of ";
    $paginationIndicatorUnderConstruction .=$totalNumberOfEntries;
    $paginationIndicatorUnderConstruction .=" forms</button>";
    }
    else
    {
    $paginationIndicatorUnderConstruction = "<button id='paginationIndicator'>".($formNumberStart+1)." - ".$formNumberStop." of ";
    $paginationIndicatorUnderConstruction .=$totalNumberOfEntries;
    $paginationIndicatorUnderConstruction .=" forms</button>";
    }



  return $paginationIndicatorUnderConstruction;
}
    public function setNumberOfEntriesInPaginationBatch($noOfEntries=3) {
        $this->noOfEntriesInPaginationBatch = $noOfEntries;
    }

    public function getNumberOfEntriesInPaginationBatch() {

        return $this->noOfEntriesInPaginationBatch;
    }
        private function determinePaginationEntryOffset($paginationRequestNumber) {
        return $this->noOfEntriesInPaginationBatch * $paginationRequestNumber;
    }

private function buildFormList($paginationRequestNumber,$searchValue=NULL)
{
    $this->loadElements();

    //  $action = $this->getParam("action", "listAllForms");
      if ($searchValue!=NULL)
      {
        //  $searchValue = $this->getParam("searchFormList", NULL);
         $formListMetaDataArray =   $this->objDBFormList->searchFormList($searchValue, $this->getNumberOfEntriesInPaginationBatch(), $this->determinePaginationEntryOffset($paginationRequestNumber));
      }
 else
     {
             $formListMetaDataArray =  $this->objDBFormList->getPaginatedEntries($this->getNumberOfEntriesInPaginationBatch(), $this->determinePaginationEntryOffset($paginationRequestNumber));
      }
   
if ($formListMetaDataArray == NULL)
{
    return "<h3>There are no forms that can be displayed.</h3>";
}

//
//           $objViewSubmitResultsButton=new button('viewSubmitResultsButton');
//$objViewSubmitResultsButton->setValue('View Submit Results');
//$objViewSubmitResultsButton->setCSS("viewSubmitResultsButton");
//$objViewSubmitResultsButton->setOnClick($onclick);
//
//$mngViewSubmitResultslink = new link($this->uri(array(
//    'module'=>'formbuilder',
//    'action'=>'viewSubmittedResults',
//    'formNumber' => 7
//   )));
//$mngViewSubmitResultslink->link ="gfhgfhgfhgfhgf";
//   $linkViewSubmitResultsManage = $mngViewSubmitResultslink->show();
//$accordianUnderConstrunction .= $objViewSubmitResultsButton->show();
//      $accordianUnderConstrunction .= $linkViewSubmitResultsManage;
    $accordianUnderConstrunction .= '<div id="accordion" style="clear:both;">';
    foreach($formListMetaDataArray as $thisformListMetaData){
   //Store the values of the array in variables

   $id = $thisformListMetaData["id"];
   $formNumber = $thisformListMetaData["formnumber"];
   $formName = $thisformListMetaData["name"];
   $formLabel = $thisformListMetaData["label"];
   $formDetails = $thisformListMetaData["details"];
   $formAuthorID = $thisformListMetaData["author"];
   $formCreated = $thisformListMetaData["created"];
   $accordianUnderConstrunction .="<h3><a href=".$formNumber.">".$formNumber."   ".$formLabel."</a></h3>";
  
   
   $accordianUnderConstrunction .= "<div style ='margin-bottom:0px;'>";
     $formMetadataTable = &$this->newObject("htmltable", "htmlelements");
  //Define the table border
  $formMetadataTable->border = 0;
  //Set the table spacing
  $formMetadataTable->cellspacing = '15';
  //Set the table width
  $formMetadataTable->width = "100%";
// $accordianUnderConstrunction .= "<div style='border:1px solid #CCCCCC;'>";
   $accordianUnderConstrunction .= "<div class='ui-widget ui-corner-all'style='border:1px solid #CCCCCC;padding:10px 25px 15px 25px;'>";
      $accordianUnderConstrunction .= '<span id="toolbar" class="ui-widget-header ui-corner-all">';

    $objBuildFormButton=new button('constructFormButton');
       $objBuildFormButton->setValue('Construct Form For Submission');
$objBuildFormButton->setCSS("constructFormButton");
$mngBuildFormlink = html_entity_decode($this->uri(array(
    'module'=>'formbuilder',
    'action'=>'buildCurrentForm',
    'formNumber' => $formNumber
   )));
$objBuildFormButton->setOnClick("parent.location='$mngBuildFormlink'");
   $mngBuildFormlink = $objBuildFormButton->showDefault();
   $linkBuildFormManage = $mngBuildFormlink;


       $objPreviewFormButton=new button($formNumber);
        $objPreviewFormButton->setValue('Preview Form');
$objPreviewFormButton->setCSS("previewFormButton");
$mngPreviewFormlink = html_entity_decode($this->uri(array(
    'module'=>'formbuilder',
    'action'=>'previewCurrentForm',
    'formNumber' => $formNumber
   )));
//ss$objPreviewFormButton->setOnClick("$formNumber");

//
   $mngPreviewFormlink = $objPreviewFormButton->showDefault();
   $linkPreviewFormManage = $mngPreviewFormlink;

             $objFormOptionsButton=new button($formNumber);
        $objFormOptionsButton->setValue('Form Options');
$objFormOptionsButton->setCSS("formOptionsButton");
$mngFormOptionslink = new link($this->uri(array(
    'module'=>'formbuilder',
    'action'=>'formOptionsOfCurrentForm',
    'formNumber' => $formNumber
   )));
   $mngFormOptionslink =$objFormOptionsButton->showDefault();
   $linkFormOptionsManage = $mngFormOptionslink;

                $objViewPublishingDataButton=new button($formNumber);
$objViewPublishingDataButton->setValue('View Form Settings');
$objViewPublishingDataButton->setCSS("viewPublishingDataButton");
$mngViewPublishingDatalink = new link($this->uri(array(
    'module'=>'formbuilder',
    'action'=>'viewPublishingDataOfCurrentForm',
    'formNumber' => $formNumber
   )));
$mngViewPublishingDatalink =$objViewPublishingDataButton->showDefault();
   $linkViewPublishingDataManage = $mngViewPublishingDatalink;

  $objEditPublishingDataButton=new button($formNumber);
$objEditPublishingDataButton->setValue('Publishing and Submit Options');
$objEditPublishingDataButton->setCSS("editPublishingDataButton");
$mngEditPublishingDatalink = new link($this->uri(array(
    'module'=>'formbuilder',
    'action'=>'editPublishingDataOfCurrentForm',
    'formNumber' => $formNumber
   )));
$mngEditPublishingDatalink =$objEditPublishingDataButton->showDefault();
   $linkEditPublishingDataManage = $mngEditPublishingDatalink;

     $objViewSubmitResultsButton=new button('viewSubmitResultsButton');
$objViewSubmitResultsButton->setValue('View Submit Result Records');
$objViewSubmitResultsButton->setCSS("viewSubmitResultsButton");
$mngViewSubmitResultslink = html_entity_decode($this->uri(array(
    'module'=>'formbuilder',
    'action'=>'viewSubmittedResults',
    'formNumber' => $formNumber
   )));
$objViewSubmitResultsButton->setOnClick("parent.location='$mngViewSubmitResultslink'");
$mngViewSubmitResultslink =$objViewSubmitResultsButton->showDefault();
   $linkViewSubmitResultsManage = $mngViewSubmitResultslink;


       $accordianUnderConstrunction .= $linkPreviewFormManage
   .$linkBuildFormManage
   .$linkFormOptionsManage
   .$linkViewPublishingDataManage
   .$linkEditPublishingDataManage
   .$linkViewSubmitResultsManage;
         ////If you want to make t
//		<button id="beginning">go to beginning</button>
//		<button id="rewind">rewind</button>
//		<button id="play">play</button>
//		<button id="stop">stop</button>
//		<button id="forward">fast forward</button>
//		<button id="end">go to end</button>
//
if ($this->objUser->isAdmin())
{
    $accessInt= 1;
}
else if ($this->objUser->userId() == $formAuthorID)
{
    $accessInt= 2;
}
 else {
    $accessInt= 3;
}
$accessRestrictionHidden = new hiddeninput("accessRestriction", $accessInt);
$accessRestrictionHidden->extra ="class=userAccessRestriction";
$accordianUnderConstrunction .=$accessRestrictionHidden->show();
$accordianUnderConstrunction .=	'</span>';

   $formMetadataTable->startRow();
   $formMetadataTable->addCell("<B>Form Name: </B>".$formLabel);
$formMetadataTable->addCell("<B>Date Created: </B>".$formCreated);
   $formMetadataTable->endRow();
$formMetadataTable->startRow();
$formMetadataTable->addCell("<B>Author: </B>". $this->objUser->fullname($formAuthorID));
$formMetadataTable->addCell("<B>Email: </B>". $this->objUser->email($formAuthorID));
//$formMetadataTable->addCell($accessRestrictionHidden->show());
   $formMetadataTable->endRow();
   $formMetadataTable->startRow();
   $formMetadataTable->addCell("<B>Form Description: </B>");
      $formMetadataTable->endRow();
         $formMetadataTable->startRow();
   $formMetadataTable->addCell($formDetails);
      $formMetadataTable->endRow();
 $accordianUnderConstrunction .= $formMetadataTable->show();
 $accordianUnderConstrunction .= "</div>";
  $accordianUnderConstrunction .= "</div>";
    };

       $accordianUnderConstrunction .= "</div>";
return $accordianUnderConstrunction;
//	'<h3><a href="#secti">Section 1</a></h3>
//	<div>
//		<p>Mauris mauris ante, blandit et, ultrices a, susceros. Nam mi. Proin viverra leo ut odio. Curabitur malesuada. Vestibulum a velit eu ante scelerisque vulputate.</p>
//
//        </div>
//	<h3><a href="#section2">Section 2</a></h3>
//	<div>
//
//            <p>Sed non urna. Donec et ante. Phasellus eu ligula. Vestibulum sit amet purus. Vivamus hendrerit, dolor at aliquet laoreet, mauris turpis porttitor velit, faucibus interdum tellus libero ac justo. Vivamus non quam. In suscipit faucibus urna. </p>
//
//        </div>
//	<h3><a href="#section3">Section 3</a></h3>
//	<div>
//		<p>Nam enim risus, molestie et, porta ac, aliquam ac, risus. Quisque lobortis. Phasellus pellentesque purus in massa. Aenean in pede. Phasellus ac libero ac tellus pellentesque semper. Sed ac felis. Sed commodo, magna quis lacinia ornare, quam ante aliquam nisi, eu iaculis leo purus venenatis dui. </p>
//		<ul>
//			<li>List item</li>
//			<li>List item</li>
//			<li>List item</li>
//			<li>List item</li>
//			<li>List item</li>
//			<li>List item</li>
//			<li>List item</li>
//		</ul>
//		<a href="#othercontent">Link to other content</a>
//	</div>
//</div>';
}
private function buildForm()
{
  $this->loadElements();
  //Create the form
  $objForm = new form('formList', $this->getFormAction());
  //Fetch the comments from DB
  $allComments =  $this->objDBFormList->listAll();
  // Create a table object
  $commentsTable = &$this->newObject("htmltable", "htmlelements");
  //Define the table border
  $commentsTable->border = 0;
  //Set the table spacing
  $commentsTable->cellspacing = '12';
  //Set the table width
  $commentsTable->width = "40%";

  //Create the array for the table header
  $tableHeader = array();
  $tableHeader[] = "ID";
  $tableHeader[] = "Formnumber";
  $tableHeader[] = "Form Name";
  $tableHeader[] = "Form Label";
    $tableHeader[] = "Form Desciortion";
    $tableHeader[] = "Aithour";
        $tableHeader[] = "Date Cerated";
        $tableHeader[] = "View Form";
        $tableHeader[] = "Edit Form";
                $tableHeader[] = "View Form Results";
  // Create the table header for display
  $commentsTable->addHeader($tableHeader, "heading");
  //Render each comment in a table.
  foreach($allComments as $thisComment){
   //Store the values of the array in variables

   $id = $thisComment["id"];
   $formNumber = $thisComment["formnumber"];
   $formName = $thisComment["name"];
   $formLabel = $thisComment["label"];
   $formDetails = $thisComment["details"];
   $formAuthor = $thisComment["author"];
   $formCreated = $thisComment["created"];
   //Edit Row
  // eval($title);
   $iconEdSelect = $this->getObject('geticon','htmlelements');
   $iconEdSelect->setIcon('edit');
   $iconEdSelect->alt = "Edit Comment";
   $mngedlink = new link($this->uri(array(
    'module'=>'formbuilder',
    'action'=>'buildCurrentForm',
    'formNumber' => $formNumber
   )));
   $mngedlink->link = $iconEdSelect->show();
   $linkEdManage = $mngedlink->show();
    //Get the icon object
   $iconDelete = $this->getObject('geticon', 'htmlelements');
   //Set the icon name
   $iconDelete->setIcon('delete');
   //Set the alternative text of the icon
   $iconDelete->alt = "Delete Form";
   //Set align to default
   $iconDelete->align = false;
   //Create a new link Object
   $objConfirm = &$this->getObject("link", "htmlelements");
   //Create a new confirm object.
   $objConfirm = &$this->newObject('confirm', 'utilities');
   //Set object to confirm and the path for the confirm implementation and confirm text
   $objConfirm->setConfirm($iconDelete->show() , $this->uri(array(
    'module' => 'formbuilder',
    'action' => 'editWYSIWYGForm',
    'formNumber' =>    $formNumber,
       'formLabel' =>    $formLabel,
       'formCaption' =>   $formDetails,
       'formTitle' =>    $formName
       )));

   $viewResultsSelect = $this->getObject('geticon','htmlelements');
   $viewResultsSelect->setIcon('view');
   $viewResultsSelect->alt = "View Submitted Results";
   $mngviewlink = new link($this->uri(array(
    'module'=>'formbuilder',
    'action'=>'viewSubmittedResults',
    'formNumber' => $formNumber
   )));
   $mngviewlink->link =    $viewResultsSelect->show();
   $mngviewlinkManage =    $mngviewlink->show();

   // Add the table rows.

   $commentsTable->startRow();
   $commentsTable->addCell($id);
   $commentsTable->addCell($formNumber);
      $commentsTable->addCell($formName);
         $commentsTable->addCell($formLabel);
            $commentsTable->addCell($formDetails);
               $commentsTable->addCell($formAuthor);
                              $commentsTable->addCell($formCreated);
   $commentsTable->addCell($linkEdManage);
   $commentsTable->addCell($objConfirm->show());
      $commentsTable->addCell($mngviewlinkManage);
   $commentsTable->endRow();

  }
  //Get the icon object
  $iconSelect = $this->getObject('geticon','htmlelements');
  //Set the name of the icon
  $iconSelect->setIcon('add');
  //Set the alternative text of the icon
  $iconSelect->alt = "Add New Comment";
  //Create a new link for the add link
  $mnglink = new link($this->uri(array(
   'module'=>'formbuilder',
   'action'=>'addForm'
  )));
  //Set the link text/image
  $mnglink->link = $iconSelect->show();
  //Build the link
  $linkManage = $mnglink->show();
  //Add the add button to the table
   // Add the table rows.
   $commentsTable->startRow();
   //Note we are using column span. The other four parameters are set to default
   $commentsTable->addCell($linkManage,'','','','','colspan="2"');
   $commentsTable->endRow();

  $objForm->addToForm($commentsTable->show());
	 return $objForm->show();
 }

 private function getFormAction()
 {
   $action = $this->getParam("action", "add");
   if ($action == "edit") {
    $formAction = $this->uri(array("action" => "update"), "helloforms" );
   } else {
    $formAction = $this->uri(array("action" => "add"), "helloforms");
   }
   return $formAction;
 }
public function showViewPublishingIndicator($formNumber)
{
    return $this->buildViewPublishingIndicator($formNumber);

}
public function showViewPublishingGeneralDetails($formNumber)
{
return $this->buildViewPublishingGeneralDetails($formNumber);
}
public function showViewPublishingSimpleDetails($formNumber)
{
return $this->buildViewPublishingSimpleDetails($formNumber);
}
public function showViewPublishingAdvancedDetails($formNumber)
{
return $this->buildViewPublishingAdvancedDetails($formNumber);
}
    public function showSimplePublishingForm()
 {
   return  $this->buildSimplePublishingForm();

     $this->buildFormPublishingIndicator();
 }

 public function showAdvancedPublishingForm()
 {
      return   $this->buildAdvancedPublishingForm();
 }

 public function showFormPublishingIndicator()
 {
        return $this->buildFormPublishingIndicator();
 }
 public function showFormOptionsMenu($formNumber)
 {
     return $this->buildFormOptionsMenu($formNumber);
 }
 public function showSearchMenu()
 {
    return $this->buildSearchMenu();
 }
 public function showPaginationIndicator($paginationRequestNumber,$searchValue)
 {
     return $this->buildPaginationIndicator($paginationRequestNumber,$searchValue);
 }
 public function showPaginationMenu($paginationRequestNumber)
 {
     return $this->buildPaginationMenu($paginationRequestNumber);
 }
 public function show($paginationRequestNumber,$searchValue)
 {
  return $this->buildFormList($paginationRequestNumber,$searchValue);
 }
}
?>
