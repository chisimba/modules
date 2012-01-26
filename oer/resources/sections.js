jQuery(document).ready(function(){ 
    
    
    jQuery("#form_createsectionnode").validate();
    jQuery("#calendar").hide();
    jQuery("#module").hide();
    jQuery("#year").hide();
    jQuery("#createin").hide();
    jQuery("#curriculum").hide();
}); 


/**
 * this dynamically shows the type of node when creating a curriculum
 */
function displaySelectedNode(){
    var selectedVal = jQuery("#input_nodetype").val(); 
    if(selectedVal == 'curriculum'){
        jQuery("#createin").hide();
        jQuery("#curriculum").show();
    }else  if(selectedVal == 'calendar'){
        jQuery("#calendar").show();
        jQuery("#module").hide();
        jQuery("#year").hide();
        jQuery("#createin").show();
        jQuery("#curriculum").hide();
    }else    if(selectedVal == 'module'){
        jQuery("#module").show();
        jQuery("#calendar").hide();
        jQuery("#year").hide();
        jQuery("#createin").show();
        jQuery("#curriculum").hide();
    }else  if(selectedVal == 'year'){
        jQuery("#year").show();
        jQuery("#calendar").hide();
        jQuery("#module").hide();
        jQuery("#createin").show();
        jQuery("#curriculum").hide();
    }
       
}