jQuery(document).ready(function() {
    jQuery("#existingQ").change(function() {
        goToPage(jQuery(this).val());
    })
});

var goToPage = function(page) {
    if(page != '-') {
        switch(page) {
            case 'calcQ':
                window.location.href = calqUrl;
                break;
            case 'matchQ':
                window.location.href = matchingqUrl;
                break;
            case 'numericalQ':
                window.location.href = numericalqUrl;
                break;
            case 'shortansQ':
                window.location.href = shortanswerqUrl;
                break;
            default:
                window.location.href = mcqUrl;
        }
    }
}
