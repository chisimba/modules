<?php
/* ----------- readxml_Scorm class extends object------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}
/**
* Model class for reading imsmanifest to create navigation
* @author Paul Mungai
* @copyright 2008 University of the Western Cape
*/

class readxml_scorm extends object
{
    /**
    * @var treeMenu an object reference.
    */
    var $_objTreeMenu;
    /**
    * @var array extra tree options for display.
    */
    var $_extra;
    /**
    * @var string Group Id of root node.
    */
    var $_rootNode;
    
    /**
    * @var string Location of tree icons.
    */
    var $_treeIcons='';
    /**
    * @var array Icons for the tree.
    */
    var $_arrTreeIcons=array();
    
    /**
    * @var string Module Link for Trees
    */
    var $treeTargetModule = 'scorm';
    
    /**
    * @var string Action Link for Trees
    */
    var $treeTargetAction = 'main';
    
    /**
    * @var string Additional Id Parameter for Trees
    */
    var $treeTargetId = NULL;
    
    /**
    * @var string Target Window for Trees
    */
    var $treeTargetWindow = NULL;

/**
*
* Intialiser for the readxml_Scorm controller
* @access public
*
*/
public function init()
{
	// Get the DB object.
	$this->objConfig =& $this->newObject('altconfig','config');
	$this->objUser =& $this->getObject('user', 'security');
 	$this->loadClass('treemenu', 'tree');
 	$this->loadClass('treenode', 'tree');
        $this->loadClass('dhtml','tree');
	$this->loadClass('hiddeninput', 'htmlelements');
        // Initialise icons
        $this->objSkin = $this->getObject( 'skin', 'skin' );
        $this->_treeIcons = 'icons/tree/';

        $this->_arrTreeIcons = array();
        $this->_arrTreeIcons['root'] = 'treebase';
        $this->_arrTreeIcons['empty']['selected'] = 'treefolder-selected_white';
        $this->_arrTreeIcons['empty']['open'] = 'treefolder-expanded_white';
        $this->_arrTreeIcons['empty']['closed'] = 'treefolder_white';

}
  
/**
* 
*Method to create the course navigations
*@param string $rootFolder
*@param string $courseFolder
* @return array
*/
public function readManifest($rootFolder, $courseFolder=Null){

	$doc = new DOMDocument();
	$doc->load( 'usrfiles/'.$rootFolder.'/imsmanifest.xml' );

	$books = $doc->getElementsByTagName( "item" );
	$allbooks = $doc->getElementsByTagName( "organization" );
	$resources = $doc->getElementsByTagName( "resource" );
	$arrIdentifier = array();
	$nodeArrId = array();
	$arrId = 0;
	$nArr = 0;
	$nodesItem = $books->item(0);
//	$content = $this->getContent($nodesItem,$resources,$rootFolder);
	$content = $this->xmlMicroxTree($nodesItem,$resources,$rootFolder,$ident="");
/*
$theNextArray = $this->xmlNextPage($pagePath=null,$rootFolder);
$thePrevArray = $this->xmlPrevPage($pagePath=null,$rootFolder);
var_dump($theNextArray);
var_dump($thePrevArray);
*/
	//another method
	//print_r($this->xml2array($books));
	foreach( $books as $book )
	{
		//if($book->hasChildNodes()==true)
		$nodes = $book->getElementsByTagName( "item" );
/***********************/
//		$nodesItem = $nodes->item(0);
		//$NodList=$nodesItem->childNodes;
		//$NodLength = $NodList->length;
		//if($NodLength>0){
//		$content = $this->getContent($Content,$nodesItem);
		//}
		//echo $Content;
//		$NodList=$nodesItem->childNodes;
//		$NodLength = $NodList->length;
//		var_dump($NodLength);
/*********************/
		if($nodes->length!==0)
		{
			
			foreach($nodes as $nodeArr)
			{
				$nodeArrRef = $nodeArr->getAttribute('identifier');
				$nodeArrIdRef = $nodeArr->getAttribute('identifierref');	
			 	//$nodeArrRef = $nodeArr->getElementsByTagName( "title" );
			  	//$resnameArr = $nodeArrRef->item(0)->nodeValue;
				$myCheck = in_array($nodeArrRef, $nodeArrId);
				if($myCheck==FALSE){
				$nodeArrId[$nArr] = $nodeArrRef;
				}
			//var_dump($myCheck);
				$nArr = $nArr + 1;
			}			
			//var_dump($nodeArrRef);
			

		}
		$identifier = $book->getAttribute('identifier'); 
		$arrIdentifier[$arrId] = "'".$identifier."'";
		//$arrIdentifier['id'] = $arrIdentifier['id']."'".$arrId."'";
		//$arrIdentifier['name'] = $arrIdentifier['name']."'".$identifier."'";
		if($nodes->length!==0)
		{
		 	$res = $book->getElementsByTagName( "title" );
		  	$resname = $res->item(0)->nodeValue;
			/* get attribute */

			$cid = $book->getAttribute('identifierref'); 
			//an array to hold the tag id
			//$identifier = $book->getAttribute('identifier'); 
			//$arrIdentifier[$arrId] = "'".$identifier."'";
			foreach ( $resources as $myresources ){
				if( $myresources->getAttribute('identifier') ==  $cid ){
					$resourcePath = $myresources->getAttribute('href');
					//An Iframe with name content is the link target
					$fullPath = '<a href = "usrfiles/'.$rootFolder.'/'.$resourcePath.'" target = "content">'.$resname.'</a>';
				}
			//}			
			}
			$navigation = $navigation."<div>$fullPath</div>";
		} else {
			//$rootNode = $book->getElementsByTagName( "item" );
		//Display root node
		 	$res = $book->getElementsByTagName( "title" );
		  	$resname = $res->item(0)->nodeValue;
			/* get attribute */
			$cid = $book->getAttribute('identifierref'); 
			//an array to hold the tag id
			//$identifier = $book->getAttribute('identifier'); 
			//$arrIdentifier[$arrId] = "'".$identifier."'";
			foreach ( $resources as $myresources ){
				if( $myresources->getAttribute('identifier') ==  $cid ){
					$resourcePath = $myresources->getAttribute('href');
					//An Iframe with name content is the link target
					$fullPath = '&nbsp;&nbsp;&nbsp;<a href = "usrfiles/'.$rootFolder.'/'.$resourcePath.'" target = "content">'.$resname.'</a>';
				}
			}
			$navigation = $navigation."<div>$fullPath</div>";
		//end Display root node
		//$arrId = $arrId + 1;
		}
		$arrId = $arrId + 1;
	//$navigation = $navigation."<p>$fullPath</p>";
	}
	$parentNode = Null;
//echo $content;
//echo "<br />\n";
//var_dump($nodeArrId);
	//$staticMenu = $this->getStaticTree($arrIdentifier);

	$xmlString = "<treemenu>";

	foreach ($arrIdentifier as $Identifier) {
		$xmlString = $xmlString.'<node text="'.$Identifier.'" icon="folder.gif" expandedIcon="folder-expanded.gif" />';
		$element = $doc->getElementById('$Identifier');
		$roughhWork = $roughhWork."$Identifier \n";
	}
	$xmlString = $xmlString."</treemenu>";
        //$this->_objTreeMenu =& new treemenu();
	//$xmlMenu = $this->_objTreeMenu->createFromXML($xmlString);
	//return $navigation.'<br>'.$staticMenu;
	$hiddenInput = new hiddeninput('input_rootFolder', $rootFolder);
	return $content."<br /><input type='hidden' name='input_rootfolder' id='input_rootfolder' value='".$rootFolder."' />";
//	return $content."<br /><span id='rootFolder'>".$rootFolder."</span>";
}

function getContent($nod,$resources,$rootFolder)
{ 
	$NodList=$nod->childNodes;
    for( $j=0 ;  $j < $NodList->length; $j++ )
    {       $nod2=$NodList->item($j);//Node j
	if($nod2->childNodes->length>0){
		$this->getContent($nod2,$resources,$rootFolder);
	}	
        $nodemane=$nod2->nodeName;
        $nodevalue=$nod2->nodeValue;
        $nodeAttr=$nod2->attribute;
var_dump($nodeAttr);
	$NodeContent .=  $nodevalue.$nodeAttr."<br>";

	$cid = $nod->getAttribute('identifierref'); 
	//an array to hold the tag id
	//$identifier = $book->getAttribute('identifier'); 
	//$arrIdentifier[$arrId] = "'".$identifier."'";
	foreach ( $resources as $myresources ){
		if( $myresources->getAttribute('identifier') ==  $cid ){
			$resourcePath = $myresources->getAttribute('href');
			//An Iframe with name content is the link target
			$fullPath = '<a href = "usrfiles/'.$rootFolder.'/'.$resourcePath.'" target = "content">'.$nodevalue.'</a>';
		}
    	}
	$navigation = $navigation."<div>$fullPath</div>";
    }
   return $navigation;
}
function xmlMicroxTree($nod,$resources,$rootFolder,$ident)
{ 
	$NodList=$nod->childNodes;	
	$treeTxt = "";//var to content temp
    for( $j=0 ;  $j < $NodList->length; $j++ )
    { 	//each child node
      $nod2=$NodList->item($j);//Node j
	//no white spaces
	if($nod2->nodeType==1){
		if($nod2->nodeName=="title"){
		$treeTxt = "<br />\n".$treeTxt.$ident;
		}
		if($nod2->childNodes->length==0){//no children.Get nodeValue
			$treeTxt = $treeTxt.$nod2->nodeValue;
			$atribId = $nod2->parentNode->getAttribute('identifierref');
			foreach ( $resources as $myresources ){
				if( $myresources->getAttribute('identifier') ==  $atribId){
					$resourcePath = $myresources->getAttribute('href');
					//An Iframe with name content is the link target
					$treeTxt = '<a href = "usrfiles/'.$rootFolder.'/'.$resourcePath.'" target = "content">'.$treeTxt.'</a>';
				}
			}			
			$treeTxt = $treeTxt."<br />\n";
		}else if ($nod2->childNodes->length>0){//children.Get first child
			//$treeTxt = $treeTxt.$nod2->firstChild->nodeValue;
			$atribId = $nod2->parentNode->getAttribute('identifierref');
			foreach ( $resources as $myresources ){
				if( $myresources->getAttribute('identifier') ==  $atribId){
					$resourcePath = $myresources->getAttribute('href');
					//An Iframe with name content is the link target
					$treeTxt = $treeTxt.'<a href = "usrfiles/'.$rootFolder.'/'.$resourcePath.'" target = "content">'.$nod2->firstChild->nodeValue.'</a>';
				}
			}			
		//recursive to child of children
		$treeTxt = $treeTxt.$this->xmlMicroxTree($nod2,$resources,$rootFolder,$ident."&nbsp;&nbsp;");

		}
	}
     }
     return $treeTxt;
}
function xmlNextPage($pagePath,$rootFolder)
{ 
//$pagePath = 'http://10.2.31.176/chisimba_frameworks/app/usrfiles/users/4733080702/dba203/l1introduction_to_organization_theory.html';
	$doc = new DOMDocument();
	$doc->load( 'usrfiles/'.$rootFolder.'/imsmanifest.xml' );
	//get the kewl_root_path
	$rootPath = $this->objConfig->getsiteRoot();
	$books = $doc->getElementsByTagName( "item" );
	$resources = $doc->getElementsByTagName( "resource" );
	$nod = $books->item(0);
	$NodList=$nod->childNodes;	
	$treeTxt = "";//var to content temp
	if($arrId==null){
		$arrId = 0;
	}
	foreach( $books as $book )
	{
		$nodes = $book->getElementsByTagName( "item" );
		//Display root node
	 	$res = $book->getElementsByTagName( "title" );
	  	$resname = $res->item(0)->nodeValue;
		/* get attribute */
		$cid = $book->getAttribute('identifierref'); 
		foreach ( $resources as $myresources ){
			if( $myresources->getAttribute('identifier') ==  $cid ){
				$resourcePath = $myresources->getAttribute('href');
				$fullPath = $rootPath.'usrfiles/'.$rootFolder.'/'.$resourcePath;
				$arrPath[$arrId] = $fullPath;
				$arrId = $arrId + 1;
			}
		}
	}
	//step through the array and get next page
	foreach($arrPath as $key=>$myarrPath){
		if ($myarrPath==$pagePath){
			$nextPage = $arrPath[$key+1];
		}
	}
     return $nextPage;
}
function xmlPrevPage($pagePath,$rootFolder)
{ 
//http://10.2.31.176/chisimba_frameworks/app/usrfiles/users/4733080702/dba203/l1introduction_to_organization_theory.html';
	$doc = new DOMDocument();
	$doc->load( 'usrfiles/'.$rootFolder.'/imsmanifest.xml' );
	//get the kewl_root_path
	$rootPath = $this->objConfig->getsiteRoot();
	$books = $doc->getElementsByTagName( "item" );
	$resources = $doc->getElementsByTagName( "resource" );
	$nod = $books->item(0);
	$NodList=$nod->childNodes;	
	$treeTxt = "";//var to content temp
	if($arrId==null){
		$arrId = 0;
	}
	foreach( $books as $book )
	{
		$nodes = $book->getElementsByTagName( "item" );
		//Display root node
	 	$res = $book->getElementsByTagName( "title" );
	  	$resname = $res->item(0)->nodeValue;
		/* get attribute */
		$cid = $book->getAttribute('identifierref'); 
		foreach ( $resources as $myresources ){
			if( $myresources->getAttribute('identifier') ==  $cid ){
				$resourcePath = $myresources->getAttribute('href');
				$fullPath = $rootPath.'usrfiles/'.$rootFolder.'/'.$resourcePath;
				$arrPath[$arrId] = $fullPath;
				$arrId = $arrId + 1;
			}
		}
	}
	//step through the array and get next page
	foreach($arrPath as $key=>$myarrPath){
		if ($myarrPath==$pagePath){
			$nextPage = $arrPath[$key-1];
		}
	}
     return $nextPage;
}

