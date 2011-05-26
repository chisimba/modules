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

// set up html elements
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('link','htmlelements');
$objIcon = $this->newObject('geticon','htmlelements');
$table = $this->newObject('htmltable', 'htmlelements');

// setup and show heading
$header = new htmlheading();
$header->type = 2;
$header->str = "Select product to adapt:";
echo $header->show();

// retrieve data from tbl_unesco_oer_products
$products = $this->objDbProducts->
        getArray('select id,title,relation from tbl_unesco_oer_products');

//create headings for table
$table->startHeaderRow();
$table->addHeaderCell('ID');
$table->addHeaderCell('TITLE');
$table->addHeaderCell('PARENT ID');
$table->endHeaderRow();

//add data to tabel with links to create adaptations from it
if (count($products) > 0){
    foreach ($products as $product) {

        $objIcon->setIcon('edit');
        $uri = $this->uri(array('action' => "createProduct" ,
                                'id' => $product['id'],
                                'prevAction' => 'addData'));
        $newAdaptationLink = new link($uri);
        $newAdaptationLink->link = $objIcon->show();

        $table->startRow();
        $table->addCell($product['id'].$newAdaptationLink->show());
        $table->addCell($product['title']);
        $table->addCell($product['relation']);
        $table->endRow();
    }
}

//createform, add fields to it and display
$form_data = new form('newAdaptation_ui');
$form_data->addToForm($table->show());
echo $form_data->show();


?>
