<?php
///Include the jQuery Library

$this->appendArrayVar('headerParams', $this->getJavascriptFile('1.4.2/jquery-1.4.2.min.js', 'jquery'));
//$this->appendArrayVar('headerParams', '<script type="text/javascript">jQuery.noConflict();</script>');
//$this->appendArrayVar('headerParams', $this->getJavascriptFile('1.2.2/jquery.maskedinput-1.2.2.js', 'jquery'));

    $formElementsAdderMenu = $this->getObject('form_element_inserter', 'formbuilder');
    $this->loadClass('label','htmlelements');
    $this->loadClass('form','htmlelements');
    $this->loadClass('textinput','htmlelements');
    $this->loadClass('radio','htmlelements');
   $this->loadClass('htmlarea','htmlelements');
      $this->loadClass('link','htmlelements');

?>
<div id="formElementsAdderMenu">
    <?php 
    $formElementsAdderMenu = $this->getObject('form_element_inserter', 'formbuilder');
    echo $formElementsAdderMenu->showFormElementInserterDropDown()."<br>";
 echo $formTitle = $this->getParam('formTitle');
  echo $formLabel = $this->getParam('formLabel');
    echo $formCaption = $this->getParam('formCaption');
        echo $formNumber = $this->getParam('formNumber');
    ?>
    <div id="finishAddingFormElementsMenu">
        <?php
 $iconSelect = $this->getObject('geticon','htmlelements');
  //Set the name of the icon
  $iconSelect->setIcon('ok');
  //Set the alternative text of the icon
  $iconSelect->alt = "Finish Adding Form Elements";

  $linkText= "Finish Adding Form Elements and Finalise Form";
  //Create a new link for the add link
  $mnglink = new link($this->uri(array(
         'module' => 'formbuilder',
   'action'=>'goOutside'

  )));
  //Set the link text/image
  $mnglink->link = $iconSelect->show()." ".$linkText." ".$iconSelect->show();
  //Build the link
 echo  $linkManage = $mnglink->show();
  ?>
</div>
</div>



  


<div id="labelInputForFormElements">
    
 <label class="labelForFormTextInput">Enter the a Unique Name for Your Form Element: </label>
    <input type="text" name="formLabel" value="Enter Form Label" maxlength="35" size="50" class="formElementLabel"/>
    <input type="submit" value="Create Form Element" id="submitFormElementName"/>
    <span></span>
</div>

<div id="tempdivcontainer"></div>


<div id="addParametersForButton">
    <div id="stylingForButton">
       <label>Button Parameters Menu</label><br>
       <div id="resetOrSubmitChoiceMenu">
          <br><label for="resetOrSubmitButtonRadio">Select Button Type: </label><br>
  <input type="radio"  name="resetOrSubmitButtonRadio" value="submit" checked>Submit Button (Submit Form)<br>
    <input type="radio"  name="resetOrSubmitButtonRadio" value="reset">Reset Button (Reset All Fields in Form)<br>
       </div>
          <div id="insertLabelForButton">
              <br><label>Insert Label for Button: </label>
 <input type="text" name="buttonLabel" maxlength="100" size="100" class="buttonLabel"/><br>
    </div>

         <input type="submit" value="Set Button Parameters" id="submitButtonParameters"/>
    </div>
        <div id="endOfInsertionButton">
         <br><br><input type="submit" value="Finish Inserting Button Objects" id="submitEndOfInsertionButton"/>
     </div>
        <div id="insertButtonName">
  <label>Insert Name for Button Object: </label>
 <input type="text" name="buttonName" maxlength="100" size="100" class="buttonName"/><br>
 <input type="submit" value="Insert Button Object" id="submitNameforButton"/>
    </div>
    <span></span>
</div>




<div id="addParametersforTextArea">
    <div id="stylingForTextArea">
     <label>Text Area Parameters Menu</label><br>
     <div id="setTextAreaSize">
       <label for="textAreaColumnSizeParameter">Set horizontal or column size for text area (between 1-140):</label>
                <input type="text" name="textAreaColumnSizeParameter" value="60" maxlength="3" size="3" class="textAreaColumnSizeMenu"/>
                <br><label for="textAreaRowSizeParameter">Set vertical or row size for text area (between 1-240):</label>
                <input type="text" name="textAreaRowSizeParameter" value="10" maxlength="3" size="3" class="textAreaRowSizeMenu"/>
     </div>
     <div id="setTextAreaText">
         <br><label>Set default text for text area: </label><br>
         <?php
//   $ha = $this->newObject('htmlarea','htmlelements');
//   $ha->setName("textAreaText");
//$ha->width ="80%";
//$ha->height ="200px";
//$ha->toolbarSet='DefaultWithoutSave';
//echo $ha->show();
         ?>
                     <textarea name="setDefaulttextArea" rows="8" cols="60">

</textarea>
     </div>
     <div id="simpleOrAdvancedTextAreaMenu">
         <br><label for="simpleOrAdvancedTextAreaRadio">Select Text Area Type: </label><br>
  <input type="radio"  name="simpleOrAdvancedTextAreaRadio" value="textarea" checked>Simple Text Area (Without Tool Bars)<br>
    <input type="radio"  name="simpleOrAdvancedTextAreaRadio" value="htmlarea">Advanced Text Area (With Tool Bars)<br>
     </div>

     <div id="toolbarChoiceMenu">
        <br><label for="toolbarChoiceRadio">Select Tool Bar for Text Area: </label><br>
 <p> <input type="radio"  name="toolbarChoiceRadio" value="simple" checked>Basic Tool Bar<br>
  <img src="packages/formbuilder/resources/images/simpletoolbar.png" alt="Basic Tool Bar"><br></p>
 <p> <input type="radio"  name="toolbarChoiceRadio" value="DefaultWithoutSave">Default Tool Bar Without Save<br>
  <img src="packages/formbuilder/resources/images/defaultwithoutsavetoolbar.png" alt="Default Without Save Tool Bar"><br></p>
 <p> <input type="radio"  name="toolbarChoiceRadio" value="advanced">Advanced Tool Bar<br>
  <img src="packages/formbuilder/resources/images/advancedtoolbar.png" alt="Advanced Tool Bar"><br></p>
      <p> <input type="radio"  name="toolbarChoiceRadio" value="cms">Content Management System (CMS) Tool Bar<br>
  <img src="packages/formbuilder/resources/images/cmstoolbar.png" alt="CMS Tool Bar"><br></p>
            <p> <input type="radio"  name="toolbarChoiceRadio" value="forms">Forms Tool Bar<br>
  <img src="packages/formbuilder/resources/images/formstoolbar.png" alt="Forms Tool Bar"><br></p>
     </div>
        <input type="submit" value="Set Text Area Parameters" id="submitTextAreaParameters"/>
    </div>
    <div id="endOfInsertionTextArea">
         <br><br><input type="submit" value="Finish Inserting Text Area Objects" id="submitEndOfInsertionTextArea"/>
     </div>

    <div id="insertTextAreaName">
  <label>Insert Name for Text Area Object: </label>
 <input type="text" name="textAreaName" maxlength="100" size="100" class="textAreaName"/><br>
 <input type="submit" value="Insert Text Area Object" id="submitTextforTextArea"/>
    </div>
    <span></span>
</div>
<div id="addParametersForTextInput">
    <div id="StylingForTextInput">
  <label>Text Field Parameters Menu</label>
         <div id="setTextInputSize">
               <label for="textInputSizeParameter">Set character or field size for text input (between 1-150):</label>
                <input type="text" name="textInputSizeParameter" value="20" maxlength="3" size="3" class="textInputSizeMenu"/>

 </div>

  <br><div id="textorPasswordMenu">
             <label for="textorPasswordRadio">Select Text Input Field Type: </label><br>
  <input type="radio"  name="textorPasswordRadio" value="text" checked>Insert Text<br>
    <input type="radio"  name="textorPasswordRadio" value="password">Insert Password<br>
        </div>

         <div id="setTextMenu">
             <br><label for="setDefaultText">Set default text for text input: </label><br>
  
             <textarea name="setDefaulttext" rows="8" cols="60">
 Insert default text to be displayed inside text input field.
</textarea>
 </div>

         <div id="setMaskedInput">
               <br><label for="textInputSizeParameter">Select and Set Input Mask:</label><br>
              <input type="radio"  name="maskedInputChoice" value="default" checked>No Masked Input<br>
               <input type="radio"  name="maskedInputChoice" value="text input_mask mask_number">Number<br>
              <input type="radio"  name="maskedInputChoice" value="text input_mask mask_date_us">Date (US Format) : day/month/year<br>
    <input type="radio"  name="maskedInputChoice" value="text input_mask mask_date_iso">Date (ISO Foramt) : year-month-day<br>
        <input type="radio"  name="maskedInputChoice" value="text input_mask mask_time">Time : hour:minute<br>
            <input type="radio"  name="maskedInputChoice" value="text input_mask mask_phone">Phone Number : (000)000-0000<br>
             <input type="radio"  name="maskedInputChoice" value="text input_mask mask_ssn">Social Security Number : 000-00-0000<br>
                         <input type="radio"  name="maskedInputChoice" value="text input_mask mask_visa">Visa Number : 0000-0000-0000-0000<br>
 </div><br>
   <input type="submit" value="Set Text Field Parameters" id="submitTextFieldParameters"/>
    </div>

 <div id="endOfInsertionTextInput">
         <br><br><input type="submit" value="Finish Inserting Text Input Objects" id="submitEndOfInsertionTextInput"/>
     </div>

    <div id="insertTextInputName">
  <label>Insert Name for Text Input Object: </label>
 <input type="text" name="textInputLabel" maxlength="100" size="100" class="textInputText"/><br>
 <input type="submit" value="Insert Text Input Object" id="submitTextforTextInput"/>
    </div>
    <span></span>
 </div>


<div id="addParametersForDatePicker">
    <div id="datePickerParametersContainer">
    <div id="dateFormat">
        <?php
        $possibleDateFomats = array ('YYYYMMDD', 'YYYY-MM-DD', 'YYYY-DD-MM',
             'YYYY/MM/DD', 'YYYY/DD/MM', 'YYYY-DD-MON', 'YYYY-MON-DD',
             'MM-DD-YYYY', 'DD-MM-YYYY', 'MM/DD/YYYY', 'DD/MM/YYYY',
             'DD-MON-YYYY', 'MON-DD-YYYY');
       $labelDateFormat = new label("Please Select Date Format:");
       $defaultDateFormatRadio = new dropdown("Date_Format");
       foreach($possibleDateFomats as $thisDateFormat)
       {
           $defaultDateFormatRadio->addOption($thisDateFormat,$thisDateFormat);
       }
       $defaultDateFormatRadio->setSelected('YYYY-MM-DD');
       echo $labelDateFormat->show()."<br>";
       echo $defaultDateFormatRadio->show()."<br><br>";
        ?>
    </div>
    <div id="defaultDateChoiceForm">
        <?php

         
       $labelDateChoice = new label("Please Select Choice of a Default Set Date:");
       echo $labelDateChoice->show()."<br>";
