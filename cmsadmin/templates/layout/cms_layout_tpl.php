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

//This is the history script which needs to be executed within the body of every page.
//Uses Bookmarks to limit reload times. Necessary for back and foward navigation without
//needing page reload cycles.
$history =' 
<script type="text/javascript">
//<![CDATA[
	var bookmarkedSection = YAHOO.util.History.getBookmarkedState( "app" );
    var querySection = YAHOO.util.History.getQueryStringParameter( "action" );
    var initSection = bookmarkedSection || querySection || "NULL";

    YAHOO.util.History.register( "app", initSection, function( state ) {
        loadSection( state );
    } );

    function loadSection( section ) {
        var url = section ;

        function successHandler( obj ) {
            // Use the response...
            YAHOO.util.Dom.get( "container" ).innerHTML = obj.responseText;
        }

        function failureHandler( obj ) {
            // Fallback...
            location.href = "cmsadmin&amp;action=" + section;
        }

        YAHOO.util.Connect.asyncRequest( "GET", url,
            {
                success:successHandler,
                failure:failureHandler
            }
        );
    }

    function initializeNavigationBar() {
       
        var anchors = YAHOO.util.Dom.get( "container" ).getElementsByTagName( "a" );
        for ( var i=0, len=anchors.length ; i<len ; i++ ) {
            var anchor = anchors[i];
            
            YAHOO.util.Event.addListener( anchor, "click", function( evt ) {
                var href = this.getAttribute( "href" );
                var section = YAHOO.util.History.getQueryStringParameter( "action", href );
               try {
                    YAHOO.util.History.navigate( "app", section );
                } catch ( e ) {
                    loadSection( section );
                }
                YAHOO.util.Event.preventDefault( evt );
            } );
        }

        
        var currentSection = YAHOO.util.History.getCurrentState( "app" );
        if ( location.hash.substr(1).length > 0 ) {
            if ( currentSection != querySection )
                YAHOO.util.Dom.get( "container" ).innerHTML = "";
            loadSection( currentSection );
        }
    }

    YAHOO.util.History.onLoadEvent.subscribe( function() {
        initializeNavigationBar();
    } );

    try {
        YAHOO.util.History.initialize();
    } catch ( e ) {
        initializeNavigationBar();
    }

//]]>
</script>
';
// Create an instance of the CSS Layout
$cssLayout = $this->getObject('csslayout', 'htmlelements');
$this->appendArrayVar('headerParams', $this->getJavascriptFile('yahoo/yahoo.js', 'yahoolib'));
$this->appendArrayVar('headerParams', $this->getJavascriptFile('event/event.js', 'yahoolib'));
$this->appendArrayVar('headerParams', $this->getJavascriptFile('dom/dom.js', 'yahoolib'));
$this->appendArrayVar('headerParams', $this->getJavascriptFile('animation/animation.js', 'yahoolib'));
$this->appendArrayVar('headerParams', $this->getJavascriptFile('connection/connection.js', 'yahoolib'));
$this->appendArrayVar('headerParams', $this->getJavascriptFile('history/history-experimental.js', 'yahoolib'));
$this->appendArrayVar('headerParams', $this->getJavascriptFile('container/container.js', 'yahoolib'));
$this->appendArrayVar('headerParams', $this->getJavascriptFile('menu/menu.js', 'cmsadmin'));
$this->appendArrayVar('headerParams',$script);
$css = '<link rel="stylesheet" type="text/css" media="all" href="'.$this->getResourceURI("fonts/fonts.css", 'yahoolib').'" />';
$css .= '<link rel="stylesheet" type="text/css" media="all" href="'.$this->getResourceURI("reset/reset.css", 'yahoolib').'" />';
$css .= '<link rel="stylesheet" type="text/css" media="all" href="'.$this->getResourceURI("menu/assets/menu.css", 'yahoolib').'" />';
$this->appendArrayVar('headerParams', $css);
//Set to automatically render htmllist into tree menu
$cssLayout->setNumColumns(2);
// Set the Content of middle column
$cssLayout->setLeftColumnContent($this->getCMSMenu());
$cssLayout->setMiddleColumnContent($this->getContent().$history);

// Display the Layout
echo $cssLayout->show();

?>