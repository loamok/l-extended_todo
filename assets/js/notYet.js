
$(document).ready(function(){
    $('a[href="#"]').not('.noClick').not('#btn-close').click(function(e) {
        showAlert('info', 'Not implemented yet.', 'notyet-info');
        e.preventDefault();
    });
});


