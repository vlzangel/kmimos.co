<?php
    global $wpdb;
    $wlabel=$_wlabel_user->wlabel;
    $WLresult=$_wlabel_user->wlabel_result;
?>

<div class="modules">

<?php
include_once(dirname(__FILE__).'/modules/booking.php');
//include_once(dirname(__FILE__).'/modules/detail.php');
//include_once(dirname(__FILE__).'/modules/client.php');
?>

</div>

<script type="text/javascript">
    jQuery(document).ready(function(e){
        jQuery('body>div:not(#panel)').each(function(e){
            //console.log(this);
            jQuery(this).remove();
        });
    });
</script>

