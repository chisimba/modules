<?php

/**
 * Description: Layout template, for displaying html list side menus, to be used by all templates
 * @since 4th May 2011
 * @author - Academic Module Team
 *
 *
 */

 $sidemenus = '<ul type="square" id="ac_side_menu">';

 $sidemenus .= '<li><a href="?module=tzschoolacademics">Home</a></li>';
 $sidemenus .= '<li><a href="?module=tzschoolacademics&action=profile">My Profile</a></li>';
 $sidemenus .= '<li>';
 $sidemenus .= '<a href="?module=tzschoolacademics&action=setup">System Setup</a>';
 $sidemenus .= '    <ul type="square">';
 $sidemenus .= '        <li>Subjects</li>';
 $sidemenus .= '        <li>Grades</li>';
 $sidemenus .= '        <li>Forms</li>';
 $sidemenus .= '        <li>Departments</li>';
 $sidemenus .= '    </ul>';
 $sidemenus .= '</li>';
 $sidemenus .= '<li>Generate Reports</li>';
 $sidemenus .= '<li>User Management</li>';

 $sidemenus .= '</ul>';

 $objCssLayout = $this->newObject('csslayout','htmlelements');
 $objCssLayout->numColumns = 2;
 $objCssLayout->setLeftColumnContent($sidemenus);
 $objCssLayout->setMiddleColumnContent($this->getContent());
 
 echo $objCssLayout->show();


?>
