function insertDatePicker(formElementType,formName,formNumber)
{
    jQuery('.errorMessageDiv').empty();
    jQuery( "#dialog-box-formElements" ).bind( "dialogclose", function(event, ui) {
        insertFormElement();
    });
    var myurlToCreateDatePicker = jQuery("#ajaxCallUrlsHiddenInputs").children(":input[name=urlToInsertFormElement]").val();
    var dataToPost ={
        "formName":formName,
        "formNumber":formNumber,
        "formElementType":formElementType
    };
    jQuery('#tempdivcontainer').load(myurlToCreateDatePicker, dataToPost ,function postSuccessFunction(html) {
        var insertDatePickerFormContent =     jQuery('#tempdivcontainer').html();
        jQuery('#tempdivcontainer').empty();
        if (insertDatePickerFormContent == 0)
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
                title: 'Insert Date Picker'
            });
            jQuery("#dialog-box-formElements").children("#content").html(insertDatePickerFormContent);
            jQuery("#dialog-box-formElements").children("#content").children("#selectDefaultDate").hide();
            jQuery("#dialog-box-formElements").children("#content").children("#selectDefaultDate").children("#datepicker").datepicker();

            //jQuery("#dialog-box-formElements").children("#content").children("#selectDefaultDate").children("#datepicker").datepicker( "option", "altFormat", 'yyyy-mm-dd' );
            //jQuery("#dialog-box-formElements").children("#content").children("#selectDefaultDate").children("#datepicker").datepicker({ appendText: 'yyyy-mm-dd' });
           
//           jQuery(':input[name=datepickerInput]').datepicker('option', {
//                dateFormat: 'yy-mm-dd'
//            });
           jQuery("#dialog-box-formElements").children("#content").children("#selectDefaultDate").children("#datepicker").datepicker('option', {
                dateFormat: 'yy-mm-dd'
            });
            jQuery("#dialog-box-formElements").children("#content").children("#selectDefaultDate").children("#datepicker").val("Real Time Date");
            var formElementID = jQuery(':input[name=uniqueFormElementID]');
            var formElementName = jQuery(':input[name=uniqueFormElementName]');
            var dateFormatDropDown = jQuery(':input[name=dateFormat]');
            var defaultDateChoice = jQuery(':input[name=defaultDateChoice]');
            var datePickerDefaultDate= jQuery("#datepicker");

            defaultDateChoice.button();

            jQuery('.ui-button-text').css('width','250px');
            var allFields = jQuery([]).add(formElementID).add(formElementName).add(datePickerDefaultDate);
            filterInput(formElementID);
            filterInput(formElementName);

            defaultDateChoice.change(function(){
                if ( jQuery('input:radio[name=defaultDateChoice]:checked').val() == "Custom Date")
                {
                    jQuery("#selectDefaultDate").show("slow");
                    jQuery("#datepicker").val("");
                }
                else
                {
                    jQuery("#selectDefaultDate").hide("slow");
                    jQuery("#datepicker").val("Real Time Date");
                }
            });
            jQuery( "#dialog-box-formElements" ).dialog( "option", "buttons", {
                "Insert Date Picker": function() {
                    var bValid = true;
                    allFields.removeClass('ui-state-error');
                    bValid = bValid && checkRegexp(formElementID,/^([0-9a-zA-Z])+$/,"The date picker ID field only allows alphanumeric characters (a-z 0-9).");
                    bValid = bValid && checkLength(formElementID,'date picker unique ID',5,150);
                    bValid = bValid && checkRegexp(formElementName,/^([0-9a-zA-Z])+$/,"The date picker name only allows alphanumeric characters (a-z 0-9).");
                    bValid = bValid && checkLength(formElementName,'date picker name',5,150);
                    if ( jQuery('input:radio[name=defaultDateChoice]:checked').val() == "Custom Date")
                    {
                        bValid = bValid && checkRegexp(datePickerDefaultDate,/^\d{4}[\/-]\d{1,2}[\/-]\d{1,2}$/,"Please Select a Default Select Date.");
                    }
                    bValid = bValid && checkLength(formElementName,'date picker name',5,150);
                    if (bValid) {
                        var formElementIDs= formElementID.val();
                        var formElementNames= formElementName.val();
                        var dateFormats=  dateFormatDropDown.val();
                        var defaultDates = datePickerDefaultDate.val();

                        produceDatePicker(formElementIDs,formElementNames,dateFormats,defaultDates);

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


function produceDatePicker(formElementID,formElementName,dateFormat,defaultDate)
{
    var myurlToCreateDatePickerIndentifier = jQuery("#ajaxCallUrlsHiddenInputs").children(":input[name=urlToCreateANewFormElement]").val();
    var formname = jQuery("#getFormName").html();
    var formnumber = jQuery("#getFormNumber").html();
    formname = jQuery.trim(formname);
    formnumber = jQuery.trim(formnumber);
    var dataToPost = {
        "formNumber":formnumber,
        "formName":formname,
        "formElementType":'datepicker',
        "formElementName":formElementID
    };

    jQuery('#tempdivcontainer').load(myurlToCreateDatePickerIndentifier, dataToPost ,function postSuccessFunction(html) {

        var postSuccessBoolean =jQuery('#tempdivcontainer #postSuccess').html();
        jQuery('#tempdivcontainer').empty();
        if (postSuccessBoolean == 1)
        {


            var datePickerDataToPost = {
                "datePickerName": formElementID,
                "datePickerValue": formElementName,
                "dateFormat": dateFormat,
                "defaultCustomDate":defaultDate
            };
            var myurlToProduceDatePicker = jQuery("#ajaxCallUrlsHiddenInputs").children(":input[name=urlToProduceDatePicker]").val();

            jQuery('#tempdivcontainer').load(myurlToProduceDatePicker, datePickerDataToPost ,function postSuccessFunction(html) {

                var datePicker = jQuery('#tempdivcontainer #WYSIWYGDatepicker').html();
                if (datePicker == 0)
                {
                    updateErrorMessage("A date picker with the ID \""+formElementID+"\" and name \""+formElementName+"\" already exists in the database.\n\
Please choose a unique date picker ID and name combination.");
                    jQuery(':input[name=uniqueFormElementID]').addClass('ui-state-error');
                    jQuery(':input[name=uniqueFormElementName]').addClass('ui-state-error');
                }
                else
                {


                    if (jQuery("#WYSIWYGForm").children("#"+formElementID).length <= 0)
                    {
                        jQuery("#WYSIWYGForm").append('<div id ='+formElementID+' class="witsCCMSFormElementDatePicker"></div>');
                        jQuery("#WYSIWYGForm").children("#"+formElementID).append("<br>[JavaScript Conflict: Date Picker Object can not be displayed.\n\
It \"will\" be displayed in the built form.]<br>");
                        var elementToHighlight = jQuery("#WYSIWYGForm").children("#"+formElementID);
                        highlightNewConstructedFormElement(elementToHighlight);

                    }
                    else
                    {

                        jQuery("#WYSIWYGForm").children("#"+formElementID).append("<br>[JavaScript Conflict: Date Picker Object can not be displayed.\n\
It \"will\" be displayed in the built form.]<br>");
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
            updateErrorMessage("The date picker ID \""+formElementID+"\" already exists in the database.\n\
Please choose a unique date picker ID.");
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
