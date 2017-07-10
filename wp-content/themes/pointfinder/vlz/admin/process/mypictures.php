<?php
    echo '<h1>Mi galería</h1><hr><br>';
    $output = new PF_Frontend_Fields(
        array(
            'formtype' => 'mypictures',
            'current_user' => $user_id,
            'detail_url' => $setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=mypicture&id=',
            'count' => $pictures['count'],
            'list' => $pictures['list']
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