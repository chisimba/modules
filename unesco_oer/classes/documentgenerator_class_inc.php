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

        //Front Page//
        $frontPage = "
            <br />
            <br />
            <br />
            <br />
            <br />
            <br />
            <h1 align='CENTER'>{$product->getTitle()}</h1>
            ";
        //Contents page//
        $contents = "
            <h1>Contents</h1>
            ";
        //Document content//
        $contentManager = $product->getContentManager();
        $outline = $this->generateContentsHTML($contentManager->getAllContents());
        $details = $this->generateModuleDetailsHTML($contentManager->getAllContents());

        $this->pdf->partWrite($frontPage);
//        $this->pdf->partWrite($contents);
        $this->pdf->partWrite($outline);
        $this->pdf->partWrite($details);
        return $this->pdf;
    }

    private function generateContentsHTML($contentObjects, $level = 1){
        $html = "";
        foreach ($contentObjects as $contentObject) {
            if (!$contentObject->isDeleted()){
//                $html .= "<h$level>{$contentObject->getTitle()}</h$level>"; //TODO move code to function below where it belongs
                $html .= $contentObject->printHTML($level);
                $html .= $this->generateContentsHTML($contentObject->getContents(), $level+1);
            }
        }

        return $html;
    }

    private function generateModuleDetailsHTML($contentObjects, $level = 1){
        $html = "";
        foreach ($contentObjects as $contentObject) {
            if (!$contentObject->isDeleted()){
//                $html .= "<h$level>{$contentObject->getTitle()}</h$level>"; //TODO move code to function below where it belongs
                if ($contentObject->getType()=="module") $html .= "<H2>{$contentObject->getTitle()}</H2>".$contentObject->showReadOnlyInput();
                $html .= $this->generateModuleDetailsHTML($contentObject->getContents(), $level+1);
            }
        }

        return $html;
    }

}
?>
