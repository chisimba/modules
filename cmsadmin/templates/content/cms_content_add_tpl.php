<?php


/**
 * This template will create new content item
 *
 * @package cmsadmin
 * @author Warren Windvogel
 * @author Wesley Nitskie
 */

//first check if there is sections
/*if (!$this->_objSections->isSections()) {
    $str = '<script language="javascript" type="text/JavaScript">
           <![CDATA[
           alert(\''.$this->objLanguage->languageText('mod_cmsadmin_addsectionfirst', 'cmsadmin').'\');
           ]]>
           </script>';
    print $str;
} else {
*/    print $addEditForm;
//}

?>