$defaultDateRadio = new radio("Default Date Choice");
$defaultDateRadio->addOption("Real Date","Set the default selected date to real time");
$defaultDateRadio->addOption("Custom Date","Customize the default selected date");
$defaultDateRadio->setBreakSpace("<br>");
$defaultDateRadio->setSelected('Real Date');
echo $defaultDateRadio->show();
        ?>
    </div>

    <div id="selectDefaultDate">
        <?php
 $labelforDefaultDatePicker = new label("Select a Default Date:",NULL);
 $defaultDatePicker = $this->newObject('datepicker', 'htmlelements');
$test='YYYY-MON-DD';
 $defaultDatePicker->name = 'defaultDateSelection';
 //$defaultDatePicker->setDefaultDate("jan-10-2007");
   $defaultDatePicker->setDateFormat($test);
 echo $labelforDefaultDatePicker->show();
 echo $defaultDatePicker->show();
        ?>
    </div><br>
    <input type="submit"  value="Apply Parameters to Date Picker" id="submitDatePickerParameters"/><br>

    <div id="endOfInsertionDatePicker">
         <br><br><input type="submit" value="Finish Inserting Date Picker Object" id="submitEndOfInsertionDatePicker"/>
     </div>
    </div>
    <div id="insertDatePickerName">
  <label class="datePickerLabel">Insert Name or Value for Date Picker Object: </label>
 <input type="text" name="datePickerLabel" maxlength="100" size="100" class="datePickerText"/><br>
 <input type="submit" value="Insert Date Picker Object" id="submitTextforDatePicker"/>

 </div>
    <span></span>
</div>

<div id="addParametersForTextElements">
     <div id="selectLayoutMenu">
         <div id="LayoutMenu">
 <label for="submitFormElementLayout">Select Layout of Text: </label><br>
 <input type="radio"  name="textLayout" value="tab">Insert a tab after the text.<br>
<input type="radio"  name="textLayout" value="new line">Insert a new line after the text.<br>
<input type="radio"  name="textLayout" value="normal" checked> Use normal layout.<br>
<br>
         </div>

                  <div id="SizeMenu">
 <label for="submitFormElementLayout">Select Font Size: </label><br>

  <input type="radio"  name="formEntitySize" value="1"><font size="+2"><b>Size 1</b></font> <br>
    <input type="radio"  name="formEntitySize" value="2"><font size="4"><b>Size 2</b></font> <br>
       <input type="radio"  name="formEntitySize" value="3" checked><font size="3"><b>Size 3</b></font> <br>
           <input type="radio"  name="formEntitySize" value="4"><font size="2"><b>Size 4</b></font> <br>
                <input type="radio"  name="formEntitySize" value="5"><font size="1"><b>Size 5 (Normal Text)</b></font> <br>
       <input type="radio"  name="formEntitySize" value="6"><font size="-2"><SUB>Size 6</SUB></font> <br>
       <br>
         </div>

                           <div id="AlignMenu">
 <label for="submitFormElementLayout">Select Alignment Type: </label><br>

  <input type="radio"  name="formEntityAlign" value="left" checked><b> Left Align</b><br>
    <input type="radio"  name="formEntityAlign" value="center"><b>Center Align</b><br>
       <input type="radio"  name="formEntityAlign" value="right"><b>Right Align</b><br>

         </div>

 <input type="submit"  value="Apply Layout and Insert Text" id="submitTextLayout"/><br>

      <div id="endOfInsertionText">
         <br><br><input type="submit" value="Finish Inserting Text" id="submitEndOfInsertionText"/>
     </div>
     </div>
    <div id="insertTextMenu">
  <label class="TextName">Insert Name For Text Object: </label>
 <input type="text" name="formEntityText" maxlength="100" size="100" class="formEntityText"/><br>
 <input type="submit" value="Insert Text Object" id="submitTextforFormElement"/>

 </div>
    

 <span></span>
    
</div>




 <div id="addOptionnValueForFormElements">
     <div id="selectLayoutMenu">
         <div id="LayoutMenu">
 <label for="submitFormElementLayout">Select Layout of Form Entity: </label><br>
 <input type="radio"  name="formEntityLayout" value="tab">Insert a tab between option.<br>
<input type="radio"  name="formEntityLayout" value="new line"> Place option in a new line.<br>
<input type="radio"  name="formEntityLayout" value="normal"checked> Use normal layout.<br>

         </div>
 <input type="submit"  value="Apply Layout and Insert Option" id="submitFormElementLayout"/><br>

      <div id="endOfInsertion">
         <br><br><input type="submit" value="Finish Inserting Options" id="submitEndOfInsetion"/>
     </div>
     </div>
 <div id="insertOptionMenu">
 <label class="formEntityValueOption">Enter a Value for your form element option: </label>
 <input type="text" name="formEntityValue" value="Value" maxlength="35" size="50" class="formEntityValue"/><br>
  <label class="formEntityLabelOption">Enter a Label for your form element option: </label>
 <input type="text" name="formEntityLabel" value="Label" maxlength="70" size="50" class="formEntityLabel"/><br>
 <INPUT class="formEntityDefaultOption"TYPE=CHECKBOX NAME="Default Value">
  <label class="formEntityDefaultOptionLabel">Set this option selected as default. </label><br>
 <input type="submit" value="Insert Option" id="submitOptionforFormElement"/>

 </div>

 <span></span>
</div>
<p style="border-bottom: 2px dotted #000000; width: 1000px;"></p>
<div id="WYSIWYGForm">

</div>
<?php
//$this->appendArrayVar('headerParams', $this->getJavascriptFile('1.4.2/jquery-1.4.2.min.js', 'jquery'));
?>
<!--<script src="jquery.maskedinput.js" type="text/javascript">
    jQuery(document).ready(function() {

                       $("#addParametersForDatePicker").show(1000);
                   jQuery('.datePickerText').mask("99/99/9999");
//   $("#date").mask("99/99/9999");
//   $("#phone").mask("(999) 999-9999");
//   $("#tin").mask("99-9999999");
//   $("#ssn").mask("999-99-9999");

    });
</script>-->

<script type="text/javascript">


    jQuery(document).ready(function() {
        //jQuery('*').fadeOut(1000);
//        jQuery("#labelInputForFormElements").hide();
//        jQuery("#addOptionnValueForFormElements").hide();
//        jQuery("#tempdivcontainer").hide();
//          fadeInOrOutFormElementsEditorMenus("#formElementsAdderMenu","Remove Menu");
//         fadeInOrOutFormElementsEditorMenus("#formElementsAdderMenu","Insert Menu","#formElementsAdderMenu");
       // displayFormElementsAdderMenu("#formElementsAdderMenu");

jQuery("#tempdivcontainer").show();
 jQuery("#addParametersForButton").hide();
 jQuery("#addParametersForTextInput").hide();
jQuery("#addParametersForDatePicker").hide();
       jQuery("#addParametersForTextElements").hide();
  jQuery("#labelInputForFormElements").hide();
        jQuery("#addOptionnValueForFormElements").hide();
        jQuery("#addParametersforTextArea").hide();
        jQuery("#tempdivcontainer").hide();
        jQuery("#formElementsAdderMenu").children("#finishAddingFormElementsMenu").hide();
insertNewFormElement();
   }

);

    function insertNewFormElement()
   {


jQuery('#tempdivcontainer').empty();

     //     fadeInOrOutFormElementsEditorMenus("#formElementsAdderMenu","Remove Menu");
         fadeInOrOutFormElementsEditorMenus("#formElementsAdderMenu","Insert Menu","#formElementsAdderMenu");
            if (jQuery("#WYSIWYGForm").children(":input[type=submit]").length > 0 )
          {
               jQuery("#formElementsAdderMenu").show();
              jQuery("#formElementsAdderMenu").children("#finishAddingFormElementsMenu").show("slow");
             // jQuery("#tempdivcontainer").append("dtecte buitton");
                //     jQuery("#tempdivcontainer").show();
          }


   jQuery('#input_add_form_elements_drop_down').change(function() {
                   if (jQuery('#input_add_form_elements_drop_down').val() == "form_heading")
    {
      fadeInOrOutFormElementsEditorMenus("#formElementsAdderMenu","Remove Menu");
        getInputFromFormElementLabel("HTML heading","Form");
            }
                   if (jQuery('#input_add_form_elements_drop_down').val() == "label")
    {
      fadeInOrOutFormElementsEditorMenus("#formElementsAdderMenu","Remove Menu");
        getInputFromFormElementLabel("label","Form");
            }
   if (jQuery('#input_add_form_elements_drop_down').val() == "radio")
    {
      fadeInOrOutFormElementsEditorMenus("#formElementsAdderMenu","Remove Menu");
        getInputFromFormElementLabel("radio","Form");
            }
       if (jQuery('#input_add_form_elements_drop_down').val() == "check_box")
    {
      fadeInOrOutFormElementsEditorMenus("#formElementsAdderMenu","Remove Menu");
        getInputFromFormElementLabel("checkbox","Form");
            }
                   if (jQuery('#input_add_form_elements_drop_down').val() == "drop_down")
    {
      fadeInOrOutFormElementsEditorMenus("#formElementsAdderMenu","Remove Menu");
        getInputFromFormElementLabel("dropdown","Form");
            }
             if (jQuery('#input_add_form_elements_drop_down').val() == "date_picker")
    {
      fadeInOrOutFormElementsEditorMenus("#formElementsAdderMenu","Remove Menu");
        getInputFromFormElementLabel("date picker","Form");
            }
   if (jQuery('#input_add_form_elements_drop_down').val() == "text_input")
       {
             fadeInOrOutFormElementsEditorMenus("#formElementsAdderMenu","Remove Menu");
        getInputFromFormElementLabel("text input","Form");    
       }
          if (jQuery('#input_add_form_elements_drop_down').val() == "text_area")
       {
             fadeInOrOutFormElementsEditorMenus("#formElementsAdderMenu","Remove Menu");
        getInputFromFormElementLabel("text area","Form");
       }
          if (jQuery('#input_add_form_elements_drop_down').val() == "button")
       {
             fadeInOrOutFormElementsEditorMenus("#formElementsAdderMenu","Remove Menu");
        getInputFromFormElementLabel("button","Form");
       }
        });
   }

   
