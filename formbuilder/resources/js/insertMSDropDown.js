function insertMSDropDown(formElementType,formName,formNumber,addAnotherOption,ddLabel,labelLayout)
{
    jQuery('.errorMessageDiv').empty();
    jQuery( "#dialog-box-formElements" ).bind( "dialogclose", function(event, ui) {
        insertFormElement();
    });
    var myurlToCreateMSDropdown = jQuery("#ajaxCallUrlsHiddenInputs").children(":input[name=urlToInsertFormElement]").val();
    var dataToPost ={
        "formName":formName,
        "formNumber":formNumber,
        "formElementType":formElementType
    };
    jQuery('#tempdivcontainer').load(myurlToCreateMSDropdown , dataToPost ,function postSuccessFunction(html) {
        var insertMSDropDownFormContent =     jQuery('#tempdivcontainer').html();
        jQuery('#tempdivcontainer').empty();
        if (insertMSDropDownFormContent == 0)
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
                title: 'Insert Multi-Selectable Drop Down List'
            });
            jQuery("#dialog-box-formElements").children("#content").html(insertMSDropDownFormContent);

            var formElementID = jQuery(':input[name=uniqueFormElementID]');
            var optionValue = jQuery(':input[name=optionValue]');
            var menuSizeRadio = jQuery(':input[name=menuSize]');
            var menuSize = jQuery(':input[name=menuSizeParameter]');
            var optionLabel = jQuery(':input[name=optionLabel]');
            var defaultOption = jQuery('#defaultOptionButton');
            var formElementLabelLayout = jQuery(':input[name=labelOrientation]');
            var formElementLabel = jQuery(':input[name=formElementLabel]');

            defaultOption.button();
            menuSizeRadio.button();
            jQuery('.ui-button-text').css('width','250px');
            var allFields = jQuery([]).add(formElementID).add(optionValue).add(optionLabel);
            filterInput(formElementID);
          //  filterInput(optionValue);
            filterNumberInput(menuSize);
            jQuery("#setCustomMenuSize").hide();
            menuSizeRadio.change(function(){

                if ( jQuery('input:radio[name=menuSize]:checked').val() == "custom")
                {
                    jQuery("#setCustomMenuSize").show("slow");
                    menuSize.val("2");
                }
                else
                {
                    jQuery("#setCustomMenuSize").hide("slow");
                    menuSize.val("1");
                }

            });
            if (addAnotherOption != null)
            {
                formElementID.val(addAnotherOption);
                formElementID.attr('disabled', true);
                formElementID.addClass('ui-state-disabled');
                    jQuery('input[name=labelOrientation]:checked').removeAttr('checked');
                  jQuery('input:radio[name="labelOrientation"]').filter('[value='+labelLayout+']').attr('checked', true);
            formElementLabelLayout.attr('disabled', true);
                  formElementLabelLayout.addClass('ui-state-disabled');

            formElementLabel.val(ddLabel);
                        formElementLabel.attr('disabled', true);
               formElementLabel.addClass('ui-state-disabled');
            }
            formElementLabelLayout.button();
            //if (defaultAlreadySelected == 'on')
            //{
            //     defaultOption.button({ disabled: true });
            //jQuery("label[for=defaultOptionButton]").val("A radio option has already been selected as default");
            //defaultOption.after("A radio option has already been selected as default.");
            //}
            jQuery( "#dialog-box-formElements" ).dialog( "option", "buttons", {
                "Insert Multi-Selectable Drop Down Option": function() {
                    var bValid = true;
                    allFields.removeClass('ui-state-error');
                    bValid = bValid && checkRegexp(formElementID,/^([0-9a-zA-Z])+$/,"The drop down ID field only allows alphanumeric characters (a-z 0-9).");
                    bValid = bValid && checkLength(formElementID,'drop down unique ID',5,150);
                   // bValid = bValid && checkRegexp(optionValue,/^([0-9a-zA-Z])+$/,"The drop down value only allows alphanumeric characters (a-z 0-9).");
                    bValid = bValid && checkLength(optionValue,'drop down value',1,150);
                    // bValid = bValid && checkRegexp(optionLabel,/^([0-9a-zA-Z])+$/,"The drop down label only allows alphanumeric characters (a-z 0-9).");
                    bValid = bValid && checkLength(optionLabel,'drop down label',1,150);
                    if ( jQuery('input:radio[name=menuSize]:checked').val() == "custom")
                    {
                        bValid = bValid && checkValue(menuSize,'menu size drop down size',2,150);
                        bValid = bValid && checkRegexp(menuSize,/^\d+$/,"The drop down menu size only allows digits (0-9).");
                    }
                    if (bValid) {
                        var formElementIDs= formElementID.val();
                        var optionValues=  optionValue.val();
                        var optionLabels= optionLabel.val();
                        var menuSizes= menuSize.val();
                        var defaultOptions = jQuery("#defaultOptionButton:checked").val();
                        var formElementLabels=formElementLabel.val();
   var formElementLabelLayouts = jQuery('input:radio[name=labelOrientation]:checked').val();
                        if (addAnotherOption != null)
                        {
                            produceMSDropDown(formElementIDs,optionValues,optionLabels,menuSizes,formElementLabels,formElementLabelLayouts,defaultOptions);
                        }
                        else
                        {
                            produceMSDropDownIdentifier(formElementIDs,optionValues,optionLabels,menuSizes,formElementLabels,formElementLabelLayouts,defaultOptions);
                        }
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

function   produceMSDropDown(formElementID,optionValue,optionLabel,menuSize,formElementLabel,formElementLabelLayout,defaultOption)
{
  var MSDropdowndataToPost = {"optionValue":optionValue, "optionLabel":optionLabel, "formElementName":formElementID, "defaultSelected":defaultOption, "menuSize":menuSize,"formElementLabel":formElementLabel,"formElementLabelLayout":formElementLabelLayout};
                        var myurlToCreateMSDropDown = jQuery("#ajaxCallUrlsHiddenInputs").children(":input[name=urlToProduceMSDropDown]").val();

                jQuery('#tempdivcontainer').load(myurlToCreateMSDropDown ,MSDropdowndataToPost ,function postSuccessFunction(html) {

                    jQuery('#tempdivcontainer').html(html);

                    var dropdown = jQuery('#tempdivcontainer #WYSIWYGMSDropdown').html();
 jQuery('#tempdivcontainer').empty();
                    if (dropdown == 0)
                    {
                         updateErrorMessage("A drop down with the ID \""+formElementID+"\" and value \""+optionLabel+"\" already exists in the database.\n\
Please choose a unique drop down ID and value combination. Insert a unique drop down value.");
                    jQuery(':input[name=optionValue]').addClass('ui-state-error');
                    }
                    else
                    {
                        if (jQuery("#WYSIWYGForm").children("#"+formElementID).length <= 0)
                        //if (jQuery("#WYSIWYGForm").children("#input_"+formElementLabel).length <= 0)
                        {
                            jQuery("#WYSIWYGForm").append('<div id ='+formElementID+' class="witsCCMSFormElementMultiSelectDropDown"></div>');

                            //jQuery("#WYSIWYGForm").children("#"+formElementLabel).append('<div id =input_'+formElementLabel+'></div>');
                            //jQuery("#WYSIWYGForm").children("#"+formElementLabel).children("#input_"+formElementLabel).replaceWith(dropdown);

                            jQuery("#WYSIWYGForm").children("#"+formElementID).append('<div id =input_'+formElementID+'></div>');
                            jQuery("#WYSIWYGForm").children("#"+formElementID).children("#input_"+formElementID).replaceWith(dropdown);
          var elementToHighlight = jQuery("#WYSIWYGForm").children("#"+formElementID);
  highlightNewConstructedFormElement(elementToHighlight);
                        }

                        //            if ($("#WYSIWYGForm #input_"+formElementLabel).length > 0)
                        else
                        {

                            jQuery("#WYSIWYGForm").children("#"+formElementID).children("#"+formElementID).replaceWith(dropdown);
          var elementToHighlights = jQuery("#WYSIWYGForm").children("#"+formElementID);
  highlightNewConstructedFormElement(elementToHighlights);
                        }
//insertFormElement();
//jQuery( "#dialog-box-formElements").dialog('open');
      //setUpDialogueBox();
                    jQuery( "#dialog-box-formElements").dialog('close');
                    addAnotherMSDropDownOption(formElementID,formElementLabel,formElementLabelLayout)
                    }
                });

}

function produceMSDropDownIdentifier(formElementID,optionValue,optionLabel,menuSize,formElementLabel,formElementLabelLayout,defaultOption)
{
 var myurlToMSDropDownIndentifier = jQuery("#ajaxCallUrlsHiddenInputs").children(":input[name=urlToCreateANewFormElement]").val();
    var formname = jQuery("#getFormName").html();
    var formnumber = jQuery("#getFormNumber").html();
    formname = jQuery.trim(formname);
    formnumber = jQuery.trim(formnumber);
    var dataToPost = {
        "formNumber":formnumber,
        "formName":formname,
        "formElementType":'multiselectable_dropdown',
        "formElementName":formElementID
    };

    jQuery('#tempdivcontainer').load(myurlToMSDropDownIndentifier, dataToPost ,function postSuccessFunction(html) {

        var postSuccessBoolean =jQuery('#tempdivcontainer #postSuccess').html();
        jQuery('#tempdivcontainer').empty();
        if (postSuccessBoolean == 1)
        {
           produceMSDropDown(formElementID,optionValue,optionLabel,menuSize,formElementLabel,formElementLabelLayout,defaultOption);

        }
        else if (postSuccessBoolean == 0)
        {
            updateErrorMessage("The multi-selectable drop down ID \""+formElementID+"\" already exists in the database.\n\
Please choose a unique drop down ID.");
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

function addAnotherMSDropDownOption(formElementID,formElementLabel,formElementLabelLayout)
 {
     var formname = jQuery("#getFormName").html();
    var formnumber = jQuery("#getFormNumber").html();
    formname = jQuery.trim(formname);
    formnumber = jQuery.trim(formnumber);
 jQuery( "#dialog-box").dialog({ title: 'Add Another Multi-Selectable Drop Down List Option?' });
            jQuery("#dialog-box").html('<p><span class="ui-icon ui-icon-check" style="float:left; margin:0 7px 20px 0;"></span>A drop down option with an id \"'+formElementID+'\" has\n\
been inserted successfully.</p><p><span class="ui-icon ui-icon-arrowrefresh-1-w" style="float:left; margin:0 7px 20px 0;"></span>Do you want to add another drop down option with this same\n\
id?</p>');
            jQuery("#dialog-box").dialog("open");
            jQuery( "#dialog-box" ).dialog( "option", "buttons", {
                "Add Another Drop Down Option": function() {
                          jQuery(this).dialog("close");

   insertMSDropDown("multiselectable_dropdown",formname,formnumber,formElementID,formElementLabel,formElementLabelLayout)


                },  "Save": function() {
                    jQuery(this).dialog("close");
                }
            });
 }