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
        $this->addJS();
        $this->loadClass('link', 'htmlelements');
        $objLanguage = $this->getObject('language', 'language');
        $content = $objLanguage->languageText('mod_oer_attachingfile', 'oer');
        $this->loadClass('iframe', 'htmlelements');
        $objAjaxUpload = $this->newObject('ajaxuploader', 'oer');

        $content.= $objAjaxUpload->show($id);
        $link = new link($this->uri(array("")));
        $link->link = $objLanguage->languageText('word_home', 'system');
        $content.= $link->show();
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
