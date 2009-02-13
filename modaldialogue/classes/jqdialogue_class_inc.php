<?php

class jqdialogue extends object
{
    protected $objSkin;

    public function init()
    {
        $this->objSkin = $this->getObject('skin', 'skin');
    }

    public function show()
    {
        $this->objSkin->setVar('SUPPRESS_PROTOTYPE', true);
        $this->objSkin->setVar('JQUERY_VERSION', '1.2.6');

        $this->appendArrayVar('headerParams', $this->getJavascriptFile('jquery/api/ui/ui.core.js', 'htmlelements'));
        $this->appendArrayVar('headerParams', $this->getJavascriptFile('jquery/api/ui/dialog/ui.dialog.js', 'htmlelements'));

        $script = '<script type="text/javascript">jQuery(function(){jQuery("#dialog").dialog({bgiframe:true,height:140,modal:true});});</script>';
        $this->appendArrayVar('headerParams', $script);

        $html = '<div id="dialog" title="Basic modal dialog"><p>Adding the modal overlay screen makes the dialog look more prominent because it dims out the page content.</p></div>';

        return $html;
    }
}
