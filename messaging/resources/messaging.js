/**
* =========================================
*  Scriptaculous global variables
* =========================================
*/
var URL = "index.php";

/**
* =========================================
*  Scriptaculous onload functions
* =========================================
*/
/**
* Function to initialise onload events
*/
function jsOnloadChat(){
    jsGetOnlineUsers();    
    jsGetChat();    
}

/**
* =========================================
*  Js functions for common use
* =========================================
*/
/**
* Function to display or hide the help div
* @param object el: The help div object
*/            
function jsShowHelp(el)
{
    if(el.style.display == "none"){
        new Effect.Appear(el.id);
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
* Function to insert the smiley code form the smiley block into the chat text input box 
* @param string el_id: The smiley code - id of the clicked smiley
*/            
function jsInsertBlockSmiley(el_id)
{
    var arrNames = new Array("angry", "cheeky", "confused", "cool", "evil", "idea", "grin", "sad", "smile", "wink");            
    var arrCodes = new Array("X-(", ":P", ":-/", "B-)", ">:)", "*-:)", ":D", ":(", ":)", ";)");
    var el_Message = document.getElementById("input_message");
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
* Function to insert the smiley code form the smiley popup into the chat text input box 
* @param string el_id: The smiley code - id of the clicked smiley
*/            
function jsInsertPopupSmiley(el_id)
{
    var arrNames = new Array("alien" ,"angel", "angry", "applause", "black_eye", "bye", "cheeky", "chicken", "clown", "confused", "cool", "cowboy", "crazy", "cry", "dance_of_joy", "doh", "drool", "embarrassed", "evil", "frustrated", "grin", "hug", "hypnotised", "idea", "kiss", "laugh", "love", "nerd", "not_talking", "praying", "raise_eyebrow", "roll_eyes", "rose", "sad", "shame_on_you", "shocked", "shy", "sick", "skull", "sleeping", "smile", "straight_face", "thinking", "tired", "victory", "whistle", "wink", "worried");
    var arrCodes = new Array(">-)", "O:)", "X-(", "=D>", "b-(", ":\"(", ":p", "~:>", ":o)", ":-/", "B-)", "<):)", "8-}", ":((", "/:D/", "#-o", "=P~", ":\">", ">:)", ":-L", ":D", ">:D<", "@-)", "*-:)", ":*", ":))", ":x", ":-B", "[-(", "[-o<", "/:)", "8-|", "@};-", ":(", "[-X", ":O", ";;)", ":-&", "8-X", "I-)", ":)", ":|", ":-?", "(:|", ":)>-", ":-\"", ";)", ":-s");                        
    var el_Message = opener.document.getElementById("input_message");
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
    var myAjax = new Ajax.Updater(target, URL, {method: "post", parameters: pars, onComplete: jsUserListTimer});
}

/*
* Function to repeat the online users ajax call
*/
function jsUserListTimer(){
    var el_Banned = document.getElementById("input_banned");
    var el_UserId = document.getElementById("input_userId");
    var el_Type = document.getElementById("input_type");
    var el_Date = document.getElementById("input_date");
    if(el_Banned.value == "Y"){                    
        var target = "bannedDiv";
        var pars = "module=messaging&action=getbanmsg&userId="+el_UserId.value+"&type="+el_Type.value+"&date="+el_Date.value;
        var myAjax = new Ajax.Updater(target, URL, {method: "post", parameters: pars});
        if(el_Type.value != 2){
            new Effect.Appear("bannedDiv");
            Element.hide("sendDiv");
        }else{
            new Effect.Appear("bannedDiv");
            new Effect.Appear("sendDiv");
        }
    }else{
        new Effect.Appear("sendDiv");
        Element.hide("bannedDiv");
    }                
    var userTimer = setTimeout("jsGetOnlineUsers()", 3000);
}

/**
* ========================================
*  Js functions for the chat messages div
* ========================================
*/
/*
* Function to get the chat messages via ajax
*/            
function jsGetChat()
{
    var target = "chatDiv";
    var pars = "module=messaging&action=getchat";
    var myAjax = new Ajax.Updater(target, URL, {method: "post", parameters: pars, onComplete: jsChatTimer});
}

/*
* Function to repeat the chat messages ajax call
*/
function jsChatTimer()
{
    var el_ChatDiv = document.getElementById("chatDiv");
    el_ChatDiv.scrollTop = el_ChatDiv.scrollHeight
    var chatTimer = setTimeout("jsGetChat()", 3000);
}

/*
* Function to change the send action
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
* Function to send the chat message
*/
function jsSendMessage()
{
    <?php
    echo "here";
    ?>
    alert("here");
    var el_Message = document.getElementById("input_message");
    var pars = "module=messaging&action=sendchat&message="+el_Message.value;
    var myAjax = new Ajax.Request(URL, {method: "post", parameters: pars});    
}

/**
* ========================================
*  Js functions for the ban user popup
* ========================================
*/
/*
* Function to validate the ban user form
* @param string warn_err: The error message for warning
* @param string ban_err: The error message for ban
*/            
function jsValidateBan(warn_err, ban_err)
{
    var el_Type = document.getElementsByName("type");
    var el_Reason = document.getElementById("input_reason");
    var len = el_Type.length;
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
    var el_TypeFeature = document.getElementById("typeFeature");
    var el_LengthFeature = document.getElementById("lengthFeature");
    var el_TypeDiv = document.getElementById("typeDiv");
    var el_LengthDiv = document.getElementById("lengthDiv");
    if(el.value == 0){
        el_TypeDiv.style.width = "49%";
        el_LengthDiv.style.width = "49%";
        el_LengthDiv.style.visibility = "visible";
        el_LengthDiv.style.display = "block";
        xHeight(el_LengthFeature, xHeight(el_TypeFeature));
    }else{
        el_TypeDiv.style.width = "100%";
        el_LengthDiv.style.visibility = "hidden";
        el_LengthDiv.style.display = "none";
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

/**
* ========================================
*  Js functions for the chat log popup
* ========================================
*/
/*
* Function to search for users using an ajax call 
* @param object el: The log type radio
*/            
function jsLogDateDiv(el)
{        
    var el_DateDiv = document.getElementById("dateDiv");
    if(el.value == 2){
        el_DateDiv.style.visibility = "visible";
        el_DateDiv.style.display = "block";
        window.resizeTo(500, 420);
    }else{
        el_DateDiv.style.visibility = "hidden";
        el_DateDiv.style.display = "none";
        window.resizeTo(500, 280);
    }
}

/*
* Function to validate chat log dates 
* @param string err_start: The start date error message
* @param string err_end: The end date error message
* @param string err_date: The date comparison error message
*/            
function jsValidateDate(err_start, err_end, err_date)
{
    var el_Type = document.getElementsByName("type");
    var el_InputStart = document.getElementById("input_start");
    var el_InputEnd = document.getElementById("input_end");
    var el_Log = document.getElementById("form_log");
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

