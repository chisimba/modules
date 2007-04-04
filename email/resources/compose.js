function listfirstname()
{        
    $("input_surname").value = "";

    var pars = "module=email&action=composelist&field=firstname";
    new Ajax.Autocompleter("input_firstname", "firstnameDiv", "index.php", {parameters: pars});
}

function listsurname()
{        
    $("input_firstname").value = "";

    var pars = "module=email&action=composelist&field=surname";
    new Ajax.Autocompleter("input_surname", "surnameDiv", "index.php", {parameters: pars});
}
    
function addRecipient(userid)
{
    var el = $("input_recipient");
    var elArr = el.value.split("|");
    var len = elArr.length
    var exist = false;
    for(i=0; i<len; i++){
        if(elArr[i] == userid){
            exist = true;
        }
    }
    if(exist == false){
        if(el.value == ""){
            el.value = userid;
        }else{
            el.value = el.value + "|" + userid;
        }
    }
    var url = "index.php";
    var pars = "module=email&action=makelist&recipientList=" + el.value;
    var target = "toList";
    var myAjax = new Ajax.Updater(target, url, {method: "get", parameters: pars, onLoading: addLoad, onComplete: addComplete});
}
    
function addLoad()
{
    $("add_load").style.visibility = "visible";
}

function addComplete()
{
    $("add_load").style.visibility = "hidden";
}
    
function deleteRecipient(userid)
{
    var el = $("input_recipient");
    var elArr = el.value.split("|");
    el.value = "";
    var len = elArr.length;
    for(i=0; i<len; i++){
        if(elArr[i] != userid){
            if(el.value == ""){
                el.value = elArr[i];
            }else{
                el.value = el.value + "|" + elArr[i];
            }
        }
    }
    var url = "index.php";
    var pars = "module=email&action=makelist&recipientList=" + el.value;
    var target = "toList";
    var myAjax = new Ajax.Updater(target, url, {method: "get", parameters: pars, onLoading: addLoad, onComplete: addComplete});
}
