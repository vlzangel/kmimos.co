<?php

    $formaction = 'pfpets_view_list';
    $noncefield = wp_create_nonce($formaction);
    $buttonid = 'pf-ajax-pets-view-list';
    $buttontext = esc_html__('VER MIS MASCOTAS','pointfindert2d');
    $this->ScriptOutput = 'jQuery("#pfuaprofileform").prop("action","?ua=mypets");';
?>