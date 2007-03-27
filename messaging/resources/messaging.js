/**
* ================================
*  Scriptaculous global variables
* ================================
*/
var URL = "index.php";
var userTimer;
var chatTimer;
var chatMode;

/**
* =====================================
*  Scriptaculous page onload functions
* =====================================
*/
/**
* Function to initialise onload events
* @param string mode: The mode of the onload - Context or Null
*/
function jsOnloadChat(mode){
    chatMode = mode;
    if(mode == "context"){
        jsGetChat();    
    }else{
        jsGetChat();    
        jsGetOnlineUsers();    
    }
}

/**
* ===========================================
*  Js function to show or hide the help divs
* ===========================================
*/
/**
* Function to display or hide the help div
* @param object el: The help div object
*/            
function jsShowHelp(el)
{
    if(el.style.display == "none"){
        Element.show(el.id);
        window.setTimeout("Element.hide('"+el.id+"')",5000);        
    }else{
        Element.hide(el.id);
    }            
}

/**
* =========================================
*  Js functions for the smiley feature box
* =========================================
*/
/**
* Function to insert the smiley code from the smiley block into the chat box 
* @param string el_id: The smiley code - id of the clicked smiley
*/            
function jsInsertBlockSmiley(el_id)
{
    var arrNames = new Array("angry", "cheeky", "confused", "cool", "evil", "idea", "grin", "sad", "smile", "wink");            
    var arrCodes = new Array("X-(", ":P", ":-/", "B-)", ">:)", "*-:)", ":D", ":(", ":)", ";)");
    var el_Message = $("input_message");
    for(i = 0; i <= arrNames.length-1; i++){
        if(arrNames[i] == el_id){
            if(el_Message.value == ""){
                el_Message.value = arrCodes[i];
            }else{
                el_Message.value = el_Message.value + " " + arrCodes[i];
            }
        }
    }
    el_Message.focus();
}
            
/**
* ===============================================
*  Js functions for the more smiley popup window
* ===============================================
*/
/**
* Function to insert the smiley code form the smiley popup into the chat box 
* @param string el_id: The smiley code - id of the clicked smiley
*/            
function jsInsertPopupSmiley(el_id)
{
    var arrNames = new Array("alien" ,"angel", "angry", "applause", "black_eye", "bye", "cheeky", "chicken", "clown", "confused", "cool", "cowboy", "crazy", "cry", "dance_of_joy", "doh", "drool", "embarrassed", "evil", "frustrated", "grin", "hug", "hypnotised", "idea", "kiss", "laugh", "love", "nerd", "not_talking", "praying", "raise_eyebrow", "roll_eyes", "rose", "sad", "shame_on_you", "shocked", "shy", "sick", "skull", "sleeping", "smile", "straight_face", "thinking", "tired", "victory", "whistle", "wink", "worried");
    var arrCodes = new Array(">-)", "O:)", "X-(", "=D>", "b-(", ":\"(", ":P", "~:>", ":o)", ":-/", "B-)", "<):)", "8-}", ":((", "/:D/", "#-o", "=P~", ":\">", ">:)", ":-L", ":D", ">:D<", "@-)", "*-:)", ":*", ":))", ":x", ":-B", "[-(", "[-o<", "/:)", "8-|", "@};-", ":(", "[-X", ":O", ";;)", ":-&", "8-X", "I-)", ":)", ":|", ":-?", "(:|", ":)>-", ":-\"", ";)", ":-s");                        
    var el_Message = opener.$("input_message");
    for(i = 0; i <= arrNames.length-1; i++){
        if(arrNames[i] == el_id){
            if(el_Message.value == ""){
                el_Message.value = arrCodes[i];
            }else{
                    el_Message.value = el_Message.value + " " + arrCodes[i];
            }
        }
    }
    window.close();
    el_Message.focus();
}

/**
* ===============================================
*  Js functions for the online users feature box
* ===============================================
*/
/*
* Function to get the online users via ajax
*/            
function jsGetOnlineUsers()
{
    var target = "usersListDiv";
    var pars = "module=messaging&action=getusers";
    var usersAjax = new Ajax.Updater(target, URL, {method: "post", parameters: pars, onComplete: jsUserListTimer});
}

/*
* Function to repeat the online users ajax call
* also handles the banned user display
*/
function jsUserListTimer(){
    var el_Banned = $("input_banned");
    var el_UserId = $("input_userId");
    var el_Type = $("input_type");
    var el_Date = $("input_date");
    if(el_Banned.value == "Y"){                    
        var target = "bannedDiv";
        var pars = "module=messaging&action=getbanmsg&type="+el_Type.value+"&date="+el_Date.value;
        var bannedAjax = new Ajax.Updater(target, URL, {method: "post", parameters: pars});
        if(el_Type.value != 2){
            Element.show("bannedDiv");
            Element.hide("sendDiv");
        }else{
            Element.show("bannedDiv");
            Element.show("sendDiv");
        }
    }else{
        Element.show("sendDiv");
        Element.hide("bannedDiv");
    }                
    userTimer = setTimeout("jsGetOnlineUsers()", 2000);
}

