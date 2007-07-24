<?php

//This script drives the menus. Uses the YAHOO libs.
$script ='
<script type="text/javascript">
//<![CDATA[
YAHOO.example.onMenuReady = function() {

                // Instantiate and render the menu

                var oMenu = new YAHOO.widget.Menu(
                                    "productsandservices", 
                                    {
                                        position:"static", 
                                        hidedelay:750, 
                                        lazyload:true 
                                    }
                                );

                oMenu.render();

            };


            // Initialize and render the menu when it is available in the DOM

            YAHOO.util.Event.onContentReady("productsandservices", YAHOO.example.onMenuReady);
            

//]]>
</script>
';

// Create an instance of the CSS Layout
$cssLayout = $this->getObject('csslayout', 'htmlelements');
$css = '<link rel="stylesheet" type="text/css" media="all" href="'.$this->getResourceURI("fonts/fonts.css", 'yahoolib').'" />';
$css .= '<link rel="stylesheet" type="text/css" media="all" href="'.$this->getResourceURI("reset/reset.css", 'yahoolib').'" />';
$css .= '<link rel="stylesheet" type="text/css" media="all" href="'.$this->getResourceURI("container/assets/container.css", 'yahoolib').'" />';
$css .= '<link rel="stylesheet" type="text/css" media="all" href="'.$this->getResourceURI("menu/assets/menu.css", 'yahoolib').'" />';
$this->appendArrayVar('headerParams', $this->getJavascriptFile('menu/menu.js', 'yahoolib'));
$this->appendArrayVar('headerParams', $css);
$this->appendArrayVar('headerParams',$script);
//Set to automatically render htmllist into tree menu
$cssLayout->setNumColumns(2);
// Set the Content of middle column
$cssLayout->setLeftColumnContent($this->getCMSMenu());
$cssLayout->setMiddleColumnContent($this->getContent());

// Display the Layout
echo $cssLayout->show();

?>