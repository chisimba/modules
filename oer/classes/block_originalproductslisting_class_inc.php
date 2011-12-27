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
    //this constructs the grid
    private $table;
    private $objUser;
    //for i18n
    private $objLanguage;

    public function init() {
        $this->table = $this->getObject('htmltable', 'htmlelements');
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
        $this->table->startRow();

        $this->table->endRow();

        return $controlBand . $this->table->show();
    }

}

?>
