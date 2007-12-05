/* 
* ===================================================================== 
*  File to hold the javascript functions for the tutorials module
* =====================================================================
*/

// global var to hold sort list id
var SORTLIST;
// global var to hold request div id
var REQUEST_DIV;

/**
* Funtion to toggle date fields for tutorial types
* @param object elType: The tutorial type dropdown
*/
function toggleDateDisplay(elType)
{
	if(elType.value == 2){
		Element.show('datesDiv');
	}else{
		Element.hide('datesDiv');
	}
	adjustLayout();
}

/**
* Function to do sotable update
*/
function doUpdate() 
{
	$('input_sortorder').value = Sortable.serialize(SORTLIST);
    if ($('form_sortform').style.display == 'none') {
        Effect.Grow($('form_sortform'));
        $('form_sortform').style.display = 'block';
    } else {
        $('warning').style.display = 'none';
        Effect.Appear($('warning'));
    }
}

/**
* Function to submit the moderation request
* @param string msg: Error message
* @param string id: Answer id
*/
function submitRequest(msg, id)
{
	var modReason = $('input_request').value;
	if(modReason == ''){
		alert(msg);
		return false;
	}
	REQUEST_DIV = id;
    var url = "index.php";
    var pars = "module=tutorials&action=submit_request&id="+id+"&reason="+modReason;
    var submitAjax = new Ajax.Request(url, {method: "post", parameters: pars, onComplete: toggleRequestBox});          
}

/**
* Funtion to hide the request box
*/
function toggleRequestBox()
{
	Element.hide(REQUEST_DIV);	
}
