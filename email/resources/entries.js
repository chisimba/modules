function listusername(){        
    $("input_firstname").value="";
    $("input_surname").value="";
    $("input_userid").value="";

    var pars = "module=email&action=searchlist&field=username";
    new Ajax.Autocompleter("input_username", "usernameDiv", "index.php", {parameters: pars});
}

function listfirstname(){        
    $("input_username").value="";
    $("input_surname").value="";
    $("input_userid").value="";

    var pars = "module=email&action=searchlist&field=firstname";
    new Ajax.Autocompleter("input_firstname", "firstnameDiv", "index.php", {parameters: pars});
}

function listsurname(){        
    $("input_username").value="";
    $("input_firstname").value="";
    $("input_userid").value="";

    var pars = "module=email&action=searchlist&field=surname";
    new Ajax.Autocompleter("input_surname", "surnameDiv", "index.php", {parameters: pars});
}
