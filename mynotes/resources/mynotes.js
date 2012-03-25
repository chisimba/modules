/* 
 * Javascript to support mynotes
 *
 * Written by Derek Keats derek@localhost.local
 * STarted on: March 16, 2012, 7:33 am
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
      jQuery("#middledynamic_area").load('packages/mynotes/resources/sample.txt');
    });

});