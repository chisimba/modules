


function loadConversations(id)
    {
         
        jQuery.ajax({
            type: "GET", 
            url: "index.php", 
            data: "module=das&action=getconversations&id="+id,
            success: function(msg){
                jQuery('#conversations').html(msg);
                if ('function' == typeof window.adjustLayout) {
                    adjustLayout();
                }
            }
        });
        
        
        //alert(workgroupId);
        
    }
	
function showLoading()
{
	
	div = document.getElementById("conversations");
	div.innerHTML = "<h3>Loading Conversations...<img src=\"skins/_common/icons/loader.gif\"></h3>";
	
}