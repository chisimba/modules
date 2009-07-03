<?php
$this->loadClass('link','htmlelements');
$sectioncLink = new link($this->uri(array("action"=>"viewform", "formnumber"=>"D", "courseid"=>"math")));
$sectioncLink->link = "Form D";

$sectioncedit = new link($this->uri(array("action"=>"editform", "formnumber"=>"D", "userid"=>"user1", "courseid"=>"math")));
$sectioncedit->link = "Edit Form D";

$link1 = $sectioncLink->show();
$link2 = $sectioncedit->show();


$sectioncLink = new link($this->uri(array("action"=>"viewform", "formnumber"=>"C", "courseid"=>"math")));
$sectioncLink->link = "Form C";

$sectioncedit = new link($this->uri(array("action"=>"editform", "formnumber"=>"C", "userid"=>"user1", "courseid"=>"math")));
$sectioncedit->link = "Edit Form C";

$link3 = $sectioncLink->show();
$link4 = $sectioncedit->show();
echo "<ul>
<li>$link1</li>
<li>$link2</li>
<li>$link3</li>
<li>$link4</li>
</ul>";

?>
