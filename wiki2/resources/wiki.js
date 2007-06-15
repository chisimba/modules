324/* 
* ===================================================================== 
*  File to hold the javascript functions for the wiki version 2 module
* =====================================================================
/*

/**
* Method to validate the create form fields
*
* @param string err_page: The no page name error message
* @param string err_name: The no capital letter error message
* @param string err_summary: The no summary error message
* @param string err_content: The no content error message 
*/
function validateCreate(err_page, err_summary, err_content)
{
    var name_input = $("input_name");
    var summary_input = $("input_summary");
    var choice_input = $("input_choice");
    var content_input = $("input_content");
    
    if(name_input.value == ''){
        alert(err_page);
        name_input.focus();
        return false;
    }
    
    if(summary_input.value == ''){
        if(confirm(err_summary)){
            choice_input.value = 'yes';
        }else{
            summary_input.focus();
            return false;
        }
    }
    
    if(content_input.value == ''){
        alert(err_content);
        content_input.focus
        return false;
    }
    
    $("form_create").submit();
}

/**
* Method to validate the update form fields
*
* @param string err_content: The no content error message 
* @param string err_summary: The no summary error message
* @param string err_comment: The no comment error message 
*/
function validateUpdate(err_summary, err_content, err_comment)
{
    var summary_input = $("input_summary");
    var choice_input = $("input_choice");
    var content_input = $("input_content");
    var comment_input = $("input_comment");
    
    
    if(summary_input.value == ''){
        if(confirm(err_summary)){
            choice_input.value = 'yes';
        }else{
            summary_input.focus();
            return false;
        }
    }
    if(content_input.value == ''){
        alert(err_content);
        content_input.focus
        return false;
    }

    if(comment_input.value == ''){
        alert(err_comment);
        comment_input.focus
        return false;
    }
    
   $("form_update").submit();
}

/**
* Method to validate the page name
*
* @param string name_input: The page name input element
*/
function validateName(name_input)
{
    var url = "index.php";
    var target = "errorDiv";
    var pars = "module=wiki2&action=validate_name&name="+name_input.value;
    var validateAjax = new Ajax.Updater(target, url, {method: "post", parameters: pars, onComplete: validationEffects});    
}

/**
* Method to show effects if valudation fails
*/
function validationEffects()
{
    var name_input = $("input_name"); 
    var summary_input = $("input_summary");   
    var div_errorDiv = $("errorDiv");
    if(Element.empty(div_errorDiv) == null){
        name_input.style.backgroundColor = "yellow";
        name_input.focus();
        name_input.select();
    }else{
        name_input.style.backgroundColor = "";
        summary_input.focus();
    }
    adjustLayout();        
}

/**
* Method to call ajax to generate the preview
*/
function refreshPreview()
{
    var name_value = $F("input_name");
    var content_value = $F("input_content");
    var url = "index.php";
    var target = "previewDiv";
    var pars = "module=wiki2&action=preview_page&name="+name_value+"&content="+content_value;
    var refreshAjax = new Ajax.Updater(target, url, {method: "post", parameters: pars,onComplete: resizeRefresh});    
}

/**
* Method to adjust the layout
*/
function resizeRefresh()
{
    adjustLayout();
}

/**
* Method to link ajax functions to the tab onclick events
*/
function tabClickEvents()
{
    $("mainTabnav3").parentNode.style.display = 'none';
    $("mainTabnav4").parentNode.style.display = 'none';
    var editLink = $("mainTabnav2");
    editLink.onclick = function(){
        checkLock();
    }
}

/**
* Method to check if the user can edit the page
*/
function checkLock()
{
    var id = $F("input_id");
    var target = 'lockedDiv';
    var url = "index.php";
    var pars = "module=wiki2&action=check_lock&id="+id;
    var checkAjax = new Ajax.Updater(target, url, {method: "post", parameters: pars, onComplete: updatePage});    
}

/**
* Method to update the page after the lock check
*/
function updatePage()
{
    var locked_input = $F("input_locked");
    if(locked_input == "locked"){
        $("mainTabnav1").parentNode.style.display = 'none';
        $("mainTabnav2").parentNode.style.display = 'none';
        $("mainTabnav3").parentNode.style.display = '';
        $("mainTabnav4").parentNode.style.display = '';
        $("mainTabnav5").parentNode.style.display = 'none';
        $('mainTab').tabber.tabShow(2);
        adjustLayout();
        lockPage();        
    }else{
        $("mainTabnav1").parentNode.style.display = '';
        $("mainTabnav2").parentNode.style.display = '';
        $("mainTabnav3").parentNode.style.display = 'none';
        $("mainTabnav4").parentNode.style.display = 'none';
        $("mainTabnav5").parentNode.style.display = '';
        $('mainTab').tabber.tabShow(1);
    }
}

/**
* Method to lock the page for editing
*/
function lockPage()
{
    var id = $F("input_id");
    var target = "input_locked";
    var url = "index.php";
    var pars = "module=wiki2&action=lock_page&id="+id;
    var lockAjax = new Ajax.Updater(target, url, {method: "post", parameters: pars, onComplete: wikiLockTimer});    
}

/*
* Function to set the lock timer
*/
function wikiLockTimer()
{
    var lockTimer = setTimeout("lockPage()", 60000);    
}

/**
* Method to add a rating
* 
* @param string rating: The rating that was given
*/
function addRating(rating)
{
    var name_value = $F("input_name");
    var target = "ratingDiv";
    var url = "index.php";
    var pars = "module=wiki2&action=add_rating&name="+name_value+"&rating="+rating;
    var ratingAjax = new Ajax.Updater(target, url, {method: "post", parameters: pars});      
}

/**
* Method to update the watchlist
*
* @param bool $watch: The state of the checkbox
*/
function updateWatchlist(watch)
{
    if(watch){
        var mode = 'add';
    }else{
        var mode = 'delete';
    }
    var name_value = $F("input_name");
    var url = "index.php";
    var pars = "module=wiki2&action=update_watch&name="+name_value+"&mode="+mode;
    var watchAjax = new Ajax.Request(url, {method: "post", parameters: pars});          
}