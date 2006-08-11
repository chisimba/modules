<?php 
/////////////////////////////////////////////////////////////////////////////// 
// xajax version 0.1 beta4 
// copyright (c) 2005 by J. Max Wilson 
// <http://xajax.sourceforge.net> 
// 
// 
// xajax is an open source PHP class library for easily creating powerful 
// PHP-driven, web-based AJAX Applications. Using xajax, you can asynchronously 
// call PHP functions and update the content of your your webpage without 
// reloading the page. 
// 
// xajax is released under the terms of the LGPL license 
// http://www.gnu.org/copyleft/lesser.html#SEC3 <http://www.gnu.org/copyleft/lesser.html> 
// 
// This library is free software; you can redistribute it and/or 
// modify it under the terms of the GNU Lesser General Public 
// License as published by the Free Software Foundation; either 
// version 2.1 of the License, or (at your option) any later version. 
// 
// This library is distributed in the hope that it will be useful, 
// but WITHOUT ANY WARRANTY; without even the implied warranty of 
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU 
// Lesser General Public License for more details. 
//  
// You should have received a copy of the GNU Lesser General Public 
// License along with this library; if not, write to the Free Software 
// Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA 
/////////////////////////////////////////////////////////////////////////////// 
 
// The xajaxResponse class is used to created responses to be sent back to your 
// webpage. A response contains one or more command messages for updating your page. 
// Currently xajax supports five kinds of command messages: 
// * Assign - sets the specified attribute of an element in your page 
// * Append - appends data to the end of the specified attribute of an element in your page 
// * Prepend - prepends data to teh beginning of the specified attribute of an element in your page 
// * Replace - searches for and replaces data in the specified attribute of an element in your page 
// * Script - runs JavaScript 
// * Alert - shows an alert box with the suplied message text 
// elements are identified by their HTML id 
class xajaxResponse 
{ 
    var $xml; 
     
    // Constructor 
    function xajaxResponse() 
    { 
        $this->xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>"; 
        $this->xml .= "<xajax>"; 
    } 
     
    // addAssign() adds an assign command message to your xml response 
    // $sTarget is a string containing the id of an HTML element 
    // $sAttribute is the part of the element you wish to modify ("innerHTML", "value", etc.) 
    // $sData is the data you want to set the attribute to 
    // usage: $objResponse->addAssign("contentDiv","innerHTML","Some Text"); 
    function addAssign($sTarget,$sAttribute,$sData) 
    { 
        $this->xml .= "<update action=\"assign\">"; 
        $this->xml .= "<target attribute=\"$sAttribute\">$sTarget</target>"; 
        $this->xml .= "<data><![CDATA[$sData]]></data>"; 
        $this->xml .= "</update>"; 
    } 
     
    // addAppend() adds an append command message to your xml response 
    // $sTarget is a string containing the id of an HTML element 
    // $sAttribute is the part of the element you wish to modify ("innerHTML", "value", etc.) 
    // $sData is the data you want to append to the end of the attribute 
    // usage: $objResponse->addAppend("contentDiv","innerHTML","Some Text"); 
    function addAppend($sTarget,$sAttribute,$sData) 
    { 
        $this->xml .= "<update action=\"append\">"; 
        $this->xml .= "<target attribute=\"$sAttribute\">$sTarget</target>"; 
        $this->xml .= "<data><![CDATA[$sData]]></data>"; 
        $this->xml .= "</update>"; 
    } 
     
    // addPrepend() adds an prepend command message to your xml response 
    // $sTarget is a string containing the id of an HTML element 
    // $sAttribute is the part of the element you wish to modify ("innerHTML", "value", etc.) 
    // $sData is the data you want to prepend to the beginning of the attribute 
    // usage: $objResponse->addPrepend("contentDiv","innerHTML","Some Text"); 
    function addPrepend($sTarget,$sAttribute,$sData) 
    { 
        $this->xml .= "<update action=\"prepend\">"; 
        $this->xml .= "<target attribute=\"$sAttribute\">$sTarget</target>"; 
        $this->xml .= "<data><![CDATA[$sData]]></data>"; 
        $this->xml .= "</update>"; 
    } 
     
    // addReplace() adds an replace command message to your xml response 
    // $sTarget is a string containing the id of an HTML element 
    // $sAttribute is the part of the element you wish to modify ("innerHTML", "value", etc.) 
    // $sSearch is a string to search for 
    // $sData is a string to replace the search string when found in the attribute 
    // usage: $objResponse->addReplace("contentDiv","innerHTML","text","<b>text</b>"); 
    function addReplace($sTarget,$sAttribute,$sSearch,$sData) 
    { 
        $this->xml .= "<update action=\"replace\">"; 
        $this->xml .= "<target attribute=\"$sAttribute\">$sTarget</target>"; 
        $this->xml .= "<search><![CDATA[$sSearch]]></search>"; 
        $this->xml .= "<data><![CDATA[$sData]]></data>"; 
        $this->xml .= "</update>"; 
    } 
     
    // addClear() adds an clear command message to your xml response 
    // $sTarget is a string containing the id of an HTML element 
    // $sAttribute is the part of the element you wish to clear ("innerHTML", "value", etc.) 
    // usage: $objResponse->addClear("contentDiv","innerHTML"); 
    function addClear($sTarget,$sAttribute) 
    { 
        $this->xml .= "<update action=\"clear\">"; 
        $this->xml .= "<target attribute=\"$sAttribute\">$sTarget</target>"; 
        $this->xml .= "</update>"; 
    } 
     
    // addAlert() adds an alert command message to your xml response 
    // $sMsg is a text to be displayed in the alert box 
    // usage: $objResponse->addAlert("This is some text"); 
    function addAlert($sMsg) 
    { 
        $this->xml .= "<alert><![CDATA[$sMsg]]></alert>"; 
    } 
     
    // addScript() adds a jscript command message to your xml response 
    // $sJS is a string containing javascript code to be executed 
    // usage: $objResponse->addAlert("var x = prompt('get some text');"); 
    function addScript($sJS) 
    { 
        $this->xml .= "<jscript><![CDATA[$sJS]]></jscript>"; 
    } 
     
    // addRemove() adds a Remove Element command message to your xml response 
    // $sTarget is a string containing the id of an HTML element to be removed 
    // from your page 
    // usage: $objResponse->addRemove("Div2"); 
    function addRemove($sTarget) 
    { 
        $this->xml .= "<update action=\"remove\">"; 
        $this->xml .= "<target>$sTarget</target>"; 
        $this->xml .= "</update>"; 
    } 
     
    function addCreate($sParent, $sTag, $sId, $sType="") 
    { 
        $this->xml .= "<update action=\"create\">"; 
        $this->xml .= "<target attribute=\"$sTag\">$sParent</target>"; 
        $this->xml .= "<data><![CDATA[$sId]]></data>"; 
        if ($sType != "") {
            $this->xml .= "<type><![CDATA[$sType]]></type>"; 
        }
        $this->xml .= "</update>"; 
    } 
     
    // getXML() returns the xml to be returned from your function to the xajax 
    // processor on your page 
    // usage: $objResponse->getXML(); 
    function getXML() 
    { 
        if (strstr($this->xml,"</xajax>") == false) {
            $this->xml .= "</xajax>"; 
        }
     
    return $this->xml;  
    } 
}// end class xajaxResponse 
 
// Communication Method Defines 
if (!defined ('GET')) {
    define ('GET', 0); 
} 

if (!defined ('POST')) { 
    define ('POST', 1); 
} 

?>