/**
* ========================================
*  Js functions for the chat messages div
* ========================================
*/
/*
* Function to get the chat messages via ajax
* @param string mode: The mode of the onload - Context or Null
*/            
function jsGetChat()
{
    var el_Counter = $("input_counter");
    var target = "chatDiv";
    var pars = "module=messaging&action=getchat&counter="+el_Counter.value+"&mode="+chatMode;
    var chatAjax = new Ajax.Updater(target, URL, {method: "post", parameters: pars, onComplete: jsChatTimer});
}

/*
* Function to repeat the chat messages ajax call
*/
function jsChatTimer()
{
    if(chatMode != "context"){
        Element.hide("loadDiv");
    }
    var el_ChatDiv = $("chatDiv");
    el_ChatDiv.scrollTop = el_ChatDiv.scrollHeight
    chatTimer = setTimeout("jsGetChat()", 2000);
}

/*
* Function to trap the enter key
* @param event e: The onkeyup event
*/
function jsTrapKeys(e)
{
    var keynum;
    var isShift = false;
    if(window.event){
        // IE
        keynum = e.keyCode
        if(window.event.shiftKey){
            isShift = true;
        }
    }else if(e.which){
        // Netscape/Firefox/Opera
        keynum = e.which
        if(e.shiftKey){
            isShift = true;
        }
    }
    if(!isShift && keynum == 13){
        jsSendMessage();
    }
}

/*
* Function to send the chat message via ajax
*/
function jsSendMessage()
{
    var el_Message = $("input_message");
    if(el_Message.value != ""){
        var el_ChatIframe = $("chatIframe");
        var el_iframe = el_ChatIframe.contentWindow || el_ChatIframe.contentDocument;
        if(el_iframe.document){
            el_iframe = el_iframe.document;
        }
        var el_form = el_iframe.getElementById("form_chat");
        var el_Msg = el_iframe.getElementById("input_msg");
        el_Msg.value = el_Message.value;                             
        el_form.submit();    
        Element.show("iconDiv");
        el_Message.value = "";
        el_Message.disabled = true;
    }
}

/*
* Function to hide the loading icon
*/
function jsHideLoading()
{
    parent.$("iconDiv").style.display = 'none';
    var el_Message = parent.$("input_message");
    el_Message.disabled = false;
    el_Message.value = "";
    el_Message.focus();    
}

/*
* Function to process the clearing of the message window
*/
function jsClearWindow()
{
    Element.show("loadDiv");
    var el_Counter = $("input_counter");
    var el_Count = $("input_count");
    el_Counter.value = Number(el_Counter.value) + Number(el_Count.value) - 1;
}

/**
* =====================================
*  Js functions for the ban user popup
* =====================================
*/
/*
* Function to validate the ban user form
* @param string warn_err: The error message for warning
* @param string ban_err: The error message for ban
*/            
function jsValidateBan(warn_err, ban_err)
{
    var el_Type = document.getElementsByName("type");
    var el_Reason = $("input_reason");
    var len = el_Type.length;
    alert(el_Type);
    alert(el_Type.length);
    for(var i = 0; i <= len-1; i++){
        if(el_Type[i].value == 2){
            if(el_Type[i].checked){
                if(el_Reason.value == ""){
                    alert(warn_err);
                    return false;
                }
            }            
        }else if(el_Type[i].value == 1){
            if(el_Type[i].checked){
                if(el_Reason.value == ""){
                    alert(ban_err);
                    return false;
                }
            }            
        }else{
            if(el_Type[i].checked){
                if(el_Reason.value == ""){
                    alert(ban_err);
                    return false;
                }
            }            
        }
    }
    return true;
}

/**
* Function to hide display the temp ban dropdown div
* @param object el: The ban type radio
*/
function jsBanLengthDiv(el)
{
    var el_TypeFeature = $("typeFeature");
    var el_LengthFeature = $("lengthFeature");
    var el_TypeDiv = $("typeDiv");
    var el_LengthDiv = $("lengthDiv");
    if(el.value == 0){
        el_TypeDiv.style.width = "49%";
        el_LengthDiv.style.width = "49%";
        Element.show(el_LengthDiv.id)
        xHeight(el_LengthFeature, xHeight(el_TypeFeature));
    }else{
        el_TypeDiv.style.width = "100%";
        Element.hide(el_LengthDiv.id)
    }    
}

