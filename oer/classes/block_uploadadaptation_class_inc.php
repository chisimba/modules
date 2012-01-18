<?php

/**
 * Handles files upload
 *
 * @author pwando
 */
class block_uploadadaptation extends object {

    function init() {
        
    }

    function show() {
        $id=  $this->configData;
        //Load objects
        $this->addJS();
        $this->loadClass('link', 'htmlelements');
        $objLanguage = $this->getObject('language', 'language');
        $content = $objLanguage->languageText('mod_oer_attachingfile', 'oer');
        $this->loadClass('iframe', 'htmlelements');
        $objAjaxUpload = $this->newObject('ajaxuploader', 'oer');
        //Load Ajax Upload form
        $content.= $objAjaxUpload->show($id);

        //Back button
        $backButton = new button('back', $objLanguage->languageText('word_back'));
        $backUri = $this->uri(array("action" => "editadaptationstep3", "id" => $id));
        $backButton->setOnClick('javascript: window.location=\'' . $backUri . '\'');
        $content.= $backButton->show();

        //Finish button
        $button = new button('finish', $objLanguage->languageText('mod_oer_finish', 'oer'));
        $uri = $this->uri(array("action" => "1b"));
        $button->setOnClick('javascript: window.location=\'' . $uri . '\'');
        
        $content.= "&nbsp;".$button->show();
        return $content;
    }

    function addJS() {
        $this->appendArrayVar('headerParams', "

<script type=\"text/javascript\">
    //<![CDATA[

    function loadAjaxForm(fileid) {
        window.setTimeout('loadForm(\"'+fileid+'\");', 1000);
    }

    function loadForm(fileid) {
        var pars = \"module=oer&action=ajaxprocess&id=\"+fileid;
        new Ajax.Request('index.php',{
            method:'get',
            parameters: pars,
            onSuccess: function(transport){
                var response = transport.responseText || \"no response text\";
                $('updateform').innerHTML = response;
            },
            onFailure: function(transport){
                var response = transport.responseText || \"no response text\";
                //alert('Could not download module: '+response);
            }
        });
    }

    function processConversions() {
        window.setTimeout('doConversion();', 2000);
    }

    function doConversion() {

        var pars = \"module=oer&action=ajaxprocessconversions\";
        new Ajax.Request('index.php',{
            method:'get',
            parameters: pars,
            onSuccess: function(transport){
                var response = transport.responseText || \"no response text\";
                //alert(response);
            },
            onFailure: function(transport){
                var response = transport.responseText || \"no response text\";
                //alert('Could not download module: '+response);
            }
        });
    }
    //]]>
</script>            
");
    }

}

?>
