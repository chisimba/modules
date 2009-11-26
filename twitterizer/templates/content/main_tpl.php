<?php
//suppress everything
$this->setVar('pageSuppressBanner', TRUE);
$this->setVar('pageSuppressToolbar', TRUE);
$this->setVar('suppressFooter', TRUE);
$this->setVar('pageSuppressSkin', TRUE);
$this->setVar('pageSuppressContainer', TRUE);
$this->setVar('SUPPRESS_PROTOTYPE', true);
$this->setVar('SUPPRESS_JQUERY', true);

$objExtJS = $this->getObject('extjs','htmlelements');
$objExtJS->show();
$this->appendArrayVar('headerParams', '
        	<script type="text/javascript">	        		
        		var baseUri = "'.$this->objConfig->getsiteRoot().'index.php";
        		var terms = "'.$this->objSysConfig->getValue('trackterms', 'twitterizer').'"
        	</script>');

$ext =$this->getJavaScriptFile('SearchField.js', 'twitterizer');
$ext .=$this->getJavaScriptFile('west.js', 'twitterizer');
$ext .=$this->getJavaScriptFile('middle.js', 'twitterizer');
$ext .=$this->getJavaScriptFile('interface.js', 'twitterizer');
$this->appendArrayVar('headerParams', $ext);
$ext .= '
<style type="text/css">
.search-item {
    font:normal 13px tahoma, arial, helvetica, sans-serif;
    padding:3px 10px 3px 10px;
    border:1px solid #fff;
    border-bottom:1px solid #eeeeee;
    white-space:normal;
    color:#555;
    height:60px;
}
.search-item h3 {
    display:block;
    position:relative;
    font:inherit;
    font-weight:bold;
    color:#222;
}

.search-item img {
    float: left;
    font-weight:normal;
    margin:0 7px 0 3px;    
    display:block;
    
}

.search-item a span{
	
}

.search-item span {
    float: right;
    font-weight:normal;
    margin:0 0 5px 5px;
    width:100px;
    display:block;
    clear:none;
}

        #search-results a {
            color: #385F95;
            font:bold 11px tahoma, arial, helvetica, sans-serif;
            text-decoration:none;
        }
        #search-results a:hover {
            text-decoration:underline;
        }
        #search-results .search-item {
            padding:5px;
        }
        #search-results p {
            margin:3px !important;
        }
        #search-results {
            border-bottom:1px solid #ddd;
            margin: 0 1px;
            height:300px;
            overflow:auto;
        }
        #search-results .x-toolbar {
            border:0 none;
        }
    </style>';
$this->appendArrayVar('headerParams', $ext);
?>


<div id="west" class="x-hide-display">
        <p>Hi. I'm the west panel.</p>
    </div>
   
    <div id="props-panel" class="x-hide-display" style="width:200px;height:200px;overflow:hidden;">

    </div>
    <div id="south" class="x-hide-display">
        <p>Powered By Chisimba</p>
    </div>
    
    
</div>
