/* 
 * Javascript to support grades
 *
 * Written by Kevin Cyster kcyster@gmail.com
 * STarted on: March 17, 2012, 5:59 pm
 *
 * The following parameters need to be set in the
 * PHP code for this to work:
 *
 * @todo
 *   List your parameters here so you won't forget to add them
 *
 */

/**
 *
 * Put your jQuery code inside this function.
 *
 */
jQuery(function() {

    
    // Things to do on loading the page.
    jQuery(document).ready(function() {
      // Load some demo content into the middle dynamic area.
      jQuery("#middledynamic_area").load('packages/grades/resources/sample.txt');
    });

});