function getInputFromFormElementLabel(formelementtype,formname)
{
 
   jQuery('*').unbind();
       jQuery("#labelInputForFormElements").fadeIn(1000);
        jQuery("#labelInputForFormElements span").show();
                        jQuery("#labelInputForFormElements input").show();
                jQuery("#labelInputForFormElements label").show();
jQuery("#addOptionnValueForFormElements").children("span").hide("slow");
jQuery("#addParametersForTextElements").children("span").hide('slow');
  jQuery("#addParametersForDatePicker").children("span").hide("slow");
  jQuery("#addParametersForTextInput").children("span").hide("slow");
  jQuery("#addParametersForButton").children("span").hide("slow");
        jQuery(".formElementLabel").attr("value",formname+"_"+formelementtype+"_");
         jQuery(".labelForFormTextInput").html("Enter a unique name for your "+formelementtype+" element:");
         jQuery("#submitFormElementName").attr("value","create new "+formelementtype+" element");
         //  $('*').unbind();
         jQuery('#submitFormElementName').click(function () {
           // $('*').unbind();
         var formElementLabel = jQuery('.formElementLabel').val();
//jQuery(this).after(FormElementLabel);
if (formElementLabel == "")
    {
jQuery("#labelInputForFormElements span").html("<br>A NULL field is not allowed!<br>Please Enter a Unique Name.");
jQuery("#labelInputForFormElements span").fadeIn(1500);
jQuery("#labelInputForFormElements span").fadeOut(1500);
    }
    else
        {

              jQuery('#submitFormElementName').unbind();
      var dataToPost = {"formName":formname, "formElementType":formelementtype, "formElementName":formElementLabel};
    var myurl="<?php echo $_SERVER[PHP_SELF];?>?module=formbuilder&action=createNewFormElement";
       jQuery.ajax({
      type:"POST",
      url:myurl,
      cache:false,
      data: dataToPost,
      async: false,
      success: function postSuccessFunction(html) {
          



         delete dataToPost.formName;
                  delete dataToPost.formElementType;
                  delete dataToPost.formElementName;
          //alert("Data Loaded: " + dataToPost);
        jQuery('#tempdivcontainer').html(html);
        
         var postSuccessBoolean =jQuery('#tempdivcontainer #postSuccess').html();
jQuery('#tempdivcontainer').empty();
         if (postSuccessBoolean == 1)
             {

            // $('*').unbind();
           jQuery("#labelInputForFormElements span").html("<br>A new "+formelementtype+" element has been made named \""+formElementLabel+"\".");
            jQuery("#labelInputForFormElements span").fadeIn(500);
            //jQuery("#labelInputForFormElements span").fadeOut(10000);
               jQuery('#tempdivcontainer').append(1);
                jQuery("#labelInputForFormElements input").hide();
                jQuery("#labelInputForFormElements label").hide();
                jQuery("#labelInputForFormElements").fadeOut(7500);

           }
               if (postSuccessBoolean == 0)
                   {
                jQuery("#labelInputForFormElements span").html(
                "<br> ERROR!! A new "+formelementtype+" element has NOT been made. <br> \""+formElementLabel+"\" already exists in the database. Please enter a unique form name.");
                           jQuery("#labelInputForFormElements span").fadeIn(3500);
                                           jQuery("#labelInputForFormElements input").hide();
                jQuery("#labelInputForFormElements label").hide();
                           insertNewFormElement();
           // jQuery("#labelInputForFormElements span").fadeOut(3500);
               jQuery('#tempdivcontainer').append(0);
               }
               if (postSuccessBoolean == 2)
                   {
                jQuery("#labelInputForFormElements span").html(
                "<br> ERROR!! A new "+formelementtype+" element has NOT been made <br> The form element tpye \""+formelementtype+"\" has not been configured or does not exist. Please Contact Software Administrator");
            jQuery("#labelInputForFormElements span").fadeIn(500);
            //jQuery("#labelInputForFormElements span").fadeOut(6500);
                jQuery('#tempdivcontainer').append(2);
               // jQuery("#labelInputForFormElements").show(3500);
               // jQuery("#labelInputForFormElements").hide();
                jQuery("#labelInputForFormElements input").hide();
                jQuery("#labelInputForFormElements label").hide();
                jQuery("#labelInputForFormElements").fadeOut(4500);
               }

        //jQuery('#tempdiv').empty();
 },
 complete: function callBackFunction()
 {
     if (jQuery('#tempdivcontainer').html() == 1)
       {
           PostSuccess=1;
   //insertNewFormElement();
          jQuery("*").unbind();
          jQuery("#addOptionnValueForFormElements .formEntityDefaultOption").show();
         insertPropertiesForFormElement(formelementtype, formElementLabel);
       }
       if(jQuery('#tempdivcontainer').html() == 0)
           {
//getInputFromFormElementLabel(formelementtype,formname);

           }
                  if(jQuery('#tempdivcontainer').html() == 2)
           {
fadeInOrOutFormElementsEditorMenus("#formElementsAdderMenu","Insert Menu","#formElementsAdderMenu");

           }
       return true;
 }

    });

   }

  });
//if (jQuery('#tempdivcontainer').html() == 1)
//{
return true;
//}
}
        function fadeInOrOutFormElementsEditorMenus(divId,option,divPredecessor)
   {
       if(option =="Remove Menu")
           {
              jQuery(divId).fadeOut(1000);
             jQuery('#input_add_form_elements_drop_down').val("default");
           }
           if(option =="Insert Menu")
               {
           jQuery('#input_add_form_elements_drop_down').val("default");
          jQuery(divId).clone().insertAfter(divPredecessor).remove();
           return jQuery(divId).fadeIn(1000);
               }
   }


   function insertPropertiesForFormElement(formelementtype, formElementLabel)
   {
        if (formelementtype == "HTML heading")
                  {


insertHTMLHeadingParameters(formElementLabel)
           }

      if (formelementtype == "label")
                  {
insertLabelParameters(formElementLabel);
           }
              if (formelementtype == "radio")
                  {
insertRadioEntityParameters(formElementLabel);
           }
                         if (formelementtype == "checkbox")
                  {
insertCheckBoxEntityParameters(formElementLabel);
           }
                                    if (formelementtype == "dropdown")
                  {
insertDropdownEntityParameters(formElementLabel);
           }
           if (formelementtype == "date picker")
               {
insertDatePickerParameters(formElementLabel);
               }

           if (formelementtype == "text input")
               {
insertTextInputParameters(formElementLabel);
               }
                          if (formelementtype == "text area")
               {
insertTextAreaParameters(formElementLabel);
               }
                                  if (formelementtype == "button")
               {
insertButtonParameters(formElementLabel);
               }

   }
   function insertButtonParameters(formElementLabel)
   {
     jQuery("#addParametersForButton").show();
          jQuery("#addParametersForButton").children("#stylingForButton").show("slow");
          jQuery("#addParametersForButton").children("#endOfInsertionButton").show("slow");
             jQuery("#addParametersForButton").children("#insertButtonName").hide("slow");
             jQuery("input:radio[name=resetOrSubmitButtonRadio]:eq(0)").attr('checked', "checked");
jQuery("#addParametersForButton").children("span").show();

jQuery("#submitButtonParameters").unbind('click').bind('click',function () {

   var buttonLabel = jQuery('.buttonLabel').val();
   var submitOrResetButtonChoice =  jQuery("input:radio[name=resetOrSubmitButtonRadio]:checked").val();
       if (buttonLabel == "")
           {
      jQuery("#addParametersForButton").children("span").html("\
<br>ERROR. A NULL field is not allowed. Please enter a label for your button.");
         jQuery("#addParametersForButton").children("span").show("slow");
           }
       else if (submitOrResetButtonChoice== "submit")
            {
jQuery("#addParametersForButton").children("span").html("\
<br>Parameters for a submit button labeled \""+buttonLabel+"\" has been configured.");
             jQuery("#addParametersForButton").children("#stylingForButton").hide("slow");
 jQuery("#addParametersForButton").children("#endOfInsertionButton").hide("slow");
  jQuery("#addParametersForButton").children("#insertButtonName").show("slow");
 jQuery("#addParametersForButton").children("span").show("slow");
        }
      else 
          {
jQuery("#addParametersForButton").children("span").html("\
<br>Parameters for a reset button labeled \""+buttonLabel+"\" has been configured.");
             jQuery("#addParametersForButton").children("#stylingForButton").hide("slow");
 jQuery("#addParametersForButton").children("#endOfInsertionButton").hide("slow");
  jQuery("#addParametersForButton").children("#insertButtonName").show("slow");
 jQuery("#addParametersForButton").children("span").show("slow");
          }
         
});

jQuery("#endOfInsertionButton").unbind("click").bind('click',function () {
  // var layoutOption= jQuery('input:radio[name=formEntityLayout]:checked').val();
   jQuery("#addParametersForButton").children("span").html("<br>A Button Enitity has been Inserted and Configured.<br>\n\
Please choose your next Form element.");
jQuery("#addParametersForButton").children("span").show("slow");
jQuery("#addParametersForButton").children("#stylingForButton").hide("slow");
jQuery("#addParametersForButton").children("#endOfInsertionButton").hide("slow");
jQuery("#addParametersForButton").children("#insertButtonName").hide("slow");
jQuery("*").unbind();
insertNewFormElement();

});

  jQuery("#submitNameforButton").click(function () {
 var formElementLabel = jQuery('.formElementLabel').val();
 var buttonLabel = jQuery('.buttonLabel').val();
   var submitOrResetButtonChoice =  jQuery("input:radio[name=resetOrSubmitButtonRadio]:checked").val();
 var buttonName = jQuery('.buttonName').val();
if (buttonName == "")
    {
jQuery("#addParametersForButton").children("span").html("<br>A NULL field is not allowed!<br>Please Complete Fields.");
jQuery("#addParametersForButton").children("span").fadeIn(1500);
jQuery("#addParametersForButton").children("span").fadeOut(1500);
    }
    else
        {
              var buttonDataToPost = {"formElementName": formElementLabel,
                  "buttonName": buttonName, "buttonLabel" : buttonLabel,
                  "submitOrResetButtonChoice": submitOrResetButtonChoice};
            var myurlToProduceButton ="<?php echo $_SERVER[PHP_SELF];?>?module=formbuilder&action=addEditButton";
  //jQuery('#tempdivcontainer').show();
 // jQuery('#tempdivcontainer').append(defaultSelected);
     jQuery("#submitNameforButton").unbind();
 jQuery('#tempdivcontainer').load(myurlToProduceButton, buttonDataToPost ,function postSuccessFunction(html) {
    //    alert("dat to post"+dataToPost);
        jQuery('#tempdivcontainer').html(html);
//jQuery('#tempdivcontainer').show();
            //jQuery('input:radio[name=simpleOrAdvancedTextAreaRadio]:checked').val("textarea");
var button = jQuery('#tempdivcontainer #WYSIWYGButton').html();
if (button == 0)
    {
        jQuery("#addParametersForButton").children("span").html(
           "<br> Error. A new button object \""+buttonName+"\" has NOT been made. <br>\n\
It already exists in the database. Please choose a unique label");
           // jQuery("input:radio[name=simpleOrAdvancedTextAreaRadio]:checked").val("textarea");
            //jQuery("input:radio[name=simpleOrAdvancedTextAreaRadio]:eq(0)").attr('checked', "checked");
                insertPropertiesForFormElement("button", formElementLabel);

    }
    else
        {
           jQuery("#WYSIWYGForm").append(button);
   jQuery("#addParametersForButton").children("span").html(" A new button Object \""+buttonName+"\" has been configured and stored.");
               //  jQuery("input:radio[name=simpleOrAdvancedTextAreaRadio]:eq(0)").attr('checked', "checked");

                insertPropertiesForFormElement("button", formElementLabel);

        }


           });

        }
        });

   }
