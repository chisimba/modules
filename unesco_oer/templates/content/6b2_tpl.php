<?php


//var_dump($productid);

$selectedproducts = explode(',', $productid);

//var_dump($selectedproducts);
 //echo $moduleid;

//$product = $this->getObject('product', 'unesco_oer');
//
//$product->loadProduct($productid);
//
//if ($product->getParentID() != NUll){
//    $productid = $product->getParentID();
//    
//}
//
//$product->loadProduct($productid);
//

$product = $this->getObject('product', 'unesco_oer');
$product->LoadProduct($chosenid);
?>





<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>UNESCO</title>
        <link href="style.css" rel="stylesheet" type="text/css">
        <!--[if IE]>
            <style type="text/css" media="screen">
            body {
    	behavior: url(csshover.htc);
            }
            </style>
        <![endif]-->

    </head>

    <body>

        <div class="mainContentHolder">
            <div class="subNavigation"></div>
              <div class="breadCrumb tenPixelLeftPadding">
                <a href="#" class="productBreadCrumbColor">
    <?php
    $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => $origional, "page" => '1a_tpl.php')));
    $abLink->link = 'UNESCO OER Products';
    $abLink->cssClass = "blueText noUnderline";
    echo $abLink->show();
    ?>
                
                
                
                </a> | 
                <a href="#" class="productBreadCrumbColor">
 <?php
    
    $abLink = new link($this->uri(array("action" => 'ViewProduct', "id" => $chosenid)));
    $abLink->link = $product->getTitle();
    $abLink->cssClass = "blueText noUnderline";
    echo $abLink->show();
  ?>
                
              </a> |
                Compare Selected
            </div>
                
            <div class="wideTopContentHolderDiv">

                <div class="topHeadingDiv">
                    <div class="productsBackgroundColor twelvePixelLeftPadding tenPixelTopPadding">
                        <div class="leftTopImage"><img src="<?php echo $product->getThumbnailPath()?>" width="45" height="49" ></div>
                        <div class="rightTopContentAndLinks">
                            <h2 class="blueText">
<?php
   echo $product->getTitle();
  
?>

                            </h2>

                            <div class="listTopLinks">
                                <div class="productLinksViewDiv">
                                    <img src="skins/unesco_oer/images/icon-product.png" alt="Bookmark" width="18" height="18"class="smallLisitngIcons">
 <?php
                        $abLink = new link($this->uri(array("action" => 'ViewProduct', "id" => $chosenid)));
                        $abLink->link = 'View Full Product';
                        $abLink->cssClass = "textNextToTheListingIconDiv";
                        echo $abLink->show();
?>
                                </div>
                            </div>



                        </div>
                    </div>
                </div>
            </div>

            <!-- Left Wide column DIv -->
            <div class="greyHorizontalLine"></div>
            <div class="slide fiftenPixelPaddingLeft">
                <div class="Arrows"><a href="#"><img src="skins/unesco_oer/images/large-icon-backwards.png" width="36" height="36"></a></div>




                <!--                                                
                                                                
                                                                <div class="slideBoxProduct">
							<div class="leftTopImage"><img src="images/compare-selected-placeholder.jpg" width="27" height="29" ></div>
                                                <h4 class="blueText">Model Curricula for Journalism</h4>
                                                <img src="images/icon-product.png" alt="Bookmark" width="18" height="18"class="smallLisitngIcons">
                                                <div class="textNextToTheListingIconDiv">
                            	<a href="#" class="productsLink">Full view of product</a>
                            	</div>
						</div>
						-->


                <?php
                $product = $this->getObject('product', 'unesco_oer');
                $product->loadProduct($selectedproducts[0]);
                $origionalproduct = $product->getParentID();
                $product->LoadProduct($origionalproduct);
                
                if ($origionalproduct == $chosenid){
                    $class =  'class="slideBoxProduct"';
                } else $class = 'class="slideBoxAdaptation"';
                
                 if ($this->objUser->isLoggedIn()) {
                    $uri = $this->uri(array('action' => 'adaptProduct', 'productID' => $origionalproduct, 'nextAction' => 'ViewProduct', 'cancelAction' => 'ViewProduct', 'cancelParams' => "id=$origionalproduct"));
                    $adaptLink = new link($uri);
                    $adaptLink->cssClass = "adaptationLinks";
                    $linkText = 'Make Adaptation';
                    $adaptLink->link = $linkText;
                }


                echo $content = '<div '. $class .'>
							<div class="leftTopImage"><img src="'. $product->getThumbnailPath() .'" width="27" height="29" ></div>
                                <h4><a href="/unesco_oer/index.php?module=unesco_oer&action=Comparechosen&id='.$moduleid.'&productid='.$productid.'&chosenid='.$origionalproduct.'" id="treelink"  class="adaptationListingLink">' . $product->getTitle() . '
                   
                                  </a></h4>
                                <img src="skins/unesco_oer/images/small-icon-make-adaptation.png" alt="Adaptation" width="18" height="18"class="smallLisitngIcons">
                                <div class="textNextToTheListingIconDiv">'. $adaptLink->show() .'</div>
						</div> ';

