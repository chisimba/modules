/* ---------------- frontpage_manager ------------------*/
/**
Global Vars
*/
var ID;
var SECTION;
/*
Function to intitialize scriptaculous for frontpage_manager
*/
function fm_init()
{
    fm_setupAddBlocks();
    fm_setupDeleteBlocks();
}

/*
Function to add a block. This function is called everytime an unused block is dropped on the 'dropzone' div
*/
function fm_addBlock(element, dropon, event)
{
    Droppables.remove($(element.id));
    Element.remove($(element.id));
    fm_sendData(element.id, 'adddynamicfrontpageblock', fm_showAddResponse);
}

/*
Function to remove a block. This function is called everytime an used block is dropped on the 'deletezone' div
*/
function fm_removeBlock(element, dropon, event)
{
    Droppables.remove($(element.id));
    Element.remove($(element.id));
    fm_sendData(element.id, 'removedynamicfrontpageblock', fm_showDeleteResponse);
}

/*
Ajax Function - Method to send the block to the server
*/
function fm_sendData (prod, action, responseFunction)
{
    var url    = 'index.php';
    var rand   = Math.random(9999);
    var pars   = 'module=cmsadmin&action='+action+'&blockid=' + prod + '&rand=' + rand;
    var myAjax = new Ajax.Request( url, {method: 'get', parameters: pars, onLoading: fm_showLoad, onComplete: responseFunction} );
}

/*
Method to show the loading icon, once the ajax function is processed
*/
function fm_showLoad ()
{
    Element.show('loading');
}

/*
Method to show the Ajax Response once a block is added
*/
function fm_showAddResponse (originalRequest)
{
    Element.hide('loading');
    $('dropzone').innerHTML += originalRequest.responseText;
    fm_setupAddBlocks();
    fm_setupDeleteBlocks();
    adjustLayout();
}

/*
Method to show the Ajax Response once a block is removed
*/
function fm_showDeleteResponse (originalRequest)
{
    Element.hide('loading');
    $('deletezone').innerHTML += originalRequest.responseText;
    fm_setupAddBlocks();
    fm_setupDeleteBlocks();
    adjustLayout();
}

/*
Method to make the unused blocks draggable. Also sets up drop zone
*/
function fm_setupAddBlocks()
{
    var addblocks = document.getElementsByClassName('addblocks');
    for (var i = 0; i < addblocks.length; i++) {
    	new Draggable(addblocks[i].id, {ghosting:false, revert:true, zIndex:100});	
    }
    Droppables.add('dropzone', {onDrop:fm_addBlock, accept:'addblocks'});
}

/*
Method to make the used blocks draggable. Also sets up drop zone
*/
function fm_setupDeleteBlocks()
{   
    var deleteblocks = document.getElementsByClassName('usedblock');
    for (var i = 0; i < deleteblocks.length; i++) {
    	new Draggable(deleteblocks[i].id, {ghosting:false, revert:true, zIndex:1000})	
    }
    Droppables.add('deletezone', {onDrop:fm_removeBlock, accept:'usedblock'});
}

/* ------------ content_add ------------------*/
/*
Function to intitialize scriptaculous for content_add
*/
function ca_init(pageid, setionid)
{
    ID = pageid;
    SECTION = sectionid;
    ca_setupAddBlocks();
    ca_setupDeleteBlocks();
}

/*
Function to add a block. This function is called everytime an unused block is dropped on the 'dropzone' div
*/
function ca_addBlock(element, dropon, event)
{
    Droppables.remove($(element.id));
    Element.remove($(element.id));
    ca_sendData(element.id, 'adddynamicpageblock', ca_showAddResponse);
}

/*
Function to remove a block. This function is called everytime an used block is dropped on the 'deletezone' div
*/
function ca_removeBlock(element, dropon, event)
{
    Droppables.remove($(element.id));
    Element.remove($(element.id));
    ca_sendData(element.id, 'removedynamicpageblock', ca_showDeleteResponse);
}

/*
Ajax Function - Method to send the block to the server
*/
function ca_sendData (prod, action, responseFunction)
{
    var url    = 'index.php';
    var rand   = Math.random(9999);
    var pars   = 'module=cmsadmin&action='+action+'&pageid='+ID+'&sectionid='+SECTION+'&blockid=' + prod + '&rand=' + rand;
    var myAjax = new Ajax.Request( url, {method: 'get', parameters: pars, onLoading: ca_showLoad, onComplete: responseFunction} );
}

/*
Method to show the loading icon, once the ajax function is processed
*/
function ca_showLoad ()
{
    Element.show('loading');
}

/*
Method to show the Ajax Response once a block is added
*/
function ca_showAddResponse (originalRequest)
{
    Element.hide('loading');
    $('dropzone').innerHTML += originalRequest.responseText;
    ca_setupAddBlocks();
    ca_setupDeleteBlocks();
    adjustLayout();
}

/*
Method to show the Ajax Response once a block is removed
*/
function ca_showDeleteResponse (originalRequest)
{
    Element.hide('loading');
    $('deletezone').innerHTML += originalRequest.responseText;
    ca_setupAddBlocks();
    ca_setupDeleteBlocks();
    adjustLayout();
}

/*
Method to make the unused blocks draggable. Also sets up drop zone
*/
function ca_setupAddBlocks()
{
    var addblocks = document.getElementsByClassName('addblocks');
    for (var i = 0; i < addblocks.length; i++) {
    	new Draggable(addblocks[i].id, {ghosting:false, revert:true, zindex:2000});	
    }
    Droppables.add('dropzone', {onDrop:ca_addBlock, accept:'addblocks'});
}

/*
Method to make the used blocks draggable. Also sets up drop zone
*/
function ca_setupDeleteBlocks()
{   
    var deleteblocks = document.getElementsByClassName('usedblock');
    for (var i = 0; i < deleteblocks.length; i++) {
    	new Draggable(deleteblocks[i].id, {ghosting:false, revert:true, zindex:20})	
    }
    Droppables.add('deletezone', {onDrop:ca_removeBlock, accept:'usedblock'});
}

/* ---------------- section_add ------------------*/
/*
Function to intitialize scriptaculous for section_add
*/
function sa_processSection(sectionType)
{
    if(sectionType == "page"){
        Element.hide("pagenumlabel");
        Element.hide("pagenumcol");
        Element.hide("dateshowlabel");
        Element.hide("dateshowcol");
    } else {
        Element.show("pagenumlabel");
        Element.show("pagenumcol");
        Element.show("dateshowlabel");
        Element.show("dateshowcol");
    }   

    if (sectionType == 'summaries' || sectionType == 'list') {
        Element.show("showintrolabel");
        Element.show("showintrocol");
    } else {
        Element.hide("showintrolabel");
        Element.hide("showintrocol");
    }            
}