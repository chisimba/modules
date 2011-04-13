<?php
/*
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the
 * Free Software Foundation, Inc.,
 * 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

/*This is a FEATURED PRODUCT UI
 *
 */


// set up html elements
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('link','htmlelements');
$objIcon = $this->newObject('geticon','htmlelements');
$table = $this->newObject('htmltable', 'htmlelements');

// setup and show heading
$header = new htmlheading();
$header->type = 2;
$header->str = "Select product to feature:";
echo $header->show();

// retrieve data from tbl_unesco_oer_feturedproducts
$products = $this->objDbProducts->getArray('select id,title,parent_id,puid from tbl_unesco_oer_products where parent_id is not null');

//create headings for table
$table->startHeaderRow();
$table->addHeadercell('PRODUCTS');
$table->addHeaderCell('TITLE');
$table->endHeaderRow();


//add data to tabel with links to create featured product
if (count($products) > 0){
    $i=1;
    foreach ($products as $product) {
        $objIcon->setIcon('select');
        $newAdaptationLink = new link($this->uri(array('action' => "createFeaturedAdaptation" , 'id' => $product['puid'])));
        $newAdaptationLink->link = $objIcon->show();
        $table->startRow();
        $table->addCell($i++.$newAdaptationLink->show());
        $table->addCell($product['title']);
        $table->endRow();
    }
}

//createform, add fields to it and display
$form_data = new form('featuredAdaptation_ui');
$form_data->addToForm($table->show());
echo $form_data->show();


?>
