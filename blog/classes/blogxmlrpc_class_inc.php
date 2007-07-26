<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

class blogxmlrpc extends object
{
	public $objDbBlog;
	
	public function init()
	{
		require_once($this->getPearResource('XML/RPC/Server.php'));
		require_once($this->getPearResource('XML/RPC/Dump.php'));
		$this->objConfig = $this->getObject('altconfig', 'config');
		$this->objLanguage = $this->getObject('language', 'language');
		//database abstraction object
        $this->objDbBlog = $this->getObject('dbblog');
        $this->objUser = $this->getObject('user', 'security');
	}
	
	public function serve()
	{
		// map web services to methods
		$server = new XML_RPC_Server(
   					array('blogger.newPost' => array('function' => array($this, 'metaWeblogNewPost'),
   											      'signature' => array(
                         											array('string', 'string', 'string', 'string','string', 'string', 'boolean'),
                     											 ),
                								  'docstring' => 'new post'),
                		  
                		  'blogger.editPost' => array('function' => array($this, 'metaWeblogEditPost'),
   											      'signature' => array(
                         											array('boolean', 'string', 'string', 'string', 'string', 'string', 'boolean'),
                     											 ),
                								  'docstring' => 'edit post'),
                		  
                		  'blogger.getPost' => array('function' => array($this, 'metaWeblogGetPost'),
   											      'signature' => array(
                         											array('struct', 'string', 'string', 'string'),
                     											 ),
                								  'docstring' => 'get post'),
                		  
                		  'blogger.getRecentPosts' => array('function' => array($this, 'metaWeblogGetRecentPosts'),
                		   						   'signature' => array(
                		   						   					array('array', 'string', 'string', 'string', 'string', 'int'),
                		   						   					),
                		   						   'docstring' => 'get recent posts'),
                		  
                		  'blogger.getCategories' => array('function' => array($this, 'metaWeblogGetCategories'),
                		   						   'signature' => array(
                		   						   					array('array', 'string', 'string', 'string', 'string'),
                		   						   					),
                		   						   'docstring' => 'get categories'),
                		  
                		  'blogger.getUsersBlogs' => array('function' => array($this, 'metaWeblogGetUsersBlogs'),
                		   						   'signature' => array(
                		   						   					array('array', 'string', 'string', 'string'),
                		   						   					),
                		   						   'docstring' => 'get user blogs'),
                		   						   
   					), 1, 0);
   					

		return $server;
	}
	
	public function metaWeblogNewPost($params)
	{
		$param = $params->getParam(0);
		if (!XML_RPC_Value::isValue($param)) {
            log_debug($param);
    	}
    	$appkey = $param->scalarval();
    	
    	$param = $params->getParam(1);
		if (!XML_RPC_Value::isValue($param)) {
            log_debug($param);
    	}
    	$blogid = $param->scalarval();
    	
    	$param = $params->getParam(2);
		if (!XML_RPC_Value::isValue($param)) {
            log_debug($param);
    	}
    	$username = $param->scalarval();
    	
    	$param = $params->getParam(3);
		if (!XML_RPC_Value::isValue($param)) {
            log_debug($param);
    	}
    	$pass = $param->scalarval();
    	
    	$param = $params->getParam(4);
		if (!XML_RPC_Value::isValue($param)) {
            log_debug($param);
    	}
    	$content = $param->scalarval();
    	
    	$param = $params->getParam(5);
		if (!XML_RPC_Value::isValue($param)) {
            log_debug($param);
    	}
    	$publish = $param->scalarval();
    	if($publish)
    	{
    		$published = 0;
    	}
    	else {
    		$published = 1;
    	}
    	
    	$userid = $this->objUser->getUserId($username);
    	
    	//insert to the db now and return the generated id as a string
    	$postarray = array(
                'userid' => $userid,
                'post_date' => date('r') ,
                'post_content' => addslashes($content) , 
                'post_title' => $this->objLanguage->languageText("mod_blog_word_apipost", "blog") ,
                'post_category' => '0',
                'post_excerpt' => '',
                'post_status' => $published,
                'comment_status' => 'on',
                'post_modified' => date('r'),
                'comment_count' => '0',
                'post_ts' => time() ,
                'post_lic' => '',
                'stickypost' => '0',
                'showpdf' => '1'
            );
    	$ret = $this->objDbBlog->insertPostAPI($userid, $postarray);
		$val = new XML_RPC_Value($ret, 'string');
		return new XML_RPC_Response($val);
	}
	
