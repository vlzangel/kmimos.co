<?php
    echo '<h1>Mis Servicios</h1><hr>';
    $output = new PF_Frontend_Fields(
        array(
            'formtype' => 'myservices',
            'current_user' => $user_id,
            'detail_url' => $setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myservice&id=',
            'count' => $services['count'],
            'list' => $services['list']
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