function insertTextAreaParameters(formElementLabel)
{
    jQuery("#addParametersforTextArea").show();
     jQuery("#addParametersforTextArea").children("#stylingForTextArea").show("slow");
jQuery("#addParametersforTextArea").children("#stylingForTextArea").children("#toolbarChoiceMenu").hide("slow");
    jQuery("input:radio[name=toolbarChoiceRadio]").attr("disabled","disabled");
jQuery("#addParametersforTextArea").children("#endOfInsertionTextArea").show("slow");
   jQuery("#addParametersforTextArea").children("#insertTextAreaName").hide("slow");
   
     jQuery('.textAreaColumnSizeMenu').live('keypress keydown keyup',function() {
      
var test = jQuery('.textAreaColumnSizeMenu').val();
var numeric1= "0123456789";
var test1 = test.charAt(test.length-1);
var bool = numeric1.search(test1);
if (bool == -1)
    {
  var test= test.substr(0,test.length-1);
    }
  jQuery('.textAreaColumnSizeMenu').val(test);
       //jQuery('.textAreaColumnSizeMenu').die('keypress keydown keyup');
});

      jQuery('.textAreaRowSizeMenu').live('keypress keydown keyup',function() {
var test = jQuery('.textAreaRowSizeMenu').val();
var numeric1= "0123456789";
var test1 = test.charAt(test.length-1);
var bool = numeric1.search(test1);
if (bool == -1)
    {
  var test= test.substr(0,test.length-1);
    }
  jQuery('.textAreaRowSizeMenu').val(test);

});

   jQuery("input:radio[name=simpleOrAdvancedTextAreaRadio]").change(function(){

      if ( jQuery('input:radio[name=simpleOrAdvancedTextAreaRadio]:checked').val() == "textarea")
      {

      jQuery("input:radio[name=toolbarChoiceRadio]").attr("disabled","disabled");
      jQuery("#addParametersforTextArea").children("#stylingForTextArea").children("#toolbarChoiceMenu").hide("slow");
jQuery("input:radio[name=toolbarChoiceRadio]:eq(0)").attr('checked', "checked");
  }
      else
          {
     jQuery("input:radio[name=toolbarChoiceRadio]").removeAttr("disabled");
jQuery("#addParametersforTextArea").children("#stylingForTextArea").children("#toolbarChoiceMenu").show("slow");
jQuery("input:radio[name=toolbarChoiceRadio]:eq(0)").attr('checked', "checked");
          }

      });

  jQuery("#submitTextAreaParameters").unbind('click').bind('click',function () {
     jQuery("*").die('keypress keydown keyup');
          var textAreaColumnSize = jQuery('.textAreaColumnSizeMenu').val();
          var textAreaRowSize = jQuery('.textAreaRowSizeMenu').val();
                    var defaultText = jQuery("textarea[name=setDefaulttextArea]").val();
                           var toolbarChoice =  jQuery("input:radio[name=toolbarChoiceRadio]:checked").val();
        if (textAreaColumnSize <=1 || textAreaColumnSize >140 || textAreaRowSize <=1 ||textAreaRowSize >240)
            {
jQuery("#addParametersforTextArea").children("span").html("\
<br>ERROR. The text area size has to between 1 and 240");
         jQuery("#addParametersforTextArea").children("span").show("slow");
        }
      else if ( jQuery('input:radio[name=simpleOrAdvancedTextAreaRadio]:checked').val() == "textarea")
          {
//          var columnSize = jQuery('.textAreaColumnSizeMenu').val();
//          var rowSize = jQuery('.textAreaRowSizeMenu').val();
//
//          var simpleOrAdvancedHAChoice = jQuery("input:radio[name=simpleOrAdvancedTextAreaRadio]:checked").val();
//          var toolbarChoice =  jQuery("input:radio[name=toolbarChoiceRadio]").val();


       jQuery("#addParametersforTextArea").children("span").html("\
<br>The text field input size is set to \""+textAreaRowSize+"\" by \""+textAreaColumnSize+"\" characters.<br> \n\
The default text is set to \""+defaultText+"\" to be displayed inside the text field.<br> \n\
No Tool bar is chosen for the text area.");

             jQuery("#addParametersforTextArea").children("#stylingForTextArea").hide("slow");
 jQuery("#addParametersforTextArea").children("#endOfInsertionTextArea").hide("slow");
  jQuery("#addParametersforTextArea").children("#insertTextAreaName").show("slow");
 jQuery("#addParametersforTextArea").children("span").show("slow");
          }
          else
              {

       jQuery("#addParametersforTextArea").children("span").html("\
<br>The text field input size is set to \""+textAreaRowSize+"\" by \""+textAreaColumnSize+"\" characters.<br> \n\
The default text is set to \""+defaultText+"\" to be displayed inside the text field.<br> \n\
The text area tool bar is set to \""+toolbarChoice+"\".");

             jQuery("#addParametersforTextArea").children("#stylingForTextArea").hide("slow");
 jQuery("#addParametersforTextArea").children("#endOfInsertionTextArea").hide("slow");
  jQuery("#addParametersforTextArea").children("#insertTextAreaName").show("slow");
 jQuery("#addParametersforTextArea").children("span").show("slow");
              }
});

jQuery("#endOfInsertionTextArea").unbind("click").bind('click',function () {
  // var layoutOption= jQuery('input:radio[name=formEntityLayout]:checked').val();
   jQuery("#addParametersforTextArea").children("span").html("<br>A Text Area Enitity has been Inserted and Configured.<br>\n\
Please choose your next Form element.");
jQuery("#addParametersforTextArea").children("span").show("slow");
jQuery("#addParametersforTextArea").children("#stylingForTextArea").hide("slow");
jQuery("#addParametersforTextArea").children("#endOfInsertionTextArea").hide("slow");
jQuery("#addParametersforTextArea").children("#insertTextAreaName").hide("slow");
jQuery("*").unbind();
insertNewFormElement();

});

  jQuery("#submitTextforTextArea").click(function () {
var formElementLabel = jQuery('.formElementLabel').val();
 var textAreaColumnSize = jQuery('.textAreaColumnSizeMenu').val();
 var textAreaRowSize = jQuery('.textAreaRowSizeMenu').val();
 var defaultText = jQuery("textarea[name=setDefaulttextArea]").val();
 var simpleOrAdvancedHAChoice = jQuery("input:radio[name=simpleOrAdvancedTextAreaRadio]:checked").val();
 var toolbarChoice =  jQuery("input:radio[name=toolbarChoiceRadio]:checked").val();
 var textAreaName = jQuery('.textAreaName').val();
if (textAreaName == "")
    {
jQuery("#addParametersforTextArea").children("span").html("<br>A NULL field is not allowed!<br>Please Complete Fields.");
jQuery("#addParametersforTextArea").children("span").fadeIn(1500);
jQuery("#addParametersforTextArea").children("span").fadeOut(1500);
    }
    else
        {
              var textAreaDataToPost = {"formElementName": formElementLabel, 
                  "textAreaName": textAreaName, "textAreaValue" : defaultText,
                  "ColumnSize": textAreaColumnSize, "RowSize": textAreaRowSize, 
                  "simpleOrAdvancedHAChoice" : simpleOrAdvancedHAChoice,
              "toolbarChoice" : toolbarChoice};
            var myurlToProduceTextArea ="<?php echo $_SERVER[PHP_SELF];?>?module=formbuilder&action=addEditTextArea";
  //jQuery('#tempdivcontainer').show();
 // jQuery('#tempdivcontainer').append(defaultSelected);
     jQuery("#submitTextforTextArea").unbind();
 jQuery('#tempdivcontainer').load(myurlToProduceTextArea, textAreaDataToPost ,function postSuccessFunction(html) {
    //    alert("dat to post"+dataToPost);
        jQuery('#tempdivcontainer').html(html);
//jQuery('#tempdivcontainer').show();
            //jQuery('input:radio[name=simpleOrAdvancedTextAreaRadio]:checked').val("textarea");

var textArea = jQuery('#tempdivcontainer #WYSIWYGTextArea').html();
if (textArea == 0)
    {
        jQuery("#addParametersforTextArea").children("span").html(
           "<br> ERROR!! A new text area Object \""+textAreaName+"\" has NOT been made. <br>\n\
It already exists in the database. Please choose a unique label");
           // jQuery("input:radio[name=simpleOrAdvancedTextAreaRadio]:checked").val("textarea");
            jQuery("input:radio[name=simpleOrAdvancedTextAreaRadio]:eq(0)").attr('checked', "checked");
                insertPropertiesForFormElement("text area", formElementLabel);

    }
    else
        {
           jQuery("#WYSIWYGForm").append(textArea);
   jQuery("#addParametersforTextArea").children("span").html(" A new text area Object \""+textAreaName+"\" has been configured and stored.");
                 jQuery("input:radio[name=simpleOrAdvancedTextAreaRadio]:eq(0)").attr('checked', "checked");
                insertPropertiesForFormElement("text area", formElementLabel);

        }


           });

        }
        });

}