//                                                $content= '';
//                                                
//                                                 $products = $this->objDbProducts->getadapted($productid);
//                                                     foreach ($products as $product){
//                                                         
//                                                        $content .= '<div class="slideBoxAdaptation">
//							<div class="leftTopImage"><img src="images/compare-selected-placeholder.jpg" width="27" height="29" ></div>
//                             <h4 class="blueText">' .$product['title'] . '
//                             
//                                </h4>
//                             <img src="images/small-icon-make-adaptation.png" alt="Adaptation" width="18" height="18"class="smallLisitngIcons">
//                                <div class="textNextToTheListingIconDiv"><a href="#" class="adaptationLinks">make adaptation using this product</a></div>
//						</div> ' ;
//                                                         
//                                       
//                                                     }
//                                                echo $content;
//                                                 
                $content = '';
//                                                
                $product = $this->getObject('product', 'unesco_oer');
                foreach ($selectedproducts as $selectedproduct) {
                    
                     if ($selectedproduct == $chosenid){
                    $class =  'class="slideBoxProduct"';
                } else $class = 'class="slideBoxAdaptation"';

                    $product->loadProduct($selectedproduct);
                    //         $content = $product->getContentManager();
                    //  $existingContent = $content->getparent($moduleID);
                    
                       if ($this->objUser->isLoggedIn()) {
                        $uri = $this->uri(array('action' => 'adaptProduct', 'productID' => $selectedproduct, 'nextAction' => 'ViewProduct', 'cancelAction' => 'ViewProduct', 'cancelParams' => "id=$selectedproduct"));
                        $adaptLink = new link($uri);
                        $adaptLink->cssClass = "adaptationLinks";
                        $linkText = 'Make Adaptation';
                        $adaptLink->link = $linkText;
                    }


                    $content .= '<div '. $class .'>
							<div class="leftTopImage"><img src="'. $product->getThumbnailPath() .'" width="27" height="29" ></div>
                            <h4><a href="/unesco_oer/index.php?module=unesco_oer&action=Comparechosen&id='. $moduleid .'&productid='.$productid.'&chosenid='.$selectedproduct.'" id="treelink"  class="adaptationListingLink">' . $product->getTitle() . ' 
                   
                                  </a></h4>
                            <img src="skins/unesco_oer/images/small-icon-make-adaptation.png" alt="Adaptation" width="18" height="18"class="smallLisitngIcons">
                               <div class="textNextToTheListingIconDiv">'. $adaptLink->show().'</div>						</div> ';
                }
                echo $content;
                ?>




                <!--						
						<div class="slideBoxAdaptation">
							<div class="leftTopImage"><img src="images/compare-selected-placeholder.jpg" width="27" height="29" ></div>
                                                <h4><a href="#" class="adaptationListingLink">Model Curricula for Journalism</a></h4>
                                                <img src="images/small-icon-make-adaptation.png" alt="Adaptation" width="18" height="18"class="smallLisitngIcons">
                                                <div class="textNextToTheListingIconDiv"><a href="#" class="adaptationLinks">make adaptation</a></div>
						</div>
                                                                
                                                                
						<div class="slideBoxProduct">
							<div class="leftTopImage"><img src="images/compare-selected-placeholder.jpg" width="27" height="29" ></div>
                                             <h4 class="blueText">Model Curricula for Journalism</h4>
                                             <img src="images/small-icon-make-adaptation.png" alt="Adaptation" width="18" height="18"class="smallLisitngIcons">
                                                <div class="textNextToTheListingIconDiv"><a href="#" class="adaptationLinks">make adaptation using this product</a></div>
						</div>-->

                <!--                        <div class="slideBoxProduct">
							<div class="leftTopImage"><img src="images/compare-selected-placeholder.jpg" width="27" height="29" ></div>
                                          <h4 class="blueText">Model Curricula for Journalism</h4><br>
						</div>-->

                <div class="Arrows rightArrowPadding"><a href="#"><img src="skins/unesco_oer/images/large-icon-forwards.png" width="36" height="36"></a></div>
            </div>




            <div class="LeftWiderColumnDiv">

                <div class="pageBreadCrumb">

                </div>


                <div class ="Root">
                    
                    
