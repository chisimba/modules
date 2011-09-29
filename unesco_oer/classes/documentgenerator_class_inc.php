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



/**
 * Description of documentgenerator_class_inc
 *
 * @author manie
 */
class documentgenerator extends object {

    public  $pdf;

    public function init() {
        $this->pdf = $this->getObject('tcpdfwrapper', 'pdfmaker');
    }

    public function  showProductPDF($productID){
        $pdf = $this->getProductPDF($productID);
        return $pdf->show();
    }

    public function  getProductPDF($productID){
        $product = $this->getObject('product');
        $product->loadProduct($productID);
        $this->pdf->initWrite();
        $this->pdf->partWrite($product->getTitle());
        $this->pdf->partWrite($product->getDescription());
        return $this->pdf;
    }

}
?>
