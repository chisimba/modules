<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

$this->objLanguage =$this->newObject('language', 'language');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('checkbox', 'htmlelements');
$this->loadClass('radio', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('form', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('link', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');
$this->loadClass('textarea','htmlelements');
$this->loadClass('htmltable','htmlelements');
$this->loadClass('layer', 'htmlelements');

//Preventing Notices
if (!isset($searchKey)) {
    $searchKey = '';
}
if (!isset($cluster)) {
    $cluster = '';
}
if (!isset($searchCluster)) {
    $searchCluster = '';
}
if (!isset($defaultClusters)) {
    $defaultClusters = '';
}


$script = <<<STARTSEARCH
<script type="text/javascript">
    //Method to initiate a search against a particular data source
    function submitSearch(targetDiv, searchKeyArr, uri, paramArr){
        //targetDiv
    }

    jQuery(document).ready(function(){
        startSearch();
    });

</script>
STARTSEARCH;
$this->appendArrayVar('headerParams', $script);
//Getting the sources asscociated with the searched cluster

//$leftContent = 'Left Column';
$middleContent = "Now searching for \"<b>$searchKey</b>\" in the \"<b>$cluster</b>\" subject cluster:<br/>";

$clusterArr = $this->objClusters->getCluster($cluster);

/*
//TODO: Implement Search for all default clusters
foreach ($defaultClusters as $cluster) {

}
*/

$loadIcon = $this->getObject('geticon', 'htmlelements');
$loadIcon->setIcon('loader');
$loadIcon->title = 'Loading';

$table = new htmlTable();
$table->width = "100%";
$table->cellspacing = "0";
$table->cellpadding = "0";
$table->border = "0";
$table->attributes = "align ='center'";

$searchScript = '';

if (!empty($clusterArr) && isset($clusterArr['id'])) {
    $table->startRow();
    $table->addCell("<h3>$clusterArr[title]</h3>");
    $table->endRow();
    
    $sources = $this->objClusters->getClusterSources($clusterArr['id']);
    foreach ($sources as $src) {
        $table->startRow();
        $table->addCell("&nbsp;&nbsp;&nbsp;<i>$src[title]</i>", null,'top','left', null, 'colspan="2"');
        $table->addCell("<div id='$sources[id]'> Connecting... ".$loadIcon->show()." </div>");
        $table->endRow();
        
        //The javascript responsible for executing the queued ajax search requests
        $searchScript .= "
        <script type='javascript'>
            submitSearch($sources[id], $searchKey, $cluster, $sources[uri]);
        </script>";
    }
}

$middleContent .= $table->show();

//$this->setVar('leftContent', $leftContent);
$this->setVar('middleContent', $middleContent);

?>
