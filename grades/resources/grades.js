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
        
    });

    jQuery('#addgradelink').click(function() {
       jQuery('#gradesformdiv').toggle();
       jQuery('#addgradesdiv').toggle();
       if (jQuery('#gradestablediv').length > 0)
       {
           jQuery('#gradestablediv').toggle();
       }
    });
    
    jQuery('#cancel_grade').live('click', function() {
       
       jQuery('#gradesformdiv').toggle();
       jQuery('#addgradesdiv').toggle();
       if (jQuery('#gradestablediv').length > 0)
       {
           jQuery('#gradestablediv').toggle();
       }
    });
    
    jQuery('#save_grade').live('click', function() {
       if (jQuery('#input_grade_id').val() == '')
       {
           alert (no_grade);
           return false;
       }
       else
       {
           jQuery('#form_grade').submit();
       }
    });

    jQuery('#addclasslink').click(function() {
       jQuery('#classesformdiv').toggle();
       jQuery('#addclassesdiv').toggle();
       if (jQuery('#classestablediv').length > 0)
       {
           jQuery('#classestablediv').toggle();
       }
    });
    
    jQuery('#cancel_class').live('click', function() {
       
       jQuery('#classesformdiv').toggle();
       jQuery('#addclassesdiv').toggle();
       if (jQuery('#classestablediv').length > 0)
       {
           jQuery('#classestablediv').toggle();
       }
    });
    
    jQuery('#save_class').live('click', function() {
       if (jQuery('#input_class_id').val() == '')
       {
           alert (no_class);
           return false;
       }
       else
       {
           jQuery('#form_class').submit();
       }
    });

    jQuery('#addcontextlink').click(function() {
       jQuery('#contextsformdiv').toggle();
       jQuery('#addcontextsdiv').toggle();
       if (jQuery('#contextstablediv').length > 0)
       {
           jQuery('#contextstablediv').toggle();
       }
    });
    
    jQuery('#cancel_context').live('click', function() {
       
       jQuery('#contextsformdiv').toggle();
       jQuery('#addcontextsdiv').toggle();
       if (jQuery('#contextstablediv').length > 0)
       {
           jQuery('#contextstablediv').toggle();
       }
    });
    
    jQuery('#save_context').live('click', function() {
       if (jQuery('#input_context_id').val() == '')
       {
           alert (no_context);
           return false;
       }
       else
       {
           jQuery('#form_context').submit();
       }
    });

});