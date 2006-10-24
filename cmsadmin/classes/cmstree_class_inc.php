<?php

/* -------------------- cmstree class ----------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check
/**
* This object is a wrapper class for building a tree using the cms sections
* @package cms
* @category cmsadmin
* @copyright 2004, University of the Western Cape & AVOIR Project
* @license GNU GPL
* @version
* @author Serge Meunier
* @example :
*/

class cmstree extends object
{

	/**
     * The sections  object
     *
     * @access private
     * @var object
    */
    protected $_objSections;

     /**
     * The Content object
     *
     * @access private
     * @var object
    */
    protected $_objContent;

     /**
     * The User object
     *
     * @access private
     * @var object
    */
    protected $_objUser;

	/**
	 * Constructor
	 */
	public function init()
	{
		try {
			$this->_objSections = & $this->newObject('dbsections', 'cmsadmin');
			$this->_objContent = & $this->newObject('dbcontent', 'cmsadmin');
			$this->_objUser = & $this->newObject('user', 'security');
            $this->objLanguage =& $this->newObject('language', 'language');

		}catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
        }

	}

 	/**
	 * Method to return back the tree code
	 * @param string $currentNode The currently selected node, which should remain open
     * @param bool $admin Select whether admin user or not
	 * @return string
	 * @access public
	 */
    public function show($currentNode = null, $admin = FALSE)
    {
        $this->appendArrayVar('headerParams','<link rel="stylesheet" type="text/css" media="all" href="tree.css" />');
        $this->appendArrayVar('headerParams', $this->getJavascriptFile('tree.js', 'financialaid'));

        $html = $this->buildTree($currentNode, $admin);
        return $html;
    }

	/**
	 * Method to build the tree
	 * @param string $currentNode The currently selected node, which should remain open
     * @param bool $admin Select whether admin user or not
	 * @return string
	 * @access public
	 */
    public function buildTree($currentNode = null, $admin = FALSE)
    {
        //check if there are any root nodes
        if ($this->getChildNodeCount('0') > 0){
            $html = '<ul class="tree">';
            //start the tree building
            $html .= $this->buildLevel('0', $currentNode, $admin);
            $html .= '</ul>';
        }else{
            $html = '';
        }
        return $html;
    }

	/**
	 * Method to build the next level in tree
     * @param string $parentid The node id whose child nodes need to be built
	 * @param string $currentNode The currently selected node, which should remain open
     * @param bool $admin Select whether admin user or not
	 * @return string
	 * @access public
	 */
    public function buildLevel($parentId, $currentNode, $admin)
    {
        //gets all the child nodes of id
        $nodes = $this->getChildNodes($parentId);

        //get the list of nodes that need to stay open for the currently selected node
        $openNodes = $this->getOpenNodes($currentNode);

        if (count($nodes)){

            $htmlLevel = '';
            foreach($nodes as $node){
                if ($this->getChildNodeCount($node['id']) > 0){
                    //if node has further child nodes, recursively call buildLevel
                    $htmlChildren = $this->buildLevel($node['id'], $currentNode, $admin);

                    //get any content for a section
                    if($this->getNodeContentCount($node['id']) > 0){
                        $htmlChildren .= $this->addContent($node['id']);
                    }

                    //if no content or child nodes with content, then suppress the node, else build it
                    if(($htmlChildren == '') && ($this->getNodeContentCount($node['id']) == 0) && ($admin == FALSE)){
                        $htmlLevel = '';
                    }else{
                        if(in_array($node['id'], $openNodes)){
                            $htmlLevel .= '<li><a href="#">'.$node['title'].'</a><ul>';
                        }else{
                            $htmlLevel .= '<li class="closed"><a href="#">'.$node['title'].'</a><ul>';
                        }
                        $htmlLevel .= $htmlChildren;
                        $htmlLevel .= '</ul></li>';
                    }
                }else{
                    //if node has no child nodes, then just get content nodes
                    if($this->getNodeContentCount($node['id']) > 0){
                        $htmlLevel .= '<li><a href="#">'.$node['title'].'</a><ul>';
                        $htmlLevel .= $this->addContent($node['id']);
                        $htmlLevel .= '</ul></li>';
                    }elseif($admin == TRUE){
                        if(in_array($node['id'], $openNodes)){
                            $htmlLevel .= '<li><a href="#">'.$node['title'].'</a><ul><li></li>';
                        }else{
                            $htmlLevel .= '<li class="closed"><a href="#">'.$node['title'].'</a><ul><li></li>';
                        }
                        $htmlLevel .= '</ul></li>';
                    }
                    //nodes with no are content suppressed, if not admin
                }
            }
            return $htmlLevel;
        }else{
            //if no nodes return empty string
            return '';
        }
    }

	/**
	 * Method to get a list of all nodes to remain open
	 * @param string $currentNode The currently selected node
	 * @return array
	 * @access public
	 */
    public function getOpenNodes($currentNode)
    {
        $nodeId = $currentNode;

        $openNodes = array();

        $openNodes[0] = $currentNode;
        $i = 1;
        while($nodeId != '0'){
            $node = $this->getNode($nodeId);
            if (count($node)){
                $nodeId = $node[0]['parentid'];
                $openNodes[$i] = $nodeId;
                $i++;
            }else{
                $nodeId = '0';
            }
        }
        return $openNodes;
    }

	/**
	 * Method to get add all content for a particular section node
	 * @param string $id The id of the section node
	 * @return string
	 * @access public
	 */
    public function addContent($id){
        $contentNodes = $this->getContent($id);

        if (count($contentNodes)){
            $htmlContent = '';
            foreach($contentNodes as $contentNode){
                $htmlContent .= '<li><a href="#">'.$contentNode['title'].'</a></li>';
            }
            return $htmlContent;
        }else{
            return '';
        }
    }

	/**
	 * Method to get all child nodes for a particular node
	 * @param string $parentId The parent node id
	 * @return array
	 * @access public
	 */
    public function getChildNodes($parentId)
    {
        $user='root';
        $password='';
        $server='127.0.0.1';
        $database='chisimba';

        $sql = "SELECT * FROM tbl_cms_sections WHERE parentid='".$parentId."' ORDER BY title";

        $conn = mysql_connect($server, $user, $password);
        if (!$conn) {
            die('Could not connect: ' . mysql_error());
        }
        mysql_select_db($database, $conn);
        $returnresult = mysql_query($sql, $conn);
        $result = array();
        $i = 0;
        while ($row = mysql_fetch_assoc($returnresult)){
            $result[$i] = $row;
            $i++;
        }

         mysql_close($conn);

         return $result;
    }

	/**
	 * Method to get node for a particular id
	 * @param string $id The node id
	 * @return array
	 * @access public
	 */
    public function getNode($id)
    {
        $user='root';
        $password='';
        $server='127.0.0.1';
        $database='chisimba';

        $sql = "SELECT * FROM tbl_cms_sections WHERE id='".$id."' ORDER BY title";

        $conn = mysql_connect($server, $user, $password);
        if (!$conn) {
            die('Could not connect: ' . mysql_error());
        }
        mysql_select_db($database, $conn);
        $returnresult = mysql_query($sql, $conn);
        $result = array();
        $i = 0;
        while ($row = mysql_fetch_assoc($returnresult)){
            $result[$i] = $row;
            $i++;
        }

         mysql_close($conn);

         return $result;
    }

	/**
	 * Method to get all content nodes for a particular section node
	 * @param string $sectionId The section id
	 * @return array
	 * @access public
	 */
    public function getContent($sectionId)
    {
        $user='root';
        $password='';
        $server='127.0.0.1';
        $database='chisimba';

        $sql = "SELECT * FROM tbl_cms_content WHERE sectionid='".$sectionId."' ORDER BY title";

        $conn = mysql_connect($server, $user, $password);
        if (!$conn) {
            die('Could not connect: ' . mysql_error());
        }
        mysql_select_db($database, $conn);
        $returnresult = mysql_query($sql, $conn);
        $result = array();
        $i = 0;
        while ($row = mysql_fetch_assoc($returnresult)){
            $result[$i] = $row;
            $i++;
        }

         mysql_close($conn);

         return $result;
    }

	/**
	 * Method to get number of child nodes for a particular node
	 * @param string $parentId The parent node id
	 * @return int
	 * @access public
	 */
    public function getChildNodeCount($parentId)
    {
        $user='root';
        $password='';
        $server='127.0.0.1';
        $database='chisimba';

        $sql = "SELECT COUNT(*) AS cnt FROM tbl_cms_sections WHERE parentid='".$parentId."'";

        $conn = mysql_connect($server, $user, $password);
        if (!$conn) {
            die('Could not connect: ' . mysql_error());
        }
        mysql_select_db($database, $conn);
        $returnresult = mysql_query($sql, $conn);
        $result = array();
        $i = 0;
        while ($row = mysql_fetch_assoc($returnresult)){
            $result[$i] = $row;
            $i++;
        }

         mysql_close($conn);

         return $result[0]['cnt'];
    }

	/**
	 * Method to get the number of content nodes for a section id
	 * @param string $id The section id
	 * @return int
	 * @access public
	 */
    public function getNodeContentCount($id)
    {
        $user='root';
        $password='';
        $server='127.0.0.1';
        $database='chisimba';

        $sql = "SELECT COUNT(*) AS cnt FROM tbl_cms_content WHERE sectionid='".$id."'";

        $conn = mysql_connect($server, $user, $password);
        if (!$conn) {
            die('Could not connect: ' . mysql_error());
        }
        mysql_select_db($database, $conn);
        $returnresult = mysql_query($sql, $conn);
        $result = array();
        $i = 0;
        while ($row = mysql_fetch_assoc($returnresult)){
            $result[$i] = $row;
            $i++;
        }

        mysql_close($conn);

        return $result[0]['cnt'];
    }

}
?>
