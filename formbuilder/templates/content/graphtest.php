<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>

<?php
/*! \file graphtest.php
 * \brief The template file is not being used. It exists so if someone want
 * to add graphical and statistical functionality to the submission results,
 * the base functions are here for you to start.
 */

$excanvasLibrary = '<script language="JavaScript" src="' . $this->getResourceUri('js/jqplot/excanvas', 'formbuilder') . '" type="text/javascript"></script>';
$jqplotLibrary = '<script language="JavaScript" src="' . $this->getResourceUri('js/jqplot/jquery.jqplot.js', 'formbuilder') . '" type="text/javascript"></script>';
$jqplotCSS = '<link rel="stylesheet" type="text/css" href="' . $this->getResourceUri('js/jqplot/jquery.jqplot.css', 'formbuilder') . '"';
$jqplotBarGraphLibrary = '<script language="JavaScript" src="' . $this->getResourceUri('js/jqplot/jqplot.barRenderer.js', 'formbuilder') . '" type="text/javascript"></script>';
$jqplotAxisLibrary = '<script language="JavaScript" src="' . $this->getResourceUri('js/jqplot/jqplot.categoryAxisRenderer.min.js', 'formbuilder') . '" type="text/javascript"></script>';
$jqplotPieGraphLibrary = '<script language="JavaScript" src="' . $this->getResourceUri('js/jqplot/jqplot.pieRenderer.js', 'formbuilder') . '" type="text/javascript"></script>';
$jqplotPntLabelsLibrary = '<script language="JavaScript" src="' . $this->getResourceUri('js/jqplot/jqplot.pointLabels.js', 'formbuilder') . '" type="text/javascript"></script>';

//[If browser is IE]; this library needs to be included to jqplot to work
$this->appendArrayVar('headerParams', $excanvasLibrary);
///[End IF]
$this->appendArrayVar('headerParams', $jqplotLibrary);
$this->appendArrayVar('headerParams', $jqplotCSS);
$this->appendArrayVar('headerParams', $jqplotBarGraphLibrary);
$this->appendArrayVar('headerParams', $jqplotAxisLibrary);
$this->appendArrayVar('headerParams', $jqplotPieGraphLibrary);
$this->appendArrayVar('headerParams', $jqplotPntLabelsLibrary);
?>

<!--<div id="chartdiv" style="height:400px;width:700px; "></div>


<div id="chart2" style="height:400px;width:700px;" ></div>-->

