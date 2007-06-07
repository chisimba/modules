/* 
* ===================================================================== 
*  File to hold the javascript functions for the wiki version 2 module
* =====================================================================
/*

/**
* Method to check the create form fields
*
* @param string err_page: The no page name error message
* @param string err_name: The no capital letter error message
* @param string err_summary: The no summary error message
* @param string err_content: The no content error message 
*/
function validateCreate(err_page, err_name, err_summary, err_content)
{
    var myName = $("input_name");
    var mySummary = $("input_summary");
    var myChoice = $("input_choice");
    var myContent = $("input_content");
    
    if(myName.value == ''){
        alert(err_page);
        myName.focus();
        return false;
    }
    
    if(mySummary.value == ''){
        if(confirm(err_summary)){
            myChoice.value = 'yes';
        }else{
            mySummary.focus();
            return false;
        }
    }
    
    if(myContent.value == ''){
        alert(err_content);
        myContent.focus
        return false;
    }
    
    $("form_create").submit();
}

/**
* Method to check the update form fields
*
* @param string err_content: The no content error message 
* @param string err_summary: The no summary error message
* @param string err_comment: The no comment error message 
*/
function validateUpdate(err_summary, err_content, err_comment)
{
    var mySummary = $("input_summary");
    var myChoice = $("input_choice");
    var myContent = $("input_content");
    var myComment = $("input_comment");
    
    
    if(mySummary.value == ''){
        if(confirm(err_summary)){
            myChoice.value = 'yes';
        }else{
            mySummary.focus();
            return false;
        }
    }
    if(myContent.value == ''){
        alert(err_content);
        myContent.focus
        return false;
    }

    if(myComment.value == ''){
        alert(err_comment);
        myComment.focus
        return false;
    }
    
   $("form_update").submit();
}