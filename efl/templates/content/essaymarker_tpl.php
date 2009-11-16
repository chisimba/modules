<?php

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-control: post-check=0, pre-check=0, false");
header("Pragma: no-cache");
header("Content-Type: application/x-java-jnlp-file");
$objAltConfig = $this->getObject('altconfig','config');
$modPath=$objAltConfig->getModulePath();
$replacewith="";
$docRoot=$_SERVER['DOCUMENT_ROOT'];
$resourcePath=str_replace($docRoot,$replacewith,$modPath);
$codebase="http://" . $_SERVER['HTTP_HOST']."/".$resourcePath.'/efl/resources/';
?>


<jnlp spec="1.0+" codebase="'.$codebase.'">
    <information>
        <title>EFL</title>
        <vendor>WITS eLearn</vendor>
        <description>EFL</description>
        <homepage href="http://www.wits.ac.za"/>
        <description kind="short">EFL</description>
        <icon href=<?php echo "$codebase/images/logo.png";?>/>
        <icon kind="splash" href=<?php echo "$codebase/images/splash_realtime.png";?>/>
        <offline-allowed/>
    </information>
    <resources>
        <j2se version="1.5+" />
        <jar href="efl.jar" />
    </resources>
    <application-desc main-class="efla.EflaApp">
    </application-desc>
    <security>
        <all-permissions/>
    </security>
</jnlp>
?>