<div id="tabs">
    <ul>
        <li><a href="#tabs-1">List All Submitted Results</a></li>
        <li><a href="#tabs-2">List Final Submitted Results</a></li>
        <li><a href="#tabs-3">View Multiple Submitted Results</a></li>
        <li><a href="#tabs-4">View Results Graphically</a></li>
    </ul>
    <div id="tabs-1">
        <p>Proin elit arcu, rutrum commodo, vehicula tempus, commodo a, risus. Curabitur nec arcu. Donec sollicitudin mi sit amet mauris. Nam elementum quam ullamcorper ante. Etiam aliquet massa et lorem. Mauris dapibus lacus auctor risus. Aenean tempor ullamcorper leo. Vivamus sed magna quis ligula eleifend adipiscing. Duis orci. Aliquam sodales tortor vitae ipsum. Aliquam nulla. Duis aliquam molestie erat. Ut et mauris vel pede varius sollicitudin. Sed ut dolor nec orci tincidunt interdum. Phasellus ipsum. Nunc tristique tempus lectus.</p>
    </div>
    <div id="tabs-2">
        <div id="chartdiv1" style="height:400px;width:700px; "></div>
        <p>Morbi tincidunt, dui sit amet facilisis feugiat, odio metus gravida ante, ut pharetra massa metus id nunc. Duis scelerisque molestie turpis. Sed fringilla, massa eget luctus malesuada, metus eros molestie lectus, ut tempus eros massa ut dolor. Aenean aliquet fringilla sem. Suspendisse sed ligula in ligula suscipit aliquam. Praesent in eros vestibulum mi adipiscing adipiscing. Morbi facilisis. Curabitur ornare consequat nunc. Aenean vel metus. Ut posuere viverra nulla. Aliquam erat volutpat. Pellentesque convallis. Maecenas feugiat, tellus pellentesque pretium posuere, felis lorem euismod felis, eu ornare leo nisi vel felis. Mauris consectetur tortor et purus.</p>
    </div>
    <div id="tabs-3">
        <div id="chart1" style="height:400px;width:700px;" ></div>
        <p>Mauris eleifend est et turpis. Duis id erat. Suspendisse potenti. Aliquam vulputate, pede vel vehicula accumsan, mi neque rutrum erat, eu congue orci lorem eget lorem. Vestibulum non ante. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Fusce sodales. Quisque eu urna vel enim commodo pellentesque. Praesent eu risus hendrerit ligula tempus pretium. Curabitur lorem enim, pretium nec, feugiat nec, luctus a, lacus.</p>
        <p>Duis cursus. Maecenas ligula eros, blandit nec, pharetra at, semper at, magna. Nullam ac lacus. Nulla facilisi. Praesent viverra justo vitae neque. Praesent blandit adipiscing velit. Suspendisse potenti. Donec mattis, pede vel pharetra blandit, magna ligula faucibus eros, id euismod lacus dolor eget odio. Nam scelerisque. Donec non libero sed nulla mattis commodo. Ut sagittis. Donec nisi lectus, feugiat porttitor, tempor ac, tempor vitae, pede. Aenean vehicula velit eu tellus interdum rutrum. Maecenas commodo. Pellentesque nec elit. Fusce in lacus. Vivamus a libero vitae lectus hendrerit hendrerit.</p>
    </div>
    <div id="tabs-4">

        <p>Mauris eleifend est et turpis. Duis id erat. Suspendisse potenti. Aliquam vulputate, pede vel vehicula accumsan, mi neque rutrum erat, eu congue orci lorem eget lorem. Vestibulum non ante. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Fusce sodales. Quisque eu urna vel enim commodo pellentesque. Praesent eu risus hendrerit ligula tempus pretium. Curabitur lorem enim, pretium nec, feugiat nec, luctus a, lacus.</p>
        <p>Duis cursus. Maecenas ligula eros, blandit nec, pharetra at, semper at, magna. Nullam ac lacus. Nulla facilisi. Praesent viverra justo vitae neque. Praesent blandit adipiscing velit. Suspendisse potenti. Donec mattis, pede vel pharetra blandit, magna ligula faucibus eros, id euismod lacus dolor eget odio. Nam scelerisque. Donec non libero sed nulla mattis commodo. Ut sagittis. Donec nisi lectus, feugiat porttitor, tempor ac, tempor vitae, pede. Aenean vehicula velit eu tellus interdum rutrum. Maecenas commodo. Pellentesque nec elit. Fusce in lacus. Vivamus a libero vitae lectus hendrerit hendrerit.</p>
    </div>
</div>

<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<script type="text/javascript">
    jQuery(document).ready(function() {

        jQuery("#tabs").tabs();
        var line1 = [14, 32, 41, 44, 40, 47, 53, 67];
        //plot1 = jQuery.jqplot('chartdiv1', [line1], {
        //    title: 'Chart with Point Labels',
        //    seriesDefaults: {showMarker:false},
        //    axesDefaults:{pad:1.3}
        //});


        //var line1 = [1,4, 9, 16];
        //var line2 = [25, 12, 6, 3];
        //var line3 = [2, 7, 15, 30];
        //plot2 = jQuery.jqplot('chart1', [line1, line2, line3], {
        //    legend:{show:true, location:'ne', xoffset:55},
        //    title:'Bar Chart With Options',
        //   stackSeries: false,
        //   seriesDefaults:{
        //        renderer:jQuery.jqplot.BarRenderer,
        //
        //        rendererOptions:{barPadding: 8, barMargin: 20},
        //              pointLabels:{stackedValue: false}
        //
        //    },
        //    series:[
        //        {label:'Profits'},
        //        {label:'Expenses'},
        //        {label:'Sales'}
        //    ],
        //    axes:{
        //        xaxis:{
        //            renderer:jQuery.jqplot.CategoryAxisRenderer,
        //            ticks:['1st Qtr', '2nd Qtr', '3rd Qtr', '4th Qtr']
        //        },
        //        yaxis:{min:0}
        //    }
        //});
        //
        //jQuery('#tabs').bind('tabsshow', function(event, ui) {
        //  if (ui.index == 1 && plot1._drawCount == 0) {
        //    plot1.replot();
        //  }
        //  else if (ui.index == 2 && plot2._drawCount == 0) {
        //    plot2.replot();
        //  }
        //});

        //var line1 = [['frogs',3], ['buzzards',7], ['deer',2.5], ['turkeys',6], ['moles',5], ['ground hogs',4]];
        //plot2 = jQuery.jqplot('chart2', [line1], {
        //    title: 'Pie Chart with Legend and sliceMargin',
        //    seriesDefaults:{renderer:jQuery.jqplot.PieRenderer, rendererOptions:{sliceMargin:8}},
        //    legend:{show:true}
        //});

    });


</script>