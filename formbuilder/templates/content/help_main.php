<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>

<style type="text/css">
    button.ui-button {
        WIDTH: 150px;
    }
</style>
<?php
$cssLayout = &$this->newObject('csslayout', 'htmlelements');

$userMenuBar = & $this->getObject('side_menu_handler', 'formbuilder');
$welcomePage = & $this->getObject('home_page_handler', 'formbuilder');

$leftContent = $userMenuBar->showSideMenu();
$middleContent = "Help Section Under Construction. Comming Soon.";
$cssLayout->setNumColumns(2);
$cssLayout->setLeftColumnContent("<div id='formPreviewDiv' class='ui-widget-content ui-corner-all'style='border:1px solid #CCCCCC;padding:20px 20px 20px 20px;'> " . $leftContent . "</div>");
$cssLayout->setMiddleColumnContent("<div id='formPreviewDiv' class='ui-accordion-content ui-widget-content ui-corner-all'style='border:1px solid #CCCCCC;padding:10px 25px 15px 25px;'> " . $middleContent . "</div>");
echo $cssLayout->show();
?>
<script type="text/javascript">


    jQuery(document).ready(function() {
        jQuery(".homeButton").button({

            icons: {
                primary: 'ui-icon-home'
            },
            text: true
        });
        jQuery(".listAllFormsButton").button({

            icons: {
                primary: 'ui-icon-script'
            },
            text: true
        });
        jQuery(".createNewFormButton").button({

            icons: {
                primary: 'ui-icon-document'
            },
            text: true
        });

        jQuery(".helpButton").button({

            icons: {
                primary: 'ui-icon-help'
            },
            text: true
        });

    });
</script>
