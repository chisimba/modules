function insertTextInput(formElementType,formName,formNumber)
{
    jQuery('.errorMessageDiv').empty();
    jQuery( "#dialog-box-formElements" ).bind( "dialogclose", function(event, ui) {
        insertFormElement();
    });
    var myurlToCreateTextInput = jQuery("#ajaxCallUrlsHiddenInputs").children(":input[name=urlToInsertFormElement]").val();
    var dataToPost ={
        "formName":formName,
        "formNumber":formNumber,
        "formElementType":formElementType
    };
    jQuery('#tempdivcontainer').load(myurlToCreateTextInput, dataToPost ,function postSuccessFunction(html) {
        var insertLabelFormContent =     jQuery('#tempdivcontainer').html();
        jQuery('#tempdivcontainer').empty();
        if (insertLabelFormContent == 0)
        {
            jQuery( "#dialog-box-formElements").dialog({
                title: 'Form Element Does Not Exist'
            });
            jQuery("#dialog-box-formElements").children("#content").html('<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 25px 0;"></span>Internal Error. Form Element\n\
does not exist. Please contact your software administrator.</p>');
            jQuery( "#dialog-box-formElements" ).dialog( "option", "buttons", {
                "OK": function() {

                    jQuery(this).dialog("close");
                }
            });
        }
        else
        {
            jQuery( "#dialog-box-formElements").dialog({
                title: 'Insert Text Input'
            });
            jQuery("#dialog-box-formElements").children("#content").html(insertLabelFormContent);
            var formElementID = jQuery(':input[name=uniqueFormElementID]');
            var formElementName = jQuery(':input[name=uniqueFormElementName]');
            var formElementSize = jQuery(':input[name=textInputLength]');
            var maskedInputChoice = jQuery(':input[name=maskedInputChoice]');
            var textOrPassword = jQuery(':input[name=textOrPasswordRadio]');
            var formElementText = jQuery(':input[name=formElementDesiredText]');
            var formElementLabel = jQuery(':input[name=formElementLabel]');
                        var formElementLabelLayout = jQuery(':input[name=labelOrientation]');
            maskedInputChoice.button();
            textOrPassword.button();
            formElementLabelLayout.button();
            jQuery('.ui-button-text').css('width','250px');
            var allFields = jQuery([]).add(formElementID).add(formElementText).add(formElementName).add(formElementSize);
            filterInput(formElementID);
            filterInput(formElementName);
            filterNumberInput(formElementSize);
            textOrPassword.change(function(){
                if ( jQuery('input:radio[name=textOrPasswordRadio]:checked').val() == "text")
                {
                    jQuery("#additionalTextProperties").show("slow");
                    jQuery("input:radio[name=maskedInputChoice]:eq(0)").attr('checked', "checked");
                }
                else
                {
                    jQuery("#additionalTextProperties").hide("slow");
                    jQuery("input:radio[name=maskedInputChoice]:eq(0)").attr('checked', "checked");
                    formElementText.val("");
                }

            });
          maskedInputChoice.change(function(){
                            if ( jQuery('input:radio[name=maskedInputChoice]:checked').val() == "default")
                {
                    jQuery("#defaultTextInput").show("slow");
                    formElementText.val('');
                    //jQuery("input:radio[name=maskedInputChoice]:eq(0)").attr('checked', "checked");
                }
                else
                {
                                        jQuery("#defaultTextInput").hide("slow");
                    formElementText.val('');

                    //jQuery("#additionalTextProperties").hide("slow");
                   // jQuery("input:radio[name=maskedInputChoice]:eq(0)").attr('checked', "checked");
                   // formElementText.val("");
                }
                        });
            jQuery( "#dialog-box-formElements" ).dialog( "option", "buttons", {
                "Insert Text Input": function() {
                    var bValid = true;
                    allFields.removeClass('ui-state-error');
                    bValid = bValid && checkRegexp(formElementID,/^([0-9a-zA-Z])+$/,"The text input ID field only allows alphanumeric characters (a-z 0-9).");
                    bValid = bValid && checkLength(formElementID,'unique ID',5,150);
                    bValid = bValid && checkRegexp(formElementName,/^([0-9a-zA-Z])+$/,"The text input name only allows alphanumeric characters (a-z 0-9).");
                    bValid = bValid && checkLength(formElementName,'text input name',5,150);
                    bValid = bValid && checkRegexp(formElementSize,/^\d+$/,"The text input character length only allows digits (0-9).");
                    bValid = bValid && checkValue(formElementSize,'text input character length',1,150);
                    //                             if ( jQuery('input:radio[name=textOrPasswordRadio]:checked').val() == "text")
                    //                  {
                    //               bValid = bValid && checkLength(formElementText," default text for the text input",1,550);
                    //  }
                    if (bValid) {
                        var formElementIDs= formElementID.val();

                        var textOrPasswords= jQuery('input:radio[name=textOrPasswordRadio]:checked').val();
                        var maskedInputChoices=jQuery('input:radio[name=maskedInputChoice]:checked').val();
                        var formElementTexts= formElementText.val();
                        var formElementNames= formElementName.val();
                        var formElementSizes= formElementSize.val();
                        var formElementLabels= formElementLabel.val();
                        var formElementLabelLayouts = jQuery('input:radio[name=labelOrientation]:checked').val();
                        produceTextInput(formElementIDs,formElementNames,formElementSizes,textOrPasswords,maskedInputChoices,formElementTexts,formElementLabels,formElementLabelLayouts);
                    }



                },
                "Cancel": function() {
                    insertFormElement();
                    jQuery("#dialog-box-formElements").dialog("close");
                }
            });


        }

        jQuery("#dialog-box-formElements").dialog("open");
    });
}


