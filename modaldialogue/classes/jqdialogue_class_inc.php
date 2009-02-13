<?php

class jqdialogue extends object
{
    protected $objSkin;
    protected $title;
    protected $content;

    public function init()
    {
        $this->objSkin = $this->getObject('skin', 'skin');
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function show()
    {
        $this->objSkin->setVar('SUPPRESS_PROTOTYPE', true);
        $this->objSkin->setVar('JQUERY_VERSION', '1.2.6');

        $this->appendArrayVar('headerParams', $this->getJavascriptFile('jquery/api/ui/ui.core.js', 'htmlelements'));
        $this->appendArrayVar('headerParams', $this->getJavascriptFile('jquery/api/ui/dialog/ui.dialog.js', 'htmlelements'));

        $script = '<script type="text/javascript">jQuery(function(){jQuery("#dialog").dialog({bgiframe:true,height:140,modal:true});});</script>';
        $this->appendArrayVar('headerParams', $script);

        $html = '<div id="dialog" title="'.htmlspecialchars($this->title).'"><p>'.htmlspecialchars($this->content).'</p></div>';

        return $html;
    }
}
