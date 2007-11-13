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
      
      // any PHP scalar will work here, boolean, string, integer, float, NULL etc
      // arrays and complextypes need an XSD doc.
      
      // maybe we need to curl params into the framework to use methods?
      // maybe a native class? 
      
      // ugh!
}
?>