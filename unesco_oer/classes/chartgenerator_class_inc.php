<?php
include 'packages/unesco_oer/resources/php-ofc-library/open-flash-chart.php';
if (!$GLOBALS['kewl_entry_point_run'])
    die("you cannot view directly");

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
 * Used to generate the data needed for reporting.
 * Sql queries are executed generating the data.
 * @author jcsepc002
 */
class chartgenerator extends object
{
    
    function drawPieChart($ProdOriginals, $ProdAdaptations)
    {

        $title = new title( 'Originals vs Adaptations' );
        $title->set_style('color: #FF0000; font-size: 14px');

        $d = array(
            new pie_value($ProdOriginals,  "Originals"),
            new pie_value($ProdAdaptations,  "Adaptations")

                   );

        $pie = new pie();
        $pie->alpha(0.5)
            ->add_animation( new pie_fade() )
            ->add_animation( new pie_bounce(5) )
            ->start_angle( 270 )
            ->start_angle( 0 )
            ->tooltip( '#percent#' )
            ->colours(array("#d01f3c","#356aa0","#C79810"));

        $pie->set_values( $d );

        $chart = new open_flash_chart();
        $chart->set_title( $title );
        $chart->add_element( $pie );
        $chart->set_bg_colour('#fff3b0');
    
        return $chart->toPrettyString();
    }

    function drawVerticleBarChart($ChartName,$data,$filter)
    {
        $title = new title( $ChartName );
        $title->set_style( '{color: #FF0000; font-size: 14px}' );

        //$data=array();
        $ArrayCount = sizeof($data);
        $NewArrayData = array();
        $NewArrayLang = array();

        for( $i=0; $i!=$ArrayCount; $i++){
            $NewArrayData[] = intval($data[$i]["count"]);
            $NewArrayLang[] = $data[$i][$filter];
        }

        $bar = new bar_filled( '#E2D66A', '#577261' );
        $bar->set_values($NewArrayData);

        $chart = new open_flash_chart();
        $chart->set_title($title);
        $chart->add_element($bar);
        $chart->set_bg_colour('#fff3b0');
        $xlabel = new x_axis();
        $xlabel->set_labels_from_array($NewArrayLang);
        $chart->set_x_axis($xlabel);

        return $chart->toPrettyString();
    }

    function drawHorizontalBarChart($ChartName,$data,$filter)
    {
        $title = new title($ChartName);
        $title->set_style( '{color: #FF0000; font-size: 14px}' );
        
        $ArrayCount = sizeof($data);
        $NewArrayData = array();
        $NewArrayLang = array();
        $NewArrayCountry = array();

        $objDbreporting = $this->getObject('dbreporting');
   
        $hbar = new hbar( '#86BBEF' );

        for( $i=0; $i!=$ArrayCount; $i++){
            $NewArrayData[] = intval($data[$i]["count"]);
            $datapoint = $NewArrayData[$i];
            $hbar->append_value( new hbar_value(0,$datapoint));
            $NewArrayLang[] = $data[$i][$filter];
        }

        for( $x=$ArrayCount-1; $x>=0; $x--){
            $countrycode = $NewArrayLang[$x];
            $NewArrayCountry[] = $objDbreporting->getCountryName($countrycode);
        }
        
        $chart = new open_flash_chart();
        $chart->set_title( $title );
        $chart->add_element( $hbar );
        $chart->set_bg_colour('#fff3b0');
             

        $y = new y_axis();
        $y->set_offset(true);
        $y->set_labels($NewArrayCountry);
        $chart->add_y_axis( $y );
        

        return $chart->toPrettyString();        
    }

function drawHorizontalBarChart1($ChartName,$data,$filter)
    {
        $title = new title($ChartName);
        $title->set_style( '{color: #FF0000; font-size: 14px}' );

        $ArrayCount = sizeof($data);
        $NewArrayData = array();
        $NewArrayLang = array();
        $NewArrayCountry = array();

        $objDbreporting = $this->getObject('dbreporting');

        $hbar = new hbar( '#86BBEF' );

        for( $i=0; $i!=$ArrayCount; $i++){
            $NewArrayData[] = intval($data[$i]["count"]);
            $datapoint = $NewArrayData[$i];
            $hbar->append_value( new hbar_value(0,$datapoint));
            $NewArrayLang[] = $data[$i][$filter];
        }

        for( $x=$ArrayCount-1; $x>=0; $x--){
            $NewArrayCountry[] = $NewArrayLang[$x];
        }

        $chart = new open_flash_chart();
        $chart->set_title( $title );
        $chart->add_element( $hbar );
        $chart->set_bg_colour('#fff3b0');


        $y = new y_axis();
        $y->set_offset(true);
        $y->set_labels($NewArrayCountry);
        $chart->add_y_axis( $y );

        return $chart->toPrettyString();
    }
    
}



?>