<?php

class jqdialogue extends object
{
    /**
     * The instance of the skin class in the skin module.
     *
     * @access protected
     * @var object $objSkin
     */
    protected $objSkin;

    /**
     * The title of the dialogue.
     *
     * @access protected
     * @var string $title
     */
    protected $title;

    /**
     * The contents of the dialogue.
     *
     * @access protected
     * @var string $content
     */
    protected $content;

    public function init()
    {
        $this->objSkin = $this->getObject('skin', 'skin');
    }

    /**
     * Sets the title of the dialogue.
     *
     * @access public
     * @param string $title The new title of the dialogue.
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Sets the content of the dialogue.
     *
     * @access public
     * @param string $content The new content of the dialogue.
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Adds applicable scripts to the HTML header and returns the HTML for the body.
     *
     * @access public
     * @return string The HTML for the body.
     */
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
