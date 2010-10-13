jQuery(document).ready(function() {
    jQuery("#existingQ").change(function() {
        goToPage(jQuery(this).val());
    })
});

var goToPage = function(page) {
    switch(page) {
        case 'calcQ':
            window.location.href = calqUrl;
            break;
        default:
            window.location.href = mcqUrl;
    }
}
