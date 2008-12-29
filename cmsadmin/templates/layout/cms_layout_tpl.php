<?php

/*
//This script drives the menus. Uses the YAHOO libs.
$script ='
<script type="text/javascript">
//<![CDATA[
// Initialize and render the menu when it is available in the DOM

            YAHOO.util.Event.onContentReady("productsandservices", function () {

                //     Instantiate the menu.  The first argument passed to the 
                //     constructor is the id of the element in the DOM that 
                //     represents the menu; the second is an object literal 
                //    representing a set of configuration properties for 
                //    the menu.
                //

                var oMenu = new YAHOO.widget.Menu(
                                    "productsandservices", 
                                    {
                                        position: "static", 
                                        hidedelay: 750, 
                                        lazyload: true, 
                                        effect: { 
                                            effect: YAHOO.widget.ContainerEffect.FADE,
                                            duration: 0.25
                                        } 
                                    }
                                );


                //     Call the "render" method with no arguments since the markup for 
                //     this menu already exists in the DOM.
                //

                oMenu.render();            
            
            });

//]]>
</script>
';
*/

/*
$script_jquery = "<script type=\"text/javascript\">
jQuery(document).ready(function(){
    jQuery('#tree1').SimpleTree();
    jQuery('#tree2').SimpleTree({animate: true});
    jQuery('#tree3').SimpleTree({animate: true,autoclose:true});
    jQuery('#tree4').SimpleTree({
        animate: true,
        autoclose:true,
        click:function(el){
            alert(jQuery(el).text());
        }
    });

});
</script>";
*/

//jQuery SuperFish Menu
$jQuery = $this->newObject('jquery', 'htmlelements');

//jQuery 1.2.6 SuperFish Menu
$jQuery->loadSuperFishMenuPlugin();

ob_start();
/*
?>

	<script type="text/javascript"> 
	// initialise Superfish 
	jQuery(document).ready(function(){ 
		jQuery("ul.sf-menu").superfish({ 
		animation: {opacity:'show'},   // slide-down effect without fade-in 
		width: 300,
		delay:     0,               // 1.2 second delay on mouseout 
		speed: 'fast',
		dropShadows: false
		}); 
	}); 
	
	</script>


<?PHP
*/
?>

    <script type="text/javascript"> 
    // initialise Superfish 
    jQuery(document).ready(function(){ 
        jQuery("ul.sf-menu").superfish({
        animation: {opacity:'show'},   // slide-down effect without fade-in 
        width: 300,
        delay: 0,               // 1.2 second delay on mouseout 
        speed: 'fast'
        }); 
    }); 
    
    </script>


<?PHP

$script = ob_get_contents();
ob_end_clean();

ob_start();
?>
<script type="text/javascript">
var simpleTreeCollection;
jQuery(document).ready(function(){
    simpleTreeCollection = jQuery('.simpleTree').simpleTree({
        autoclose: true,
        drag: false,
        afterClick:function(node){
            //alert("text-"+jQuery('span:first',node).text());
            //alert("link-"+jQuery('.active a:first', node).attr('href') + "\n");

            var turl = jQuery('.active a:first', node).attr('href');
            document.location.href = turl;

            /*
            var turl = jQuery('.active a:first', node).attr('href');
            var xhr = jQuery.ajax({
                type: 'GET',
                url:turl,
                success:function(){
                            var cleanContent = xhr.responseText;
                            cleanContent = jQuery('#content', cleanContent).html()
                            jQuery('#content').html(cleanContent);
                        }
            });
            */
            //alert(xhr.responseText);

            //jQuery('#content').html(tcontent);

            //jQuery('#content').load(turl);
        },
        /*
        afterDblClick:function(node){
            //alert("text-"+$('span:first',node).text());
        },
        afterMove:function(destination, source, pos){
            //alert("destination-"+destination.attr('id')+" source-"+source.attr('id')+" pos-"+pos);
        },
        afterAjax:function()
        {
            //alert('Loaded');
        },
        */
        animate:true
        //,docToFolderConvert:true
    });
});
</script>
<?php
$script = ob_get_contents();
ob_end_clean();

$this->appendArrayVar('headerParams', $script);

$jQuery->loadSimpleTreePlugin();


// Create an instance of the CSS Layout
$cssLayout = $this->getObject('csslayout', 'htmlelements');
//$css = '<link rel="stylesheet" type="text/css" media="all" href="'.$this->getResourceURI("menu/assets/skins/sam/menu.css", 'yahoolib').'" />';

//Yahoo Libs
//$this->appendArrayVar('headerParams', $this->getJavascriptFile('yahoo-dom-event/yahoo-dom-event.js', 'yahoolib'));
//$this->appendArrayVar('headerParams', $this->getJavascriptFile('animation/animation.js', 'yahoolib'));
//$this->appendArrayVar('headerParams', $this->getJavascriptFile('container/container_core.js', 'yahoolib'));
//$this->appendArrayVar('headerParams', $this->getJavascriptFile('menu/menu.js', 'yahoolib'));
//$this->setVar('bodyParams','class=" yui-skin-sam"');	
//$this->appendArrayVar('headerParams', $css);
$this->appendArrayVar('headerParams',$script);

//Set to automatically render htmllist into tree menu
$cssLayout->setNumColumns(2);
// Set the Content of middle column
$cssLayout->setLeftColumnContent($this->getCMSMenu());
$cssLayout->setMiddleColumnContent($this->getContent());

// Display the Layout
echo $cssLayout->show();

?>