/**
* ========================================
*  Js functions for the invite user popup
* ========================================
*/
/*
* Function to search for users using an ajax call 
*/            
function jsInviteUserList()
{        
    var el_option = document.getElementsByName("option");
    var len = el_option.length;
    var myValue = "";
    for(var i = 0; i <= len-1; i++){
        if(el_option[i].checked){
            myValue = el_option[i].value;
        }
    }
    var input = "input_username";
    var target = "userDiv";
    var pars = "module=messaging&action=invitelist&option="+myValue;
    new Ajax.Autocompleter(input, target, URL, {parameters: pars});
}

/*
* Function to validate the invite user input
* @param string err_invite: The user invite error message
*/            
function jsValidateInvite(err_invite)
{
    var el_UserId = $("input_userId");
    var el_Username = $("input_username");
    if(el_UserId.value == ""){
        alert(err_invite);
        el_Username.value = "";
        el_Username.focus();
        return false;
    }else{
        $("form_invite").submit();
    }    
}

/**
* ========================================
*  Js functions for the chat log popup
* ========================================
*/
/*
* Function to show hide the log dates div
* @param object el: The log type radio
*/            
function jsLogDateDiv(el)
{        
    var el_DateDiv = $("dateDiv");
    if(el.value == 2){
        Element.show(el_DateDiv.id);
        window.resizeTo(500, 420);
    }else{
        Element.hide(el_DateDiv.id);
        window.resizeTo(500, 280);
    }
}

/*
* Function to validate chat log dates 
* @param string err_start: The start date error message
* @param string err_end: The end date error message
* @param string err_date: The date comparision error message
*/            
function jsValidateDate(err_start, err_end, err_date)
{
    var el_Type = document.getElementsByName("type");
    var el_InputStart = $("input_start");
    var el_InputEnd = $("input_end");
    var el_Log = $("form_log");
    var len = el_Type.length;
    if(el_Type[1].checked){
        if(el_InputStart.value == ""){
            alert(err_start);
            return false;
        }else{
            if(el_InputEnd.value == ""){
                alert(err_end);
                return false;
            }else{
                var startString = el_InputStart.value;
                var arrStartDateAndTime = startString.split(" ");
                var arrStartDate = arrStartDateAndTime[0].split("-");
                var arrStartTime = arrStartDateAndTime[1].split(":");
                var startDate = new Date();
                startDate.setYear(arrStartDate[0]);
                startDate.setMonth(arrStartDate[1]-1);
                startDate.setDate(arrStartDate[2]);
                startDate.setHours(arrStartTime[0]-1);
                startDate.setMinutes(arrStartTime[1]);
                        
                var endString = el_InputEnd.value;
                var arrEndDateAndTime = endString.split(" ");
                var arrEndDate = arrEndDateAndTime[0].split("-");
                var arrEndTime = arrEndDateAndTime[1].split(":");
                var endDate = new Date();
                endDate.setYear(arrEndDate[0]);
                endDate.setMonth(arrEndDate[1]-1);
                endDate.setDate(arrEndDate[2]);
                endDate.setHours(arrEndTime[0]-1);
                endDate.setMinutes(arrEndTime[1]);

                if(endDate <= startDate){
                    alert(err_date);
                    return false; 
                }
            }
        }                    
    }
    return true;   
}

/**
* ======================================
*  Js function for the remove user popup
* ======================================
*/
/*
* Function to validate the removed user form
* @param string err_remove: The user remove error message
*/            
function jsValidateRemove(err_remove)
{
    var el_checkbox = document.getElementsByName("userId[]");
    var myValue = false;
    for(var i = 0; i<el_checkbox.length; i++){
        if(el_checkbox[i].checked == true){
            myValue = true;
        }
    }
    if(myValue){
        $("form_remove").submit();
    }else{
        alert(err_remove);
        return false;
    }
}

/**
* ======================================
*  Js function for the im popup
* ======================================
*/
/*
* Function to validate the im input
* @param string err_invite: The user im error message
*/            
function jsValidateUser(err_user)
{
    var el_UserId = $("input_userId");
    var el_Username = $("input_value");
    if(el_UserId.value == ""){
        alert(err_user);
        el_Username.value = "";
        el_Username.focus();
        return false;
    }else{
        $("form_im").submit();
    }    
}

/*
* Function to search for users using an ajax call 
*/            
function jsImUserList()
{        
    var el_option = document.getElementsByName("option");
    var len = el_option.length;
    var myValue = "";
    for(var i = 0; i <= len-1; i++){
        if(el_option[i].checked){
            myValue = el_option[i].value;
        }
    }
    var input = "input_value";
    var target = "userDiv";
    var pars = "module=messaging&action=getimusers&option="+myValue;
    new Ajax.Autocompleter(input, target, URL, {parameters: pars});
}

