<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$this->loadClass('link','htmlelements');


$linkText1 = "Load Dialogue Box With Chisimba Header";
$linkText2 = "Load Dialogue Box With Chisimba Header Suppressed";
///Create a new link to build the current form that accepts a parameter
///from the "getFormNumber" span.
    $mnglink1 = new link($this->uri(array(
                        'module' => 'jqueryconflictwithgoogle',
                        'action' => 'loadDialogBoxWithChisimbaHeader',

                    )));
        $mnglink2 = new link($this->uri(array(
                        'module' => 'jqueryconflictwithgoogle',
                        'action' => 'loadDialogBoxWithChisimbaHeaderSuppressed',

                    )));

///Set the link text and image.
    $mnglink1->link = $linkText1;
        $mnglink2->link = $linkText2;
///Build the link and show it.
        echo "Test Both Links on 3 browsers (IE, FireFox, Chrome):<BR><Br>";
    echo "This link will show that a dialog box will not work in chrome."."<BR>";
    echo $linkManage1 = $mnglink1->show()."<BR>";

        echo "Now that chisimba header is suppressed. This link will show that a dialog box
            will work properly in chrome."."<BR>";
    echo $linkManage2 = $mnglink2->show()."<Br>";
?>
