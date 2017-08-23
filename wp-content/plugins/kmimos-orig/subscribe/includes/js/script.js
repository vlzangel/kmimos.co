//FORM
function form_subscribe(element){
    var base = jQuery(element).closest('.subscribe').data('subscribe');
    var url = base+'/subscribe/subscription.php';
    jQuery.post(url, jQuery(element).serialize(),function(data){
        console.log(data);
    });
    return false;
}