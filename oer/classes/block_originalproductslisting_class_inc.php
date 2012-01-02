<?php

$this->loadClass('link', 'htmlelements');

/**
 * This class constructs a grid of the original products
 *
 * @author davidwaf
 */
class block_originalproductslisting extends object {

    //this references the db layer for getting products
    private $dbProducts;
   
    private $objUser;
    //for i18n
    private $objLanguage;

    public function init() {
        $this->loadClass("link", "htmlelements");
        
        $this->dbProducts = $this->getObject('dbproducts', 'oer');
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objUser = $this->getObject('user', 'security');
        
    }

    public function show() {
        $originalProducts = $this->dbProducts->getOriginalProducts();
        $link = new link($this->uri(array("action" => "newproduct")));
        $link->link = $this->objLanguage->languageText('mod_oer_newproduct', 'oer');

        $controlBand = '';
        if ($this->objUser->isLoggedIn()) {
            $controlBand.=
                    '<div id="originalproducts_controlband">'
                    . $link->show()
                    . '</div> ';
        }
        $startNewRow = TRUE;
        $count = 2;
        $table = $this->getObject('htmltable', 'htmlelements');
        foreach ($originalProducts as $originalProduct) {
            if ($startNewRow) {
                $startNewRow=FALSE;
                $table->startRow();
            }
            $thumbnail=$originalProduct['thumbnail'];
            if($thumbnail == NULL){
                $thumbnail='<img src="skins/oer/images/documentdefault.png" " width="79" height="101" align="bottom">';
            }
            $link=new link($this->uri(array("action"=>"vieworiginalproduct","id"=>$originalProduct['id'])));
            $link->link=$thumbnail.'<br/>'.$originalProduct['title'];
            $table->addCell($link->show());
            if($count >3){
                $table->endRow();
                 $startNewRow=TRUE;
                $count=1;
            }
            $count++;
        }
        return $controlBand . $table->show();
    }

}

?>