<?php

       $product = $this->getObject('product');
            $product->loadProduct($chosenid);
            $content = $product->getContentManager();
            
      
            $temp = $this->objDbmodules->getmoduleparent($moduleid);
           $parentid =  $temp[0]['parentid'];

        if  ($parentid != 'NULL'){                                              //check if origional product was selected

                $modules = $this->objDbmodules->getmodulebyparent($parentid);


           }else{

               $modules = $this->objDbmodules->getmodulebyparent($moduleid);

           } 

           if ($parentid == ''){
                     $temp = $this->objDbcurricula->getCurriculaparent($moduleid);
                      $parentid =  $temp[0]['parentid'];
                     
                            if  ($parentid != NULL){                                              //check if origional product was selected
      
                                   $modules = $this->objDbcurricula->getCurriculabyparent($parentid);

    
                                  
                                                    }else{
                                                         
                                                           $modules = $this->objDbcurricula->getCurriculabyparent($moduleid);
                                                       
           }

        
           }
     
                           // var_dump($modules);
               
        $check = FAlSE;     
        foreach ( $modules as $module){  // run through modules till matching module and product are selected
           
            $existingContent = $content->getContentByContentID($module['id']);
                
        if   ($existingContent != FALSE){
            
            $check = TRUE;
            $existingContent = $content->getContentByContentID($module['id']);
             echo '<div class="heading2"><h2 class="greyText">' .$existingContent->getTitle() . ' </div> <br>';      
             echo  $existingContent->showReadOnlyInput();
            
                 }  

       } 
     
            if   ($check == FALSE){
              
                 if  ($parentid != null) // check if origional product was selected
                 {
                     $existingContent = $content->getContentByContentID($temp[0]['parentid']);
                 } else 
                 {
                      $existingContent = $content->getContentByContentID($moduleid);
                 }
                 
                 if ($existingContent != FALSE){
                     
                     echo '<div class="heading2"><h2 class="greyText">' .$existingContent->getTitle() . ' </div><br>';      
                     echo  $existingContent->showReadOnlyInput();
          
                 }
                 else 
                     echo '<div class="heading2"><h2 class="greyText">Module/Curriculum Not Available </div><br>'; 
        
            }



?>           






                </div>

            </div>

           

                <div class="rightColumnDivWide rightColumnPadding">
               
                     <div id="treeunesco">
                         
                    <?php
                    $product = $this->getObject('product');
                    $product->loadProduct($chosenid);
                    $content = $product->getContentManager();
                    echo $content->getContentTree(FALSE,FALSE,TRUE,TRUE,$selectedproducts);
                    
            //    echo $origionalproduct;
       
                    ?>
                         


                </div>

          
        </div>
        <!-- Footer-->

    </div>


<!--<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script> 
<script type="text/javascript" src="packages/unesco_oer/resources/jquery.validate.js"></script>
<script  >
   

    function moduleselect(origionalprod,moduleid){
        //$('.root').hide();
     
           
       $('.Root').load('index.php?module=unesco_oer&action=loadmodule&id=' + origionalprod + '&moduleid=' + moduleid);
  
        $('#treeunesco').load('index.php?module=unesco_oer&action=loadtree&id=' + origionalprod + '&moduleid=' + moduleid);
     
      //$.getScript('core_modules/tree/resources/TreeMenu.js');
  
        
       
       
        
        
       
    }
</script>-->
