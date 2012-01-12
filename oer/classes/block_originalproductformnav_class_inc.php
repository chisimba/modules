<?php

/**
 * This creates a navigation for quick moving in between forms when creating
 * a new orginal product
 *
 * @author davidwaf
 */
class block_originalproductformnav extends object {

    function init() {
        
    }

    public function show() {
        $id = $this->configData;
        $objProductManager = $this->getObject('productmanager', 'oer');
        return $objProductManager->buildProductStepsNav($id);
    }

}

?>
