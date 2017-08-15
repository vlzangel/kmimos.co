<?php
    echo '<h1>Mis Mascotas</h1><hr><br>';
    echo '<p align="justify">En esta sección podrás identificar a las mascotas de tu propiedad</p>';
    echo '<p align="justify">Si piensas contratar un servicio a través de Kmimos, es importante que las identifiques ya que solo las identificadas en tu perfil estarán amparadas por la cobertura de servicios veterinarios Kmimos.</p>';
    echo '<p align="justify">Si además te interesa formar parte de la familia de Cuidadores asociados a Kmimos, es importante también que tus mascotas estén identificadas. Muchas personas prefieren contratar a cuidadores que tengan perritos similares a los suyos, mientras que hay otros que buscan cuidadores que tengan mascotas de determinadas razas y tamaños.</p><br><hr>';

    $output = new PF_Frontend_Fields(
        array(
            'formtype' => 'mypets',
            'current_user' => $user_id,
            'detail_url' => $setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=mypet&id=',
            'count' => $pets['count'],
            'list' => $pets['list']
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