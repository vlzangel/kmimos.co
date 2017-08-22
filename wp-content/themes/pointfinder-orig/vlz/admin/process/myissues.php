<?php
    echo '<h1>Mis solicitudes para conocerme</h1><hr><br>';
    $output = new PF_Frontend_Fields(
        array(
            'formtype' => 'myissues',
            'current_user' => $user_id,
            'detail_url' => $setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myissue&id=',
            'count' => $issues['count'],
            'list' => $issues['list']
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