function produceTextInput(formElementID,formElementName,formElementSize,textOrPassword,maskedInputChoice,formElementText,formElementLabel,formElementLabelLayout)
{
    var myurlToCreateTextInputIndentifier = jQuery("#ajaxCallUrlsHiddenInputs").children(":input[name=urlToCreateANewFormElement]").val();
    var formname = jQuery("#getFormName").html();
    var formnumber = jQuery("#getFormNumber").html();
    formname = jQuery.trim(formname);
    formnumber = jQuery.trim(formnumber);
    var dataToPost = {
        "formNumber":formnumber,
        "formName":formname,
        "formElementType":'text_input',
        "formElementName":formElementID
    };

    jQuery('#tempdivcontainer').load(myurlToCreateTextInputIndentifier, dataToPost ,function postSuccessFunction(html) {

        var postSuccessBoolean =jQuery('#tempdivcontainer #postSuccess').html();
        jQuery('#tempdivcontainer').empty();
        if (postSuccessBoolean == 1)
        {
            var textInputDataToPost = {
                'formNumber':formnumber,
                "formElementName": formElementID,
                "textInputName": formElementName,
                "textInputValue" : formElementText,
                "textInputType": textOrPassword,
                "textInputSize": formElementSize,
                "maskedInputChoice" : maskedInputChoice,
       "formElementLabel" :formElementLabel,
        "formElementLabelLayout":formElementLabelLayout
            };
            var myurlToCreateTextInput = jQuery("#ajaxCallUrlsHiddenInputs").children(":input[name=urlToProduceTextInput]").val();
            jQuery('#tempdivcontainer').load(myurlToCreateTextInput, textInputDataToPost ,function postSuccessFunction(html) {
                jQuery('#tempdivcontainer').html(html);
                var textInput = jQuery('#tempdivcontainer #WYSIWYGTextInput').html();
                if (textInput == 0)
                {
                    updateErrorMessage("A text input with the ID \""+formElementID+"\" and name \""+formElementName+"\" already exists in the database.\n\
Please choose a unique text input ID and name combination.");
                    jQuery(':input[name=uniqueFormElementID]').addClass('ui-state-error');
                    jQuery(':input[name=uniqueFormElementName]').addClass('ui-state-error');
                }
                else
                {
                    if (jQuery("#WYSIWYGForm").children("#"+formElementID).length <= 0)
                    {
                        jQuery("#WYSIWYGForm").append('<div id ='+formElementID+' class="witsCCMSFormElementTextInput"></div>');
                        jQuery("#WYSIWYGForm").children("#"+formElementID).html(textInput);
                        var elementToHighlight = jQuery("#WYSIWYGForm").children("#"+formElementID);
                        highlightNewConstructedFormElement(elementToHighlight);
                    }

                    else
                    {
                        jQuery("#WYSIWYGForm").children("#"+formElementID).append(textInput);
                        jQuery("#WYSIWYGForm").children("#"+formElementID).addClass('ui-state-highlight');
                        var elementToHighlights = jQuery("#WYSIWYGForm").children("#"+formElementID);
                        highlightNewConstructedFormElement(elementToHighlights);
                    }
                    insertFormElement();
                    jQuery( "#dialog-box-formElements").dialog('close');


                }
            });
        }
        else if (postSuccessBoolean == 0)
        {
            updateErrorMessage("The text input ID \""+formElementID+"\" already exists in the database.\n\
Please choose a unique text input ID.");
            jQuery(':input[name=uniqueFormElementID]').addClass('ui-state-error');
        }
        else
        {
            insertFormElement();
            jQuery( "#dialog-box-formElements").dialog('close');
            produceInsertErrorMessage();
        }
    });
}