	public function metaWeblogEditPost()
	{
		$val = new XML_RPC_Value(TRUE, 'boolean');
   		return new XML_RPC_Response($val);
	}
	
	public function metaWeblogGetPost()
	{
		$myStruct = new XML_RPC_Value(array(
    		"title" => new XML_RPC_Value("Tom"),
    		"link" => new XML_RPC_Value("http://www.google.com", "string"),
    		"description" => new XML_RPC_Value("a blog test entry", "base64")), "struct");
    	return new XML_RPC_Response($myStruct);
	}
	
	public function metaWeblogGetRecentPosts($params)
	{
		$param = $params->getParam(0);
		if (!XML_RPC_Value::isValue($param)) {
            log_debug($param);
    	}
    	$appkey = $param->scalarval();
    	
    	$param = $params->getParam(1);
		if (!XML_RPC_Value::isValue($param)) {
            log_debug($param);
    	}
    	$blogid = $param->scalarval();
    	
    	$param = $params->getParam(2);
		if (!XML_RPC_Value::isValue($param)) {
            log_debug($param);
    	}
    	$username = $param->scalarval();
    	
    	$param = $params->getParam(3);
		if (!XML_RPC_Value::isValue($param)) {
            log_debug($param);
    	}
    	$pass = $param->scalarval();
    	
    	$param = $params->getParam(4);
		if (!XML_RPC_Value::isValue($param)) {
            log_debug($param);
    	}
    	$noPosts = $param->scalarval();
    	$userid = $this->objUser->getUserId($username);
    
		$recentposts = $this->objDbBlog->getLastPosts($noPosts, $userid);
		foreach($recentposts as $recentpost)
		{
			$myStruct = new XML_RPC_Value(array(
    			"content" => new XML_RPC_Value($recentpost['post_content']),
    			"userId" => new XML_RPC_Value($recentpost['userid'], "string"),
    			"postId" => new XML_RPC_Value($recentpost['id'], "string"),
    			"dateCreated" => new XML_RPC_Value($recentpost['post_date'], "string")), "struct");
    	
    		$arrofStructs[] = $myStruct;
		}
    	$ret = new XML_RPC_Value($arrofStructs, "array");
    	return new XML_RPC_Response($ret);
	}

	public function metaWeblogGetCategories()
	{
		$val = new XML_RPC_Value('a returned string', 'string');
		return new XML_RPC_Response($val);
	}
	
	public function metaWeblogGetUsersBlogs($params)
	{
		$param = $params->getParam(0);
		if (!XML_RPC_Value::isValue($param)) {
            log_debug($param);
    	}
    	$appkey = $param->scalarval();
    	
    	$param = $params->getParam(1);
		if (!XML_RPC_Value::isValue($param)) {
            log_debug($param);
    	}
    	$username = $param->scalarval();
    	
    	$param = $params->getParam(2);
		if (!XML_RPC_Value::isValue($param)) {
            log_debug($param);
    	}
    	$pass = $param->scalarval();
    	
		$userid = $this->objUser->getUserId($username);
		$prf = $this->objDbBlog->checkProfile($userid);
		$prf = $prf['blog_name']; 
		if(!$prf)
		{
			$prf = htmlentities($this->objUser->fullname($userid));
		}
		else {
			$prf = htmlentities($prf);
		}
		$url = $this->uri(array('action' => 'randblog', 'userid' => $userid), 'blog');
		$myStruct = new XML_RPC_Value(array(
    		"blogid" => new XML_RPC_Value($userid, 'string'),
    		"blogName" => new XML_RPC_Value($prf, "string"),
    		"url" => new XML_RPC_Value($url, "string")), "struct");
    	
    	$arrofStructs = new XML_RPC_Value(array($myStruct), "array");
    	return new XML_RPC_Response($arrofStructs);
	}
	
}
?>