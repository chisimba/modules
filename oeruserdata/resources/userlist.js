/* 
 * Javascript to support oeruserdata for delete action
 * on user list
 *
 * Written by Derek Keats derek@dkeats.com
 * STarted on: February 1, 2012, 8:34 am
 *
 */
var id;
var next;
var prev;
var newnext;
var newprev;
var pages;


/**
 *
 * Put your jQuery code inside this function.
 *
 */
jQuery(function() {    
    // Things to do on loading the page.
    jQuery(document).ready(function() {        
    });
    /*jQuery("a[class=confirm_del_user_link]").click(function(){
        var answer=confirm(confirm_delete_user);
        if (answer==true){
            var link = this.href;
            window.location=link;
        }
        return false;
    });*/
    // The function for deleting a post
    jQuery("a[class=confirm_del_user_link]").click(function(e) {
        var dId = jQuery(this).attr("id");
        //alert('clicked ' + dId);
        jQuery.ajax({
           beforeSend: function (request) {
                if (!confirm("You really want to delete?")) {
                    return FALSE;
                }
           },
           type: "POST",
           url: "index.php?module=oeruserdata&action=delete&id=" + dId,
           cache: false,
           success: function(ret){
               if(ret == "RECORD_DELETED") {
                   jQuery("#ROW_"+dId).slideUp('slow', function() {jQuery("#ROW_"+dId).remove();})
               } else {
                   alert(ret);
               }
          }
        });
        return false;
    });
    
    jQuery(".nav_next").delegate("img", "click", function() {
        id=jQuery(".nav_next").attr("id");
        next = id.replace("next_", "");
        //alert(next);
        jQuery.ajax({
           type: "POST",
           url: "index.php?module=oeruserdata&action=userlistajax&page=" + next,
           cache: false,
           success: function(ret){
               jQuery("#userlisting").html(ret);
               jQuery("#current_page").html(next);
               newnext = parseInt(next)+1;
               //alert(newnext);
               jQuery(".nav_next").attr("id", 'next_'+newnext);
          }
        });
        return false;
    });
    
    jQuery(".nav_prev").delegate("img", "click", function() {
        id=jQuery(".nav_prev").attr("id");
        prev = id.replace("prev_", "");
        alert(prev);
        jQuery.ajax({
           type: "POST",
           url: "index.php?module=oeruserdata&action=userlistajax&page=" + prev,
           cache: false,
           success: function(ret){
               jQuery("#userlisting").html(ret);
               jQuery("#previous_page").html(prev);
               newprev = parseInt(prev)-1;
               //alert(newnext);
               jQuery(".nav_prev").attr("id", 'prev_'+newprev);
          }
        });
        return false;
        
    });
});