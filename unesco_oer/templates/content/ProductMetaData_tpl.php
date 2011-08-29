

<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script> 
  <script type="text/javascript" src="packages/unesco_oer/resources/jquery.validate.js"></script>
<script>
   


 $(document).ready(
        function()
        {
            
            $("#form_add_products_ui").validate();
       
       
        });




</script>
  <?php
        $homelink = new link('home');
        $homelink->href = $this->uri(array("action"=>"home"));
        $homelink->link = 'Home';

        $productNavCap = 'New Product';
        if ($product->isAdaptation()) {
            $homelink->href = "?module=unesco_oer&action=FilterProducts&adaptationstring=parent_id+is+not+null+and+deleted+%3D+0&page=2a_tpl.php";
            $homelink->link = 'Product Adaptations';
            $productID = $product->getIdentifier();
            if (empty ($productID)) {
                $productNavCap = 'New Adaptation';
            } else {
                $productNavCap = 'Edit Adaptation';
            }

        } else {
            if (!$product->isDeleted()) $productNavCap = 'Edit Product';
        }
  ?>
<div style="clear:both;"></div>
<div class="breadCrumb module">
    <div id='breadcrumb'>
        <ul>
            <li><?php echo $homelink->show(); ?></li>
            <li><?php echo $productNavCap; ?></li>
        </ul>
    </div>

</div>
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
$this->setLayoutTemplate('maincontent_layout_tpl.php');
echo $product->showMetaDataInput($this->getParam('nextAction'),$this->getParam('cancelAction'), $this->getParam('cancelParams'));
//var_dump($product->dummyValue);

?>
