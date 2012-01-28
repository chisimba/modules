/**
 * 
 */

jQuery(document).ready(function(){ 
    jQuery("#form_curriculumform").validate();
    jQuery("#form_createsectionnode").validate();
    jQuery("#calendardiv").hide();
    jQuery("#modulediv").hide();
    jQuery("#yeardiv").hide();
    jQuery("#createin").hide();
    
//jQuery("#form_createsectionnode").validate().element( "#input_nodetype" );

}); 


/**
 * this dynamically shows the type of node when creating a curriculum
 */
function displaySelectedNode(){
    var selectedVal = jQuery("#input_nodetype").val(); 
    jQuery("#createin").show();
    
    if(selectedVal == 'calendar'){
        jQuery("#calendardiv").show();
        jQuery("#modulediv").hide();
        jQuery("#yeardiv").hide();
    }else    if(selectedVal == 'module'){
        jQuery("#modulediv").show();
        jQuery("#calendardiv").hide();
        jQuery("#yeardiv").hide();
    }else  if(selectedVal == 'year'){
        jQuery("#yeardiv").show();
        jQuery("#calendardiv").hide();
        jQuery("#modulediv").hide();
    }else{
        jQuery("#calendardiv").hide();
        jQuery("#modulediv").hide();
        jQuery("#yeardiv").hide();
        jQuery("#createin").hide(); 
    }
       
}