function insertTextInputParameters(formElementLabel)
{
  jQuery("#addParametersForTextInput").show();
 jQuery("#addParametersForTextInput").children("#StylingForTextInput").show("slow");
  jQuery("#addParametersForTextInput").children("#insertTextInputName").hide("slow");
jQuery("#addParametersForDatePicker").children("#insertDatePickerName").hide("slow");
jQuery("#addParametersForTextInput").children("#endOfInsertionTextInput").show("slow");
 jQuery("#addParametersForTextInput").children("span").show("slow");

    jQuery('.textInputSizeMenu').live('keypress keydown keyup',function() {
var test = jQuery('.textInputSizeMenu').val();
var numeric1= "0123456789";
var test1 = test.charAt(test.length-1);
var bool = numeric1.search(test1);
if (bool == -1)
    {
  var test= test.substr(0,test.length-1);
    }
  jQuery('.textInputSizeMenu').val(test);
  //     jQuery('.textInputSizeMenu').die('keypress keydown keyup');
});

   jQuery("input:radio[name=textorPasswordRadio]").change(function(){

      if ( jQuery('input:radio[name=textorPasswordRadio]:checked').val() == "text")
      {
      jQuery("#addParametersForTextInput").children("#StylingForTextInput").children('#setTextMenu').show('slow');
      jQuery("#addParametersForTextInput").children("#StylingForTextInput").children("#setMaskedInput").show("slow");
jQuery("input:radio[name=maskedInputChoice]").removeAttr("disabled");
jQuery("input:radio[name=maskedInputChoice]:eq(0)").attr('checked', "checked");
jQuery("textarea[name=setDefaulttext]").removeAttr("disabled");
jQuery("textarea[name=setDefaulttext]").val("Insert Default text to be displayed inside text input field.");
  }
      else
          {
     jQuery("#addParametersForTextInput").children("#StylingForTextInput").children('#setTextMenu').hide('slow');
      jQuery("#addParametersForTextInput").children("#StylingForTextInput").children("#setMaskedInput").hide("slow");
               jQuery("input:radio[name=maskedInputChoice]").attr("disabled","disabled");
               jQuery("textarea[name=setDefaulttext]").attr("disabled","disabled");
               jQuery("input:radio[name=maskedInputChoice]:eq(0)").attr('checked', "checked");
               jQuery("textarea[name=setDefaulttext]").val("");
          }

      });





  jQuery("#submitTextFieldParameters").unbind('click').bind('click',function () {
     jQuery("*").die('keypress keydown keyup');
          var textInputSize = jQuery('.textInputSizeMenu').val();
        if (textInputSize <= 1 || textInputSize >= 150)
            {
jQuery("#addParametersForTextInput").children("span").html("\
<br>ERROR!! The text input size has to between 1 and 150");
         jQuery("#addParametersForTextInput").children("span").show("slow");
        }

      else if ( jQuery('input:radio[name=textorPasswordRadio]:checked').val() == "text")
          {
          var defaultText = jQuery("textarea[name=setDefaulttext]").val();
          var maskedInputChoice = jQuery("input:radio[name=maskedInputChoice]:checked").val();

       jQuery("#addParametersForTextInput").children("span").html("\
<br>The text field input size is set to \""+textInputSize+"\" characters.<br> \n\
The default text is set to \""+defaultText+"\" to be displayed inside the text field.<br> \n\
The input mask is set to \""+maskedInputChoice+"\" to mask the text input.");
             jQuery("#addParametersForTextInput").children("#StylingForTextInput").hide("slow");
 jQuery("#addParametersForTextInput").children("#endOfInsertionTextInput").hide("slow");
  jQuery("#addParametersForTextInput").children("#insertTextInputName").show("slow");
 jQuery("#addParametersForTextInput").children("span").show("slow");
          }
          else
              {

             jQuery("#addParametersForTextInput").children("span").html("\
<br>The text field input size is set to \""+textInputSize+"\" characters.<br> \n\
The text input is set to a password field.");

             jQuery("#addParametersForTextInput").children("#StylingForTextInput").hide("slow");
 jQuery("#addParametersForTextInput").children("#endOfInsertionTextInput").hide("slow");
  jQuery("#addParametersForTextInput").children("#insertTextInputName").show("slow");
 jQuery("#addParametersForTextInput").children("span").show("slow");
              }
});

jQuery("#endOfInsertionTextInput").unbind("click").bind('click',function () {
  // var layoutOption= jQuery('input:radio[name=formEntityLayout]:checked').val();
   jQuery("#addParametersForTextInput").children("span").html("<br>A Text Input Enitity has been Inserted and Configured.<br>\n\
Please choose your next Form element.");
       // jQuery("#addOptionnValueForFormElements .formEntityDefaultOption").show();
      //  jQuery('input[name=Default Value]').attr('checked', false);
//jQuery("#addOptionnValueForFormElements span").fadeIn(100);
jQuery("#addParametersForTextInput").children("span").show("slow");
jQuery("#addParametersForTextInput").children("#StylingForTextInput").hide("slow");
jQuery("#addParametersForTextInput").children("#endOfInsertionTextInput").hide("slow");
jQuery("#addParametersForTextInput").children("#insertTextInputName").hide("slow");
//jQuery("#addOptionnValueForFormElements #insertOptionMenu").hide();
//jQuery("#addOptionnValueForFormElements #endOfInsertion").hide();
//jQuery("#formElementsAdderMenu").fadeIn(100);
jQuery("*").unbind();
insertNewFormElement();

});

    jQuery("#submitTextforTextInput").click(function () {

         var formElementLabel = jQuery('.formElementLabel').val();
        var textInputType =  jQuery('input:radio[name=textorPasswordRadio]:checked').val();
          var textInputSize = jQuery('.textInputSizeMenu').val();
          var defaultText = jQuery("textarea[name=setDefaulttext]").val();
          var maskedInputChoice = jQuery("input:radio[name=maskedInputChoice]:checked").val();
 var textInputName = jQuery('.textInputText').val();
if (textInputName == "")
    {
jQuery("#addParametersForTextInput").children("span").html("<br>A NULL field is not allowed!<br>Please Complete Fields.");
jQuery("#addParametersForTextInput").children("span").fadeIn(1500);
jQuery("#addParametersForTextInput").children("span").fadeOut(1500);
    }
    else
        {
              var textInputDataToPost = {"formElementName": formElementLabel, "textInputName": textInputName, "textInputValue" : defaultText, "textInputType": textInputType, "textInputSize": textInputSize, "maskedInputChoice" : maskedInputChoice};
            var myurlToProduceTextInput ="<?php echo $_SERVER[PHP_SELF];?>?module=formbuilder&action=addEditTextInput";
  //jQuery('#tempdivcontainer').show();
 // jQuery('#tempdivcontainer').append(defaultSelected);
     jQuery("#submitTextforTextInput").unbind();
 jQuery('#tempdivcontainer').load(myurlToProduceTextInput, textInputDataToPost ,function postSuccessFunction(html) {
    //    alert("dat to post"+dataToPost);
        jQuery('#tempdivcontainer').html(html);
//jQuery('#tempdivcontainer').show();
var textInput = jQuery('#tempdivcontainer #WYSIWYGTextInput').html();
if (textInput == 0)
    {
        jQuery("#addParametersForTextInput").children("span").html(
           "<br> ERROR!! A new text input Object \""+textInputName+"\" has NOT been made. <br>\n\
It already exists in the database. Please choose a unique label.//");
                insertPropertiesForFormElement("text input", formElementLabel);
    }
    else
        {
           jQuery("#WYSIWYGForm").append(textInput);
   jQuery("#addParametersForTextInput").children("span").html(" A new text input Object \""+textInputName+"\" has been configured and stored.");

                insertPropertiesForFormElement("text input", formElementLabel);
        }


           });

        }
        });


}

function insertDatePickerParameters(formElementLabel)
{
         jQuery("#addParametersForDatePicker").show(1000);
 jQuery("#addParametersForDatePicker").children("#datePickerParametersContainer").show("slow");
jQuery("#addParametersForDatePicker").children("#dateFormat").show("slow");
jQuery("#addParametersForDatePicker").children("span").show("slow");
jQuery("#addParametersForDatePicker").children("#datePickerParametersContainer").children("#selectDefaultDate").hide();
jQuery("#addParametersForDatePicker").children("#insertDatePickerName").hide();

jQuery("#endOfInsertionDatePicker").unbind("click").bind('click',function () {
  // var layoutOption= jQuery('input:radio[name=formEntityLayout]:checked').val();
   jQuery("#addParametersForDatePicker").children("span").html("<br>A Date Picker Enitity has been Inserted and Configured.<br>\n\
Please choose your next Form element.");
       // jQuery("#addOptionnValueForFormElements .formEntityDefaultOption").show();
      //  jQuery('input[name=Default Value]').attr('checked', false);
//jQuery("#addOptionnValueForFormElements span").fadeIn(100);
jQuery("#addParametersForDatePicker").children("span").show("slow");
jQuery("#addParametersForDatePicker").children("#datePickerParametersContainer").hide("slow");
jQuery("#addParametersForDatePicker").children("#insertDatePickerName").hide("slow");
//jQuery("#addOptionnValueForFormElements #insertOptionMenu").hide();
//jQuery("#addOptionnValueForFormElements #endOfInsertion").hide();
//jQuery("#formElementsAdderMenu").fadeIn(100);
jQuery("*").unbind();
insertNewFormElement();

});

      jQuery("input:radio[name=Default Date Choice]").change(function(){



      if ( jQuery('input:radio[name=Default Date Choice]:checked').val() == "Real Date")
      {

    jQuery("#addParametersForDatePicker").children("#datePickerParametersContainer").children("#selectDefaultDate").hide('slow');

jQuery("#addParametersForDatePicker").children("#datePickerParametersContainer").children("#selectDefaultDate :input").attr("disabled","disabled");
     //  jQuery("#tempdivcontainer").hide();
      }
      else
          {
      jQuery("#addParametersForDatePicker").children("#datePickerParametersContainer").children('#selectDefaultDate').show('slow');
      jQuery("#addParametersForDatePicker").children("#datePickerParametersContainer").children("#selectDefaultDate :input").removeAttr("disabled");

          }
      });




      jQuery("#submitDatePickerParameters").unbind('click').bind('click',function () {

          var dateFormat = jQuery('#input_Date_Format').val();
          //jQuery('#input_Date_Format').val("YYYY-MM-DD");
          


      if ( jQuery('input:radio[name=Default Date Choice]:checked').val() == "Custom Date")
          {
              var CustomDay = jQuery("#defaultDateSelection_Day_ID").val();
              var CustomMonth = jQuery("#defaultDateSelection_Month_ID option:selected").text();
              //var CustomMonth = jQuery("#defaultDateSelection_Month_ID").eq(value).text();
              var CustomYear = jQuery("#defaultDateSelection_Year_ID").val();
              var defaultCustomDate = CustomMonth+"-"+CustomDay+"-"+CustomYear;
       jQuery("#addParametersForDatePicker").children("span").html("\
<br>Date Format is set to \""+dateFormat+"\".<br> \n\
The default selected customized date is set to \""+defaultCustomDate+"\" for Date Picker Object.");
          }
          else
              {
                  defaultCustomDate = "Real Time Date";
                         jQuery("#addParametersForDatePicker").children("span").html("\
<br>Date Format is set to \""+dateFormat+"\".<br> \n\
The default selected customized date is set to \""+defaultCustomDate+"\" for Date Picker Object.");
              }
             // jQuery('input:radio[name=Default Date Choice]').val("Real Date");
//$('input:radio[name=Default Date Choice]').filter('[value="Real Date"]').attr('checked', true);

jQuery("#addParametersForDatePicker").children("span").show("slow");
jQuery("#addParametersForDatePicker").children("#datePickerParametersContainer").hide('slow');
jQuery("#addParametersForDatePicker").children("#insertDatePickerName").show("slow");

  });

           jQuery("#submitTextforDatePicker").click(function () {

         var formElementLabel = jQuery('.formElementLabel').val();
         var datePickerValue = jQuery('.datePickerText').val();


         var dateFormat = jQuery('#input_Date_Format').val();
          jQuery('#input_Date_Format').val("YYYY-MM-DD");

           if ( jQuery('input:radio[name=Default Date Choice]:checked').val() == "Custom Date")
          {
              var CustomDay = jQuery("#defaultDateSelection_Day_ID").val();
              var CustomMonth = jQuery("#defaultDateSelection_Month_ID option:selected").text();
              var CustomYear = jQuery("#defaultDateSelection_Year_ID").val();
              var defaultCustomDate = CustomMonth+"-"+CustomDay+"-"+CustomYear;
          }
          else
              {
                  defaultCustomDate = "Real Time Date";
              }
   jQuery('input:radio[name=Default Date Choice]').filter('[value="Real Date"]').attr('checked', true);

if (datePickerValue == "")
    {
jQuery("#addParametersForDatePicker").children("span").html("<br>A NULL field is not allowed!<br>Please Complete Fields.");
jQuery("#addParametersForDatePicker").children("span").fadeIn(1500);
jQuery("#addParametersForDatePicker").children("span").fadeOut(1500);
    }
    else
        {
              var datePickerDataToPost = {"datePickerName": formElementLabel, "datePickerValue": datePickerValue, "dateFormat": dateFormat, "defaultCustomDate":defaultCustomDate};
            var myurlToProduceDatePicker="<?php echo $_SERVER[PHP_SELF];?>?module=formbuilder&action=addEditDatePickerEntity";
  //jQuery('#tempdivcontainer').show();
 // jQuery('#tempdivcontainer').append(defaultSelected);
     jQuery("#submitTextforDatePicker").unbind();
 jQuery('#tempdivcontainer').load(myurlToProduceDatePicker, datePickerDataToPost ,function postSuccessFunction(html) {
    //    alert("dat to post"+dataToPost);
        //jQuery('#tempdivcontainer').html(html);

jQuery('#tempdivcontainer').show();


    var datePicker = jQuery('#tempdivcontainer #WYSIWYGDatepicker').html();
    if (datePicker == 0)
        {
          jQuery("#addParametersForDatePicker").children("span").html(
           "<br> ERROR!! A new date Picker Object \""+datePickerValue+"\" has NOT been made. <br>\n\
It already exists in the database. Please choose a unique label.");

                insertPropertiesForFormElement("date picker", formElementLabel);
        }
        else
            {
                      
                      jQuery("#WYSIWYGForm").append(datePicker);
                  jQuery("#addParametersForDatePicker").children("span").html(" A new date Picker Object \""+datePickerValue+"\" has been configured and stored.");
jQuery("#WYSIWYGForm").append("[JavaScript Conflict: Date Picker Object can not be displayed.\n\
It \"will\" be displayed in the built form.]<br>");
                insertPropertiesForFormElement("date picker", formElementLabel);
            }
   
           });
                       
        }
        });
}

