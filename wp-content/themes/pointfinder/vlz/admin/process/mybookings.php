<?php
    $output = new PF_Frontend_Fields(
        array(
            'formtype' => 'mybookings',
            'current_user' => $user_id,
            'detail_url' => $setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=mybooking&id=',
            'count' => $bookings['count'],
            'list' => $bookings['list']
        )
    );
    echo $output->FieldOutput;
    echo '<script type="text/javascript">
                                (function($) {
                                    "use strict";
                                    '.$output->ScriptOutput.'
                                })(jQuery);</script>';
    unset($output);
?>