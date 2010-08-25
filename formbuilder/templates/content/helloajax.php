

<html>
<body>
    <div id="teaser">
<form id="teser">
    <label>Enter your Name</label>
    <input type="text" name="uname" class="uname"/>
    <input type="submit" id="submit"/>
</form>
    <div id ="testing">
            <?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$this->appendArrayVar('headerParams', $this->getJavascriptFile('1.4.2/jquery-1.4.2.min.js', 'jquery'));

  $this->objDBComments = $this->getObject('dbhelloforms_comments','helloforms');
  $selcolor='this hte clecoler variable';

      $this->loadClass('radio','htmlelements');
      $objElement = new radio('sex_radio');
                $objElement->addOption('m','Male');
                          //      $objElement->addOption('m','Male');
                 $objElement->setBreakSpace(null);
                $objElement->addOption('f','Female');
                $objElement->addOption('n','Seaweed');
                $objElement->setSelected('f');


  
// $title = $this->getParam('title');
  //  $comments = $this->getParam('commenttxt');
    //Insert the data to DB
 //   $id = $this->objDBComments->insertSingle($name,$comments);
  

?>
    </div>
        <div id =" radio">
            <?php

           ?>
        </div>
        </div>
  <div id="message">

  </div>

</body>
</html>


<script type="text/javascript">
//$(document).ready(function() {
//    //$.mask.definitions['#']='[1234]';
//    jQuery(function($){
//  // $('.uname').mask("999-99999");
//});
//  $('#submit').click(function () {
//var name = $('.uname').val();
//   // var htmldata = $(this).html();
//    var data = 'uname=' + name;
//    var datas="commentsfile 1";
//    var selcolor = '<% $selcolor %>';
//  //  var url="<?php echo $_SERVER[PHP_SELF];?>?module=formbuilder&action=helloajax";
//    var url = <?php $this->uri(array("action" => "helloajax"), "formbuilder" );?>
//    //var url = '/hello.php';
// $("#message").load(url, {"uname":name,"comments":selcolor});
// });
////    $.ajax({
////      type:"POST",
////      url: url,
////      data: data,
////      success: function (html) {
////              var name = $('#teaser').html();
////        $('#message').html(html);
////      }
////    });
//
//    return false;
//  });

$(document).ready(function() {
  $('#submit').click(function () {
    var name = $('.uname').val();
    var data = 'uname=' + name;
    var myurl="<?php echo $_SERVER[PHP_SELF];?>?module=formbuilder&action=helloajaxnew";
   // var myurl = <?php// $this->uri(array("action" => "helloajax"), "formbuilder" );?>;
    $.ajax({
      type:"POST",
      url:myurl,
      data: data,
      success: function (html) {
        $('#message').html(html);
      }
    });
    return false;
  });
});


    </script>