function insertHTMLHeadingParameters(formElementLabel)
{
  jQuery("#addParametersForTextElements").show();
//jQuery("#addParametersForTextElements").fadeIn(1000);
jQuery("#addParametersForTextElements").children("#selectLayoutMenu").show("slow");
//jQuery("#addParametersForTextElements #selectLayoutMenu").show();
jQuery("#addParametersForTextElements").children("#selectLayoutMenu").children("#LayoutMenu").hide();
jQuery("#addParametersForTextElements").children("#insertTextMenu").hide();
jQuery("#addParametersForTextElements").children("#selectLayoutMenu").children("#SizeMenu").show();
jQuery("#addParametersForTextElements").children("#selectLayoutMenu").children("#AlignMenu").show();

jQuery("#submitTextLayout").click(function () {
jQuery("#addParametersForTextElements").children("#selectLayoutMenu").hide("slow");
jQuery("#addParametersForTextElements").children("#insertTextMenu").show("slow");
});

jQuery("#submitEndOfInsertionText").click(function () {
jQuery("#addParametersForTextElements").children("span").html("<br>A HTML Heading object has been Inserted and Configured.<br>\n\
Please choose your next Form element.");
jQuery("#addParametersForTextElements").children("#selectLayoutMenu").show();
jQuery("#addParametersForTextElements").children("span").show("slow");
//jQuery("#addParametersForTextElements span").fadeOut(2000);
jQuery("#addParametersForTextElements").children("#selectLayoutMenu").hide();
jQuery("#addParametersForTextElements").children("#insertTextMenu").hide();
jQuery("*").unbind();
insertNewFormElement();


});

 jQuery("#submitTextforFormElement").click(function () {
         var HTMLHeadingValue = jQuery('.formEntityText').val();
         var fontSize = jQuery('input:radio[name=formEntitySize]:checked').val();
         var textAlignment = jQuery('input:radio[name=formEntityAlign]:checked').val();
         var formElementLabel = jQuery('.formElementLabel').val();
if (HTMLHeadingValue == "")
    {
jQuery("#addParametersForTextElements").children("span").html("<br>A NULL field is not allowed!<br>Please Complete Fields.");
jQuery("#addParametersForTextElements").children("span").fadeIn(1500);
jQuery("#addParametersForTextElements").children("span").fadeOut(1500);
    }
    else
        {

             var dataToPost = {"formElementName": formElementLabel, "HTMLHeadingValue": HTMLHeadingValue, "fontSize": fontSize,"textAlignment": textAlignment};
     var myurlToCreateHTMLHeading ="<?php echo $_SERVER[PHP_SELF];?>?module=formbuilder&action=addEditHTMLHeadingEntity";
     jQuery("#submitTextforFormElement").unbind();
 jQuery('#tempdivcontainer').load(myurlToCreateHTMLHeading, dataToPost ,function postSuccessFunction(html) {

     jQuery('#tempdivcontainer').html(html);

    var HTMLheading = jQuery('#tempdivcontainer #WYSIWYGHTMLHeading').html();
    if (HTMLheading == 0)
        {
          jQuery("#addParametersForTextElements").children("span").html(
           "<br> ERROR!! A new HTML Heading \""+HTMLHeadingValue+"\" has NOT been made. <br> \n\
\""+HTMLHeadingValue+"\" already exists for the heading named \""+formElementLabel+"\". Please enter some unique HTML Heading or text.");
          //  jQuery("#addParametersForTextElements span").fadeIn(1000);
           // jQuery("#addOptionnValueForFormElements span").fadeOut(2500);
            jQuery("#addParametersForTextElements").children("#insertTextMenu").hide("slow");
                insertPropertiesForFormElement("HTML heading", formElementLabel);
        }
        else
            {
                  jQuery("#addParametersForTextElements").children("span").html("A HTML heading \""+formElementLabel+"\" has been configured and stored.");

                //jQuery("#addOptionnValueForFormElements #endOfInsertion").show();
    jQuery("#WYSIWYGForm").append(HTMLheading);
    insertPropertiesForFormElement("HTML heading", formElementLabel);
            }
 });
        }
  });
}
   function insertLabelParameters(formElementLabel)
{

jQuery("#addParametersForTextElements").show();
//jQuery("#addParametersForTextElements").fadeIn(1000);
jQuery("#addParametersForTextElements").children("#selectLayoutMenu").show("slow");
jQuery("#addParametersForTextElements").children("#selectLayoutMenu").children("#LayoutMenu").show();
jQuery("#addParametersForTextElements").children("#insertTextMenu").hide();
jQuery("#addParametersForTextElements").children("#selectLayoutMenu").children("#SizeMenu").hide();
jQuery("#addParametersForTextElements").children("#selectLayoutMenu").children("#AlignMenu").hide();

jQuery("#submitTextLayout").unbind("click").bind('click',function () {
jQuery("#addParametersForTextElements").children("#selectLayoutMenu").hide('slow');
jQuery("#addParametersForTextElements").children("#insertTextMenu").show('slow');
});

jQuery("#submitEndOfInsertionText").unbind('click').bind('click',function () {

jQuery("#addParametersForTextElements").children("span").html("<br>A Label has been Inserted and Configured.<br>\n\
Please choose your next Form element.").unbind();
jQuery("#addParametersForTextElements").children("#selectLayoutMenu").show().unbind();
jQuery("#addParametersForTextElements").children("span").show('slow');
////jQuery("#addParametersForTextElements span").fadeOut(2000);
jQuery("#addParametersForTextElements").children("#selectLayoutMenu").hide("slow");
jQuery("#addParametersForTextElements").children("#insertTextMenu").hide("slow");
//jQuery("*").unbind();
insertNewFormElement();


});

  jQuery("#submitTextforFormElement").unbind('click').bind('click',function () {
         var labelValue = jQuery('.formEntityText').val();
         var layoutOption= jQuery('input:radio[name=textLayout]:checked').val();
         var formElementLabel = jQuery('.formElementLabel').val();
if (labelValue == "")
    {
jQuery("#addParametersForTextElements").children("span").html("<br>A NULL field is not allowed!<br>Please Complete Fields.");
jQuery("#addParametersForTextElements").children("span").fadeIn(1500);
jQuery("#addParametersForTextElements").children("span").fadeOut(1500);
    }
    else
        {
             var dataToPost = {"labelValue":labelValue, "formElementName":formElementLabel, "layoutOption":layoutOption};
     var myurlToCreateLabel ="<?php echo $_SERVER[PHP_SELF];?>?module=formbuilder&action=addEditLabelEntity";
     jQuery("#submitTextforFormElement").unbind();
 jQuery('#tempdivcontainer').load(myurlToCreateLabel, dataToPost ,function postSuccessFunction(html) {
     jQuery('#tempdivcontainer').html(html);

    var label = jQuery('#tempdivcontainer #WYSIWYGLabel').html();
    if (label == 0)
        {
          jQuery("#addParametersForTextElements").children("span").html(
           "<br> ERROR!! A new label \""+labelValue+"\" has NOT been made. <br> \n\
\""+labelValue+"\" already exists for \""+formElementLabel+"\" label. Please enter a unique label value.");
           // jQuery("#addParametersForTextElements span").fadeIn(1000);
           // jQuery("#addOptionnValueForFormElements span").fadeOut(2500);
            jQuery("#addParametersForTextElements").children("#insertTextMenu").hide("slow");
                insertPropertiesForFormElement("label", formElementLabel);
        }
        else
            {
                  jQuery("#addParametersForTextElements").children("span").html("A label \""+formElementLabel+"\" has been configured and stored.");

                //jQuery("#addOptionnValueForFormElements #endOfInsertion").show();
    jQuery("#WYSIWYGForm").append(label);
    insertPropertiesForFormElement("label", formElementLabel);
            }
 });
        }
  });

   }

