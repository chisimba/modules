/* 
 * Javascript to support simpleblog
 *
 * Written by Derek Keats
 * Started on: January 17, 2011, 1:41 pm
 *
 *
 */

// Helper function for debugging
function seeData(data) {
    alert(data);
}

/**
 *
 * Put your jQuery code inside this function.
 *
 */
jQuery(function() {

    var id;
    var elocation;
    var hideWall;
    var showWall;
    var wallpoint;
    
    // Add borders to the active Title input
    jQuery("#input_post_title").live("click", function(){
        jQuery("#input_post_title").css("border","2px dotted red");
    });
    jQuery("#input_post_title").live("blur", function(){
        jQuery("#input_post_title").css("border","none");
    });
    /*jQuery(".simpleblog_editicon").live("click", function(){
        id = jQuery(this).attr("id");
        elocation = '#wrapper_'+id;
        jQuery(elocation).load('index.php?module=simpleblog&action=geteditorajax&mode=edit&postid='+id);
        //alert(elocation);
    });*/
    jQuery(".simpleblog_delicon").live("click", function(){
        id = jQuery(this).attr("id");
        var target='index.php?module=simpleblog';
        //alert(id);
        jQuery.ajax({
            url: target,
            type: "POST",
            data: "&action=delpost&postid="+id,
            success: function(msg) {
                alert(msg);
            }
        });
    });

    // Show the wall the first time
    jQuery(".wall_link").live("click", function(){
        id = jQuery(this).attr("id");
        id = id.replace("wall_link_", "");
        wallpoint = '#simpleblog_wall_'+id;
        var tmpTxt = jQuery("#simpleblog_wall_nav_"+id).html();
        hideWall = '<div class="togglewall" id="togglewall_'+id+'"><a href="javascript:void(0);" class="togglewall_link">Toggle wall</a></div>';
        jQuery("#simpleblog_wall_nav_"+id).html(hideWall);
        jQuery(wallpoint).load('index.php?module=wall&action=getsimpleblogwall&walltype=4&identifier='+id);
        //jQuery.get('index.php?module=wall&action=getsimpleblogwall&walltype=4&identifier='+id, seeData);
    });

    // Toggle the wall
    jQuery(".togglewall").live("click", function(){
        id = jQuery(this).attr("id");
        fixedid = id.replace("togglewall_", "");
        wallpoint = '#simpleblog_wall_'+fixedid;
        jQuery(wallpoint).toggle('slow');
    });


});