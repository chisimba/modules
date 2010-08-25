<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$this->appendArrayVar('headerParams', $this->getJavascriptFile('1.4.2/jquery-1.4.2.min.js', 'jquery'));
//Get the CSS layout to make two column layout
$cssLayout = $this->newObject('csslayout', 'htmlelements');
//Add some text to the left column
//$cssLayout->setLeftColumnContent("Place holder text");
//get the editform object and instantiate it
$objEditForm = $this->getObject('add_form_parameters_form', 'formbuilder');
//Add the form to the middle (right in two column layout) area
$cssLayout->setMiddleColumnContent($objEditForm->show());
echo $cssLayout->show();


?>
<span id="errorMessageDiv"></span>
<div id="tempdivcontainer"></div>
<script type="text/javascript">


    jQuery(document).ready(function() {

//jQuery(':input[name=submitNewFormDetails]',this).attr("disabled","true");
//jQuery(':input[name=submitNewFormDetails]').children('span').children('span').children('span').html("Complete All Fields");
//jQuery(':input[name=submitNewFormDetails]').children('span').children('span').children('span').removeClass();
//jQuery(':input[name=submitNewFormDetails]').children('span').children('span').children('span').addClass('decline');
  
     testSubmitButton();
     
  jQuery(':input[name=submitNewFormDetails]').unbind('click').bind('click',function () {
  
var formTitle = jQuery(':input[name=formTitle]').val();
var formLabel = jQuery(':input[name=formLabel]').val();
var formDescription = jQuery('textarea[name=formCaption]').val();

          var formParametersToPost = {"formTitle": formTitle,
                  "formLabel": formLabel,
                  "formDescription" : formDescription};

            var myurlToStoreFormParameters ="<?php echo $_SERVER[PHP_SELF];?>?module=formbuilder&action=addNewFormParameters";
  //jQuery('#tempdivcontainer').show();
 // jQuery('#tempdivcontainer').append(defaultSelected);
     //jQuery(':input[name=submitNewFormDetails]').unbind();
 jQuery('#tempdivcontainer').load(myurlToStoreFormParameters, formParametersToPost ,function postSuccessFunction(html) {
    //    alert("dat to post"+dataToPost);
        jQuery('#tempdivcontainer').html(html);
//jQuery('#tempdivcontainer').show();
//insertFields();
            //jQuery('input:radio[name=simpleOrAdvancedTextAreaRadio]:checked').val("textarea");

var postSuccess = jQuery('#tempdivcontainer #insertFormDetailsSuccessParameter').html();
var formNumber = jQuery('#tempdivcontainer #insertFormNumber').html();
if (postSuccess == 0)
    {
        jQuery("#errorMessageDiv").html(
           "<br> Error! The Form Database Name \""+formTitle+"\" has NOT been made. <br>\n\
It already exists in the database. Please choose a unique form name");
            jQuery('#formNameIcon').attr("src","skins/_common/icons/cancel.gif");
    }
    else
        {
jQuery(":input[type='hidden']").attr("value",formNumber);
jQuery("#form_formDetails").submit();
//
//           jQuery("#WYSIWYGForm").append(textArea);
//   jQuery("#addParametersforTextArea").children("span").html(" A new text area Object \""+textAreaName+"\" has been configured and stored.");
//                 jQuery("input:radio[name=simpleOrAdvancedTextAreaRadio]:eq(0)").attr('checked', "checked");
//                insertPropertiesForFormElement("text area", formElementLabel);

        }


           });
         //  return true;
  });
   insertFields();

    

    });


    function insertFields()
    {
         jQuery(':input[name=formTitle]').live('keypress keydown keyup',function() {

var test = jQuery(':input[name=formTitle]').val();
if (test.length <= 3)
    {
    jQuery('#formNameIcon').attr("src","skins/_common/icons/failed.gif");
    jQuery('#formNameIcon').attr("title","Database Name of less than 3 characters is not allowed");
    testSubmitButton();
 }
if (test.length >= 5)
    {
      jQuery('#formNameIcon').attr("src","skins/_common/icons/warning.gif");
        jQuery('#formNameIcon').attr("title","Database Name of less than 15 characters is weak");
    testSubmitButton();
}
if (test.length > 15)
    {
      jQuery('#formNameIcon').attr("src","skins/_common/icons/ok.gif");
              jQuery('#formNameIcon').attr("title","Database Name is strong");
              testSubmitButton();
    }

});

     jQuery(':input[name=formLabel]').live('keypress keydown keyup',function() {
var test = jQuery(':input[name=formLabel]').val();
if (test.length <= 3)
    {
    jQuery('#formLabelIcon').attr("src","skins/_common/icons/failed.gif");
    jQuery('#formLabelIcon').attr("title","Title of less than 3 characters is not allowed");
    testSubmitButton();
}
if (test.length >= 5)
    {
      jQuery('#formLabelIcon').attr("src","skins/_common/icons/warning.gif");
        jQuery('#formLabelIcon').attr("title","Form title of less than 10 characters is weak");
    testSubmitButton();
 }
if (test.length > 10)
    {
      jQuery('#formLabelIcon').attr("src","skins/_common/icons/ok.gif");
      jQuery('#formLabelIcon').attr("title","form title is strong");
      testSubmitButton();
    }

});

jQuery('textarea[name=formCaption]').live('keypress keydown keyup',function() {
var test = jQuery('textarea[name=formCaption]').val();
if (test.length <= 5)
    {
    jQuery('#formDescriptionIcon').attr("src","skins/_common/icons/failed.gif");
    jQuery('#formDescriptionIcon').attr("title","Form Description of less than 5 characters is not allowed");
    testSubmitButton();
  }
if ( test.length >= 5)
    {
      jQuery('#formDescriptionIcon').attr("src","skins/_common/icons/warning.gif");
        jQuery('#formDescriptionIcon').attr("title","Form Description of less than 25 characters is weak");
    testSubmitButton();
 }
if (test.length > 25)
    {
      jQuery('#formDescriptionIcon').attr("src","skins/_common/icons/ok.gif");
      jQuery('#formDescriptionIcon').attr("title","Form Description is strong");
         testSubmitButton();
    }

});
    }
    function testSubmitButton()
{
    var formDesciptionOK = jQuery('#formDescriptionIcon').attr("src");
var formLabelOK = jQuery('#formLabelIcon').attr("src");
var formTitleOK = jQuery('#formNameIcon').attr("src");

if (formTitleOK == "skins/_common/icons/ok.gif" && formLabelOK == "skins/_common/icons/ok.gif" && formDesciptionOK == "skins/_common/icons/ok.gif")
    {
jQuery(':input[name=submitNewFormDetails]').removeAttr("disabled");
//jQuery(':input[name=submitNewFormDetails]').("type");
jQuery(':input[name=submitNewFormDetails]').children('span').children('span').children('span').html("Submit General Form Details");
jQuery(':input[name=submitNewFormDetails]').children('span').children('span').children('span').removeClass();
jQuery(':input[name=submitNewFormDetails]').children('span').children('span').children('span').addClass('ok');
    }
    else
        {
       //   jQuery(':input[name=submitNewFormDetails]').attr('type','reset');
          jQuery(':input[name=submitNewFormDetails]').attr("disabled","false");
jQuery(':input[name=submitNewFormDetails]').children('span').children('span').children('span').html("Complete All Fields");
jQuery(':input[name=submitNewFormDetails]').children('span').children('span').children('span').removeClass();
jQuery(':input[name=submitNewFormDetails]').children('span').children('span').children('span').addClass('decline');
        }


}
    </script>