function insertDropdownEntityParameters(formElementLabel)
{
jQuery("#addOptionnValueForFormElements").show();
jQuery("#addOptionnValueForFormElements").children("#selectLayoutMenu").show("slow");
jQuery("#addOptionnValueForFormElements").children("#selectLayoutMenu").children("#LayoutMenu").hide();
jQuery("#addOptionnValueForFormElements #submitFormElementLayout").show();
jQuery("#addOptionnValueForFormElements #submitFormElementLayout").attr("value","Insert Option for Dropdown Menu");
//jQuery("label[for='submitFormElementLayout']").html("Select Layout for Dropdown Entity:");
jQuery("#addOptionnValueForFormElements").children("span").show("slow");
//jQuery("#addOptionnValueForFormElements").fadeIn(1000);
jQuery("#addOptionnValueForFormElements #insertOptionMenu").hide();
jQuery("#addOptionnValueForFormElements #endOfInsertion").show();
    
jQuery("#submitFormElementLayout").unbind('click').bind('click',function () {
     //jQuery("#addOptionnValueForFormElements span").html("<br>Layout for Dropdown Option Has Been Stored.");
//jQuery("#addOptionnValueForFormElements span").fadeIn(8500);
//jQuery("#addOptionnValueForFormElements span").fadeOut(2500);
jQuery("#addOptionnValueForFormElements").children("#selectLayoutMenu").hide("slow");
jQuery("#addOptionnValueForFormElements").children("#insertOptionMenu").show("slow");

});
jQuery("#submitEndOfInsetion").unbind('click').bind('click',function () {

jQuery("#addOptionnValueForFormElements").children("span").html("<br>A Dropdown Enitity has been Inserted and Configured.<br>\n\
Please choose your next Form element.").unbind();
jQuery("#addOptionnValueForFormElements").children("#selectLayoutMenu").children("#LayoutMenu").show();
jQuery("#addOptionnValueForFormElements").children("span").show("slow").unbind();
//jQuery("#addOptionnValueForFormElements span").fadeOut(1000);
jQuery("#addOptionnValueForFormElements").children("#selectLayoutMenu").hide("slow").unbind();
jQuery("#addOptionnValueForFormElements #insertOptionMenu").hide(100);
jQuery("#addOptionnValueForFormElements #endOfInsertion").hide();
//jQuery("#addOptionnValueForFormElements .formEntityDefaultOption").show();
jQuery('input[name=Default Value]').attr('checked', false).unbind();
jQuery("*").unbind();
insertNewFormElement();


});

if (jQuery(".formEntityDefaultOption:checked").val() == "on" )
    {
      jQuery("#addOptionnValueForFormElements .formEntityDefaultOptionLabel").html("Selected Default Option has been chosen.");
      jQuery("#addOptionnValueForFormElements .formEntityDefaultOption").hide();
     jQuery('input[name=Default Value]').attr('checked', false);
     //jQuery("#addOptionnValueForFormElements .formEntityDefaultOptionLabel").hide(5500);
    // jQuery("#addOptionnValueForFormElements .formEntityDefaultOptionLabel").fadeOut(2500);



}
else
    {
jQuery("#addOptionnValueForFormElements .formEntityDefaultOptionLabel").html("Set this option selected as default.");
      jQuery("#addOptionnValueForFormElements .formEntityDefaultOption").show();
}
    
jQuery("#addOptionnValueForFormElements .formEntityDefaultOptionLabel").html("Set this this option selected as default.");
         jQuery(".formEntityValueOption").html("Enter a value for your dropdown option:");
         jQuery(".formEntityLabelOption").html("Enter a label for your dropdown option:");
         jQuery("#submitOptionforFormElement").attr("value","Insert Dropdown Option");

            jQuery("#submitOptionforFormElement").unbind('click').bind('click',function () {
         var optionValue = jQuery('.formEntityValue').val();
         var optionLabel = jQuery('.formEntityLabel').val();
         //var layoutOption= jQuery('input:radio[name=formEntityLayout]:checked').val();
         var defaultSelected = jQuery(".formEntityDefaultOption:checked").val();
         var formElementLabel = jQuery('.formElementLabel').val();

//jQuery(this).after(FormElementLabel);
if (optionValue == "" ||optionLabel == "")
    {
jQuery("#addOptionnValueForFormElements").children("span").html("<br>A NULL field is not allowed!<br>Please Complete Fields.");
jQuery("#addOptionnValueForFormElements").children("span").fadeIn(1500);
jQuery("#addOptionnValueForFormElements").children("span").fadeOut(1500);
    }
 else
   {
     var dataToPost = {"optionValue":optionValue, "optionLabel":optionLabel, "formElementName":formElementLabel, "defaultSelected":defaultSelected};
  var myurl="<?php echo $_SERVER[PHP_SELF];?>?module=formbuilder&action=addEditDropdownEntity";
 // jQuery('#tempdivcontainer').show();
     jQuery("#submitOptionforFormElement").unbind();
 jQuery('#tempdivcontainer').load(myurl, dataToPost ,function postSuccessFunction(html) {
        jQuery('#tempdivcontainer').html(html);
    var dropdown = jQuery('#tempdivcontainer #WYSIWYGDropdown').html();
    if (dropdown == 0)
        {
          jQuery("#addOptionnValueForFormElements").children("span").html(
           "<br> ERROR!! A new dropdown option \""+optionValue+"\" has NOT been made. <br> \n\
\""+optionValue+"\" option already exists for \""+formElementLabel+"\" dropdown. Please enter a unique option value.");
           // jQuery("#addOptionnValueForFormElements span").fadeIn(1000);
           // jQuery("#addOptionnValueForFormElements span").fadeOut(3500);
            jQuery("#addOptionnValueForFormElements").children("#insertOptionMenu").hide("slow");
            jQuery('input[name=Default Value]').attr('checked', false);
                insertPropertiesForFormElement("dropdown", formElementLabel);
        }
else
    {
if (jQuery("#WYSIWYGForm").children("#input_"+formElementLabel).length == false)
    {
        jQuery("#WYSIWYGForm").append('<div id =input_'+formElementLabel+'></div>');
     jQuery("#WYSIWYGForm").children("#input_"+formElementLabel).replaceWith(dropdown);
                   jQuery("#addOptionnValueForFormElements").children("span").html(
           "An option for the \""+formElementLabel+"\" dropdown menu has been configured and stored.");
    }

//            if ($("#WYSIWYGForm #input_"+formElementLabel).length > 0)
            else
            {
            
              jQuery("#WYSIWYGForm").children("#input_"+formElementLabel).replaceWith(dropdown);
              jQuery("#addOptionnValueForFormElements").children("span").html(
           "An option for the \""+formElementLabel+"\" dropdown menu has been configured and stored.");

       }

                                 if (jQuery(".formEntityDefaultOption:checked").val() =="on")
                {
            jQuery("#addOptionnValueForFormElements .formEntityDefaultOption").hide();
           jQuery("#addOptionnValueForFormElements").children("span").html(
           "An option for the \""+formElementLabel+"\" dropdown menu has been configured and stored.<br>\n\
<br> The option with value \""+optionValue+"\" has been set as default");
                 //   insertPropertiesForFormElement("dropdown", formElementLabel);

                }
                    insertPropertiesForFormElement("dropdown", formElementLabel);
    }

            }


);

      //  jQuery('#tempdivcontainer').show();

        }
   });
   }

