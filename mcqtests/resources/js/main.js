jQuery(document).ready(function() {
    hideAllForms();
    jQuery("#qnoption").change(function(){
        var val=this.value;

        existingQ = jQuery('#existingQ').val();
        
        if(existingQ == 'oldQ') {
            processQuestionMethod(existingQ);
        }
        else if(existingQ == 'newQ') {
            checkNewQuestion(val);
        }
        else {
            hideAllForms();
        }
    });

    jQuery('#existingQ').change(function() {
        val = this.value;
        if(val == '-') {
            hideAllForms();
        }
        else {
            processQuestionMethod(val);
        }
    });
});

function processQuestionType() {
    if (document.getElementById('input_qnoption').value == '-')
    {
        alert('Please select an action');
        document.getElementById('input_qnoption').focus();
    } else {
        //document.getElementById('form_qnform').submit();
        document.getElementById('input_qnoptionlabel').textContent='Updated!';
    }
}

function checkNewQuestion(val) {

    if(val == 'freeform'){
        jQuery('#freeform').show();
        jQuery('#addquestion').hide();
        Ext.get('mcqGrid').hide();
    }else if(val == 'mcq'){
        jQuery('#addquestion').show();
        jQuery('#freeform').hide();
    }else{
        jQuery('#freeform').hide();
        jQuery('#addquestion').hide();
        Ext.get('mcqGrid').hide();
    }
}

function processQuestionMethod(val) {
    var dataType;
    if(val == 'oldQ') {
        var type = jQuery("#qnoption").val();
        if(type == 'freeform' || type == 'mcq'){
            jQuery('#freeform').hide();
            jQuery('#addquestion').hide();
            jQuery('#dbquestions').show();
            Ext.get('mcqGrid').show();

            if(type == 'mcq') {
                dataType = 'mcq';
            }
            else {
                dataType = 'freeform';
            }
            getGridData(dataType);
        }
    }
    else if(val == 'newQ') {
        checkNewQuestion(jQuery("#qnoption").val());
    }
    else {
        jQuery('#dbquestions').hide();
        jQuery('#freeform').show();
        jQuery('#addquestion').show();
    }
}

function hideAllForms() {
    jQuery('#freeform').hide();
    jQuery('#addquestion').hide();
    jQuery("#dbquestions").hide();
}
