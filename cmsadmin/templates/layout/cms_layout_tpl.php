<?php

//This script drives the menus. Uses the YAHOO libs.
$script ='
<script type="text/javascript">
//<![CDATA[
// Initialize and render the menu when it is available in the DOM

            YAHOO.util.Event.onContentReady("productsandservices", function () {

                /*
                     Instantiate the menu.  The first argument passed to the 
                     constructor is the id of the element in the DOM that 
                     represents the menu; the second is an object literal 
                     representing a set of configuration properties for 
                     the menu.
                */

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

                /*
                     Call the "render" method with no arguments since the markup for 
                     this menu already exists in the DOM.
                */

                oMenu.render();            
            
            });

//]]>
</script>
';

// Create an instance of the CSS Layout
$cssLayout = $this->getObject('csslayout', 'htmlelements');
$css = '<link rel="stylesheet" type="text/css" media="all" href="'.$this->getResourceURI("menu/assets/skins/sam/menu.css", 'yahoolib').'" />';
$this->appendArrayVar('headerParams', $this->getJavascriptFile('yahoo-dom-event/yahoo-dom-event.js', 'yahoolib'));
$this->appendArrayVar('headerParams', $this->getJavascriptFile('animation/animation.js', 'yahoolib'));
$this->appendArrayVar('headerParams', $this->getJavascriptFile('container/container_core.js', 'yahoolib'));
$this->appendArrayVar('headerParams', $this->getJavascriptFile('menu/menu.js', 'yahoolib'));
$this->setVar('bodyParams','class=" yui-skin-sam"');	
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