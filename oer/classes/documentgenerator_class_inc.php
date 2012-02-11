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
 * @author Paul Mungai paulwando@gmail.com, manie
 *
 */
class documentgenerator extends object {

    public $pdf;
    private $dbproducts;

    public function init() {
        $this->pdf = $this->getObject('tcpdfwrapper', 'pdfmaker');
        $this->objViewAdaptation = $this->getObject("viewadaptation", "oer");
        $this->dbproducts = $this->getObject("dbproducts", "oer");
    }

    public function showProductPDF($productId, $prodType) {
        $this->pdf->initWrite();
        $prodData = "";
        if ($prodType == "adaptation") {
            $prodData = $this->objViewAdaptation->buildAdaptationForPrint($productId);
            
        }
        $this->pdf->partWrite($prodData);

        return $this->pdf->show();
    }
    /**
     * Function that generates docs into diff word formats i.e. .doc, .odt
     * @param <type> $productId
     * @param <type> $prodType
     * @param <type> $ext 
     */

    public function showProductWordFormats($productId, $prodType, $ext) {
        $this->pdf->initWrite();
        $prodData = "";
        if(empty($ext)){
            $ext = ".doc";
        }
        if ($prodType == "adaptation") {
            $prodData = $this->objViewAdaptation->buildAdaptationForPrint($productId);
            //Remove all images
            $prodData = preg_replace("/<img[^>]+\>/i", " ", $prodData);
        }
        //Form doc name
        $randNo = mt_rand(1000, 15000);
        $prodTitle = $randNo;
        $prodTitle = $this->dbproducts->getProductTitle($productId);
        if($prodTitle != Null && !empty($prodTitle)) {
            $prodTitle = $randNo."_".str_replace(" ", "_", $prodTitle);
        }

        $fpath = "/tmp/";
        $docPath = $fpath.$prodTitle.$ext;
        
        //Form the document
        $fp = fopen($docPath, 'w+');
        fwrite($fp, $prodData);
        fclose($fp);
    }
}
?>