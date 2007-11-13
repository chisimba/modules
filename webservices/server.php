<?php
include 'SCA/SCA.php';

/**
 * @service
 * @binding.soap
 * @binding.jsonrpc
 * @binding.xmlrpc
 * @binding.restrpc
 * 
 */
 
 class server
 {
     /**
      * Method to say hello
      *
      * @param string $name
      * @return string
      */
      public function hello($name)
      {
          return 'hello '.$name;
      }
      
      /**
       * Method to grab all blog posts by userid
       * 
       * @param string $userid http://server
       * @return array $posts http://server
       */
      public function getBlogPosts($userid)
      {
      	//$posts = SCA::createDataObject('http://server','posts');
      	//$pair = $posts->createDataObject('entry');
        //$pair->id = 'gensrvname123';
        //$pair->content = 'whatever dude!';
        
      	$posts = array('blogs', 'posts', 'whatever');
      	return $posts;
      }
}
?>