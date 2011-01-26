<?php

/**
 * Class alfrescodisplay containing all display/output functions of the alfresco module
 *
 * Displays the given alfresco site in an iframe.
 *
 * @author Warren Windvogel <warren.windvogel@wits.ac.za>
 *
 * @copyright Wits University 2010
 * @license http://opensource.org/licenses/lgpl-2.1.php
 *
 */
class alfrescodisplay extends object
{
   /** @var object $objLanguage: The language class of the language module
    * @access private
    */
    private $objLanguage;

   /**
    * @var object $objContext: The dbcontext class in the context module
    * @access public
    */
    public $objContext;

   /** @var object $objConfig: The Config class of the sysconfig module
    * @access public
    */
    public $objConfig;

    /**
    * Method to construct the class
    *
    * @access public
    * @return
    */
    public function init()
    {
        // load html element classes
        $this->loadClass('iframe', 'htmlelements');

        // system classes
        $this->objLanguage = $this->getObject('language','language');
        $this->objConfig = $this->getObject('dbsysconfig', 'sysconfig');
        $this->objAlfrescoApi = $this->getObject('alfrescoapi', 'alfresco');
    }

    /**
    * Method to display the Alfresco site in an iframe
    *
    * @access public
    * @return string $str: The output string
    **/
    public function displayiframe($width, $height)
    {
        //Get the redmine url from sys config
        $redmineUrl = $this->objConfig->getValue('mod_alfresco_siteurl', 'alfresco');
        //Create the iframe
        $objIframe = $this->newObject('iframe', 'htmlelements');
        $objIframe->width = $width;
        $objIframe->height = $height;
        $objIframe->src = $redmineUrl;
        $objIframe->border = 0;
        $objIframe->id = 'alfresco';
        $objIframe->name = 'alfresco';
        return $objIframe->show();
    }

    /**
     * Method to insert a row with a link to the content node 
     */
    public function outputRow($node)
    {
        //Remove illegal chars from string
        $jsVar = str_replace("-", "1", $node->id);
        //var cannot start with number
        $jsVar = 'a'.$jsVar;
        
        $content = '<script language="JavaScript">'.
            'var '.$jsVar.' ;'.
            '</script>';
        
        $content .= "<tr><td><a href='";
        $content .= $this->objAlfrescoApi->getURL($node);
        $content .= "'>";
        $content .= $node->cm_name;
        $content .= '</a></td><td><button id="'.$jsVar.'0" type="button" >Copy To Clipboard</button></td></tr>';
        $content .= '<script language="JavaScript">'.
            $jsVar.' = new ZeroClipboard.Client();'.
            $jsVar.'.setText("<a href='.$this->objAlfrescoApi->getURL($node).'>'.$node->cm_name.'</a>") ;'.
            $jsVar.'.setHandCursor( true );'.
            $jsVar.'.glue( "'.$jsVar.'0" );'.
            '</script>';

        return $content;
    }

    /**
     * Method to return the html for a table containing Alfresco content with way of copying link to content
     *
     * @param string $title The tables title
     * @param object $node The node object
     * @param string $type_filter The alfresco element to display. Content or folder etc.
     * @param string $empty_message The message to display if there are no elements to display
     * @access public
     * @return string $html The html to display the content
     */
    public function outputTable($title, $node, $type_filter = '{http://www.alfresco.org/model/content/1.0}content', $empty_message = 'No content')
    {
        //Add the zeroclipboard js
        $this->appendArrayVar('headerParams', $this->getJavascriptFile('ZeroClipboard.js', ''));

        //Set the js zero clipboard vars
        $this->appendArrayVar('headerParams', '<script language="JavaScript">ZeroClipboard.setMoviePath( "http://localhost/vre/packages/alfresco/resources/ZeroClipboard.swf" );');
        $this->appendArrayVar('headerParams', 'ZeroClipboard.setMoviePath( "http://localhost/vre/packages/alfresco/resources/ZeroClipboard10.swf" );</script>');

        $html = "<table cellspacing=1 cellpadding=1 border=0 width=95% align=center>".
        "   <tr>".
        "       <td bgcolor='#D3E6FE'>".
        "           <table border='0' cellspacing='0' cellpadding='0' width='100%'><tr><td><span>".$title."</span></td></tr></table>".
        "       </td>".
        "   </tr>".
        "   <tr>".
        "       <td bgcolor='white' style='padding-top:6px;'>".
        "           <table border='0' cellspacing='3' cellpadding='3' width='100%'>";
        //Loop through content and display each item in a new row
        foreach ($node->children as $child)
        {
            if ($child->child->type == $type_filter)
            {
                $html .= $this->outputRow($child->child);
            }
        }
        //Closing tags
        $html .= "         </table>".
        "      </td>".
        "   </tr>".
        "</table>";

        return $html;
    }
}
?>