    /**
    * Method to generate a tree that 
    * will to used in the index.php file of 
    * the static content
    */
    function getStaticTree($nodesArr)
    {
        $menu  = new treemenu();
        $basenode = new treenode(array('text' => 'Static Cotent', 'link' => '', 'icon' => 'base.gif', 'expandedIcon' => 'base.gif'));    
	$myid = 0;
        foreach($nodesArr as $node)
        {            
            if($node[$myid] !== null)
            {            
                $basenode->addItem($this->getStaticChildNodes($nodesArr,$node[$myid],$node[$myid]));
            }
	    $myid = $myid + 1;
        }
        $menu->addItem($basenode);
        //$menu->addItem($this->recurTree($rootnodeid,$rootlabel));

        // Create the presentation class
        $treeMenu = &new dhtml($menu, array('images' => 'treeimages', 'defaultClass' => 'treeMenuDefault'));
      
        return $treeMenu->getMenu();
     
    }  
    
    /*
    *Method to create a child node for the tree
    * @access Private
    * @param array $nodesArr : The list of nodes for a course
    * @param string $parentId : The Id of the parent Node
    * @param string $title : The Title of the node
    * @return string $basenode : A tree node 
    */
    function getStaticChildNodes($nodeArr,$parentId,$title){
        //setup the link      
        $link = $parentId.'.html';
        //create a new tree node
        $basenode = new treenode(array('text' => $title, 'link' => $link, 'icon' => NULL, 'expandedIcon' => NULL ));        
        $myid = 0;
        //search for more children
        foreach ($nodeArr as $line) 
        {   
            if($line[$myid]==$parentId){
                $basenode->addItem($this->getStaticChildNodes($nodeArr,$line[$myid],$line[$myId]));
            }
	    $myid = $myid + 1;
        }
        return $basenode;
    }


function xml2array($n)
{
    $return=array();
    foreach($n->childNodes as $nc){
    ($nc->hasChildNodes())
    ?($n->firstChild->nodeName== $n->lastChild->nodeName&&$n->childNodes->length>1)
    ?$return[$nc->nodeName][]=$this->xml2array($item)
    :$return[$nc->nodeName]=$this->xml2array($nc)
    :$return=$nc->nodeValue;
    }
    return $return;
}
}
?>
