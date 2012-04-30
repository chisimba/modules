function LoadEditRadioForm(formNumber,formElementName,formElementType){
        var myurlToEditRadio = jQuery("#ajaxCallUrlsHiddenInputs").children(":input[name=urlToEditFormElement]").val();
    var dataToPost ={
        "formNumber":formNumber,
        "formElementName":formElementName,
        "formElementType":formElementType
    };
    jQuery( "#dialog-box-editFormElements").children("#content").load(myurlToEditRadio , dataToPost ,function postSuccessFunction(html) {
        if (jQuery( "#dialog-box-editFormElements").children("#content").html() == 0){
            jQuery("#dialog-box-editFormElements").children("#content").html('<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 25px 0;"></span>Internal Error. Form Element\n\
does not exist or is empty. Please contact your software administrator.</p>');   
            jQuery( "#dialog-box-editFormElements").children("#content").fadeIn("slow");     
        }else{
            
//            jQuery( "#dialog-box-editFormElements").children("#content").css("min-height","450px");
            setUpEditRadioForm(formNumber,formElementName);
            jQuery( "#dialog-box-editFormElements").children("#content").fadeIn("slow");   
       
        }
    });
}


function setUpEditRadioForm(formNumber,formElementName){
    var formElementType = "radio";
    var formElementLabel = "Radio Button";
    setUpEditLinkForMovableOptions(formElementType,formElementLabel);
    hideFormElementOptionSuperContainer();
            $("#editFormElementTabs").tabs({ 
    fx: { opacity: 'toggle' } 
});

            var optionValue = jQuery(':input[name=optionValue]');
            var optionLabel = jQuery(':input[name=optionLabel]');
            var layoutOption = jQuery(':input[name=formElementLayout]');
            var defaultOption = jQuery('#defaultOptionButton');
            var formElementLabel = jQuery(':input[name=formElementLabel]');
            var formElementLabelLayout = jQuery(':input[name=labelOrientation]');
            defaultOption.button();
            layoutOption.button();
            formElementLabelLayout.button();
            var allFields = jQuery([]);
            

}