function insertCheckBoxEntityParameters(formElementLabel)
{

//s("*").unbind();
jQuery("#addOptionnValueForFormElements").show();
jQuery("#addOptionnValueForFormElements").children("#selectLayoutMenu").show("slow");

//jQuery("#addOptionnValueForFormElements").fadeIn(1000);
//jQuery("#addOptionnValueForFormElements #selectLayoutMenu").show();
jQuery("label[for='submitFormElementLayout']").html("Select Layout for Checkbox Entity:");
jQuery("#addOptionnValueForFormElements #insertOptionMenu").hide();
jQuery("#addOptionnValueForFormElements #endOfInsertion").show();
jQuery("#addOptionnValueForFormElements .formEntityDefaultOption").show();
jQuery("#addOptionnValueForFormElements").children("span").show("slow");
jQuery("#addOptionnValueForFormElements #submitFormElementLayout").attr("value","Apply Layout and Insert Option for Checkbox Item");

jQuery("#submitFormElementLayout").unbind('click').bind('click',function () {
     jQuery("#addOptionnValueForFormElements").children("span").html("<br>Layout for Checkbox Option Has Been Stored.");
//jQuery("#addOptionnValueForFormElements span").fadeIn(8500);
jQuery("#addOptionnValueForFormElements").children("span").show("slow");
jQuery("#addOptionnValueForFormElements").children("#selectLayoutMenu").hide("slow");
//jQuery("#addOptionnValueForFormElements #selectLayoutMenu").fadeOut(1500);
jQuery("#addOptionnValueForFormElements #insertOptionMenu").fadeIn(500);

});
jQuery("#submitEndOfInsetion").unbind('click').bind('click',function () {

jQuery("#addOptionnValueForFormElements").children("span").html("<br>A Checkbox Enitity has been Inserted and Configured.<br>\n\
Please choose your next Form element.").unbind();
jQuery("#addOptionnValueForFormElements").children("span").show("slow").unbind();
//jQuery("#addOptionnValueForFormElements").children("span").fadeOut(1000);
jQuery("#addOptionnValueForFormElements").children("#selectLayoutMenu").hide("slow").unbind();
//jQuery("#addOptionnValueForFormElements #selectLayoutMenu").hide(100);
jQuery("#addOptionnValueForFormElements #insertOptionMenu").hide();
jQuery("#addOptionnValueForFormElements #endOfInsertion").hide();
jQuery('input[name=Default Value]').attr('checked', false);

insertNewFormElement();
});

//if (jQuery(".formEntityDefaultOption:checked").val() == "on" )
//    {
//      jQuery("#addOptionnValueForFormElements .formEntityDefaultOptionLabel").html("Selected Default Option has been chosen.");
//     jQuery('input[name=Default Value]').attr('checked', false);
//     jQuery("#addOptionnValueForFormElements .formEntityDefaultOptionLabel").hide(5500);
//    // jQuery("#addOptionnValueForFormElements .formEntityDefaultOptionLabel").fadeOut(2500);
//
//
//
//}

jQuery("#addOptionnValueForFormElements .formEntityDefaultOptionLabel").html("\"Check\" this this option selected as default.");
         jQuery(".formEntityValueOption").html("Enter a value for your checkbox option:");
         jQuery(".formEntityLabelOption").html("Enter a label for your checkbox option:");
         jQuery("#submitOptionforFormElement").attr("value","Insert Checkbox Option");

            jQuery("#submitOptionforFormElement").click(function () {
         var optionValue = jQuery('.formEntityValue').val();
         var optionLabel = jQuery('.formEntityLabel').val();
         var layoutOption= jQuery('input:radio[name=formEntityLayout]:checked').val();
         var defaultSelected = jQuery(".formEntityDefaultOption:checked").val();
         var formElementLabel = jQuery('.formElementLabel').val();

//jQuery(this).after(FormElementLabel);
if (optionValue == "" ||optionLabel == "")
    {
jQuery("#addOptionnValueForFormElements span").html("<br>A NULL field is not allowed!<br>Please Complete Fields.");
jQuery("#addOptionnValueForFormElements span").fadeIn(1500);
jQuery("#addOptionnValueForFormElements span").fadeOut(1500);
    }
 else
   {
     var dataToPost = {"optionValue":optionValue, "optionLabel":optionLabel, "formElementName":formElementLabel, "defaultSelected":defaultSelected, "layoutOption":layoutOption};
  var myurl="<?php echo $_SERVER[PHP_SELF];?>?module=formbuilder&action=addEditCheckboxEntity";
  jQuery('#tempdivcontainer').show();
     jQuery("#submitOptionforFormElement").unbind();
 jQuery('#tempdivcontainer').load(myurl, dataToPost ,function postSuccessFunction(html) {
        jQuery('#tempdivcontainer').html(html);
    var checkbox = jQuery('#tempdivcontainer #WYSIWYGCheckbox').html();
    if (checkbox == 0)
        {
          jQuery("#addOptionnValueForFormElements span").html(
           "<br> ERROR!! A new checkbox option \""+optionValue+"\" has NOT been made. <br> \n\
\""+optionValue+"\" option already exists for \""+formElementLabel+"\" checkbox. Please enter a unique option value.");
            //jQuery("#addOptionnValueForFormElements span").fadeIn(2000);
           // jQuery("#addOptionnValueForFormElements span").fadeOut(2500);
            jQuery("#addOptionnValueForFormElements #insertOptionMenu").hide(100);
                insertPropertiesForFormElement("checkbox", formElementLabel);
                            jQuery('input[name=Default Value]').attr('checked', false);
        }
        else
            {
                            if (defaultSelected =="on")
                {
           // jQuery("#addOptionnValueForFormElements .formEntityDefaultOption").hide();
           // jQuery("#addOptionnValueForFormElements .formEntityDefaultOptionLabel").html("Selected Default Option has been chosen.");
           // jQuery(".formEntityDefaultOption").attr("checked",false);
            jQuery('input[name=Default Value]').attr('checked', false);
                }
            jQuery("#addOptionnValueForFormElements span").html("An option for the \""+formElementLabel+"\" checkbox has been configured and stored.");
            //    jQuery("#addOptionnValueForFormElements #endOfInsertion").show();
    jQuery("#WYSIWYGForm").append(checkbox);
    insertPropertiesForFormElement("checkbox", formElementLabel);






            }

}
);

        jQuery('#tempdivcontainer').hide();

        }
   });
   }

   function insertRadioEntityParameters(formElementLabel)
   {
//jQuery("*").unbind();
jQuery("#addOptionnValueForFormElements").show();
jQuery("#addOptionnValueForFormElements").children("#selectLayoutMenu").show("slow");
//jQuery("#addOptionnValueForFormElements #selectLayoutMenu").show("slow");
//jQuery("#addOptionnValueForFormElements #selectLayoutMenu").fadeIn(1000);
jQuery("label[for='submitFormElementLayout']").html("Select Layout for Radio Entity:");
jQuery("#addOptionnValueForFormElements #insertOptionMenu").hide();
jQuery("#addOptionnValueForFormElements #endOfInsertion").show();
            jQuery("#addOptionnValueForFormElements").children("span").show("slow");

            jQuery("#addOptionnValueForFormElements #submitFormElementLayout").attr("value","Apply Layout and Insert Option for Radio Entity");
        //    jQuery("#addOptionnValueForFormElements span").fadeOut(2500);
///////jQuery("#addOptionnValueForFormElements .formEntityDefaultOption").show();
jQuery("#submitFormElementLayout").unbind('click').bind('click',function () {
   //var layoutOption= jQuery('input:radio[name=formEntityLayout]:checked').val();
jQuery("#addOptionnValueForFormElements").children("span").html("<br>Layout for Radio Option Has Been Stored.");
//jQuery("#addOptionnValueForFormElements span").fadeIn(3500);
jQuery("#addOptionnValueForFormElements").children("span").show("slow");
jQuery("#addOptionnValueForFormElements").children("#selectLayoutMenu").hide("slow");
jQuery("#addOptionnValueForFormElements #insertOptionMenu").fadeIn(1000);

});
jQuery("#submitEndOfInsetion").unbind("click").bind('click',function () {
  // var layoutOption= jQuery('input:radio[name=formEntityLayout]:checked').val();
   jQuery("#addOptionnValueForFormElements").children("span").html("<br>A Radio Enitity has been Inserted and Configured.<br>\n\
Please choose your next Form element.");
        jQuery("#addOptionnValueForFormElements .formEntityDefaultOption").show();
        jQuery('input[name=Default Value]').attr('checked', false);
//jQuery("#addOptionnValueForFormElements span").fadeIn(100);
jQuery("#addOptionnValueForFormElements").children("span").show("slow");
jQuery("#addOptionnValueForFormElements").children("#selectLayoutMenu").hide("slow");
jQuery("#addOptionnValueForFormElements #insertOptionMenu").hide();
jQuery("#addOptionnValueForFormElements #endOfInsertion").hide();
//jQuery("#formElementsAdderMenu").fadeIn(100);
jQuery("*").unbind();
insertNewFormElement();

});


//jQuery("#addOptionnValueForFormElements .formEntityDefaultOption").show();
if (jQuery(".formEntityDefaultOption:checked").val() == "on" )
    {
      jQuery("#addOptionnValueForFormElements .formEntityDefaultOptionLabel").html("Selected Default Option has been chosen.");
     jQuery('input[name=Default Value]').attr('checked', false);
     jQuery("#addOptionnValueForFormElements .formEntityDefaultOptionLabel").hide(5500);
    // jQuery("#addOptionnValueForFormElements .formEntityDefaultOptionLabel").fadeOut(2500);

}
else
    {
jQuery("#addOptionnValueForFormElements .formEntityDefaultOptionLabel").html("Set this option selected as default.");
     //   jQuery("#addOptionnValueForFormElements .formEntityDefaultOptionLabel").show();
 }
 // jQuery("#addOptionnValueForFormElements span").hide();
      // jQuery("#labelInputForFormElements").fadeIn(1000);
      //  jQuery(".formEntityValueOption").attr("value",formname+"_"+formelementtype+"_");
         jQuery(".formEntityValueOption").html("Enter a value for your radio option:");
         jQuery(".formEntityLabelOption").html("Enter a label for your radio option:");
         jQuery("#submitOptionforFormElement").attr("value","Insert Radio Option");

         
           jQuery("#submitOptionforFormElement").click(function () {
         var optionValue = jQuery('.formEntityValue').val();
         var optionLabel = jQuery('.formEntityLabel').val();
         var layoutOption= jQuery('input:radio[name=formEntityLayout]:checked').val();
         var defaultSelected = jQuery(".formEntityDefaultOption:checked").val();
         var formElementLabel = jQuery('.formElementLabel').val();

//jQuery(this).after(FormElementLabel);
if (optionValue == "" ||optionLabel == "")
    {
jQuery("#addOptionnValueForFormElements").children("span").html("<br>A NULL field is not allowed!<br>Please Complete Fields.");
jQuery("#addOptionnValueForFormElements").children("span").fadeIn(1500);
jQuery("#addOptionnValueForFormElements").children("span").fadeOut(1500);
    }
    else
        {

         var dataToPost = {"optionValue":optionValue, "optionLabel":optionLabel, "formElementName":formElementLabel, "defaultSelected":defaultSelected, "layoutOption":layoutOption};
            var myurl="<?php echo $_SERVER[PHP_SELF];?>?module=formbuilder&action=addEditRadioEntity";
  //jQuery('#tempdivcontainer').show();
 // jQuery('#tempdivcontainer').append(defaultSelected);
     jQuery("#submitOptionforFormElement").unbind();
 jQuery('#tempdivcontainer').load(myurl, dataToPost ,function postSuccessFunction(html) {
    //    alert("dat to post"+dataToPost);
        jQuery('#tempdivcontainer').html(html);
    var radio = jQuery('#tempdivcontainer #WYSIWYGRadio').html();
    if (radio == 0)
        {
          jQuery("#addOptionnValueForFormElements").children("span").html(
           "<br> ERROR!! A new radio option \""+optionValue+"\" has NOT been made. <br> \n\
\""+optionValue+"\" option already exists for \""+formElementLabel+"\" radio. Please enter a unique option value.");
           // jQuery("#addOptionnValueForFormElements span").fadeIn(1000);
           // jQuery("#addOptionnValueForFormElements span").fadeOut(2500);
            jQuery("#addOptionnValueForFormElements #insertOptionMenu").hide();
                insertPropertiesForFormElement("radio", formElementLabel);
        }
        else
            {
                            if (defaultSelected =="on")
                {
            jQuery("#addOptionnValueForFormElements .formEntityDefaultOption").hide();
            //jQuery("#addOptionnValueForFormElements .formEntityDefaultOptionLabel").html("Selected Default Option has been chosen.");
           // jQuery(".formEntityDefaultOption").attr("checked",false);
           // jQuery('input[name=Default Value]').attr('checked', false);
                }
                  jQuery("#addOptionnValueForFormElements").children("span").html("An option for the \""+formElementLabel+"\" radio has been configured and stored.\n\
            <br> The option with value \""+optionValue+"\" has been set as default.");

                jQuery("#addOptionnValueForFormElements #endOfInsertion").show();
    jQuery("#WYSIWYGForm").append(radio);
    insertPropertiesForFormElement("radio", formElementLabel);
            }
//jQuery('#tempdivcontainer').empty();
}
);
  //  $("#submitOptionforFormElement").unbind();
        //  jQuery('#tempdivcontainer').html(html);
        // jQuery('#tempdivcontainer').s();
        //jQuery('#tempdivcontainer').hide();
         //jQuery('#tempdivcontainer').after(WYSIWYGRadioEntity);
       // jQuery("#WYSIWYGForm").append(WYSIWYGRadioEntity);
//jQuery('#tempdivcontainer').empty();
//  jQuery.ajax({
//    type:"POST",
//    url:myurl,
//    data: dataToPost,
//    success: function (html) {
//      jQuery('#tempdivcontainer').html(html);
//      jQuery('#tempdivcontainer').show();
//    }
//  });



        }
   });
   }




    </script>
