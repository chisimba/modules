<?php
$xmlGenLink = $this->uri(
  array(
    "action"=>"genxml"
  ), "portalimporter"
);

$xmlReadLink = $this->uri(
  array(
    "action"=>"readportal"
  ), "portalimporter"
);

$dtaStoreLink = $this->uri(
  array(
    "action"=>"storedata"
  ), "portalimporter"
);

$showStructured = $this->uri(
  array(
    "action"=>"showstructured"
  ), "portalimporter"
);

$showFiles = $this->uri(
  array(
    "action"=>"showfiles"
  ), "portalimporter"
);
$configure = $this->uri(
  array(
    "action"=>"step2",
    "pmodule_id" => "portalimporter"
  ), "sysconfig"
);

//module=&action=&pmodule_id=portalimporter
?>
<h3>Options</h3>

Please note that these are all actions that may take a long time to complete. 
Half an hour for a large site would not be unusual.  

<ul>
<li><a href="<?php echo $xmlReadLink;?>">Read portal content and show XML</a></li>
<li><a href="<?php echo $showStructured;?>">Read portal content and show which files are structured and which raw</a></li>
<li><a href="<?php echo $showFiles;?>">Read portal content and show all files</a></li>
<li><a href="<?php echo $xmlGenLink;?>">Read portal content and generate XML to file</a></li>
<li><a href="<?php echo $dtaStoreLink;?>">Read portal content and store in database</a></li>
<br />
<li><a href="<?php echo $configure;?>">Configure import settings</a></li>
</ul>

<?php

if (isset($str)) {
   echo $str; 
}

?>