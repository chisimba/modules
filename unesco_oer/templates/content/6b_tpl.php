<?php

var_dump($productid);

$selectedproducts = explode(',',$productid);

//var_dump($selectedproducts);
echo $moduleid;

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
            <div class="wideTopContentHolderDiv">
            	
                <div class="topHeadingDiv">
                <div class="productsBackgroundColor twelvePixelLeftPadding tenPixelTopPadding">
                	<div class="leftTopImage"><img src="images/adapted-product-grid-institution-logo-placeholder.jpg" width="45" height="49" ></div>
            <div class="rightTopContentAndLinks">
                   	  <h2 class="blueText">
                              <?php
                           //   echo $product->getTitle();
                         //     echo $product->getParentID();
                              
                              ?>
                              
                          </h2>
                      	
                      <div class="listTopLinks">
                        <div class="productLinksViewDiv">
                            <img src="images/icon-product.png" alt="Bookmark" width="18" height="18"class="smallLisitngIcons">
                            <div class="textNextToTheListingIconDiv"><a href="#" class="productsLink">Full view of product</a></div>
                        </div>
              </div>
                        
                      

                  </div>
                </div>
                </div>
            </div>
            
        	<!-- Left Wide column DIv -->
           <div class="greyHorizontalLine"></div>
            <div class="slide fiftenPixelPaddingLeft">
						<div class="Arrows"><a href="#"><img src="images/large-icon-backwards.png" width="36" height="36"></a></div>
		  				
                                                
                                                
                                                
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
                                                
                                     echo    $content = '<div class="slideBoxProduct">
							<div class="leftTopImage"><img src="images/compare-selected-placeholder.jpg" width="27" height="29" ></div>
                                <h4><a href="#" onclick= javascript:moduleselect('. "'$origionalproduct'".','. "'$moduleid'".') class="adaptationListingLink">' .  $product->getTitle().'
                   
                                  </a></h4>
                                <img src="images/small-icon-make-adaptation.png" alt="Adaptation" width="18" height="18"class="smallLisitngIcons">
                                <div class="textNextToTheListingIconDiv"><a href="#" class="adaptationLinks">make adaptation</a></div>
						</div> ';
                                                
//                                                
//                                                
//                                                
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
                                foreach ($selectedproducts as $selectedproduct){                    
                                  
                                    $product->loadProduct($selectedproduct);
                            //         $content = $product->getContentManager();
                                 //  $existingContent = $content->getparent($moduleID);
                                    
                                  
                                   $content .= '<div class="slideBoxAdaptation">
							<div class="leftTopImage"><img src="images/compare-selected-placeholder.jpg" width="27" height="29" ></div>
                            <h4><a href="#" onclick= javascript:moduleselect('. "'$origionalproduct'".','. "'$existingContent'".') class="adaptationListingLink">' .  $product->getTitle().'
                   
                                  </a></h4>
                            <img src="images/small-icon-make-adaptation.png" alt="Adaptation" width="18" height="18"class="smallLisitngIcons">
                               <div class="textNextToTheListingIconDiv"><a href="#" class="adaptationLinks">make adaptation using this product</a></div>						</div> ' ;
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
												
			    <div class="Arrows rightArrowPadding"><a href="#"><img src="images/large-icon-forwards.png" width="36" height="36"></a></div>
				  </div>
            
            
            
            
            	<div class="LeftWiderColumnDiv">
              
                <div class="pageBreadCrumb">
                    
                </div>
                    
                    
                    <div class ="Root">
            <?php
                        $product = $this->getObject('product'); 
            $product->loadProduct($origionalproduct);
            $content = $product->getContentManager();
            $existingContent = $content->getContentByContentID($moduleid);
                 echo  $existingContent->showReadOnlyInput();
                        
                        
                        
             ?>           
                        
                        
                        
                        
                        
                        
                    </div>
                        
                </div>
           
           <div class="tree">
            
            <div class="rightColumnDivWide rightColumnPadding">
               <?php
                $product = $this->getObject('product'); 
                  $product->loadProduct($origionalproduct);
                 $content = $product->getContentManager();
                  echo  $content->getContentTree(FALSE,FALSE);
                 
               
               
               
               ?>
               
               
               
          
                
             
            </div>
            
           </div>
            
         </div>
        <!-- Footer-->
        
    </div>
</body>
</html>

<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script> 
  <script type="text/javascript" src="packages/unesco_oer/resources/jquery.validate.js"></script>
<script  >
   

function moduleselect(origionalprod,moduleid){
    //$('.root').hide();
    
    $('.Root').load('index.php?module=unesco_oer&action=loadmodule&id=' + origionalprod + '&moduleid=' + moduleid)
    

  
        
       
       
        
        
       
}
</script>
